<div class="innercontent">
<div class="full_innercontent">
	<div class="heading"><?php echo __('Issue a New Deal');?></div>
	<?php if(isset($successMessage)) echo '<div class = "dashinnerdiv"><div class="successmsg">' . $successMessage . '</div></div>';?>
	<div class="usernameStyleAddUser blueheading"><?php echo __('Customer Deal Information');?></div>
	<?php echo $this->Form->create('Deal');?>
		<fieldset>
			<?php if( $UnSaved['name'] != '' ) {?>
			<div>
				<?php echo $this->Form->input('name', array('label' => __('Deal Name'), 'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false, 'value' => $UnSaved['name'])); ?>
			</div>
			<div>
				<?php echo $this->Form->input('customer_name', array('label' => __('Customer Name'), 'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false, 'value' => $UnSaved['customer_name'])); ?>
			</div>
			<div>
				<?php echo $this->Form->input('recipient_name', array('label' => __('Recipient Name'), 'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false, 'value' => $UnSaved['recipient_name'])); ?>
			</div>
			<div>
				<?php echo $this->Form->input('recipient_email', array('label'=> __('Recipient Email Address'), 'required' => 'false', 'div' => false, 'value' => $UnSaved['recipient_email'])); ?>
			</div>
			<div>
				<?php echo $this->Form->label('master_docs_id', __('Master Document to Issue')); ?>
				<div class="styled-select"><?php echo $this->Form->input(__('master_docs_id'), array('label' => false, 'options' => $MastersDocsDd, 'error' => false, 'default' => $selectedOption));?></div>
				<?php  echo $this->Form->error('master_docs_id', null, array('class' => 'error-message')); ?>
			</div>
			<?php } else {?>
			<div>
				<?php echo $this->Form->input('name', array('label' => __('Deal Name'), 'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false)); ?>
			</div>
			<div>
				<?php echo $this->Form->input('customer_name', array('label' => __('Customer Name'), 'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false)); ?>
			</div>
			<div>
				<?php echo $this->Form->input('recipient_name', array('label' => __('Recipient Name'), 'required' => 'false', 'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'), 'div' => false)); ?>
			</div>
			<div>
				<?php echo $this->Form->input('recipient_email', array('label'=> __('Recipient Email Address'), 'required' => 'false', 'div' => false)); ?>
			</div>
			<div>
				<?php echo $this->Form->label('master_docs_id', __('Master Document to Issue')); ?>
				<div class="styled-select"><?php echo $this->Form->input(__('master_docs_id'), array('label' => false, 'options' => $MastersDocsDd, 'error' => false, 'default' => 0));?></div>
				<?php  echo $this->Form->error('master_docs_id', null, array('class' => 'error-message')); ?>
			</div>
			<?php } ?>
			<div>
				<label></label>
				<?php echo $this->Form->submit(__('Next'), array('label' => false, 'name' => 'next', 'div' => false));?>
				<?php echo $this->Form->submit(__('Cancel'), array('label' => false, 'onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'deals/view')."'",'name' => 'cancel', 'div' => false));?>
			</div>
	</fieldset>
</div>
</div>
<?php echo $this->Form->end();?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#DealName").focus(); 	
 	});	
</script>