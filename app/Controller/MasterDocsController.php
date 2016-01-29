<?php
class MasterDocsController extends AppController {
    
    public $uses = array('MasterDoc', 'MasterdocUser', 'RepresentingInterest','File', 'User', 'UserCompany', 'Company', 'Section','Section', 'SectionUser', 'RolesPermissions');
    
    
	Public function _displayContract(){
		$data = $this->MasterDoc->displayHtml();
		$this->set('data', $data);
		$this->render('contract');
	}
	
	public function contract() {
		$this->Session->delete('sectionValue');
		$this->set('confirm_message', __('Delete This Section and it&#039;s change control?'));
		$masterDocUsersData = $this->MasterDoc->getUsersByCompanyId(CakeSession::read('user.Company.id'));
		$listing = $this->MasterDoc->getSectionData();
		$masterDocData = $this->MasterDoc->getMasterDocById(CakeSession::read('masterDoc.MasterDoc.files_id'));
		$this->set('masterDocData',$masterDocData);
		if($listing) {
			// Getting tool tip data
			$className = '';
			foreach($listing as $key=>$section) {
				$names = $this->MasterDoc->getOpinionProviderByIdToolTip($section['Section']['id']);
				$listing[$key]['Section']['opinionproviders'] = ($names) ? $names : 'NA';	
				if( $section['Section']['name'] != 'ENTIRE DOCUMENT' && $className == '') {
					$className = ".". str_replace(' ','_', $section['Section']['name']);
				} elseif ( $section['Section']['name'] != 'ENTIRE DOCUMENT' ) {
					$className .= ",." . str_replace(' ','_', $section['Section']['name']);
				}
			}
			$this->set('sectionListing', $listing);
			$this->set('className', $className);
		}
		$this->_displayContract($this->action);
	}
	
	public function contractAssignement() {
		$this->set('confirm_message', __('Please Select an area of Text'));
		$this->set('validateSectionName', __('Please give Selected section a Name'));
		$this->set('validateUniqueSectionName', __('This section name is already used'));
		$this->set('validateSpcCharsSectionName', __('Special Characters are not allowed in Section Name'));
		$this->Session->write('actionName', $this->action);
		$masterDocData = $this->MasterDoc->getMasterDocById(CakeSession::read('masterDoc.MasterDoc.files_id'));
		$this->set('masterDocData', $masterDocData);
		$this->_displayContract($this->action);
	}
	
	
	Public function removeSections() {
		$updatedHtml = $this->request->data['removedSectionContract'];
		$this->MasterDoc->saveHtml($updatedHtml,$this->request->data['removedSectionId']);
		$this->MasterDoc->removeSectionUsers($this->request->data['removedSectionId']);
		$this->redirect('contract');
	}
	
	Public function editSections() {
		if($this->request->is('post')){
			$this->Session->write('editAction',$this->action);
			$this->set('validateSectionName', __('Please provide a name'));
			$this->set('validateUniqueSectionName', __('This section name is already used'));
			$this->set('validateSpcCharsSectionName', __('Special Characters are not allowed in Section Name'));
			$sectionData = explode(',', $this->request->data['editSectionNameId']); 
			$sectionName = str_replace('_', ' ', $sectionData[0]); 
			$sectionId = str_replace('_', ' ', $sectionData[1]); 
			
			$this->set('sectionValue', $sectionName);
			$this->set('updateSectionId', $sectionId);
			$decisionMaker = $this->MasterDoc->decisionMakerById($sectionId);
			$decisionMaker = $decisionMaker[0]['SectionUser']['id'];
			$this->Session->write('getLastInsertedId',$sectionId);
			$this->Session->write('lastSectionsUsersId', $decisionMaker);
			$masterDocData = $this->MasterDoc->getMasterDocById(CakeSession::read('masterDoc.MasterDoc.files_id'));
			$this->set('masterDocData', $masterDocData);
			$this->_displayContract('contractassignement');
		}
		else {
			$this->redirect('contract');
		}
	}
	
	Public function validateSectionName() {
		$this->layout = 'ajax'; // Or $this->RequestHandler->ajaxLayout, Only use for HTML
		$this->autoLayout = false;
		$this->autoRender = false;
		$sectionName = str_replace('','_',trim($this->data['name']));
		$return = $this->MasterDoc->validateSection($sectionName);
		echo $return;
		return;
	}
    
    public function addMasterDoc() {
        //set page title
        $this->set('title_for_layout', __('Add Master Document | Upload'));
        
        $filesize = NULL;
        $masterdocData = array('files_id' => NULL);
        $submitted = NULL;
        if ($this->request->is('post')) {
            $this->MasterDoc->data = $this->request->data;
            if ($this->MasterDoc->validates()) { 
                $submitted = 1;
                $masterdocData = $this->MasterDoc->uploadMasterDoc($this->MasterDoc->data);
                if ($masterdocData == Configure::read('MASTER_DOC_FAIL')) {
                    $this->redirect('/companies/message/fail');
                }
                $filesize = $this->request->data['MasterDoc']['s3_key']['size']/1000;
                CakeSession::write('masterDocFileId', $masterdocData['files_id']);
            }
        }
        $this->set('filesize',$filesize);
        $this->set('masterdocData',$masterdocData);
        $this->set('submitted',$submitted);
    }
    
    public function describeMasterDoc() {
        //set page title
        $this->set('title_for_layout', __('Add Master Document | Describe Document'));
        
        //delete section session
        $this->Session->delete('lastSectionsUsersId');
        $this->Session->delete('getLastInsertedId');
        if(CakeSession::read('masterDocFileId') != ''){
            $masterDocData = $this->MasterDoc->getMasterDocByIdForDescribe(CakeSession::read('masterDocFileId'));
            if(!empty($masterDocData)){
                foreach($masterDocData as $masterDocData){
					unset($masterDocData['MasterDoc']['converted_html']);
                    CakeSession::write('masterDoc', array(
                          'MasterDoc' => array ('id' => $masterDocData['MasterDoc']['id'], 'files_id' => $masterDocData['MasterDoc']['files_id']),
                           'Section' => array('id' => $masterDocData['Section']['id']),
                           'SectionUser' => array('id' => $masterDocData['SectionUser']['id'])
                       ));
                }
            }
            //CakeSession::write('masterDoc', $masterDocData);
        }
        if ($this->request->is('post')) { 
            if (!empty($this->request->data['MasterDocs']['docid'])) {
                $masterDocData = $this->MasterDoc->getMasterDocByIdForDescribe($this->request->data['MasterDocs']['docid']);
			if(!empty($masterDocData)){
                   foreach($masterDocData as $masterDocData){
					   unset($masterDocData['MasterDoc']['converted_html']);
                       CakeSession::write('masterDoc', array(
                          'MasterDoc' => array ('id' => $masterDocData['MasterDoc']['id'], 'files_id' => $masterDocData['MasterDoc']['files_id']),
                           'Section' => array('id' => $masterDocData['Section']['id']),
                           'SectionUser' => array('id' => $masterDocData['SectionUser']['id'])
                       ));
                   }
               }
                CakeSession::write('masterDocFileId', $this->request->data['MasterDocs']['docid']);
            } else {
                $this->MasterDoc->data = $this->request->data;
				$update = 0;
                if($this->request->data['MasterDoc']['name'] == $masterDocData['MasterDoc']['name']){
                    $update = 1;
                }
				if($this->MasterDoc->validateForm($this->request->data, $update)){
				   $this->request->data['MasterDoc']['available_after_dt'] = date("Y-m-d", strtotime($this->request->data['CalenderMasterDoc']['available_after_dt']));
					$this->request->data['MasterDoc']['available_until_dt'] = date("Y-m-d", strtotime($this->request->data['CalenderMasterDoc']['available_until_dt']));
					$this->request->data['MasterDoc']['available_deal_issue'] = 1;
					if ($this->MasterDoc->saveData($this->request->data['MasterDoc'], true, $masterDocData['MasterDoc']['id'])) {
						//$this->MasterDoc->uploadMasterOnAWS($masterDocData['MasterDoc']['id']);
						$this->redirect(array('action' => 'defaultChangeControl'));
					} else {
						$this->redirect('/companies/message/fail');
					}
				}
            }
        }
        if (!$masterDocData['User']['image']) {
            $profileImage = Configure::read('DEFAULT_IMAGE_NAME');
            $amazonS3ServerPath ="";
        } else {
            $profileImage = $masterDocData['User']['image'];
            $amazonS3ServerPath = Configure::read('AWS_PROFILE_IMG_IMAGES_URL');
        }
        $this->set('range',array_combine(range(1,120), range(1,120)));
        $this->set('masterDocData',$masterDocData);
        $this->set('profileImage',$profileImage);
        $this->set('amazonS3ServerPath',$amazonS3ServerPath);
        $this->set('confirm_message',Configure::read('DESCRIBE_MASTERDOC_CONFIRMATION_MSG'));
    }

    public function defaultChangeControl($id = null){
        //set page title
        $this->set('title_for_layout', __('Add Master Document | Define default change controls'));
        
        //delete opinion provider if id is not null
        if (!empty($id)) {
            if ($this->MasterDoc->deleteOpinionProvider($id)) {
                $this->redirect(array('action' => 'defaultChangeControl'));
            } else {
                $this->redirect('/companies/message/fail');
            }
        }
        
        if($this->Session->read('lastSectionsUsersId') == null) {
				$sectionId = $this->Session->read('masterDoc.Section.id');
				$sectionUserId = $this->Session->read('masterDoc.SectionUser.id');
			
        } else {
            //set section id and section user for section
            $sectionId = $this->Session->read('getLastInsertedId');
            $sectionUserId = $this->Session->read('lastSectionsUsersId');
        }
        //get master document data  
        $masterDocData = $this->MasterDoc->getMasterDocById(CakeSession::read('masterDoc.MasterDoc.files_id'));
        
        //get decision maker data of master document or section based on above conditions

        $masterDocDecisionData = $this->MasterDoc->getSectionData($sectionId);

        //get decision maker data of master document or section based on above conditions
        $opinionProviderData = $this->MasterDoc->getOpinionProviderById($sectionId);
        $this->set('masterDocDecisionData', $masterDocDecisionData);
        $this->set('masterDocData', $masterDocData);
        $this->set('opinionProviderData',$opinionProviderData);
        
        if (!$masterDocData['User']['image']) {
			$profileImage = Configure::read('DEFAULT_IMAGE_NAME');
        } else {
			$profileImage = $masterDocData['User']['image'];
        }
        $this->set('profileImage', $profileImage);
        if ($this->request->is('post')) {
			$sectionName = trim($this->request->data['sectionName']);
			$this->Session->write('PostedSectionName', $sectionName); 
			$sectionName = preg_replace('/\s+/', '_', $sectionName);
			$action = $this->Session->read('editAction');
			$this->Session->delete('editAction');
			if($action == 'editSections') {
				$oldSectionName = preg_replace('/\s+/', '_', $this->request->data['oldSectionName']);
				$sectionId = $this->request->data['updateSectionId'];
				$html = str_replace($oldSectionName, $sectionName, $this->request->data['unEditedContract']);
				$saveHtml = $this->MasterDoc->saveHtml($html, $sectionName);
				$section = array('id' => $sectionId, 'master_docs_id' => $masterDocData['MasterDoc']['id'], 'name' => $sectionName);
				$this->Section->saveData($section,true,Null);
			}
			else {
				if(!empty($this->request->data['sectionName']) && !empty($this->request->data['contract'])) {
						$currentAction = $this->Session->read('actionName');
						$this->set('currentAction', $currentAction);
						$html = str_replace('marked', $sectionName, $this->request->data['contract']);
						$saveHtml = $this->MasterDoc->saveHtml($html, $sectionName);
						$section = array('master_docs_id' => $masterDocData['MasterDoc']['id'], 'name' => $sectionName);
						$getLastInsertedId = $this->Section->saveData($section,true,Null);

						$sectionUsers = array('sections_id' => $getLastInsertedId, 'user_id' => $masterDocData['User']['id'], 'representing_interest_of' => $masterDocData['SectionUser']['representing_interest_of'], 'custom_description' => $masterDocData['SectionUser']['custom_description'], 'act_as' => Configure::read('DECISION_MAKER'));

						$lastSectionsUsersId = $this->SectionUser->saveData($sectionUsers,true,Null);

						$this->Session->write('getLastInsertedId',$getLastInsertedId);
						$this->Session->write('lastSectionsUsersId',$lastSectionsUsersId);
						//$this->Session->write('PostedSectionName',$this->request->data['sectionName']);
						$action = $this->Session->read('actionName');
						$this->set('actionName', $action);
						$this->Session->delete('actionName');
						$this->redirect('defaultChangeControl');
				} else {
					$this->redirect('defaultChangeControl');
				}
			}
		}
		//added by krishna
		$this->set('currentAction',$this->action);
		//added by krishna
       $this->set('confirm_message', Configure::read('REM_OPINION_CONFIRMATION_MSG'));
    }
    
    public function changeUsers($id = null){
        $this->set('title_for_layout', __('Add Master Document | Define default change controls'));
		$invalidCustomDesc = 0;
          $invalidRepresentingInterestOf = 0;
        if($this->Session->read('lastSectionsUsersId') == null) {
            $sectionId = $this->Session->read('masterDoc.Section.id');
			$sectionUserId = $this->Session->read('masterDoc.SectionUser.id');
		} else {

			$sectionId = $this->Session->read('getLastInsertedId');
			$sectionUserId = $this->Session->read('lastSectionsUsersId');
			}
        
        $this->set('representingInterestDd', CakeSession::read('representingInterest'));	
		$masterDocDecisionData = $this->MasterDoc->getSectionData($sectionId);	
        $masterDocData = $this->MasterDoc->getMasterDocById(CakeSession::read('masterDoc.MasterDoc.files_id'));
        
        $decisionMakerData = $this->MasterDoc->decisionMakerById($sectionId);
        $opinionProviderData = $this->MasterDoc->getOpinionProviderById($sectionId);

        $opinionProviderUserId = array();
        $opinionProviderUser = array();
        if(!empty($opinionProviderData)){
            foreach($opinionProviderData as $opinionProviderData){
                $opinionProviderUserId[] = $opinionProviderData['SectionUser']['user_id'];
                $opinionProviderUser[$opinionProviderData['SectionUser']['user_id'].'_representing_interest_of'] = $opinionProviderData['SectionUser']['representing_interest_of'];
                $opinionProviderUser[$opinionProviderData['SectionUser']['user_id'].'_custom_description'] = $opinionProviderData['SectionUser']['custom_description'];
            }
        }

        $masterDocUsersData = $this->MasterDoc->getUsersByCompanyId(CakeSession::read('user.Company.id'));
        
        if ($this->request->is('post')) {
			// Check if custom description is empty
			if(isset($this->request->data['MasterDoc']) && is_array($this->request->data['MasterDoc'])) {
				if(is_array($this->request->data['MasterDoc']['owner']) && count($this->request->data['MasterDoc']['owner']) > 0 ) {
                        foreach ($this->request->data['MasterDoc']['owner'] as $key => $value) {
						if( $this->request->data['represent_interests_of_' . $value] == 7) {
							if(empty($this->request->data["custom_descr_" . $value]) || $this->request->data["custom_descr_" . $value] == '' ) {
								$invalidCustomDesc = 1;
								break;
							}
						} elseif ( $this->request->data['represent_interests_of_' . $value] == "") {
                                  $invalidRepresentingInterestOf = 1;
                              }
					}
				} elseif (isset($this->request->data['MasterDoc']['owner'])) {
                        $value = $this->request->data['MasterDoc']['owner'];
                        
					if($this->request->data['represent_interests_of_' . $this->request->data['MasterDoc']['owner']] == 7 && (empty($this->request->data["custom_descr_" . $value]) || $this->request->data["custom_descr_" . $value] == '' )) {
						$invalidCustomDesc = 1;
					} 
                         if ( $this->request->data['represent_interests_of_' . $value] == "") {
                            $invalidRepresentingInterestOf = 1;
                        }
				} 
			}
			if( $invalidCustomDesc != 1 && $invalidRepresentingInterestOf != 1) {
				 if (array_key_exists(Configure::read('ADD_OPINION_PROVIDER'), $this->request->data)) {
					if($this->Session->read('lastSectionsUsersId') != NULL) {
						$result = $this->MasterDoc->changeOpinionProvider($this->request->data, $this->Session->read('getLastInsertedId'));
						if ($result === Configure::read('MASTER_DOC_FAIL')) {
							$this->redirect('/companies/message/fail');
						}
					} else {
					   $result = $this->MasterDoc->changeOpinionProvider($this->request->data, $this->Session->read('masterDoc.Section.id'));
					   if ($result === Configure::read('MASTER_DOC_FAIL')) {
							$this->redirect('/companies/message/fail');
					   }
					}
						
				//$this->Masterdoc->changeOpinionProvider($this->request->data);
				} else {
					if($this->Session->read('lastSectionsUsersId') != NULL) {
						$result = $this->MasterDoc->changeDecisionMaker($this->request->data, $this->Session->read('lastSectionsUsersId'));

						if ($result === Configure::read('MASTER_DOC_FAIL')) {
							$this->redirect('/companies/message/fail');
						}
					//$this->Session->delete('lastSectionsUsersId');
					} else {
						$result = $this->MasterDoc->changeDecisionMaker($this->request->data, $this->Session->read('masterDoc.SectionUser.id'));
						if ($result === Configure::read('MASTER_DOC_FAIL')) {
							$this->redirect('/companies/message/fail');
						}
					}    
				}						
				$this->redirect(array('action' => 'defaultChangeControl'));
			}
		}
		$this->set('invalidCustomDesc',$invalidCustomDesc);
          $this->set('invalidRepresentingInterestOf',$invalidRepresentingInterestOf);
        $this->set('masterDocUsersData',$masterDocUsersData);
        $this->set('masterDocData',$masterDocData);
		
        $this->set('masterDocDecisionData',$masterDocDecisionData);
		
        $this->set('opinionProviderData',$opinionProviderData);
        
        $this->set('opinionProviderUser',$opinionProviderUser);
        $this->set('decisionMakerData',$decisionMakerData[0]);
        $this->set('opinionProviderUserId',$opinionProviderUserId);
        
        $this->set('id', $id);
    }
    
    public function reassignRole($token) {
        $this->Session->delete('lastSectionsUsersId');
        $this->Session->delete('getLastInsertedId');
        if ($token) {
            $result = $this->MasterDoc->reassignRole($token); 
            if ($result === Configure::read('LOGGEDIN_REASSIGN')) {
                $this->redirect(array('action' => 'defaultChangeControl'));
            } elseif ($result === Configure::read('NOT_LOGGEDIN_REASSIGN')) {
                $this->set('token',$token);
            } elseif ($result === Configure::read('LOGGEDIN_REASSIGN_OWNER')) {
                $this->redirect(array('controller' => 'masterdocusers','action' => 'index'));
            } elseif ($result === Configure::read('EXPIRED_LINK')) {
                $this->Session->destroy();
                $this->redirect(array('controller' => 'companies','action' => 'message','expired_link'));
            } elseif ($result === Configure::read('INVALID_TOKEN')) {
                $this->Session->destroy();
                $this->redirect('companies/message/page_not_found');
            } 
        }
    }
}
?>