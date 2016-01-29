<?php
class CompaniesControllerTest extends ControllerTestCase {
    public $fixtures = array('app.company');

    public function testIndex() {
        $result = $this->testAction('/companies/index');
        debug($result);
    }
}
?>