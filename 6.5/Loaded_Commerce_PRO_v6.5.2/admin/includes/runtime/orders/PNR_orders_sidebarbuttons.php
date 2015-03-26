<?php
/*
  $Id: PNR_orders_sidebarbuttons.php,v 1.0.0.0 2007/08/16 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if (MODULE_ADDONS_POINTS_STATUS == 'True') {
  global $oInfo;
  $rci = '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, 'search=' . $oInfo->orders_id) . '">' . tep_image_button('button_order_points.gif', BOX_CUSTOMERS_POINTS_ORDERS) . '</a>';
}
?>