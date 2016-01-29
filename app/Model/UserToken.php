<?php 
// app/Model/UserToken.php
class UserToken extends AppModel {
	public $name = 'UserToken';
	
	/**
	 * @name 			generatToken 
	 * @description		Token Management 
	 * @parm	        $userId
	 * @return	        $token
	 */
	function generatToken($userId,$type) {
		if(empty($userId) && empty($type)) 
			return false;			
		$token = md5(microtime());
		$expiry =  date(Configure::read('DB_DATE_FORMAT'), strtotime(Configure::read('FREE_TRIAL_LINK_EXPIRED_IN_HOURS')));		
		$userTokenData = array('user_id' => $userId, 'token' => $token, 'expiry' => $expiry,'type' => $type);
		$this->create();
		$this->save($userTokenData); 
		return $token;
	}
	
	/**
	 * @name 			checkTokenForExpiry 
	 * @description		Token Management 
	 * @parm	        $token
	 * @return	        T/F
	 */
	function validateToken($token,$allowType) {
		if(empty($token) || empty($allowType))
			return 1;
		$userTokenData = $this->findByToken($token);
		
		if(empty($userTokenData) || empty($userTokenData['UserToken']['user_id']) || empty($userTokenData['UserToken']['type'])) 
			return 1;
		/*pr($userTokenData);
		echo date(Configure::read('DB_DATE_FORMAT'));*/
		if ($userTokenData['UserToken']['expiry'] <= date(Configure::read('DB_DATE_FORMAT'))) 
			return 2; //expired token
		if (in_array($userTokenData['UserToken']['type'], $allowType)) {
			return $userTokenData;
		} else {
			return 3;// Invalid Token Type
		}
	}
	
	/**
	 * @name 			clearToken 
	 * @description		Token Management 
	 * @parm	        $userId,$token
	 * @return	        T/F
	 */
	function clearToken($userId,$token) {
		if(empty($userId) || empty($token) ) 
			return false;		
		$userTokenData = array('user_id' => $userId, 'token' => $token);
		$userData = $this->findByUserIdAndToken($userId, $token);		
		if(empty($userData)) 
			return false;
		$this->delete($userData['UserToken']['id'], false);
		return true;		
	}
	
	
}


