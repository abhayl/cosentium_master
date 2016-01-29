<?php echo $this->element('usersetup_left_links'); ?>
<div class="right">
	<div class="heading">Add New User</div>
	<div class = "dashinnerdiv">
	<?php if(isset($successMessage)) echo "<div class='successmsg'>".$successMessage."</div>";?>
	</div>
	<?php echo $this->Form->create('User');?>
		<fieldset>
			<div>
				<?php echo $this->Form->input('first_name',array('label' => __('First Name'),'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false)); ?>
			</div>
			<div>
				<?php echo $this->Form->input('last_name', array('label' => __('Last Name'),'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false)); ?>
			</div>
			<div>
				<?php echo $this->Form->input('username',array('label'=> __('Email Address'),'required' => 'false', 'div' => false)); ?>
			</div>
			<div>
				<?php echo $this->Form->label('role_id', __('Role')); ?>
				<div class="styled-select"><?php echo $this->Form->input(__('role_id'), array('label' => false,'options' => $roles,'empty' => '--No Selection--', 'error' => false));?></div>
				<?php  echo $this->Form->error('role_id', null, array('class' => 'error-message')); ?>
			</div>
			<div>
				<label></label>
				<?php echo $this->Form->submit(__('Save and Add New'), array('label' => false,'name' => 'new', 'div' => false));?>
				<?php echo $this->Form->submit(__('Save'), array('label' => false,'name' => 'ok', 'div' => false));?>
				<?php echo $this->Form->submit(__('Cancel'), array('label' => false,'onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/view')."'",'name' => 'cancel','div' => false));?>
			</div>
	</fieldset>
<?php echo $this->Form->end();?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#UserFirstName").focus(); 	
 	});	
</script>