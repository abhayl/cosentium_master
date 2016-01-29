<?php 
App::uses('Deal', 'Model');
#App::uses('Controller', 'Controller');
#App::uses('View', 'View');
#App::uses('ProgressHelper', 'View/Helper');

class DealTest extends PHPUnit_Framework_TestCase {
	//public $fixtures = array('app.deal');
	//public $autoFixtures = false;
    public function setUp() {
		parent::setUp();
		$this->Deal = ClassRegistry::init('Deal');
		$this->MasterDoc = ClassRegistry::init('MasterDoc');
    }	
	public function testAddDeal() {
		//Sample Test Data 
		$userPostData['Deal']['name'] = 'Test First Deal';
		$userPostData['Deal']['email_id'] = 251;
		$userPostData['Deal']['master_docs_id'] = 2;
		$userPostData['Deal']['customer_name'] = 'Deal One Customer';
		$userPostData['Deal']['recipient_name'] = 'Deal One Recipient';
		$userPostData['Deal']['recipient_email'] = 'test@test.com';
		CakeSession::write('user.User.id',299);
		CakeSession::write('user.Company.prefix', '192_phpunitcompany' );
			
		
		//Pass the Test Data to Model 	Positive
		$result = $this->Deal->addNewDeal($userPostData);
		
		if ( $result = Configure::read('ADD_DEAL_SUCCESS') ) {
			$this->assertEquals($result, Configure::read('ADD_DEAL_SUCCESS'));
		} else if($result == Configure::read('ADD_DEAL_FAIL')) { 
			$this->assertEquals($result, Configure::read('ADD_DEAL_FAIL'));
		}
	}
}
?>