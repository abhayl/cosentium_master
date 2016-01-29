<?php echo $this->element('usersetup_left_links'); ?>
<div class="right">
<div class="heading"><?php echo __('Master Documents');?></div>
	<div class="userlistcontainer">    
       	<table width="100%" border="0">
          <tr>
            <td width="19%">
                <?php echo $this->Form->submit(__('Add New'), array('label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'masterdocs/addMasterDoc')."'",'div' => false));?>
            </td>
            <td width="4%"><?php echo __('View')?>
            </td>
            <?php echo $this->Form->create('MasterDoc', array('action' => 'index'));?>
            <td width="39%">
                <div class="styled-select usrlistdropdown"><?php echo $this->Form->input('master_doc_filter', array('onchange' => 'javascript:submit();','options' => $filterDd,'label' => false,'div' => false, 'style' => 'width:220px', 'class' => 'addmaster_dropdown_txtcolor', 'default' => $filterSelectedOption));?></div>
            </td>
            <td width="38%" align="right">
            <div class="styled-select usrlistdropdown"><?php echo $this->Form->input('records_per_page', array('onchange' => 'javascript:submit();','options' => $padinationDd,'label' => false,'div' => false, 'style' => 'width:220px', 'class' => 'addmaster_dropdown_txtcolor', 'default' => $paginationSelectedOption));?></div>
            </td>
            <?php echo $this->Form->end();?>
          </tr>
        </table>
        <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0" id="usr_list_tbl">
         <thead>
              <tr>
                <th width="23%" align="left" class="roles_permission_tblcol1"><?php echo $this->Paginator->sort('name', __('Master Documents').' '.$this->Html->image($sortImage['name']),$options = array('escape' => false));?></th>
                <th width="30%" align="left" class="roles_permission_tblcol1"><?php echo $this->Paginator->sort('User.last_name', __('Owner').' '.$this->Html->image($sortImage['name']),$options = array('escape' => false));?></th>
                <th width="17%" align="center"><?php echo $this->Paginator->sort('available_after_dt', __('Available After').' '.$this->Html->image($sortImage['name']),$options = array('escape' => false));?></th>
                <th width="30%" align="center"><?php echo $this->Paginator->sort('available_until_dt', __('Available Until').' '.$this->Html->image($sortImage['name']),$options = array('escape' => false));?></th>
              </tr>
          </thead>
          <tbody>
              <?php $i = 0;
              foreach ($masterDoc as $masterDoc):
                  if($i % 2 != 0){ ?>
                      <tr class="odd">
                  <?php }else{ ?>
                      <tr>
                  <?php }
                  $masterDocName = explode('.docx', $masterDoc['MasterDoc']['name']);        
                if($masterDoc['MasterDoc']['owner'] == CakeSession::read('user.User.id')){ ?>
                          <td align="left" class="roles_permission_tblcol1"><a href="#" onClick="javascript:submitForm(<?php echo $masterDoc['MasterDoc']['files_id'] ?>);"><?php echo __($masterDocName[0]);?></a><?php //echo $this->Html->link(__($masterDocName[0]), array('action' => '#', 'onclick' => 'javascript:submitForm();'));?></td>
                <?php }else{ ?>
                    <td align="left" class="roles_permission_tblcol1"><?php echo $masterDocName[0]; ?></td>
                <?php }
                         ?> 
                <?php if (!empty($masterDoc['User']['image'])) {
                    $profileImage = $masterDoc['User']['image'];
                } else {
                    $profileImage = Configure::read('DEFAULT_IMAGE_NAME');
                }?>
                <td align="left" class="roles_permission_tblcol1"><?php echo $this->Html->image($profileImage, array('class' => "usrthumbnail_right_margin"));?><?php echo $masterDoc['User']['last_name'].', '.$masterDoc['User']['first_name'];?></td>
                <td align="center">
                    <?php if($masterDoc['MasterDoc']['available_after_dt'] != NULL){
                        echo date(Configure::read('DATE_FORMAT'), strtotime($masterDoc['MasterDoc']['available_after_dt']));
                    }else{
                        echo $masterDoc['MasterDoc']['available_after_dt'];
                    }?>
                </td>
                <td align="center">
                    <?php if($masterDoc['MasterDoc']['available_after_dt'] != NULL){
                        echo date(Configure::read('DATE_FORMAT'), strtotime($masterDoc['MasterDoc']['available_until_dt']));
                    }else{
                        echo $masterDoc['MasterDoc']['available_until_dt'];
                    }?>
                </td>
              </tr>
              <?php $i++; endforeach; ?>
			</tbody>
        </table>
<?php echo $this->Form->create('MasterDoc',array('id' => 'docForm','action' => 'describeMasterDoc','action' => 'describeMasterDoc')); ?>
    <?php echo $this->Form->input('docid',array('id' => 'docid','required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
<?php echo $this->Form->end();?>
		<table width="100%" border="0">
          <tr>
            <td><?php echo $this->Form->submit(__('Add New'), array('label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'masterdocs/addMasterDoc')."'",'div' => false));?></td>
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
<script type="text/javascript">
	function submitForm(id){
         $('#docid').val(id);
         $('#docForm').submit();
     }
</script>