<div class="innercontent">
<div class="full_innercontent">
	<div class="heading"><?php echo __('Upload Customer Response Document')?></div>
    <div class="descdocformcontainer">
            <?php echo $this->Form->create('Redline', array('type' => 'file', 'action' => 'upload'));?>
        <fieldset>
        <div class="clsfileupload1">

            <table border="0" width="99%">
                  <tr>
                    <td width="95%" height="20"><?php echo __('Select the file')?></td>
                    
                  </tr>
                  
                  <tr>
                    <td>  
                    <div class="file-wrapper">
                    <?php  
                        if(!strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE' ) === TRUE) { ?>
                            <?php echo $this->Form->input('myText',array('id' => 'myText','type' => 'text','label' => false,'required' => 'false', 'div' => false, 'style' => "margin-bottom:0px; width:75%", 'readonly' => 'readonly')); ?>
                            <?php echo $this->Form->input('s3_key', array('id' => 'attach','error' => false,'div' => false, 'label' => false,'type' => 'file'));?>
                            <div class="button2"><?php echo $this->Html->image('browse.png');?></div>
                        <?php }else{ 
                            echo $this->Form->input('s3_key', array('id' => 'attachIe','error' => false,'div' => false, 'label' => false,'type' => 'file', 'style' => 'margin:5px;'));
                        }
                    ?>
                    </div>  
                    <?php //echo $this->Form->input('s3_key', array('error' => false,'div' => false, 'label' => false,'type' => 'file', 'value' => 'Choose File'));?>
                    </td>
                    
                  </tr>
                  <tr>
                    <td class = "error-message">
                        <?php  echo $this->Form->error('s3_key', null, array('div' => false)); ?>
                    </td>
                    <td>&nbsp;</td>
                  </tr>
            </table>
            <div class="clsdwnarrow"></div>
            </div>
            <div class="selectedfiles" id ="selectedfiles" style = "display:none;">
                File:<strong><span id="master_selected_doc1"></span></strong>
            </div>

        <div class="clr"></div>


        <div class="clsfileupload">
            <table border="0" width="99%">
                  <tr>
                    <td width="75%" height="20"><?php echo __('Click the Attach File button')?></td>
                    <td width="24%">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20"><?php echo __('When the upload is complete the information will appear to the right')?></td>
                    <td align="right">
                    <?php echo $this->Form->submit(__('Attach File', true), array('div' => false, 'name' => 'attach', 'disabled' => true));?>
                    <?php echo $this->Form->input(__('Submit', true), array('id' => 'submit' ,'label' => false, 'value' => $submitted, 'div' => false, 'id' => 'submit', 'type' => 'hidden'));?>
                    </td>
                  </tr>
                  <tr>
                    <td class="error-message"></td>
                    <td>&nbsp;</td>
                  </tr>
            </table>
            <div class="clsdwnarrow"></div>
            </div>
			<?php echo $this->Form->input(__('dealRefId'), array('id' => 'dealRefId' ,'label' => false, 'value' => $dealRefId, 'div' => false, 'type' => 'hidden'));?>
        <div class="selectedfiles" id ="uploadedfiles" style = "display:none;">
        <?php if (strlen($filename) > Configure::read('FILENAME_STRING_LENGTH')) {
                $filename = substr($filename, 0, Configure::read('FILENAME_STRING_LENGTH')).'...';
            } ?>	    
		
		<?php echo __('You have just uploaded the following file<br>File:<strong>'. $filename .'</strong><br>Size:<strong>'.$filesize.' KB</strong>');?>
        </div>
        <div class="clr"></div>
        <div class="clsfileupload">
            <table border="0" width="99%">
                  <tr>
                    <td width="75%" height="20"><?php echo __('Click the Next button to go to the next step.')?></td>
                    <td width="24%">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20">&nbsp;</td>
                    <td align="right">
                    <?php echo $this->Form->submit(__('Next'), array('onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'redlines/detail/')."'", 'id' => 'next','type' =>'button', 'div' => false, 'disabled' => true));?>
                    </td>
                  </tr>
                  <tr>
                    <td class="error-message"></td>
                    <td>&nbsp;</td>
                  </tr>
            </table>
		</div>
		<div class="selectedfiles" id ="dealRelation" style = "display:none;">
			<?php echo __('Deal Relationship details:<br>Deal Name:<strong>'.$dealInfo['Deal']['name'].'</strong><br>Upload is a response to:<strong>'. $stage. '/'.$turn['Redline']['turn'].'( '. $filename .') </strong>');?>
		</div>
        </fieldset>
		<br><br><br>
        <?php echo $this->Form->end();?>
	</div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
          var submitval = $("#submit").val();
          if(submitval == 1){
              $("#uploadedfiles").css({ display: "block" });
              $("#dealRelation").css({ display: "block" });
              $("#next").removeAttr("disabled");
          }
          
          $(".button2").click(function(){
            $("#attach").trigger("click");
        });

		$("#myText").click(function(){
                    $("#attach").trigger("click");
		});

		$("#attach").bind('change', function(){	
                    var filename = $("#attach").val().split('\\').pop();
                    $("#myText").val(filename);
                    
					if (filename.length > <?php echo Configure::read('FILENAME_STRING_LENGTH')?>) {
                        filename = filename.substring(0, <?php echo Configure::read('FILENAME_STRING_LENGTH')?>)+'...';
                    }
					$('#master_selected_doc1').html(filename);
					$("#selectedfiles").css({ display: "block" });
                    $("input[name= attach]").removeAttr("disabled");
		});
		$("#attachIe").bind('change', function(){	
                    var filename = $("#attachIe").val().split('\\').pop();
                    $("#myText").val(filename);
                    
					if (filename.length > <?php echo Configure::read('FILENAME_STRING_LENGTH')?>) {
                        filename = filename.substring(0, <?php echo Configure::read('FILENAME_STRING_LENGTH')?>)+'...';
                    }
					$('#master_selected_doc1').html(filename);
					$("#selectedfiles").css({ display: "block" });
                    $("input[name= attach]").removeAttr("disabled");
		});
		
	});	
</script>