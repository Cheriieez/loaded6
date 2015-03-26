<?php
/*
  $Id: FDMS_boxes_dhtmlmenu.php,v 1.2 2008/12/11 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$is_651 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 5 && INSTALLED_PATCH == 1) ? true : false;
if (!$is_651) {
  if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') { 
    $rci = "fdm_library, fdm_library.php, File Distribution";
  }
}
?>