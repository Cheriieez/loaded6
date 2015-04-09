<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('checkoutpaymentaddress', 'top');
// RCI code eof
?>
<style>
.ck_pay_add .form-horizontal .form-group { margin-left:0px; margin-right:0px;}
.cart-tbl th { font-weight:500;}
label { font-weight:500; }
</style>
<div class="ck_pay_add">
<?php
echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'), 'post', 'class="form-horizontal" onSubmit="return check_form_optional(checkout_address);"'); ?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt">Payment Information</h1>
<div class="clearfix"></div>
<?php
  if ($messageStack->size('checkout_address') > 0) 
  {
 	echo $messageStack->output('checkout_address');  
  }
  if ($process == false) {
?>
	<div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo TABLE_HEADING_PAYMENT_ADDRESS; ?></h3>
      </div>
      <div class="panel-body">
		<?php echo TEXT_SELECTED_PAYMENT_DESTINATION; ?>
        <address>
		<?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br>'); ?>
        </address>
        </div>
    </div>  
    <table class="table cart-tbl">
        <thead>
            <tr>
                <th><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES.'<br>'.TEXT_SELECT_OTHER_PAYMENT_DESTINATION;?></th>
                <th class="text-right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b>'; ?> </th>
            </tr>
        </thead>   
        <tbody> 
<?php
    if ($addresses_count > 1) {

      $radio_buttons = 0;
      $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_SESSION['customer_id'] . "'");
      while ($addresses = tep_db_fetch_array($addresses_query)) {
        $format_id = tep_get_address_format_id($addresses['country_id']);

       if ($addresses['address_book_id'] == $_SESSION['billto']) {
          echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
        } else {
          echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
        }
?>
                    <td class="col-sm-10 col-md-7"><b><?php echo $addresses['firstname'] . ' ' . $addresses['lastname']; ?></b>
                    	<?php echo tep_address_format($format_id, $addresses, true, ' ', ', '); ?>
                    </td>
                    <td class="col-sm-1 col-md-1 text-right"><?php echo tep_draw_radio_field('address', $addresses['address_book_id'], ($addresses['address_book_id'] == $_SESSION['billto'])); ?></td>
                   </tr>

<?php
        $radio_buttons++;
      }
?>
      </tbody></table>
<?php
    }
  }
  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) 
  {
       if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . 'checkout_new_address.php')) {
          require(TEMPLATE_FS_CUSTOM_MODULES . 'checkout_new_address.php');
        } else {
          require(DIR_WS_MODULES . 'checkout_new_address.php');
        }
  }
// RCI code start
echo $cre_RCI->get('checkoutpaymentaddress', 'menu');
// RCI code eof
?>
<div class="row">
    <div class="col-sm-6"><h5>Continue Checkout Procedure</h5><p>to confirm this order.</p></div>
    <div class="col-sm-6 text-right">
        <?php echo tep_draw_hidden_field('action', 'submit'); ?><button class="btn btn-primary">Continue</button>
    </div>
</div>
<?php
  if ($process == true) {
?>
      <tr>
        <td><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
      </tr>
<?php
  }
?>
<div class="stepwizard">
            <div class="stepwizard-row">
                <div class="stepwizard-step">
                    <?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="btn btn-danger btn-circle">1</a>'; ?>
                    <p><?php echo 'Delivery Information'; ?></p>
                </div>
            
                <div class="stepwizard-step">
                    <button class="btn btn-primary btn-circle" type="button">2</button>
                    <p><?php echo CHECKOUT_BAR_PAYMENT; ?></p>
                </div>
                <div class="stepwizard-step">
                    <button class="btn btn-danger btn-circle" type="button">3</button>
                    <p><?php echo CHECKOUT_BAR_CONFIRMATION; ?></p>
                </div>
                <div class="stepwizard-step">
                    <button class="btn btn-danger btn-circle" type="button">4</button>
                    <p><?php echo CHECKOUT_BAR_FINISHED; ?></p>
                </div> 
            </div>
        </div></form>
 </div>
<?php 
// RCI code start
echo $cre_RCI->get('checkoutpaymentaddress', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>