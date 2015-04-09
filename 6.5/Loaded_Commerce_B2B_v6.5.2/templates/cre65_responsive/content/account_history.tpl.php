<?php
/*
  $Id: sss_accounthistory_.php,v 1.0.0.0 2008/05/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accounthistory', 'top');
?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADING_TITLE; ?></h1>
<div class="clearfix"></div>
<div class="col-sm-12">
  <table class="table table-striped margin-bottom-20 margin-top-10">
    <tbody>
      <?php
        $orders_total = tep_count_customer_orders();
        if ($orders_total > 0) {
          $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$_SESSION['customer_id'] . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id DESC";
          $history_split = new responsiveSplitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
          $history_query = tep_db_query($history_split->sql_query);
          while ($history = tep_db_fetch_array($history_query)) {
            $products_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$history['orders_id'] . "'");
            $products = tep_db_fetch_array($products_query);
            if (tep_not_null($history['delivery_name'])) {
              $order_type = TEXT_ORDER_SHIPPED_TO;
              $order_name = $history['delivery_name'];
            } else {
              $order_type = TEXT_ORDER_BILLED_TO;
              $order_name = $history['billing_name'];
            }
        ?>
        <tr class="cursor-pointer" onclick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&amp;' : '') . 'order_id=' . $history['orders_id'], 'SSL'); ?>'">
          <td>#<?php echo $history['orders_id']; ?><span class="hide-below-480 margin-left-15"><i class="fa fa-calendar"></i> <?php echo tep_date_short($history['date_purchased']); ?></span></td>
          <td class="hide-below-768"><?php echo TEXT_ORDER_PRODUCTS . ' ' . $products['count']; ?></td>
          <td><?php echo $history['orders_status_name']; ?></td>
          <td class="text-right"><?php echo strip_tags($history['order_total']); ?></td>
          <td class="text-right"><a class="btn btn-default btn-xs" href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&amp;' : '') . 'order_id=' . $history['orders_id'], 'SSL'); ?>"><i class="fa fa-search margin-right-5"></i><?php echo SMALL_IMAGE_BUTTON_VIEW; ?></a></td>
        </tr>
        <?php
          }
        }
      ?>  
    </tbody>
  </table>
</div>
<?php
  // RCI accounthistory menu
  echo $cre_RCI->get('accounthistory', 'middle');
?>
<div class="row">
  <div class="col-xm-6 col-sm-6 col-md-6 col-lg-6">
    <?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="btn btn-primary">' . IMAGE_BUTTON_BACK . '</a>'; ?>
  </div>
<?php
  if ($orders_total > 0) {
?>
  <div class="col-xm-6 col-sm-6 col-md-6 col-lg-6 text-right">
    <ul class="pagination margin-top-0 margin-bottom-0">
      <?php echo $history_split->display_responsive_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
    </ul>
  </div>
<?php
  }
?>
</div>    
<?php
  // RCI bottom
  echo $cre_RCI->get('accounthistory', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
?>