<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'Cosentium');
$company_url = Configure::read('SITE_BASE_URL');
?>
<!DOCTYPE html>
<html>
<head>
<?php echo $this->Html->charset(); ?>
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<!--[if lt IE 9]> 
    <script src="js/html5.js"></script> 
<![endif]-->
<title><?php echo $cakeDescription ?> : <?php echo $title_for_layout; ?></title>
<?php 
	echo $this->Html->css('style');
	echo $this->Html->css('jquery-ui-1.10.3');	
	echo $this->Html->script('jquery-2.0.3.min'); 
	echo $this->Html->script('jquery-ui-1.10.3'); 
?>
<script type="text/javascript">
$(document).ready(function(){
	// build a variable to target the #menu div
	var menu = $('.toplinks_dropdown-menu');
	var menu1 = $('.toplinks_dropdown-menu1');
	
	// bind a click function to the menu-trigger
	$('.dropdown-style').click(function(event){
		event.preventDefault();
        event.stopPropagation();
		// if the menu is visible slide it up
		if (menu.is(":visible"))
		{
			menu.slideUp('fast');
			$(this).removeClass('dropdown-style_hover');
			$('#arrowimg').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/user_icon_white_arrow.png');
		}
		// otherwise, slide the menu down
		else
		{
			menu.slideDown('fast');
			$(this).addClass('dropdown-style_hover');
			$('#arrowimg').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/user_icon_arrow.png');
			if (menu1.is(":visible"))
			{
				menu1.slideUp('fast');
				$('.dropdown-style1').removeClass('dropdown-style_hover');
				$('#arrowimg1').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/user_icon_white_arrow.png');
				$('#settingimg').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/settings.png');
			}
		}
	});

	$('.dropdown-style1').click(function(event){
		event.preventDefault();
        event.stopPropagation();
		// if the menu is visible slide it up
		if (menu1.is(":visible"))
		{
			menu1.slideUp('fast');
			$(this).removeClass('dropdown-style_hover');
			$('#arrowimg1').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/user_icon_white_arrow.png');
			$('#settingimg').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/settings.png');
		}
		// otherwise, slide the menu down
		else
		{
			menu1.slideDown('fast');
			$(this).addClass('dropdown-style_hover');
			$('#arrowimg1').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/user_icon_arrow.png');
			$('#settingimg').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/settingshover.png');
			if (menu.is(":visible"))
			{
				menu.slideUp('fast');
				$('.dropdown-style').removeClass('dropdown-style_hover');
				$('#arrowimg').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/user_icon_white_arrow.png');
			}
		}
	});
	
	
    $("body").not('.dropdown-style, .toplinks_dropdown-menu').click(function(event) {
		
		if (menu.is(":visible"))
		{
            menu.slideUp('fast');
			$('.dropdown-style').removeClass('dropdown-style_hover');
			$('#arrowimg').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/user_icon_white_arrow.png');
		}
    });
	$("body").not('.dropdown-style1, .toplinks_dropdown-menu1').click(function(event) {
		
		if (menu1.is(":visible"))
		{
            menu1.slideUp('fast');
			$('.dropdown-style1').removeClass('dropdown-style_hover');
			$('#arrowimg1').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/user_icon_white_arrow.png');
			$('#settingimg').attr('src','<?php echo Configure::read('SITE_BASE_URL');?>img/settings.png');
		}
    });

	
})
</script>
</head>

<body>
<div class="wrapper">
<div class="innercontainer">
<div class="innerheader">
<div class="inner_logo_panel">
    <div class="innerlogo">
        Cosentium
    </div>
	<div class="top_links">
    <div><?php echo $this->Session->read('user.User.username');?></div>
    <div class="dropdown-style" id="drop">
    <?php echo $this->Html->image($amazonS3ServerPath.$userProfPic , array('width' => '27px','height' => '26px','style' => 'vertical-align:middle'));?><?php echo $this->Html->image('user_icon_white_arrow.png', array('style' => 'vertical-align:middle;padding-left:2px;','id' => 'arrowimg'));?>
    </div>
    <div class="toplinks_dropdown-menu">
        <div class="user_pic_class"><?php echo $this->Html->image($amazonS3ServerPath.$userProfPic, array('width' => '78px','height' => '77px','style' => 'vertical-align:middle'));?></div>
    	<ul id="user-menu" >
        <li><?php echo $this->Html->link(__('My Profile Dashboard'),array('controller' => 'users', 'action' => 'dashboard'));?></li>
        <li><?php echo $this->Html->link(__('Personal Account Info'),array('controller' => 'users', 'action' => 'personalinfo'));?></li>
        <li><?php echo $this->Html->link(__('Profile Picture'),array('controller' => 'users', 'action' => 'profilePic'));?></li>
        <li><?php echo $this->Html->link(__('Manage Delegation'),array('controller' => 'users', 'action' => 'manageDelegation'));?></li>
        <li><?php echo $this->Html->link(__('Change Password'),array('controller' => 'users', 'action' => 'profilechangepassword'));?></li>
       </ul>
       <div class="last"><?php echo $this->Html->link(__('Logout'),array('controller' => 'users', 'action' => 'logout'));?></div>
    </div>
    <div class="dropdown-style1" id="drop1">
	<?php echo $this->Html->image('settings.png', array('style' => 'vertical-align:middle','id' => 'settingimg'));?><?php echo $this->Html->image('user_icon_white_arrow.png', array('style' => 'vertical-align:middle;','id' => 'arrowimg1'));?>
    </div>
    <div class="toplinks_dropdown-menu1">
        <ul id="user-menu1" >
        <li><?php echo $this->Html->link(__('Users'),array('controller' => 'users', 'action' => 'view'));?></li>
        <li><?php echo $this->Html->link(__('Roles'),array('controller' => 'roles', 'action' => 'index/'));?></li>
        <li><?php echo $this->Html->link(__('Master Document'),array('controller' => 'masterdocusers', 'action' => 'index'));?></li>
        <li><a href="#">SLA</a></li>
        <li><a href="#">Company Account</a></li>
       </ul>
    </div>
    <div><?php echo $this->Html->image('helpmenu.jpg', array('style' => 'vertical-align:middle')); ?></div>
    </div>
    
    <div style="clear:both"></div>
</div>
<?php echo $this->element('top_menu_nav'); ?>

</div>

<div class="innercontent">
			
		<?php echo $this->Session->flash(); ?>
		
		<?php echo $this->fetch('content'); ?>
			
</div>


<div style="clear:both"></div>
<footer>Copyright 2013 Cosentium Inc. | Terms of Use | Security | Privacy | <?php echo Configure::read('SITE_VERSION'); ?></footer>
</div>
</div>
</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>