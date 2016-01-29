<?php 
class File extends AppModel {
	public $name = 'File';	
	
	function __construct() {
		parent::__construct();
		$this->tablePrefix = CakeSession::read('user.Company.prefix'). '_';
		$this->useTable = 'files';
	}
	
	/**
	 * @name 			saveData 
	 * @description		Save User Data         			
	 * @parm	        $userData Request Data 
	 * @return	        LastInsertId/false
	 */	
	public function saveData($fieldArr, $checkValidate=true, $id = null){
        if($id != NULL){
            $this->id = $id;
        }
        $this->save($fieldArr, $checkValidate);
        if($this->getLastInsertID() != null){
            return $this->getLastInsertID();
        }else {
            return true;
        }
        
    }	
}


