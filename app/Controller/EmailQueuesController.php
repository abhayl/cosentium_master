<?php

class EmailQueuesController extends AppController {
	public $name = 'EmailQueues';
	public $components = array('Notification');	
	/**
	 * @name 			index 
	 * @description		         			
	 * @parm	        null
	 * @return	        null	 
	 */	
	public function index($emailQueueID=null) {
		$sessData = $this->Session->read('Auth');
		if(!empty($emailQueueID)) {
			$emailQueueData = $this->EmailQueue->getEmailQueue($emailQueueID);
			$this->set('view','1');
			$this->set('emailQueueData',$emailQueueData);
			return;
		}
		//pr($sessData); 
		if(!isset($sessData['User'])) {
			$this->redirect('/users/login');
		}		
		$this->set('userData',$sessData);
		//pr($this->request->data);
		if ($this->request->is('post')) {
			$this->EmailQueue->data = $this->request->data;
			if($this->EmailQueue->insertEmailQueue($this->request->data)) {			
				$this->set('succeMessage',__('Email Queue Added successfully'));
			} else { 
				$this->set('succeMessage',__('Email Queue have some error.'));
			}
			
		}		
	}
	
	/**
	 * @name 			index 
	 * @description		         			
	 * @parm	        null
	 * @return	        null	 
	 */	
	public function sendEmail() {
		/*$sessData = $this->Session->read('Auth');
		//pr($sessData); 
		if(!isset($sessData['User'])) {
			$this->redirect('/users/login');
		}		
		$this->set('userData',$sessData);
		*/		
		
		$resultData = $this->EmailQueue->sendEmailQueue();
		
		$this->set('Total',__('Total Email '.$resultData['total_emails']));
		$this->set('Succ',__('Sent Email '.$resultData['total_emails_sent']));
		$this->set('Fail',__('Failed Email '.$resultData['total_emails_fails']));
			
		
	}
	
	
}