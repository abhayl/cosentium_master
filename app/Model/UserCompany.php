<?php 
// app/Model/UserCompany.php
class UserCompany extends AppModel {
    public $validate = array(
        'delegate_to' => array(
            'rule1' => array('rule' => array('checkDelegate'), 'message' => 'You must select a user.'),
        )
    );
    public function checkDelegate($data) {
            foreach ($data as $key => $value) {
                if ($value == '') {
                    return FALSE;
                } else {
                    continue;
                }
            }
            return TRUE;
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
?>