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

<title><?php echo $cakeDescription ?> : <?php echo $title_for_layout; ?></title>

	<?php
		//echo $this->Html->meta('icon');

		echo $this->Html->css('style');

		/*echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		*/
		echo $this->Html->script('jquery-2.0.3.min'); 
		
	?>

<!--[if lt IE 9]> 
        <script src="js/html5.js"></script> 
    <![endif]-->

<script>
/*jQuery(function() {
jQuery.support.placeholder = false;
webkit_type = document.createElement('input');
if('placeholder' in webkit_type) jQuery.support.placeholder = true;
});
$(function() {
 
if(!$.support.placeholder) {
 
var active = document.activeElement;
 
$(':text, textarea, :password').focus(function () {
 
if (($(this).attr('placeholder')) && ($(this).attr('placeholder').length > 0) && ($(this).attr('placeholder') != '') && $(this).val() == $(this).attr('placeholder')) {
$(this).val('').removeClass('hasPlaceholder');
}
}).blur(function () {
if (($(this).attr('placeholder')) && ($(this).attr('placeholder').length > 0) && ($(this).attr('placeholder') != '') && ($(this).val() == '' || $(this).val() == $(this).attr('placeholder'))) {
$(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
}
});
 
$(':text, textarea, :password').blur();
$(active).focus();
$('form').submit(function () {
$(this).find('.hasPlaceholder').each(function() { $(this).val(''); });
});
}
});*/
</script>

</head>

<body>
<div class="wrapper">
<div id="container">
<header>
	<div class="logo">
		<h1>Cosentium<?php //echo $this->Html->link($cakeDescription, $company_url); ?> </h1>	
    </div>
    <div class="help_menu">
    <!--Need Help?-->
    </div>
    <div class="clr"></div>
</header>
<div class="content">
	<div class="content_container">
			
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
			
</div>
<footer>Copyright 2013 Cosentium Inc. | Terms of Use | Security | Privacy | <?php echo Configure::read('SITE_VERSION'); ?></footer>
</div>
</div>
</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>	
	
