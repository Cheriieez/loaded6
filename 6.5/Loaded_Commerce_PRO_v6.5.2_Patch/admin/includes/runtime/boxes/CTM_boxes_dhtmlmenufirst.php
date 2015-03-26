<?php
/*
  $Id: CTM_boxes_dhtmlmenu.php,v 1.0.0.0 2007/11/03 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2007 ContributionCentral

  Released under the GNU General Public License
*/
$is_65 = (INSTALLED_VERSION_MAJOR == '6' && INSTALLED_VERSION_MINOR == '5') ? true : false;
if (!$is_65) {
  if (defined('MODULE_ADDONS_CTM_STATUS') && MODULE_ADDONS_CTM_STATUS == 'True') {
    $rci = "testimonials, testimonials.php, Customer Testimonials";
  }
}
?>