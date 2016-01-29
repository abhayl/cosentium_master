<!-- app/View/companies/message.ctp -->
<?php 
	switch($message) {
		case 'success' : 
						$message = "<div id=\"contactinner\">
							<h1>".__('Your free trial is ready to use')."</h1>
							<div class=\"contpdleft\">
							".__('We just sent you an email with instructions for getting started with Cosentium.')."<br><br>
							".__('Enjoy your free trial!')."<br><br>
							<div>
							<div id=\"steps\">
								<div class=\"step\"><span>1</span>".__('Check your email')."</div>
								<div class=\"step\"><span>2</span>".__('Login to Cosentium')."</div>
								<div class=\"step\"><span>3</span>".__('Start using Cosentium')."</div>
							</div>						
							<div class=\"needhelp\">
								".__('Need Help?')."<br>
								".__('Call us at:')." <strong>".__('800 XXX XXXX')."</strong>
							</div>
							<div class=\"clr\"></div>
							</div>
							<br><br>
								</div>
							</div>";
						break;
	case 'expired_link': //Expired link
						$message = "<div id=\"contactinner\">
							<h1>".__('Expired link.')."</h1>
							".__('Your link has expired. You need to generate a new link. Links expire 48 hours from the time they are sent.')."<br><br><br><br>			
							<center><a href=\"".Configure::read('SITE_BASE_URL')."users/login\"><input type=\"submit\" value=\"Continue\" class=\"signbtn\"></a></ceneter> 
							</div>";	
						break;
					
	case 'lock_user_forgot' : // Locked user by wrong answer given in forgot password link
					
						$message = "<div id=\"contactinner\">
							<h1>".__('Account Locked')."</h1>
							".__('You have attempted to answer the security question more than 5 times.  Your account has been locked for 30 minutes.  You can contact your administrator to reset your account or wait until the lock expires and try logging in again.')."<br><br><br><br>			
							<center><a href=\"".Configure::read('SITE_BASE_URL')."users/login\"><input type=\"submit\" value=\"Continue\" class=\"signbtn\"></a></ceneter> 
							</div>";	
						break;
						
	case 'fail' : // Common Fail Operation
					$message = "<div id=\"contactinner\">
							<h1>".__('System Failiure')."</h1>
							".__('We are sorry, the page you are looking for could not be found.  Either we moved the content or the address was mistyped.  Please try your request again.  If you arrived at this page by clicking on a link on our site, or a link in an email we sent you, please')." <a href=\"mailto:".Configure::read('SUPPORT_EMAIL')."?subject=".Configure::read('SUPPORT_SUBJECT')."\">".__('let us know')."</a> ".__('so that we can fix it.')."<br><br><br><br>			
							<center><a href=\"".Configure::read('SITE_BASE_URL')."users/login\"><input type=\"submit\" value=\"Continue\" class=\"signbtn\"></a></ceneter> 
							</div>";	
						break;
	
	case 'page_not_found': //Page Not Found
	default:
						$message = "<div id=\"contactinner\">
							<h1>".__('Page Not Found')."</h1>
							".__('We are sorry, the page you are looking for could not be found.  Either we moved the content or the address was mistyped.  Please try your request again.  If you arrived at this page by clicking on a link on our site, or a link in an email we sent you, please')." <a href=\"mailto:".Configure::read('SUPPORT_EMAIL')."?subject=".Configure::read('SUPPORT_SUBJECT')."\">".__('let us know')."</a> ".__('so that we can fix it.')."<br><br><br><br></div>";	
						break;		
	} 
?>
<?php echo $message ?>
 