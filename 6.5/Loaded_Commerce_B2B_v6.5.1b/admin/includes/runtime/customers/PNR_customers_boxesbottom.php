<?php
/*
  $Id: ostatuspro_orders_boxesbottom.php, v 1.2.0.0 2008/01/28 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (MODULE_ADDONS_POINTS_STATUS == 'True') { 
  $rci .= tep_admin_files_boxes('', BOX_CUSTOMERS_POINTS_MENU);
  $rci .= tep_admin_files_boxes(FILENAME_CUSTOMERS_POINTS, BOX_CUSTOMERS_POINTS, 'SSL', '', '2');
  $rci .= tep_admin_files_boxes(FILENAME_CUSTOMERS_POINTS_PENDING, BOX_CUSTOMERS_POINTS_ORDERS, 'SSL', '', '2');
  $rci .= tep_admin_files_boxes(FILENAME_CUSTOMERS_POINTS_REFERRAL, BOX_CUSTOMERS_POINTS_PENDING, 'SSL', '', '2');
}
?>