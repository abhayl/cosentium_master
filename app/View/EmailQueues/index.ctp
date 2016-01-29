<!-- app/View/EmailQueues/index.ctp -->
<?php 
if(!isset($view)) { 
?>
<div class="full_innercontent">
<div class="heading"><?php echo __('Review and Send'); ?></div>
	<div class="usernameStyleAddUser blueheading"><?php echo __('The following will be sent to the customer: '); ?>&nbsp;<?php echo $this->Html->link(__('Send Email Queue'),array('controller' => 'emailqueues', 'action' => 'sendEmail'));?></div>
	<?php echo $this->Form->create('EmailQueue'); ?>
	
       <fieldset>
			 <?php echo $this->Form->input('to_email',array('label' => __('To email'), 'type' => 'hidden', 'value' => 'rahulth@test.com','maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'),'required' => 'false')); ?>
       		         
       		<div>
                <label for="to">To</label>
                <div class="usernameStyleAddMaster">Recipient Name(<?php echo 'rahulth@test.com';?>)</div>
            </div>
            <div>
                <label for="cust_name">From</label>
                <div class="usernameStyleAddMaster">Contracts Administrator(donotreply@cosentium.com)</div>
            </div>
            <div>
                <label for="recipient_name">Cc</label>
                <div class="usernameStyleAddMaster">Salesperson's Name(<?php echo 'rahulth@test.com';?>)</div>
            </div>
			<?php 
				 echo $this->Form->input('add_cc',array('label' => __('Additional Cc'), 'value' => '','required' => 'false','after' =>'<span class="reviewdeal_righttext">Enter email addresses separated by commas</span>','div'=>array('id' => 'addCC')));
				 
				 echo $this->Form->input('bcc',array('label' => __('Bcc'), 'value' => '','required' => 'false','after' => '<span class="reviewdeal_righttext">Enter email addresses separated by commas</span>','div'=>array('id' => 'BCC')));
				 
				 echo $this->Form->input('email_subject_line',array('label' => __('Subject'),'placeholder' =>'Emailing <Document File Name> Issued by <Company Name>', 'value' =>'', 'required' => 'false'));
				 
			?>
            <div>
                <label for="attachments">Attachments</label>
                <div class="usernameStyleAddMaster">system created file name</div>
            </div>
			<div>
            	<label></label>
				<?php echo $this->Form->textarea('email_body_text',array('label' => __('Email Body'),'required' => 'false','value' => 'The attached document has been issued for your review')); ?>
			</div>
			<?php 
			
				echo $this->Form->input('attachement_file_name', array('value' => 'use case issue a deal 08152013.docx','type' => 'hidden','readonly' => 'readonly'));
				echo $this->Form->hidden('user_id', array('value' => $userData['User']['id'],'type' => 'hidden'));
				echo $this->Form->hidden('cc_user_id', array('value' => $userData['User']['id'],'type' => 'hidden'));
				echo $this->Form->hidden('company_id', array('value' => $userData['User']['UserCompany']['company_id'],'type' => 'hidden'));
			?>
           
            <div>
                <label></label>
				<?php echo $this->Form->submit(__('Send'), array('label' => false,'name' => 'send', 'div' => false));?>
					<?php echo $this->Form->submit(__('Cancel'), array('type'=>'button','label' => false,'onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/view')."'",'name' => 'cancel','div' => false));?>
				
                <?php 
					//echo $this->Form->input(__('Send'),array('div'=> false, 'type'=>'submit','label'=> false));
					//echo $this->Form->submit(__('Cancel'), array('div'=> false,'type'=>'button','label' => false,'onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/dashboard')."'",'name' => 'cancel','div' => false));?>
				<!--<input type="submit" name="send" value="Send"><input type="submit" name="cancel" value="Cancel">-->
            </div>
        </fieldset>
    </form>
    
</div>
<div style="clear:both"></div>
<?php } else { 
//pr($emailQueueData);
?>
<div class="full_innercontent">
<div class="heading"><?php echo __('View Issue Deal Email'); ?></div>
	<?php if(isset($emailQueueData['EmailQueue']['sent_dt'])) {	?>
	<div class="usernameStyleAddUser blueheading">The following was sent to the customer on <?php echo date("m/d/y",strtotime($emailQueueData['EmailQueue']['sent_dt'])); ?> at <?php echo date("h:i A",strtotime($emailQueueData['EmailQueue']['sent_dt'])); ?></div>
	<?php } ?>
	   <form action="#" method="post">
       <fieldset>   		         
       		<div>
                <label for="to">To</label>
                <div class="usernameStyleAddMaster">Jay Smith (<?php echo $emailQueueData['EmailQueue']['to_email']?>)</div>
            </div>
            <div>
                <label for="cust_name">From</label>
                <div class="usernameStyleAddMaster">Contracts Administrator(donotreply@cosentium.com)</div>
            </div>
            <div>
                <label for="recipient_name">Cc</label>
                <div class="usernameStyleAddMaster">Bob Jones(<?php echo $emailQueueData['EmailQueue']['to_email']?>)</div>
            </div>
            <div>
                <label for="additional_cc">Additional Cc</label>
                <div class="usernameStyleAddMaster"><?php echo $emailQueueData['EmailQueue']['add_cc']?></div>
            </div>
            <div>
                <label for="bcc">Bcc</label>
                <div class="usernameStyleAddMaster"><?php echo $emailQueueData['EmailQueue']['bcc']?></div>
            </div>
            <div>
                <label for="subject">Subject</label>
                <div class="usernameStyleAddMaster"><?php echo $emailQueueData['EmailQueue']['email_subject_line']?></div>
            </div>
            <div>
                <label for="attachments">Attachments</label>
                <div class="usernameStyleAddMaster"><?php echo $emailQueueData['EmailQueue']['attachement_file_name']?></div>
            </div>
            <div>
            	<label></label>
                <textarea class="usernameStyleAddMaster" readonly><?php echo $emailQueueData['EmailQueue']['email_body_text']?></textarea>
            </div>
            <div>
                <label></label>
               <?php echo $this->Form->submit(__('Done'), array('type'=>'button','label' => false,'onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/view')."'",'name' => 'cancel','div' => false));?>
            </div>
        </fieldset>
    </form>
    
</div>
<div style="clear:both"></div>

<?php }  ?>
 
<script type="text/javascript">
	$(document).ready(function() {
		$('#addCC').removeClass('required');
		$('#BCC').removeClass('required');
	});	
</script>

