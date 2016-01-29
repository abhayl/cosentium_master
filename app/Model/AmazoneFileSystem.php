<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AmazoneFileSystem extends AppModel {
    public $useDbConfig = 'amazon_s3',
          $useTable    = false,
          $bucket      = 'cosentium';
}
?>
