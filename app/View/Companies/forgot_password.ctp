<!-- app/View/Company/forgot_password.ctp -->
<?php if(isset($succeMessage)) echo "<div class=\"successmsg\" style=\"text-align:left;\">$succeMessage</div>"; ?>
<div id="contactinner">
	<h1><?php echo __('Reset Password'); ?></h1>
	<?php echo __('Enter your Cosentium Username below.'); ?><br>
	<?php echo __('A unique link will be sent to the email address associated with your account. Click the link and follow the instructions to reset your password.'); ?><br>
	<strong><?php echo __('Username is always in the form of an email address.'); ?></strong><br><br><br>
	<?php echo $this->Form->create('User'); ?>
		<fieldset>
			<!--<legend>Sign up for your free trial</legend>-->
			<?php echo $this->Form->input('username',array('label' => __('Username'),'required' => 'false')); ?>
			<div><label></label><?php echo $this->Form->input(__('Submit'),array('div'=> false, 'type'=>'submit','label'=> false,'class' => 'signbtn'));?></div>
		</fieldset>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#UserUsername").focus();
	});	
</script>

