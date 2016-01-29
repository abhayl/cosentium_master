<!-- app/View/Deals/email.ctp -->
<?php 
if(!isset($view)) { 
?>
<div class="full_innercontent">
<div class="heading"><?php echo __('Review and Send'); ?></div>
	<div class="usernameStyleAddUser blueheading"><?php echo __('The following will be sent to the customer: '); ?>&nbsp;</div>
	<?php echo $this->Form->create('EmailQueue'); ?>
	
       <fieldset>
			 <?php echo $this->Form->input('to_email',array('label' => __('To email'), 'type' => 'hidden', 'value' => $sessDealData['Deal']['recipient_email'],'maxlength' => Configure::read('MAX_TEXTBOX_LENGTH'),'required' => 'false')); ?>
       		         
       		<div class="addmaster_email_label_width">
                <label for="to"><?php echo __('To'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo $sessDealData['Deal']['recipient_name'].'('.$sessDealData['Deal']['recipient_email'].')';?></div>
            </div>
            <div class="addmaster_email_label_width">
                <label for="cust_name"><?php echo __('From'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo Configure::read('CONTRACTS_ADMINISTRATOR')."(".Configure::read('NOREPLY_EMAIL').")"; ?></div>
            </div>
            <div class="addmaster_email_label_width">
                <label for="recipient_name"><?php echo __('Cc'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo $userData['User']['first_name'].' '.$userData['User']['last_name'].'('.$userData['User']['username'].')';?> </div>
            </div>
			<?php 
				 echo $this->Form->input('add_cc',array('label' => __('Additional Cc'),'required' => 'false','after' =>'<span class="reviewdeal_righttext">Enter email addresses separated by commas</span>','div'=>array('id' => 'addCC','class' =>'addmaster_email_label_width')));
				 
				 echo $this->Form->input('bcc',array('label' => __('Bcc'),'required' => 'false','after' => '<span class="reviewdeal_righttext">Enter email addresses separated by commas</span>','div'=>array('id' => 'BCC','class' =>'addmaster_email_label_width')));
				 
				 if(empty($this->data)){
					$subj_line = 'Emailing '.$sessDealData['Deal']['master_docs_name'].' Issued by '.$userData['User']['Company']['company_name'];
					$body_text = 'The attached document has been issued for your review';
				} else {
					$subj_line = $this->data['EmailQueue']['email_subject_line'];
					$body_text = $this->data['EmailQueue']['email_body_text'];
				}
				 
				 echo $this->Form->input('email_subject_line',array('label' => __('Subject'),'value' => $subj_line, 'required' => 'false','div'=>array('id' => 'subjectLine','class' =>'addmaster_email_label_width'),'style' => 'width:430px;'));				 
			?>
           <div class="addmaster_email_label_width">
                <label for="attachments"><?php echo __('Attachments'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo $sessDealData['Deal']['master_docs_name'];?></div>
            </div>
			<div class="addmaster_email_label_width">
            	<label></label>
				<?php echo $this->Form->textarea('email_body_text',array('label' => __('Email Body'),'required' => 'false','value' => $body_text)); ?>
				<?php  echo $this->Form->error('email_body_text', null, array('class' => 'error-message')); ?>	
			</div>
			<?php 
			
				echo $this->Form->input('attachement_file_name', array('value' => $sessDealData['Deal']['master_docs_file'],'type' => 'hidden','readonly' => 'readonly'));
				echo $this->Form->hidden('user_id', array('value' => $userData['User']['id'],'type' => 'hidden'));
				echo $this->Form->hidden('cc_user_id', array('value' => $userData['User']['id'],'type' => 'hidden'));
				echo $this->Form->hidden('company_id', array('value' => $userData['User']['UserCompany']['company_id'],'type' => 'hidden'));
			?>
           
            <div class="addmaster_email_label_width">
                <label></label>
				<?php echo $this->Form->submit(__('Send'), array('label' => false,'name' => 'send', 'div' => false));?>
				<?php echo $this->Form->submit(__('Cancel'), array('type'=>'button','label' => false,'onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'deals/add')."'",'name' => 'cancel','div' => false));?>
            </div>
        </fieldset>
    </form>
    
</div>
<div style="clear:both"></div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#addCC').removeClass('required');
		$('#BCC').removeClass('required');
		$('#subjectLine').removeClass('required');		
	});	
</script>
<?php } else { 
?>
<div class="full_innercontent">
<div class="heading"><?php echo __('View Issue Deal Email'); ?></div>
	<?php if(isset($emailQueueData['EmailQueue']['sent_dt'])) {	?>
	<div class="usernameStyleAddUser blueheading">The following was sent to the customer on <?php echo date("m/d/y",strtotime($emailQueueData['EmailQueue']['sent_dt'])); ?> at <?php echo date("h:i A",strtotime($emailQueueData['EmailQueue']['sent_dt'])); ?></div>
	<?php } ?>
	   <form action="#" method="post">
       <fieldset>   		         
       		<div class="addmaster_email_label_width">
                <label for="to"><?php echo __('To'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo $dealData['Deal']['recipient_name'];?> (<?php echo $emailQueueData['EmailQueue']['to_email']?>)</div>
            </div>
            <div class="addmaster_email_label_width">
                <label for="cust_name"><?php echo __('From'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo Configure::read('CONTRACTS_ADMINISTRATOR')."(".Configure::read('NOREPLY_EMAIL').")"; ?></div>
            </div>
            <div class="addmaster_email_label_width">
                <label for="recipient_name"><?php echo __('Cc'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo $userData['User']['first_name'].' '.$userData['User']['last_name'].'('.$userData['User']['username'].')';?> </div>
            </div>
           <div class="addmaster_email_label_width">
                <label for="additional_cc"><?php echo __('Additional Cc'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo $emailQueueData['EmailQueue']['add_cc']?></div>
            </div>
            <div class="addmaster_email_label_width">
                <label for="bcc"><?php echo __('Bcc'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo $emailQueueData['EmailQueue']['bcc']?></div>
            </div>
            <div class="addmaster_email_label_width">
                <label for="subject"><?php echo __('Subject'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo $emailQueueData['EmailQueue']['email_subject_line']?></div>
            </div>
            <div class="addmaster_email_label_width">
                <label for="attachments"><?php echo __('Attachments'); ?></label>
                <div class="usernameStyleAddMaster"><?php echo $dealData['MasterDoc']['name']?></div>
            </div>
            <div class="addmaster_email_label_width">
            	<label></label>
                <textarea class="usernameStyleAddMaster" readonly><?php echo $emailQueueData['EmailQueue']['email_body_text']?></textarea>
            </div>
            <div class="addmaster_email_label_width">
                <label></label>
               <?php echo $this->Form->submit(__('Done'), array('type'=>'button','label' => false,'onclick' => "javascript:history.back();",'name' => 'Done','div' => false));?>
            </div>
        </fieldset>
    </form>    
</div>
<div style="clear:both"></div>

<?php }  ?>
 


