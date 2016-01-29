<?php
echo $this->Html->script('jquery-migrate-1.2.0.min.js');
echo $this->Html->script('__jquery.tablesorter.min.js');
?>
<script type="text/javascript">

    $(document).ready(function(){     		        
        $("#roles_list_tbl").tablesorter({
            widgets: ['zebra'],
            sortList: [[0,0]],
            headers: {
            1: { sorter: false },
            2: { sorter: false }, 
            3: { sorter: false }  
                    }
});  
}); 
</script>
<?php echo $this->element('usersetup_left_links'); 
$actionSet = $this->Session->read('lastSectionsUsersId');
if(!empty($actionSet)) {
	$sectionName = $this->Session->read('PostedSectionName');
	$sectionName = ", Section Name:".$sectionName;
	$cancelUrl = "MasterDocs/contract";
}
else {
	$sectionName = '';
	$cancelUrl = "MasterDocs/describeMasterDoc/";
}
?>
<div class="right">
    <div class="heading"><?php echo __('Add Master Document | Define default change controls');?></div>
    <div class="clsbodynav">
		<?php if(!empty($actionSet)) {
			$step3 = 'unselectedstep';
			$step4 = 'selectedstep';
		}
		else {
			$step3 = 'selectedstep';
			$step4 = 'unselectedstep';
		}
		?>
        <div class="clsbodynavinnerone unselectedstep">
            <div >1</div><?php echo __('Upload Master Document');?>
        </div>
        <div class="clsbodynavinnertwo unselectedstep">
            <div >2</div><?php echo __('Describe Document')?>
        </div>
        <div class="clsbodynavinnerthree <?php echo $step3;?>">
            <div >3</div><?php echo __('Define Default Change Controls')?>
        </div>
        <div class="clsbodynavinnerfour <?php echo $step4; ?>">
            <div >4</div><?php echo __('Define Special Change Controls')?>
        </div>
        <div class="clsone"></div>
    </div>
    <div class="descdocformcontainer">
        <div class="blueheading_nopadding"><?php echo __('Master Document | '.$masterDocData['MasterDoc']['name']. $sectionName)?></div>

    </div>
    <div class="userlistcontainer">
        <div class="bluesubheader"><?php echo __('Decision Maker')?></div>
        <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0">
             <thead>
                  <tr>
                    <th width="50%" align="left" class="roles_permission_tblcol1"><?php echo __('User')?></th>
                    <th width="50%" align="left"><?php echo __('Representing Interests Of')?></th>
                  </tr>
              </thead>
              <tbody>
                  <tr class="odd">
                    <td align="left" class="roles_permission_tblcol1"><?php echo $this->Html->image($profileImage, array('class' => "usrthumbnail_right_margin"));?><?php echo __($masterDocDecisionData[0]['User']['last_name'].', '.$masterDocDecisionData[0]['User']['first_name'])?></td>
                    <td align="left"><?php 
                    if(!empty($masterDocDecisionData[0]['RepresentingInterest']['name'])){
                        echo __($masterDocDecisionData[0]['RepresentingInterest']['name']);
                    }else{
                        echo __(Configure::read('REPRESENTING_OF_DEFAULT'));
                    }?>
                    </td>
                  </tr>
              </tbody>
        </table>
        <?php echo $this->Form->submit(__('Change'), array('class' => "profile", 'label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'MasterDocs/changeUsers')."'",'div' => false)); ?><br><br>
        <br/><div class="bluesubheader"><?php echo __('Opinion Provider')?></div>
        <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0" id="roles_list_tbl">
             <thead>
                  <tr>
                    <th width="50%" align="left" class="roles_permission_tblcol1"><span><?php echo __('User')?></span></th>
                    <th width="25%" align="left" class="roles_permission_tblcol1"><?php echo __('Representing Interests Of')?></th>
                    <th width="25%" align="center" class="roles_permission_tblcol1"><?php echo __('Action')?></th>
                  </tr>
              </thead>
              <tbody>
                  
                  <?php 
                  if(!empty($opinionProviderData)){
                    $i = 0;
              foreach ($opinionProviderData as $opinionProviderData):
                  if($i % 2 != 0){ ?>
                      <tr class="odd">
                  <?php }else{ ?>
                      <tr>
                  <?php } ?> 
                <?php if (!empty($opinionProviderData['User']['image'])) {
                    $profileImage = $opinionProviderData['User']['image'];
                } else {
                    $profileImage = Configure::read('DEFAULT_IMAGE_NAME');
                }?>
                <?php if(!empty($opinionProviderData['User']['id'])){
                    $userName = $opinionProviderData['User']['last_name'].', '.$opinionProviderData['User']['first_name'];
                    if(!empty($opinionProviderData['SectionUser']['representing_interest_of'])){
                        $representingOf = $opinionProviderData['RepresentingInterest']['name'];
                    }else{
                        $representingOf = Configure::read('REPRESENTING_OF_DEFAULT');
                    }
                } ?>
                <td align="left" class="roles_permission_tblcol1"><?php echo $this->Html->image($profileImage, array('class' => "usrthumbnail_right_margin"));?><?php echo $userName;?></td>
                <td align="left" class="roles_permission_tblcol1"><?php echo $representingOf;?></td>
                <td align="center" class="roles_permission_tblcol1">
                    <a onClick = "deleteConfirm('<?php echo $opinionProviderData['SectionUser']['id'];?>')" id="remove-opinionProvider">Delete</a>
                </td>
                
              </tr>
              <?php $i++; endforeach;
                  }else{ ?>
                      <tr class="odd">
                <td align="left" class="roles_permission_tblcol1"><?php echo __('No user added')?></td>
                <td align="left"></td>
                <td align="center"></td>
              </tr>
                  <?php }
                  ?>
                  
                  
              </tbody>
        </table>
        <?php echo $this->element('overlay'); ?>
        <?php echo $this->Form->submit(__('Add'), array('class' => "profile", 'label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'MasterDocs/changeUsers/1')."'",'div' => false)); ?><br><br>
        <div>
            <input type="submit" name="next" value="Next" onclick="location.href='<?php echo $this->Html->url(Configure::read('SITE_BASE_URL').'MasterDocs/contract');?>'">
			<?php if(empty($actionSet)) { ?>
				<input type="button" name="cancel" value="Cancel" onclick="location.href='<?php echo $this->Html->url(Configure::read('SITE_BASE_URL')).$cancelUrl;?>'">
			<?php } ?>

        </div>
    </div>
</div>
<?php echo $this->Form->create('Masterdoc',array('url' => array('controller' => 'MasterDocs', 'action' => 'defaultChangeControl'))); ?>
<?php echo $this->Form->input('userid',array('id' => 'userid', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
<?php echo $this->Form->end();?>
<script>
	function deleteConfirm(opinionProviderId) {
		document.getElementById('light').style.display = 'block';
		document.getElementById('fade').style.display = 'block';
			$( "#confirmBut" ).click(function() {
                   $("#MasterdocDefaultChangeControlForm").attr("action", "defaultChangeControl/"+opinionProviderId) 
				document.getElementById('MasterdocDefaultChangeControlForm').submit();
			});
	}
</script>	