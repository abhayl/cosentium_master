<!-- app/View/Users/changepassword.ctp -->
<?php echo $this->element('personalinfo_left_links'); ?>
<div class="right">
	<div class="heading"><?php echo __('Change Password');?></div>
		<?php echo $this->Form->create('User');?>
		   <fieldset>
				<div>
					<label>Username</label>
					<div class="usernameStyleAddUser"><?php echo $user['User']['username']; ?></div>
				</div>
				<?php 
					echo $this->Form->input('old_password',array('label' => __('Old Password'), 'type' => 'password','required' => 'false'));
					echo $this->Form->input('password',array('label' => __('New Password'),'value' => '','after' => '<div id="result"></div>','required' => 'false'));
					echo $this->Form->input('new_password',array('label' => __('Verify New Password'), 'value' => '', 'type' => 'password','required' => 'false'));
				?>
				<div>
				<?php echo $this->Form->label('security_question_id', __('Question<span class="mandatory">*</span>')); ?>
				<div class="styled-select" style="width:370px;"><?php echo $this->Form->input('security_question_id', array('label' => false,'options' => $secrets_question_data, 'error' => false,'style' =>'width:390px;')); ?></div>		
				<?php  echo $this->Form->error('security_question_id', null, array('class' => 'error-message')); ?>	
				</div>
				<?php
					echo $this->Form->input('security_answer',array('label' => __('Answer'),'maxlength' => 20,'required' => 'false'));
					echo $this->Form->hidden('id', array('value' => $user['User']['id'],'type' => 'hidden'));		
				?>
				<div>
					<label></label>
					<?php echo $this->Form->submit(__('Save'), array('label' => false,'name' => 'ok', 'div' => false));?>
					<?php echo $this->Form->submit(__('Cancel'), array('type'=>'button','label' => false,'onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/dashboard')."'",'name' => 'cancel','div' => false));?>
				</div>
			</fieldset>
		</form>
</div>
	
<?php echo $this->Html->script('pass_strength'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#UserOldPassword").focus();
		
 		$('#UserPassword').keyup(function(){
			$('#result').html(checkStrength($('#UserPassword').val(),'<?php echo Configure::read('SITE_BASE_URL');?>'))
		})
		$('#UserOldPassword').val('');	
 	});	
</script>

