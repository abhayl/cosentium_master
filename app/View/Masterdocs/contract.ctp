<?php
$mouseUp = '';
if($this->action == 'contractassignement') {
	echo $this->Html->script('segmentation'); 
	$mouseUp = 'onMouseUp = "getTag()"';
	?>
	<!--[if IE 9]> 
		<?php echo $this->Html->script('segmentationIE'); ?>
	<![endif]-->
	
<?php }
?>
	<?php echo $this->element('usersetup_left_links'); ?>
	<div class="right">

<div class="heading"><?php echo __('Add Master Document'); ?> | <?php echo __('Define special change controls'); ?></div>


<div class="clsbodynav">
	<div class="clsbodynavinnerone unselectedstep">
		<div ><?php echo __('1'); ?></div>
		<?php echo __('Upload Master Document'); ?>
	</div>
	<div class="clsbodynavinnertwo unselectedstep">
		<div ><?php echo __('2'); ?></div>
		<?php echo __('Describe Document'); ?>
	</div>
	<div class="clsbodynavinnerthree unselectedstep">
		<div ><?php echo __('3'); ?></div>
		<?php echo __('Define Default Change Controls'); ?>
	</div>
	<div class="clsbodynavinnerfour selectedstep">
		<div ><?php echo __('4'); ?></div>
		<?php echo __('Define Special Change Controls');?>
	</div>
	<div class="clsone"></div>
</div>
<div class="descdocformcontainer">
<div class="blueheading_nopadding">Master Document : <?php echo $masterDocData['MasterDoc']['name'];?></div>
</div>
<div class="userlistcontainer">
	<table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0">
         <thead>
              <tr class="special_tbl_height20">
                
                <th width="100%" align="center">&nbsp;</th>
              </tr>
          </thead>
          <tbody>
              <tr class="odd">
                
                <td align="left">
	
		<div class="contract_viewer" id="container" <?php echo $mouseUp; ?>> <?php echo $data['MasterDoc']['converted_html']; ?> </div>
		</td>
			</tr>
          </tbody>
    </table>
		<?php if($this->action == 'contractassignement' || $this->action == 'editSections') {
			echo $this->Form->create('masterDoc',array('action' => 'defaultChangeControl')); ?>
			<br><br><div>
				<span class="addmaster_labeltext"><?php echo __('Section Name');?></span>
				<?php if($this->Session->read('editAction') == NULL) {
					$sectionValue = '';
					$updateSectionId = '';
				} ?>
				<?php echo $this->form->input('sectionName', array('label' => false, 'id'=> "name" , 'maxlength'=> "25", 'value' => $sectionValue)); ?>
				<?php echo $this->form->hidden('oldSectionName', array('label' => false, 'id'=> "oldSectionName" , 'maxlength'=> "25", 'value' => $sectionValue)); ?>
				<?php echo $this->form->hidden('updateSectionId', array('label' => 'updateSectionId', 'id'=> "updateSectionId",'value' => $updateSectionId)); ?>
				<?php echo $this->form->hidden('unEditedContract', array('label' => 'unEditedContract', 'id'=> "unEditedContract",'value' => '')); ?>
			</div>
			<?php echo $this->form->hidden('contract', array('label' => 'Contract', 'id'=> "contract", 'value' => '')); ?>
			 <span class="addmaster_smalltext">Select an area of text by clicking your cursor at the beginning point, dragging your cursor to the end point, and releasing. Then click Next.</span><br>
			<br>
			<div>
				<?php 
				if($this->action == 'editSections') {
					echo $this->Form->submit(__('Next'),array('id'=>'confirmation','label' => false,'type' => 'button','onclick' => "submitEditForm()",'div' => false)); 
				}
				else {
					echo $this->Form->submit(__('Next'),array('id'=>'confirmation','label' => false,'type' => 'button','onclick' => "submitForm($sectionValue)",'div' => false)); 
				}
				?> 
				<?php echo $this->Form->submit(__('Cancel', true), array('type' => 'button', 'onclick' => "location.href='" . $this->Html->url(Configure::read('SITE_BASE_URL') . 'masterDocs/contract') . "'", 'name' => 'done', 'div' => false)); ?>
			</div>
			<?php echo $this->Form->end(); 
		}
		else { ?>
		<br><br>
			<span class="addmaster_labeltext">Change Controls</span>  <?php echo $this->Form->submit(__('ADD', true), array('type' => 'button', 'class'=>'profile', 'onclick' => "location.href='" . $this->Html->url(Configure::read('SITE_BASE_URL') . 'masterdocs/contractassignement') . "'", 'name' => 'Change', 'div' => false)); ?>
			<br/><br/>
			<table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0">
				 <thead>
					  <tr>
						<th width="25%" align="left" class="roles_permission_tblcol1">Section Name</th>
						<th width="25%" align="left">Decision Maker</th>
						<th width="30%" align="left">Representing Interests Of</th>
						<th width="20%" align="center">Action</th>
					  </tr>
				  </thead>
				  <tbody>
				  
					<?php 
					foreach ($sectionListing as $listing) { 
						if(empty($listing['User']['image'])){
							$listing['User']['image'] = Configure::read('DEFAULT_IMAGE_NAME');
						}
						if(empty($listing['RepresentingInterest']['name'])){
							$listing['RepresentingInterest']['name'] = Configure::read('REPRESENTING_OF_DEFAULT');
						}
						$name = str_replace('_',' ', $listing['Section']['name']);
						$decision_maker = $listing['User']['last_name'] . " " .$listing['User']['first_name'];
						$listing['Section']['tooltip'] = "Section name: ". $name ."<span></span> <br>Decision maker: <span>". $decision_maker ."</span><br>Opinion Provider: <span>". $listing['Section']['opinionproviders']."<span>";
					?>
						
					<tr class="odd">
							<td align="left" class="roles_permission_tblcol1"><?php echo $name; echo "<div id='Tooltip_". $listing['Section']['name'] ."' style='display:none;'>".$listing['Section']['tooltip']."</div>";?></td>
							<td align="left"><?php echo $this->Html->image($listing['User']['image'], array('width' => '27px','height' => '26px', 'style' => "vertical-align:middle;"));?><?php echo $decision_maker;?></td>
							<td align="left"><?php echo $listing['RepresentingInterest']['name'];?></td>
							<td align="center" id = "remove">
							<?php if($listing['Section']['name'] != Configure::read('SECTION_FOR_ENTIRE_DOCUMENT')){ ?>
								<a onClick = "editSection('<?php echo $listing['Section']['name'];?>',<?php echo $listing['Section']['id'];?>)" id="remove-section">Edit</a>
								<a onClick = "confirm('<?php echo $listing['Section']['name'];?>',<?php echo $listing['Section']['id'];?>)" id="remove-section">Delete</a>
								
							<?php } ?>
						</td>	
					</tr>
					<?php } ?>
				  </tbody>
			</table>
			<div>
			<?php echo $this->Form->create('masterDoc',array('action' => 'removeSections')); ?>
			<?php echo $this->form->hidden('removedSectionContract', array('label' => 'Contract', 'id'=> "contract", 'value' => '')); ?>
			<?php if($this->Session->read('editAction') == NULL) {
					$sectionValue = '';
				} ?>
			<?php echo $this->form->hidden('removedSectionId', array('label' => 'sectionId', 'id'=> "sectionId",'value' => $sectionValue)); ?>
			<?php echo $this->Form->end(); 
			echo $this->Form->create('masterDoc',array('action' => 'editSections')); ?>
			<?php echo $this->form->hidden('action', array('label' => 'action', 'id'=> "action",'value' => '')); ?>
			<?php echo $this->form->hidden('editSectionNameId', array('label' => 'editsectionId', 'id'=> "editSectionNameId",'value' => '')); ?>
			<?php echo $this->Form->end();
				echo $this->Form->submit(__('Done', true), array('type' => 'button', 'onclick' => "location.href='" . $this->Html->url(Configure::read('SITE_BASE_URL') . 'masterdocusers') . "'", 'name' => 'done', 'div' => false)); ?>
			</div>
		<?php } ?>
	</div>

</div>
<?php 
	if(isset($className) && !empty($className)) {
		echo "<input type='hidden' value='" . $className ."' id='className'>"; 
	}
	else {
		echo "<input type='hidden' value='' id='className'>"; 
	}
?>
<?php echo $this->element('overlay'); 
echo $this->element('validateSection');
echo $this->element('validateSectionName');
echo $this->element('validateUniqueSectionName');
echo $this->element('validateSpcCharsSectionName');
?>

<?php if($this->action == 'contract' || $this->action == 'editSections' ) { ?>
<script>
	function confirm(sectionName,sectionId) {
		document.getElementById('light').style.display = 'block';
		document.getElementById('fade').style.display = 'block';
			$( "#confirmBut" ).click(function() {
				removeSelection(sectionName,sectionId);
			});
	}

	function removeSelection(sectionName,sectionId) {
		document.getElementById('light').style.display = 'none';
		document.getElementById('fade').style.display = 'none';
		$("."+sectionName).css("background-color", "");
		$("."+sectionName).removeClass(sectionName);
		document.getElementById('contract').value =  document.getElementById('container').innerHTML;
		document.getElementById('sectionId').value = sectionId;
		document.getElementById('action').value = 'remove';
		document.getElementById('masterDocRemoveSectionsForm').submit();
	}
	
	function editSection(sectionName,sectionId){
		document.getElementById('editSectionNameId').value = sectionName + "," + sectionId;
		document.getElementById('action').value = 'edit';
		document.getElementById('masterDocEditSectionsForm').submit();
	}
	
	function submitEditForm() {
		document.getElementById('unEditedContract').value = document.getElementById('container').innerHTML;
		if(isValid(document.getElementById('name').value) == false) {
			document.getElementById('light5').style.display = 'block';
			document.getElementById('fade5').style.display = 'block';
			return false;
		}

		if(!document.getElementById('name').value){
			document.getElementById('light2').style.display = 'block';
			document.getElementById('fade2').style.display = 'block';
			return false;
		}
		else {
			var withoutTrim = document.getElementById('name').value;
			var sectionName = $.trim(document.getElementById('name').value);
			sectionName = sectionName.replace(/\s\s+/g, ' ');
			sectionName = sectionName.replace(/ /g,"_");
				$.ajax({
					type: "post",		
					// Request method: post, get
					url: "/masterdocs/validatesectionname/",
					data: {name:sectionName},		// Form variable
					success: function(response) {
						if(response == 1) {
							document.getElementById('masterDocDefaultChangeControlForm').submit();
							return true;
						}	
						else {	
							if(withoutTrim == document.getElementById('oldSectionName').value) {
								document.getElementById('masterDocDefaultChangeControlForm').submit();
								return true;
							}
							document.getElementById('light3').style.display = 'block';
							document.getElementById('fade3').style.display = 'block';
							return false;
						}	
					}
				});
			}
			return false;	
	}
	
	function isValid(name) {
		return !/[().~`!#@$%\^&*+=\-\[\]\\';,/{}|\\_":<>\?]/g.test(name);
	}
	
</script>	
<?php }
else { ?>
<script>
	function confirm() {
		document.getElementById('light').style.display = 'block';
		document.getElementById('fade').style.display = 'block';
	}
</script>
<?php }	
?>
<script>
$(document).ready(function() {
 
	var changeTooltipPosition = function(event) {
	  var tooltipX = event.pageX - 8;
	  var tooltipY = event.pageY + 8;
	  $('div.tooltip').css({top: tooltipY, left: tooltipX});
	};
 
	var showTooltip = function(event) {
	  
	  $('div.tooltip').remove();
	  var selectedClass =  'Tooltip_' + $(this).attr("class").split(/\s/).pop();
	  var data = document.getElementById(selectedClass.toString());
	  $('<div class="tooltip">' + data.innerHTML +'</div>')
            .appendTo('body');
	  changeTooltipPosition(event);
	  event.stopImmediatePropagation();
	};
 
	var hideTooltip = function() {
	   $('div.tooltip').remove();
	};
 
	$(document.getElementById('className').value).bind({
	   mousemove : changeTooltipPosition,
	   mouseenter : showTooltip,
	   mouseleave: hideTooltip
	});
});
</script>
	