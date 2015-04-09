<?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('account', 'top');
  // RCI code eof
?>
<div class="account-login">
  <?php if ($messageStack->size('account') > 0) { echo '<p>' . $messageStack->output('account') . '</p>'; } ?>
  <?php if (tep_count_customer_orders() > 0) { ?>
  <h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo OVERVIEW_PREVIOUS_ORDERS; ?></h1>
  <div class="clearfix"></div>
  <div data-example-id="striped-table" class="margin-top-10">
    <table class="table table-striped margin-bottom-10">
      <tbody>
        <?php
          $orders_query = tep_db_query("select o.orders_id, o.date_purchased, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id desc limit 3");
          while ($orders = tep_db_fetch_array($orders_query)) {
            if (tep_not_null($orders['delivery_name'])) {
              $order_name = $orders['delivery_name'];
              $order_country = $orders['delivery_country'];
            } else {
              $order_name = $orders['billing_name'];
              $order_country = $orders['billing_country'];
            }
        ?>
        <tr class="cursor-pointer" onclick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL'); ?>'">
          <td><?php echo '#' . $orders['orders_id'] . '<span class="hide-below-480 margin-left-15"><i class="fa fa-calendar"></i> ' . tep_date_short($orders['date_purchased']) . '</span>'; ?></td>
          <td class="hide-below-768"><?php echo tep_output_string_protected($order_name); ?></td>
          <td><?php echo $orders['orders_status_name']; ?></td>
          <td><?php echo $orders['order_total']; ?></td>
          <td class="text-right"><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'] . '&fromacct', 'SSL'); ?>" class="btn btn-default btn-xs"><i class="fa fa-search margin-right-5"></i><?php echo SMALL_IMAGE_BUTTON_VIEW; ?></a></td>
        </tr>
        <?php
          }
        ?>
      </tbody>
    </table>
    <div class="text-right margin-right-5"><?php echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . SHOW_ALL_ORDERS . '</a>'; ?></div>
  </div>
  <?php } ?>
  <div class="span12">
    <h2 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo MY_ACCOUNT_SETTINGS_TITLE; ?></h1>
    <div class="clearfix"></div>
    <ul class="list2 pull-left margin-top-10 list-unstyled margin-left-20">
      <li><p><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MY_ACCOUNT_INFORMATION . '</a>'; ?></p></li>
      <li><p><?php echo ' <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MY_ACCOUNT_ADDRESS_BOOK . '</a>'; ?></p></li>
      <li><p><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MY_ACCOUNT_PASSWORD . '</a>'; ?></p></li>
      <li><p><?php echo ' <a href="' . tep_href_link('wishlist.php', '', 'SSL') . '">Wishlist</a>'; ?></p></li>
    </ul>
  </div>
  <div class="clearfix"></div>
  <div class="span12">
    <h2 class="col-sm-12 gry_box2 y_clr con_txt margin-top-0"><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></h1>
    <div class="clearfix"></div>
    <ul class="list2 pull-left margin-top-10 list-unstyled margin-left-20">
      <li><p><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a>'; ?></p></li>
    </ul>
  </div>
  <?php echo $cre_RCI->get('account', 'menu'); ?>
</div>
<?php
  // RCI code start
  echo $cre_RCI->get('account', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>