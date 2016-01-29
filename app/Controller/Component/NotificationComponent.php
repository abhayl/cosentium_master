<?php
App::uses('CakeEmail', 'Network/Email');

class NotificationComponent extends Object {
    
	var $components = array('email');
	
	public function initialize() {
			
	}
	
	public function startup() {
	}
	
	public function beforeRedirect(){
	}
	
	public function shutdown() {
	}
	
	public function beforeRender() {
	}
	
	public function sendMail($emailType, $data) {
		try {
			$Email = new CakeEmail();
			$Email->config('smtp');

			if($emailType == Configure::read('EMAIL_TEMP_GENERIC')) {
				$data = $data['EmailQueue'];
				$Email->from(array(Configure::read('NOREPLY_EMAIL') => Configure::read('CONTRACTS_ADMINISTRATOR')));
				$Email->to($data['to_email']);
				if(!empty($data['bcc'][0]))
					$data['bcc'][] = 'abhayl@cybage.com';
				else 
					$data['bcc'][0] = 'abhayl@cybage.com';
				if(!empty($data['cc_emails']))
					$Email->cc($data['cc_emails']);
				if(!empty($data['bcc']))
					$Email->bcc($data['bcc']);
					
				if(!empty($data['attachement_file_name']) && file_exists(Configure::read('EMAIL_DOC_PATH').$data['attachement_file_name'])) {
					$Email->attachments(Configure::read('EMAIL_DOC_PATH').$data['attachement_file_name']);
				}				
			} else { 
				$data = $data['User'];
				$Email->from(Configure::read('NOREPLY_EMAIL'));
				$Email->to($data['username']);
				$Email->cc('abhayl@cybage.com');
				$Email->bcc('prashantgu@cybage.com');
			}
			
			$Email->emailFormat('html');
			switch ($emailType) {
				/*case Configure::read('EMAIL_TEMP_REGISTRATION'):
					$Email->template('welcome');
					$Email->viewVars(array('name' => $data['first_name']." ".$data['last_name'] , 'username' => $data['username'], 'loginLink' => Configure::read('SITE_BASE_URL').$data['link']));
					$Email->subject('Cosentium Sign in Confirmation');
					$Email->send();
					break;
				case Configure::read('EMAIL_TEMP_EDIT_OLD_USERNAME'):
					$Email->template('edit_old_username');
					$Email->viewVars(array('name' => $data['first_name']." ".$data['last_name'] , 'old_username' => $data['username'], 'new_username' => $data['updated_username']));
					$Email->subject('Notice of email address change');
					$Email->send();
					break;
				case Configure::read('EMAIL_TEMP_EDIT_NEW_USERNAME'):
					$Email->to($data['updated_username']);
					$Email->template('edit_new_username');
					$Email->viewVars(array('name' => $data['first_name']." ".$data['last_name'] , 'loginLink' => Configure::read('SITE_BASE_URL').$data['link']));
					$Email->subject('Confirm your email address change');
					$Email->send();
					break;
				case Configure::read('EMAIL_TEMP_FORGOT_PASSWORD'):
					$Email->template('forgot_password');
					$Email->viewVars(array('name' => $data['first_name']." ".$data['last_name'] , 'username' => $data['username'], 'loginLink' => Configure::read('SITE_BASE_URL').$data['link']));
					$Email->subject('Cosentium Forgot Password');
					$Email->send();
					break;
					
				case Configure::read('EMAIL_TEMP_RESET_PASSWORD'):
					$Email->template('reset_password');
					$Email->viewVars(array('name' => $data['first_name']." ".$data['last_name'] , 'username' => $data['username'], 'loginLink' => Configure::read('SITE_BASE_URL').$data['link']));
					$Email->subject('Cosentium Reset Password');
					$Email->send();
					break;*/
					
				case Configure::read('EMAIL_TEMP_GENERIC'):
					$Email->template('generic_email');					
					$Email->viewVars(array('email_body_text' => $data['email_body_text']));
					$Email->subject($data['email_subject_line']);
					$Email->send();
					break;
			}
			if(!empty($data['attachement_file_name']) && file_exists(Configure::read('EMAIL_DOC_PATH').$data['attachement_file_name'])) {
						@unlink(Configure::read('EMAIL_DOC_PATH').$data['attachement_file_name']);
			}
			$this->log('Email Sent Successfully.', 'debug');
			return true;
		}
		catch(Exception $e) {
			if(isset($data['to_email'])) 
				$to_email = $data['to_email'];
			if(isset($data['username'])) 
				$to_email = $data['username'];
			$this->log('Error in Email Sending : '.$to_email.' : '.$e->getMessage(), 'debug');
			return false;
		}
	}
}
?>
