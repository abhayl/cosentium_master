<section class="left_sect">
    <div class="freetrial">
		<div class="freetrial_text"><?php echo __('Welcome to Cosentium')?></div></br></br></br></br></br></br></br></br></br></br>
		<div class="placing"><?php echo __('Place holder for content')?></div>
	</div>   
</section>

<section class="right_sect">
	<?php
		if(isset($errorMessage) && $errorMessage != 'success' ) {
			$leftAlign = "";
			if(strlen($errorMessage) > Configure::read('MAX_ERROR_MESSAGE_LENGTH'))
				$leftAlign = "style='text-align:left;'";
			echo "<div class=\"loginerrormsg\" $leftAlign>$errorMessage</div>";   
		} else if(isset($errorMessage) && $errorMessage == 'success' ) {
			echo "<div class=\"loginsuccessmsg\">".__('Your password has been saved successfully. Please login to continue.')."</div>";
		} else {
			echo "<div class=\"loginsuccessmsg\" style = \"background-color:white\">&nbsp;</div>";
		}
	?>
    <div id="contact">
        <h1>Sign in</h1>
        <?php echo $this->Form->create('User'); ?>
        <fieldset id="loginsect">
            <?php 
                echo $this->Form->input('username', array('label' => __('Username'), 'tabIndex' => '1', 'required' => 'false', 'value' => ''));
				echo $this->Form->input('usernameCookie', array('type' => 'hidden', 'required' => 'false', 'value' => $user['username']));
                echo $this->Form->input('password', array('label' => __('Password'), 'tabIndex' => '2', 'required' => 'false', 'value' => ''));
                echo $this->Form->input('passwordCookie', array('type' => 'hidden', 'required' => 'false', 'value' => $user['password']));
            ?>
			<div class="logindiv">
				<label></label>
				<div class="usernameStyleLogin"><?php 
				if ( $user['rememberMe']  == 1 ) {
					echo $this->Form->input('rememberMe', array('type' => 'checkbox', 'tabIndex' => '3', 'label' => false, 'div' => false, 'checked' => $user['rememberMe']));
				} else {
					echo $this->Form->input('rememberMe', array('type' => 'checkbox', 'tabIndex' => '3', 'label' => false, 'div' => false));
				}
				echo __('Remember Me');?></div>
			</div>
			<div class="logindiv">
				<div class="loginlft"><?php echo $this->Form->input(__('Sign in'),array('div'=> false, 'type'=>'submit','label'=> false, 'style' => "margin-right: 35px;"));?></div>
				<span class="forgotcls"><?php echo $this->Html->link(__('Can\'t recall your password?'), array('controller' => 'companies', 'action' => 'forgotPassword'));?></span>
				<div class="clr"></div>
			</div>
			<div>
				<label></label>
				<div class="don"><?php echo __("Don't have an account?")." ".$this->Html->link(__('Sign up for free'), array('controller' => 'companies', 'action' => 'register'));?></div>
			</div>
            <?php 
            if (isset($token)) {
                echo $this->Form->input('tokenId',array('value' => $token, 'id' => 'tokenId', 'required' => 'false', 'type' => 'hidden', 'div' => false));
            }
            ?>
			<?php echo $this->Form->end(); ?>
		</fieldset>
	</div>
</section>
<div class="clr"></div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#UserUsername").focus();
		$('#UserUsername').bind("keyup focusout click", function () {
			if($('#UserUsername').val() == $('#UserUsernameCookie').val())
			{
				$('#UserPassword').val($('#UserPasswordCookie').val());
			}
		});
	});
</script>
