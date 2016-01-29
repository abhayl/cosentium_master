<?php 
class Redline extends AppModel {
	public $name = 'Redline';
	public $validate = array(
			's3_key' => array(
				'rule1' => array('rule' => array('checkEmptyFile'), 'message' => 'You must select a file.'),
				'rule2' => array('rule' => array('extension', array('docx')),'message' => 'File type not supported.'),
				'rule3' => array('rule' => array('checkFileType'), 'message' => 'File type not supported.')
			),
			'name' => array(
				'rule1' => array('rule' => array('checkEmptyFileName'), 'message' => 'You must select a value.')
			)
		);	
	public $belongsTo = array (
	'Deal' => array (
				'classname' => 'Deal',
                'foreignKey' => false,
                'conditions' => 'Redline.deal_id = Deal.id'
			),
	'MasterDoc' => array (
				'classname' => 'MasterDoc',
                'foreignKey' => false,
                'conditions' => 'Deal.master_docs_id = MasterDoc.id'
			),		
	'File' => array(
                'classname' => 'File',
                'foreignKey' => false,
                'conditions' => 'Redline.files_id = File.id'
            )		
	 );
	
	function __construct() {
		parent::__construct();
		$this->tablePrefix = CakeSession::read('user.Company.prefix'). '_';
		$this->useTable = 'deal_revisions';
	}
	
	public function checkEmptyFileName($data) {
		if(empty($data['name'])){
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
	
	function checkFileType($data) {
		if( isset($data['s3_key']['type']) && $data['s3_key']['type'] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
			return true;
		else
			return false;
	}
	
	/**
	 * @name 			getDealCondition 
	 * @description		         			
	 * @parm	        deal_id
	 * @return	        deal revisions of a deal	 
	 */ 
	public function getDealRedlines($dealId) {
		return $this->find('all', array('conditions' => array('Deal.id' => $dealId)));
	}
	
	/**
	 * @name 			saveRedline 
	 * @description		save the revision of deal         			
	 * @parm	        revisions data
	 * @return	        	 
	 */
	public function saveRedline($data) {
		$this->create();
		$this->data = $data[$this->name];
		if($this->Save($data)) {
			return true;
		} else {
			return false;
		}
		
	}
	
	Public function executeDiff() {
		App::import('Vendor', 'diffLibrary', array('file' => 'dodiff.php'));
		$dealId = CakeSession::read('receiveRedlineId');
		$htmls = $this->find('all',array('fields' => array('Redline.id','MasterDoc.converted_html','Redline.converted_html', 'Redline.diff_html'), 'order' => array('Redline.id' => 'desc'),
		'conditions' => array('deal_id' => $dealId , 'Redline.stage LIKE' => '%Received%'), 
		'limit' => 2));		
		if(count($htmls) > 1) {
			$doc1 = $htmls[1]['Redline']['converted_html'];
			$doc2 = $htmls[0]['Redline']['converted_html'];
		}
		else {
			$doc1 = $htmls[0]['MasterDoc']['converted_html'];
			$doc2 = $htmls[0]['Redline']['converted_html'];
		}
		$htmls = $htmls[0];
		$diff = new doDiff();
		$difference = $diff->doHtmlDiff($doc1, $doc2);
		$dom = new DOMDocument;
		$dom->loadHTML($difference);
		$allElementsIns = $dom->getElementsByTagName('ins');
		$allElementsDel = $dom->getElementsByTagName('del');
		$diffCount = $allElementsIns->length + $allElementsDel->length;
		$this->saveDiffHtml(CakeSession::read('lastInsertedRedlineId'), $difference, $diffCount);
		return $diffCount;
	}
	
	public function uploadRedline($redlineData){
		// Make the entry in the files table
		$redlineData['Redline']['s3_key']['name'] = trim($redlineData['Redline']['s3_key']['name']);	
        $file = explode('.', $redlineData['Redline']['s3_key']['name']);
        if (strlen($file[0]) > Configure::read('FILENAME_STRING_LENGTH')) {
            $file[0] = substr($file[0], 0, Configure::read('FILENAME_STRING_LENGTH'));
        }
		
		// Get master doc user friendly Name
		$masterData = $this->Deal->find('first', array(
			'conditions' => array('Deal.id' => $redlineData['Redline']['dealRefId']),'fields' => array('MasterDoc.name', 'Deal.name')
		));
						
		$stage = $this->getRedlineReceiveCount($redlineData['Redline']['dealRefId'], true) . Configure::read('REDLINE_RECEIVED_STAGE');
		
		$s3_uri_path  =  CakeSession::read('user.Company.company_name') . '/'. $masterData['Deal']['name'] . '/'. $masterData['MasterDoc']['name'] . $stage . date('dmY') . '/';
		$filename = $file[0].'_'.date(Configure::read('DB_DATEIMG_FORMAT')).'.'.$file[1];
		// Put on Amazon bucket

                if($this->storeDoc($redlineData['Redline']['s3_key']['tmp_name'],$s3_uri_path, $filename)){
			$convertedHtml = $this->MasterDoc->convert($redlineData['Redline']['s3_key']['tmp_name']);
                    if($lastInsertedId = $this->File->saveData(array('File' => array('s3_key' => $filename, 's3_uri_path' => $s3_uri_path )), true, null)){
				$turn =  $this->find('count', array('conditions' => array('Deal.id' => $redlineData['Redline']['dealRefId'])));
				$stage = $this->getRedlineReceiveCount($redlineData['Redline']['dealRefId'], false) . ' Redline Received';
				$data['Redline'] = array(
					'deal_id' => $redlineData['Redline']['dealRefId'],
					'stage' =>  $stage, // TO be taken from the count and config file
					'turn' => $turn + 1,
					'material_changes'   => 0,
					'non_material_changes'   => 0,
					'converted_html' => $convertedHtml, 
					'total_changes'   => 0,
					'user_id' => CakeSession::read('user.User.id'),
					'files_id' => $lastInsertedId
				);
				// save the upload entry for redline 
                if($this->saveRedline($data)){
					CakeSession::write('lastInsertedRedlineId', $this->getLastInsertID());
					// Update the modified column of deals for this new revision
					$this->Deal->id = $redlineData['Redline']['dealRefId'];
					$this->Deal->Save(array('stage' =>  $stage, 'turn' => $turn + 1));
					 return $stage;
                } else {
					return Configure::read('RECEIVE_DEAL_FAIL');
				}
        }
        } else {
			return Configure::read('RECEIVE_DEAL_FAIL');
		}
        return Configure::read('RECEIVE_DEAL_SUCCESS');
    }

	
	Public function saveDiffHtml($dealId, $diffHtml, $diffCount) {
		if(!empty($dealId)) {
			$this->save(array('id' => $dealId, 'diff_html' => $diffHtml, 'total_changes' => $diffCount));
		}
	}
	
	public function getDealInfo($dealId) {
		// This will get the last document issued
		return $this->find('first', 
		array(
			'conditions' => array('Deal.id' => $dealId, 'Redline.stage LIKE' => '%Issued%'),
			'order' => array('Redline.id' => 'desc')
		));
	}

	public function getRedlineTurn($dealId) {
		// This will get the current turn
		return $this->find('first', 
		array(
			'fields' => ('Redline.turn'),
			'conditions' => array('Deal.id' => $dealId),
			'order' => array('Redline.id' => 'desc')
		));
	}
	
	public function getRedlineReceiveCount($dealId, $format) {
		// This will get the last document issued
		$cnt = $this->find('count', 
			array(
				'conditions' => array('Deal.id' => $dealId, 'Redline.stage LIKE' => '%Received%'),
				'order' => array('Redline.id' => 'desc')
			));
		$cnt++;
		return $this->addOrdinalNumberSuffix($cnt,$format);
	}
	
    public function storeDoc($tmpname, $s3_uri_path, $filename){
       
        $this->AmazoneFileSystem = ClassRegistry::init('AmazoneFileSystem');		
	return $this->AmazoneFileSystem->putObjectFile($tmpname, $this->AmazoneFileSystem->bucket, Configure::read('AWS_DOC_FOLDER') . $s3_uri_path . $filename, 'authenticated-read');
        /*    
        if(move_uploaded_file($tmpname, $filepath))
            return true;
        else
            return false;          
         */
    }
	
	function addOrdinalNumberSuffix($num, $path) {
		if (!in_array(($num % 100),array(11,12,13))) {
		  switch ($num % 10) {
				// Handle 1st, 2nd, 3rd
				case 1:  return ($path === true) ? $num.'st' : $num.'<sup>st</sup>';
				case 2:  return ($path === true) ? $num.'nd' : $num.'<sup>nd</sup>';
				case 3:  return ($path === true) ? $num.'rd' : $num.'<sup>rd</sup>';
			}
		}
		return ($path === true) ? $num .'th' : $num .'<sup>th</sup>';
	}
}


