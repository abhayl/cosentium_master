<?php 
	switch($this->request->params['controller']) {
		case 'users' : 
		default	:
					$link1_arr = array("id"=>"home","class" => "active");
					$link2_arr = array("id"=>"deals","class" => "");
					$link3_arr = array("id"=>"docs","class" => "");
					break;
		case 'deals' : 
					$link1_arr = array("id"=>"home","class" => "");
					$link2_arr = array("id"=>"deals","class" => "active");
					$link3_arr = array("id"=>"docs","class" => "");
					break;
		case 'masterdocs' :
					$link1_arr = array("id"=>"home","class" => "");
					$link2_arr = array("id"=>"deals","class" => "");
					$link3_arr = array("id"=>"docs","class" => "active");
					break;
					
					
	}
?>
<div class="menu_div">
<ul>
<li><?php echo $this->Html->link(__('Home'),array('controller' => 'users', 'action' => 'dashboard'),$link1_arr);?></li>
<li><?php echo $this->Html->link(__('Deals'),array('controller' => 'deals', 'action' => 'view'),$link2_arr);?></li>
<li><a href="#" id="work"><?php echo __('Work Items');?></a></li>
<li><?php echo $this->Html->link(__('Documents'),array('controller' => 'masterdoclib', 'action' => 'index'),$link3_arr);?></li>
<li><a href="#" id="rpt"><?php echo __('Reports');?></a></li>
<!--<li><a href="#"><?php echo __('Feedback');?></a></li>
<li><a href="#"><?php echo __('Blog');?></a></li>
<li><a href="#"><?php echo __('Contact Us');?></a></li>
<li><?php echo $this->Html->link(__('Logout'),array('controller' => 'users', 'action' => 'logout'));?></li>-->
</ul>
</div>
