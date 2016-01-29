<?php
class UsersController extends AppController {
    
    public $helpers = array('Html', 'Form');
    public $uses = array('User', 'Role', 'UserCompany', 'Company', 'SecurityQuestions', 'RolesPermissions', 'RepresentingInterest', 'Masterdoc');
    var $components = array('Notification');
    
	var $paginate = array(
        'order' => array(
            'User.id' => 'asc'
        )
    );
    
	public function beforeFilter() {
		parent::beforeFilter();
	}

    /**
    * 
    *purpose : Redirect to login page from index 
    */
    public function index() {
        $this->redirect(array('action' => 'login'));
    }
    
    /**
    * 
    *purpose : Authenticate user and validation for login functionality 
    */
    public function login($succMsg = null) {
		$sessData = $this->Session->read('Auth');
		if(isset($sessData['User']))
			$this->redirect('/users/dashboard');
		if(isset($succMsg) && !empty($succMsg)) {
			$this->set('errorMessage', $succMsg);
		}
		
		$user = $this->Cookie->read('rememberMe');
		if ( empty($user) ) {
			$user = array('username' => '', 'password' => '', 'rememberMe' => 'false');
		}
		$this->set('title_for_layout', __('Sign in'));
		$this->set('user', $user);

        if ($this->request->is('post')) {
            if (isset($this->request->data['UserToken']['tokenId'])) {
                $this->set('token', $this->request->data['UserToken']['tokenId']);
            } else {
                $prestatus = $this->User->prelogin(trim($this->request->data['User']['username']), trim($this->request->data['User']['password']), $this->request->data['User']['rememberMe']);
                switch($prestatus) {
                    case Configure::read('INVALID_USER'):
                         $this->set('errorMessage',__('Username and password combination does not match our records'));
                         break;

                    case Configure::read('INACTIVE_COMPANY'):
                    case Configure::read('EXPIRED_COMPANY'):
                         $this->redirect(array('action' => 'inactive_company'));
                         break;

                    case Configure::read('INACTIVE_USER'):
                         $this->redirect(array('action' => 'inactive_user'));
                         break;
                } 
				$passForCookie = $this->request->data['User']['password'];
                if (is_array($prestatus)) {
                    //Security Purpose we are adding UserID & Creation TimeStamp in his password
                    if(isset($this->request->data['User']['password']) && isset($prestatus['User']['id']) && isset($prestatus['User']['created'])) {
                         $this->request->data['User']['password'] = $this->request->data['User']['password'].$prestatus['User']['id'].$prestatus['User']['created'];
                    }

                    if ($this->Auth->login()) {

                        //write session for roles dropdown for add and edit page and user object
                        $roleDd = $this->User->getAllRoles();
                        $this->Session->write('roles', $roleDd);
                        $this->Session->write('user', $prestatus);

                        $representingInterestDd = $this->User->getAllRepresentingInterest('all');
                        $this->Session->write('representingInterest', $representingInterestDd);
                        if(isset($this->params->query['emailUpdate'])){
                            $this->redirect(array('action' => 'updateEmailSuccess'));
                        }

                        $status = $this->User->login($prestatus, $this->request->data);

                        if (($status == Configure::read('DASHBOARD')) || ($status == Configure::read('WELCOME'))) {
                            //rememberMe
                            //did they select the remember me checkbox?
                            if ($this->request->data['User']['rememberMe'] == 1 && !$this->Cookie->read('rememberMe')) {
                                    // hash the user's password
                                    $this->request->data['User']['password'] = $passForCookie;
                                    $this->request->data['User']['rememberMe'] = true;
                                    // write the cookie
                                    $this->Cookie->write('rememberMe', $this->request->data['User'], false, Configure::read('COOKIE_LIFETIME'));
                                    // remove "remember me checkbox"
                                    unset($this->request->data['User']['rememberMe']);
                            } elseif ( $this->request->data['User']['rememberMe'] != 1 ) {
                                    // Delete the cookie if user un-check remember me 
                                    $this->Cookie->delete('rememberMe');
                            }
                        }

                        switch($status) {  
                            case Configure::read('EXPIRED_PASSWORD'):
                                $this->Session->destroy();
                                $token = $this->UserToken->generatToken($prestatus['User']['id'],Configure::read('TOKEN_EXPIRED_PASSWORD')); 
                                $this->redirect('/updateuserdata/'.$token);
                                break;

                            case Configure::read('WELCOME'):
                                $this->redirect(array('action' => 'welcome'));
                                break;

                            case Configure::read('DASHBOARD'):
                                $this->redirect(array('action' => 'dashboard'));
                                break;

                            case Configure::read('DEFAULT_CHANGE'):
                                $this->redirect(array('controller' => 'masterdocs', 'action' => 'defaultChangeControl'));
                                break;
                            case Configure::read('LOGGEDIN_REASSIGN_OWNER'):
                                $this->redirect(array('controller' => 'masterdocusers', 'action' => 'index'));
                                break;
                        }

                    } else {
                        $statusfail = $this->User->loginfail($prestatus);
                        switch($statusfail) {    
                            case Configure::read('NEW_USER_STATUS');
                                $this->set('errorMessage',__('Username and password combination does not match our records'));
                                break;

                            case Configure::read('LOCK_USER_LOGIN_STATUS') :
                                $this->set('errorMessage',__('You have attempted to login more than 5 times.  Your account has been locked for 30 minutes.</br>  You can contact your administrator to reset your account or wait until the lock expires and try logging in again.'));
                                break;

                            case Configure::read('USER_PASS_FAILED') :
                                $this->set('errorMessage',__('Username and password combination does not match our records'));
                                break;
                        }
                    }
                }
            }
        }
    }
    
    /**
    * 
    *purpose : If company free trial is expired 
    */
    public function inactive_company() {}

    /**
    * 
    *purpose : If user is deactive 
    */
    public function inactive_user() {}

	/**
    * 
    *purpose : Display welcome page for first time log in user 
    */
    public function welcome() {}
    
    /**
    * 
    *purpose : Redirection to homepage 
    */ 
    public function dashboard($msg=null) {
        $userLoggedIn = $this->Session->read('user');
		if(!empty($msg)) {
			$this->set('successMessage', __('Your password has been saved successfully.'));
		}
		$userData = $this->User->find('first', array('conditions' => array('User.id' => $userLoggedIn['User']['id']), 'fields' => array('User.image')));
		if (!$userData['User']['image']) {
            $profileImage = Configure::read('DEFAULT_IMAGE_NAME');
            $this->set('amazonS3ServerPath', '');
        } else {
            $profileImage = $userData['User']['image'];
            $this->set('amazonS3ServerPath', Configure::read('AWS_PROFILE_IMG_IMAGES_URL'));
            
        }
        $this->set('profileImage', $profileImage);
        $this->set('users', $userLoggedIn);
    }

    /**
    * 
    *purpose : To destroy user session 
    */
    public function logout() {
        if ($this->Auth->user()) {
			$this->Session->destroy();
			//$this->Cookie->delete('rememberMe');
            $this->redirect($this->Auth->logout());
        } else {
            $this->redirect(array('controller'=>'users','action' => 'index','home'));
        }
    }
    
    /**
    * 
    *purpose : To fetch users to display on user listing page 
    */
    public function view($msg=null) {
        
        $this->set('title_for_layout', __('All Users'));
        
        if($this->Session->read('userId') != null){
            $this->Session->delete('userId');
        }
        
		if(!empty($msg)) {
			$this->set('successMessage', __('Email notification for resetting password has been sent successfully to the user(s).'));
		}
		$sortImage = array('last_name' => Configure::read('DEFAULT_SORT_ARROW'), 'username' => Configure::read('DEFAULT_SORT_ARROW'), 'Role.name' => Configure::read('DEFAULT_SORT_ARROW'), 'UserCompany.status' => Configure::read('DEFAULT_SORT_ARROW'));
		if (!empty($this->params['named']['direction'])) {
			if ($this->params['named']['direction'] == 'asc') {
				$sortImage[$this->params['named']['sort']] = Configure::read('DEFAULT_SORT_ARROW_TOGGLE');
			} else {
				$sortImage[$this->params['named']['sort']] = Configure::read('DEFAULT_SORT_ARROW');
			}
		}
	    $this->set('sortImage', $sortImage);
		$dropArr = Configure::read('PAGINATION_DROPDOWN');
		$this->set('selectedOption', 50);
        if($this->request->is('post')){
		$this->Session->write('pageNumber', $this->request->data['User']['records_per_page']);
			$this->pageNumber = $this->request->data['User']['records_per_page'];
            $this->paginate['limit'] = $this->request->data['User']['records_per_page'];
        } elseif ($this->Session->read('pageNumber') != '') {
			$this->paginate['limit'] = $this->Session->read('pageNumber');
			$this->set('selectedOption', $this->Session->read('pageNumber'));
		} else {
            $this->paginate['limit'] = Configure::read('PAGINATION_LIMIT');
        }
        $this->set('padinationDd', Configure::read('PAGINATION_DROPDOWN'));
        $userSession = $this->Session->read('user');
        $this->set('users',$this->paginate('User', array('Company.id' => $userSession['UserCompany']['company_id'])));
		
		$this->set('confirm_message',__('Reset Password for these users?'));
		$this->set('confirm_message2',__('Please select the user to reset the password.'));
    }
    
    /**
    * 
    *purpose : To add the user data from add users page 
    */
    public function add() {
        
        $this->set('title_for_layout', __('Add New User'));
        
		//read session for roles dropdown on add page
        $this->set('roles',$this->Session->read('roles'));
        if ($this->request->is('post')) {
            $this->User->data = $this->request->data;
           if ($this->User->validates()) { 
                $this->User->create();
                $data = $this->request->data;
                if(array_key_exists('cancel', $data)) {
                    $this->redirect(array('action' => 'view'));
                } else {
                    if($result = $this->User->addUser($data)){
                        if ($result == Configure::read('FAIL_TO_ADD_IN_USERS_FOR_ADD') || $result == Configure::read('FAIL_TO_ADD_IN_USERCOMPANY_FOR_ADD')) {
                            $this->redirect('/companies/message/fail');
                        }
                        unset($this->request->data);
                        if (array_key_exists('new', $data)) {
                            $this->set('successMessage',__('User is Added Successfully')); 
                        } elseif (array_key_exists('ok', $data)) {
                            $this->redirect(array('action' => 'view'));
                        }
                    }
                }
            }
        }
    }
    
    /**
    * 
    *purpose : To edit the user
    *@param : user.id 
    */
    function edit() {
        
        $this->set('title_for_layout', __('Edit Users'));
        
        //check if edit from userlisting page or from personal info page
        $this->set('checkAction',$this->params->query);
        $this->set('roles', $this->Session->read('roles'));
        
        if($this->Session->read('userId') != null){
            $userData = $this->User->findById($this->Session->read('userId'));
        }
        
        if ($this->request->is('post')) {
            if(isset($this->request->data['User']['userid'])){
                //check different scenarios while changing the status of user fron active to incative to display toast
                $userStatus = $this->User->getUserStatus($this->request->data['User']['userid']);
                if (empty($userStatus)) {
                    $this->set('confirm_message1', Configure::read('CONFIRMATION_TEXT_CHANGE_STATUS_NULL'));
                } else {
                    $this->set('confirm_message2', Configure::read('CONFIRMATION_TEXT_CHANGE_STATUS_NOT_NULL'));
                }
                
                $userData = $this->User->findById($this->request->data['User']['userid']);
                $this->Session->write('userId', $this->request->data['User']['userid']);
            } else {
                if(isset($this->request->data['User']['username'])){
                  if($this->request->data['User']['username'] == $this->Session->read('user.User.username')){
                      unset($this->User->validate['username']['rule3']);
                  }
                }
            
                $this->User->data = $this->request->data;
                if ($this->User->validates()) {
                    if($this->Session->read('userId') != null){
                        if (empty($this->params->query)) {
                            $paramVar = Configure::read('SET_PARAM');
                        } else {
                            $paramVar = Configure::read('UNSET_PARAM');
                        }

                        $result = $this->User->editUser($userData, $this->request->data, $paramVar);
                        if ($result == Configure::read('FAIL_TO_ADD_IN_USERS_FOR_EDIT') || $result == Configure::read('FAIL_TO_ADD_IN_USERCOMPANY_FOR_EDIT')) {
                            $this->redirect('/companies/message/fail');
                        }
                        if ($result == Configure::read('SUCCESS_PERSONALINFO')) {
                            $this->redirect('/users/personalinfo/success');
                        } elseif($paramVar == Configure::read('SET_PARAM')){
                             $this->redirect(array('action' => 'view'));
                        } else {
                            $this->redirect('/users/personalinfo/');  
                        }
                    }
                }
            }
            
            
        }
        $this->set('user', $userData);
    }
	
    /**
    * 
    *purpose : To view and edit personal info
    *@param : user.id 
    */


    public function personalinfo ($succMsg = NULL) {
        $this->log("personal Info");
        $this->log($this->Cookie->read('rememberMe'));
        $userDataSess = $this->Session->read('user');
        if(isset($succMsg) && !empty($succMsg)){
            $this->set('successMessage', "A validation link has been sent to your new email address. Once your new email address is confirmed, your account will be updated and begin using it as your email address of record.");
        }
        $userData = $this->User->findById($userDataSess['User']['id']);
        $this->set('user',$userData);
    }
    
    /**
    * 
    *purpose : To view and edit profile picture
    *@param : user.id 
    */
    public function profilePic() {
        $userDataSess = $this->Session->read('user');
        $userData = $this->User->findById($this->Session->read('user.User.id'));
        if ($this->request->is('post')) {            
            $this->User->data = $this->request->data;
            if ($this->User->validates()) {
                if (!empty($this->User->data['User']['image'])) {
                    $result = $this->User->uploadProfilePic($this->request->data, $userData);
                    if ($result == Configure::read('FAIL_TO_ADD_IN_USERS_FOR_PROFILEPIC')) {
                    $this->redirect('/companies/message/fail');
                } else {
                    $this->redirect(array('action' => 'profilePic'));
                }
            

                }
            }
        }
        if(!$userData['User']['image']){
            $profileImage = Configure::read('DEFAULT_IMAGE_NAME');
            $this->set('amazonS3ServerPath', '');
        } else {
            $profileImage = $userData['User']['image'];
            $this->set('amazonS3ServerPath', Configure::read('AWS_PROFILE_IMG_IMAGES_URL'));
        }
        $this->set('user',$userData);
        $this->set('profileImage',$profileImage);
        $this->set('confirm_message',Configure::read('PIC_CONFIRMATION_MSG'));
    }
    
    /**
     * 
     * purpose : Delete profile picture
     * @param : Profile Picture name 
     */
    public function deleteprofilepic($profileImage) {
        $return = $this->User->deleteprofilepic($profileImage);        
        $this->redirect(array('action' => 'profilePic'));
    }	
	/**
	 * @name 			resetPassword 
	 * @description		Reset Pasword Functionality	         			
	 * @param	        null
	 * @return	        null	 
	 */
	public function resetPassword() {
		$this->autoRender = false;
		//pr($this->request->data['Users']);
		if ($this->request->is('post') && !empty($this->request->data['Users'])) {
			$result = $this->User->resetPassword($this->request->data);
			if($result == Configure::read('USER_RESET_PASSWORD_SUCCESS')) {
				$this->redirect('/users/view/success');	
			} else { 
				$this->redirect('/users/view');	
			}
		} else {
			$this->redirect('/users/view');	
		}
		
	}
	/**
	 * @name 			profileChangePassword 
	 * @description		Change Password Functionality	         			
	 * @param	        null
	 * @return	        null	 
	 */
	public function profileChangePassword() {
		$sessData = $this->Session->read('Auth');
		$secrets_question_data = $this->SecurityQuestions->find("list",array("fields"=>"question"));		
		$this->set('user', $sessData);
		$this->set('secrets_question_data', $secrets_question_data);

		if ($this->request->is('post') || isset($this->request->data['User'])) {
			$this->User->data = $this->request->data;
			$result = $this->User->profileChangePassword($this->request->data,$sessData);
			if($result == Configure::read('USER_PROFILE_CHANGE_PASSWORD_SUCCESS')) {
				$this->redirect('/users/dashboard/success');
			} else if($result == Configure::read('USER_VALIDATION_FAIL')) { 
				return false;
			}
		}
	}
	/**
	 * @name 			checkForPasswordExpiry 
	 * @description		Check for Password Expiry Functionality	         			
	 * @param	        null
	 * @return	        null	 
	 */
	public function checkForPasswordExpiry($userData) {
		//pr($userData['User']['last_password_updated_dt']); 
		//echo date(Configure::read('DB_DATE_FORMAT'));
		$expiry =  date(Configure::read('DB_DATE_FORMAT'), strtotime(Configure::read('PASSWORD_EXPIRY')));		
		if($expiry >= $userData['User']['last_password_updated_dt']){
			$this->Session->destroy();
			//Generate Token 
			$token = $this->UserToken->generatToken($userData['User']['id'],Configure::read('TOKEN_EXPIRED_PASSWORD')); 
			$this->redirect('/updateuserdata/'.$token);
			return false;
		} 
		return true;
	}
	
    /**
	 * @name 		changeEmail 
	 * @description		Validate token and save database changes	         			
	 * @param	        null
	 * @return	        null	 
	 */
    public function changeEmail($token = NULL){
        $result = $this->User->changeEmail($token);
        switch($result) {
            case Configure::read('FAIL_TO_UPDATE_EMAIL') :
                $this->redirect('/companies/message/fail');
                break;
            
            case Configure::read('INVALID_TOKEN');
                $this->Session->destroy();
                $this->redirect('companies/message/page_not_found');
                break;

            case Configure::read('EXPIRED_LINK') :
                $this->Session->destroy();
                $this->redirect(array('controller' => 'companies','action' => 'message','expired_link'));
                break;

            case Configure::read('ISSET_SESSION') :
                $this->redirect(array('action' => 'updateEmailSuccess'));
                break;
            
            case Configure::read('NOT_SET_SESSION') :
                $this->Session->destroy();
                $this->redirect('/users/login/?emailUpdate=true');
                break;
        }
    }
    
    /**
	 * @name 		updateEmailSuccess 
	 * @description		Static page for successfully changed email address	         			
	 * @param	        null
	 * @return	        null	 
	 */
    public function updateEmailSuccess(){}
    
    /**
	 * @name 		manageDelegation 
	 * @description		DDifine Delegation	         			
	 * @param	        null
	 * @return	        null	 
	 */
    public function manageDelegation($succMsg = NULL) {
        if(isset($succMsg) && !empty($succMsg)){
            $this->set('successMessage',__('Changes saved successfully!'));
        }
        //$userSessData = $this->Session->read('user');
        $this->set('confirm_message2',__('There is no delegate information to save.'));
        
        //fetch role ids for deal review permission
        $DealReviewRoleId = $this->RolesPermissions->find('list', array(
            'fields' => array('RolesPermissions.role_id'),
            'conditions' => array('RolesPermissions.company_id' => CakeSession::read('user.UserCompany.company_id'), 
            'RolesPermissions.permission_id' => Configure::read('REVIEW_DEALS_PERMISSIONS')),
        ));
        $userDataDdArr = array();
        //conditions to fetch user displayed in manage delegation dropdown
        if (!empty($DealReviewRoleId)) {
            $userDataDdArr = $this->User->UserDdCondition($DealReviewRoleId, 'manageDelegation');
        }
        
        
        $userData = $this->User->findById($this->Session->read('user.User.id'));
        
        if($userData['UserCompany']['delegation_start_dt'] != NULL && $userData['UserCompany']['delegation_end_dt'] != NULL){
            $userData['UserCompany']['delegation_start_dt'] = date(Configure::read('DATE_FORMAT'), strtotime($userData['UserCompany']['delegation_start_dt']));
            $userData['UserCompany']['delegation_end_dt'] = date(Configure::read('DATE_FORMAT'), strtotime($userData['UserCompany']['delegation_end_dt']));
        }
        $this->set('user',$userData);
        $this->set('confirm_message', Configure::read('REM_DEL_CONFIRMATION_MSG'));
        $this->set('userDataDdArr',$userDataDdArr);
        if ($this->request->is('post')) {
            if (array_key_exists('confirm', $this->request->data)) {
                $result = $this->User->removeDelegation();
                if($result == Configure::read('FAIL_TO_REMOVE_DELEGATION')) {
                    $this->redirect('/companies/message/fail');
                } else {
                    $this->redirect('/users/manageDelegation');
                }
            } else {
                $this->UserCompany->data = $this->request->data;
                if ($this->UserCompany->validates()) {
                    $result = $this->User->manageDelegation($this->request->data);
                    
                    switch($result) {
                        case Configure::read('FAIL_TO_MANAGE_DELEGATION') :
                            $this->redirect('/companies/message/fail');
                            break;

                        case Configure::read('SUCCESS_TO_MANAGE_DELEGATION');
                            $this->redirect('/users/manageDelegation/success');
                            break;

                        case Configure::read('NULL_FROM_DATE_DELEGATION'):
                            $this->UserCompany->invalidate('from', __('A date selection is required.', true));
                            break;
                        
                        case Configure::read('NULL_TO_DATE_DELEGATION'):
                            $this->UserCompany->invalidate('to', __('A date selection is required.', true));
                            break;
                        
                        case Configure::read('EARLIER_FROM_DATE_DELEGATION'):
                            $this->UserCompany->invalidate('from', __('Selected Date Earlier.', true));
                            break;
                        
                        case Configure::read('EARLIER_TO_DATE_DELEGATION'):
                            $this->UserCompany->invalidate('to', __('Selected Date Earlier.', true));
                            break;
                        
                        case Configure::read('EARLIER_TO_THAN FROM_DATE_DELEGATION'):
                            $this->UserCompany->invalidate('to', __('The "To" date cannot be earlier than the "From" date..', true));
                            break;
                    }
                }
            }    
        }
        if ($userData['UserCompany']['delegation_start_dt'] != NULL && $userData['UserCompany']['delegation_end_dt'] != NULL) {
            $userData['UserCompany']['delegation_start_dt'] = date(Configure::read('DATE_FORMAT'), strtotime($userData['UserCompany']['delegation_start_dt']));
            $userData['UserCompany']['delegation_end_dt'] = date(Configure::read('DATE_FORMAT'), strtotime($userData['UserCompany']['delegation_end_dt']));
        }
        $this->set('user',$userData);
    }
    
    public function viewUserReport() {
        if ($this->request->is('post')) {
            if (isset($this->request->data['UserId']['userid'])) {
                $userId = $this->request->data['UserId']['userid'] ;
            } else {
                $userId = $this->request->data['user']['userId'] ;
            }
            
            //fetch role ids for 'Create and maintain master documents'
            $DealReviewRoleId = $this->RolesPermissions->find('list', array(
                'fields' => array('RolesPermissions.role_id'),
                'conditions' => array('RolesPermissions.company_id' => CakeSession::read('user.UserCompany.company_id'), 
                'RolesPermissions.permission_id' => Configure::read('CREATE_MAINTAIN_MASTER_DOCUMENTS_PERMISSION')),
            ));
            
            //conditions to fetch user displayed in Reassign To dropdown
            $reassignToDdArr = array();
            if (!empty($DealReviewRoleId)) {
                $reassignToDdArr = $this->User->UserDdCondition($DealReviewRoleId, 'reassignTo', $userId);
            }
            
            $userData = $this->User->getUserNameById($userId);
            $decisionMakerData = $this->User->getdecisionMakerDataByUserId($userId);
            $opinionProviderData = $this->User->getopinionProviderDataByUserId($userId);
            $masterDocOwnerData = $this->Masterdoc->find('all', array(
                'conditions' => array('Masterdoc.owner' => $userId, 'SectionUser.act_as' => Configure::read('OWNER'))));
            
            $this->set('userData',$userData);
            $this->set('decisionMakerData',$decisionMakerData);
            $this->set('opinionProviderData',$opinionProviderData);
            $this->set('masterDocOwnerData',$masterDocOwnerData);
            $this->set('reassignToDdArr',$reassignToDdArr);
            
            if (isset($this->request->data['user']['userId'])) {
                $changeDecisionMakerReport = '';
                $changeOpinionProviderReport = '';
                if (!empty($this->request->data['checkBox']['decisionCheckBox'])) {
                    $changeDecisionMakerReport = $this->User->changeReport($this->request->data['checkBox']['decisionCheckBox'], $decisionMakerData, $userData, Configure::read('DECISION_MAKER'));
                }
                if (!empty($this->request->data['checkBox']['opinionCheckBox'])) {
                    $changeOpinionProviderReport = $this->User->changeReport($this->request->data['checkBox']['opinionCheckBox'], $opinionProviderData, $userData, Configure::read('OPINION_PROVIDER'));
                }
                if (!empty($this->request->data['masterDocOwnerCheckBox'])) {
                    $reassignMasterDocOwnerReport = $this->User->reassign($this->request->data, $reassignToDdArr, $masterDocOwnerData, $userData, 'MASTER_DOC_REASSIGN');
                }
                $this->redirect('/users/view');
            }            
            
        }
    }
    
}
?>
