<?php echo $this->element('usersetup_left_links'); ?>
<div class="right">
	<div class="heading">Profile Dashboard</div>
	<?php if(isset($successMessage)) echo "<div class='successmsg'>".$successMessage."</div>";?>
	<div class="dashinnerdiv">
		<table border="0" class="usr_list_tbl dashleft" cellpadding="0" cellspacing="0" >
			<thead>
				<tr>
					<th align="left" class="dashheader"><?php echo __('Personal Account Information');?></th>
				</tr>
			</thead>
			<tbody>
				<tr class="odd">
					<td align="left" class="dashmatter1">
					<?php echo $this->Html->link(__('Review and update your personal account profile information'), array('controller' => 'users', 'action' => 'personalinfo', '?' => array('action' => 'personalinfo')));?>
					</td>
				</tr>
			</tbody>
		</table>
		<table border="0" class="usr_list_tbl dashright" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th align="left" class="dashheader"><?php echo __('Profile Picture');?></th>
				</tr>
			</thead>
			<tbody>
				<tr class="odd">
					<td align="left"  class="dashmatter2">
					<?php echo $this->Html->image($amazonS3ServerPath.$profileImage, array('class' => 'dashprofthumb'));
					if($profileImage == Configure::read('DEFAULT_IMAGE_NAME')){
						$photoString= __('Upload a Profile Picture');
					}else{
						$photoString= __('Update Profile Picture');
					}?>
					<?php echo $this->Html->link($photoString, array('controller' => 'users', 'action' => 'profilePic'));?>
					</td>
				</tr>
			</tbody>
		</table>
		<table border="0" class="usr_list_tbl dashleft" cellpadding="0" cellspacing="0" >
			<thead>
				<tr>
					<th align="left" class="dashheader">Manage Delegation</th>
				</tr>
			</thead>
			<tbody>
				<tr class="odd">
					<td align="left"  class="dashmatter1">
                                            <?php echo $this->Html->link(__('Delegate your review decisions to another user for a defined<br>period of time'), array('controller' => 'users', 'action' => 'manageDelegation'), array('escape' => false));?>
					</td>
				</tr>
			</tbody>
		</table>
		<table border="0" class="usr_list_tbl dashright" cellpadding="0" cellspacing="0"  >
			<thead>
			  <tr>
				<th align="left" class="dashheader"><?php echo __('Change Password');?></th>
			  </tr>
			</thead>
			<tbody>
				<tr class="odd">
					<td align="left" class="dashmatter1"><?php echo $this->Html->link(__('Change your password and set password security question'), array('controller' => 'users', 'action' => 'profilechangepassword'));?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
