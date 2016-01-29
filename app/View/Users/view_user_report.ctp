<?php echo $this->element('usersetup_left_links'); ?>
<div class="right">
    <div class="heading"><?php echo __('Where Used Report | '.$userData['User']['last_name'].', '.$userData['User']['first_name'])?></div>
    <div class="userlistcontainer1">
        <?php echo $this->Form->create('user',array('action' => 'viewUserReport')); ?>
        <?php echo $this->Form->input(__('userId', true), array('label' => false, 'value' => $userData['User']['id'], 'div' => false, 'type' => 'hidden'));?>
        <?php if (!empty($decisionMakerData)) { ?>
        <div class="blueheading18padding"><?php echo __($userData['User']['last_name'].', '.$userData['User']['first_name'].' is used as Decision Maker')?></div>
        <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0">
            <thead>
                  <tr>
                    <th width="11%" height="32" align="left" class="roles_permission_tblcol1"><?php echo __('Master Document')?></th>
                    <th width="25%" align="left"><?php echo __('Owner')?></th>
                    <th width="15%" align="left"><?php echo __('Section Name')?></th>
                    <th width="18%" align="center"><?php echo __('Request Removal')?></th>
                  </tr>
            </thead>
            <tbody>
             <?php $i = 0;
                foreach ($decisionMakerData as $decisionMakerData):
                if ($i % 2 != 0) { ?>
                    <tr class="odd">
                <?php }else{ ?>
                    <tr>
                <?php }
                ?>
                <td align="left"  class="roles_permission_tblcol1">
                   <a href="#" onClick="javascript:submitForm(<?php echo $decisionMakerData['MasterDoc']['files_id'] ?>);"><?php echo __($decisionMakerData['MasterDoc']['name']);?></a>
                </td>      
                <td align="left">
                <?php 
                if (!empty($decisionMakerData['User']['image'])) {
                    $profileImage = $decisionMakerData['User']['image'];
                    $amazonS3ServerPath =  Configure::read('AWS_PROFILE_IMG_IMAGES_URL');
                } else {
                    $profileImage = Configure::read('DEFAULT_IMAGE_NAME');
                    $amazonS3ServerPath = "";
                }
                echo $this->Html->image($amazonS3ServerPath.$profileImage, array('class' => "usrthumbnail_right_margin"));?><?php echo $decisionMakerData['User']['last_name'].' '.$decisionMakerData['User']['first_name']; ?>
                </td>
                <td align="left">
                    <?php if ($decisionMakerData['Section']['name'] == Configure::read('SECTION_FOR_ENTIRE_DOCUMENT')) {
                        echo __($decisionMakerData['Section']['name']);
                    } else {
                        echo $this->Html->link(__($decisionMakerData['Section']['name']), array('controller' => 'users'));
                    } ?>
                </td>
                <td align="center">
                    <?php $id = $decisionMakerData['Section']['id'];?>
                    <?php echo $this->Form->input('decisionCheckBox', array('id' => $decisionMakerData['Section']['id'], 'type' => 'checkbox', 'name' => "checkBox[decisionCheckBox][decisionCheckBox".$id."]", 'label' => false, 'div' => false));?>
                </td>
                <?php $i++; endforeach; ?>
            </tbody>
        </table>
        <br><br>
       <?php } 
       if (!empty($opinionProviderData)) { ?>
    <div class="blueheading18padding"><?php echo __($userData['User']['last_name'].', '.$userData['User']['first_name'].' is used as Opinion Provider')?></div>
    <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0">
        <thead>
              <tr>
                <th width="11%" height="32" align="left" class="roles_permission_tblcol1"><?php echo __('Master Document'); ?></th>
                <th width="25%" align="left"><?php echo __('Owner')?></th>
                <th width="15%" align="left"><?php echo __('Section Name')?></th>
                <th width="18%" align="center"><?php echo __('Request Removal')?></th>
              </tr>
        </thead>
        <tbody>
             <?php $i = 0;
                foreach ($opinionProviderData as $opinionProviderData):
                if ($i % 2 != 0) { ?>
                    <tr class="odd">
                <?php }else{ ?>
                    <tr>
                <?php }
                ?>
                <td align="left"  class="roles_permission_tblcol1">
                   <a href="#" onClick="javascript:submitForm(<?php echo $opinionProviderData['MasterDoc']['files_id'] ?>);"><?php echo __($opinionProviderData['MasterDoc']['name']);?></a>
                </td>      
                <td align="left">
                <?php 
                if (!empty($opinionProviderData['User']['image'])) {
                    $profileImage = $opinionProviderData['User']['image'];
                    $amazonS3ServerPath =  Configure::read('AWS_PROFILE_IMG_IMAGES_URL');
                } else {
                    $profileImage = Configure::read('DEFAULT_IMAGE_NAME');
                    $amazonS3ServerPath = "";
                }
                echo $this->Html->image($amazonS3ServerPath.$profileImage, array('class' => "usrthumbnail_right_margin"));?><?php echo $opinionProviderData['User']['last_name'].' '.$opinionProviderData['User']['first_name']; ?>
                </td>
                <td align="left">
                    <?php if ($opinionProviderData['Section']['name'] == Configure::read('SECTION_FOR_ENTIRE_DOCUMENT')) {
                        echo __($opinionProviderData['Section']['name']);
                    } else {
                        echo $this->Html->link(__($opinionProviderData['Section']['name']), array('controller' => 'users'));
                    } ?>
                </td>
                <td align="center">
                    <?php $id = $opinionProviderData['Section']['id'];?>
                    <?php echo $this->Form->input('opinionCheckBox', array('id' => $opinionProviderData['Section']['id'], 'type' => 'checkbox', 'name' => 'checkBox[opinionCheckBox][opinionCheckBox'.$id.']', 'label' => false, 'div' => false));?>
                </td>
                <?php $i++; endforeach; ?>
            </tbody>
    </table>
    <br><br>
    
    <?php } 
       if (!empty($masterDocOwnerData)) { ?>
    
    <div class="blueheading18padding"><?php echo __($userData['User']['last_name'].', '.$userData['User']['first_name'].' is the owner of Master Documents')?></div>
    <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0">
    	<thead>
              <tr>
                <th width="15%" align="left" class="roles_permission_tblcol1"><?php echo __('Master Document')?></th>
                <th width="35%" align="left"><?php echo __('Reassign To');?></th>
                <th width="50%" align="center" class="roles_permission_tblcol1"><?php echo __('Reassign?')?></th>
              </tr>
          </thead>
    	<tbody>
         <?php $i = 0;
                foreach ($masterDocOwnerData as $masterDocOwnerData):
                if ($i % 2 != 0) { ?>
                    <tr class="odd">
                <?php }else{ ?>
                    <tr>
                <?php }
                ?>
            <td align="left"  class="roles_permission_tblcol1">
            	<a href="#" onClick="javascript:submitForm(<?php echo $masterDocOwnerData['MasterDoc']['files_id'] ?>);"><?php echo __($masterDocOwnerData['MasterDoc']['name']);?></a>
            </td>
            <td align="left">
                <div class="styled-select usrlistdropdown"><?php echo $this->Form->input(__('owner'.$masterDocOwnerData['MasterDoc']['id']), array('id' => 'dropdown'.$masterDocOwnerData['MasterDoc']['id'], 'label' => false,'options' => $reassignToDdArr,'empty' => '--None Selected--', 'error' => false, 'class' => 'addmaster_dropdown_txtcolor', 'disabled' => true, 'style' => 'width:220px'));?></div>
            </td>
            <td align="center" class="roles_permission_tblcol1">
                <?php $id = $masterDocOwnerData['MasterDoc']['id'];?>
                <?php echo $this->Form->input('masterDocOwnerCheckBox', array('id' => $masterDocOwnerData['MasterDoc']['id'], 'type' => 'checkbox', 'name' => 'masterDocOwnerCheckBox[masterDocOwnerCheckBox'.$id.']', 'label' => false, 'div' => false));?>
            </td>
          </tr>
          <?php $i++; endforeach; ?>
        </tbody>
    </table>
    <br><br>
    <?php } 
    $id = $userData['User']['id'];
    ?>
    <?php echo $this->Form->submit(__('Save'),array('label' => false,'div' => false));?>
    <?php echo $this->Form->submit(__('Cancel'), array('label' => false,'type' => 'button','onClick' => "javascript:cancelForm($id);",'div' => false)); ?>
    <?php echo $this->Form->end();?>
    </div>
</div>
<?php echo $this->Form->create('MasterDocs',array('id' => 'docForm', 'url' => array('controller' => 'masterdocs', 'action' => 'describeMasterDoc'))); ?>
<?php echo $this->Form->input('docid',array('id' => 'docid','required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
<?php echo $this->Form->end();?>
<?php echo $this->Form->create('User',array('id' => 'UserForm', 'url' => array('controller' => 'users', 'action' => 'edit'))); ?>
<?php echo $this->Form->input('userid',array('id' => 'userid', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
<?php echo $this->Form->end();?>
<script>
    $(document).ready(function() {
		$("input[name^='masterDocOwnerCheckBox']").click(function(){
              var dropId = $(this).attr("id");
              $("#dropdown"+dropId).removeAttr("disabled");
          })
 	});
    function submitForm(id){
        $('#docid').val(id);
        $('#docForm').submit();
    }
    function cancelForm(id){
        $('#userid').val(id);
         $('#UserForm').submit();
    }
</script>