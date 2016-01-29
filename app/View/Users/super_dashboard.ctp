<?php
echo '&nbsp;&nbsp;&nbsp;system admin<br>';

echo '&nbsp;&nbsp;&nbsp;Personal Setup<br>';
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->Html->link('Personal info', array('controller' => 'users', 'action' => 'personalinfo',$users['User']['id'],'?' => array('action' => 'personalinfo')))."<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->Html->link('Profile Picture', array('controller' => 'users', 'action' => 'profilePic',$users['User']['id']))."<br>";
echo "&nbsp;&nbsp;&nbsp;".$this->Html->link('Users list', array('controller' => 'users', 'action' => 'view'))."<br>";
echo "&nbsp;&nbsp;&nbsp;".$this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'))."<br>";
?>
