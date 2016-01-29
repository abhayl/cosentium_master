<?php
class MasterdocUser extends AppModel {
    public $name = 'MasterdocUser';

	function __construct() {
		parent::__construct();
		$this->tablePrefix = CakeSession::read('user.Company.prefix'). '_';
		$this->useTable = 'master_docs';
	}
	
	public $belongsTo=array (
			'User' => array(
                    'classname' => 'User',
                    'foreignKey' => false,
                    'conditions' => 'User.id = MasterdocUser.owner'
            )
        );

     public function getMasterdocCondition($filterCondition){
        // Listing Masterdocs based on the selected condition
        if ($filterCondition == Configure::read('DEFAULT_FILTER')) {
            // 1. Getting master docs with logged in user as owner
            $masterdocCondition = array(
                'owner ' => CakeSession::read('user.User.id'),
				'available_deal_issue ' => 1
            );
        } else {
            $masterdocCondition = array(
				'available_deal_issue ' => 1
			);
        }
        return $masterdocCondition;
    }
}
?>
