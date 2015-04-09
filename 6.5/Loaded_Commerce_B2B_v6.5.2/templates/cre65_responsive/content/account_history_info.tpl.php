<?php
  /*
  $Id: account_history_info.tpl.php,v 1.0 20090/04/06 23:38:03 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  */
  // RCI top start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accounthistoryinfo', 'top');
?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt">Order Info</h1>
<div class="clearfix"></div>
<div class="margin-top-10">
  <div class="col-md-6"><b><?php echo sprintf(HEADING_ORDER_NUMBER, $_GET['order_id']) . ' <small>(' . $order->info['orders_status'] . ')</small>'; ?></b><br><?php echo HEADING_ORDER_DATE . ' ' . tep_date_long($order->info['date_purchased']); ?></div>
  <div class="col-md-6 text-right"><?php echo HEADING_ORDER_TOTAL . ' ' . $order->info['total']; ?></div>
  <div class="clearfix"></div>
</div>  
<div class="table-responsive margin-top-20">
  <table class="table">
    <thead>
      <tr>
        <th colspan="3">Product</th>
      </tr>
    </thead>
    <tbody>
      <?php
        for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
          echo '          <tr>' . "\n" .
          '            <td  class="col-sm-9 col-md-7"><div class="media"><div class="media-body"><h5 class="media-heading">' . $order->products[$i]['name'].'</h5>' ; 
          echo '<ul>';
          //check for attibutes:
          $attributes_check_query = tep_db_query("SELECT *
            from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "
            WHERE orders_id = '" .(int)$_GET['order_id'] . "' 
            and orders_products_id = '" . $order->products[$i]['orders_products_id'] . "' ");
          if (tep_db_num_rows($attributes_check_query)) {
            while ($attributes = tep_db_fetch_array($attributes_check_query)) {
              echo '<li><strong>' . $attributes['products_options'] . ' :</strong> ' . $attributes['products_options_values'] .  $attributes['price_prefix'] . ' ' . $currencies->display_price($attributes['options_values_price'], tep_get_tax_rate($order->products[$i]['tax_class_id']), 1) . '</li>';
            }
          }
          // Begin RMA Returns System
          $return_link = '';
          if ($order->products[$i]['return'] == '1') {
            $rma_query_one = tep_db_query("SELECT returns_id FROM " . TABLE_RETURNS_PRODUCTS_DATA . " where products_id = '" . $order->products[$i]['id'] . "' and order_id = '" . $_GET['order_id'] . "'");
            while($rma_query = tep_db_fetch_array($rma_query_one)) {
              $rma_number_query = tep_db_query("SELECT rma_value FROM " . TABLE_RETURNS . " where returns_id = '" . $rma_query['returns_id'] . "'");
              $rma_result = tep_db_fetch_array($rma_number_query);
              $return_link .= '<b>' . TEXT_RMA . ' #&nbsp;<u><a href="' . tep_href_link(FILENAME_RETURNS_TRACK, 'action=returns_show&rma=' . $rma_result['rma_value'], 'NONSSL') . '">' . $rma_result['rma_value'] . '</a></u></b>';
            }
          }
          if (defined('DISPLAY_RMA_LINK') && DISPLAY_RMA_LINK == 'true') {
            $return_link .= '<a href="' . tep_href_link(FILENAME_RETURN, 'order_id=' . $_GET['order_id'] . '&product_id=' . ($order->products[$i]['id']), 'NONSSL') . '"><b><u>' . TEXT_RETURN_PRODUCT .'</a></u></b>';
          }
          // Don't show Return link if order is still pending or processing
          // You can change this or comment it out as best fits your store configuration
          if (($order->info['orders_status'] == 'Pending') OR ($order->info['orders_status'] == 'Processing')) {
            $return_link = '';
          }
          echo $return_link  . "</ul></div></div>";
          // End RMA Returns System                 
          echo '</td>';
          /*echo '</td><td class="main" valign="top" align="right">' .  $currencies->display_price($order->products[$i]['price'], (isset($products[$i]['tax_class_id']) ? tep_get_tax_rate($products[$i]['tax_class_id']) : 0), 1) . '</td>' . "\n";
          if (sizeof($order->info['tax_groups']) > 1) {
          echo '<td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
          }*/
          echo '<td class="col-sm-1 col-md-1 text-right" colspan="2">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' .
          '</tr>';
        }
      ?>   
      <?php
        for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
          echo '<tr>' . "\n" .
          '<td class="text-right">' . $order->totals[$i]['title'] . '</td>' . "\n" .
          '<td class="text-right">' . $order->totals[$i]['text'] . '</td>' . "\n" .
          '</tr>' . "\n";
        }
      ?>                  
    </tbody>
  </table>
</div><!--/table-responsive--> <h2 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADING_BILLING_INFORMATION; ?></h2>
<div class="clearfix"></div>
<div class="col-sm-6">        	
  <div class="panel panel-default"> 	
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo HEADING_BILLING_ADDRESS; ?></h3>
    </div>
    <div class="panel-body">
      <address>
        <?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?>
      </address>
    </div>
  </div>	
</div>
<div class="col-sm-6">        	
  <div class="panel panel-default"> 	
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo HEADING_DELIVERY_ADDRESS; ?></h3>
    </div>
    <div class="panel-body">
      <address>
        <?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?>
      </address>
    </div>
  </div>	
</div>
<div class="clearfix"></div>
<div class="col-sm-4">
  <div class="panel panel-default"> 	
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo HEADING_PAYMENT_METHOD; ?></h3>
    </div>
    <div class="panel-body">
      <?php echo $order->info['payment_method']; ?>
    </div>
  </div>
</div>  
<div class="col-sm-4">
  <div class="panel panel-default"> 	
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo HEADING_SHIPPING_METHOD; ?></h3>
    </div>
    <div class="panel-body">
      <?php echo $order->info['shipping_method']; ?>
    </div>
  </div>
</div>
<div class="col-sm-4">
  <div class="panel panel-default"> 	
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo HEADING_ORDER_HISTORY; ?></h3>
    </div>
    <div class="panel-body">
      <?php
        $statuses_query = tep_db_query("select os.orders_status_name, osh.date_added, osh.comments from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.customer_notified <> 0 and osh.orders_id = '" . (int)$_GET['order_id'] . "' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' order by osh.date_added");
        while ($statuses = tep_db_fetch_array($statuses_query)) {
          echo  tep_date_short($statuses['date_added']) . '</br>' . $statuses['orders_status_name'] . '</br>' . (empty($statuses['comments']) ? '&nbsp' : nl2br(tep_output_string_protected($statuses['comments'])));
        }
      ?> 				
    </div>
  </div>
</div>
<?php 
  echo $cre_RCI->get('accounthistoryinfo', 'bottominsidetable'); 
  if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . FILENAME_DOWNLOADS);
?>
<div class="margin-top-10">
  <div class="col-sm-12">
    <div class="col-md-6"><?php echo '<a class="btn btn-primary" href="' . ((!isset($_GET['fromacct'])) ? tep_href_link(FILENAME_ACCOUNT_HISTORY, tep_get_all_get_params(array('order_id')), 'SSL') : tep_href_link(FILENAME_ACCOUNT, '', 'SSL')) . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></div>
    <div class="col-md-6 text-right"><?php echo '<a class="btn btn-primary" href="javascript:popupResponsiveWindow(\'' .  tep_href_link('printresponsiveorder.php', tep_get_all_get_params(array('order_id')) . 'order_id=' . (int)$_GET['order_id'], 'NONSSL') . '\')">' . tep_template_image_button('button_printorder.gif', IMAGE_BUTTON_PRINT_ORDER) . '</a>'; ?></div>
  </div> 
</div>         
<?php
  // RCI bottom
  echo $cre_RCI->get('accounthistoryinfo', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
?>
