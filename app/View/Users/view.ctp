<?php echo $this->element('usersetup_left_links'); ?>
<div class="right">
    <div class="heading"><?php echo __('All Users');?></div>
    <div class="userlistcontainer">
		<?php if(isset($successMessage)) echo "<div class='successmsg'>".$successMessage."</div>";?>
        <table width="100%" border="0">
			<tr>
				<td>
					<?php echo $this->Form->submit(__('Add New User'), array('label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/add')."'",'div' => false));?>
					<?php echo $this->Form->submit(__('Reset Password'), array('id'=>'reset1','label' => false,'type' => 'button','onclick' => "javascript:document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'",'div' => false));?>
				</td>
                <?php echo $this->Form->create('User', array('action' => 'edit'));?>
                <td align="right">
					<div class="styled-select usrlistdropdown"><?php echo $this->Form->input('records_per_page', array('onchange' => 'javascript:submit();','options' => $padinationDd,'label' => false,'div' => false, 'style' => 'width:220px;', 'default' => $selectedOption));?></div>
				</td>
				
                <?php echo $this->Form->end();?>
			</tr>
        </table>
		<?php echo $this->Form->create('User1', array('url' => '/users/resetPassword'));?>
        <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0">
            <thead>
              <tr>
				<th align="center" class = "forchkboxcol"><?php
				if($this->Paginator->counter('{:count}') > 1) {			
					echo $this->Form->input('', array('type' => 'checkbox','id'=>'checkAll', 'checked' => false, 'div' => false));
				}
				?></th>
                <th align="left"><?php echo $this->Paginator->sort('last_name', __('Name').' '.$this->Html->image($sortImage['last_name']),$options = array('escape' => false));?></th>
                <th align="left"><?php echo $this->Paginator->sort('username', __('Username').' '.$this->Html->image($sortImage['username']),$options = array('escape' => false)); ?></th>
                <th align="left"><?php echo $this->Paginator->sort('Role.name', __('Role').' '.$this->Html->image($sortImage['Role.name']),$options = array('escape' => false)); ?></th>
                <th align="left"><?php echo $this->Paginator->sort('UserCompany.status', __('Status').' '.$this->Html->image($sortImage['UserCompany.status']),$options = array('escape' => false)); ?></th>
                <th align="center"><?php echo __('Action');?></th>
              </tr>
			</thead>
			<tbody>
              <?php $i = 0;
              foreach ($users as $user):
                  if($i % 2 != 0){ ?>
                      <tr class="odd">
                  <?php }else{ ?>
                      <tr>
                  <?php }?>
              
				<?php if ($user['Role']['id'] == Configure::read('DEFAULT_ROLE_ID')){?>
				<td align="center">
					<div class="checkbox"></div>
				</td>
				<?php } else { ?>
				<td align="center" class = "forchkboxcol">
					<input type="checkbox" value="<?php echo $user['User']['id'];?>" id="Users" name="Users[]">
				</td>
				<?php } ?>
				<?php if (!empty($user['User']['image'])) {
                    $profileImage = $user['User']['image'];
					$amazonS3ServerPath =  Configure::read('AWS_PROFILE_IMG_IMAGES_URL');
                } else {
                    $profileImage = Configure::read('DEFAULT_IMAGE_NAME');
					$amazonS3ServerPath = "";
                }?>
                <td align="left"><?php echo $this->Html->image($amazonS3ServerPath.$profileImage, array('class' => "usrthumbnail_right_margin"));?><?php echo $user['User']['last_name'].', '.$user['User']['first_name']; ?></td>
                <td align="left"><?php echo $user['User']['username'];?></td>
                <td align="left"><?php echo $user['Role']['name']; ?></td>
                <td align="left"><?php if(!$user['UserCompany']['status']){ 
                    echo $this->Html->image(Configure::read('INACTIVE_USER_IMAGE')).__(' Inactive');
                    }else{
                        echo $this->Html->image(Configure::read('ACTIVE_USER_IMAGE')).__(' Active');
                    }?>
                </td>
                <?php if($user['Role']['id'] == Configure::read('DEFAULT_ROLE_ID')){ ?>
				<td align="center"></td>
                <?php }else{ ?>
				<td align="center">
                        <a href="#" onClick="javascript:submitForm(<?php echo $user['User']['id'] ?>);"><?php echo __('Edit');?></a>
                        <?php //echo $this->Html->link(__('Edit'), array('controller' => 'users', 'action' => 'edit',$user['User']['id']));?>
                    </td>  
                <?php }
                ?>
              </tr>
              <?php $i++; endforeach; ?>
			</tbody>
        </table>
		<?php echo $this->element('overlay'); ?>
		</form>
        <?php echo $this->Form->create('User',array('id' => 'UserForm', 'url' => array('controller' => 'users', 'action' => 'edit'))); ?>
        <?php echo $this->Form->input('userid',array('id' => 'userid', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
        <?php echo $this->Form->end();?>
        <table width="100%" border="0">
          <tr>
            <td>
                <?php echo $this->Form->submit(__('Add New User'), array('label' => false,'type' => 'button','onclick' => "location.href='".$this->Html->url(Configure::read('SITE_BASE_URL').'users/add')."'",'div' => false));?>
                <?php echo $this->Form->submit(__('Reset Password'), array('id'=>'reset2','label' => false,'type' => 'button','onclick' => "javascript:document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'",'div' => false));?>
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
<script type="text/javascript">
    function submitForm(id){
         $('#userid').val(id);
         $('#UserForm').submit();
     }
    $("#checkAll").change(function () {
         $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
    $( "#reset1" ).click(function() {
         //alert($("input:checkbox:checked").length);
         if($("input:checkbox:checked").length >= 1){
              $('#confirm_message').html('<?php echo $confirm_message;?>');
              $('#confirmBut').show();
              $('#cancelBut').show();
              $('#okBut').hide();

         }else {
              $('#confirm_message').html('<?php echo $confirm_message2; ?>');
              $('#confirmBut').hide();
              $('#cancelBut').hide();
              $('#okBut').show();
         }
    });
    $( "#reset2" ).click(function() {
         //alert($("input:checkbox:checked").length);
         if($("input:checkbox:checked").length >= 1){
              $('#confirm_message').html('<?php echo $confirm_message;?>');
              $('#confirmBut').show();
              $('#cancelBut').show();
              $('#okBut').hide();		
         }else {
              $('#confirm_message').html('<?php echo $confirm_message2; ?>');
              $('#confirmBut').hide();
              $('#cancelBut').hide();
              $('#okBut').show();
         }
    });
</script>


