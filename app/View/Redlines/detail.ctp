<div class="innercontent">
<div class="full_innercontent">
<div class="heading"><?php echo __('Start Review Process'); ?></div>
	<div class="userlistcontainer">
	<div class="usernameStyleAddUser blueheading18padding"><?php echo __('Changes Detected'); ?><span class="readline_numberdiv"><?php echo $changes; ?></span></div>
    <div class="bluesubheader18padding">Review Process</div>
    
    <table width="80%" border="0" class="usr_list_tbl tbltopbordernone" cellpadding="0" cellspacing="0" id="usr_list_tbl">
        <tbody>
              <tr class="odd">
                <td align="left" class="roles_permission_tblcol1"><?php echo __('First') ?></td>
                <td align="left"><?php echo __('Non Material changes will be processed'); ?></td>
                <td align="left"><?php echo Configure::read('REVIEW_FIRST_DAY') . " "; echo __('days or less'); ?></td>
                
              </tr>
              <tr class="odd">
                <td align="left" class="roles_permission_tblcol1"><?php echo __('Second'); ?></td>
                <td align="left"><?php echo __('Openions and decisions will be collected on all remaining changes'); ?></td>
                <td align="left"><?php echo Configure::read('REVIEW_SECOND_DAY') . " "; echo __('days or less'); ?></td>
              </tr>
              <tr class="odd">
                <td align="left" class="roles_permission_tblcol1"><?php echo __('Third'); ?></td>
                <td align="left"><?php echo __('You will review all the decisions and authorize them to be translated into "legalese"'); ?></td>
                <td align="left"><?php echo Configure::read('REVIEW_THIRD_DAY') . " "; echo __('days or less'); ?></td>
              </tr>
              <tr class="odd">
                <td align="left" class="roles_permission_tblcol1"><?php echo __('Fourth'); ?></td>
                <td align="left"><?php echo __('A response document will be made. You will be notified when it is ready to issue'); ?></td>
                <td align="left"><?php echo Configure::read('REVIEW_FOURTH_DAY') . " "; echo __('days or less'); ?></td>
              </tr>
              
           </tbody>
      </table>
	  <div class="readlinebluemsg"><?php echo __('You can <strong>check the state of the review</strong> any time. You can <strong>audit the progress of the review</strong> any time');?></div><br>
    <table width="100%" border="0">
      <tr>
        <td><input type="submit" name="start_review_process" value="Start Review Process">
		<?php echo $this->Form->submit(__('Cancel'), array('label' => false, 'onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'deals/view')."'", 'name' => 'done', 'div' => false));?></td>
        <td align="right"></td>
      </tr>
    </table>
        
        </div>
</div>