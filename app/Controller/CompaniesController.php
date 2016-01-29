<?php
class CompaniesController extends AppController {
	public $uses = array('Company', 'User', 'UserCompany', 'Role', 'SecurityQuestions');
	public $components = array('Notification');	
	/**
	 * @name 			register 
	 * @description		Free Trial         			
	 * @parm	        null
	 * @return	        null	 
	 */	
	public function register() {
		$sess_auth = $this->Session->read('Auth');
		if(isset($sess_auth['User'])) {
			$this->redirect('/users/logout');
			return;
		}
		$this->set('title_for_layout', __('FREE TRIAL'));
		$this->set('employee_strength_val', $this->Company->employeeStrengthVal);
		
		if ($this->request->is('post')) {
			$this->Company->data = $this->request->data;
			$this->User->data = $this->request->data;
			$result = $this->User->register($this->request->data);
			if($result == Configure::read('USER_REGISTER_SUCCESS')) {
				$this->redirect('message/success');	
			} else if($result == Configure::read('USER_VALIDATION_FAIL')) { 
				return false;
			}
		}
	}
	/**
	 * @name 			_logoutUser 
	 * @description		Common User Logout functionality
	 * @param	        
	 * @return	        	 
	 */     
    public function _logoutUser() {
         $sess_auth = $this->Session->read('Auth');
         if(isset($sess_auth['User'])) {
            $this->redirect('/users/logout');
            return;
         }
         return;
     }
             
	/**
	 * @name 			message 
	 * @description		Common Message         			
	 * @param	        $id (success/expired_link/lock_user_forgot/page_not_found)
	 * @return	        	 
	 */
	public function message($id = null) {
		switch($id){
			case 'success': //Success
                    $this->_logoutUser();
					$this->set('title_for_layout', 'Your free trial is ready to use');
					$this->set('message', $id);
					break;
			case 'expired_link': //Expired link
                    $this->_logoutUser();
					$this->set('title_for_layout', 'Expired link.');
					$this->set('message', $id);
					break;
					
			case 'lock_user_forgot' : // Locked user by wrong answer given in forgot password link
                    $this->_logoutUser();
					$this->set('title_for_layout', 'Account Locked');
					$this->set('message', $id);
					break;
			case 'fail' : // Common Fail Operation
					$this->set('title_for_layout', 'System Failiure');
					$this->set('message', $id);
					break;
			
			case 'page_not_found': //Page Not Found
			default:
                    $this->_logoutUser();
					$this->set('title_for_layout', 'Page Not Found');
					$this->set('message', $id);
					break;
		}
	}
	/**
	 * @name 			agreement 
	 * @description		COMMERCIAL SERVICES AGREEMENT         			
	 * @parm	        null
	 * @return	        null	 
	 */
	public function agreement() {
		$this->set('title_for_layout', 'COMMERCIAL SERVICES AGREEMENT');
	}
	/**
	 * @name 			changepassword 
	 * @description		Free Trial - change password        			
	 * @parm	        $token
	 * @return	        null	 
	 */
	public function changepassword($token=null){
		$this->_logoutUser();
		
		$allowTokenType = array(
						Configure::read('TOKEN_REGISTRATION_OR_ADDUSER'),
						Configure::read('TOKEN_FORGOT_PASSWORD'),
						Configure::read('TOKEN_RESET_PASSWORD'),
						Configure::read('TOKEN_EXPIRED_PASSWORD')
					);
		$tokenResult = $this->UserToken->validateToken($token,$allowTokenType);
		/*pr($tokenResult); 
		exit;*/
		switch($tokenResult) { 
			case 1: // Token Empty 
			case 3: // Invalid Token or Invalid Type
					$this->redirect('message/page_not_found');
					return;
					break;
			case 2: // Expired link 
					$this->redirect('message/expired_link');
					return;
					break;
			default : // Token ok
					$userTokenData = $tokenResult;
					break;			
		}
		
		$this->set('token',$token);
		$userData = $this->User->findById($userTokenData['UserToken']['user_id']);
		
		
		if($userTokenData['UserToken']['type'] == Configure::read('TOKEN_FORGOT_PASSWORD')) {
			$this->set('title_for_layout', __('Change Password'));
			$this->set('change_password_heading',__('Change Password'));
			$this->set('change_password_body_text',__('For security purposes, your password must now be changed.'));
		} else 	if($userTokenData['UserToken']['type'] == Configure::read('TOKEN_RESET_PASSWORD')) {
			$this->set('title_for_layout', __('Reset Password'));
			$this->set('change_password_heading',__('Reset Password'));
			$this->set('change_password_body_text',__('For security purposes, your password must now be changed.'));
		} else  if($userTokenData['UserToken']['type'] == Configure::read('TOKEN_EXPIRED_PASSWORD')) {
			$this->set('title_for_layout', __('Expired Password'));
			$this->set('change_password_heading',__('Expired Password'));
			$this->set('change_password_body_text',__('For security purposes, your password must now be changed.'));
		} else { 
		//if($userTokenData['UserToken']['type'] == Configure::read('TOKEN_REGISTRATION_OR_ADDUSER')) {
			$this->set('title_for_layout', __('Welcome to Cosentium!'));
			$this->set('change_password_heading',__('Welcome to Cosentium!'));
			$this->set('change_password_body_text',__('You must now specify a password as well as a security question and answer'));
		}
		
		$secrets_question_data = $this->SecurityQuestions->find("list",array("fields"=>"question"));		
		$this->set('userData', $userData);
		$this->set('secrets_question_data', $secrets_question_data);			
		
		
		if ($this->request->is('post') || isset($this->request->data['User'])) {
			$this->User->data = $this->request->data;
			$result = $this->User->changepassword($this->request->data,$userData);
			if($result == Configure::read('USER_CHANGE_PASSWORD_SUCCESS')) {
				$this->redirect('/users/login/success');
			} else if($result == Configure::read('USER_VALIDATION_FAIL')) { 
				return false;
			}
		}
	}	
	
	/**
	 * @name 			Forgot Password 
	 * @description		Forgot Password -- Login Page
	 * @parm	        null
	 * @return	        null	 
	 */
	public function forgotPassword($success=null){
		$this->_logoutUser();
	
		$this->set('title_for_layout', 'Forgot Password');
		if(isset($success) && !empty($success)) {
			$this->set('succeMessage',__('Check your inbox for sign-in information. If you do not receive an email, please check your junk or spam folder or contact your Cosentium system administrator.'));
		}
			
		if ($this->request->is('post')) {
			$this->User->data = $this->request->data;
			$result = $this->User->forgotPassword($this->request->data);
			if($result == Configure::read('USER_FORGOT_PASSWORD_SUCCESS')) {
				$this->redirect('forgotPassword/success');
			} else if($result == Configure::read('USER_VALIDATION_FAIL')) { 
				return false;
			}
		}
	}
	/**
	 * @name 			securityAnswer  
	 * @description		securityAnswer -- Forgot Password Link
	 * @parm	        $token
	 * @return	        null	 
	 */
	public function securityAnswer($token=null){
		$this->_logoutUser();
		
		$this->set('title_for_layout', 'Forgot Password');
		
		$allowTokenType = array(Configure::read('TOKEN_FORGOT_PASSWORD'));
		$tokenResult = $this->UserToken->validateToken($token,$allowTokenType);
		/*pr($tokenResult); 
		exit;*/
		switch($tokenResult) { 
			case 1: // Token Empty 
			case 3: // Invalid Token or Invalid Type
					$this->redirect('message/page_not_found');
					return;
					break;
			case 2: // Expired link 
					$this->redirect('message/expired_link');
					return;
					break;
			default : // Token ok
					$userTokenData = $tokenResult;
					break;			
		}
		
		$this->set('token',$token);
		$userData = $this->User->findById($userTokenData['UserToken']['user_id']);
		
		//Check for 30 Min. for Unlock Account
		//echo "@@".$userData['User']['last_unsuccess_security_access_dt']."@@".date(Configure::read('DB_DATE_FORMAT'), strtotime(Configure::read('USER_LOCK_PERIOD_IN_MIN'))); exit;
		if ($userData['User']['access_status'] == Configure::read('LOCK_USER_FORGOT_STATUS') && $userData['User']['last_unsuccess_security_access_dt'] <= date(Configure::read('DB_DATE_FORMAT'), strtotime(Configure::read('USER_LOCK_PERIOD_IN_MIN')))) {
			$this->User->id = $userData['User']['id'];
			$this->User->set(array(
							'last_unsuccess_security_access_dt' => NULL,
							'unsuccess_security_counter' => Configure::read('DEFAULT_COUNTER'),
							'access_status' => Configure::read('ACTIVE_USER_STATUS')
						));
			$this->User->save();
			$userData['User']['last_unsuccess_access_dt'] = NULL;
			$userData['User']['unsuccess_access_counter'] = Configure::read('DEFAULT_COUNTER');
			$userData['User']['access_status'] = Configure::read('ACTIVE_USER_STATUS');			
		}
		//pr($userData);
		//LOCK_USER_FORGOT_STATUS
		if($userData['User']['access_status'] == Configure::read('LOCK_USER_FORGOT_STATUS')) {
			$this->redirect('message/lock_user_forgot');
		}
		

		$this->set('security_question',$userData['SecurityQuestions']['question']);
		$this->set('userData', $userData); 

		
		if ($this->request->is('post') || isset($this->request->data['User'])) {
			$result = $this->User->securityAnswer($this->request->data,$userData);
			switch($result) {
				case Configure::read('USER_SECURITY_ANSWER_SUCCESS') : //correct_security_answer		
										$this->redirect('changepassword/'.$this->request->data['User']['token']);
										break;
				case Configure::read('USER_SECURITY_ANSWER_LOCK')  : //lock_user_forgot
										$this->redirect('message/lock_user_forgot');
										break;
				case Configure::read('USER_SECURITY_ANSWER_INVALID') : //invalid_security_answer
				default	:
							return false;
			}
			
		}	
	}
}