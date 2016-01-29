<?php if (empty($checkAction)) { ?>
<?php echo $this->element('usersetup_left_links'); ?>
<div class="right">
	<div class="heading">Edit Users</div>
	<?php echo $this->Form->create('User');?>
		<fieldset>
			<div>
				<?php echo $this->Form->input('first_name',array('label' => __('First Name'), 'value' => $user['User']['first_name'], 'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false)); ?>
			</div>
			<div>
				<?php echo $this->Form->input('last_name', array('label' => __('Last Name'), 'value' => $user['User']['last_name'], 'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false)); ?>
			</div>
			<div>
				<label for="email"><?php echo __('Email Address');?></label>
				<div class="usernameStyleAddUser"><?php echo $user['User']['username']; ?></div>
			</div>
			<div>
				<?php echo $this->Form->label('role_id', __('Role')); ?>
				<div class="styled-select"><?php echo $this->Form->input('role_id', array('error' => false, 'value' => $user['UserCompany']['role_id'], 'label' => false,'options' => $roles,'empty' => '--No Selection--', 'div' => false));?></div>
				<?php  echo $this->Form->error('role_id', null, array('class' => 'error-message')); ?>
			</div>
			<div>
				<label for="status"><?php echo __('Status');?></label>
				<div class="usernameStyleAddUser"><?php echo $this->Form->radio('UserCompany.status', array(1 => ' Active ', 0 => ' Inactive '), array('name' => 'status', 'value' => $user['UserCompany']['status'], 'legend' => false, 'label' => false));?></div>
			</div>
			<div>
				<label></label>
				<?php echo $this->Form->submit(__('Save'),array('id' => 'save', 'label' => false,'div' => false));?>
				<?php echo $this->Form->submit(__('Cancel'), array('label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/view')."'",'div' => false)); ?>
			</div>
		</fieldset>
	<?php echo $this->Form->end(); 
	
} else { ?>
<?php echo $this->element('personalinfo_left_links'); ?>
<div class="right">
	<div class="heading">Edit Personal Account Information</div>
	<?php echo $this->Form->create('User');?>
	<fieldset>
		<div>
			<?php echo $this->Form->input('first_name',array('value' => $user['User']['first_name'],'label' => __('First Name'),'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false)); ?>
		</div>
		<div>
			<?php echo $this->Form->input('last_name', array('value' => $user['User']['last_name'],'label' => __('Last Name'),'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false)); ?>
		</div>
                <div>
			<?php echo $this->Form->input('username', array('value' => $user['User']['username'], 'label' => __('Email'),'required' => 'false','div' => false)); ?>
		</div>
		<div>
			<label for="email"><?php echo __('Company');?></label>
			<div class="usernameStyle"><?php echo $user['Company']['company_name']; ?></div>
		</div>
		<div>
			<?php echo $this->Form->label('role_id', __('Role')); ?>
			<div class="usernameStyle"><?php echo $user['Role']['name']; ?></div>
		</div>
		<div>
			<label for="status"><?php echo __('Status');?></label>
			<div class="usernameStyle"><?php if(!$user['UserCompany']['status']){
				echo __('Inactive');
			}else{
				echo __('Active');
			} ?></div>
		</div>
		<div>
			<label></label>
			<?php echo $this->Form->submit(__('Save'),array('label' => false,'div' => false));?>
			<?php echo $this->Form->submit(__('Cancel'), array('label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/personalinfo/')."'",'div' => false)); ?>
		</div>
	</fieldset>
<?php echo $this->Form->end();
}?>
     <?php echo $this->element('overlay'); ?>
     <?php echo $this->Form->create('UserId',array('url' => array('controller' => 'users', 'action' => 'viewUserReport'))); ?>
    <?php echo $this->Form->input('userid',array('value' => $user['User']['id'], 'id' => 'userid', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
    <?php echo $this->Form->end();?>
<script type="text/javascript">
	$(document).ready(function() {
         $("#UserFirstName").focus();
          $('#save').click(function(event) {
            event.preventDefault();
            var dbval = "<?php echo $user['UserCompany']['status']; ?>";
            var selected = $("input[type='radio'][name='status']:checked");
            if (selected.val() == 0 && dbval != 0) {
                document.getElementById('light').style.display='block';
                document.getElementById('fade').style.display='block';
                <?php if (isset($confirm_message1)) { ?>
                    $('#confirm_message').html('<?php echo $confirm_message1; ?>');
                    $('#deactivateBut').show();
                    $('#cancelBut').show();
                    $('#deactivateBut').click(function() {
                        document.getElementById('UserEditForm').submit();
                    });
                <?php } else {
                    ?>
                    $('#confirm_message').html('<?php echo $confirm_message2; ?>');
                    $('#reportBut').show();
                    $('#cancelBut').hide();
                    $('#reportBut').click(function() {
                        document.getElementById('UserIdEditForm').submit();
                    });
                <?php } ?>
                $('#confirmBut').hide();
                $('#okBut').hide();
            } else {
                $('#UserEditForm').submit();
            }
        })  
 	});
     
</script>