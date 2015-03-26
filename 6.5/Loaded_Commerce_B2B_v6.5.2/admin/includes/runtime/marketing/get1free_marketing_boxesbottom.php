<?php
/*
  $Id: get1free_marketing_boxesbottom.php, v 1.2.0.0 2008/01/28 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  if (defined('MODULE_ADDONS_GET1FREE_STATUS') && MODULE_ADDONS_GET1FREE_STATUS == 'True') { 
    $rci = tep_admin_files_boxes(FILENAME_GET_1_FREE, BOX_CATALOG_GET_1_FREE, 'SSL','','2');
  }
?>
