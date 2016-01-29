<?php 
App::uses('Company', 'Model');
#App::uses('Controller', 'Controller');
#App::uses('View', 'View');
#App::uses('ProgressHelper', 'View/Helper');

class CompanyTest extends PHPUnit_Framework_TestCase {
	public $fixtures = array('app.company');
	//public $autoFixtures = false;
    public function setUp() {
		parent::setUp();
		$this->Company = ClassRegistry::init('Company');	
		$this->User = ClassRegistry::init('User');	
		$this->UserToken = ClassRegistry::init('UserToken');	
		
		
    }	
	public function testRegister() {
		//Sample Test Data 
		$userPostData['User']['first_name'] = 'PHPUnitFirstName1';
		$userPostData['User']['last_name'] = 'PHPUnitLastName';
		$userPostData['User']['username'] = 'test_username'.rand(1,999).'@username.com';
		$userPostData['Company']['phone1'] = '123';
		$userPostData['Company']['phone2'] = '123';
		$userPostData['Company']['phone3'] = '1234';
		$userPostData['Company']['job_title'] = 'job_title';
		$userPostData['Company']['company_name'] = 'company_name'.rand(1,999);		
		$inputEmpStrenVal = array_rand($this->Company->employeeStrengthVal,1);		
		$userPostData['Company']['employee_strength'] = "$inputEmpStrenVal";
		$userPostData['Company']['terms'] = 1;	
		
		//Pass the Test Data to Model 	Positive
		$result = $this->User->register($userPostData);
		$this->assertEquals($result, Configure::read('USER_REGISTER_SUCCESS'));
		
		$userPostData['User']['first_name'] = 'PHPUnitFirstName1';
		$userPostData['User']['last_name'] = 'PHPUnitLastName';
		$userPostData['User']['username'] = 'test_username'.rand(1,999).'@username.com';
		$userPostData['Company']['phone1'] = '123';
		$userPostData['Company']['phone2'] = '123';
		$userPostData['Company']['phone3'] = '1234';
		$userPostData['Company']['job_title'] = 'job_title';
		$userPostData['Company']['company_name'] = 'company_name'.rand(1,999);		
		$inputEmpStrenVal = array_rand($this->Company->employeeStrengthVal,1);		
		$userPostData['Company']['employee_strength'] = "$inputEmpStrenVal";
		$userPostData['Company']['terms'] = 1;	
		
			
		} else if($result == Configure::read('USER_FAIL')) { 
			$this->assertEquals($result, Configure::read('USER_FAIL'));
		}
		
		//$this->testChangepassword($userPostData['User']['username']);
		$this->testChangepassword('test_username788@username.com');
		exit;
	}
	
	
	public function testChangepassword($userName) {
		$tokenInfo = $this->User->query("SELECT token 
								FROM `user_tokens`,`users` 
								WHERE 
								users.id = user_tokens.user_id  AND 
								user_tokens.type = 'TOKEN_REGISTRATION_OR_ADDUSER' AND users.username like '$userName'");
		pr($tokenInfo); 
		$token = $tokenInfo[0]['user_tokens']['token'] ;
		$allowTokenType = array(
						Configure::read('TOKEN_REGISTRATION_OR_ADDUSER'),
						Configure::read('TOKEN_FORGOT_PASSWORD'),
						Configure::read('TOKEN_RESET_PASSWORD'),
						Configure::read('TOKEN_EXPIRED_PASSWORD')
					);					
		$tokenResult = $this->UserToken->validateToken($token,$allowTokenType);
		pr($tokenResult);
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
		pr($userTokenData);
		$userData = $this->User->findById($userTokenData['UserToken']['user_id']);		
		pr($userData);
		
		//sample test data
		$temp_pass = 'Test@'.rand(1,999);
		$temp_answer = 'Test@'.rand(1,999);
		$userPostData['User']['password'] = $temp_pass;
		$userPostData['User']['new_password'] = $temp_pass;
		$userPostData['User']['security_question_id'] = rand(2,5);
		$userPostData['User']['security_answer'] = $temp_answer;
		pr($userPostData);
		
		//$result = $this->User->changepassword($userPostData,$userData);
		
	}
    /*public function testRegister1() {
		//$this->Company->loadFixtures('Company');
		$data['company_name'] = '';
		$result = $this->Company->checkEmptyCompanyName($data['company_name']);
		$this->assertEquals(false, $result);
		
		$data['company_name'] = 'Cybage123';
		$result = $this->Company->checkEmptyCompanyName($data['company_name']);
		$this->assertEquals(true, $result);
		
		$data['phone'] = '';
		$result = $this->Company->checkEmptyPhone($data['phone']);
		$this->assertEquals(false, $result);
		
		$data['phone'] = '0123456789';
		$result = $this->Company->checkEmptyPhone($data['phone']);
		$this->assertEquals(false, $result);
		
		$data['job_title'] = '';
		$result = $this->Company->checkEmptyJobTitle($data['job_title']);
		$this->assertEquals(false, $result);
		
		$data['job_title'] = 'JOB TITLE';
		$result = $this->Company->checkEmptyJobTitle($data['job_title']);
		$this->assertEquals(true, $result);
		
		$data['employee_strength'] = "";
		$result = $this->Company->checkEmpSize($data['employee_strength']);
		$this->assertEquals(false, $result);
		
		$data['employee_strength'] = '16-50';
		$result = $this->Company->checkEmpSize($data['employee_strength']);
		$this->assertEquals(true, $result);
		
		$data['employee_strength'] = "";
		$result = $this->Company->checkEmpSize($data['employee_strength']);
		$this->assertEquals(false, $result);
		
		$data['terms'] = '0';
		$result = $this->Company->checkTerms($data['terms']);
		$this->assertEquals(false, $result);
		
		$data['terms'] = '1';
		$result = $this->Company->checkTerms($data['terms']);
		$this->assertEquals(true, $result);
		
		
		
		$data['phone'] = 'XYXYX';
		$result = $this->Company->checkEmptyPhone($data['phone']);
		$this->assertEquals(false, $result);
		
		
		
		
		
		
		$data['terms'] = 0;
		$result = $this->Company->checkTerms($data['terms']);
		$this->assertEquals(false, $result);

	
		$data['terms'] = 1;
		$result = $this->Company->checkTerms($data['terms']);
		$this->assertEquals(1, $result);
		
		echo $result;
		exit();

		
		$data['company_name'] = 'Cybage';
		$result = $this->Company->checkCompany($data['company_name']);
		echo $result;
		$this->assertEquals(1, $result);
		
		$data['company_name'] = 'CybageTest';
		$result = $this->Company->checkCompany($data['company_name']);
		echo $result;
		exit();
		$this->assertEquals(false, $result);
		
    }*/
}
?>