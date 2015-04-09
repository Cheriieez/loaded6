<?php
/*
  $Id: orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  include_once (DIR_WS_LANGUAGES . '/' . $_SESSION['language'] . '/orders.php');
  
  $oID = tep_db_prepare_input((int)$_GET['oID']);
  $action = (isset($_GET['action']) ? $_GET['action'] : 'edit');  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');  
  
  // RCI code start
  //echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('orders_tracking', 'top'); 
  // RCI code eof
  
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
  
  //get shipping method
  $shipping = array();
  $orders_ship_method = array();
  $orders_ship_method_array = array();
  $orders_ship_method_query = tep_db_query("select ship_method from orders_ship_methods where ship_method_language = '" . (int)$languages_id . "'");
  while ($orders_ship_methods = tep_db_fetch_array($orders_ship_method_query)) {
    $orders_ship_method[] = array('id' => $orders_ship_methods['ship_method'],
                                  'text' => $orders_ship_methods['ship_method']);
    $orders_ship_method_array[$orders_ship_methods['ship_method']] = $orders_ship_methods['ship_method'];
    $shippingElements[] = array('id' => $orders_ship_methods['ship_method'],
                                'text' => $orders_ship_methods['ship_method']);
  }
  $tracking_num_query = tep_db_query("select shipment_track_num from " . TABLE_ORDERS . " where orders_id = '" . $oID . "'");
  $tracking_num = tep_db_fetch_array($tracking_num_query);
  $ship_method_query = tep_db_query("select shipment_method from " . TABLE_ORDERS . " where orders_id = '" . $oID . "'");
  $ship_method = tep_db_fetch_array($ship_method_query);
  
  //UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
  $order_query = tep_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
  //UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
  
  if (tep_not_null($action)) {
    if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
      $notify_comments = '';
      $customer_notified = '0';
      if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
        $customer_notified = '1';
        $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $_POST['comments']) . "\n\n";
      }
    }
    switch ($action) {
      case 'update_order':
        $status = tep_db_prepare_input($_POST['status']);
        $comments = tep_db_prepare_input($_POST['comments']);
        $shipment_track_num = (isset($_POST['shipment_track_num'])) ? $_POST['shipment_track_num'] : '';
        $shipment_method = (isset($_POST['shipment_method'])) ? $_POST['shipment_method'] : '';
        $order_updated = 'false';
        $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, shipment_track_num, shipment_method, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$_GET['oID'] . "'");
        $check_status = tep_db_fetch_array($check_status_query);
        if (tep_not_null($comments) && $notify_comments != '') {
          $order_updated = 'true';
        }
        if (($check_status['orders_status'] != $status) || ($check_status['shipment_track_num'] != $shipment_track_num) || ($check_status['shipment_method'] != $shipment_method)) {
          tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', shipment_method = '" . tep_db_input($shipment_method) . "', shipment_track_num = '" . tep_db_input($shipment_track_num) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");
          $order_updated = 'true';
          $check_status_query2 = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
          $check_status2 = tep_db_fetch_array($check_status_query2);
        }
        if ($order_updated == 'true') {
          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");
        }
        if (isset($_POST['notify']) && $_POST['notify'] == 'on' && $order_updated == 'true') {
          if (tep_not_null($shipment_track_num)) {
            $url_mail = (tep_catalog_href_link(FILENAME_POPUP_TRACKER, 'action=track&tracknumbers=' . urlencode($shipment_track_num). '&type=' . preg_replace('/\s+/', '_', $shipment_method), 'NONSSL'));
            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . EMAIL_TEXT_TRACKING . ' ' . $url_mail . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]) . "\n\n" . $notify_comments;                 
            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');        
            $customer_notified = '1';
          } else {
            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT_1 . $oID . EMAIL_TEXT_SUBJECT_2, nl2br($email), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            $customer_notified = '1';
          }
        }
        if ($order_updated == 'true') {
          $messageStack->add_session('search', SUCCESS_ORDER_UPDATED, 'success');
        } else {
          $messageStack->add_session('search', WARNING_ORDER_NOT_UPDATED, 'warning');
        }
        tep_redirect(tep_href_link(FILENAME_ORDERS, 'page=' . $_GET['page'] . '&amp;oID=' . $_GET['oID'] . '&amp;action=edit', 'SSL'));
        break;
    }
  }
  
  if (($action == 'edit') && isset($_GET['oID'])) {
    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add('search', sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }
  include(DIR_WS_CLASSES . 'order.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
    <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <?php
        if (($action == 'edit') && ($order_exists == true)) {
          $order = new order($oID);
      ?>
        <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              <td align="right"><a href="<?php echo tep_href_link(FILENAME_ORDERS, 'page=' . $_GET['page'] . '&oID=' . $_GET['oID'] . (tep_not_null($_GET['page']) ? 'page=' . (int)$_GET['page'] : '') , 'SSL');?>"><?php echo tep_image_button('button_back.gif', IMAGE_BACK);?></a></td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td class="main"><table border="1" cellspacing="0" cellpadding="5">
                  <tr>
                    <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
                    <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
                    <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
                    <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
                  </tr>
                  <?php
                    $orders_history_query = tep_db_query("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
                    if (tep_db_num_rows($orders_history_query)) {
                      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
                        echo '          <tr>' . "\n" .
                             '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
                             '            <td class="smallText" align="center">';
                        if ($orders_history['customer_notified'] == '1') {
                          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
                        } else {
                          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
                        }
                        echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" .
                             '            <td class="smallText">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n" .
                             '          </tr>' . "\n";
                      }
                    } else {
                        echo '          <tr>' . "\n" .
                             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
                             '          </tr>' . "\n";
                    }
                  ?>
                </table></td>
            </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
            <tr>
              <td><?php echo tep_draw_form('status', 'orders_tracking.php', tep_get_all_get_params(array('action')) . 'action=update_order', 'post', '', 'SSL'); ?>
                <table border="0" cellspacing="5" cellpadding="5">
                  <tr>
                    <td><b><?php echo TABLE_HEADING_SHIPPING_METHOD; ?>:&nbsp;</b><?php echo tep_draw_pull_down_menu('shipment_method', $shippingElements, $ship_method['shipment_method']); ?></td>
                    <td><b><?php echo TABLE_HEADING_TRACKING_NUMBER; ?>:&nbsp;</b><?php echo tep_draw_input_field('shipment_track_num', $tracking_num['shipment_track_num']); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="main"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="main"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="main"><b><?php echo ENTRY_STATUS; ?></b>&nbsp;<?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status_number']); ?></td>
                  </tr>
                  <tr>
                    <td><table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
                          <td class="main">&nbsp; <b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>
                        </tr>
                      </table>
                  </tr>
                  <tr>
                    <td colspan="2" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) ;?></td>
                  </tr>
                </table></form></td>
            </tr>
            <?php
                  // RCI start
                  echo $cre_RCI->get('orders_tracking', 'bottom');
                  // RCI eof
            ?>
            
            </tr>
            <tr>
              <?php
             }
          // RCI code start
             echo $cre_RCI->get('global', 'bottom');                                        
         // RCI code eof
           ?>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
  </td>
  </tr>
  </table>
  </td>
  <!-- body_text_eof //-->
  </tr>
  </table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>