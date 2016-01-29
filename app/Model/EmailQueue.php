<?php 
// app/Model/EmailQueue.php
App::import('Component', 'Notification');
App::import('Model', 'User');
App::import('Model', 'Company');
class EmailQueue extends AppModel {
	public $name = 'EmailQueue';
	public $validate = array(
		'user_id' => array(
			'rule1' => array('rule' => array('notEmpty'), 'message' => 'You must enter a value.'),
			'rule2' => array('rule' => array('checkUserId'), 'message' => 'You must enter a value.'),
		),
		'company_id' => array(
			'rule1' => array('rule' => array('notEmpty'), 'message' => 'You must enter a value.'),
			'rule2' => array('rule' => array('checkCompanyId'), 'message' => 'You must enter a value.'),
		),
		'to_email' => array(
			'rule1' => array('rule' => array('notEmpty'), 'message' => 'You must enter a value.'),
			'rule2' => array('rule'    => '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/', 'message' => 'Enter a properly formed email address.  For example john@company.com'),
		),
		'cc_user_id' => array(
			'rule1' => array('rule' => array('notEmpty'), 'message' => 'You must enter a value.'),
			'rule2' => array('rule' => array('checkCcUserId'), 'message' => 'You must enter a value.'),
		),
		'email_subject_line' => array(
			'rule1' => array('rule' => array('notEmpty'), 'message' => 'A subject line is required.'),
		),
		'email_body_text' => array(
			'rule1' => array('rule' => array('notEmpty'), 'message' => 'Message content is required.'),
		),
		'add_cc' => array( 
			'rule1' => array ('rule' => array('checkCC'),'message' => 'Entry must be in the form of an email address.  If entering multiple email addresses, these must be separated by commas.')
		),
		'bcc' => array( 
			'rule1' => array ('rule' => array('checkBCC'),'message' => 'Entry must be in the form of an email address.  If entering multiple email addresses, these must be separated by commas.')
		),
    );
	/**
	 * @name 			checkBCC 
	 * @description		Check Field           			
	 * @parm	        $data
	 * @return	        true/false	 
	 */	 
	public function checkBCC($data) {
		if(empty($data['bcc']))
			return true;
		$contactlist = str_replace("\r\n", "\n", $data['bcc']);
		$csv_array= explode(",",$contactlist);
		$csvnum=count($csv_array);

		for ($n=0;$n<$csvnum;$n++) {
			trim($csv_array[$n]);
			$emails = $csv_array[$n];
			if(!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", $emails)){
				//$badMail = "You've got an invalid email: $emails";
				//echo $badMail . "\t";
				return false;
			}
		}
		return true;
    }
	
	/**
	 * @name 			checkCC 
	 * @description		Check Field          			
	 * @parm	        $data
	 * @return	        true/false	 
	 */	 
	public function checkCC($data) {
		if(empty($data['add_cc']))
			return true;
		$contactlist = str_replace("\r\n", "\n", $data['add_cc']);
		$csv_array= explode(",",$contactlist);
		$csvnum=count($csv_array);

		for ($n=0;$n<$csvnum;$n++) {
			trim($csv_array[$n]);
			$emails = $csv_array[$n];
			if(!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", $emails)){
				//$badMail = "You've got an invalid email: $emails";
				//echo $badMail . "\t";
				return false;
			}
		}
		return true;
    }
	/**
	 * @name 			checkUserId 
	 * @description		Check User_id exist in DB or not
	 * @parm	        $data
	 * @return	        
	 */
	public function checkUserId($data){
		$User = new User();
		$userCnt = $User->find('count', array(
		'fields' => 'DISTINCT User.id',
		'conditions' => array('User.id ' => $data['user_id'])
			));
		/*$log = $User->getDataSource()->getLog(false, false);
		debug($log);*/
		if($userCnt != 0) {
				return true;
		} else
				return false;
	}
	/**
	 * @name 			checkCcUserId 
	 * @description		Check cc_user_id exist in DB or not
	 * @parm	        $data
	 * @return	        
	 */
	public function checkCcUserId($data){
			$User = new User();
			$userCnt = $User->find('count', array(
			'fields' => 'DISTINCT User.id',
            'conditions' => array('User.id ' => $data['cc_user_id'])
				));
			/*$log = $User->getDataSource()->getLog(false, false);
			debug($log);*/
            if($userCnt != 0) {
                    return true;
            } else
                    return false;
	}
	/**
	 * @name 			checkCompanyId 
	 * @description		Check company_id exist in DB or not
	 * @parm	        $data
	 * @return	        
	 */
	public function checkCompanyId($data){
			$Company = new Company();
			$userCnt = $Company->find('count', array(
			'fields' => 'DISTINCT Company.id',
            'conditions' => array('Company.id ' => $data['company_id'])
				));
			/*$log = $User->getDataSource()->getLog(false, false);
			debug($log);*/
            if($userCnt != 0) {
                    return true;
            } else
                    return false;
	}
	
	/**
	 * @name 			insertEmailQueue 
	 * @description		Insert Email Queue
	 * @parm	        $queueData
	 * @return	        
	 */
	function insertEmailQueue($queueData) {
		if($this->validates($queueData)) {
			$this->create();	
			 if($this->save($queueData)) {
				return $this->getLastInsertID();
			 } else { 
				return false;
			 }			
		} else {
			return false;
		}	
	}
	/**
	 * @name 			getEmailQueue 
	 * @description		Get Email Queue Details
	 * @parm	        $queueID
	 * @return	        
	 */
	function getEmailQueue($queueID) {
		$emailQueueData = $this->findById($queueID);
		if (empty($emailQueueData)) {
			return false;
		}
		return $emailQueueData;	
	}
	
	
	/**
	 * @name 			sendEmailQueue 
	 * @description		Sending Email(s)
	 * @parm	        null
	 * @return	        
	 */
	function sendEmailQueue() {
		//$pendingEmailQueues = $this->findAllByEmailStatus('0',null,null,10);
		$pendingEmailQueues = $this->query("
			SELECT EmailQueue.*, User.username
			FROM email_queues EmailQueue, users User
			WHERE 
				(EmailQueue.email_status = '0' OR EmailQueue.email_status = '2')
			AND EmailQueue.cc_user_id = User.id
			
			limit 0,".Configure::read('EMAI_QUEUE_QUERY_LIMIT')."				
		"); 
		
		$resultData = array();
		$resultData['total_emails'] = count($pendingEmailQueues);
		$successEmailSentCnt = 0;
		$failedEmailSentCnt = 0;
		foreach($pendingEmailQueues as $key=>$val) { 
			$userData = array();
			$userData['EmailQueue'] = $val['EmailQueue'];
			if(!empty($val['EmailQueue']['add_cc'])) {
				$userData['EmailQueue']['cc_emails'] = array_map('trim',explode(",",$val['EmailQueue']['add_cc']));  // Addition CC comma separated explode(",",$val['EmailQueue']['add_cc']);
			}
			if($val['EmailQueue']['to_email'] != $val['User']['username'])
				$userData['EmailQueue']['cc_emails'][] = $val['User']['username']; // SalesPerson Email ID
			$userData['EmailQueue']['bcc'] = array_map('trim',explode(",",$val['EmailQueue']['bcc']));
	
			$userData['EmailQueue']['email_body_text'] = nl2br($userData['EmailQueue']['email_body_text']);
			//pr($userData); exit;
			set_time_limit(0);
			$Notification = new  NotificationComponent();
			$result = $Notification->sendMail(Configure::read('EMAIL_TEMP_GENERIC'), $userData);
			$this->id = $val['EmailQueue']['id'];
			if($result) { 
				// success update status as 1 & sent_dt				
				$this->set(array(
							'email_status' => Configure::read('EMAI_QUEUE_SENT_STATUS'),
							'sent_dt' => date(Configure::read('DB_DATE_FORMAT'))
						));
				$successEmailSentCnt ++;
			} else { 
				//Increment failed_count & update status as 2
				$this->set(array(
						'email_status' => Configure::read('EMAI_QUEUE_FAILED_STATUS'),
						'failed_count' => $val['EmailQueue']['failed_count'] + 1
				));
				$failedEmailSentCnt++;
			}
			$this->save();			
		}
		$resultData['total_emails_sent'] = $successEmailSentCnt;
		$resultData['total_emails_fails'] = $failedEmailSentCnt;
		return $resultData;
	}
	
	
}


