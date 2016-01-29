<!-- app/View/Company/register.ctp -->
<div id="contactinner">
	<h1><?php echo __('Sign up for your free trial'); //echo __('Download '.Configure::read('FREE_TRIAL_PERIOD_IN_DAYS').' free trial.'); ?></br> <?php //echo __('Get started !'); ?></h1>
<?php echo $this->Form->create('Company'); ?>
    <fieldset>
        <?php 
		echo $this->Form->input('User.first_name',array('label' => __('First Name'), 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'),'required' => 'false'));
        echo $this->Form->input('User.last_name',array('label' => __('Last Name'), 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'),'required' => 'false'));
		echo $this->Form->input('User.username',array('label' => __('Email'),'required' => 'false'));		
		//echo $this->Form->input('phone',array('label' => __('Phone'), 'maxlength' => 10,'type' => 'text','required' => 'false'));?>
		<div>
		<?php echo $this->Form->label('phone1', __('Phone<span class="mandatory">*</span>')); ?>
		<?php echo $this->Form->input('phone1', array('label' => false,'id' => 'areaCode', 'div' => false,'error' => false,'required' => 'false','maxlength' => '3','style' =>'width:30px;')); ?><span class="forphonedash"> - </span>
		<?php echo $this->Form->input('phone2', array('label' => false,'id' => 'firstNum','div' => false,'error' => false,'required' => 'false','maxlength' => '3','style' =>'width:30px;')); ?><span class="forphonedash"> - </span>
		<?php echo $this->Form->input('phone3', array('label' => false,'id' => 'secNum','div' => false,'error' => false,'required' => 'false','maxlength' => '4','style' =>'width:40px;')); ?>
		<?php  
			if ($this->Form->isFieldError('phone1')) {
				echo $this->Form->error('phone1', null, array('class' => 'error-message'));
			} else if ($this->Form->isFieldError('phone2')) {
				echo $this->Form->error('phone2', null, array('class' => 'error-message'));
			} else if ($this->Form->isFieldError('phone3')) {
				echo $this->Form->error('phone3', null, array('class' => 'error-message'));
			} 
		?>
		</div>
		<?php
		echo $this->Form->input('job_title',array('label' => __('Job Title'),'required' => 'false'));
		echo $this->Form->input('company_name',array('label' => __('Company'),'required' => 'false'));
		?>		
		<?php echo $this->Form->label('employee_strength', __('Employees<span class="mandatory">*</span>')); ?>
		<div class="styled-select"><?php echo $this->Form->input('employee_strength', array('label' => false,'options' => $employee_strength_val, 'error' => false)); ?></div>
		<?php  echo $this->Form->error('employee_strength', null, array('class' => 'error-message')); ?>
		<?php
		/***** Emp Drop Down ****** 
		//echo $this->Form->input('employee_strength',array('label' => __('Employees'), 'options' => $employee_strength_val, 'after' => '<div class = "styled-select"></div>')); 		
		***** Emp Drop Down ******/ 

		/***** Open it window ****** 
		$popuplink = $this->html->link('Commercial Services Agreement', '#', array('onclick'=>"var openWin = window.open('".$this->html->url(array('action'=>'agreement'))."', '_blank', 'toolbar=0,scrollbars=1,location=0,status=1,menubar=0,resizable=1,width=500,height=500');  return false;"));		
		echo $this->Form->input('terms', array('div' => true, 'type' => 'checkbox', 'value' => 0, 'label' => false, 'before' => '<label for="UsersTerms">I agree to the '.$popuplink.' </label>'));
		***** Open it window ******/ 
				
		//echo $this->Form->input('terms', array('div' => true, 'type' => 'checkbox', 'value' => 0, 'label' => false, 'before' => '<label></label>', 'after' => '<div class="usernameStyleReg">I agree to the '.$this->Html->link('Commercial Services Agreement', array('controller' => 'Companies', 'action' => 'agreement'),array('target'=>'_blank')).' </div>')); 
    ?>
		<div><label></label><div class="usernameStyleReg"><?php echo $this->Form->input('terms', array('div' => false, 'type' => 'checkbox', 'value' => 0, 'label' => false,'error' => false)); ?> I agree to the <?php echo $this->Html->link('Commercial Services Agreement', array('controller' => 'Companies', 'action' => 'agreement'),array('target'=>'_blank')); ?></div><?php  echo $this->Form->error('terms', null, array('class' => 'error-message')); ?></div>
	
   <label></label>
<?php 
$options = array(
	//'id' => 'compSubmt',
    'label' => __('Begin free trial'),
);
echo $this->Form->end($options); ?>
</div>
</fieldset>
 
<script type="text/javascript">
	$(document).ready(function() {
		$("#UserFirstName").focus();
		$('div.checkbox').removeClass('required error');		
		
		$('#areaCode,#firstNum,#secNum').keyup(function(e){
			$(this).next().focus();
			if($(this).val().length==$(this).attr('maxlength')) {
				if($(this).attr('id') == 'areaCode')
					$('#firstNum').focus();
				if($(this).attr('id') == 'firstNum')
					$('#secNum').focus();
			}
		})

		
	});	
</script>

