<?php $arrCompanyName = explode("/", $masterDocData['s3_uri_path']); ?>  
<div class="full_innercontent"> 
    <div class="heading"><?php echo __('Document Library'); ?>
        <div class="search"><input type="text" class="searchtextbox" name="doclibsearch" id="doclibsearch" placeholder="ABC Corporation" onKeyPress="javascript:checkSubmit(event, 'searchForm','' ,'', this.value);" onClick="javascript:submitForm('searchForm','' ,'', this.value);"><img src="../img/search_icon.png" onClick="javascript:submitForm('searchForm','' ,'', doclibsearch.value);"></div>
    </div>
    <div class="userlistcontainer"> 

        <table width="100%" border="0" class="usr_list_tbl" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th width="800%" align="left" class="roles_permission_tblcol1"><?php echo $arrCompanyName[0]; ?> | <?php echo __('File'); ?>  : <?php echo $masterDocData['s3_key']; ?> 
            <div class="document_open_state_header_righttext">
                <?php echo __('Last'); ?>  <?php echo __('updated'); ?> <?php echo __('on'); ?>  : <?php echo date(Configure::read('DB_DATE_DOC_FORMAT'), strtotime($masterDocData['modified'])); ?></div></th>
            </tr>
            </thead>
            <tbody>
                <tr class="odd">

                    <td align="left">
                        <div class="contract_viewer">
                            <?php if($masterDocData['master_converted_html'] != ""){							
								echo $masterDocData['master_converted_html'];
							} else { 
								echo $masterDocData['deal_converted_html'];
							} ?>
                        </div>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php echo $this->Form->create('MasterdocLibSearch', array('id' => 'searchForm', 'url' => array('controller' => 'masterdoclib', 'action' => 'index'))); ?>
<?php echo $this->Form->input('docquery', array('id' => 'docquery', 'required' => 'false', 'type' => 'hidden', 'div' => false)); ?>             
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    function submitForm(frmName, docid, cpid, qString, level, fldname){
        if(frmName == "searchForm"){          
            $('#docquery').val(qString);
            if(qString != ""){
                $('#searchForm').submit();
            }else{
                return false;
            }
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