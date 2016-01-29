<?php
class UsersController extends AppController {
    
    public $helpers = array('Html', 'Form');
    public $uses = array('User','Role','UserCompany','Company');
    var $components = array('Notification');
    
	/** Custom Variables **/
	public $DEFAULT_ROLE = 1;
	public $DB_DATE_FORMAT = 'Y-m-d H:i:s';
	public $NEW_USER_STATUS = 0;
	public $ACTIVE_USER_STATUS = 1;
	public $INACTIVE_USER_STATUS = 2;
	public $LOCK_USER_LOGIN_STATUS = 3;
	public $LOCK_USER_FORGOT_STATUS = 4;
	public $MAX_ANSWER_ATTTMPT = 5;
	
    
        var $paginate = array(
        'limit' => 5,
        'order' => array(
            'User.id' => 'asc'
        )
    );
    
    /**
    * 
    *purpose : Redirect to login page from index 
    */
    public function index() {
        $this->redirect(array('action' => 'login'));
    }
    
    /**
    * 
    *purpose : Authenticate user and validation for login functionality 
    */
    public function login() {
        if ($this->request->is('post')) {
            if ($this->User->validates()) {
                $userData = $this->User->findByUsername($this->data['User']['username']);
                $str_time = date(Configure::read('DB_DATE_FORMAT'), strtotime(Configure::read('USER_LOCK_PERIOD_IN_MIN')));
                if ($userData['User']['last_unsuccess_access_dt'] <= $str_time && $userData['User']['access_status'] == Configure::read('LOCK_USER_LOGIN_STATUS')) {
                        $this->User->id = $userData['User']['id'];
                        $this->User->set(array(
                            'last_unsuccess_access_dt' => NULL,
                            'unsuccess_access_counter' => Configure::read('DEFAULT_COUNTER'),
                            'access_status' => Configure::read('ACTIVE_USER_STATUS')
                        ));
                        $this->User->save();
                        $userData['User']['last_unsuccess_access_dt'] = NULL;
                        $userData['User']['unsuccess_access_counter'] = Configure::read('DEFAULT_COUNTER');
                        $userData['User']['access_status'] = Configure::read('ACTIVE_USER_STATUS');
                }
                if ($this->Auth->login()) {
                    $roles = $this->Role->find('all');
                        $roleDd = array();
                        foreach ($roles as $role) {
                        if ($role['Role']['id'] != Configure::read('DEFAULT_ROLE_ID')){
                            $roleDd[].= $role['Role']['name'];
                        }
                    }
                    $this->Session->write('roles', $roleDd);
                    $this->Session->write('user', $userData); 
                    $this->User->id=$userData['User']['id'];
                    $this->User->set(array(
                        'last_unsuccess_access_dt' => '',
                        'unsuccess_access_counter' => Configure::read('DEFAULT_COUNTER')
                    ));
                    $this->User->save();
                    if ($userData['User']['last_access_dt'] == '') {
                        $this->User->saveField('last_access_dt', date(Configure::read('DB_DATE_FORMAT')));
                        $this->redirect(array('action' => 'welcome'));
                    } else {
                        $this->User->saveField('last_access_dt', date(Configure::read('DB_DATE_FORMAT')));
                        $this->redirect(array('action' => 'dashboard'));
                    }
                } else {
                    if (empty($userData)) {
                        $this->Session->setFlash(__('Username and password combination does not match our records'));
                        $this->redirect(array('action' => 'login'));
                    } elseif ($userData['Company']['status'] == 0) {
                        $this->Session->setFlash(__('Your free trial has expired. Click "Continue" to subscribe now and continue using your Cosentium account. After 30 days, we will delete your account data if you have not subscribed. '));
                        $this->redirect(array('action' => 'inactive_company'));
                    } elseif($userData['UserCompany']['status'] == 0) {
                        $this->Session->setFlash(__('Your account is currently inactive. Contact your system administrator and ask to have your account reactivated. '));
                        $this->redirect(array('action' => 'inactive_user'));
                    }
                    $this->User->id=$userData['User']['id'];
                    switch ($userData['User']['access_status']) {
                            case Configure::read('NEW_USER_STATUS') :
                                    $this->Session->setFlash(Configure::read('login.invalid_user'));
                                    break ;
                            case Configure::read('INACTIVE_USER_STATUS') :
                                    $this->redirect(array('action' => 'inactive_user'));
                                    break ;
                            case Configure::read('LOCK_USER_LOGIN_STATUS') :
                                    $this->Session->setFlash(__('You have attempted to login more than 5 times.  Your account has been locked for 30 minutes.  You can contact your administrator to reset your account or wait until the lock expires and try logging in again.'));
                                    break ;
                            case Configure::read('LOCK_USER_FORGOT_STATUS') :
                                    $this->Session->setFlash(__('You have attempted to answer more than 5 times.  Your account has been locked for 30 minutes.  You can contact your administrator to reset your account or wait until the lock expires and try logging in again.'));
                                    break ;
                            default:
                                    if ($userData['User']['unsuccess_access_counter']==0) {
                                        /*$this->User->saveField('last_unsuccess_access_dt', date('Y-m-d G:i:s'));
                                        $this->User->saveField('unsuccess_access_counter', 1);*/
                                        $this->User->set(array(
                                            'last_unsuccess_access_dt' => date(Configure::read('DB_DATE_FORMAT')),
                                            'unsuccess_access_counter' => 1
                                        ));
                                        $this->User->save();
                                    } elseif($userData['User']['unsuccess_access_counter'] > 0 && $userData['User']['unsuccess_access_counter'] < 5) {
                                    $str_time = date('Y-m-d H:i:s', strtotime('-30 Minute'));
                                            if ($userData['User']['last_unsuccess_access_dt'] >= $str_time) {
                                                    if ($userData['User']['unsuccess_access_counter'] == 4) {
                                                            $this->User->saveField('access_status', $this->LOCK_USER_LOGIN_STATUS);
                                                    }
                                                    $this->User->saveField('unsuccess_access_counter',$userData['User']['unsuccess_access_counter']+1);
                                            } else {
                                                    /*$this->User->saveField('last_unsuccess_access_dt', date('Y-m-d H:i:s'));
                                                    $this->User->saveField('unsuccess_access_counter',1);*/
                                                    $this->User->set(array(
                                                        'last_unsuccess_access_dt' => date('Y-m-d G:i:s'),
                                                        'unsuccess_access_counter' => 1
                                                    ));
                                                    $this->User->save(); 
                                            }
                                    }
                            $this->Session->setFlash(Configure::read('login.invalid_user'));
                    }
                }
            }
        }
    }
    
    /**
    * 
    *purpose : If company free trial is expired 
    */
    public function inactive_company() {
        $this->Session->setFlash(__('Your free trial period has expired. Click "Continue" to subscribe now and continue using your Cosentium account. After 30 days, we will delete your account data if you have not subscribed'));
    }

    /**
    * 
    *purpose : If is deactive 
    */
    public function inactive_user() {
        $this->Session->setFlash(__('Your user account is currently inactive.  Contact your system administrator and ask to have your account reactivated'));
    }

    public function welcome(){
    }
    
    /**
    * 
    *purpose : Redirection to homepage 
    */ 
    public function dashboard() {
        $userLoggedIn = $this->Session->read('user');
        $this->set('users',$userLoggedIn);
        if ($userLoggedIn['UserCompany']['role_id'] == 1) {
            $this->render('super_dashboard');
        } else {
            $this->render('dashboard');
        }
    }

    /**
    * 
    *purpose : To destroy user session 
    */
    public function logout() {
        if ($this->Auth->user()) {
			$this->Session->destroy();
            $this->redirect($this->Auth->logout());
        } else {
            $this->redirect(array('controller'=>'users','action' => 'index','home'));
            $this->Session->setFlash(__('Not logged in'), 'default', array(), 'auth');
        }
    }
    
    /**
    * 
    *purpose : To fetch users to display on user listing page 
    */
    public function view() {
        $this->Session->write('personalInfo', 0);
        $user_session_arr=$this->Session->read('user');
        $this->set('users',$this->paginate('User',array('Company.id' => $user_session_arr['UserCompany']['company_id'], 'User.username !=' => $user_session_arr['User']['username'], 'UserCompany.role_id !=' => 1)));
    }
    
    /**
    * 
    *purpose : To add the user data from add users page 
    */
    public function add() {
        $this->set('roles',$this->Session->read('roles'));
        
        if ($this->request->is('post')) {
           if ($this->User->validates()) { 
                $this->User->create();
                $data=$this->request->data;
                if(array_key_exists('cancel', $data)) {
                    $this->redirect(array('action' => 'view'));
                } else {
                    $token = md5(uniqid($this->request->data['User']['username'], true));
                    $data['User']['token'] = $token;
                    if ($this->User->save($data['User'])) {
                        $data['UserCompany']['user_id'] = $this->User->getLastInsertID();
                        $data['UserCompany']['company_id'] = $this->Session->read('user.UserCompany.company_id');
                        $data['UserCompany']['role_id'] = $data['User']['role_id']+2;
                        if($this->UserCompany->save($data['UserCompany'])){
							$data['User']['link'] = 'companies/changepassword/'.$token;
                            $this->Notification->sendMail('registration', $data);
                        }
                        unset($this->request->data);
                        //$this->Session->setFlash('Your post has been saved.');
                        if (array_key_exists('new', $data)) {
                            $this->redirect(array('action' => 'add'));
                        } elseif (array_key_exists('ok', $data)) {
                            $this->redirect(array('action' => 'view'));
                        }
                    } else {
                        $this->Session->setFlash('Unable to add your user.');
                    }
                }
           }
        }
    }
    
    /**
    * 
    *purpose : To edit the user
    *@param : user.id 
    */
    function edit($id) {
        $this->set('roles', $this->Session->read('roles'));
        if (!$id) {
            throw new NotFoundException(__('Invalid user'));
        }
        $userData = $this->User->findById($id);
        $this->set('user', $userData);
        if (!$userData) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->User->id = $id;
        $this->UserCompany->id = $userData['UserCompany']['id'];
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data['User'])) {
                if($this->Session->read('personalInfo') == 0){
                    $this->request->data['UserCompany']['role_id'] = $this->request->data['UserCompany']['role_id'] + 2;
                }
                $this->UserCompany->save($this->request->data['UserCompany']);
                //$this->Session->setFlash('Your post has been updated.');
				if($this->Session->read('personalInfo') == 1){
					$this->Session->delete('personalInfo');
					$this->redirect(array('action' => 'personalinfo',$id));
				}
                $this->redirect(array('action' => 'view'));
            } else {
                $this->Session->setFlash('Unable to update your post.');
            }
        }
        if (!$this->request->data) {
            $userData['UserCompany']['role_id'] = $userData['UserCompany']['role_id']-2;
            $this->request->data = $userData;
        }
    }
	
    /**
    * 
    *purpose : To view and edit personal info
    *@param : user.id 
    */
    public function personalinfo($id){
        $this->Session->write('personalInfo', 1);
        $userData = $this->User->findById($id);
        $this->set('user',$userData);
        if($this->request->is('post')){
                $this->Session->delete('personalInfo');
        }
    }
    
    /**
    * 
    *purpose : To view and edit profile picture
    *@param : user.id 
    */
    public function profilePic(){
        if ($this->request->is('post')) {
            pr($this->request->data['User']['image']);
            pr($this->Session->read('user.User.id'));exit;
            if(!empty($this->request->data['User']['image']))
            {
                $file = $this->request->data['User']['image'];
                move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/uploads/users/' . $file['name']);
                $this->request->data['User']['image'] = $file['name'];
            }
        }
    }
}
?>
