<!-- app/View/Company/forgot_password.ctp -->
<?php if(isset($succeMessage)) echo "<div class=\"successmsg\">$succeMessage</div>"; ?>
<div id="contactinner">
	<h1><?php echo __('Reset Password'); ?></h1>
	<?php echo $this->Form->create('User'); ?>
		<fieldset>
			<!--<legend>Sign up for your free trial</legend>-->
			<div>
			<label for="username">Question:</label>
			<!--<input type="text" id="username" placeholder="mr.someone@email.com" />-->
			<div class="usernameStyle"><?php echo $security_question; ?> <!--What is your mother's maiden name?--> </div>
			</div>
			<div>
			<?php echo $this->Form->input('security_answer',array('label' => __('Answer'),'maxlength' => 20,'required' => 'false')); ?>
			<!--<label for="answer">Answer</label>
			<input type="text" id="answer" placeholder="" />-->
			</div>
			<?php echo $this->Form->hidden('id', array('value' => $userData['User']['id'],'type' => 'hidden')); 
				echo $this->Form->hidden('token', array('value' => $token,'type' => 'hidden'));			
			?>
			
			<div><label></label><?php echo $this->Form->input(__('Submit'),array('div'=> false, 'type'=>'submit','label'=> false,'class' => 'signbtn'));?></div>
		</fieldset>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#UserSecurityAnswer").focus();
	});	
</script>

