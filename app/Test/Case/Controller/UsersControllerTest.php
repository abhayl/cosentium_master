<?php
class UsersControllerTest extends ControllerTestCase {
    public $fixtures = array('user');

    public function testIndex() {
        $result = $this->testAction('/users/index');
        debug($result);
    }
}
?>