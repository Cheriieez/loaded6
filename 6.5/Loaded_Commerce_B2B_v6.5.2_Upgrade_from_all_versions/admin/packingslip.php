<?php
/*
  $Id: packingslip.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

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
<title><?php echo TITLE . ' - ' . TITLE_PRINT_ORDER . ' #' . $_GET['order_id']; ?></title>
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
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center" class="main"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td valign="top" align="left" class="main"><script language="JavaScript">
  if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
  }
  else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
        </script></td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src='images/close_window.jpg' border=0></a></p></td>
      </tr>
    </table></td>
  </tr>
  <tr align="left">
    <td class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
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
            <td colspan="2" align="center" class="titleHeading"><b><?php echo 'Order#' . $_GET['oID']; ?></b></td>
          </tr>
          <tr align="left">
            <td colspan="2" class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="dataTableContent"><?php echo '<b>' . ENTRY_DATE_PURCHASED . '</b> ' . $order->info['date_purchased']; ?></td>
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
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo '&nbsp;<b>Telephone#</b>' . '<br>&nbsp;' . $order->customer['telephone']; ?></td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo '&nbsp;<b>eMail Address:</b>' . '<br>&nbsp;' . $order->customer['email_address']; ?></td>
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
    <td><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="dataTableHeadingContent"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
        <td class="dataTableContent"><?php echo $order->info['payment_method']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <?php 
    // multi-vendor shipping
    if (defined('MVS_STATUS') && MVS_STATUS == 'true') { 
      $index = 0;
      $order_packslip_query = tep_db_query("SELECT vendors_id, orders_products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from " . TABLE_ORDERS_PRODUCTS . " WHERE orders_id = '" . (int)$oID . "'");
      while ($order_packslip_data = tep_db_fetch_array($order_packslip_query)) {
        $packslip_products[$index] = array('qty' => $order_packslip_data['products_quantity'],
                                           'name' => $order_packslip_data['products_name'],
                                           'model' => $order_packslip_data['products_model'],
                                           'tax' => $order_packslip_data['products_tax'],
                                           'price' => $order_packslip_data['products_price'],
                                           'final_price' => $order_packslip_data['final_price']);
        $subindex = 0;
        $packslip_attributes_query = tep_db_query("SELECT products_options, products_options_values, options_values_price, price_prefix 
        from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " 
        WHERE orders_id = '" . (int)$oID . "' 
        and orders_products_id = '" . (int)$order_packslip_data['orders_products_id'] . "'");
        if (tep_db_num_rows($packslip_attributes_query)) {
          while ($packslip_attributes = tep_db_fetch_array($packslip_attributes_query)) {
            $packslip_products[$index]['packslip_attributes'][$subindex] = array('option' => $packslip_attributes['products_options'],
           'value' => $packslip_attributes['products_options_values'],
           'prefix' => $packslip_attributes['price_prefix'],
           'price' => $packslip_attributes['options_values_price']);
            $subindex++;
          }
        }
        $index++;
      }
      // multi-vendor shipping //eof
    }
    ?>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor=#000000>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
          </tr>
<?php
if (defined('MVS_STATUS') && MVS_STATUS == 'true') { 

  for ($i = 0, $n = sizeof($packslip_products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top" align="right">' . $packslip_products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $packslip_products[$i]['name'];

      if (isset($packslip_products[$i]['packslip_attributes']) && sizeof($packslip_products[$i]['packslip_attributes']) > 0) {
        for ($j = 0, $k = sizeof($packslip_products[$i]['packslip_attributes']); $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $packslip_products[$i]['packslip_attributes'][$j]['option'] . ': ' . $packslip_products[$i]['packslip_attributes'][$j]['value'];
          echo '</i></small></nobr>';
        }
      }

      echo '        </td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $packslip_products[$i]['model'] . '</td>' . "\n" .
           '      </tr>' . "\n";
    }


} else {

    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && sizeof($order->products[$i]['attributes']) > 0) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option_name'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          echo '</i></small></nobr>';
        }
      }

      echo '        </td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '      </tr>' . "\n";
    }
}
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
