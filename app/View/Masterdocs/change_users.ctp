<?php
echo $this->Html->script('jquery-migrate-1.2.0.min.js');
echo $this->Html->script('__jquery.tablesorter.min.js');
?>
<script type="text/javascript">

    $(document).ready(function(){     		        
        $("#roles_list_tbl").tablesorter({
            widgets: ['zebra'],
            sortList: [[1,0]],
            headers: {
            0: { sorter: false },
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
}
else {
	$sectionName = '';
}

?>
<div class="right">

<div class="heading"><?php echo __('Add Master Document | Define default change controls')?></div>


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
<div >1</div><?php echo __('Upload Master Document')?>
</div>
<div class="clsbodynavinnertwo unselectedstep">
<div >2</div><?php echo __('Describe Document')?></div>
<div class="clsbodynavinnerthree <?php echo $step3; ?>">
<div >3</div><?php echo __('Define Default Change Controls')?></div>
<div class="clsbodynavinnerfour <?php echo $step4; ?>">
<div >4</div><?php echo __('Define Special Change Controls')?></div>
<div class="clsone"></div>
</div>
<div class="descdocformcontainer">
<div class="blueheading_nopadding"><?php echo __('Master Document : '.$masterDocData['MasterDoc']['name'].$sectionName)?></div>
</div>
<?php echo $this->Form->create('MasterDoc');?>
<div class="userlistcontainer1">
	<div class="bluesubheader">
         <?php if($id == Configure::read('ADD_OPINION_PROVIDER')){
             echo __('Select Opinion Provider');
         }else{
             echo __('Select Decision Maker');
         }?>
    </div>
<?php 
		 if( $invalidCustomDesc == 1 ) {
			echo '<table width="100%"><tr><td align="left" width="100%" class="error-message">' . __('You must enter a value for Custom Description.') . '</td></tr></table>';
		 }
           if ($invalidRepresentingInterestOf == 1) {
               echo '<table width="100%"><tr><td align="left" width="100%" class="error-message">' . __('You must enter a value for Representing Interest Of.') . '</td></tr></table>';
           }
	?>
    <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0" id="roles_list_tbl">
         <thead>
              <tr>
              	<th width="10%" align="center" class="roles_permission_tblcol1"><?php echo __('Select')?></th>
                <th width="22%" align="left" class="roles_permission_tblcol1"><span><?php echo __('User')?></span></th>
                <th width="34%" align="left" class="roles_permission_tblcol1"><?php echo __('Representing Interests Of')?></th>
                <th width="34%" align="left" class="roles_permission_tblcol1"><?php echo __('Custom Description')?></th>
              </tr>
          </thead>
          <tbody>
              <?php $i = 0;
              if(!empty($masterDocUsersData)){
              foreach ($masterDocUsersData as $masterDocUsersData):
                  if($i % 2 != 0){ ?>
                      <tr class="odd">
                  <?php }else{ ?>
                      <tr>
                  <?php }?>
                <td align="center">
                    <?php 
                    
                    if($id == Configure::read('ADD_OPINION_PROVIDER')){ 
                        if(in_array($masterDocUsersData['User']['id'], $opinionProviderUserId)){ ?>
                            <input type="checkbox" checked = "checked" value="<?php echo $masterDocUsersData['User']['id'];?>" id ="checkbox<?php echo $masterDocUsersData['User']['id']?>" name="data[MasterDoc][owner][]">
                        <?php }else { ?>
                            <input type="checkbox" value="<?php echo $masterDocUsersData['User']['id'];?>" id ="checkbox<?php echo $masterDocUsersData['User']['id']?>" name="data[MasterDoc][owner][]">
                        <?php }
                    }else{
                        if($masterDocUsersData['User']['id'] == $masterDocDecisionData[0]['User']['id']){ ?>
                            <input type="radio" name = "data[MasterDoc][owner]" checked = "checked" value="<?php echo $masterDocUsersData['User']['id'];?>">
                        <?php }else{ ?>
                            <input type="radio" name = "data[MasterDoc][owner]" value="<?php echo $masterDocUsersData['User']['id'];?>">
                        <?php } 
                    }
                    ?>
                </td>
                <?php if (!empty($masterDocUsersData['User']['image'])) {
                    $profileImage = $masterDocUsersData['User']['image'];
                } else {
                    $profileImage = Configure::read('DEFAULT_IMAGE_NAME');
                }?>
                <td align="left" class="roles_permission_tblcol1"><?php echo $this->Html->image($profileImage, array('class' => "usrthumbnail_right_margin"));?><?php echo __($masterDocUsersData['User']['last_name'].', '.$masterDocUsersData['User']['first_name'])?></td>
                <td align="left" valign="middle">
                	<div class="styled-select usrlistdropdown">
                        <?php 
                        if($id == Configure::read('ADD_OPINION_PROVIDER')){ 
                        if(in_array($masterDocUsersData['User']['id'], $opinionProviderUserId)){ 
                            $representinOf = $opinionProviderUser[$masterDocUsersData['User']['id'].'_representing_interest_of'];
                            $custom_description = $opinionProviderUser[$masterDocUsersData['User']['id'].'_custom_description'];
                        }else { 
                            $representinOf = '';
                           $custom_description = '';
                        }
                    }else{
                        if($masterDocUsersData['User']['id'] == $masterDocDecisionData[0]['User']['id']){
                            $representinOf = $decisionMakerData['SectionUser']['representing_interest_of'];
                            $custom_description = $decisionMakerData['SectionUser']['custom_description'];
                        }else{
                           $representinOf = '';
                           $custom_description = '';
                        } 
                    }?>
                        
                        <?php echo $this->Form->input('representing_interest_of_'.$masterDocUsersData['User']['id'], array('name' => 'represent_interests_of_'.$masterDocUsersData['User']['id'], 'value' => $representinOf,'label' => false,'options' => $representingInterestDd,'empty' => '--None Selection--', 'div' => false, 'class' => 'addmaster_dropdown_txtcolor', 'style' => 'width:220px'));?>
                    </div>
                </td>
                <td align="left" valign="middle">
				<?php if ($representinOf == 7) {
					echo $this->Form->input('custom_descr_'.$masterDocUsersData['User']['id'], array('value' => $custom_description, 'name' => 'custom_descr_'.$masterDocUsersData['User']['id'], 'label' => false, 'div' => false, 'class' => "usrlistdropdown", 'style' => 'display:block'));
				} else {
					echo $this->Form->input('custom_descr_'.$masterDocUsersData['User']['id'], array('value' => $custom_description, 'name' => 'custom_descr_'.$masterDocUsersData['User']['id'], 'label' => false, 'div' => false, 'class' => "usrlistdropdown", 'style' => 'display:none'));
				}?>
                </td>
              </tr>
              <?php $i++; endforeach;
              }?>
          </tbody>
    </table>
    <div>
        <?php if($id == Configure::read('ADD_OPINION_PROVIDER')){
            echo $this->Form->submit(__('Done'),array('label' => false, 'name' => Configure::read('ADD_OPINION_PROVIDER'),'div' => false));
        }else {
            echo $this->Form->submit(__('Done'),array('label' => false, 'div' => false));
        }?>
        <?php echo $this->Form->submit(__('Cancel'), array('label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'MasterDocs/defaultChangeControl')."'",'div' => false));?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
         $("select[name^='represent_interests_of']").change(function(event){
             var dropId = $(this).attr("id");
            var inputId = dropId.replace("MasterDocRepresentingInterestOf", "MasterDocCustomDescr");
            //alert($("select[name^='represent_interests_of']").val());
            if ($('#'+dropId).val() == 7)  {
                $('#'+inputId).show();
            }else {
                $('#'+inputId).hide();
            } 
            
         });
    });	
</script>