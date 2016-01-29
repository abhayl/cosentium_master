<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $uses = array('User','UserToken');
	
    public $components = array(
                    'Session',
					'Cookie',
                    'Auth' => array(
                        'authenticate' => array(
                            'Form' => array( 
                            'fields' => array(
                                    'username' => 'username',
                                    'password' => 'password'
                                ),
                            'scope' => array('User.access_status' => 1,'UserCompany.status' => 1,'Company.status' => 1)
                            )       
                        ),
                    'loginRedirect' => array('controller' => 'users', 'action' => 'dashboard'),
                    'logoutRedirect' => array('controller' => 'users', 'action' => 'index')
                    )
    );
    
    public function beforeFilter() {
        $this->Auth->autoRedirect = false;
        $this->Auth->allow('users/index', 'login','inactive_user','inactive_company','register','changeEmail','changepassword','forgotPassword','securityAnswer','message','agreement', 'reassignRole','sendEmail');
		$this->Cookie->httpOnly = true;
		// Check for the cookie
		if (!$this->Auth->loggedIn() && $this->Cookie->read('rememberMe')) {
			 $cookie = $this->Cookie->read('rememberMe');

			 $this->loadModel('User'); // If the User model is not loaded already
			 $user = $this->User->find('first', array(
					'conditions' => array(
						'User.username' => $cookie['username'],
						'User.password' => $cookie['password']
					)
			 ));
			
			 if ($user && !$this->Auth->login($user['User'])) {
					$this->redirect('/users/logout'); // destroy session & cookie
			}
		}
	 
        if($this->isLoggedIn() == TRUE){
            // URL tampering in case logged in user removes the controller and action
            if($this->request->url == '' ||  $this->request->url == '/') {
                    $this->redirect($this->Auth->redirect());
            }
            $this->setProfileImage();
            
            $this->layout = 'home';
        } else {
            $this->layout = 'default';
        }
    }
	
    function isLoggedIn(){
        if($this->Auth->user()){
           $loggedin= TRUE;
        } else {
            $loggedin= FALSE;
        }
        return $loggedin;
    }
	
		
	
        
        public function setProfileImage(){
            $userProfPicArr = $this->User->find('first',array('fields' => array('User.image'),'conditions' => array('User.id' => $this->Session->read('user.User.id'))));
            if($userProfPicArr['User']['image'] != NULL){
                $this->set('amazonS3ServerPath', Configure::read('AWS_PROFILE_IMG_IMAGES_URL'));
                $userProfPic = $userProfPicArr['User']['image'];
            }else{
                $this->set('amazonS3ServerPath', '');
                $userProfPic = Configure::read('DEFAULT_IMAGE_NAME');
            }
            $this->set('userProfPic', $userProfPic);
            return;
        }
	
}
