<?php echo $this->element('personalinfo_left_links'); ?>

<div class="right">
<div class="heading"><?php echo __('Manage Delegation');?></div>
<?php if(isset($successMessage)) echo "<div class='successmsg'>".$successMessage."</div>";?>
    <div class="managedelinnerdiv">
    	<div class="profilepicdiv" style="height:220px;">
            <p><?php echo __("You can set a delegate for opinions you are asked to provide and decisions you are asked to make. Authorizing a delegate can be appropriate when you plan to be unavailable for extended periods of time and when your delegate is someone you trust to make the types of decisions you're asked to make in Cosentium.");?></p>
            <p><?php echo __('All opinions and decisions provided by your delegate will be stamped "..by &lt;delegate full name&gt; on behalf of &lt;your full name&gt;." For the duration period you select, when you have assigned a delegate and set a duration, your delegate will be asked to provide all opinions and decisions requested from you until the duration expires.');?></p>
            <p><?php echo __("You will be copied on all the document review requests that go to your delegate");?></p>
        </div>
        <?php echo $this->Form->create('User', array('action' => 'manageDelegation'));?>
        <table width="100%" border="0">
          <tr>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left"  colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td height="26" align="left"><?php echo __('Delegate to');?>
                <?php if($user['UserCompany']['delegate_to']) {
                  echo $this->Html->link(__('Remove Delegation'), '#',array('onclick' => "javascript:document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block';", 'escape' => false,'name' => 'clear', 'style' => "float:right;"));
                } ?></td>
            <td align="left">&nbsp;</td>
            <td align="left"  colspan="2"><?php echo __('Duration');?></td>
          </tr>
          <tr>
            <td style="width:300px;">
            	<div class="styled-select" style="margin-bottom:0px;">
                    <?php echo $this->Form->input('UserCompany.delegate_to', array('value' => $user['UserCompany']['delegate_to'], 'label' => false,'options' => $userDataDdArr,'empty' => '--No Selection--', 'error' => false, 'div' => false));?></div>
            </td>
            <td style="width:100px;">&nbsp;</td>
            <td>
       	      <?php echo __('From'); ?>
              <?php echo $this->Form->input('UserCompany.from', array('placeholder' => 'mm/dd/yyyy','value' => $user['UserCompany']['delegation_start_dt'], 'id' => 'datepicker1', 'label' => false,'required' => 'false','error' => false, 'div' => false, 'class' => 'dt','readonly' => 'readonly')); ?>
            </td>
            <td>   
                <?php echo __('To'); ?>
              <?php echo $this->Form->input('UserCompany.to', array('placeholder' => 'mm/dd/yyyy','value' => $user['UserCompany']['delegation_end_dt'], 'id' => 'datepicker2', 'label' => false,'required' => 'false','error' => false, 'div' => false, 'class' => 'dt','readonly' => 'readonly')); ?>
            </td>
          </tr>
          
          <tr>
            <td style="width:300px;" align = "left"><?php  echo $this->Form->error('UserCompany.delegate_to', null, array('class' => 'manageDelerrormsg')); ?></td>
            <td style="width:100px;">&nbsp;</td>
            <td align = "left"><?php  echo $this->Form->error('UserCompany.from', null, array('class' => 'manageDelFromerrormsg')); ?></td>
            <td align = "left"><?php  echo $this->Form->error('UserCompany.to', null, array('class' => 'manageDelToerrormsg')); ?></td>
          </tr>
        </table><br>
            <div>
                 
                <?php echo $this->Form->submit(__('Save'), array('id'=>'butSave','label' => false,'div' => false,'type' => 'button','onclick' => ""));?>
                <?php echo $this->Form->submit(__('Cancel'), array('type' => 'button','label' => false,'onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/dashboard/')."'",'name' => 'cancel','div' => false));?>
            </div>
        <?php echo $this->element('overlay'); ?>
        <?php echo $this->Form->end();?>
    </div>   
</div>
<?php echo $this->element('overlay'); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$( "#datepicker1" ).datepicker({
        dateFormat: 'mm/dd/yy',
        minDate: 0
		/*showOn: "button",
      	buttonImage: "img/calendar.jpg",
      	buttonImageOnly: true*/
	});
	$( "#datepicker2" ).datepicker({
            dateFormat: 'mm/dd/yy',
            minDate: 0
		/*showOn: "button",
     	buttonImage: "img/calendar.jpg",
      	buttonImageOnly: true*/
    });
	});

$( "#butSave" ).click(function() {
       if($('#UserCompanyDelegateTo').val()== "" && ($('#datepicker1').val() == "" || $('#datepicker2').val() == "")) {
           document.getElementById('light').style.display='block';
           document.getElementById('fade').style.display='block'
          $('#confirm_message').html('<?php echo $confirm_message2; ?>');
		$('#confirmBut').hide();
		$('#cancelBut').hide();
		$('#okBut').show();
       } else { 
           $('#UserManageDelegationForm').submit();
       }
       
	
});
</script>