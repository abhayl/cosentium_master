<div class="full_innercontent">
    <div class="heading"><?php echo __('Deal Detail');?></div>
	<div class="userlistcontainer">
	<div class="blueheading18padding"><?php echo __('Deal Data');?></div>
    <table width="100%" border="0" class="usr_list_tbl tbltopbordernone" cellpadding="0" cellspacing="0">
    	<tbody>
          <tr class="odd">
            <td width="9%"  align="center">
            	<?php echo __('Deal Name'); ?>
            </td>
            <td width="29%" align="left" class="darktext">
            	<?php echo  $detail['Deal']['name'];?>
            </td>
            <td width="13%"  align="left">
                <?php echo __('Master Document'); ?>
            </td>
            <td width="24%" align="left" class="darktext">
				<?php echo  $detail['MasterDoc']['name'];?>
            </td>
            <td width="13%"  align="left">
                <?php echo __('Total Elapsed Time'); ?>
            </td>
            <td width="12%" align="left" class="darktext">
				<?php echo time2string(time()- strtotime($detail['Deal']['created']));?>
            </td>
          </tr>
          <tr>
            <td width="9%"  align="center">
            	<?php echo __('Deal Owner'); ?>
            </td>
            <td width="29%"  align="left" class="darktext">
				<?php
				if (!$detail['User']['image']) {
					$profileImage = Configure::read('DEFAULT_IMAGE_NAME');
				} else {
					$profileImage = $detail['User']['image'];
				}
				echo $this->Html->image($amazonS3ServerPath.$profileImage, array('class' => 'usrthumbnail_right_margin'));
            	echo $detail['User']['last_name']. ', ' . $detail['User']['first_name']; 
				?>
            </td>
            <td width="13%"  align="left">
                <?php echo __('Current Turn'); ?>
            </td>
            <td width="24%" align="left" class="darktext">
				<?php echo $detail['Deal']['turn'];?>
            </td>
            <td width="13%">
                
            </td>
            <td width="12%" align="right" class="darktext">
            
            </td>
          </tr>
        </tbody>
    </table>
   	<?php echo $this->Form->create('DealRevision', array('id'=>'dealRevisionId', 'url' => '/redlines/upload'));?>
		<input type="hidden" name="dealId" id="dealId">
	
	<?php
		if( $LoggedInUserId == $detail['User']['id'] ) {
			echo $this->Form->submit(__('Upload Customer Response Document'), array('type'=>'button','label' => false, "onClick" => "javacript:submitDeal(".$detail['Deal']['id'].")",'name' => 'audit_deal','div' => false,'class' =>'profile'));
		} else {
			echo $this->Form->submit(__('Upload Customer Response Document'), array('type'=>'button','label' => false, "onClick" => "javacript:submitDeal(".$detail['Deal']['id'].")",'name' => 'audit_deal','div' => false,'class' =>'profile', 'disabled'=> 'disabled'));
		}
	?>
	</form>
    <br><br>
    
	 <div class="blueheading18padding"><?php echo __('Deal History'); ?></div>
    
    <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0" id="usr_list_tbl">
         <thead>
              <tr>
                <th width="10%" height="32" align="left" class="roles_permission_tblcol1"><?php echo __('Turn'); ?></th>
                <th width="15%" align="left"><?php echo __('Stage'); ?></th>
                <th width="15%" align="center"><?php echo __('Date'); ?></th>
                <th width="15%" align="center"><?php echo __('Material Changes'); ?></th>
                <th width="15%" align="center"><?php echo __('Non Material Changes'); ?></th>
                <th width="30%" align="center"><?php echo __('Total Changes'); ?></th>
              </tr>
          </thead>
          <tbody>
              <?php $i = 1;
              foreach ($redlines as $dealRedline):
                  if($i % 2 != 0){ ?>
                      <tr class="odd">
                  <?php }else{ ?>
                      <tr>
                  <?php } $i++;?>
				<td align="left" class="roles_permission_tblcol1"><?php echo $dealRedline[$redlineTable]['turn'];?></td>
                <td align="left"><?php echo $dealRedline[$redlineTable]['stage'];?></td>
				<td align="center"><?php echo date('m/d/Y', strtotime($dealRedline[$redlineTable]['modified']));?></td>
                <td align="center"><?php echo $dealRedline[$redlineTable]['material_changes'];?></td>
                <td align="center"><?php echo $dealRedline[$redlineTable]['non_material_changes'];?></td>
                <td align="center"><?php echo $dealRedline[$redlineTable]['total_changes'];?></td>
              </tr>
              <?php $i++; endforeach; ?>
          </tbody>
      </table>
      <table width="100%" border="0">
          <tr>
            <td width="19%">
				<?php echo $this->Form->create('DealEmail',array('id'=>'dealEmail','url' => '/deals/deal_email'));?>
				<input type="hidden" name="emailId" id="emailId">
				</form>
            	<input type="submit" name="audit" value="Audit" class="profile"><input type="submit" name="status" value="Status" class="profile"><input type="submit" name="view_file" value="View File" class="profile">
				<?php echo $this->Form->submit(__('View Send Email'), array('type'=>'button','label' => false, "onClick" => "javacript:submitForm(".$detail['Deal']['email_id'].")",'name' => 'view_send_email','div' => false,'class' =>'profile'));?>
				
            </td>
          </tr>
      </table>
      <br><hr class="hrcolor"><br>
		<?php echo $this->Form->submit(__('Done'), array('label' => false, 'onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'deals/view')."'", 'name' => 'done', 'div' => false));?>
    </div>
</div>
<?php
// Get the time in days and Hours format
function time2string($timeline) {
    $periods = Configure::read('ELAPSED_TIME_FORMAT');
	$ret = '';
    foreach($periods AS $name => $seconds){
        $num = floor($timeline / $seconds);
        $timeline -= ($num * $seconds);
		if($name == 'day')
			$ret .= $num .' '. $name .(($num > 1) ? 's' : '') . ', ';
		else
			$ret .= $num .' '. $name .(($num > 1) ? 's' : '') . ' ';
    }
    return trim($ret);
}
?>

<script type="text/javascript">
	function submitForm(id){
         $('#emailId').val(id);
         $('#dealEmail').submit();
     }
	function submitDeal(id){
		 $('#dealId').val(id);
		 $('#dealRevisionId').submit();
	 }
</script>