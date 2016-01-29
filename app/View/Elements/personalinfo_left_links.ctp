<div class="left">
	<div class="links">
<?php
	
	switch($this->request->params['action']) {
		case 'personalinfo' : 
								$link5_arr = array('id' => 'link5', 'class' => 'act');
								$link6_arr = array('id' => 'link6');
								$link7_arr = array('id' => 'link7');							
								$link8_arr = array('id' => 'link8');
								break;
		case 'edit' : 
								$link5_arr = array('id' => 'link5', 'class' => 'act');
								$link6_arr = array('id' => 'link6');
								$link7_arr = array('id' => 'link7');							
								$link8_arr = array('id' => 'link8');
								break;								
		case 'profilePic' : 
								$link5_arr = array('id' => 'link5');
								$link6_arr = array('id' => 'link6', 'class' => 'act');
								$link7_arr = array('id' => 'link7');							
								$link8_arr = array('id' => 'link8');
								break;								
         case 'manageDelegation' : 
								$link5_arr = array('id' => 'link5');
								$link6_arr = array('id' => 'link6');
								$link7_arr = array('id' => 'link7', 'class' => 'act');							
								$link8_arr = array('id' => 'link8');
								break;
		case 'profilechangepassword' : 
								$link5_arr = array('id' => 'link5');
								$link6_arr = array('id' => 'link6');
								$link7_arr = array('id' => 'link7');							
								$link8_arr = array('id' => 'link8', 'class' => 'act');
								break;
	}
?>
<?php 
	echo $this->Html->link(__('Personal Account Info'), array('controller' => 'users', 'action' => 'personalinfo','?' => array('action' => 'personalinfo')), $link5_arr);
	echo $this->Html->link(__('Profile Picture'), array('controller' => 'users', 'action' => 'profilePic'), $link6_arr);
	echo $this->Html->link(__('Manage Delegation'), array('controller' => 'users', 'action' => 'manageDelegation'), $link7_arr);
	echo $this->Html->link(__('Change Password'), array('controller' => 'users', 'action' => 'profilechangepassword'), $link8_arr);
?>	

	</div>
</div>