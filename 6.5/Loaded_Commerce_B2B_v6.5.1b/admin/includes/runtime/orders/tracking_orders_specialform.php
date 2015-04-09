<?php
/*
  $Id: ostatuspro_orders_boxesbottom.php, v 1.2.0.0 2008/01/28 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (defined('MODULE_ADDONS_OPT_STATUS') && MODULE_ADDONS_OPT_STATUS == 'True') {
    $tracking_num_query = tep_db_query("select shipment_track_num from " . TABLE_ORDERS . " where orders_id = '" . (int)$_GET['oID'] . "'");
    $tracking_num = tep_db_fetch_array($tracking_num_query);
    $ship_method_query = tep_db_query("select shipment_method from " . TABLE_ORDERS . " where orders_id = '" . (int)$_GET['oID'] . "'");
    $ship_method = tep_db_fetch_array($ship_method_query);

    if ($tracking_num['shipment_track_num']) {
      $rci = '<tr><td><table width="400"><tr><td style="border:1px solid #D3D3D3; padding-bottom:3px;">';
      $rci .= '<br style="line-height:40%;" /><span class="main"><b>&nbsp;Shipment method: </b>' . $ship_method['shipment_method'] . '<br /><br style="line-height:30%;" /><b>&nbsp;Tracking Number: </b>' . $tracking_num['shipment_track_num'] . '<br /><br style="line-height:40%;" /></span>';
      $rci .= '&nbsp;<a href="' . tep_href_link('orders_tracking.php', 'selected_box=customers&oID=' . $_GET['oID'] . '&action=edit', 'SSL') . '">' . tep_image_button('button_edit.gif', 'Edit Tracking'). '</a>';
      $rci .= '</td></tr></table></td></tr>';
    } else {
      $rci = '<tr><td><table width="400"><tr><td style="border:1px solid #D3D3D3; padding-bottom:3px;">';
      $rci .= '<br style="line-height:40%;" /><span class="main"><b>&nbsp;Shipment method: </b><br /><br style="line-height:30%;" />&nbsp;&nbsp;&nbsp;No Package Tracking has been set for this order.<br /><br style="line-height:30%;" />';
      $rci .= '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link('orders_tracking.php', 'selected_box=customers&oID=' . $_GET['oID'] . '&action=edit', 'SSL') . '">' . tep_image_button('button_edit.gif', 'Edit Tracking'). '</a>';
      $rci .= '</td></tr></table></td></tr>';
    }
  }

?>