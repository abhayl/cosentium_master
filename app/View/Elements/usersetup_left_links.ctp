<div class="left">
	<div class="links">
<?php
	switch($this->request->params['controller']) {
		default :
						$link1_arr = array('id' =>'link1');
						$link2_arr = array('id' =>'link2');
						$link3_arr = array('id' =>'link3');
						$link4_arr = array('id' =>'link4');
						break;
							
		case 'users' :
						if($this->request->params['action'] == 'dashboard') {
							$link1_arr = array('id' =>'link1');
							$link2_arr = array('id' =>'link2');
							$link3_arr = array('id' =>'link3');
							$link4_arr = array('id' =>'link4');
						} else { 
							$link1_arr = array('id' =>'link1', 'class' => 'act');
							$link2_arr = array('id' =>'link2');
							$link3_arr = array('id' =>'link3');
							$link4_arr = array('id' =>'link4');
						}
						break;
							
		case 'roles' :
						$link1_arr = array('id' =>'link1');
						$link2_arr = array('id' =>'link2', 'class' => 'act');
						$link3_arr = array('id' =>'link3');
						$link4_arr = array('id' =>'link4');
						break;
        case 'masterdocusers' :
        case 'masterdocs' :
		case 'master_docs' :
		case 'masterDocs' :	
		case 'MasterDocs' :			
						$link1_arr = array('id' =>'link1');
						$link2_arr = array('id' =>'link2');
						$link3_arr = array('id' =>'link3', 'class' => 'act');
						$link4_arr = array('id' =>'link4');
						break;
							
	}
?>
<?php 
	echo $this->Html->link(__('Users'), array('controller' => 'users', 'action' => 'view'), $link1_arr );
	echo $this->Html->link(__('Roles'), array('controller' => 'roles', 'action' => 'index/'), $link2_arr );
	echo $this->Html->link(__('Master Document'), array('controller' => 'masterdocusers', 'action' => 'index'), $link3_arr );
	//echo $this->Html->link(__('Workflow'), array('controller' => '', 'action' => '#'), $link4_arr );
?>
<a href="#" id="link4"><?php echo __('Workflow');?></a>
	</div>
</div>