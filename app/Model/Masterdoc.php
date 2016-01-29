<?php
App::import('Model', 'RolesPermissions');
App::import('Model', 'User');
App::import('Model', 'Section');
App::import('Model', 'UserToken');

class MasterDoc extends AppModel {
     public $name = 'MasterDoc';
	 
    public $validate = array(
        's3_key' => array(
            'rule1' => array('rule' => array('checkEmptyFile'), 'message' => 'You must select a file.'),
			'rule2' => array('rule' => array('extension', array('docx')),'message' => 'File type not supported.'),
            'rule3' => array('rule' => array('checkFileType'), 'message' => 'File type not supported.')
        ),
        'available_after_dt' => array(
            'rule1' => array('rule' => array('checkEarlierDate'), 'message' => 'Available After date must be today’s date or later.'),
        ),
		
		'sectionName' => array(
            'rule1' => array('rule' => array('checkSectionName'), 'message' => 'You must select a value.')
        )
		
    );
	
    public $belongsTo = array (
            'File' => array(
                'classname' => 'File',
                'foreignKey' => false,
                'conditions' => 'MasterDoc.files_id = File.id'
            ),
            'Section' => array(
                    'classname' => 'Section',
                    'foreignKey' => false,
                    'conditions' => 'MasterDoc.id = Section.master_docs_id'
                ),
            'SectionUser' => array(
                    'classname' => 'SectionUser',
                    'foreignKey' => false,
                    'conditions' => 'Section.id = SectionUser.sections_id'
            ),
            'RepresentingInterest' => array(
                    'classname' => 'RepresentingInterest',
                    'foreignKey' => false,
                    'conditions' => 'RepresentingInterest.id = SectionUser.representing_interest_of'
            ),
            'User' => array(
					'classname' => 'User',
                    'foreignKey' => false,
                    'conditions' => 'User.id = SectionUser.user_id'
            ),
            'UserCompany' => array(
                        'classname' => 'UserCompany',
                        'foreignKey' => false,
                        'conditions' => 'User.id = UserCompany.user_id'
            ),
            'Company' => array (
                'classname' => 'Company',
                'foreignKey' => false,
                'conditions' => 'UserCompany.company_id = Company.id'
            ),
        );
		
	function __construct() {
		parent::__construct();
		$this->tablePrefix = CakeSession::read('user.Company.prefix'). '_';
		$this->useTable = 'master_docs';
	}
	
	Public function convert($file) {
		App::import('Vendor', 'test', array('file' => 'phpdocx_community' . DS . 'classes'. DS .'TransformDoc.inc')); 		                
		$document = new TransformDoc();
		$document->setStrFile($file);
		$document->generateXHTML();
		$contents = $document->getStrXHTML();
	
		preg_match("/<body.*>.*<\/body>/s", $contents, $matches);

		$body_html = preg_replace('/<body.*>/','',$matches[0]);
		$body_html = preg_replace('/<\/body>/','',$body_html);
		return $body_html;
	}
	
	Public function displayHtml() {
		$docId = CakeSession::read('masterDoc.MasterDoc.id');
		$html = $this->findById($docId, array('fields' => 'MasterDoc.converted_html'));
		return $html;
	}
	
	
	Public function getSectionData($sectionId = null) {
		$masterDocId = $this->getMasterDocById(CakeSession::read('masterDoc.MasterDoc.files_id'));
		$masterDocId = $masterDocId['MasterDoc']['id'];

		if (!empty($sectionId)) {
			$sectionData = $this->Section->find('all', array('conditions' => array( 'master_docs_id' => $masterDocId, 'SectionUser.act_as' => 'DECISION_MAKER', 'Section.id' => $sectionId), 'fields' => array('Section.name','Section.master_docs_id','User.id','User.first_name','User.last_name', 'RepresentingInterest.name')
			));
		} else {
			$sectionData = $this->Section->find('all', array('conditions' => array( 'master_docs_id' => $masterDocId, 'SectionUser.act_as' => 'DECISION_MAKER'), 'fields' => array('Section.id', 'Section.name', 'Section.master_docs_id', 'User.id','User.first_name', 'User.last_name', 'RepresentingInterest.name')));
		}
		if(!empty($sectionData)){
			return $sectionData;
		}
		else {
			return false;
		}
	}
	
	function saveHtml($updateHtml, $sectionName) {
		if(!empty($updateHtml) && !empty($sectionName)) {
			//$check = $this->validateSection($sectionName);
				$docId = CakeSession::read('masterDoc.MasterDoc.id');
				$this->save(array('id' => $docId, 'converted_html' => $updateHtml));
		}
	}
	
	function removeSectionUsers($sectionId) {
		$this->Section->delete($sectionId, true);
	}
	
	function checkFileType($data) {
		if( isset($data['s3_key']['type']) && $data['s3_key']['type'] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
			return true;
		else
			return false;
	}
	
	function validateSection($sectionName) {
		$docId = CakeSession::read('masterDoc.MasterDoc.id');

		if(!empty($sectionName)) {
			$count = $this->query("SELECT count(name) FROM ". $this->tablePrefix ."sections WHERE name = '".$sectionName."' AND master_docs_id = ".$docId);
			if($count[0][0]['count(name)'] > 0) {
				return 0;
			}
			else {
				return 1;
			}
		}
		else {
			return 0;
		}
	}
    
    
    public function checkEmptyFileName($data) {
            if(empty($data['name'])){
                    return false;
            }
            return true;
    }
	
	public function checkSectionName($data) {
		if(empty($data['sectionName'])){
			return false;
		}
		else {
			return true;
		}
	}
    
    public function checkEarlierDate($data) {
        if($data['available_after_dt'] < date("Y-m-d", strtotime(Configure::read('DATE_FORMAT')))) {
                return false;
        }
        return true;
    }
    
    public function checkEmptyFile($data) {
            if(empty($data['s3_key']['name'])){
                    return false;
            }
            return true;
    }
    public function checkName($data){
            $cnt = $this->find('count', array('conditions' => array('MasterDoc.name' => $data['name'])));
            if($cnt != 0) {
                    return false;
            } else
                    return true;
	}
    public function saveData($fieldArr, $checkValidate = true, $id = null, $table = null){
        if($id != NULL && $table == NULL){
            $this->id = $id;
        }elseif($id != NULL && $table != NULL){
            $this->id = $id;
        }
        if(is_array($fieldArr) && count($fieldArr) > 0)
			$fieldArr = array_map('trim', $fieldArr);
        if($table != NULL){
            if ($this->$table->save($fieldArr, $checkValidate)) {
                if ($this->$table->getLastInsertID() != null) {
                    return $this->$table->getLastInsertID();
                } else {
                    return true;
                }
            }
        } else {
            if ($this->save($fieldArr, $checkValidate)) {
                if ($this->getLastInsertID() != null) {
                    return $this->getLastInsertID();
                } else {
                    return true;
                }
            }
        }
        return false;
    }

    public function uploadMasterDoc($masterDocRequestData){
        
        $file = explode('.', $masterDocRequestData['MasterDoc']['s3_key']['name']);
        if (strlen($file[0]) > Configure::read('FILENAME_STRING_LENGTH')) {
            $file[0] = substr($file[0], 0, Configure::read('FILENAME_STRING_LENGTH'));
        }
        $filename = $file[0].'_'.date(Configure::read('DB_DATEIMG_FORMAT')).'.'.$file[1];
        $s3UriPath = CakeSession::read('user.Company.company_name') . '/MasterDoc/';       
        
if ($this->storeMasterDoc($masterDocRequestData['MasterDoc']['s3_key']['tmp_name'],$s3UriPath , $filename)== true) {
            if ($this->File->saveData(array('File' => array('s3_key' => $filename, 's3_uri_path'=> $s3UriPath)), true, null)) {
                $convertedHtml = $this->convert($masterDocRequestData['MasterDoc']['s3_key']['tmp_name']);
                $masterdocData = array('name' => $filename, 'owner' => CakeSession::read('user.User.id'), 'files_id' => $this->File->getLastInsertID(),'converted_html' => $convertedHtml);    
                if ($lastInsertedId = $this->saveData($masterdocData)) {
                    if ($this->Section->saveData(array('Section' => array('master_docs_id' => $lastInsertedId, 'name' => Configure::read('SECTION_FOR_ENTIRE_DOCUMENT'))), true, null)) {
                        $lastInsertedId = $this->Section->getLastInsertID();
                        //$lastInsertedId = $this->query('select MAX(id) as id from cybage454_sections');
                        if ($this->SectionUser->saveData(array('SectionUser' => array('sections_id' => $lastInsertedId, 'act_as' => Configure::read('DECISION_MAKER'), 'user_id' => CakeSession::read('user.User.id'))), true, null)) {
                            unset($this->SectionUser->id);
                            $this->SectionUser->saveData(array('SectionUser' => array('sections_id' => $lastInsertedId, 'act_as' => Configure::read('OWNER'), 'user_id' => CakeSession::read('user.User.id'))), true, null);
                            return $masterdocData;
                        } else {
                            return Configure::read('MASTER_DOC_FAIL');
                        }
                    } else {
                        return Configure::read('MASTER_DOC_FAIL');
                    }
                } else {
                    return Configure::read('MASTER_DOC_FAIL');
                }
            } else {
                return Configure::read('MASTER_DOC_FAIL');
            }
        }
        return Configure::read('MASTER_DOC_FAIL');
    }
    
    public function storeMasterDoc($tmpname, $s3_uri_path, $filename){
        //if(move_uploaded_file($tmpname, $filepath))
             $this->AmazoneFileSystem = ClassRegistry::init('AmazoneFileSystem');
             return $this->AmazoneFileSystem->putObjectFile($tmpname, $this->AmazoneFileSystem->bucket, Configure::read('AWS_DOC_FOLDER') . $s3_uri_path . $filename, 'authenticated-read');                          
        //else
         //   return false;
    }
	/*
	public function uploadMasterOnAWS( $MasterDocId ) {
		// Need to add hadnling for edit master docs friendly name
		// Get the file id and actual file name from master docs
		$masterData = $this->find('first', array(
										'conditions' => array('MasterDoc.id' => $MasterDocId),
										'fields' => array('MasterDoc.files_id', 'File.s3_key', 'MasterDoc.name')
								));
		// pass user friendly name on s3 and update the file id -> file name
		$this->AmazoneFileSystem = ClassRegistry::init('AmazoneFileSystem');
		$s3UriPath = CakeSession::read('user.Company.company_name') . '/MasterDoc/';		
		$this->AmazoneFileSystem->putObjectFile(Configure::read('MASTER_DOC_PATH') . $masterData['File']['s3_key'], $this->AmazoneFileSystem->bucket, Configure::read('AWS_DOC_FOLDER') . $s3_uri_path . $masterData['MasterDoc']['name'], 'authenticated-read');
		$this->File->id = $masterData['MasterDoc']['files_id'];
		$this->File->Save(array('s3_key' =>  Configure::read('AWS_DOC_FOLDER') . $s3_uri_path . $masterData['MasterDoc']['name']));
		
		// Unlink the tmp file
		unlink(Configure::read('MASTER_DOC_PATH') . $masterData['File']['s3_key']);
	
	}
    */
    public function getMasterDocById($id){
        if($masterDocData = $this->findByFiles_id($id))
            return $masterDocData;
        else 
            return false;
    }
    
    public function getMasterDocByIdForDescribe($id) {
        $masterDocData = $this->find('all', array('conditions' => array( 'files_id' => $id), 'group' => array('MasterDoc.owner')));
        if(!empty($masterDocData))
            return $masterDocData;
        else 
            return false;
    }
    
    
    public function getUsersByCompanyId($companyId){
        
        $DealReviewRoleIdArr = $this->query("SELECT role_id FROM roles_permissions as RolesPermissions 
                WHERE RolesPermissions.company_id = $companyId AND RolesPermissions.permission_id = ".Configure::read('REVIEW_DEALS_PERMISSIONS'));
        if(!empty($DealReviewRoleIdArr)){
            $DealReviewRoleId = array();
            foreach($DealReviewRoleIdArr as $DealReviewRoleIdArr){
                $DealReviewRoleId []= $DealReviewRoleIdArr['RolesPermissions']['role_id'];
            }
            if(!empty($DealReviewRoleId)){
                if(count($DealReviewRoleId) == 1){ 
                    $userDataDd = $this->query("SELECT * FROM users as User 
                    LEFT JOIN user_companies as UserCompany ON (User.id = UserCompany.user_id)
					LEFT JOIN companies as Company ON (UserCompany.company_id = Company.id)
                    WHERE UserCompany.status = 1 AND Company.status = 1 AND User.access_status = 1 AND UserCompany.company_id = $companyId AND UserCompany.role_id = ".$DealReviewRoleId[0]);
                } else {
					$str = '';

                    foreach ($DealReviewRoleId as $id){
                        $str.= $id.",";
                    }
                    $roles = substr($str, 0, -1);
                    $roles = "(".$roles.")";
                    $userDataDd = $this->query("SELECT * FROM users as User 
                    LEFT JOIN user_companies as UserCompany ON (User.id = UserCompany.user_id) 
                    LEFT JOIN companies as Company ON (UserCompany.company_id = Company.id)
                    WHERE UserCompany.status = 1 AND Company.status = 1 AND User.access_status = 1 AND UserCompany.company_id = $companyId AND UserCompany.role_id IN ".$roles);
                }
            }
        }
        
        if(!empty($userDataDd))
            return $userDataDd;
        else 
            return false;
    }
    
    public function validateForm($describeData, $update){
		$cnt = true;
		$describeData['MasterDoc']['name'] = trim($describeData['MasterDoc']['name']);
		if(empty($describeData['MasterDoc']['name'])){
			$this->invalidate('name', __('You must select a value.', true));
            $cnt = false;
        }

		if(!empty($describeData['MasterDoc']['name']) && !preg_match('/^[a-zA-Z0-9_-\s]+$/',$describeData['MasterDoc']['name'])){
			$this->invalidate('name', __('Invalid characters in file name.', true));
            $cnt = false;
        }

		if( $update == 0 && !$this->checkName($describeData['MasterDoc'])) {
			$this->invalidate('name', __('Master Document name already in use.', true));
            $cnt = false;
		}
		$describeData['MasterDoc'] = $describeData['CalenderMasterDoc'];
        if(empty($describeData['MasterDoc']['available_after_dt'])){
            $this->invalidate('available_after_dt', __('You must select a value.', true));
            $cnt = false;
        }
        if(date("Y-m-d", strtotime($describeData['MasterDoc']['available_until_dt'])) < date("Y-m-d", strtotime($describeData['MasterDoc']['available_after_dt']))){
            $this->invalidate('available_until_dt', __('"Available Until" date cannot be earlier than the "Available After" date.', true));
            $cnt = false;
        }
        return $cnt;
        
    }
    
    public function changeDecisionMaker($requestData, $sectionId) {
		if (!empty($requestData)) {
			$owner = $requestData['MasterDoc']['owner'];
			if ($this->SectionUser->saveData(array('user_id' => $requestData['MasterDoc']['owner'], 'representing_interest_of' => $requestData['represent_interests_of_'.$owner], 'custom_description' => $requestData['custom_descr_'.$owner]), true, $sectionId)) {
                   return true;
               } else {
                   return Configure::read('MASTER_DOC_FAIL');
               }
			
		}
		return false;
    }

    
    public function changeOpinionProvider($requestData, $sectionId){
        $values = '';
        if(!empty($sectionId)){
            $cnt = $this->SectionUser->find('count', array('conditions' => array('SectionUser.sections_id' => $sectionId, 'SectionUser.act_as' => Configure::read('OPINION_PROVIDER'))));
            if($cnt != 0) {
                $this->SectionUser->deleteAll(array('SectionUser.sections_id' => $sectionId, 'SectionUser.act_as' => Configure::read('OPINION_PROVIDER')));
            }
        }
        
        if(!empty($requestData['MasterDoc']['owner'])){
            foreach($requestData['MasterDoc']['owner'] as $opinionProvider){
                if(empty($values)) {
                    $values = "('" . $sectionId ."','". $opinionProvider."','". $requestData['represent_interests_of_'.$opinionProvider]."','". $requestData['custom_descr_'.$opinionProvider]."','". date(Configure::read('DB_DATE_FORMAT'))."','". date(Configure::read('DB_DATE_FORMAT'))."','". Configure::read('OPINION_PROVIDER') . "')";
                } else {
                    $values .= ",('" . $sectionId ."','". $opinionProvider."','". $requestData['represent_interests_of_'.$opinionProvider]."','". $requestData['custom_descr_'.$opinionProvider]."','". date(Configure::read('DB_DATE_FORMAT'))."','". date(Configure::read('DB_DATE_FORMAT'))."','". Configure::read('OPINION_PROVIDER') . "')";
                }
            }
        }
        if(!empty($values)){
            $this->query("INSERT INTO ".$this->tablePrefix ."sections_users (`sections_id`, `user_id`, `representing_interest_of`, `custom_description`, `created`, `modified`, `act_as`) VALUES ".$values);
                return true;
        }
        return false;
        //$this->saveData(array('sections_id' => $sectionId, 'act_as' => 'OPINION_PROVIDER', 'user_id' => $requestData), true, null, 'Cybage454SectionsUser');
    }
    
    public function getOpinionProviderById($sectionId){
        if(!empty($sectionId)){
            if($opinionProviderData = $this->Section->find('all', array('conditions' => array('SectionUser.sections_id' => $sectionId, 'SectionUser.act_as' => Configure::read('OPINION_PROVIDER'))))){
                return $opinionProviderData;
            }
        return false;
        }
    }
	
	public function getOpinionProviderByIdToolTip($sectionId){
        if(!empty($sectionId)){
            if($opinionProviderData = $this->Section->find('all', array('conditions' => array('SectionUser.sections_id' => $sectionId, 'SectionUser.act_as' => Configure::read('OPINION_PROVIDER')), 'fields' => array('User.first_name','User.last_name')))){
				$cnt = 0;
				foreach($opinionProviderData as $userdata) {
					if($cnt == 0) {
						$opinionProviders = $userdata['User']['last_name'] . " " . $userdata['User']['first_name'];
					} else {
						$opinionProviders .= ", " . $userdata['User']['last_name'] . " " . $userdata['User']['first_name'];
					}
					$cnt++;
				}
                return $opinionProviders;
            }

        return false;
        }
    }
    
    public function decisionMakerById($sectionId){
        if(!empty($sectionId)){
            if($decisionMakerData = $this->Section->find('all', array('conditions' => array('SectionUser.sections_id' => $sectionId, 'SectionUser.act_as' => Configure::read('DECISION_MAKER'))))){
                return $decisionMakerData;
            }
        return false;
        }
    }
    
    public function deleteOpinionProvider($sectionUserId){
        if($this->SectionUser->delete($sectionUserId)){
            return true;
        }
        return false;
    }
    
    public function reassignRole ($token) {
        //check if session exits or not
        if(CakeSession::read('user') != null) {
            $User = new User();
            $Section = new Section();
            $UserToken = new UserToken();
            
            $userToken = $User->getTokenIdFromToken($token);
            //$UserToken->clearToken(CakeSession::read('user.User.id'), $token);
            $sectionUserData = $this->find('first', array('conditions' => array('SectionUser.token_id' => $userToken['UserToken']['id'])));
            
            if ($sectionUserData['SectionUser']['act_as'] == Configure::read('DECISION_MAKER')) {
                $tokenType = Configure::read('TOKEN_CHANGE_DECISIONMAKER');
            } elseif ($sectionUserData['SectionUser']['act_as'] == Configure::read('OPINION_PROVIDER')) {
                $tokenType = Configure::read('TOKEN_CHANGE_OPINIONPROVIDER');
            } elseif ($sectionUserData['SectionUser']['act_as'] == Configure::read('OWNER')) {
                $tokenType = Configure::read('TOKEN_CHANGE_OWNER');
            }
            
            $userTokenData = $UserToken->validateToken($token, array($tokenType));
            //redirection
            if ($userTokenData == 1 || $userTokenData == 3) {
                // Invalid Token redirected to page not found as per Use Case Free Trial - B6
                return Configure::read('INVALID_TOKEN');
            } elseif ($userTokenData == 2) {
                return Configure::read('EXPIRED_LINK');
            }
            
            $UserToken->clearToken(CakeSession::read('user.User.id'), $token);
            
            if ($sectionUserData['SectionUser']['act_as'] == Configure::read('OWNER')) {
                return Configure::read('LOGGEDIN_REASSIGN_OWNER');
            }

            if ($sectionUserData['Section']['name'] != Configure::read('SECTION_FOR_ENTIRE_DOCUMENT')) {
            //change decision maker of section
                CakeSession::write('getLastInsertedId', $sectionUserData['Section']['id']);
                CakeSession::write('lastSectionsUsersId', $sectionUserData['SectionUser']['id']);
                CakeSession::write('masterDoc.MasterDoc.files_id', $sectionUserData['MasterDoc']['files_id']);
                CakeSession::write('PostedSectionName', $sectionUserData['Section']['name']);
            } else {
            //change decision maker of entire document
                    unset($sectionUserData['MasterDoc']['converted_html']);
                   CakeSession::write('masterDoc', array(
                     'MasterDoc' => array ('id' => $sectionUserData['MasterDoc']['id'], 'files_id' => $sectionUserData['MasterDoc']['files_id']),
                      'Section' => array('id' => $sectionUserData['Section']['id']),
                      'SectionUser' => array('id' => $sectionUserData['SectionUser']['id'])
                  ));
            }  
            return Configure::read('LOGGEDIN_REASSIGN');
        } else {
            return Configure::read('NOT_LOGGEDIN_REASSIGN');
        }
    }
}
?>
