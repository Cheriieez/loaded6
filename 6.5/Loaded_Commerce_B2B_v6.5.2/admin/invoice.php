<?php
/*
  $Id: invoice.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$order_id = (isset($_GET['order_id']) ? $_GET['order_id'] : '');
$customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($order_id)) . "'");
$customer_number = tep_db_fetch_array($customer_number_query);
$payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($order_id)) . "'");
$payment_info = tep_db_fetch_array($payment_info_query);
$payment_info = $payment_info['payment_info'];
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$oID = tep_db_prepare_input($_GET['oID']);
$orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
include(DIR_WS_CLASSES . 'order.php');
$order = new order($oID);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE . ' - ' . TITLE_PRINT_ORDER . $oID; ?></title>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>
<script type="text/javascript">
  if (typeof jQuery == 'undefined') {
    //alert('You are running a local copy of jQuery!');
    document.write(unescape("%3Cscript src='includes/javascript/jquery-1.6.2.min.js' type='text/javascript'%3E%3C/script%3E"));
  }
</script> 
<link rel="stylesheet" type="text/css" href="includes/print.css">
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<!-- body_text //-->
<table width="600" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td align="center" class="main"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td valign="top" align="left" class="main">
          <script language="JavaScript">
            if (window.print) {
              document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
            }
            else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
          </script>
        </td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src='images/close_window.jpg' border=0></a></p></td>
      </tr>
    </table></td>
  </tr>
  <tr align="left">
    <td class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '25'); ?></td>
  </tr>
  <tr>
    <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" align="center" width="75%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . '/logo/' . STORE_LOGO ,STORE_NAME);?></td>
          </tr>
          <tr>
            <td colspan="2" align="center" class="titleHeading"><b><?php echo TITLE_PRINT_ORDER  . $oID; ?></b></td>
          </tr>
          <tr align="left">
            <td colspan="2" class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="left" class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><?php echo '<b>' . ENTRY_PAYMENT_METHOD . '</b> ' . $order->info['payment_method']; ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo $payment_info; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="main"><?php echo '<b>' . ENTRY_DATE_PURCHASED . '</b> ' . $order->info['date_purchased']; ?></td>
  </tr>
  <tr>
    <td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor=#000000>
          <tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '&nbsp;', '<br>'); ?></td>
             
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor=#000000>
          <tr>
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>  
    <!-- multi-vendor shipping Invoice, only if the the data is in the "orders_shipping" table -->
    <?php 
   // if (defined('MVS_STATUS') && MVS_STATUS == 'true') { 
      if (tep_not_null($order->orders_shipping_id)) {  
        ?>
        <td><table border="1" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_PRODUCTS_VENDOR; ?></td>
            <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_VENDORS_SHIP; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SHIPPING_COST; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
          <?php
          $package_num = sizeof($order->products);
          $box_num = $l + 1;
          echo '<td class="dataTableContent">There will be <b>at least ' . $package_num . '</b><br>packages shipped.</td>';
          for ($l=0, $m=sizeof($order->products); $l<$m; $l++) {
            echo '<tr class="dataTableRow">' . "\n" .
                 '  <td class="dataTableContent" valign="center">Shipment Number ' . $box_num++ . '</td>' . "\n" .
                 '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>' . "\n" .
                 '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['Vmodule'] . '</td>' . "\n" .
                 '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['Vmethod'] . '</td>' . "\n" .
                 '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['Vcost'] . '</td>' . "\n" .
                 '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>' . "\n" .
                 '  <td class="dataTableContent" valign="center" align="center">ship tax<br>' . $order->products[$l]['Vship_tax'] . '</td>' . "\n" .
                 '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>' . "\n" .
                 '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>' . "\n" .
                 '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>' . "\n" .
                 '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['spacer'] . '</td>' . 
                 '</tr>' . "\n";
            for ($i=0, $n=sizeof($order->products[$l]['orders_products']); $i<$n; $i++) {
              echo '<tr>' . "\n" .
                   '  <td class="dataTableContent" valign="center" align="right">' . $order->products[$l]['orders_products'][$i]['qty'] . '&nbsp;x</td>' . "\n" .
                   '  <td class="dataTableContent" valign="center" align="left">' . $order->products[$l]['orders_products'][$i]['name'];
                        if (isset($order->products[$l]['orders_products'][$i]['attributes']) && (sizeof($order->products[$l]['orders_products'][$i]['attributes']) > 0)) {
                          for ($j = 0, $k = sizeof($order->products[$l]['orders_products'][$i]['attributes']); $j < $k; $j++) {
                            echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$l]['orders_products'][$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['orders_products'][$i]['attributes'][$j]['value'];
                            if ($order->products[$l]['orders_products'][$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$l]['orders_products'][$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$l]['orders_products'][$i]['attributes'][$j]['price'] * $order->products[$l]['orders_products'][$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
                            echo '</i></small></nobr>';
                          }
                          echo '</td>';  
                        }
                   echo '<td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
                   '<td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
                   '<td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
                   '<td class="dataTableContent" valign="center" align="center">' . $order->products[$l]['orders_products'][$i]['model'] . '</td>' . "\n" .
                   '<td class="dataTableContent" align="center" valign="center">' . tep_display_tax_value($order->products[$l]['orders_products'][$i]['tax']) . '%</td>' . "\n" .
                   '<td class="dataTableContent" align="center" valign="center"><b>' . $currencies->format($order->products[$l]['orders_products'][$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
                   '<td class="dataTableContent" align="center" valign="center"><b>' . $currencies->format(tep_add_tax($order->products[$l]['orders_products'][$i]['final_price'], $order->products[$l]['orders_products'][$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
                   '<td class="dataTableContent" align="center" valign="center"><b>' . $currencies->format($order->products[$l]['orders_products'][$i]['final_price'] * $order->products[$l]['orders_products'][$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
                   '<td class="dataTableContent" align="right" valign="center"><b>' .  $currencies->format(tep_add_tax($order->products[$l]['orders_products'][$i]['final_price'], $order->products[$l]['orders_products'][$i]['tax']) * $order->products[$l]['orders_products'][$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
              echo '</tr>';
            }
          }
          ?>
        </table></td>
        <?php
      //}
    } else {     
      //if (tep_not_null($order->orders_shipping_id)) { 
        ?>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor=#000000>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
                <?php 
                if (DISPLAY_PRICE_WITH_TAX == 'true') {
                  echo '<td class="dataTableHeadingContent" align="right">' . TABLE_HEADING_TOTAL_INCLUDING_TAX . '</td>';
                }
                ?>
              </tr>
              <?php
              for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
                echo '<tr class="dataTableRow">' . "\n" .
                     '  <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
                     '  <td class="dataTableContent" valign="top">' . $order->products[$i]['name'] . '<br>';
                          if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
                            for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
                             echo '<nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i><br></small></nobr>';
                            }
                          }
                          if (DISPLAY_PRICE_WITH_TAX == 'true') {
                            $pricew_tax = '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
                          } 
                echo '  </td>' . "\n" .
                     '  <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n";
                echo '  <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
                     '  <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
                     '  <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
                echo (isset($pricew_tax) ? $pricew_tax : '') ;
                echo '</tr>' . "\n";
              }
              ?>
            </table></td>
          </tr>
        </table></td>
        <?php
      //}
    }
    ?>
  </tr>
  <tr>
    <td align="right" colspan="7"><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <?php
          for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
            echo '<tr>' . "\n" .
                 '  <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
                 '  <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
                 '</tr>' . "\n";
          }
          ?>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
