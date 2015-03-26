<?php
/*
  $Id: tracking_orders_sidebarbuttons.php, v 1.2.0.0 2009/04/25 maestro Exp $

  ContributionCentral, Custom CRE Loaded & osCommerce Programming
  http://www.contributioncentral.com

  Copyright (c) 2009 ContributionCentral
  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $opt_enabled_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_ADDONS_OPT_STATUS'");
  $opt_enabled = tep_db_fetch_array($opt_enabled_query);
  if ($opt_enabled['configuration_value'] == 'True') {
    global $oInfo;
  
    if ($_GET['page'] == '') {
      $page = '1';
    } else {
      $page = $_GET['page'];
    }
  
    $rci = '<a href="' . tep_href_link('orders_tracking.php', 'selected_box=customers&page=' . $page . '&oID=' . $oInfo->orders_id . '&action=edit', 'SSL') . '">' . tep_image_button('button_order_tracking.gif', 'Order Tracking'). '</a>';
  }

?>