<?php 
class Section extends AppModel {
	public $name = 'Section';	
	
	public $belongsTo=array (
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
            )
        );
		
	function __construct() {
		parent::__construct();
		$this->tablePrefix = CakeSession::read('user.Company.prefix'). '_';
		$this->useTable = 'sections';
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