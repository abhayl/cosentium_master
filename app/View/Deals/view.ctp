<div class="innercontent">
<div class="full_innercontent">
    <div class="heading"><?php echo __('Deals');?></div>
    <div class="userlistcontainer">
		<?php if(isset($successMessage)) echo "<div class='successmsg' style='width:98.99%;'>" . $successMessage . "</div>";?>
        <table width="100%" border="0">
			<tr>
				<td>
					<?php echo $this->Form->submit(__('Add New Deal'), array('label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'deals/add')."'",'div' => false));?>
				</td>
                <?php echo $this->Form->create('Deal', array('action' => 'view'));?>
					<td width="4%"> <?php echo __('View');?> </td>
					<td width="39%">
					<div class="styled-select usrlistdropdown"><?php echo $this->Form->input('deal_type', array('onchange' => 'javascript:submit();', 'options' => $viewDealDd, 'type' => 'select', 'label' => false, 'div' => false, 'class' => 'addmaster_dropdown_txtcolor', 'default' => $selectedDealOption, 'style' => 'width:220px;'));?></div>
				</td>
				<td align="right">
					<div class="styled-select usrlistdropdown"><?php echo $this->Form->input('records_per_page', array('onchange' => 'javascript:submit();', 'options' => $padinationDd, 'type' => 'select', 'label' => false, 'div' => false, 'class' => 'addmaster_dropdown_txtcolor', 'default' => $selectedRecordsOption, 'style' => 'width:220px;'));?></div>
				</td>
                <?php echo $this->Form->end();?>
			</tr>
        </table>
		<?php echo $this->Form->create('Deal1',array('id'=>'dealDetails','url' => '/deals/detail'));?>
        <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                <th align="left"  class="roles_permission_tblcol1"><?php echo $this->Paginator->sort('name', __('Deal Name') . ' ' . $this->Html->image($sortImage['name']), $options = array('escape' => false));?></th>
                <th align="left"><?php echo $this->Paginator->sort('User.last_name', __('Deal Owner') . ' ' . $this->Html->image($sortImage['User.last_name']),$options = array('escape' => false)); ?></th>
                <th align="left"><?php echo $this->Paginator->sort('stage', __('Stage') . ' ' .$this->Html->image($sortImage['stage']), $options = array('escape' => false)); ?></th>
				<th align="center"><?php echo $this->Paginator->sort('created', __('Date') . ' ' .$this->Html->image($sortImage['created']), $options = array('escape' => false)); ?></th>
				<th align="center"><?php echo $this->Paginator->sort('modified', __('Elapsed Time'). '</br>(' . __('This Step') . ')' . $this->Html->image($sortImage['created']), $options = array('escape' => false)); ?></th>
				<th align="center"><?php echo __('Expires');?></th>
				<th align="center"><?php echo $this->Paginator->sort('turn', __('Turns') . ' ' .$this->Html->image($sortImage['turn']), $options = array('escape' => false)); ?></th>
				<th align="center"><?php echo $this->Paginator->sort('created', __('Elapsed Time'). '</br>(' . __('Total') . ') ' . $this->Html->image($sortImage['created']), $options = array('escape' => false)); ?></th>
              </tr>
			</thead>
			<input type="hidden" name="dealId" id="dealId">
			<tbody>
              <?php $i = 0;
              foreach ($deals as $deal):
                  if($i % 2 != 0){ ?>
                      <tr class="odd">
                  <?php }else{ ?>
                      <tr>
                  <?php } $i++;?>
				<td align="left" class="roles_permission_tblcol1">
				<?php 
					// Pemission to be set from the controller
					if( $LoggedInUserId == $deal['User']['id']) {
						//echo '<a href="'.Configure::read('SITE_BASE_URL'). 'deals/detail/'. $deal['Deal']['id'] . '">' . $deal['Deal']['name'] . '</a>' ;
						echo '<a href="#" onClick="javacript:submitForm('.$deal['Deal']['id'].')">'. $deal['Deal']['name'] . '</a>' ;
					} else {
						echo  $deal['Deal']['name'];
					}
				?>
				</td>
                <td align="left"><?php 
				if (!$deal['User']['image']) {
					$profileImage = Configure::read('DEFAULT_IMAGE_NAME');
                                        $amazonS3ServerPath = '';
				} else {
					$profileImage = $deal['User']['image'];
                                        $amazonS3ServerPath = Configure::read('AWS_PROFILE_IMG_IMAGES_URL');
				}
				echo $this->Html->image($amazonS3ServerPath.$profileImage, array('class' => 'usrthumbnail_right_margin'));
				echo $deal['User']['last_name'].', '.$deal['User']['first_name']; ?></td>
                <td align="left"><?php echo $deal['Deal']['stage'];?></td>
                <td align="center"><?php echo date('m/d/Y', strtotime($deal['Deal']['created']));?></td>
                <td align="center"><?php echo time2string(time()-strtotime($deal['Deal']['modified']));?></td>
                <td align="center">
				<?php
				// Logic to take care of master document with no expiry defined
				if( $deal['Deal']['expiry'] == NULL ) {
					echo __('N/A');
				} else {
					echo date('m/d/Y', strtotime($deal['Deal']['expiry']));
				}?>
				<td align="center"><?php echo $deal['Deal']['turn'];?></td>
                <td align="center"><?php echo time2string(time()- strtotime($deal['Deal']['created']));?></td>
              </tr>
              <?php $i++; endforeach; ?>
			</tbody>
        </table>
		</form>
		
        <table width="100%" border="0">
          <tr>
            <td>
                <?php echo $this->Form->submit(__('Add New Deal'), array('label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'deals/add')."'",'div' => false));?>
            </td>
            <td align="right">
                <?php echo $this->Paginator->prev(__('« Previous '), null, null, array('class' => 'disabled')); ?>
                <?php echo $this->Paginator->numbers(); ?>
                <?php echo $this->Paginator->next(__(' Next »'), null, null, array('class' => 'disabled')); ?>
                <?php echo $this->Paginator->counter(); ?>
            </td>
          </tr>
        </table>
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
         $('#dealId').val(id);
         $('#dealDetails').submit();
     }
</script>


