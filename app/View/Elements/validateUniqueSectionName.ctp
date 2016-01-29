<div id="light3" class="white_content">
<br><br><br>
	<div id="confirm_message"><?php echo $validateUniqueSectionName; ?></div>
	<br><br><br>
	<?php echo $this->Form->submit(__('OK'), array('id' => 'cancelBut','type' => 'button','div' => false,'onclick' => "javascript:document.getElementById('light3').style.display='none';document.getElementById('fade3').style.display='none'")); ?>
</div>
<div id="fade3" class="black_overlay"></div>
<script type="text/javascript">
	$('#okBut').hide();
</script>