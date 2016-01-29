<?php echo $this->element('personalinfo_left_links'); ?>
<div class="right">
	<div class="heading"><?php echo __('Personal Account Info');?></div>
        <?php if(isset($successMessage)) echo "<div class='successmsg' style = 'text-align:left' >".$successMessage."</div>";?>
	<?php echo $this->Form->create('User');?>
		<fieldset>
			<div>
				<label for="email"><?php echo __('Name');?></label>
				<div class="usernameStyle"><?php echo $user['User']['first_name']." ".$user['User']['last_name']; ?></div>
			</div>
			<div>
				<label for="email"><?php echo __('Email');?></label>
				<div class="usernameStyle"><?php echo $user['User']['username']; ?></div>
			</div>
                        <?php if(isset($successMessage)){ ?>
                        <div>
				<label for="email"><?php echo __('New Email');?></label>
				<div class="usernameStyle"><?php echo $user['User']['updated_username']; ?></div>
			</div>
                        <?php } ?>
			<div>
				<label for="email"><?php echo __('Company');?></label>
				<div class="usernameStyle"><?php echo $user['Company']['company_name']; ?></div>
			</div>
			<div>
				<label for="email"><?php echo __('Role');?></label>
				<div class="usernameStyle"><?php echo $user['Role']['name']; ?></div>
			</div>
			<div>
				<label for="email"><?php echo __('Status');?></label>
				<div class="usernameStyle"><?php if(!$user['UserCompany']['status']){
					echo __('Inactive');
				}else{
					echo __('Active');
				} ?></div>
			</div>
			<div>
				<label></label>
                    <?php echo $this->Form->submit(__('Edit'),array('type' => 'button','onclick' => "javascript:submitForm(".$user['User']['id'].");"));?>
			</div>
		</fieldset>  
    <?php echo $this->Form->end();?>
    <?php echo $this->Form->create('User',array('id' => 'UserForm', 'url' => array('controller' => 'users', 'action' => 'edit/?action= personalinfo'))); ?>
    <?php echo $this->Form->input('userid',array('id' => 'userid', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
    <?php echo $this->Form->end();?>
</div>
<script type="text/javascript">
	function submitForm(id){
         $('#userid').val(id);
         $('#UserForm').submit();
     }
</script>