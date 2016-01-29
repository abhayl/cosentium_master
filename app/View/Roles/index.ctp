<?php
foreach ($permissionSet as $set) {
    foreach ($set as $dbPermissions) {
        $finalPermissionSet[] = array($dbPermissions['id'], $dbPermissions['permission']);
    }
}

foreach ($rolesSet as $role) {
    foreach ($role['roles'] as $role) {
        if (!is_numeric($role)) {
            $rolesData[] = $role;
        }
    }
}

foreach ($permissions as $existingPermissions) {
    $finalSet[] = $existingPermissions['roles_permissions']['role_id'] . '_' . $existingPermissions['roles_permissions']['permission_id'];
}
if ($this->action != 'update') {    
    echo $this->Html->script('jquery-migrate-1.2.0.min.js');
    echo $this->Html->script('__jquery.tablesorter.min.js');
    ?>
    <script type="text/javascript">
        $(document).ready(function(){     		
        $("#roles_list_tbl").tablesorter({
            widgets: ['zebra'],
            sortList: [[0,0]]
}); 
}); 
    </script>
    <?php
    $strCursorPointor = '';
} else {
    $strCursorPointor = 'style="cursor:text"';
}
$strCursorPointorTxt = 'style="cursor:text"';
echo $this->element('usersetup_left_links');
?>
<?php echo $this->element('usersetup_left_links'); ?>
<div class="right">
    <div class="heading">
        <?php
        if ($this->action == 'update') {
            echo Configure::read('ROLES_PERMISSIONS_EDIT_MSG');
        } else {
            echo Configure::read('ROLES_PERMISSIONS_VIEW_MSG');
        }
        ?>
    </div>
    <div style="padding:1.49105% 8px;" width="97.0179%"> 
        <table width="100%" border="0" class="usr_list_tbl"  id="roles_list_tbl" <?php echo $strCursorPointor; ?> cellpadding="0" cellspacing="0">

            <?php
            echo $this->Form->create('Role', array('action' => 'update'));
            $rows = 0;
            $columns = 1;
            $permissionCount = count($finalPermissionSet);
            $rolesCount = count($rolesData);
            do {
                if ($rows == 0) {
                    ?>
                    <thead>
                        <tr> 
                            <?php
                            for ($columns = 1; $columns <= $rolesCount; $columns++) {
                                if ($columns == 1) {

                                    echo '<th align="left" class="roles_permission_tblcol1"' . $strCursorPointor . '><span>Permissions</span></th>';
                                    foreach ($rolesData as $role) {
                                        echo '<th align="center" ' . $strCursorPointor . ' style="width:10%;"><span>' . $role . '</span></th>';
                                    }
                                }
                            }
                            ?>
                        </tr>
                    </thead><tbody>	
                    <?php }
                    if ($rows == 1) {
                        ?>

                    <?php
                    }
                    if ($rows % 2) {
                        $class = 'class = "odd"';
                    } else {
                        $class = '';
                    }
                    ?>
                    <tr <?php echo $class; ?>>
                        <?php
                        for ($columns = 1; $columns <= $rolesCount; $columns++) {
                            if ($columns == 1) {
                                echo '<td align="left" class="roles_permission_tblcol1" ' . $strCursorPointorTxt . '>' . $finalPermissionSet[$rows][1] . '</td>';
                            }


                            $id = $columns . '_' . $finalPermissionSet[$rows][0];
                            if (!empty($finalSet)) {
                                if (in_array($id, $finalSet)) {                                    
                                    $value = 1;
                                } else {
                                    
                                    $value = 0;
                                }
                            } else {                                
                                $value = 0;
                            }

                            if ($this->action == 'update') {
                                //if ($rows % 2) {
                                if($value ==1){
                                    echo '<td class="role_tbl" align="center" style="cursor:pointer">' . $this->Form->input('per', array('div' => false, 'type' => 'checkbox', 'name' => $id, 'id' => $id, 'label' => false, 'value' => $value, 'error' => false,  'checked' =>$value)) . '</td>';
                                }else{
                                    echo '<td class="role_tbl" align="center" style="cursor:pointer">' . $this->Form->input('per', array('div' => false, 'type' => 'checkbox', 'name' => $id, 'id' => $id, 'label' => false, 'value' => $value, 'error' => false)) . '</td>';
                                }                                
                            } else {
                                //if ($rows % 2) {
                               if($value ==1){
                                    echo '<td class="role_tbl" align="center" style="cursor:pointer">' . $this->Form->input('per', array('div' => false, 'type' => 'checkbox', 'name' => $id, 'id' => $id, 'label' => false, 'value' => $value, 'error' => false,  'disabled' => 'disabled', 'checked' =>$value)) . '<span style="display:none" class="hidden">' . $value . '</span></td>';
                                }else{
                                    echo '<td class="role_tbl" align="center" style="cursor:pointer">' . $this->Form->input('per', array('div' => false, 'type' => 'checkbox', 'name' => $id, 'id' => $id, 'label' => false, 'value' => $value, 'disabled' => 'disabled', 'error' => false)) . '<span style="display:none" class="hidden">' . $value . '</span></td>';                                }
                            }
                        }
                        ?>
                    </tr>
                        <?php
                    } while ($rows++ < $permissionCount - 1);
                    ?>

            </tbody>
        </table>
                <?php echo $this->element('overlay'); ?>
        <table width="100%" border="0">
            <tr>
        <?php if ($this->action == 'update') { ?>
                    <td>
    <?php
    echo $this->Form->submit(__('Save'), array('id' => 'confirmation', 'label' => false, 'type' => 'button', 'onclick' => "javascript:document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'", 'div' => false));
    echo $this->Form->submit(__('Cancel', true), array('type' => 'button', 'onclick' => "location.href='" . $this->Html->url(Configure::read('SITE_BASE_URL') . 'roles') . "'", 'name' => 'cancel', 'div' => false));
    ?>
                    </td>
                    <?php } else { ?>
                    <td><?php echo $this->Form->submit(__('Edit'), array('label' => false, 'type' => 'submit')); ?></td>
                    <?php } ?>	
            </tr>
        </table>
    </div>
</div>
