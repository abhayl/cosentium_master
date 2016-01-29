<!-- app/View/Users/add.ctp -->
<div id="contactinner">
	<h1><?php echo $change_password_heading; ?></h1>
	<?php echo $change_password_body_text;?>
	<?php echo $this->Form->create('User', array('id' => '')); ?>
    <fieldset>
		<div><label for="username">Username</label><div class="usernameStyle"><?php echo $userData['User']['username']; ?></div></div>
        
        <?php 
        echo $this->Form->input('password',array('label' => __('New Password'),'after' => '<div id="result"></div>','required' => 'false'));
		//echo "<span id=\"result\"></span>";		
		echo $this->Form->input('new_password',array('label' => __('Verify New Password'), 'type' => 'password','required' => 'false'));
		
		/*echo $this->Form->input('security_question_id', array(
			'label' => __('Question'),
            'options' => $secrets_question_data,
			'class' => 'styled-select'
		));*/
		?> 
		<div>
		<?php echo $this->Form->label('security_question_id', __('Question<span class="mandatory">*</span>')); ?>
		<div class="styled-select" style="width:370px;"><?php echo $this->Form->input('security_question_id', array('label' => false,'options' => $secrets_question_data, 'error' => false,'style' =>'width:390px;')); ?></div>		
		<?php  echo $this->Form->error('security_question_id', null, array('class' => 'error-message')); ?>	
		</div>
		
		<?php
		echo $this->Form->input('security_answer',array('label' => __('Answer'),'maxlength' => 20,'required' => 'false'));
		echo $this->Form->hidden('id', array('value' => $userData['User']['id'],'type' => 'hidden'));
		echo $this->Form->hidden('token', array('value' => $token,'type' => 'hidden'));		
    ?>
    <label></label>
<?php 
$options = array(
	'label' => __('Save'),
);
echo $this->Form->end($options); ?>
 </fieldset>
</div>
<?php echo $this->Html->script('pass_strength'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#UserPassword").focus();
		
 		$('#UserPassword').keyup(function(){
			$('#result').html(checkStrength($('#UserPassword').val(),'<?php echo Configure::read('SITE_BASE_URL');?>'))
		})
		$('#UserPassword').val('');
		$('#UserNewPassword').val('');		
 	});	
</script>

