<div id="contactinner">
<h1><?php echo __('Inactive user account');?></h1>
<?php echo __('Your user account is currently inactive.  Contact your system administrator and ask to have your account reactivated.');?>
</div>
<div align="center" style= "margin-bottom:20px;margin-top:20px;">
<?php echo $this->Form->submit(__('Continue', true), array('onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/login/')."'"));?>
</div>
<div></div>