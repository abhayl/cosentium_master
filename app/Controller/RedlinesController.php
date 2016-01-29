<?php
/**
* file - DealController.php
* author - Abhay Lalpotu <abhayl@cybage.com>
* Created Date - 28-08-2013
* Modified By - 
* Tables Used - deals, master, user, email
*/
class RedlinesController extends AppController {
    
    public $helpers = array('Html', 'Form', 'Session');
    public $uses = array('Deal', 'MasterDoc', 'User', 'Redline', 'EmailQueue');
       
    
	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index() {
		$this->redirect(array('controller' =>'users', 'action' => 'login'));
	}
        
    /**
	 * @name 			details 
	 * @description		show deal detail      			
	 * @parm	        deal_id
	 * @return	        	 
	 */
    public function upload($dealId=null) {
		if($this->request->is('post')) {
			$dealInfo = $filesize = $submitted = $filename = $stage = $turn = NULL;
			$redlineData = array('files_id' => NULL);
			if( isset($this->request->data['dealId'])  && !empty($this->request->data['dealId']) ) {
				$dealRefId = $this->request->data['dealId'];
			} else {
				$this->Redline->data = $this->request->data;
				$dealRefId = $this->request->data['Redline']['dealRefId'];
				if ($this->Redline->validates()) { 
					$submitted = 1;
					$stage = $this->Redline->uploadRedline($this->request->data);
					if ( $stage == Configure::read('RECEIVE_DEAL_FAIL')) {
						$this->redirect('/companies/message/fail');
					}
					$filesize = $this->request->data['Redline']['s3_key']['size']/1000;
					$filename = $this->request->data['Redline']['s3_key']['name'];
					$dealInfo = $this->Redline->getDealInfo($dealRefId);
					$turn = $this->Redline->getRedlineTurn($dealRefId);
				}	
			}
			$this->Session->write('receiveRedlineId', $dealRefId);
			$this->set('title_for_layout', __('Upload Redline'));
			$this->set('dealRefId', $dealRefId);
			$this->set('filesize', $filesize);
			$this->set('dealInfo', $dealInfo);
			$this->set('filename', $filename);
			$this->set('stage', $stage);
			$this->set('turn', $turn);
			$this->set('submitted', $submitted);
		} else {
			$this->redirect('/deals/view');
		}
    }
	
	
	public function detail($dealId=null) {
		$this->set('title_for_layout', __('Redline Review'));
		$data = $this->Redline->executeDiff(CakeSession::read('user.Company.prefix'));
		// Call to get the total changes
		$this->set('changes', $data);
		$this->Session->delete('receiveRedlineId');
		$this->Session->delete('lastInsertedRedlineId');
    }
	
}
?>
