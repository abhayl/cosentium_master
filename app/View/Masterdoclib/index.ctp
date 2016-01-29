<?php
echo $this->Html->script('jquery-migrate-1.2.0.min.js');
echo $this->Html->script('__jquery.tablesorter.min.js');
?>
<script type="text/javascript">

    $(document).ready(function(){     		

        $("#roles_list_tbl").tablesorter({
            widgets: ['zebra'],
            sortList: [[0,0]],
            headers: {
            3: { sorter: false }         
                    }
}); 
}); 
</script>   
    <div class="full_innercontent"> 
        <div class="heading"><?php echo __('Document Library') . " | "; echo __("Customers"); ?><?php echo $masterDocCompanyName; ?><div class="search"><input type="text" class="searchtextbox" name="doclibsearch" id="doclibsearch" placeholder="ABC Corporation" onKeyPress="javascript:checkSubmit(event, 'searchForm','' ,'', this.value);" onClick="javascript:submitForm('searchForm','' ,'', this.value);"><img src="img/search_icon.png" onClick="javascript:submitForm('searchForm','' ,'', doclibsearch.value);"></div></div>
<?php if (isset($masterDocSearchResult) && $masterDocSearchResult > 0) { ?> <div class="blueheading"><?php echo __('Search')." - " . $masterDocSearchResult ." ". __('Results'); ?></div><?php } ?>

        <div class="userlistcontainer"> 

            <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0" id="roles_list_tbl">
                <thead>
                    <tr>
                        <th width="28%" height="32" align="left" data-column="1" class="roles_permission_tblcol1"><span><?php echo __('Name'); ?></span></th>
                        <th width="23%" align="left" data-column="2" class="roles_permission_tblcol1"><span><?php echo __('Kind'); ?></span></th>
                        <th width="23%" align="left" data-column="3" class="roles_permission_tblcol1"><span><?php echo __('Modified'); ?></span></th>
                        <th width="26%" align="left" data-column="4" class="roles_permission_tblcol1"><span><?php echo __('Action'); ?></span></th>
                    </tr>
                </thead><tbody> 
                <?php
                if (count($masterDoc) > 0) {
                    $i = 0;
                    $j = 0;
                    foreach ($masterDoc as $key => $val):

                        if ($key != "FilesSeachCount") {
                            if ($val['Kind'] == "File") {
                                foreach ($val['details'] as $FileKey => $FileVar):
                                    // pr($FileVar);
                                    //echo $FileVar['FileId'];
                                    ?>
                                    
                                        <tr class="odd">
                                            <td align="left" class="roles_permission_tblcol1"><a href="#" onClick="javascript:submitForm('docViewForm',<?php echo $FileVar['FileId']; ?> ,<?php echo $FileVar['CompanyId']; ?>,'','','');"><img src="img/file_icon.png" class="filethumbnail_right_margin"><?php echo ($FileVar['FileName'] != "") ? $FileVar['FileName'] : $FileVar['S3key']; ?></a></td>
                                            <td align="left" class="roles_permission_tblcol1"><?php echo $val['Kind']; ?></td>
                                            <td align="left" class="roles_permission_tblcol1"><?php echo date(Configure::read('DB_DATE_DOC_FORMAT'), strtotime($FileVar['Modified'])); ?></td>
                                            <td align="left" class="roles_permission_tblcol1"><a href="#" onClick="javascript:submitForm('docForm',<?php echo $FileVar['FileId']; ?> ,<?php echo $FileVar['CompanyId']; ?> ,'','','');"><?php echo "Download"; ?></a></td>
                                        </tr>              

                                                
                                    <?php
                                    $j++;
                                endforeach;
                            }
                            else {
                                ?>
                                    <tr class="odd">
                                        <td align="left" class="roles_permission_tblcol1"><a href="#" onClick="javascript:submitForm('docSaerchForm',0 ,<?php echo $val['CompanyId']; ?>, '<?php echo $val['Search']; ?>', <?php echo $val['Level']; ?>, '<?php echo $key; ?>');"><img src="img/folder_icon.png" class="folderthumbnail_right_margin"><?php echo $key; ?></a></td>
                                        <td align="left" class="roles_permission_tblcol1"><?php echo $val['Kind']; ?></td>
                                        <td align="left" class="roles_permission_tblcol1"><?php echo "--"; ?></td>
                                        <td align="left" class="roles_permission_tblcol1"><?php echo "--"; ?></td>
                                    </tr>              

                                 
                                <?php
                            }
                        }
                        $i++;
                    endforeach;
                }
                ?>
            </tbody></table>
        </div>
    </div>
    <?php echo $this->Form->create('MasterdocLib', array('id' => 'docForm', 'url' => array('controller' => 'masterdoclib', 'action' => 'fileDownLoad'), 'target' => '_blank')); ?>
    <?php echo $this->Form->input('docid', array('id' => 'docid', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
    <?php echo $this->Form->input('cpid', array('id' => 'cpid', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
    <?php echo $this->Form->end(); ?>

    <?php echo $this->Form->create('MasterdocLibSearch', array('id' => 'searchForm', 'url' => array('controller' => 'masterdoclib', 'action' => 'index'))); ?>
    <?php echo $this->Form->input('docquery', array('id' => 'docquery', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>             
    <?php echo $this->Form->end(); ?>

    <?php echo $this->Form->create('MasterdocLibFolderSearch', array('id' => 'docSaerchForm', 'url' => array('controller' => 'masterdoclib', 'action' => 'index'))); ?>
    <?php echo $this->Form->input('foldocquery', array('id' => 'foldocquery', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?> 
    <?php echo $this->Form->input('folcpid', array('id' => 'folcpid', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
    <?php echo $this->Form->input('level', array('id' => 'level', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>             
    <?php echo $this->Form->input('fldname', array('id' => 'fldname', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>             
    <?php echo $this->Form->end(); ?>
     <?php echo $this->Form->create('MasterdocViewDetails', array('id' => 'docViewForm', 'url' => array('controller' => 'masterdoclib', 'action' => 'viewMasterDoc'), 'target' => '_blank')); ?>
    <?php echo $this->Form->input('viewdocid', array('id' => 'viewdocid', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
    <?php echo $this->Form->input('viewcpid', array('id' => 'viewcpid', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>         
    <?php echo $this->Form->end(); ?>
    
    <script type="text/javascript">
        function submitForm(frmName, docid, cpid, qString, level, fldname){            
            if(frmName == "docForm"){
                $('#docid').val(docid);  
                $('#cpid').val(cpid);
                $('#docForm').submit();
            }else if(frmName == "searchForm"){          
                $('#docquery').val(qString);
                if(qString != ""){
                    $('#searchForm').submit();
                }else{
                    return false;
                }
            }else if(frmName == "docSaerchForm"){ 
                $('#folcpid').val(cpid);
                $('#foldocquery').val(qString);
                $('#level').val(level);
                $('#fldname').val(fldname);
                $('#docSaerchForm').submit();
                return false;
            }else if(frmName == "docViewForm"){ 
                $('#viewdocid').val(docid);
                $('#viewcpid').val(cpid);                
                $('#docViewForm').submit();
                return false;
            }else{
                return false;
            }
         
        }
        function checkSubmit(e,frmName, docid, cpid, qString, level, fldname)
        {
            if(e && e.keyCode == 13)
            {
                submitForm(frmName, docid, cpid, qString, level, fldname);
            }
        }
    </script>