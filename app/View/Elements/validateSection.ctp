<div id="light1" class="white_content">
<br><br><br>
	<div id="confirm_message"><?php echo $confirm_message; ?></div>
	<br><br><br>
	<?php echo $this->Form->submit(__('OK'), array('id' => 'cancelBut','type' => 'button','div' => false,'onclick' => "javascript:document.getElementById('light1').style.display='none';document.getElementById('fade1').style.display='none'")); ?>
</div>
<div id="fade1" class="black_overlay"></div>
<script type="text/javascript">
	$('#okBut').hide();
</script>