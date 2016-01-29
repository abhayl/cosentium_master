<?php
/**
 * Role model.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       role.Model
 */
class Role extends AppModel {

    public $uses = array('Company');
    public $hasAndBelongsToMany = array(
        'Permission' =>
        array(
            'className' => 'Permission',
            'joinTable' => 'roles_permissions',
            'foreignKey' => 'role_id',
            'associationForeignKey' => 'permission_id',
            'unique' => true,
        )
    );

    public function pullPermissions() {
        $permissionSet = $this->Permission->find('all');
        return $permissionSet;
    }

    public function pullRolePermissions() {
        $rolePermissionSet = array();
        $companyId = CakeSession::read('Auth.User.UserCompany.company_id');        
        if (!empty($companyId)) {
            $rolePermissionSet = $this->query("SELECT role_id, permission_id FROM roles_permissions WHERE company_id = '".$companyId."'");
        }
        return $rolePermissionSet;
    }

    public function pullRoles() {
        $rolesSet = $this->query("SELECT id, name FROM roles");
        return $rolesSet;
    }

    Public function setPermissions($companyId, $defaultCompany, $requestType, $data) {

        if (empty($companyId) && empty($defaultCompany) && $requestType == 'update' && !empty($data)) {
			$companyId = CakeSession::read('Auth.User.UserCompany.company_id');
            $this->query("DELETE FROM roles_permissions WHERE company_id = '".$companyId."'");
            foreach ($data as $key => $val) {
                if ($val == '1') {
                    $combination = explode('_', $key);
                    $role = $combination[0];
                    $permission = $combination[1];
                    $this->query('INSERT INTO roles_permissions (`role_id`, `permission_id`, `company_id`) VALUES (' . $role . ',' . $permission . ',' . $companyId . ')');
                }
            }
            return TRUE;
        } else if (!empty($companyId) && !empty($defaultCompany) && empty($requestType) && empty($data)) {
            $defaultPermissions = $this->query("SELECT role_id, permission_id FROM roles_permissions WHERE company_id = '.$defaultCompany.'");
            foreach ($defaultPermissions as $permissions) {
                $set = $permissions['roles_permissions'];
                $this->query('INSERT INTO roles_permissions (`role_id`, `permission_id`, `company_id`) VALUES (' . $set['role_id'] . ',' . $set['permission_id'] . ',' . $companyId . ')');
            }
            return TRUE;
        } else {
            throw new NotFoundException(__('Invalid Company'));
        }
    }

}

