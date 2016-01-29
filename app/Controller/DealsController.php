<?php
/**
* file - DealController.php
* author - Abhay Lalpotu <abhayl@cybage.com>
* Created Date - 28-08-2013
* Modified By - 
* Tables Used - deals, master, user, email
*/
class DealsController extends AppController {
    
    public $helpers = array('Html', 'Form', 'Session');
    public $uses = array('Deal', 'MasterDoc', 'User', 'Redline', 'EmailQueue');
       
	var $paginate = array(
        'order' => array(
            'Deal.created' => 'asc'
        )
    );
    
	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index() {
		$this->redirect(array('controller' =>'users', 'action' => 'login'));
	}
    /**
    * purpose : Listing of Deals per company 
    */
    public function view($msg=null) {
		if(!empty($msg)) {
			$this->set('successMessage', __('Your message and document was sent.'));
		}

		$sortImage = array(
							'name' => Configure::read('DEFAULT_SORT_ARROW'),
							'username' => Configure::read('DEFAULT_SORT_ARROW'),
							'User.last_name' => Configure::read('DEFAULT_SORT_ARROW'),
							'created' => Configure::read('DEFAULT_SORT_ARROW'),
							'modified' => Configure::read('DEFAULT_SORT_ARROW'),
							'stage' => Configure::read('DEFAULT_SORT_ARROW'),
							'turn' => Configure::read('DEFAULT_SORT_ARROW'),
							'stage' => Configure::read('DEFAULT_SORT_ARROW')
							);
							
		if (!empty($this->params['named']['direction'])) {
			if ($this->params['named']['direction'] == 'asc') {
				$sortImage[$this->params['named']['sort']] = Configure::read('DEFAULT_SORT_ARROW_TOGGLE');
			} else {
				$sortImage[$this->params['named']['sort']] = Configure::read('DEFAULT_SORT_ARROW');
			}
		}

		//Default settings
	    $this->set('sortImage', $sortImage);
		$this->set('selectedDealOption', 1);
		$this->set('selectedRecordsOption', Configure::read('PAGINATION_LIMIT'));
       
	   // Setting the selected options in the drop downs (deal/records) based on selection
		if($this->request->is('post')) {
		$this->Session->write('dealRecordsPerPage', $this->request->data['Deal']['records_per_page']);
		$this->Session->write('dealType', $this->request->data['Deal']['deal_type']);
			$this->pageNumber = $this->request->data['Deal']['records_per_page'];
            $this->paginate['limit'] = $this->request->data['Deal']['records_per_page'];
        } elseif ($this->Session->read('dealRecordsPerPage') != '') {
			$this->paginate['limit'] = $this->Session->read('dealRecordsPerPage');
			$this->set('selectedRecordsOption', $this->Session->read('dealRecordsPerPage'));
		} else {
            $this->paginate['limit'] = Configure::read('PAGINATION_LIMIT');
        }
		
		if ($this->Session->read('dealType') != '') {
			$this->set('selectedDealOption', $this->Session->read('dealType'));
		} else {
			$this->Session->write('dealType', 1);
		}
		
		// Setting drop down details from the config file
		$this->set('title_for_layout', __('Deal Listing'));
        $this->set('LoggedInUserId', $this->Session->read('user.User.id'));
        $this->set('padinationDd', Configure::read('PAGINATION_DROPDOWN'));
        $this->set('viewDealDd', Configure::read('VIEW_DEAL_DROPDOWN'));
		$this->set('deals', $this->paginate('Deal', $this->Deal->getDealCondition($this->Session->read('dealType'))));
    }
    
    /**
	 * @name 			add 
	 * @description		Add a New deal page     	 
	 */
    public function add() {
		$savedData = $this->Session->read('DealDataUnsaved.Deal');
		
		if( isset( $savedData )) {
			$selectedMaster = $savedData['master_docs_id'];
			$this->set('UnSaved', $savedData);
		} else {
			$selectedMaster = 0;
			$this->set('UnSaved', array('name' => '', 'customer_name' => '', 'recipient_name' => '', 'recipient_email' => ''));
		}	
		if ($this->request->is('post')) {
			// If cancel request then redirect user to Deal listing page and clear the session deal data
			if(array_key_exists('cancel', $this->request->data)) {
				$this->Session->delete('DealDataUnsaved');
				$this->redirect(array('action' => 'view'));
			}

			$this->Deal->data = $this->request->data;
			$dealUnsavedData = $this->request->data;
			if( $this->Deal->validates()) {
				// transfer control to the email controller
				$masterData = $this->MasterDoc->find('first', array('conditions' => array('MasterDoc.id' => $dealUnsavedData['Deal']['master_docs_id'])));
				$days =  $masterData['MasterDoc']['validity'];
				$date = strtotime(date(Configure::read('DB_DATE_FORMAT')));
				$dealUnsavedData['Deal']['expiry'] = date(Configure::read('DB_DATE_FORMAT'), strtotime("+ ". $days." day", $date));
				$this->Session->write('DealDataUnsaved', $dealUnsavedData);	
				$this->redirect('deal_email');
			} else {
				$this->Session->delete('DealDataUnsaved');
			}
        }
		
		// Assigning default values to the form
		$MaseterDocs = $this->MasterDoc->find('all', array('fields' => array('id', 'name'), 'conditions' => array(
								'MasterDoc.available_after_dt <=' => date(Configure::read('DB_DATE_FORMAT'), strtotime(date(Configure::read('DB_DATE_FORMAT')))),
								'OR' => array(
										array('MasterDoc.available_until_dt >=' => date(Configure::read('DB_DATE_FORMAT'), strtotime(date(Configure::read('DB_DATE_FORMAT'))))),
										array('MasterDoc.available_until_dt =' => null))
							)));					
		$MastersDocsDd = array();
		$MastersDocsDd[0] = __('--No Selection--');
		foreach ($MaseterDocs as $Master) {
			$MastersDocsDd[$Master['MasterDoc']['id']] = $Master['MasterDoc']['name'];
		}
		$this->set('title_for_layout', __('ADD New Deal'));
		$this->set('selectedOption', $selectedMaster);		
		$this->set('MastersDocsDd', $MastersDocsDd);	
    }
	
	/**
	 * @name 			details 
	 * @description		show deal detail      			
	 * @parm	        deal_id
	 * @return	        	 
	 */
    public function detail($dealId=null) {
		$dealId = $this->request->data['dealId'];
		$dealData = $this->Deal->findById($dealId);
		if (!empty($dealData)) {
			$this->set('title_for_layout', __('Deal Details'));
			$this->set('LoggedInUserId', $this->Session->read('user.User.id'));
			$this->set('redlineTable', $this->Redline->name);
			$this->set('detail', $this->Deal->getDealDetails($dealId));
			$this->set('redlines', $this->Deal->getDealHistory($dealId));
		} else {
			$this->redirect('view');
		}
    }
	
	/**
	 * @name 			deal_email 
	 * @description		         			
	 * @parm	        $emailQueueID
	 * @return	        	 
	 */	
	public function deal_email($emailQueueID=null) {
		
		$sessData = $this->Session->read('Auth');
		$sessDealData = $this->Session->read('DealDataUnsaved');
	
		if(!empty($emailQueueID) || isset($this->request->data['emailId']) || !empty($this->request->data['emailId'])) {
			$emailQueueID = $this->request->data['emailId'];
			$dealData = $this->Deal->getDealDetailsEmailID($emailQueueID);
			$emailQueueData = $this->EmailQueue->getEmailQueue($emailQueueID);
			$userData = $this->User->findById($emailQueueData['EmailQueue']['user_id']);			
			$this->set('view','1');
			$this->set('title_for_layout', __('View Deal Email'));
			$this->set('emailQueueData',$emailQueueData);
			$this->set('dealData',$dealData);
			$this->set('userData',$userData);				
			return;
		}
		if(!isset($sessData['User'])) {
			$this->redirect('/users/login');
		}		
		$maseterDocsData = $this->MasterDoc->findById($sessDealData['Deal']['master_docs_id']);
		
		$sessDealData['Deal']['master_docs_file'] = $maseterDocsData['File']['s3_key'];
		$sessDealData['Deal']['master_docs_name'] = $maseterDocsData['MasterDoc']['name'];
                $sessDealData['Deal']['master_docs_file_path'] = $maseterDocsData['File']['s3_uri_path'];
		
                //Start Code to save file from S3 server to local Server
                $resultDoc = $this->Deal->getEmailDoc($sessDealData);                
                //End Code to save file from S3 server to local Server
                
                $this->set('userData',$sessData);
		$this->set('sessDealData',$sessDealData);
		$this->set('title_for_layout', __('Deal Emails'));
		
		

          if ($this->request->is('post')) {
            $this->EmailQueue->data = $this->request->data;
            $newEmaiQueueId = $this->EmailQueue->insertEmailQueue($this->request->data);
            if($newEmaiQueueId) {                                                
                $sessDealData['Deal']['email_id'] = $newEmaiQueueId;
                if ($this->Deal->addNewDeal($sessDealData) == Configure::read('ADD_DEAL_SUCCESS')) {
                    $this->Session->delete('DealDataUnsaved');
                    $this->redirect('view/success');
                } elseif ($this->Deal->addNewDeal($sessDealData) == Configure::read('ADD_DEAL_FAIL')) {
                    $this->Session->delete('DealDataUnsaved');
                    $this->redirect('/companies/message/fail');
                }

            } else { 
                $this->set('succeMessage', __('Email Queue have some error.'));
            }
          }
	}
	
	
}
?>
