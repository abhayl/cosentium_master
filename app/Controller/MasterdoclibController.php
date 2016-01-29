<?php
/**
* file - MasterdoclibController.php
* author - Abhay Lalpotu <abhayl@cybage.com>
* Created Date - 26-09-2013
* Modified By - 
*/
class MasterdoclibController extends AppController {

    public $uses = array('MasterdocLib', 'User');
    var $paginate = array();

    public function index() {

        $this->set('title_for_layout', __('Master Documents'));

        if (CakeSession::read('masterDoc') != NULL) {
            CakeSession::delete('masterDoc');
        }
        $strSearch = "";
        $intLevel = "0";
        $intCompanyId = "0";
        $srtFldName = "";
        $intSeachCount = 0;
        $flgSerchCount = false;
        if ($this->request->is('post')) {
            if (isset($this->request->data['MasterdocLibSearch'])) {
                $strSearch = isset($this->request->data['MasterdocLibSearch']['docquery']) ? trim($this->request->data['MasterdocLibSearch']['docquery']) : "";
                $intCompanyId = isset($this->request->data['MasterdocLibSearch']['cpid']) ? $this->request->data['MasterdocLibSearch']['cpid'] : 0;
                $intLevel = isset($this->request->data['MasterdocLibSearch']['level']) ? $this->request->data['MasterdocLibSearch']['level'] : 0;
            } else if (isset($this->request->data['MasterdocLibFolderSearch'])) {
                $strSearch = isset($this->request->data['MasterdocLibFolderSearch']['foldocquery']) ? trim($this->request->data['MasterdocLibFolderSearch']['foldocquery']) : "";
                $intCompanyId = isset($this->request->data['MasterdocLibFolderSearch']['folcpid']) ? $this->request->data['MasterdocLibFolderSearch']['folcpid'] : 0;
                $intLevel = isset($this->request->data['MasterdocLibFolderSearch']['level']) ? $this->request->data['MasterdocLibFolderSearch']['level'] : 0;
                $srtFldName = isset($this->request->data['MasterdocLibFolderSearch']['fldname']) ? $this->request->data['MasterdocLibFolderSearch']['fldname'] : "";
            }
        }
        if ($strSearch != "") {
            $flgSerchCount = true;
        }
        //pr($this->request->data);
        // pr($this->MasterdocLib->getFileDownLoad(46, 169));
        //exit;
        $masterDoc = $this->MasterdocLib->getMasterDocByCompany($strSearch, $intLevel, $intCompanyId, $srtFldName);
        $this->set('masterDoc', $masterDoc);
        if ($flgSerchCount == true) {
            $intSeachCount = $masterDoc['FilesSeachCount'];
            $this->set('masterDocSearchResult', $intSeachCount);
        }
        $this->set('masterDocCompanyName', $this->MasterdocLib->getCompanyName($intLevel, $intCompanyId));
    }

    public function serachDoc() {

        $this->set('title_for_layout', __('Master Documents'));

        if (CakeSession::read('masterDoc') != NULL) {
            CakeSession::delete('masterDoc');
        }
        // pr($this->MasterdocLib->getFileDownLoad(46, 169));
        // exit;
        $this->set('masterDoc', $this->MasterdocLib->getMasterDocByCompany('', 2, 169));
    }

    public function fileDownLoad() {
        $this->set('title_for_layout', __('Master Documents'));
        $intFileId = $this->request->data['MasterdocLib']['docid'];
        $intCompanyId = $this->request->data['MasterdocLib']['cpid'];

        try {
            $filePath = @$this->MasterdocLib->getFileDownLoad($intFileId, $intCompanyId);
            $masterDocData = $this->MasterdocLib->getDocDownloadDetails($intFileId, $intCompanyId); 
            $file_name = $masterDocData['s3_key'];
            $this->response->file($filePath, array(
                'download' => true,
                'name' => $file_name,
            ));
            return $this->response;
        } catch (Exception $e) {
            $this->set('errorMessage', __('The issue in the download the file. Try it later'));
        }
        //$this->set('FilePath', $filePath);
    }

    public function viewMasterDoc() {
        //set page title
        $this->set('title_for_layout', __('View Document'));
        if ($this->request->is('post')) {
            if (!empty($this->request->data['MasterdocViewDetails']['viewdocid'])) {                
                $masterDocData = $this->MasterdocLib->getDocDownloadDetails($this->request->data['MasterdocViewDetails']['viewdocid'], $this->request->data['MasterdocViewDetails']['viewcpid'], true);                
                if (!empty($masterDocData)) {                     
                    $this->set('masterDocData', $masterDocData);
                }
            }
            $this->set('masterDocData', $masterDocData);
            $this->set('confirm_message', Configure::read('DESCRIBE_MASTERDOC_CONFIRMATION_MSG'));
        }
    }

}
?>