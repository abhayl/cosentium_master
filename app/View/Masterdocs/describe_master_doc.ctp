<?php echo $this->element('usersetup_left_links'); ?>
<div class="right">
    <div class="heading"><?php echo __('Add Master Document | Describe Document')?></div>
    <div class="clsbodynav">
        <div class="clsbodynavinnerone unselectedstep">
            <div >1</div><?php echo __('Upload Master Document')?>
        </div>
        <div class="clsbodynavinnertwo selectedstep">
            <div >2</div><?php echo __('Describe Document')?>
        </div>
        <div class="clsbodynavinnerthree unselectedstep">
            <div >3</div><?php echo __('Define Default Change Controls')?>
        </div>
        <div class="clsbodynavinnerfour unselectedstep">
            <div >4</div><?php echo __('Define Special Change Controls')?>
        </div>
        <div class="clsone"></div>
    </div>
    <div class="descdocformcontainer">
    <div class="usernameStyleAddUser blueheading_nopadding"><?php echo __('Master Document Information')?></div>
        <?php echo $this->Form->create('MasterDoc', array('action' => 'describeMasterDoc/'));?>
        <fieldset>
        <div>
            <?php echo $this->Form->input('name', array('value' => $masterDocData['MasterDoc']['name'],'label' => __('Friendly Master Document Name'),'required' => 'false', 'div' => false, 'maxlength' => Configure::read('MAX_FILENAME_LENGTH'))); ?>
        </div>
        <div>
            <label for="master_doc_owner"><?php echo __('Master Document Owner');?></label>
            <div class="usernameStyleAddMaster"><?php echo $this->Html->image($profileImage, array('class' => "usrthumbnail_right_margin"));?><?php echo $masterDocData['User']['last_name'].', '.$masterDocData['User']['first_name']; ?></div>
        </div>
        <div>
            <label for="available_after"><?php echo __('Available After');?></label>
            <?php if($masterDocData['MasterDoc']['available_after_dt'] != NULL){
                $masterDocAfterDate = date(Configure::read('DATE_FORMAT'), strtotime($masterDocData['MasterDoc']['available_after_dt']));
            }else{
                $masterDocAfterDate = $masterDocData['MasterDoc']['available_after_dt'];
            }?>
            <?php echo $this->Form->input('CalenderMasterDoc.available_after_dt', array('placeholder' => 'mm/dd/yyyy','value' => $masterDocAfterDate, 'label' => false,'id' => 'datepicker3','required' => 'false','error' => false, 'div' => false, 'readonly' => 'readonly'));?>
            <?php  echo $this->Form->error('available_after_dt', null, array('class' => 'error-message')); ?>
        </div>
        <div>
            <label for="available_until"><?php echo __('Available Until');?></label>
            <?php if($masterDocData['MasterDoc']['available_until_dt'] != NULL){
                $masterDocUntilDate = date(Configure::read('DATE_FORMAT'), strtotime($masterDocData['MasterDoc']['available_until_dt']));
            }else{
                $masterDocUntilDate = date(Configure::read('DATE_FORMAT'), strtotime(Configure::read('DEFAULT_MASTERDOC_UNTIL_DT')));
            }?>
            <?php echo $this->Form->input( 'CalenderMasterDoc.available_until_dt', array('placeholder' => 'mm/dd/yyyy','value' => $masterDocUntilDate, 'id' => 'datepicker4', 'label' => false,'required' => 'false','error' => false, 'div' => false,'readonly' => 'readonly')); ?>
            <?php  echo $this->Form->error('available_until_dt', null, array('class' => 'error-message')); ?>
        </div>
        <div>
            <label for = "issued_copies_valid_for"><?php echo __('Issued copies are valid for');?></label>
            <div class="styled-select addmaster_dropdown_width_divclass">
                <?php 
				if(!isset($masterDocData['MasterDoc']['validity'])) 
					$masterDocData['MasterDoc']['validity'] = 1;
				echo $this->Form->input(__('validity'), array('value' => $masterDocData['MasterDoc']['validity'],'label' => false,'options' => $range, 'error' => false, 'class' => "addmaster_dropdown_width_class"));?>
            </div>
            <span class="reviewdeal_righttext"><?php echo __('days from issuance')?></span>
        </div>
        <div class="clr">
            <label></label>
            <?php echo $this->Form->submit(__('Next'),array('label' => false,'div' => false));?>
            <?php if($masterDocData['MasterDoc']['available_after_dt'] != NULL) {
                $cancelBtnCondition = "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'masterdocusers')."'";
            } else {
                $cancelBtnCondition = "javascript:document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'";
            }?>
			<?php echo $this->Form->submit(__('Cancel'), array('label' => false,'type' => 'button','onclick' => $cancelBtnCondition,'div' => false)); ?>
            <?php echo $this->element('overlayformaserdoc');?>
        </div>
        </fieldset>
        </form>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
       	$( "#datepicker3" ).datepicker({
        dateFormat: 'mm/dd/yy',
        minDate: 0
		/*showOn: "button",
      	buttonImage: "img/calendar.jpg",
      	buttonImageOnly: true*/
	});
	$( "#datepicker4" ).datepicker({
            dateFormat: 'mm/dd/yy',
            minDate: 0
		/*showOn: "button",
     	buttonImage: "img/calendar.jpg",
      	buttonImageOnly: true*/
    });
	});	
</script>
