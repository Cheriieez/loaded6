<?php
if (MODULE_ADDONS_OPT_STATUS == 'True') {
  $shipping_query = tep_db_query("select shipment_track_num, shipment_method from " . TABLE_ORDERS . " where orders_id = '" . (int)$_GET['order_id'] . "'");
  $shipping = tep_db_fetch_array($shipping_query);

  if (tep_not_null($shipping['shipment_method'])) {
    $track_url = '?action=track&tracknumbers=' . urlencode($shipping['shipment_track_num']). '&type=' . preg_replace('/\s+/', '_', $shipping['shipment_method']);
?>
<table width="100%" cellspacing="" cellpadding="">
  <tr>
    <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="3">
	       <tr>
	         <td class="pageHeading">Order Package Tracking</td>
	       </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td valign="top" WIDTH="50%">
      <table border="0" width="100%" cellspacing="0" cellpadding="5">
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="5" style="border:1px solid #d3d3d3;">
            <tr>
              <td class="main"><b><?php echo HEADING_SHIPPING_METHOD; ?>:&nbsp;</b><?php echo $shipping['shipment_method']; ?></td>
            </tr>
            <tr>
              <td class="main"><b>Tracking No:&nbsp;</b><?php echo $shipping['shipment_track_num']; ?></td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
            </tr>
            <tr>
            <td class="main">
              <?php echo '<a target="_blank" href="' . tep_href_link(FILENAME_POPUP_TRACKER . $track_url, tep_get_all_get_params(array('order_id')), 'SSL') . '">' . tep_template_image_button('button_track_shipment.gif', IMAGE_BUTTON_TRACK_SHIPMENT) . '</a>'; ?>
            </td>
            </tr>
          </table></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php
  }
}
?>