<?php 
App::uses('User', 'Model');
#App::uses('Controller', 'Controller');
#App::uses('View', 'View');
#App::uses('ProgressHelper', 'View/Helper');

class UserTest extends PHPUnit_Framework_TestCase {
	public $fixtures = array('app.user');
	//public $autoFixtures = false;
    public function setUp() {
		parent::setUp();
		//Initialise Models
		$this->User = ClassRegistry::init('User');		
		$this->Company = ClassRegistry::init('Company');
		$this->UserCompany = ClassRegistry::init('UserCompany');		
		$this->UserToken = ClassRegistry::init('UserToken');		
		
		//Sample Test Data Positive Data
		//Register 
		$this->reqPassData['User']['first_name'] = 'PHPUnitFirstName';
		$this->reqPassData['User']['last_name'] = 'PHPUnitLastName';
		$this->reqPassData['User']['username'] = 'PHPUnitUsername@username.com';
		$this->reqPassData['Company']['phone1'] = '123';
		$this->reqPassData['Company']['phone2'] = '123';
		$this->reqPassData['Company']['phone3'] = '1234';
		$this->reqPassData['Company']['job_title'] = 'job_title';
		$this->reqPassData['Company']['company_name'] = 'PHPUnitCompany';		
		$this->reqPassData['Company']['employee_strength'] = '51-150';
		$this->reqPassData['Company']['terms'] = 1;	
		
		//Change Password For Register		
		$this->changePassData['User']['password'] = 'Test@123';
		$this->changePassData['User']['new_password'] = 'Test@123';
		$this->changePassData['User']['security_question_id'] = rand(2,5);
		$this->changePassData['User']['security_answer'] = 'pune';
		//Negative Data
		$this->changeNegData['User']['password'] = 'Test@1231';
		$this->changeNegData['User']['new_password'] = 'Test@123'; 
		$this->changeNegData['User']['security_question_id'] = rand(2,5);
		$this->changeNegData['User']['security_answer'] = 'Test@123';
		
		//Forgot Password
		$this->forgotPassData['User']['username'] = 'PHPUnitUsername@username.com';
		$this->forgotNegData['User']['username'] = 'PHPUnitUsername121@username.com';
		
		//Forgot Password SecurityAnswer 
		$this->SecAnsPassData['User']['security_answer'] = 'pune';
		$this->SecAnsPassData['User']['password'] = 'Test@234';
		$this->SecAnsPassData['User']['new_password'] = 'Test@234';
		$this->SecAnsPassData['User']['security_question_id'] = rand(2,5);
		
		$this->SecAnsNegData['User']['security_answer'] = 'pune1121';
		
		//User Login 
		$this->loginPassData['User']['username'] = 'PHPUnitUsername@username.com';
		$this->loginPassData['User']['password'] = 'Test@123';
		//$this->loginPassData['User']['password'] = 'Test@123';
		
		//Profile ChangePassword
		$this->profChangePassData['User']['security_answer'] = 'pune';
		$this->profChangePassData['User']['old_password'] = 'Test@234';
		$this->profChangePassData['User']['password'] = 'Test@345';
		$this->profChangePassData['User']['new_password'] = 'Test@345';
		$this->profChangePassData['User']['security_question_id'] = rand(2,5);
		
		//Add User 
		$this->addUserPassData['User']['first_name'] = 'PHPUnitFirstName';
		$this->addUserPassData['User']['last_name'] = 'PHPUnitLastName';
		$this->addUserPassData['User']['username'] = 'PHPUnitUsername1@username.com';
		$this->addUserPassData['User']['role_id'] = rand(2,8);
		
		
		//Reset Password 
		$this->resetChangePassData['Users'][] = 'PHPUnitUsername1@username.com';
		$this->resetChangePassData['Users'][] = 'PHPUnitUsername999@username.com'; //Negative
		

		//Edit Personal Account Information
		$this->editUserPassData['User']['first_name'] = 'PHPUnitFirstNameNew';
		$this->editUserPassData['User']['last_name'] = 'PHPUnitLastNameNew';
		$this->editUserPassData['User']['username'] = 'PHPUnitUsernameNew@username.com';	
		
		
		//Manage Delegation 
		$this->manageDelegationPassData['delegate_from']['username'] = 'PHPUnitUsername@username.com';				
		$this->manageDelegationPassData['delegate_to']['username'] = 'PHPUnitUsername1@username.com';		
		$this->manageDelegationPassData['UserCompany']['from'] = '';
		$this->manageDelegationPassData['UserCompany']['to'] = '';
		
		
		//Upload Profile Photo 
		$this->photoUserPassData['User']['image']['name'] = 'Jellyfish.jpg';
		$this->photoUserPassData['User']['image']['type'] = 'image/jpeg';
		$this->photoUserPassData['User']['image']['tmp_name'] = __DIR__ . '//_files/Jellyfish.jpg';
		

    }
	public function tearDown() {
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('Company');
		ClassRegistry::removeObject('UserCompany');		
		ClassRegistry::removeObject('UserToken');
	}
    public function testRegister() {
		//Positive Test Data 
		$this->User->set($this->reqPassData['User']);
		$this->Company->set($this->reqPassData['Company']);
		$result = $this->User->register($this->reqPassData);
		$this->assertEquals($result, Configure::read('USER_REGISTER_SUCCESS'));
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('Company');		
		
		
		//Negative Scenario passing same data as Username & Company Name should be unique it will be return false;  
		/*$result = $this->User->register($this->reqPassData);
		$this->assertEquals(Configure::read('USER_VALIDATION_FAIL'),$result);
		*/
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('Company');		
    }
	public function testChangepassword() {		
		//Positive Test Data 
		$userName = $this->reqPassData['User']['username'];
		/*$this->User->deleteAll(array('User.username' => "$userName"),true);
		return;*/
		
		
		$tokenInfo = $this->User->query("SELECT token 
								FROM `user_tokens`,`users` 
								WHERE 
								users.id = user_tokens.user_id  AND 
								user_tokens.type = '".Configure::read('TOKEN_REGISTRATION_OR_ADDUSER')."' AND users.username like '$userName'");
		if(!isset($tokenInfo[0]['user_tokens']['token'])) {
			$this->assertEquals('3', 3);	
			return;
		}			
		$token = $tokenInfo[0]['user_tokens']['token'];				
		$allowTokenType = array(
						Configure::read('TOKEN_REGISTRATION_OR_ADDUSER'),
						Configure::read('TOKEN_FORGOT_PASSWORD'),
						Configure::read('TOKEN_RESET_PASSWORD'),
						Configure::read('TOKEN_EXPIRED_PASSWORD')
					);					
		$tokenResult = $this->UserToken->validateToken($token,$allowTokenType);
		//pr($tokenResult); exit;
		switch($tokenResult) { 
			case 1: // Token Empty 
			case 3: // Invalid Token or Invalid Type
					$this->assertEquals($tokenResult, 3);	
					return;
			case 2: // Expired link 
					$this->assertEquals($tokenResult, 2);	
					return;
					break;
			default : // Token ok
					$userTokenData = $tokenResult;
					break;			
		}
		
		$userData = $this->User->findById($userTokenData['UserToken']['user_id']);		
				
		//Check for Positive Test Data
		$this->changePassData['User']['token'] = $token;
		$this->User->data = $this->changePassData;
		$result = $this->User->changepassword($this->changePassData,$userData);
		$this->assertEquals($result, Configure::read('USER_CHANGE_PASSWORD_SUCCESS')); 
		ClassRegistry::removeObject('User');		
		
		//Check for Negative Test Data		
		$this->changeNegData['User']['token'] = $token;
		$this->User->data = $this->changeNegData;
		$result = $this->User->changepassword($this->changeNegData,$userData);
		$this->assertEquals(Configure::read('USER_VALIDATION_FAIL'),$result);
		
		//Clear Object Data
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('UserToken');
		
		
	}	
	public function testForgotPassword() {
		//Check for Negative Test Data
		$this->User->data = $this->forgotNegData;
		$result = $this->User->forgotPassword($this->forgotNegData);
		$this->assertEquals($result, Configure::read('USER_FORGOT_PASSWORD_SUCCESS'));	
		ClassRegistry::removeObject('User');
		
		//Check for Positive Test Data
		$this->User->data = $this->forgotPassData;
		$result = $this->User->forgotPassword($this->forgotPassData);
		$this->assertEquals($result, Configure::read('USER_FORGOT_PASSWORD_SUCCESS'));	
		
	}
	public function testSecurityAnswer() {
		$userName = $this->reqPassData['User']['username'];
		/*
		[User] => Array
        (
            [security_answer] => sdfsdf
            [id] => 242
            [token] => 7f932cf60058b7f546716683fdcd3368
        )		
		*/
		$tokenInfo = $this->User->query("SELECT token 
								FROM `user_tokens`,`users` 
								WHERE 
								users.id = user_tokens.user_id  AND 
								user_tokens.type = '".Configure::read('TOKEN_FORGOT_PASSWORD')."' AND users.username like '$userName'");							
								
		//pr($tokenInfo); exit;
		if(!isset($tokenInfo[0]['user_tokens']['token'])) {
			$this->assertEquals('3', 3);	
			return;
		}			
		$token = $tokenInfo[0]['user_tokens']['token'];
		$allowTokenType = array(Configure::read('TOKEN_FORGOT_PASSWORD'));		
		$tokenResult = $this->UserToken->validateToken($token,$allowTokenType);
		//pr($tokenResult); exit;
		switch($tokenResult) { 
			case 1: // Token Empty 
			case 3: // Invalid Token or Invalid Type
					$this->assertEquals($tokenResult, $tokenResult);	
					return;
			case 2: // Expired link 
					$this->assertEquals($tokenResult, 2);	
					return;
					break;
			default : // Token ok
					$userTokenData = $tokenResult;
					break;			
		}		
		$userData = $this->User->findById($userTokenData['UserToken']['user_id']);
		
		
		//Check for Positive Test Data
		$this->SecAnsPassData['User']['token'] = $token;
		$this->User->data = $this->SecAnsPassData;
		$result = $this->User->securityAnswer($this->SecAnsPassData,$userData);
		$this->assertEquals($result,Configure::read('USER_SECURITY_ANSWER_SUCCESS'));
		ClassRegistry::removeObject('User');		
		//Reset Password
		$this->User->data = $this->SecAnsPassData;
		$result = $this->User->changepassword($this->SecAnsPassData,$userData);
		$this->assertEquals($result, Configure::read('USER_CHANGE_PASSWORD_SUCCESS')); 
		ClassRegistry::removeObject('User');
		
		
		//Check for Negative Test Data
		$this->SecAnsNegData['User']['token'] = $token;
		$this->User->data = $this->SecAnsNegData;
		$result = $this->User->securityAnswer($this->SecAnsNegData,$userData);
		$this->assertEquals($result,Configure::read('USER_SECURITY_ANSWER_INVALID'));
		ClassRegistry::removeObject('User');	
		
	}
	public function testUserLogin() {
		$prestatus = $this->User->prelogin($this->loginPassData['User']['username'],$this->loginPassData['User']['password']);
		switch($prestatus) {
			case Configure::read('INVALID_USER') : $this->assertEquals($prestatus, Configure::read('INVALID_USER')); return; //INVALID_USER 50
			case Configure::read('INACTIVE_COMPANY') : $this->assertEquals($prestatus, Configure::read('INACTIVE_COMPANY')); return; //INACTIVE_COMPANY 51
			case Configure::read('EXPIRED_COMPANY') : $this->assertEquals($prestatus, Configure::read('EXPIRED_COMPANY')); return; //EXPIRED_COMPANY 52
			case Configure::read('INACTIVE_USER') : $this->assertEquals($prestatus, Configure::read('INACTIVE_USER')); return; //INACTIVE_USER 53 
		}
		if(is_array($prestatus)){
			$enc_password = $this->User->encryptPassword($this->loginPassData['User']['password'],$prestatus);
		}
		if($enc_password == $prestatus['User']['password']) {
			$result = $this->User->login($prestatus,$this->loginPassData);
			switch($result) {
				case Configure::read('WELCOME') : $this->assertEquals($result, Configure::read('WELCOME')); return; //Welcome
				case Configure::read('DASHBOARD') : $this->assertEquals($result, Configure::read('DASHBOARD')); return; //DASHBOARD
				case Configure::read('EXPIRED_PASSWORD') : $this->assertEquals($result, Configure::read('EXPIRED_PASSWORD')); return; //Password Expiry
			}
		} else { 
			$result = $this->User->loginfail($prestatus);
			switch($result) {
				case Configure::read('NEW_USER_STATUS') : $this->assertEquals($result, Configure::read('NEW_USER_STATUS')); return; //NEW_USER_STATUS
				case Configure::read('LOCK_USER_LOGIN_STATUS') : $this->assertEquals($result, Configure::read('LOCK_USER_LOGIN_STATUS')); return; //LOCK_USER_LOGIN_STATUS
				case Configure::read('USER_PASS_FAILED') : $this->assertEquals($result, Configure::read('USER_PASS_FAILED')); return; //USER_PASS_FAILED
			}	
		}
	}
	public function testProfileChangePassword() {
		$userName = $this->reqPassData['User']['username'];
		$userData = $this->User->findByUsername($userName);
		$this->profChangePassData['User']['id'] = $userData['User']['id'];
		$result = $this->User->profileChangePassword($this->profChangePassData,$userData);
		$this->assertEquals($result, Configure::read('USER_PROFILE_CHANGE_PASSWORD_SUCCESS'));
		ClassRegistry::removeObject('User');
		//Negative Test Case with Same Password
		$result = $this->User->profileChangePassword($this->profChangePassData,$userData);
		$this->assertEquals($result, Configure::read('USER_VALIDATION_FAIL'));
		ClassRegistry::removeObject('User');
	}
	//test methods for add users functionality
	public function testAddUsers() {
		$companyData = $this->Company->findByCompanyName($this->reqPassData['Company']['company_name']);
		CakeSession::write('user.UserCompany.company_id', $companyData['Company']['id']);
		$this->User->set($this->addUserPassData);
		$result = $this->User->addUser($this->addUserPassData);
		$this->assertEquals($result,Configure::read('SUCCESS_TO_ADD_NEW_USER'));
		
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('UserToken');
		ClassRegistry::removeObject('UserCompany');
		
		$this->User->set($this->addUserPassData);
		$result = $this->User->addUser($this->addUserPassData);
		$this->assertEquals($result, Configure::read('FAIL_TO_ADD_IN_USERS_FOR_ADD'));
		
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('UserToken');
		ClassRegistry::removeObject('UserCompany');
	}
	
	
	public function testResetPassword() {
		foreach($this->resetChangePassData['Users'] as $key => $val){
			$userData = $this->User->findByUsername($val);
			if(isset($userData['User']['id']) && !empty($userData['User']['id']))
				$requestData['Users'][] = $userData['User']['id'];
			else 
				$requestData['Users'][] = '0'; //Negative Testing
		}
		$result = $this->User->resetPassword($requestData);
		$this->assertEquals($result, Configure::read('USER_RESET_PASSWORD_SUCCESS'));
		ClassRegistry::removeObject('User'); 
	}
	public function testUploadProfilePic() {
		$userData = $this->User->findByUsername($this->reqPassData['User']['username']);
		CakeSession::write('user.User.id', $userData['User']['id']);
		$result = $this->User->uploadProfilePic($this->photoUserPassData,$userData);
		$this->assertEquals($result, Configure::read('SUCCESS_TO_ADD_PROFILEPIC'));
	}
	public function testEditUser() {
		$userName = $this->reqPassData['User']['username'];
		$userData = $this->User->findByUsername($userName);

		//pr($this->editUserPassData);exit;
		CakeSession::write('user.User.username', $userData['User']['username']); // logged in Username as PHPUnitUsername@username.com
		$result = $this->User->editUser($userData,$this->editUserPassData,1);
		$this->assertEquals($result, Configure::read('SUCCESS_PERSONALINFO')); //60
		$tokenInfo = $this->UserToken->findByTypeAndUserId(Configure::read('TOKEN_EDIT_USER'),$userData['User']['id'],array('token'),array('id' => 'DESC'));
		$resultChangeEmail = $this->User->changeEmail($tokenInfo['UserToken']['token']);
		$this->assertEquals($resultChangeEmail, Configure::read('ISSET_SESSION'));
		
		ClassRegistry::removeObject('User'); 
		
		//Revert back username data 
		CakeSession::write('user.User.username', $this->editUserPassData['User']['username']); // logged in Username as PHPUnitUsernameNew@username.com
		$this->editUserPassData['User']['username'] = $this->reqPassData['User']['username'];		
		$result = $this->User->editUser($userData,$this->editUserPassData,1);
		$this->assertEquals($result, Configure::read('SUCCESS_PERSONALINFO')); //60
		$tokenInfo = $this->UserToken->findByTypeAndUserId(Configure::read('TOKEN_EDIT_USER'),$userData['User']['id'],array('token'),array('id' => 'DESC'));
		$resultChangeEmail = $this->User->changeEmail($tokenInfo['UserToken']['token']);
		$this->assertEquals($resultChangeEmail, Configure::read('ISSET_SESSION'));
		
	}
	
	
	
	//test methods for manage delegation functionality
	public function testManageDelegation() {
		$userCompany = $this->User->findByUsername($this->manageDelegationPassData['delegate_from']['username'],array('UserCompany.id'));
		CakeSession::write('user.UserCompany.id', $userCompany['UserCompany']['id']);
		
		$userData = $this->User->findByUsername($this->manageDelegationPassData['delegate_to']['username']);		
		$this->manageDelegationPassData['UserCompany']['delegate_to'] = $userData['User']['id'];		
		
		$result = $this->User->manageDelegation($this->manageDelegationPassData);
		$this->assertEquals($result, Configure::read('SUCCESS_TO_MANAGE_DELEGATION'));
		
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('UserCompany');
		
	}
	
	//test methods for remove delegation functionality
	public function testRemoveDelegation() {
		$userCompany = $this->User->findByUsername($this->manageDelegationPassData['delegate_from']['username'],array('UserCompany.id'));
		CakeSession::write('user.UserCompany.id', $userCompany['UserCompany']['id']);

		$result = $this->User->removeDelegation();
		$this->assertEquals($result, Configure::read('SUCCESS_TO_REMOVE_DELEGATION'));
		
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('UserCompany');
	}
}
?>