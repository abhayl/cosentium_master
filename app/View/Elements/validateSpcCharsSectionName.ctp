<div id="light5" class="white_content">
<br><br><br>
	<div id="confirm_message"><?php echo $validateSpcCharsSectionName; ?></div>
	<br><br><br>
	<?php echo $this->Form->submit(__('OK'), array('id' => 'cancelBut','type' => 'button','div' => false,'onclick' => "javascript:document.getElementById('light5').style.display='none';document.getElementById('fade5').style.display='none'")); ?>
</div>
<div id="fade5" class="black_overlay"></div>
<script type="text/javascript">
	$('#okBut').hide();
</script>