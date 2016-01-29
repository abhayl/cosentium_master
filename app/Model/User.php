<?php 
// app/Model/User.php
App::uses('AuthComponent', 'Controller/Component');
App::import('Model', 'UserToken');
App::import('Model', 'EmailQueue');
App::import('Model', 'Masterdoc');
App::import('Model', 'SectionUser');
App::import('Model', 'Role');

class User extends AppModel {
	public $name = 'User';
	public $validate = array(
		'first_name' => array(
                    'rule1' => array('rule' => array('checkEmptyFirstName'), 'message' => 'You must enter a value.'),
                    'rule2' => array('rule' => '/^[a-zA-Z\']+$/i', 'message' => 'Entry can only contain alphabet letters.'),
                    'rule3' => array('rule' => array('maxLength', 20), 'message' => 'Please enter your first name max 20 character.'),
		),
		'last_name' => array(
                    'rule1' => array('rule' => array('checkEmptyLastName'), 'message' => 'You must enter a value.'),
                    'rule2' => array('rule' => '/^[a-zA-Z\']+$/i', 'message' => 'Entry can only contain alphabet letters.'),
                    'rule3' => array('rule' => array('maxLength', 20), 'message' => 'Please enter your last name max 20 character.'),
		),
		'role_id' => array(
                    'rule1' => array('rule' => array('checkRole'), 'message' => 'You must enter a value.'),
		),
		'username' => array(
                    'rule1' => array('rule' => array('checkEmptyUsername'), 'message' => 'You must enter a value.'),
                    'rule2' => array('rule'    => '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/', 'message' => 'Enter a properly formed email address.  For example john@company.com'),
                    'rule3' => array('rule' => array('checkEmail'),  'message' => 'Email address already in use.')
		),
		'password' => array(
                    'rule1' => array('rule' => array('checkEmptyPassword'), 'message' => 'You must enter a value.'),
                    'rule2' => array('rule' => '/\d/', 'message' => 'Entry must consist of at least 8 characters and include at least one lowercase letter, one uppercase letter, and one special character.'),
                    'rule3' => array('rule' => '/^(?=.*?[A-Z])(?=.*?[a-z])/', 'message' => 'Entry must consist of at least 8 characters and include at least one lowercase letter, one uppercase letter, and one special character.'),
                    'rule4' => array('rule' => '/^(?=.*?[\~\!\@\#\$\%\^\&\*\-\_\?])/', 'message' => 'Entry must consist of at least 8 characters and include at least one lowercase letter, one uppercase letter, and one special character.'),
					'rule5' => array('rule' => array('minLength', 8), 'message' => 'Entry must consist of at least 8 characters and include at least one lowercase letter, one uppercase letter, and one special character.'),
		),
		'new_password' => array(
                    'rule1' => array('rule' => array('checkEmptyNewPassword'), 'message' => 'You must enter a value.'),
                    'rule2' => array('rule' => array('checkNewPassword'), 'message' => '"New password" and "Verify password" entries must match'),
                ),
		'old_password' => array(
                    'rule1' => array('rule' => array('checkEmptyOldPassword'), 'message' => 'You must enter a value.'),                    
				),
		'security_question_id' => array(
                    'rule' => array('checkScretQuestion'),
                    'message' => 'You must enter a value.'
                ),
		'security_answer' => array(
                    'rule' => array('notEmpty'),
                    'message' => 'You must enter a value.'
		),
		'image' => array(
                    'rule1' => array('rule' => array('checkEmptyImage'), 'message' => 'You must select a file.'),
                    'rule2' => array('rule' => array('extension', array('gif', 'jpeg', 'png', 'jpg')),'message' => 'File type not supported.'),
                    'rule3' => array('rule' => array('fileSize', '<=', '2MB'),'message' => 'Selected file exceeds maximum allowable size.'),
                    
		)/*,
		'decisionCheckBox' => array(
                    'rule1' => array('rule' => array('checkRequestRemoval'), 'message' => 'You must enter a value.'),                    
                    
		)*/
        );        
     
        public $belongsTo=array (
            'UserCompany' => array(
                'classname' => 'UserCompany',
                'foreignKey' => false,
                'conditions' => 'UserCompany.user_id = User.id'
            ),
            'Role' => array (
                'classname' => 'Role',
                'foreignKey' => false,
                'conditions' => 'UserCompany.role_id = Role.id'
            ),
            'Company' => array (
                'classname' => 'Company',
                'foreignKey' => false,
                'conditions' => 'UserCompany.company_id = Company.id'
            ),
            'SecurityQuestions' => array (
                'classname' => 'SecurityQuestions',
                'foreignKey' => false,
                'conditions' => 'SecurityQuestions.id = User.security_question_id'
            )
        );
        
        /*public function checkRequestRemoval($data) {
            pr($data);exit;
            if(empty($data['first_name']))
                    return false;
            else 
                    return true;
        }*/
        
        public function checkEmptyFirstName($data) {
            if(empty($data['first_name']))
                    return false;
            else 
                    return true;
        }
        public function checkEmptyImage($data) {
            if(empty($data['image']['name'])){
                    return false;
            }
            return true;
        }
        public function checkEmptyLastName($data) {
            if(empty($data['last_name']))
                    return false;
            else 
                    return true;
        }
        public function checkEmptyPassword($data) {
            if(empty($data['password'])){
                return false;
            } 
            return true;
        }
        public function checkEmptyNewPassword($data) {
            if(empty($data['new_password']))
                    return false;
            else 
                    return true;
        }
		public function checkEmptyOldPassword($data) {
            if(empty($data['old_password']))
                    return false;
            else 
                    return true;
        }
        public function checkEmptyUsername($data) {
            if(empty($data['username'])){
                return false;
            }
            return true;
        }
        public function checkEmail($data){
            $cnt = $this->find('count', array('conditions' => array('User.username =' => $data['username'])));
            //echo "<br>".$cnt; exit;
            if($cnt != 0) {
                    return false;
            } else
                    return true;
	}
        
        /**
         * 
         * @param type $data
         * @return boolean
         */
	public function checkNewPassword($data){
            if($this->data['User']['password'] != $this->data['User']['new_password'])
                    return false;
            else 
                    return true;
	}
        
        
	public function checkScretQuestion($data){
            if(empty($data['security_question_id']) || $data['security_question_id'] == 1)
                    return false; 
            else 
                    return true;
	}
        
	public function checkRole($data) {
		foreach ($data as $key => $value) {                
			if ($value === '') {
				return FALSE;
			} else {
				continue;
			}
		}
		return TRUE;
	}	
	
	/**
	 * @name 			checkOldPassword 
	 * @description		checkOldPassword -- Change Password Page
	 * @parm	        $newPassword, $date
	 * @return	        True/False	 
	 */
	public function checkOldPassword($newPassword,$userData){
		if($userData['User']['password'] == $newPassword) {
			return true;
		}
		if(!empty($userData['User']['last_password_1']) && $userData['User']['last_password_1'] == $newPassword) {
			return true;
		}
		
		if(!empty($userData['User']['last_password_2']) && $userData['User']['last_password_2'] == $newPassword) {
			return true;
		}
		return false;
	}
	
	/**
	 * @name 			updateNewPassword 
	 * @description		updateNewPassword -- Change Password Page
	 * @parm	        $newPassword, $date
	 * @return	        NULL
	 */
	public function updateNewPassword($newPassword=null,$userData){
		$passData = array();
		if(empty($userData['User']['password'])) {
			//Updated Other fields			
			$passData =array(
				'id' => $userData['User']['id'],	
				'password' => $newPassword,
				'last_password_updated_dt' => date(Configure::read('DB_DATE_FORMAT'))				
			);
		}
		if(!empty($userData['User']['password']) && empty($userData['User']['last_password_1'])) {
			//Updated Other fields
			$passData =array(
				'id' => $userData['User']['id'],
				'last_password_1' =>  $userData['User']['password'],				
				'password' => $newPassword,
				'last_password_updated_dt' => date(Configure::read('DB_DATE_FORMAT'))				
			);
		}		
		if(!empty($userData['User']['password']) && !empty($userData['User']['last_password_1'])) {
			//Updated Other fields			
			$passData =array(
				'id' => $userData['User']['id'],
				'last_password_2' =>  $userData['User']['last_password_1'],
				'last_password_1' =>  $userData['User']['password'],				
				'password' => $newPassword,
				'last_password_updated_dt' => date(Configure::read('DB_DATE_FORMAT'))				
			);
		}		
		$this->save($passData,false); 
		return;		
	}
	
	/**
	 * @name 			saveData 
	 * @description		Save User Data         			
	 * @parm	        $userData Request Data 
	 * @return	        LastInsertId/false
	 */	
	public function saveData($fieldArr, $checkValidate = true, $id = null, $table = null){
        if ($id != NULL && $table == NULL) {
            $this->id = $id;
        } elseif($id != NULL && $table != NULL) {
            $this->$table->id = $id;
        }
        if($table != NULL){
            if ($this->$table->save($fieldArr, $checkValidate)) {
                if ($this->$table->getLastInsertID() != null) {
                    return $this->$table->getLastInsertID();
                } else {
                    return true;
                }
            }
        } else {
            if ($this->save($fieldArr, $checkValidate)) {
                if ($this->getLastInsertID() != null) {
                    return $this->getLastInsertID();
                } else {
                    return true;
                }
            }
        }
        return false;
        
    }
	/**
	 * @name 			Register
	 * @description		Free Trial         			
	 * @parm	        $userData Request Data 
	 * @return	        T/F
	 */
	public function register($userData) {
		$userData['Company']['phone'] = $userData['Company']['phone1'].$userData['Company']['phone2'].$userData['Company']['phone3'];
		if(!$this->validates()) { 
			if(!$this->Company->validates())
				return Configure::read('USER_VALIDATION_FAIL');	
			else
				return Configure::read('USER_VALIDATION_FAIL');
		}	
		if ($this->Company->validates() && $this->validates()) {
				$userData['Company']['expiry_date'] = date(Configure::read('DB_DATE_FORMAT'), strtotime("+ ".Configure::read('FREE_TRIAL_PERIOD_IN_DAYS')));	
				
				$newCompanyId = $this->Company->saveData($userData); 
				//Creating company prefix 
				$company_prefix = preg_replace('/[^A-Za-z0-9_\-]/', '',str_replace(" ", "_", strtolower($newCompanyId.'_'.trim($userData['Company']['company_name']))));
                //save company prefix
                $this->saveData(array('prefix' => $company_prefix), false, $newCompanyId, 'Company');

				$newUserId = $this->saveData($userData);
				
				$userData['UserCompany']['company_id'] = $newCompanyId;
				$userData['UserCompany']['user_id'] = $newUserId;						
				$userData['UserCompany']['role_id']	= Configure::read('DEFAULT_ROLE_ID');					
				
				$newUserCompanyId = $this->UserCompany->saveData($userData);
				
				//Generate Token
				$UserToken = new UserToken();	
				$token = $UserToken->generatToken($newUserId,Configure::read('TOKEN_REGISTRATION_OR_ADDUSER')); 
			
				// Send Email 	
				$userData['User']['link'] = 'updateuserdata/'.$token;
				$this->Role->setPermissions($userData['UserCompany']['company_id'], Configure::read('DEFAULT_COMPANY'), '', '');
                $this->Company->createDocTables($company_prefix);
				/*$Notification = new  NotificationComponent();
				$result = $Notification->sendMail(Configure::read('EMAIL_TEMP_REGISTRATION'), $userData);
				*/
				
				//Email Queue integration 
				$EmailQueueData['EmailQueue']['to_email'] = $userData['User']['username'];
				$EmailQueueData['EmailQueue']['user_id'] = $newUserId;
				$EmailQueueData['EmailQueue']['cc_user_id'] = $newUserId;
				$EmailQueueData['EmailQueue']['company_id'] = $newCompanyId;
				$emailName = $userData['User']['first_name']." ".$userData['User']['last_name'];
				$emailUsername = $userData['User']['username'];
				$emailLink = Configure::read('SITE_BASE_URL').$userData['User']['link'];
				$EmailQueueData['EmailQueue']['email_subject_line'] = Configure::read('EMAIL_REGISTRATION_SUBJECT');
				$EmailQueueData['EmailQueue']['email_body_text'] = "
$emailName,


Welcome to cosentium! When you first sign in, you will be asked to create a password as well as 
a security question/answer combination in the event you forget your password.

<strong>Your Username:</strong>$emailUsername 

You may click on the link below to sign in.
<strong>Sign in:</strong> $emailLink 

Again, welcome to Cosentium!

Cosentium Inc.
P.O. Box 163
Indianola, WA 98342
USA
www.cosentium.com
800.xxx.xxxx";
				
				$EmailQueue = new  EmailQueue();
				$newEmaiQueueId = $EmailQueue->insertEmailQueue($EmailQueueData);
				//Email Queue integration 
				return Configure::read('USER_REGISTER_SUCCESS');
			} else {
				return Configure::read('USER_VALIDATION_FAIL');
			}
	}
	/**
	 * @name 			forgotPassword
	 * @description		Forgot Password         			
	 * @parm	        $userPostData Request Data 
	 * @return	        T/F
	 */
	public function forgotPassword($userPostData) { 
		unset($this->validate['username']['rule3']);	
		if(!$this->validates()){
			return Configure::read('USER_VALIDATION_FAIL');
		}			
		$userData = $this->findByUsername($userPostData['User']['username']);
		//pr($userData);
			
		if (empty($userData) || empty($userData['User']['id']) || $userData['User']['access_status'] == Configure::read('NEW_USER_STATUS')) {
			//$this->redirect('forgotPassword/success');
			return Configure::read('USER_FORGOT_PASSWORD_SUCCESS');
		}
		//Generate Token
		$UserToken = new UserToken();		
		$token = $UserToken->generatToken($userData['User']['id'],Configure::read('TOKEN_FORGOT_PASSWORD'));			
		$userData['User']['link'] = 'setupforgot/'.$token;	
		
		//Send Email with Security Answer link
		/*$Notification = new  NotificationComponent();
		$result = $Notification->sendMail(Configure::read('EMAIL_TEMP_FORGOT_PASSWORD'), $userData);*/
		
		//Email Queue integration 
		$EmailQueueData['EmailQueue']['to_email'] = $userData['User']['username'];
		$EmailQueueData['EmailQueue']['user_id'] = $userData['User']['id'];
		$EmailQueueData['EmailQueue']['cc_user_id'] = $userData['User']['id'];
		$EmailQueueData['EmailQueue']['company_id'] = $userData['Company']['id'];
		$emailName = $userData['User']['first_name']." ".$userData['User']['last_name'];
		$emailUsername = $userData['User']['username'];
		$emailLink = Configure::read('SITE_BASE_URL').$userData['User']['link'];
		$EmailQueueData['EmailQueue']['email_subject_line'] = Configure::read('EMAIL_FORGOT_PASSWORD_SUBJECT');
		$EmailQueueData['EmailQueue']['email_body_text'] = "
$emailName,


You may click the link below which will sign you in automatically. After logging in, you be prompted to set a new password.


<strong>Your Username:</strong>$emailUsername

Sign in: $emailLink


Thank you,

Cosentium support
P.O. Box 163
Indianola, WA 98342
USA
www.cosentium.com
800.xxx.xxxx";
		
		$EmailQueue = new  EmailQueue();
		$newEmaiQueueId = $EmailQueue->insertEmailQueue($EmailQueueData);
		//Email Queue integration 
				
		
		return Configure::read('USER_FORGOT_PASSWORD_SUCCESS');
		
	}
	/**
	 * @name 			securityAnswer
	 * @description		Security Answer Page         			
	 * @parm	        $userPostData Request Data 
	 * @return	        T/F
	 */
	public function securityAnswer($userPostData,$userData) {		
		if(empty($userPostData['User']['security_answer'])){
				$this->invalidate('security_answer', __('You must enter a value.', true));
				 return  Configure::read('USER_SECURITY_ANSWER_INVALID');
		}			
		$security_answer = strtolower(trim($userPostData['User']['security_answer'])); //COS-26 - Eric comments on 04/09/2013
		$security_answer_enc = $this->encryptPassword($security_answer,$userData);
		
		$this->id = $userData['User']['id'];
		
		if($userData['User']['security_answer'] != $security_answer_enc) {
			//Max. Attempt Are done	
			if($userData['User']['unsuccess_security_counter'] == Configure::read('MAX_ANSWER_ATTTMPT')) { 
				//Updated Other fields
				unset($this->data['User']);
				$this->set(array(
					'id' => $userData['User']['id'],	
					'unsuccess_security_counter' => Configure::read('DEFAULT_COUNTER'),
					'access_status' => Configure::read('LOCK_USER_FORGOT_STATUS')					
				));
				$this->save(); 
				return Configure::read('USER_SECURITY_ANSWER_LOCK');
				//$this->redirect('message/lock_user_forgot');
			}
			//Updated Other fields
			unset($this->data['User']);
			$this->set(array(
				'id' => $userData['User']['id'],
				'unsuccess_security_counter' => $userData['User']['unsuccess_security_counter']+1,
				'last_unsuccess_security_access_dt' => date(Configure::read('DB_DATE_FORMAT'))
			));
			$this->save();
			$this->invalidate('security_answer', __('Security question answer does not match our records', true));
			return  Configure::read('USER_SECURITY_ANSWER_INVALID');
			
		} else { // Answer is correct 
			
			//Updated Other fields
			unset($this->data['User']);
			$this->set(array(
				'id' => $userData['User']['id'],
				'access_status' => Configure::read('FORGOT_PASS_STATUS')					
			));
			$this->save();
			return Configure::read('USER_SECURITY_ANSWER_SUCCESS');	
		}
		
	}
	/**
	 * @name 			changepassword
	 * @description		Changepassword By Token Page         			
	 * @parm	        $userPostData Request Data 
	 * @return	        T/F
	 */
	public function changepassword($userPostData,$userData) {
		//'Your new password must be one you have not used previously.'
		$pass = $this->encryptPassword($userPostData['User']['password'],$userData); 
		
		if($this->checkOldPassword($pass,$userData)) {
			$this->invalidate('password', __('Your new password must be one you have not used previously.', true));
			return Configure::read('USER_VALIDATION_FAIL');
		}
	
		if($this->validates()){
			
			$password_enc = $this->encryptPassword($userPostData['User']['password'],$userData);
			$security_answer = strtolower(trim($userPostData['User']['security_answer'])); //COS-26 - Eric comments on 04/09/2013
			$security_answer_enc = $this->encryptPassword($security_answer,$userData);
			
						
			$this->updateNewPassword($password_enc,$userData);
			
			//Updated Other fields
			unset($this->data['User']);
			$this->set(array(
				'id' => $userData['User']['id'],
				'security_question_id' => $userPostData['User']['security_question_id'],
				'security_answer' => $security_answer_enc,
				'access_status' => Configure::read('ACTIVE_USER_STATUS')					
			));
			$this->save(); 
			$UserToken = new UserToken();
			$UserToken->clearToken($userData['User']['id'],$userPostData['User']['token']);			
			return Configure::read('USER_CHANGE_PASSWORD_SUCCESS');
		} else {
			return Configure::read('USER_VALIDATION_FAIL');
		}
	}
	/**
	 * @name 			profileChangePassword
	 * @description		profileChangePassword Page         			
	 * @parm	        $userPostData Request Data 
	 * @return	        T/F
	 */
	public function profileChangePassword($userPostData,$sessData) {
		if($this->validates()){
			$userData = $this->findById($sessData['User']['id']);
			$old_pass_enc = $this->encryptPassword($userPostData['User']['old_password'],$userData);
			$new_pass_enc = $this->encryptPassword($userPostData['User']['password'],$userData);
			
			if($old_pass_enc != $userData['User']['password']) {
				$this->invalidate('old_password', __('Password does not match system record.', true));
				return Configure::read('USER_VALIDATION_FAIL');
			}
			if($this->checkOldPassword($new_pass_enc,$userData)) {
				$this->invalidate('password', __('Your new password must be one you have not used previously.', true));
				return Configure::read('USER_VALIDATION_FAIL');
			}
			$password_enc = $new_pass_enc;
			$security_answer = strtolower(trim($userPostData['User']['security_answer'])); //COS-26 - Eric comments on 04/09/2013
			$security_answer_enc = $this->encryptPassword($security_answer,$userData); 

			$this->updateNewPassword($password_enc,$userData);// Updated password
			
			//Updated Other fields
			unset($this->data['User']);
			$this->set(array(
				'id' => $userData['User']['id'],
				'security_question_id' => $userPostData['User']['security_question_id'],
				'security_answer' => $security_answer_enc					
			));
			$this->save();
			
			return Configure::read('USER_PROFILE_CHANGE_PASSWORD_SUCCESS');
			//$this->redirect('/users/personalinfo/success');
		} else { 
			return Configure::read('USER_VALIDATION_FAIL');
		}
	}
	/**
	 * @name 			resetPassword
	 * @description		Reset Password - System Admin Page
	 * @parm	        $userPostData Request Data 
	 * @return	        
	 */
	public function resetPassword($userPostData) {
		foreach($userPostData['Users'] as $key => $val){
			$userData = $this->findById($val);
			if(isset($userData['User']['id']) && !empty($userData['User']['id'])) {
				// Generate Token 
				$UserToken = new UserToken();
				$token = $UserToken->generatToken($val,Configure::read('TOKEN_RESET_PASSWORD')); 
				
				/* if client wants that user's password should get empty then uncomment this code
				$newPassword = NULL;
				$this->updateNewPassword($newPassword,$userData);
				*/
				// Send Reset Password Eamil
				$userData['User']['link'] = 'updateuserdata/'.$token;
				/*$Notification = new  NotificationComponent();				
				$result = $Notification->sendMail(Configure::read('EMAIL_TEMP_RESET_PASSWORD'), $userData);
				if(!$result) { 
					return Configure::read('USER_FAIL');
				}*/
				//Email Queue integration			
				$EmailQueueData['EmailQueue']['to_email'] = $userData['User']['username'];
				$EmailQueueData['EmailQueue']['user_id'] = $userData['User']['id'];
				$EmailQueueData['EmailQueue']['cc_user_id'] = $userData['User']['id'];
				$EmailQueueData['EmailQueue']['company_id'] = $userData['Company']['id'];
				$emailName = $userData['User']['first_name']." ".$userData['User']['last_name'];
				$emailUsername = $userData['User']['username'];
				$emailLink = Configure::read('SITE_BASE_URL').$userData['User']['link'];
				$EmailQueueData['EmailQueue']['email_subject_line'] = Configure::read('EMAIL_RESET_PASSWORD_SUBJECT');
				$EmailQueueData['EmailQueue']['email_body_text'] = "
$emailName,


Your administrator has reset your password.



Your username is $emailUsername 

Sign in: $emailLink


Thank you,

Cosentium support
P.O. Box 163
Indianola, WA 98342
USA
www.cosentium.com
800.xxx.xxxx";

				
				$EmailQueue = new  EmailQueue();
				$newEmaiQueueId = $EmailQueue->insertEmailQueue($EmailQueueData);
				//Email Queue integration 
			}
			
		}
		return Configure::read('USER_RESET_PASSWORD_SUCCESS');
	}
	/**
	 * @name 			encryptPassword
	 * @description		Encryption Password         			
	 * @parm	        $key, $userData which contains ID & Created date for encryption
	 * @return	        encrypted string
	 */
	public function encryptPassword($key,$userData) {
		 return AuthComponent::password($key.$userData['User']['id'].$userData['User']['created']);	
	}

	public function getAllRepresentingInterest(){
         $representingInterests = $this->query('Select * from representing_interests');
        $roleDd = array();
        foreach ($representingInterests as $representingInterest) {
                $representingInterestDd[$representingInterest['representing_interests']['id']]= $representingInterest['representing_interests']['name'];
        } 
        if($representingInterestDd){
            return $representingInterestDd;
        }else{
            return false;
        }
     }
	
     /**
	 * @name 			prelogin
	 * @description     will check all the condition before login
	 * @parm           username, password
	 * @return	    userdata
	 */
     public function prelogin($username, $password){
         if($this->validateUsernamePasswordForEmpty($username, $password)){
            $userData = $this->getUserByUsername($username);
            if (empty($userData)) {
                return Configure::read('INVALID_USER');
            }
            if ($userData) {
                if ( $userData['Company']['expiry_date'] < date(Configure::read('DB_DATE_FORMAT'))) {
                    return Configure::read('EXPIRED_COMPANY') ;
                } 
                if ($userData['Company']['status'] == Configure::read('INACTIVE_COMPANY_STATUS')) {
                    return Configure::read('INACTIVE_COMPANY');
                } 
                if($userData['UserCompany']['status'] == Configure::read('INACTIVE_USER_STATUS')) {
                    return Configure::read('INACTIVE_USER');
                }
                
                $str_time = date(Configure::read('DB_DATE_FORMAT'), strtotime(Configure::read('USER_LOCK_PERIOD_IN_MIN')));
                if ($userData['User']['last_unsuccess_access_dt'] <= $str_time && $userData['User']['access_status'] == Configure::read('LOCK_USER_LOGIN_STATUS')) {
                     $userData = $this->resetTimeStamp($userData);
                }
            }
            return $userData;
         }
     }

          
	/**
	 * @name 			login
	 * @description     will logged in the authenticated user and will check all the scenarios of redirection depending on the status on user and company
	 * @parm           userdata
	 * @return	    status
	 */
	public function login($userData, $requestData) {
         
        //authenticate and login the user to application
        if ($this->checkForPasswordExpiry($userData)) {
            return Configure::read('EXPIRED_PASSWORD');
        }
        
        //reset unsuccess access date and counter to its default value as user is successfully logged in
        $this->saveData(array(
            'last_unsuccess_access_dt' => NULL,
            'unsuccess_access_counter' => Configure::read('DEFAULT_COUNTER')
        ), true, $userData['User']['id']);
        
        if (isset($requestData['User']['tokenId'])) {
            $Masterdoc = new Masterdoc();
            $result = $Masterdoc->reassignRole($requestData['User']['tokenId']);
            if ($result == Configure::read('LOGGEDIN_REASSIGN_OWNER')) {
                return Configure::read('LOGGEDIN_REASSIGN_OWNER');
            } else {
                return Configure::read('DEFAULT_CHANGE');
            }
        }
        if ($userData['User']['last_access_dt'] == NULL) {
            $this->saveData(array(
            'last_access_dt' => date(Configure::read('DB_DATE_FORMAT'))
            ), true, $userData['User']['id']);
            return Configure::read('WELCOME');
        } else {
            $this->saveData(array(
            'last_access_dt' => date(Configure::read('DB_DATE_FORMAT'))
            ), true, $userData['User']['id']);
            return Configure::read('DASHBOARD');
        }
	}
     
     /**
	 * @name 			loginfail
	 * @description     will check all the scenarios of redirection depending on the status on user and company if user unable to logged in into the system
	 * @parm           userdata
	 * @return	    status
	 */
     public function loginfail($userData){
         switch ($userData['User']['access_status']) {
            case Configure::read('NEW_USER_STATUS') :
                    return Configure::read('NEW_USER_STATUS');
                    break ;

            case Configure::read('LOCK_USER_LOGIN_STATUS') :
                    return Configure::read('LOCK_USER_LOGIN_STATUS');
                    break;

            default:
                    $this->blockUserForFiveAttempt($userData);
                    return Configure::read('USER_PASS_FAILED'); 
                    break ;
            }
     }
     
     /**
	 * @name 			blockUserForFiveAttempt
	 * @description     user will be blocked if user tries to logged in with wrong password more than 5 times in 30 minutes
	 * @parm            userdata
	 * @return	    true/ false
	 */
     public function blockUserForFiveAttempt($userData){
        if ($userData['User']['unsuccess_access_counter'] == Configure::read('DEFAULT_COUNTER')) {
            $this->saveData(array(
                'last_unsuccess_access_dt' => date(Configure::read('DB_DATE_FORMAT')),
                'unsuccess_access_counter' => Configure::read('INITIAL_COUNTER')
            ), true, $userData['User']['id']);
        } elseif ($userData['User']['unsuccess_access_counter'] > Configure::read('DEFAULT_COUNTER') && $userData['User']['unsuccess_access_counter'] < Configure::read('MAX_ANSWER_ATTTMPT')) {
            $str_time = date(Configure::read('DB_DATE_FORMAT'), strtotime(Configure::read('USER_LOCK_PERIOD_IN_MIN')));
            if ($userData['User']['last_unsuccess_access_dt'] >= $str_time) {
                if ($userData['User']['unsuccess_access_counter'] == Configure::read('LAST_COUNTER')) {
                    $this->saveData(array(
                    'access_status' => Configure::read('LOCK_USER_LOGIN_STATUS')
                    ), true, $userData['User']['id']);
                }
                $this->saveData(array(
                    'unsuccess_access_counter' => $userData['User']['unsuccess_access_counter']+1
                    ), true, $userData['User']['id']);
            } else {
                $this->saveData(array(
                    'last_unsuccess_access_dt' => date(Configure::read('DB_DATE_FORMAT')),
                    'unsuccess_access_counter' => Configure::read('INITIAL_COUNTER')
                ), true, $userData['User']['id']); 
            }
        }
        return true;
    }
     
     /**
	 * @name 			validateUsernamePasswordForEmpty
	 * @description     validate username and password if username and pasword are empty
	 * @parm           username, password
	 * @return	    true/ false
	 */
     public function validateUsernamePasswordForEmpty($username, $password){
        if(empty($username) && empty($password)){
                $this->invalidate('username', __('You must enter a value.', true));
                $this->invalidate('password', __('You must enter a value.', true));
                return false;
        }
        if(empty($username)){
                $this->invalidate('username', __('You must enter a value.', true));
                return false;
        }
        if(empty($password)){
                $this->invalidate('password', __('You must enter a value.', true));
                return false;
        }
        return true;
    }
    
    /**
	 * @name 			getUserByUsername
	 * @description     fetch data from database using username
	 * @parm           username
	 * @return	    userData or false;
	 */
    public function getUserByUsername($userName){
        $userData = $this->findByUsername($userName);
        if($userData){
            return $userData;
        }else{
            return false;
        }
        
    }
    
    /**
	 * @name 			resetTimeStamp
	 * @description     reset all time stamp and counter if user logs in after 30 minute of lock
	 * @parm           userData
	 * @return	    userData or false;
	 */
    public function resetTimeStamp($userData){
        if($this->saveUserFields(array(
                'last_unsuccess_access_dt' => NULL,
                'unsuccess_access_counter' => Configure::read('DEFAULT_COUNTER'),
                'access_status' => Configure::read('ACTIVE_USER_STATUS')
                ), true, $userData['User']['id'])){
            $userData['User']['last_unsuccess_access_dt'] = NULL;
            $userData['User']['unsuccess_access_counter'] = Configure::read('DEFAULT_COUNTER');
            $userData['User']['access_status'] = Configure::read('ACTIVE_USER_STATUS');
            return $userData;
        }else{
            return false;
        }
    }
    
    /**
	 * @name 			checkForPasswordExpiry 
	 * @description		Check for Password Expiry Functionality	         			
	 * @param	        null
	 * @return	        true indicates that the password has expired	 
	 */
	public function checkForPasswordExpiry($userData) {
		$expiry =  date(Configure::read('DB_DATE_FORMAT'), strtotime(Configure::read('PASSWORD_EXPIRY')));		
		if($expiry >= $userData['User']['last_password_updated_dt']){
			return true;
		} 
		return false;
	}
     
     /**
	 * @name 			getAllRoles 
	 * @description	fetch all roles from database
	 * @param           null
	 * @return	     returns roles array or false	 
	 */
     public function getAllRoles(){
        $roles = $this->Role->find('all');
        $roleDd = array();
        foreach ($roles as $role) {
            if ($role['Role']['id'] != Configure::read('DEFAULT_ROLE_ID')){
                $roleDd[$role['Role']['id']]= $role['Role']['name'];
            }
        } 
        if($roleDd){
            return $roleDd;
        }else{
            return false;
        }
    }
    
    /**
	 * @name 			addUser 
	 * @description	add new user to company
	 * @param           all requested data on add new user form
	 * @return	     returns status	 
	 */
    public function addUser($data){
        $lastinsertedId = $this->saveData($data['User'], true);
        if ($lastinsertedId != false) {
            $data['UserCompany']['user_id'] = $lastinsertedId;
            $data['UserCompany']['company_id'] = CakeSession::read('user.UserCompany.company_id');
            $data['UserCompany']['role_id'] = $data['User']['role_id'];
            
            $UserToken = new UserToken();
            $token = $UserToken->generatToken($lastinsertedId, Configure::read('TOKEN_REGISTRATION_OR_ADDUSER'));
            
            $lastinsertedCompanyId = $this->saveData($data['UserCompany'], true, null, 'UserCompany');
            if ($lastinsertedCompanyId != false) {
                $data['User']['link'] = 'updateuserdata/'.$token;
                /*$Notification = new  NotificationComponent();
                $result = $Notification->sendMail(Configure::read('EMAIL_TEMP_REGISTRATION'), $data);*/
				
				//Email Queue integration			
				$EmailQueueData['EmailQueue']['to_email'] = $data['User']['username'];
				$EmailQueueData['EmailQueue']['user_id'] = $data['UserCompany']['user_id'];
				$EmailQueueData['EmailQueue']['cc_user_id'] = $data['UserCompany']['user_id'];
				$EmailQueueData['EmailQueue']['company_id'] = $data['UserCompany']['company_id'];
				$emailName = $data['User']['first_name']." ".$data['User']['last_name'];
				$emailUsername = $data['User']['username'];
				$emailLink = Configure::read('SITE_BASE_URL').$data['User']['link'];
				$EmailQueueData['EmailQueue']['email_subject_line'] = Configure::read('EMAIL_REGISTRATION_SUBJECT');
				$EmailQueueData['EmailQueue']['email_body_text'] = "
$emailName,


Welcome to cosentium! When you first sign in, you will be asked to create a password as well as 
a security question/answer combination in the event you forget your password.

<strong>Your Username:</strong>$emailUsername 


You may click on the link below to sign in.


<strong>Sign in:</strong> $emailLink


Again, welcome to Cosentium!


Cosentium Inc.
P.O. Box 163
Indianola, WA 98342
USA
www.cosentium.com
800.xxx.xxxx";				
				$EmailQueue = new  EmailQueue();
				$newEmaiQueueId = $EmailQueue->insertEmailQueue($EmailQueueData);
				//Email Queue integration			
				return Configure::read('SUCCESS_TO_ADD_NEW_USER');
            } else {
                return Configure::read('FAIL_TO_ADD_IN_USERCOMPANY_FOR_ADD');
            }
        } else {
            return Configure::read('FAIL_TO_ADD_IN_USERS_FOR_ADD');
        }
    }
    
    /**
	 * @name 			editUser 
	 * @description	edit user information
	 * @param           userData, request data and params variable
	 * @return	     returns status
	 */
    public function editUser($userData, $requestData, $paramVar){
        $UserToken = new UserToken();
        $token = $UserToken->generatToken($userData['User']['id'] ,Configure::read('TOKEN_EDIT_USER'));
        
        if(isset($requestData['User']['username'])){
            if($requestData['User']['username'] != CakeSession::read('user.User.username')){
                $requestData['User']['updated_username'] = $requestData['User']['username'];
            }
            $requestData['User']['username'] = CakeSession::read('user.User.username');
        }
        
        
        if($paramVar == Configure::read('SET_PARAM')){
            unset($requestData['User']['updated_username']);
            unset($requestData['User']['username']);
        } else {
            $requestData['User']['username'] = CakeSession::read('user.User.username');
        }

        if ($this->saveData($requestData['User'], false, $userData['User']['id'])) {
            if($paramVar == Configure::read('SET_PARAM')){
                $requestData['UserCompany']['role_id'] = $requestData['User']['role_id'];
                $requestData['UserCompany']['status'] = $requestData['status'];
                if ($this->saveData($requestData['UserCompany'], true, $userData['UserCompany']['id'], 'UserCompany')) {
                    return Configure::read('SUCCESS_TO_EDIT_USER');
                } else {
                    return Configure::read('FAIL_TO_ADD_IN_USERCOMPANY_FOR_EDIT');
                }
            }
            if (array_key_exists('updated_username', $requestData['User'])) {
                //$this->request->data['User']['username'] = $this->request->data['User']['updated_username'];
                $requestData['User']['link'] = 'changeemail/'.$token;
				/*$Notification = new  NotificationComponent();
                $Notification->sendMail(Configure::read('EMAIL_TEMP_EDIT_OLD_USERNAME'), $requestData);
                $Notification->sendMail(Configure::read('EMAIL_TEMP_EDIT_NEW_USERNAME'), $requestData);*/
				
				//Email Queue integration			
				$EmailQueueData['EmailQueue']['to_email'] = $requestData['User']['username'];
				$EmailQueueData['EmailQueue']['user_id'] = $userData['User']['id'];
				$EmailQueueData['EmailQueue']['cc_user_id'] = $userData['User']['id'];
				$EmailQueueData['EmailQueue']['company_id'] = $userData['Company']['id'];
				$emailName = $requestData['User']['first_name']." ".$requestData['User']['last_name'];
				$emailOldUsername = $requestData['User']['username'];
				$emailNewUsername = $requestData['User']['updated_username'];
				
				$EmailQueueData['EmailQueue']['email_subject_line'] = Configure::read('EMAIL_EDIT_OLD_USERNAME_SUBJECT');
				$EmailQueueData['EmailQueue']['email_body_text'] = "
Dear $emailName,


You recently changed the email address for your account in Cosentium.  If you did not make this change, please notify your administrator right away.  Following is a summary of your request.

  
Old email address: $emailOldUsername, 
New email address: $emailNewUsername.  



Cosentium Inc.
P.O. Box 163
Indianola, WA 98342
USA
www.cosentium.com
800.xxx.xxxx";				
				$EmailQueue = new  EmailQueue();
				//First email to Old username
				$newEmaiQueueId = $EmailQueue->insertEmailQueue($EmailQueueData);
				
				//Second email to New Username
				$EmailQueueData['EmailQueue']['to_email'] = $requestData['User']['updated_username'];				
				$emailLink = Configure::read('SITE_BASE_URL').$requestData['User']['link'];
				$EmailQueueData['EmailQueue']['email_subject_line'] = Configure::read('EMAIL_EDIT_NEW_USERNAME_SUBJECT');				
				$EmailQueueData['EmailQueue']['email_body_text'] = "
Dear $emailName,


You recently changed the email address for your account in Cosentium.  Click the link below to validate your new email address to complete the update process.
$emailLink


The above link is valid for 48 hours only.


Cosentium Inc.
P.O. Box 163
Indianola, WA 98342
USA
www.cosentium.com
800.xxx.xxxx";
				$newEmaiQueueId = $EmailQueue->insertEmailQueue($EmailQueueData);
				//Email Queue integration
                return Configure::read('SUCCESS_PERSONALINFO');
            }
        } else {
            return Configure::read('FAIL_TO_ADD_IN_USERS_FOR_EDIT');
        }
        return Configure::read('SUCCESS_TO_EDIT_USER');
    }
    
    /**
     * @name 			uploadProfilePic 
     * @description	upload or update profile picture
     * @param           userData, request data
     * @return	     returns status	 
     */
    public function uploadProfilePic($requestData, $userData) {
        if (!empty($requestData['User']['image'])) {
            $this->AmazoneFileSystem = ClassRegistry::init('AmazoneFileSystem');
            $file = $requestData['User']['image'];
            $type = explode('/', $file['type']);
            $file['name'] = md5(CakeSession::read('user.User.id') . "_" . date(Configure::read('DB_DATEIMG_FORMAT')) . "." . $type[1]);
            //remove existing file
            if ($this->AmazoneFileSystem->getObjectInfo($this->AmazoneFileSystem->bucket, Configure::read('AWS_IMAGES_FOLDER') . $userData['User']['image'], true)) {
                $this->AmazoneFileSystem->deleteObject($this->AmazoneFileSystem->bucket, Configure::read('AWS_IMAGES_FOLDER') . $userData['User']['image']);
            }
            $this->AmazoneFileSystem->putObjectFile($file['tmp_name'], $this->AmazoneFileSystem->bucket, Configure::read('AWS_IMAGES_FOLDER') . $file['name'], S3::ACL_PUBLIC_READ);

            if ($this->saveData(array('image' => $file['name']), false, CakeSession::read('user.User.id'))) {
                return Configure::read('SUCCESS_TO_ADD_PROFILEPIC');
            } else {
                return Configure::read('FAIL_TO_ADD_IN_USERS_FOR_PROFILEPIC');
            }
        }
    }

    /**
     * @name 			deleteprofilepic 
     * @description	delete profile picture
     * @param           profile Picture name
     * @return	     returns status	 
     */
    public function deleteprofilepic($profileImage) {

        
        $this->AmazoneFileSystem = ClassRegistry::init('AmazoneFileSystem');        
        if ($this->AmazoneFileSystem->getObjectInfo($this->AmazoneFileSystem->bucket, Configure::read('AWS_IMAGES_FOLDER') . $profileImage, true)) {
            $this->AmazoneFileSystem->deleteObject($this->AmazoneFileSystem->bucket, Configure::read('AWS_IMAGES_FOLDER') . $profileImage);            
            if ($this->saveData(array('image' => NULL), false, CakeSession::read('user.User.id'))) {                
                return Configure::read('SUCCESS_TO_ADD_DELETE_PROFILEPIC');
            } else {                
                return Configure::read('FAIL_TO_ADD_IN_USERS_FOR_DELETE_PROFILEPIC');
            }
        }
    }
    
    /**
	 * @name 			changeEmail 
	 * @description	update email address in database and session if session is set
	 * @param           edit email token
	 * @return	     returns status	 
	 */
    public function changeEmail($token) {
        $UserToken = new UserToken();
        $userTokenData = $UserToken->validateToken($token, array(Configure::read('TOKEN_EDIT_USER')));
        
        $userData = $this->findById($userTokenData['UserToken']['user_id']);

        //redirection
        if ($userTokenData == 1 || $userTokenData == 3) {
            // Invalid Token redirected to page not found as per Use Case Free Trial - B6
            return Configure::read('INVALID_TOKEN');
        } elseif ($userTokenData == 2) {
            return Configure::read('EXPIRED_LINK');
        } 

        //update database
        if ($this->saveData(array(
            'username' => $userData['User']['updated_username'],
            'updated_username' => NULL
            ), true, $userData['User']['id'])) {
            //delete token from database
            $UserToken->clearToken($userData['User']['id'], $token);

            if(CakeSession::read('user') != null) {
                //update user session and auth variable if user is logged in
                CakeSession::write('user.User.username', $userData['User']['updated_username']);
                CakeSession::write('user.User.updated_username', NULL);
                return Configure::read('ISSET_SESSION');
            }else{
                return Configure::read('NOT_SET_SESSION');
            }
        } else {
            return Configure::read('FAIL_TO_UPDATE_EMAIL');
        }
    }
    
    /**
	 * @name 			removeDelegation 
	 * @description	delete delegation
	 * @param           null
	 * @return	     returns status	 
	 */
    public function removeDelegation() {
        if ($this->saveData(array(
                'delegate_to' => NULL,
                'delegation_start_dt' => NULL,
                'delegation_end_dt' => NULL
            ), false, CakeSession::read('user.UserCompany.id') ,'UserCompany')) {
            return Configure::read('SUCCESS_TO_REMOVE_DELEGATION');
        } else {
            return Configure::read('FAIL_TO_REMOVE_DELEGATION');
        }
    }

    /**
	 * @name 			manageDelegation 
	 * @description	add delegation
	 * @param           null
	 * @return	     returns status	 
	 */
    public function manageDelegation($requestData) {
        $UserCompany = new UserCompany();
          if (($requestData['UserCompany']['from'] != NULL && $requestData['UserCompany']['to'] != NULL)) {
            if(date("Y-m-d", strtotime($requestData['UserCompany']['from'])) < date("Y-m-d")){
                return Configure::read('EARLIER_FROM_DATE_DELEGATION'); 
            }
            if(date("Y-m-d", strtotime($requestData['UserCompany']['to'])) < date("Y-m-d")){
                return Configure::read('EARLIER_TO_DATE_DELEGATION');
            }
            if(date("Y-m-d", strtotime($requestData['UserCompany']['from'])) > date("Y-m-d", strtotime($requestData['UserCompany']['to']))){
                return Configure::read('EARLIER_TO_THAN FROM_DATE_DELEGATION');
            }
            if ($this->saveData(array(
                'delegate_to' => $requestData['UserCompany']['delegate_to'],
                'delegation_start_dt' => date("Y-m-d", strtotime($requestData['UserCompany']['from'])),
                'delegation_end_dt' => date("Y-m-d", strtotime($requestData['UserCompany']['to']))
                ), false, CakeSession::read('user.UserCompany.id'), 'UserCompany')) {
                return Configure::read('SUCCESS_TO_MANAGE_DELEGATION');
            } else {
                return Configure::read('FAIL_TO_MANAGE_DELEGATION');
            }
        } elseif ($requestData['UserCompany']['from'] == NULL && $requestData['UserCompany']['to'] == NULL){
            if ($this->saveData(array(
                'delegate_to' => $requestData['UserCompany']['delegate_to']
                ), false, CakeSession::read('user.UserCompany.id'), 'UserCompany')) {
                return Configure::read('SUCCESS_TO_MANAGE_DELEGATION');
            } else {
                return Configure::read('FAIL_TO_MANAGE_DELEGATION');
            }
        }else{
            if($requestData['UserCompany']['from'] == NULL){
                return Configure::read('NULL_FROM_DATE_DELEGATION');
            }else{
                return Configure::read('NULL_TO_DATE_DELEGATION');
            }
        }
    }
    
    /**
	 * @name 			UserDdCondition 
	 * @description	get users only with review deals permission
	 * @param           Roles id with review deals permission
	 * @return	     user data	 
	 */
    public function UserDdCondition($DealReviewRoleId, $type, $userId = null) {
        $userDataDdArr = array();
        if(!empty($DealReviewRoleId)){
            if ($type == Configure::read('DROPDOWN_TYPE_MANAGEDELEGATION') && $userId == '') {
                $userId = CakeSession::read('user.User.id');
            }
            if (count($DealReviewRoleId) == 1) {
                $userDataDd = $this->find('all',array('conditions' => array('UserCompany.company_id' => CakeSession::read('user.UserCompany.company_id'), 
                    'User.id !=' => $userId,
                    'UserCompany.status' => 1,
                    'Company.status' => 1,
                    'User.access_status' => 1,
                    'UserCompany.role_id' => $DealReviewRoleId)));
            } else {
                $userDataDd = $this->find('all',array('conditions' => array('UserCompany.company_id' => CakeSession::read('user.UserCompany.company_id'), 
                    'User.id !=' => $userId,
                    'UserCompany.status' => 1,
                    'Company.status' => 1,
                    'User.access_status' => 1,
                    'UserCompany.role_id IN' => $DealReviewRoleId)));
            }
            foreach ($userDataDd as $userDataDd) {
                $userDataDdArr[$userDataDd['User']['id']]= $userDataDd['User']['last_name']." ".$userDataDd['User']['first_name'];
            }
            return $userDataDdArr;
        }
    }
    
    /**
	 * @name 			getUserStatus 
	 * @description	get users only with review deals permission
	 * @param           Roles id with review deals permission
	 * @return	     user data	 
	 */
    public function getUserStatus($userId) {
        if (!empty($userId)) {
            $result = $this->query("Select user_id from ".CakeSession::read('user.Company.prefix')."_sections_users where user_id = ".$userId.
                " UNION 
                Select owner from ".CakeSession::read('user.Company.prefix')."_master_docs where owner = ".$userId);
            return $result;
        }
    }
    
    public function getUserNameById($userId) {
        if (!empty($userId)) {
            $userData = $this->find('first', array('conditions' => array( 'User.id' => $userId), 'fields' => array('id', 'first_name', 'last_name')));
            return $userData;
        } 
        return false;
    }
    
    public function getdecisionMakerDataByUserId($userId) {
        if (!empty($userId)) {
            $decisionMakerData = $this->query("Select MasterDoc.name, MasterDoc.owner, MasterDoc.files_id, Section.name, Section.id, SectionUser.id, User.first_name, User.last_name, User.username, User.image from ".CakeSession::read('user.Company.prefix')."_sections_users as SectionUser
                    left join ".CakeSession::read('user.Company.prefix')."_sections as Section on (Section.id = SectionUser.sections_id)
                    left join ".CakeSession::read('user.Company.prefix')."_master_docs as MasterDoc on (MasterDoc.id = Section.master_docs_id)
                    left join users as User on (MasterDoc.owner = User.id)
                    where SectionUser.user_id = ".$userId." AND
                    MasterDoc.available_deal_issue = 1 AND
                    SectionUser.act_as = '".Configure::read('DECISION_MAKER')."'");
            return $decisionMakerData;
        }
        return false;
    }
    
    public function getopinionProviderDataByUserId($userId) {
        if (!empty($userId)) {
            $opinionProviderData = $this->query("Select MasterDoc.name, MasterDoc.owner, MasterDoc.files_id, Section.name, Section.id, SectionUser.id, User.first_name, User.last_name, User.username, User.image from ".CakeSession::read('user.Company.prefix')."_sections_users as SectionUser
                    left join ".CakeSession::read('user.Company.prefix')."_sections as Section on (Section.id = SectionUser.sections_id)
                    left join ".CakeSession::read('user.Company.prefix')."_master_docs as MasterDoc on (MasterDoc.id = Section.master_docs_id)
                    left join users as User on (MasterDoc.owner = User.id)
                    where SectionUser.user_id = ".$userId." AND
                    MasterDoc.available_deal_issue = 1 AND
                    SectionUser.act_as = '".Configure::read('OPINION_PROVIDER')."'");
            return $opinionProviderData;
        }
        return false;
    }
    
    /*public function validateRequestRemoval($requestData) {
        $uncheckedRequestRemoval = array();
        foreach($requestData as $requestData) {
            foreach($requestData as $key => $requestData) {
                if ($requestData == 0) {
                    $uncheckedRequestRemoval[] = $key;
                }
            }
        }
        return $uncheckedRequestRemoval;
    }*/
    
    public function getTokenIdFromToken($token) {
        $UserToken = new UserToken();
        if ($result = $UserToken->findByToken($token)) {
            return $result;
        }
        return false;
    }
    
    public function saveTokenInSectionUsers($token, $sectionUserId) {
        $result = $this->getTokenIdFromToken($token);
        if ($result) {
            $this->query("UPDATE ".CakeSession::read('user.Company.prefix')."_sections_users
                SET token_id = ".$result['UserToken']['id']."
                WHERE id = ".$sectionUserId);
            return true;
        }
        return false;
    }

    public function reassign($requestData, $reassignee, $typeData, $userData, $type) {
        foreach ($typeData as $typeData) {
            if ($type == Configure::read('MASTER_DOC_REASSIGN')) {
                $checkboxCondition = $requestData['masterDocOwnerCheckBox']['masterDocOwnerCheckBox'.$typeData['MasterDoc']['id']];
            }

            if ($checkboxCondition == 1) {
                $reassigneeData = $this->findById($requestData['user']['owner'.$typeData['MasterDoc']['id']]);
                //pr($reassigneeData);exit;
                $Masterdoc = new Masterdoc();
                $SectionUser = new SectionUser();
                $SectionUser->id = $typeData['SectionUser']['id'];
                
                if (!empty($requestData['user']['owner'.$typeData['MasterDoc']['id']])) {
                    $SectionUser->savefield('user_id', $requestData['user']['owner'.$typeData['MasterDoc']['id']]);
                    $Masterdoc->saveData(array('owner' => $requestData['user']['owner'.$typeData['MasterDoc']['id']]), false, $typeData['MasterDoc']['id']);
                }
                
                //$Masterdoc->saveData(array('user_id' => $requestData['user']['owner'.$typeData['MasterDoc']['id']]), false, $typeData['SectionUser']['id'], 'SectionUser');

                $EmailQueueData['EmailQueue']['to_email'] = $reassigneeData['User']['username'];
                $EmailQueueData['EmailQueue']['user_id'] = CakeSession::read('user.User.id');
                $EmailQueueData['EmailQueue']['cc_user_id'] = CakeSession::read('user.User.id');
                $EmailQueueData['EmailQueue']['company_id'] = CakeSession::read('user.Company.id');

                $emailName = $userData['User']['first_name']." ".$userData['User']['last_name']."'s";
                $reassigneeName = $reassigneeData['User']['first_name']." ".$reassigneeData['User']['last_name'];
                $reassigneerName = CakeSession::read('user.User.first_name')." ".CakeSession::read('user.User.last_name')."'s";
                $emailUserName = $userData['User']['first_name']." ".$userData['User']['last_name'];
                $emailMasterDoc = $typeData['MasterDoc']['name'];
                //$emailSectionName = $typeData['Section']['name'];
                
                $EmailQueueData['EmailQueue']['email_subject_line'] = 'One of '.$emailName.' Cosentium Master Documents has been reassigned to you';
                
                $UserToken = new UserToken();
                if ($type == Configure::read('MASTER_DOC_REASSIGN')) { 
                        $token = $UserToken->generatToken($reassigneeData['User']['id'], Configure::read('TOKEN_CHANGE_OWNER')); 
                        
                        //insert token id in setions user table
                        $this->saveTokenInSectionUsers($token, $typeData['SectionUser']['id']);
                        
                        $emailLink = Configure::read('SITE_BASE_URL').'Masterdocs/reassignRole/'.$token;

                        $EmailQueueData['EmailQueue']['email_body_text'] = "
$reassigneeName,
 

$reassigneerName has reassigned one of $emailName Cosentium master documents to you.  The master document details are:
Master Document Name: $emailMasterDoc
Click this link to access the master document: $emailLink

Cosentium Inc.
P.O. Box 163
Indianola, WA 98342
USA
www.cosentium.com
800.xxx.xxxx"; 
                    }
                    $EmailQueue = new  EmailQueue();
                    $newEmaiQueueId = $EmailQueue->insertEmailQueue($EmailQueueData);
            }
        }
    }
    
  public function changeReport($requestData, $typeData, $userData, $type) {
        $newEmaiQueueId = '';
        if (!empty($typeData)) {
            foreach ($typeData as $typeData) {
                
                if ($type == Configure::read('DECISION_MAKER')) {
                    $checkboxCondition = $requestData['decisionCheckBox'.$typeData['Section']['id']];
                } elseif ($type == Configure::read('OPINION_PROVIDER')) {
                    $checkboxCondition = $requestData['opinionCheckBox'.$typeData['Section']['id']];
                }
                
                if ($checkboxCondition == 1) {
                    
                    //Generate Token
                    $EmailQueueData['EmailQueue']['to_email'] = $typeData['User']['username'];
                    $EmailQueueData['EmailQueue']['user_id'] = CakeSession::read('user.User.id');
                    $EmailQueueData['EmailQueue']['cc_user_id'] = CakeSession::read('user.User.id');
                    $EmailQueueData['EmailQueue']['company_id'] = CakeSession::read('user.Company.id');

                    $emailName = $typeData['User']['first_name']." ".$typeData['User']['last_name'];
                    $emailUserName = $userData['User']['first_name']." ".$userData['User']['last_name'];
                    $emailMasterDoc = $typeData['MasterDoc']['name'];
                    $emailSectionName = $typeData['Section']['name'];

                    
                    $EmailQueueData['EmailQueue']['email_subject_line'] = 'Request to remove '.$emailUserName.' from your Cosentium master document';
                    
                    $UserToken = new UserToken();
                    if ($type == Configure::read('DECISION_MAKER')) { 
                        $token = $UserToken->generatToken($typeData['MasterDoc']['owner'], Configure::read('TOKEN_CHANGE_DECISIONMAKER')); 
                        
                        //insert token id in setions user table
                        $this->saveTokenInSectionUsers($token, $typeData['SectionUser']['id']);
                        
                        $emailLink = Configure::read('SITE_BASE_URL').'Masterdocs/reassignRole/'.$token;

                        $EmailQueueData['EmailQueue']['email_body_text'] = "
$emailName,
 

$emailName requests that you remove $emailUserName from Master Document $emailMasterDoc, which you are the owner of, so that the user's Cosentium account can be deprovisioned.  The user is listed as a Decision Maker for section $emailSectionName.  Please click the link below to update the section to a different decision maker.
    

Update section decision maker: $emailLink
  

Cosentium Inc.
P.O. Box 163
Indianola, WA 98342
USA
www.cosentium.com
800.xxx.xxxx"; 
                    }
                    if ($type == Configure::read('OPINION_PROVIDER')) { 
                        $token = $UserToken->generatToken($typeData['MasterDoc']['owner'], Configure::read('TOKEN_CHANGE_OPINIONPROVIDER')); 
                        
                        //insert token id in setions user table
                        $this->saveTokenInSectionUsers($token, $typeData['SectionUser']['id']);
                        
                        $emailLink = Configure::read('SITE_BASE_URL').'Masterdocs/reassignRole/'.$token;
                        $EmailQueueData['EmailQueue']['email_body_text'] = "
$emailName,


$emailName requests that you remove $emailUserName from Master Document $emailMasterDoc, which you are the owner of, so that the user's Cosentium account can be deprovisioned.  The user is listed as a Opinion Provider for section $emailSectionName.  
Please click the link below to update the section to a different Opinion Provider.


Update section opinion provider: $emailLink
    

Cosentium Inc.
P.O. Box 163
Indianola, WA 98342
USA
www.cosentium.com
800.xxx.xxxx"; 
                    }

                    //pr($EmailQueueData);
                    $EmailQueue = new  EmailQueue();
                    $newEmaiQueueId = $EmailQueue->insertEmailQueue($EmailQueueData);
                }
            }
        }
        if($newEmaiQueueId == '') {
             return Configure::read('USER_REGISTER_SUCCESS');
        } 
    }
}


