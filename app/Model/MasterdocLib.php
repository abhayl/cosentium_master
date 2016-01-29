<?php
/**
* file - MasterdocLib.php
* author - Abhay Lalpotu <abhayl@cybage.com>
* Created Date - 26-09-2013
* Modified By - 
*/
class MasterdocLib extends AppModel {

    public $name = 'MasterdocLib';

    function __construct() {
        parent::__construct();
        $this->tablePrefix = CakeSession::read('user.Company.prefix') . '_';
        $this->useTable = 'master_docs';
    }

    public function getDocDownloadDetails($id, $companyId, $flgDocView=false) {

        if ($companyId > 0) {
            $arrDocDetails = array();

            $srtCompanyCondition = " AND id ='" . $companyId . "'";

            $masterCompanyData = $this->query("SELECT id, company_name, prefix FROM companies WHERE status = 1 " . $srtCompanyCondition);

            $strCompanyNamePrifix = $masterCompanyData[0]['companies']['prefix'];
            $strBaseTableName = strtolower($strCompanyNamePrifix . "_files");
            $strDocTableName = strtolower($strCompanyNamePrifix . "_master_docs");
            $strDealRevisionTableName = strtolower($strCompanyNamePrifix . "_deal_revisions");
            if($flgDocView == true){
                $strCondition = " WHERE  " . $strBaseTableName . ".id = " . trim($id);
                $masterDocData = $this->query("SELECT " . $strBaseTableName . ".* , " . $strDocTableName . ".* , ".$strDealRevisionTableName . ".*   FROM " . $strBaseTableName . " LEFT JOIN " . $strDocTableName . " ON " . $strBaseTableName . ".id = " . $strDocTableName . ".files_id  LEFT JOIN " . $strDealRevisionTableName . " ON " . $strBaseTableName . ".id = " . $strDealRevisionTableName . ".files_id " . $strCondition);
            }else{
                $strCondition = " WHERE  " . $strBaseTableName . ".id = " . trim($id);
                $masterDocData = $this->query("SELECT " . $strBaseTableName . ".* , " . $strDocTableName . ".name  FROM " . $strBaseTableName . " LEFT JOIN " . $strDocTableName . " ON " . $strBaseTableName . ".id = " . $strDocTableName . ".files_id " . $strCondition);
            }            
            if (isset($masterDocData) && count($masterDocData) > 0) {
                $arrDocDetails['id'] = $masterDocData[0][$strBaseTableName]['id'];
                $arrDocDetails['s3_uri_path'] = $masterDocData[0][$strBaseTableName]['s3_uri_path'];
                $arrDocDetails['s3_key'] = $masterDocData[0][$strBaseTableName]['s3_key'];
                $arrDocDetails['created'] = $masterDocData[0][$strBaseTableName]['created'];
                $arrDocDetails['modified'] = $masterDocData[0][$strBaseTableName]['modified'];
                $arrDocDetails['name'] = $masterDocData[0][$strDocTableName]['name'];
                if($flgDocView == true){
                    $arrDocDetails['master_converted_html'] = $masterDocData[0][$strDocTableName]['converted_html'];
                    $arrDocDetails['deal_converted_html'] =   $masterDocData[0][$strDealRevisionTableName]['converted_html'];                                      
                 }
                return $arrDocDetails;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getCompanyName($level, $companyId = null) {

        if ($companyId == null || $companyId == 0 && $level > 0) {
            return "";
        } elseif ($companyId > 0) {
            $srtCompanyCondition = " AND id ='" . $companyId . "'";
            $masterCompanyData = $this->query("SELECT company_name FROM companies WHERE status = 1 " . $srtCompanyCondition);
            $company_name = $masterCompanyData[0]['companies']['company_name'];
            return " | " . $company_name;
        }



        return "";
    }

    public function getFileDownLoad($id, $companyId) {
        $this->AmazoneFileSystem = ClassRegistry::init('AmazoneFileSystem');
        $arrDocDetails = $this->getDocDownloadDetails($id, $companyId);
        if (isset($arrDocDetails) && count($arrDocDetails) > 0) {
            if ($this->AmazoneFileSystem->getObject($this->AmazoneFileSystem->bucket, Configure::read('AWS_DOC_FOLDER') . $arrDocDetails['s3_uri_path'] . $arrDocDetails['s3_key'], Configure::read('DOWNLOAD_DOC_PATH') . $arrDocDetails['s3_key']) != false) {
                return Configure::read('GET_DOWNLOAD_DOC_PATH') . $arrDocDetails['s3_key'];
            } else {
                return false;
            }
        }
    }

    public function getMasterCompanyList() {
        $db = ConnectionManager::getDataSource('default');
        return $arrTablesList = $db->listSources();
    }

    public function getMasterDocByCompany($strSearchKey, $level, $companyId = 0, $srtFldName = "") {

        $arrTablesList = $this->getMasterCompanyList();

        if ($companyId == 0) {
            $srtCompanyCondition = "";
        } elseif ($companyId > 0) {
            $srtCompanyCondition = " AND id ='" . $companyId . "'";
        }

        $masterCompanyData = $this->query("SELECT id, company_name, prefix FROM companies WHERE status = 1 " . $srtCompanyCondition);

        $arrLevelPathFinal = array();
        $arrLevelPath = array();
        $intSeachCount = 0;
        foreach ($masterCompanyData as $key => $val) {

            $strId = $masterCompanyData[$key]['companies']['id'];
            $strCompanyNamePrifix = $masterCompanyData[$key]['companies']['prefix'];
            $strBaseTableName = strtolower($strCompanyNamePrifix . "_files");
            $strDocTableName = strtolower($strCompanyNamePrifix . "_master_docs");
            $strDealTableName = strtolower($strCompanyNamePrifix . "_deals");

            if (in_array(trim($strBaseTableName), $arrTablesList)) {
                $strCondition = "";
                if ($strSearchKey != "") {
                    $strCondition = " WHERE " . $strBaseTableName . ".s3_uri_path like '%" . trim($strSearchKey) . "%' OR " . $strBaseTableName . ".s3_key like '%" . trim($strSearchKey) . "%' OR " . $strDocTableName . ".name like '%" . trim($strSearchKey) . "%'";
                    "SELECT " . $strBaseTableName . ".* , " . $strDocTableName . ".name  FROM " . $strBaseTableName . " LEFT JOIN " . $strDocTableName . " ON " . $strBaseTableName . ".id = " . $strDocTableName . ".files_id " . $strCondition;
                }
                $masterDocData = $this->query("SELECT " . $strBaseTableName . ".* , " . $strDocTableName . ".name  FROM " . $strBaseTableName . " LEFT JOIN " . $strDocTableName . " ON " . $strBaseTableName . ".id = " . $strDocTableName . ".files_id " . $strCondition);

                if (count($masterDocData) > 0) {
                    $i = 0;
                    foreach ($masterDocData as $key => $val) {
                        $flgFolder = true;
                        if (($masterDocData[$key][$strBaseTableName]['s3_uri_path']) != "") {
                            $arrLevelPath = explode("/", $masterDocData[$key][$strBaseTableName]['s3_uri_path']);

                            $intLevelCount = count($arrLevelPath) - 1;

                            if ($intLevelCount >= $level && $arrLevelPath[0] != "") {
                                if ($srtFldName != "") {
                                    $newLevel = $level - 1;
                                    if ($srtFldName == $arrLevelPath[$newLevel]) {
                                        $flgFolder = true;
                                    } else {
                                        $flgFolder = false;
                                    }
                                }
                                if ($flgFolder == true) {
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['folder_name'] = $arrLevelPath[$level];
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['Kind'] = (isset($arrLevelPath[$level]) && $arrLevelPath[$level] != "") ? "Folder" : "File";
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['CompanyId'] = $strId;
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['Level'] = ($level == $intLevelCount) ? $level : $level + 1;
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['Search'] = ($strSearchKey != "") ? $strSearchKey : "";
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['details'][$i]['Location'] = $masterDocData[$key][$strBaseTableName]['s3_uri_path'];
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['details'][$i]['S3key'] = trim($masterDocData[$key][$strBaseTableName]['s3_key']);
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['details'][$i]['Modified'] = $masterDocData[$key][$strBaseTableName]['modified'];
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['details'][$i]['FileName'] = $masterDocData[$key][$strDocTableName]['name'];
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['details'][$i]['CompanyId'] = $strId;
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['details'][$i]['FileId'] = $masterDocData[$key][$strBaseTableName]['id'];
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['details'][$i]['Level'] = ($level == $intLevelCount) ? $level : $level + 1;
                                    $arrLevelPathFinal[$arrLevelPath[$level]]['details'][$i]['Kind'] = (isset($arrLevelPath[$level]) && $arrLevelPath[$level] != "") ? "Folder" : "File";
                                    $i++;
                                    $intSeachCount = $intSeachCount + 1;
                                }
                            }
                        }
                    }
                }
            }
        }
        $arrLevelPathFinal['FilesSeachCount'] = $intSeachCount;
        //pr($arrLevelPathFinal);
        //if (!empty($arrLevelPathFinal))
        return $arrLevelPathFinal;
        // else
        //  return false;
    }

}

?>
