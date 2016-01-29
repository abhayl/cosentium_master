<div id="contactinner">
<h1><?php echo __('Your free trial period has expired');?></h1>
<?php echo __('Your free trial period has expired. Click "Continue" to subscribe now and continue using your Cosentium account. After 30 days, we will delete your account data if you have not subscribed.');?>
</div>
<div align="center" style= "margin-bottom:20px;margin-top:20px;">
<?php echo $this->Form->submit(__('Continue', true), array('onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/login/')."'"));?>
</div>
<div></div>