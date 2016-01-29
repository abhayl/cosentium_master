<?php 
App::uses('Masterdoc', 'Model');

class MasterdocTest extends PHPUnit_Framework_TestCase {
	public $fixtures = array('app.masterdoc');
	//public $autoFixtures = false;
    public function setUp() {
		parent::setUp();
		//Initialise Models
		$this->User = ClassRegistry::init('User');		
		$this->Company = ClassRegistry::init('Company');
		$this->UserCompany = ClassRegistry::init('UserCompany');		
		$this->UserToken = ClassRegistry::init('UserToken');
		$this->Masterdoc = ClassRegistry::init('Masterdoc');
		
		//Sample Test Data Positive Data
		//Register 
		$this->reqPassData['Company']['company_name'] = 'PHPUnitCompany';
		$this->reqPassData['User']['username'] = 'PHPUnitUsername@username.com';
		
		
		$this->addMasterDocPassData['attach'] = 'Attach File';
		$this->addMasterDocPassData['MasterDoc']['myText'] = 'test.docx';
		$this->addMasterDocPassData['MasterDoc']['Submit'] = '';
		$this->addMasterDocPassData['MasterDoc']['s3_key']['name'] = 'test.docx';
		$this->addMasterDocPassData['MasterDoc']['s3_key']['type'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
		$this->addMasterDocPassData['MasterDoc']['s3_key']['tmp_name'] = __DIR__ . '//_files/test.docx';
		//$this->addMasterDocPassData['MasterDoc']['s3_key']['tmp_name'] = 'C:\wamp\tmp\php49CE.tmp';
		//$this->addMasterDocPassData['MasterDoc']['s3_key']['error'] = 0;
		//$this->addMasterDocPassData['MasterDoc']['s3_key']['size'] = 20744;

    }
	
	public function tearDown() {
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('Company');
		ClassRegistry::removeObject('UserCompany');		
		ClassRegistry::removeObject('UserToken');
	}
    public function testuploadMasterDoc() {
		//write session variable
		$userData = $this->User->findByUsername($this->reqPassData['User']['username']);
		CakeSession::write('user.User.id', $userData['User']['id']);
		CakeSession::write('user.Company.company_name', $userData['Company']['company_name']);
		CakeSession::write('user.Company.prefix', $userData['Company']['prefix']);
		
		$result = $this->Masterdoc->uploadMasterDoc($this->addMasterDocPassData);
		$this->assertNotEmpty($result['files_id']);
		
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('Company');		
    }
}
?>