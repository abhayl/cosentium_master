<div style="min-height:475px;">
	<div align="center" class="welcomecontainer"><?php echo __('Your email address has been updated successfully');?></div>
	<div align="center"><?php echo $this->Form->submit(__('Continue', true), array('onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/dashboard/')."'"));?></div>
</div>