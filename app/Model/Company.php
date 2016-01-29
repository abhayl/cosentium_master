<?php 
// app/Model/Company.php
//App::uses('AuthComponent', 'Controller/Component');
class Company extends AppModel {
	public $name = 'Company';
	protected $employeeStrengthVal = array(	
							"" => "-- No Selection --",
							"1-5" => "1-5",
							"6-15" => "6-15",
							"16-50" => "16-50",
							"51-150" => "51-150",
							"151-500" => "151-500",
							"501-2,500" => "501-2,500",
							"2,501-10,000" => "2,501-10,000",
							"10,001-25,001" => "10,001-25,001",
							"over 25,001" => "over 25,001",
							);	
	public $validate = array(
		'company_name' => array(
			'rule1' => array('rule' => array('checkEmptyCompanyName'), 'message' => 'You must enter a value.'),
			//'rule2' => array('rule' => '/^[a-z0-9A-Z\s\.\,\'\/\&\(\)\[\]\{\}\-\+\!\:\;\$]+$/i', 'message' => 'Your entry contains a reserved character.  Entries can contain letters, numbers, and a limited set of special characters.'), 
			'rule3' => array('rule' => array('checkCompany'), 'message' => 'Company name already in use.')
		),
		'phone' => array(
			'rule1' => array('rule' => array('checkEmptyPhone'), 'message' => 'You must enter a value.'),
			'rule2' => array('rule' => '/^[0-9,]+$/','message' => 'Phone number must be 10 digits and consist only of numbers.'),
			'rule3' => array('rule' => array('minLength', 10), 'message' => 'Phone number must be 10 digits and consist only of numbers.'),
		),
		'phone1' => array(
			'rule1' => array('rule' => array('notEmpty'), 'message' => 'You must enter a value.'),
			'rule2' => array('rule' => '/^[0-9,]+$/','message' => 'Phone number must be 10 digits and consist only of numbers.'),
			'rule3' => array('rule' => array('minLength', 3), 'message' => 'Phone number must be 10 digits and consist only of numbers.'),
		),		
		'phone2' => array(
			'rule1' => array('rule' => array('notEmpty'), 'message' => 'You must enter a value.'),
			'rule2' => array('rule' => '/^[0-9,]+$/','message' => 'Phone number must be 10 digits and consist only of numbers.'),
			'rule3' => array('rule' => array('minLength', 3), 'message' => 'Phone number must be 10 digits and consist only of numbers.'),
		),
		'phone3' => array(
			'rule1' => array('rule' => array('notEmpty'), 'message' => 'You must enter a value.'),
			'rule2' => array('rule' => '/^[0-9,]+$/','message' => 'Phone number must be 10 digits and consist only of numbers.'),
			'rule3' => array('rule' => array('minLength', 4), 'message' => 'Phone number must be 10 digits and consist only of numbers.'),
		),
		'job_title' => array(
			'rule1' => array('rule' => array('checkEmptyJobTitle'), 'message' => 'You must enter a value.'),
			//'rule2' => array('rule' => '/^[a-z0-9A-Z\s\.\,\'\/\&\(\)\[\]\{\}\-\+\!\:\;\$]+$/i', 'message' => 'Your entry contains a reserved character.  Entries can contain letters, numbers, and a limited set of special characters.'),
		),
		'employee_strength' => array(
			'rule1' => array('rule' => array('checkEmpSize'), 'message' => 'Please select number of employees.'),
			'rule2' => array('rule' => array('checkEmpValidSize'), 'message' => 'Please select number of employees.')
		),
		'terms' => array(
            'rule1' => array ('rule' => array('checkTerms'),'message' => 'You must agree to the Commercial Services Agreement.')
        ),
    );
	/**
	 * @name 			checkRequiredField 
	 * @description		Check Field is empty or not for IE          			
	 * @parm	        $data
	 * @return	        true/false	 
	 */	 
	public function checkEmptyCompanyName($data) {
		$data['company_name'] = trim($data['company_name']);
		if(empty($data['company_name']))
			return false;
		else 
			return true;
    }
	/**
	 * @name 			checkEmptyPhone 
	 * @description		Check Field is empty or not for IE          			
	 * @parm	        $data
	 * @return	        true/false	 
	 */	 
	public function checkEmptyPhone($data) {
		$data['phone'] = trim($data['phone']);
		if(empty($data['phone']))
			return false;
		else 
			return true;
    }
	/**
	 * @name 			checkEmptyJobTitle 
	 * @description		Check Field is empty or not for IE          			
	 * @parm	        $data
	 * @return	        true/false	 
	 */	 
	public function checkEmptyJobTitle($data) {
		$data['job_title'] = trim($data['job_title']);
		if(empty($data['job_title']))
			return false;
		else 
			return true;
    }
	
	/**
	 * @name 			checkCompany 
	 * @description		Check Company Name exist or not         			
	 * @parm	        $data['company_name']
	 * @return	        true/false	 
	 */		
	public function checkCompany($data){
		$cnt = $this->find('count', array('conditions' => array('Company.company_name =' => $data['company_name'])));
		if($cnt != 0){
			return false;
		}else
			return true;
	}
	/**
	 * @name 			checkTerms 
	 * @description		Check Term Condition checked or not         			
	 * @parm	        $data['terms']
	 * @return	        true/false	 
	 */	
	public function checkTerms($data){
		if($data['terms'] == 0){
			return false;
		}
		return true;
	}
	/**
	 * @name 			checkEmpSize 
	 * @description		Check Employee drop down selected or not         			
	 * @parm	        $data['employee_strength']
	 * @return	        true/false	 
	 */	
	public function checkEmpSize($data){
		if(empty($data['employee_strength'])){
			return false;
		}
		return true;
	}
	/**
	 * @name 			checkEmpValidSize 
	 * @description		Check Employee drop down selected from array         			
	 * @parm	        $data['employee_strength']
	 * @return	        true/false	 
	 */	
	public function checkEmpValidSize($data){
		if(in_array($data['employee_strength'],$this->employeeStrengthVal)){
			return true;
		}
		return false;
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


	 public function createDocTables($data) {
		$this->query('CREATE TABLE IF NOT EXISTS '.$data.'_files (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `s3_key` varchar(50) NOT NULL,
					  `s3_uri_path` text(1000) DEFAULT NULL,
					  `created` datetime NOT NULL,
					  `modified` datetime DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8');
		
		$this->query('CREATE TABLE IF NOT EXISTS '.$data.'_master_docs (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(50) NOT NULL,
			  `available_after_dt` datetime DEFAULT NULL,
			  `available_until_dt` datetime DEFAULT NULL,
			  `converted_html` longtext,
			  `files_id` int(10) unsigned NOT NULL,
			  `validity` int(5) DEFAULT NULL,
			  `created` datetime NOT NULL,
			  `modified` datetime DEFAULT NULL,
			  `owner` int(10) DEFAULT NULL,
			  `available_deal_issue` tinyint(4) DEFAULT 0,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `name` (`name`),
			 FOREIGN KEY `files_id`(files_id) REFERENCES '.$data.'_files (id) ON DELETE RESTRICT ON UPDATE RESTRICT
		) ENGINE=INNODB DEFAULT CHARSET = utf8 AUTO_INCREMENT=1');
		
		$this->query('CREATE TABLE IF NOT EXISTS '.$data.'_sections (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `master_docs_id` int(10) unsigned NOT NULL,
			  `name` varchar(50) NOT NULL,
			  `created` datetime NOT NULL,
			  `modified` datetime DEFAULT NULL,
			  PRIMARY KEY (`id`,`name`,`master_docs_id`),
			FOREIGN KEY `master_docs_id`(master_docs_id) REFERENCES '.$data.'_master_docs (id) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=INNODB DEFAULT CHARSET = utf8 AUTO_INCREMENT=1');
		
		$this->query('CREATE TABLE IF NOT EXISTS '.$data.'_sections_users (
			`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`sections_id` INT(10) UNSIGNED NOT NULL,
			`user_id` INT(10) UNSIGNED NOT NULL,
			`representing_interest_of` VARCHAR(50) DEFAULT NULL,
			`custom_description` VARCHAR(100) DEFAULT NULL,
			`act_as` VARCHAR(20) DEFAULT NULL,
			`created` DATETIME NOT NULL,
			`modified` DATETIME DEFAULT NULL,
			`available_deal_issue` TINYINT(4) DEFAULT 0,
			`token_id` int(10) UNSIGNED NULL,
			PRIMARY KEY (`id`),
			FOREIGN KEY `sections_id`(sections_id) REFERENCES '.$data.'_sections (id) ON DELETE CASCADE ON UPDATE CASCADE,
			FOREIGN KEY `user_id`(user_id) REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
			FOREIGN KEY `token_id`(token_id) REFERENCES user_tokens (id) ON DELETE CASCADE ON UPDATE CASCADE
		)ENGINE=INNODB DEFAULT CHARSET = utf8 AUTO_INCREMENT=1');
		
		$this->query('CREATE TABLE IF NOT EXISTS '.$data.'_deals (
		  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		  `name` VARCHAR(200) NOT NULL,
		  `customer_name` CHAR(50) NOT NULL,
		  `recipient_name` CHAR(50) NOT NULL,
		  `recipient_email` VARCHAR(60) NOT NULL,
		  `created` DATETIME NOT NULL,
		  `modified` DATETIME DEFAULT NULL,
		  `stage` CHAR(50) NOT NULL,
		  `turn` TINYINT(4) UNSIGNED DEFAULT NULL,
		  `master_docs_id` INT(10) UNSIGNED NOT NULL,
		  `user_id` INT(10) UNSIGNED NOT NULL,
		  `email_id` INT(10) UNSIGNED NOT NULL,
		  `expiry` DATETIME DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `name` (`name`),
		  FOREIGN KEY `master_docs_id`(master_docs_id) REFERENCES '.$data.'_master_docs (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
		  FOREIGN KEY `email_id`(email_id) REFERENCES email_queues (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
		  FOREIGN KEY `user_id`(user_id) REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT
		) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');
		
		$this->query('CREATE TABLE IF NOT EXISTS '.$data.'_deal_revisions(
		  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		  `deal_id` INT(10) UNSIGNED NOT NULL,
		  `files_id` INT(10) UNSIGNED NOT NULL,
		  `converted_html` LONGTEXT,
		  `diff_html` LONGTEXT,
		  `created` DATETIME NOT NULL,
		  `modified` DATETIME DEFAULT NULL,
		  `stage` CHAR(50) NOT NULL,
		  `turn` TINYINT(4) UNSIGNED DEFAULT NULL,
		  `material_changes` TINYINT(4) UNSIGNED DEFAULT NULL,
		  `non_material_changes` TINYINT(4) UNSIGNED DEFAULT NULL,
		  `total_changes` int(10) UNSIGNED DEFAULT NULL,
		  `user_id` INT(10) UNSIGNED NOT NULL,
		  PRIMARY KEY (`id`),
		  FOREIGN KEY `deal_id`(deal_id) REFERENCES '.$data.'_deals (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
		  FOREIGN KEY `files_id`(files_id) REFERENCES '.$data.'_files (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
		  FOREIGN KEY `user_id`(user_id) REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT
		) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');
        return true;
    }
	
}


