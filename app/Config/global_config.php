<?php 
	$config['SITE_VERSION'] = '1.4v';
	$config['COOKIE_LIFETIME'] = '3600';

	$config['SITE_BASE_URL'] = 'http://'.$_SERVER['SERVER_NAME'].'/sprint-4.1/';

	//$config['SITE_BASE_URL'] = 'http://'.$_SERVER['SERVER_NAME'].'/';


	$config['SUPPORT_EMAIL'] = 'support@cosentium.com';
	$config['SUPPORT_SUBJECT'] = 'Possible broken link';
	
	$config['NOREPLY_EMAIL'] = 'no-reply@cosentium.com';
	
	$config['FREE_TRIAL_PERIOD_IN_DAYS'] = '30 days';
	$config['FREE_TRIAL_LINK_EXPIRED_IN_HOURS'] = '+48 hours';
	$config['PASSWORD_EXPIRY'] = '-180 days';
	
	
	$config['DEFAULT_ROLE_ID'] = 1;
	
	$config['DB_DATE_FORMAT'] = "Y-m-d H:i:s";
        $config['DATE_FORMAT'] = "m/d/Y";
        $config['DB_DATEIMG_FORMAT'] = "YmdHis";
         $config['DB_DATE_DOC_FORMAT'] = "m/d/Y,  h:i a" ;
	
	$config['DEFAULT_COUNTER'] = 0;	
	$config['INITIAL_COUNTER'] = 1;
	$config['LAST_COUNTER'] = 4;
	$config['MAX_ANSWER_ATTTMPT'] = 5;
    $config['USER_LOCK_PERIOD_IN_MIN'] = '-30 Minute';
        
	$config['NEW_USER_STATUS'] = 0;
	$config['ACTIVE_USER_STATUS'] = 1;
	$config['LOCK_USER_LOGIN_STATUS'] = 2;
	$config['LOCK_USER_FORGOT_STATUS'] = 3;
	$config['FORGOT_PASS_STATUS'] = 4;
	
	$config['INACTIVE_COMPANY_STATUS'] = 0;
	$config['INACTIVE_USER_STATUS'] = 0;
	$config['MAX_TEXTBOX_LENGTH'] = 20;
	$config['PAGINATION_LIMIT'] = 50;
	
	$config['USER_PROFILE_IMAGE_PATH'] = WWW_ROOT.'img';
    $config['MASTER_DOC_PATH'] = WWW_ROOT.'files/masterdoc/';
    $config['REDLINE_DOC_PATH'] = WWW_ROOT.'files/redline/';
	$config['EMAIL_DOC_PATH'] = WWW_ROOT.'files/emails/';
    $config['DOWNLOAD_DOC_PATH'] = WWW_ROOT.'files/download/';
    $config['GET_DOWNLOAD_DOC_PATH'] =WWW_ROOT.'files\\download\\';
        
	
	$config['EMAIL_TEMP_REGISTRATION'] = 'adduser';
    $config['EMAIL_TEMP_EDIT_OLD_USERNAME'] = 'edit_old_username';
    $config['EMAIL_TEMP_EDIT_NEW_USERNAME'] = 'edit_new_username';
	$config['EMAIL_TEMP_FORGOT_PASSWORD'] = 'forgot_password';
	$config['EMAIL_TEMP_RESET_PASSWORD'] = 'reset_password';
	$config['EMAIL_TEMP_GENERIC'] = 'generic_email';
	$config['CONTRACTS_ADMINISTRATOR'] = 'Contracts Administrator';
	$config['EMAI_QUEUE_SENT_STATUS'] = 1;
	$config['EMAI_QUEUE_FAILED_STATUS'] = 2;
	$config['EMAI_QUEUE_QUERY_LIMIT'] = 100;
	
	$config['EMAI_QUEUE_FAILED_STATUS'] = 2;
	$config['EMAI_QUEUE_QUERY_LIMIT'] = 100;
	
	//Notification Subject Lines
	$config['EMAIL_REGISTRATION_SUBJECT'] = 'Cosentium Sign in Confirmation';
	$config['EMAIL_FORGOT_PASSWORD_SUBJECT'] = 'Cosentium Forgot Password';
	$config['EMAIL_RESET_PASSWORD_SUBJECT'] = 'Cosentium Reset Password';
	$config['EMAIL_EDIT_OLD_USERNAME_SUBJECT'] = 'Notice of email address change';
	$config['EMAIL_EDIT_NEW_USERNAME_SUBJECT'] = 'Confirm your email address change';
	
	$config['DEFAULT_SORT_ARROW'] = 'down_arr.png';
	$config['DEFAULT_SORT_ARROW_TOGGLE'] = 'up_arr.png';
	$config['DEFAULT_IMAGE_NAME'] = 'default_profile.png';
	$config['INACTIVE_USER_IMAGE'] = 'inactive.png';
	$config['ACTIVE_USER_IMAGE'] = 'active.png';
	
	$config['PAGINATION_DROPDOWN'] = array('50' => 'View 50 records per page', '25' => 'View 25 records per page', '10' => 'View 10 records per page');
	$config['VIEW_DEAL_DROPDOWN'] = array('0' => 'All Deals', '1' => 'Unexpired Deals', '2' => 'Expired Deals');
    $config['MASTER_DOC_FILTER_DROPDOWN'] = array('0' => 'My Master Documents', '1' => 'All Master Documents');
	
    $config['REM_DEL_CONFIRMATION_MSG'] = 'Do you want to remove assigned delegation?';
    $config['PIC_CONFIRMATION_MSG'] = 'Are you sure you want to delete your photo?';
    $config['DESCRIBE_MASTERDOC_CONFIRMATION_MSG'] = 'Master Document will not be saved.  Click "OK" to abandon the doc, or "Cancel" to continue';
        
	$config['TOKEN_REGISTRATION_OR_ADDUSER'] = 'TOKEN_REGISTRATION_OR_ADDUSER';
	$config['TOKEN_EDIT_USER'] = 'TOKEN_EDIT_USER';
	$config['TOKEN_FORGOT_PASSWORD'] = 'TOKEN_FORGOT_PASSWORD';
	$config['TOKEN_RESET_PASSWORD'] = 'TOKEN_RESET_PASSWORD';
	$config['TOKEN_EXPIRED_PASSWORD'] = 'TOKEN_EXPIRED_PASSWORD';
     
     $config['TOKEN_CHANGE_DECISIONMAKER'] = 'TOKEN_CHANGE_DECISIONMAKER';
     $config['TOKEN_CHANGE_OPINIONPROVIDER'] = 'TOKEN_CHANGE_OPINIONPROVIDER';
     $config['TOKEN_CHANGE_OWNER'] = 'TOKEN_CHANGE_OWNER';
     
	$config['DEFAULT_COMPANY'] = 184;
	$config['ROLES_PERMISSIONS_VIEW_MSG'] = 'View Role Permissions';
	$config['ROLES_PERMISSIONS_EDIT_MSG'] = 'Edit Role Permissions';
        
	$config['REVIEW_DEALS_PERMISSIONS'] = 9;
     $config['CREATE_MAINTAIN_MASTER_DOCUMENTS_PERMISSION'] = 6;

	$config['MAX_ERROR_MESSAGE_LENGTH'] = 135;

	$config['DEFAULT_MASTERDOC_UNTIL_DT'] = '5 Years';
     
    $config['DEFAULT_FILTER'] = 0;
    $config['CHANGED_FILTER'] = 1;
	$config['ADD_OPINION_PROVIDER'] = 1;
     
	$config['SECTION_ID_ENTIRE_DOC'] = "ENTIRE_DOC";
     
	$config['DECISION_MAKER'] = "DECISION_MAKER";
	$config['OPINION_PROVIDER'] = "OPINION_PROVIDER";
	
	/**** EXCEPTION Unique Keys  *****/
	$config['USER_VALIDATION_FAIL'] = 1;
	$config['USER_REGISTER_SUCCESS'] = 2;
	$config['USER_FORGOT_PASSWORD_SUCCESS'] = 3;
	$config['USER_SECURITY_ANSWER_INVALID'] = 4; //invalid_security_answer	
	$config['USER_SECURITY_ANSWER_LOCK'] = 5; //lock_user_forgot 
	$config['USER_SECURITY_ANSWER_SUCCESS'] = 6; //correct_security_answer
	$config['USER_CHANGE_PASSWORD_SUCCESS'] = 7; //Reset Password
	$config['USER_PROFILE_CHANGE_PASSWORD_SUCCESS'] = 8; //Profile Change Password
	$config['USER_RESET_PASSWORD_SUCCESS'] = 9; //System Reset Password
    $config['ADD_DEAL_SUCCESS'] = 100;
    $config['ADD_DEAL_FAIL'] = 101;
    $config['RECEIVE_DEAL_SUCCESS'] = 201;
    $config['RECEIVE_DEAL_FAIL'] = 202;
	
    //add a master document
    $config['MASTER_DOC_FAIL'] = 10;

    //add users
    $config['FAIL_TO_ADD_IN_USERS_FOR_ADD'] = 150;
    $config['FAIL_TO_ADD_IN_USERCOMPANY_FOR_ADD'] = 151;
    $config['SUCCESS_TO_ADD_NEW_USER'] = 152;
    
    //edit user
    $config['FAIL_TO_ADD_IN_USERS_FOR_EDIT'] = 153;
    $config['FAIL_TO_ADD_IN_USERCOMPANY_FOR_EDIT'] = 154;
    $config['SUCCESS_TO_EDIT_USER'] = 155;
    
    //upload profile picture
    $config['FAIL_TO_ADD_IN_USERS_FOR_PROFILEPIC'] = 156;
    $config['SUCCESS_TO_ADD_PROFILEPIC'] = 157;
    
    //delete profile picture
    $config['FAIL_TO_ADD_IN_USERS_FOR_DELETE_PROFILEPIC'] = 158;
    $config['SUCCESS_TO_ADD_DELETE_PROFILEPIC'] = 159;
    
    //change Email
    $config['FAIL_TO_UPDATE_EMAIL'] = 160;
    
    //remove Delegation
    $config['FAIL_TO_REMOVE_DELEGATION'] = 161;
    $config['SUCCESS_TO_REMOVE_DELEGATION'] = 162;
    
    //manage delegation
    $config['FAIL_TO_MANAGE_DELEGATION'] = 163;
    $config['SUCCESS_TO_MANAGE_DELEGATION'] = 164;
	/**** EXCEPTION Unique Keys *****/


	$config['DEFAULT_MASTERDOC_UNTIL_DT'] = '5 Years';

	$config['DEFAULT_FILTER'] = 0;
	$config['CHANGED_FILTER'] = 1;
	$config['ADD_OPINION_PROVIDER'] = 1;

	$config['SECTION_ID_ENTIRE_DOC'] = "ENTIRE_DOC";

	$config['DECISION_MAKER'] = "DECISION_MAKER";
	$config['OPINION_PROVIDER'] = "OPINION_PROVIDER";

	$config['DOC_ISSUED_STAGE'] = "1<sup>st</sup> Document Issued";
	$config['REDLINE_RECEIVED_STAGE'] = " Redline Received";
	$config['REPRESENTING_OF_DEFAULT'] = "The Company";
	$config['ELAPSED_TIME_FORMAT'] = array('day' => 86400, 'hour' => 3600);
     
     $config['SECTION_FOR_ENTIRE_DOCUMENT'] = 'ENTIRE DOCUMENT';
     
     //login status
    $config['INVALID_USER'] = 50;
    $config['INACTIVE_COMPANY'] = 51;
    $config['EXPIRED_COMPANY'] = 52;
    $config['INACTIVE_USER'] = 53;
    $config['EXPIRED_PASSWORD'] = 54;
    $config['EMAIL_UPDATE'] = 55;
    $config['WELCOME'] = 56;
    $config['DASHBOARD'] = 57;
    $config['USER_PASS_FAILED'] = 58;
    
    //edit user
    $config['SUCCESS_PERSONALINFO'] = 60;
    
    $config['SET_PARAM'] = 0;
    $config['UNSET_PARAM'] = 1;
    
    //change Email
    $config['INVALID_TOKEN'] = 61;
    $config['EXPIRED_LINK'] = 62;
    
    $config['NOT_SET_SESSION'] = 0;
    $config['ISSET_SESSION'] = 1;
    
    //manage delegation
    $config['NULL_FROM_DATE_DELEGATION'] = 63;
    $config['NULL_TO_DATE_DELEGATION'] = 64;
    $config['EARLIER_FROM_DATE_DELEGATION'] = 65;
    $config['EARLIER_TO_DATE_DELEGATION'] = 66;
    $config['EARLIER_TO_THAN FROM_DATE_DELEGATION'] = 67;	
	$config['AWS_IMAGES_FOLDER'] = "img/";
	$config['AWS_DOC_FOLDER'] = "doc/";
	$config['AWS_PROFILE_IMG_IMAGES_URL'] = "https://s3-us-west-2.amazonaws.com/cosentium/img/";
	$config['AWS_PROFILE_IMG_DOC_URL'] = "https://s3-us-west-2.amazonaws.com/cosentium/doc/";
    $config['AWS_MASTER_DOC_PATH'] = '/masterdoc/';

    
    $config['REM_OPINION_CONFIRMATION_MSG'] = "Do you want to delete opinion provider";
    
    $config['FILENAME_STRING_LENGTH'] = 30;
    $config['MAX_FILENAME_LENGTH'] = 50;
    $config['REVIEW_FIRST_DAY'] = 2;
    $config['REVIEW_SECOND_DAY'] = 2;
    $config['REVIEW_THIRD_DAY'] = 2;
    $config['REVIEW_FOURTH_DAY'] = 2;
    
    $config['CONFIRMATION_TEXT_CHANGE_STATUS_NULL'] = 'This user is not defined as a decision maker or opinion provider in any master documents, is not the owner of any deals or master documents, and has no open work items.  Deactivate this user now?';
    $config['CONFIRMATION_TEXT_CHANGE_STATUS_NOT_NULL'] = 'The user is a decision maker and or opinion provider from one or more master documents, and/or has open work items.  Before deactivating, remove the users decision making and opinion providing responsibilities, and reassign the users work items.  Click "Report" to view and resolve these items.';
    
    $config['LOGGEDIN_REASSIGN'] = 165;
    $config['NOT_LOGGEDIN_REASSIGN'] = 166;
    $config['LOGGEDIN_REASSIGN_OWNER'] = 167;
    
    $config['DROPDOWN_TYPE_REASSIGN'] = 'reassignTo';
    $config['DROPDOWN_TYPE_MANAGEDELEGATION'] = 'manageDelegation';
    
    $config['MASTER_DOC_REASSIGN'] = 'MASTER_DOC_REASSIGN';
    $config['OWNER'] = 'OWNER';
    
?>
