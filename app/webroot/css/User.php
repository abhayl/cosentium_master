<?php 
// app/Model/User.php
//App::uses('AuthComponent', 'Controller/Component');
App::import('Component', 'Notification');
App::import('Model', 'UserToken');
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
                    
		)
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
	public function saveData($fieldArr, $checkValidate=true, $id = null){
        if($id != NULL){
            $this->id = $id;
        }
		$this->save($fieldArr, $checkValidate);
        if($this->getLastInsertID() != null){
            return $this->getLastInsertID();
        }else {
            return true;
        }
        
    }
	/**
	 * @name 			Register
	 * @description		Free Trial         			
	 * @parm	        $userData Request Data 
	 * @return	        T/F
	 */
	public function register($userData) { 
		if(!$this->validates()) { 
			if(!$this->Company->validates())
				return false;	
			else
				return false;	
		}	
		if ($this->Company->validates() && $this->validates()) {
				$userData['Company']['expiry_date'] = date(Configure::read('DB_DATE_FORMAT'), strtotime("+ ".Configure::read('FREE_TRIAL_PERIOD_IN_DAYS')));	
				
				$newCompanyId = $this->Company->saveData($userData); 
				
				$newUserId = $this->saveData($userData);
				
				$userData['UserCompany']['company_id'] = $newCompanyId;
				$userData['UserCompany']['user_id'] = $newUserId;						
				$userData['UserCompany']['role_id']	= Configure::read('DEFAULT_ROLE_ID');					
				
				$newUserCompanyId = $this->UserCompany->saveData($userData);
				
				//Generate Token
				$UserToken = new UserToken();	
				$token = $UserToken->generatToken($newUserId,Configure::read('TOKEN_REGISTRATION_OR_ADDUSER')); 
			
				// Send Email 	
				$userData['User']['link'] = 'companies/changepassword/'.$token;
				
				//$this->Role->setPermissions($userData['UserCompany']['company_id'], Configure::read('DEFAULT_COMPANY'), '', '');
                $this->Company->createDocTables(strtolower($userData['Company']['company_name']));
				$Notification = new  NotificationComponent();
				$result = $Notification->sendMail(Configure::read('EMAIL_TEMP_REGISTRATION'), $userData);
				if($result) {
					return Configure::read('USER_REGISTER_SUCCESS');
				} else { 
					return Configure::read('USER_FAIL');
				}				
			} else {
				return false;
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
			return false;
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
		$userData['User']['link'] = 'companies/securityAnswer/'.$token;	
		
		//Send Email with Security Answer link
		$Notification = new  NotificationComponent();
		$result = $Notification->sendMail(Configure::read('EMAIL_TEMP_FORGOT_PASSWORD'), $userData);
		if($result) { 
			return Configure::read('USER_FORGOT_PASSWORD_SUCCESS');
		} else { 
			return Configure::read('USER_FAIL');
		}
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
				 return false;
		}			
		$security_answer = strtolower(trim($userPostData['User']['security_answer'])); //COS-26 - Eric comments on 04/09/2013
		$security_answer_enc = $this->encryptPassword($security_answer,$userData);
		
		$this->id = $userData['User']['id'];
		
		if($userData['User']['security_answer'] != $security_answer_enc) {
			//Max. Attempt Are done	
			if($userData['User']['unsuccess_security_counter'] == Configure::read('MAX_ANSWER_ATTTMPT')) { 
				//Updated Other fields
				$this->set(array(
					'unsuccess_security_counter' => Configure::read('DEFAULT_COUNTER'),
					'access_status' => Configure::read('LOCK_USER_FORGOT_STATUS')					
				));
				$this->save(); 
				return Configure::read('USER_SECURITY_ANSWER_LOCK');
				//$this->redirect('message/lock_user_forgot');
			}
			//Updated Other fields
			$this->set(array(
				'unsuccess_security_counter' => $userData['User']['unsuccess_security_counter']+1,
				'last_unsuccess_security_access_dt' => date(Configure::read('DB_DATE_FORMAT'))
			));
			$this->save();
			$this->invalidate('security_answer', __('Security question answer does not match our records', true));
			return  Configure::read('USER_SECURITY_ANSWER_INVALID');
			
		} else { // Answer is correct 
			
			//Updated Other fields
			$this->set(array(
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
			return false;
		}
	
		if($this->validates()){
			
			$password_enc = $this->encryptPassword($userPostData['User']['password'],$userData);
			$security_answer = strtolower(trim($userPostData['User']['security_answer'])); //COS-26 - Eric comments on 04/09/2013
			$security_answer_enc = $this->encryptPassword($security_answer,$userData);
			
						
			$this->updateNewPassword($password_enc,$userData);
			
			//Updated Other fields
			$this->id = $userData['User']['id'];
			$this->set(array(
				'security_question_id' => $userPostData['User']['security_question_id'],
				'security_answer' => $security_answer_enc,
				'access_status' => Configure::read('ACTIVE_USER_STATUS')					
			));
			$this->save(); 
			$UserToken = new UserToken();
			$UserToken->clearToken($userData['User']['id'],$userPostData['User']['token']);			
			return Configure::read('USER_CHANGE_PASSWORD_SUCCESS');
		} else {
			return false;
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
				return false;
			}
			if($this->checkOldPassword($new_pass_enc,$userData)) {
				$this->invalidate('password', __('Your new password must be one you have not used previously.', true));
				return false;
			}
			$password_enc = $new_pass_enc;
			$security_answer = strtolower(trim($userPostData['User']['security_answer'])); //COS-26 - Eric comments on 04/09/2013
			$security_answer_enc = $this->encryptPassword($security_answer,$userData); 

			$this->updateNewPassword($password_enc,$userData);// Updated password
			
			$this->id = $userData['User']['id'];
			//Updated Other fields
			$this->set(array(
				'security_question_id' => $userPostData['User']['security_question_id'],
				'security_answer' => $security_answer_enc					
			));
			$this->save();
			
			return Configure::read('USER_PROFILE_CHANGE_PASSWORD_SUCCESS');
			//$this->redirect('/users/personalinfo/success');
		} else { 
			return false;
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
			// Generate Token 
			$UserToken = new UserToken();
			$token = $UserToken->generatToken($val,Configure::read('TOKEN_RESET_PASSWORD')); 
			
			/* if client wants that user's password should get empty then uncomment this code
			$newPassword = NULL;
			$this->updateNewPassword($newPassword,$userData);
			*/
			// Send Reset Password Eamil
			$userData['User']['link'] = 'companies/changepassword/'.$token;
			$Notification = new  NotificationComponent();				
			$result = $Notification->sendMail(Configure::read('EMAIL_TEMP_RESET_PASSWORD'), $userData);
			if(!$result) { 
				return Configure::read('USER_FAIL');
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
	
	
	
}


