<div id="light2" class="white_content">
<br><br><br>
	<div id="confirm_message"><?php echo $validateSectionName; ?></div>
	<br><br><br>
	<?php echo $this->Form->submit(__('OK'), array('id' => 'cancelBut','type' => 'button','div' => false,'onclick' => "javascript:document.getElementById('light2').style.display='none';document.getElementById('fade2').style.display='none'")); ?>
</div>
<div id="fade2" class="black_overlay"></div>
<script type="text/javascript">
	$('#okBut').hide();
</script>