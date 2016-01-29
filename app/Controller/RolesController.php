<?php
/**
 * Role Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 * 
 */
class RolesController extends AppController {

    var $name = 'Roles';

    //Common function which displays the view/edit form
    public function _display($action) {
        $rolePermissions = $this->Role->pullRolePermissions();
        $permissions = $this->Role->Permission->find('list', array('fields' => array('id')));
        $permissionSet = $this->Role->pullPermissions();
        $rolesSet = $this->Role->pullRoles();
        $this->set('permissionSet', $permissionSet);
        $this->set('rolesSet', $rolesSet);
        $this->set('permissions', $rolePermissions);
        if ($action == 'update') {
            $this->render('index');
        }
    }

    // Processes View
    public function index() {
        $roleId = CakeSession::read('Auth.User.Role.id');
        if ($roleId == 1) {
            $this->_display($this->action);
        } else {
            $this->redirect('/');
        }
    }

    // Processes Update Operation
    Public function update() {
        if (CakeSession::read('Auth.User.Role.id') == 1) {
            $this->set('confirm_message', __('Modify the role default Permission sets?'));
            $this->_display($this->action);
            if (($this->request->data)) {
                $updateData = $this->Role->setPermissions('', '', $this->action, $this->request->data);
                if ($updateData == 1) {
                    $this->redirect('index');
                }
            }
        } else {
            $this->redirect('/');
        }
    }

}