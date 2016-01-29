<?php
/*********************
* To Run this go to application directory,
* $ Console/cake email 
* Sample Setting 
* # m h dom mon dow command
* # 5 *   *   *   * cd /full/path/to/app && Console/cake emails
*********************/
class EmailsShell extends AppShell {
	public $uses = array('EmailQueue');
	
    public function main() {
       //$this->out('Hello world.');
	   $resultData = $this->EmailQueue->sendEmailQueue();
	   $this->out(print_r($resultData, true));
    }
}

?>