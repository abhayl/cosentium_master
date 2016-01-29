<div style="min-height:475px;">
	<div align="center" class="welcomecontainer"><?php echo __('Welcome to Cosentium');?></div>
	<div align="center"><?php echo $this->Form->submit(__('Done', true), array('type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/dashboard')."'",'name' => 'done', 'div' => false));?></div>
</div>