<!-- app/View/EmailQueues/index.ctp -->
<div id="contactinner">
	<h1><?php echo __('Sending Email Queue Testing'); ?></br></h1>
	<?php echo $this->Html->link(__('Email Queue Testing'),array('controller' => 'emailqueues', 'action' => 'index'));?>
	
	<?php if(isset($Total)) echo "<div class=\"successmsg\" style=\"text-align:left;\">$Total</div>"; ?>
	</br>
	<?php if(isset($Succ)) echo "<div class=\"successmsg\" style=\"text-align:left;\">$Succ</div>"; ?>
	</br>
	<?php if(isset($Fail)) echo "<div class=\"successmsg\" style=\"text-align:left;\">$Fail</div>"; ?>
	</br>
 
<script type="text/javascript">
	$(document).ready(function() {
	});	
</script>

