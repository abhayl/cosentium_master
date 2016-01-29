<?php echo $this->element('personalinfo_left_links'); ?>
<div class="right">
<div class="heading"><?php echo __('Manage Profile Picture');?></div>
    <div class="profilepicinnerdiv">
            <div class="profilepicdiv">
                <div>
					<?php echo $this->Html->image($amazonS3ServerPath.$profileImage, array('width' => '101px','height' => '100px','class' => 'profpageimg'));?>
					<div><div class="profname"><?php echo $user['User']['last_name']." ".$user['User']['first_name'];?></div><br><br><br>
						<?php echo $this->Form->create('User', array('action' => 'deleteprofilepic/' . $profileImage));
						if($profileImage != Configure::read('DEFAULT_IMAGE_NAME')){
							echo $this->Form->submit(__('Delete Picture'), array('type' => 'button','onclick' => "javascript:document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'", 'class' => 'profile', 'div' => false));
						} else {
							echo $this->Form->submit(__('Delete Picture'), array('onclick' => 'javascript:return confirm("Are you sure you want to delete your photo?");', 'class' => 'profile', 'div' => false, 'disabled' => true));
						}
                                                echo $this->element('overlay');
						echo $this->Form->end();?>
					</div>
				</div>
			</div>
        
			<?php echo $this->Form->create('User', array('type' => 'file', 'action' => 'profilePic'));?>
                    <div class="browsediv">
                        <table border="0">
                                <tr>
                                    <td width="450"><?php echo __('Browse and select picture to upload')?></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="file-wrapper">
                                            <?php 
                                            if(!strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE' ) === TRUE) { ?>
                                                <?php echo $this->Form->input('myText',array('id' => 'myText','type' => 'text','label' => false,'required' => 'false', 'div' => false, 'style' => "margin-bottom:0px;", 'readonly' => 'readonly')); ?>
                                                <?php echo $this->Form->input('image', array('id' => 'attach','error' => false,'div' => false, 'label' => false,'type' => 'file'));?>
                                                <div class="button1"><?php echo $this->Html->image('browse.png');?></div>
                                            <?php }else{ 
                                                echo $this->Form->input('image', array('error' => false,'div' => false, 'label' => false,'type' => 'file', 'style' => 'margin:5px;'));
                                            }
                                            ?>
                                            
                                        </div>
                                    </td>
                                    <td valign="top">Maximum size limit is 2MB. Permitted formats are<br>JPG,PNG and GIF.</td>
                                </tr>
                                <tr>
                                    <td><?php  echo $this->Form->error('image', null, array('class' => "profilepicerror")); ?></td>
                                    <td>&nbsp;</td>
                                </tr>
                        </table>
                    </div>
			
        
        
            <div><?php 
				echo $this->Form->submit(__('Upload and Save', true), array('div' => false));
                                //echo $this->Form->submit(__('Upload and Save', true), array('type' =>'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/profilePic')."'",'name' => 'save','div' => false));
				echo $this->Form->submit(__('Cancel', true), array('type' =>'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/dashboard')."'",'name' => 'cancel','div' => false));
				?>
            </div>
        <?php echo $this->Form->end();?>
            
    </div>   
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".button1").click(function(){
                    $("#attach").trigger("click");
		});

		$("#myText").click(function(){
                    $("#attach").trigger("click");
		});

		$("#attach").bind('change', function(){	
                    var filename = $("#attach").val().split('\\').pop();
                    $("#myText").val(filename);
				
		});
	});	
</script>