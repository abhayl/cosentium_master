<?php echo $this->Form->create('UserToken',array('url' => array('controller' => 'users', 'action' => 'login'))); ?>
<?php echo $this->Form->input('tokenId',array('value' => $token, 'id' => 'tokenId', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
<?php echo $this->Form->end();?>

<script type="text/javascript">
    $(document).ready(function() {
         $('#UserTokenReassignRoleForm').submit();
     })
</script>