<div id="light" class="white_content">
<br><br><br>
	<div id="confirm_message"><?php echo $confirm_message; ?></div>
	<br><br><br>
	<?php echo $this->Form->submit(__('Confirm'), array('id' => 'confirmBut', 'div' => false, 'name' => 'confirm')); ?>
	<?php echo $this->Form->submit(__('Deactivate'), array('id' => 'deactivateBut','div' => false)); ?>
     <?php echo $this->Form->submit(__('Report'), array('id' => 'reportBut','div' => false)); ?>
        <?php echo $this->Form->submit(__('Cancel'), array('id' => 'cancelBut','type' => 'button','div' => false,'onclick' => "javascript:document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'")); ?>
	<?php echo $this->Form->submit(__('Ok'), array('id' => 'okBut','type' => 'button','div' => false,'onclick' => "javascript:document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'")); ?>
</div>
<div id="fade" class="black_overlay"></div>
<script type="text/javascript">
	$('#okBut').hide();
     $('#deactivateBut').hide();
     $('#reportBut').hide();
</script>