<?php 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('checkoutpayment', 'top');
  // RCI code eof
  echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="return check_form();"');

  if (isset($_GET['error_message']) && tep_not_null($_GET['error_message'])) {
    //echo 'x_Invoice_Num ' . $x_Invoice_Num;
    $sql_data_array = array('orders_id' =>  (isset($order_id1) ? $order_id1 : 0),
                            'orders_status_id' => '0',
                            'date_added' => 'now()',
                            'customer_notified' => '0',
                            'comments' => $_GET['error_message']);
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
  }
?>
<h1 class="col-lg-12 gry_box2 y_clr con_txt">Payment Information</h1>
<div class="clearfix"></div>
<?php
  if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
    $trans_error	=	tep_db_fetch_array(tep_db_query("select configuration_value from configuration where configuration_id=3563"));
    if ($error['title'] != 'Coupon Redemption') {
      echo '<p class="bg-danger margin-top-20">';
      echo tep_output_string_protected($error['title']).'<br>';  
      echo tep_output_string_protected($error['error']).'<br>'; 
      echo $trans_error['configuration_value'].'<br>';
      echo '</p>'; 
    }
    //print_r($error);
  } 
?>
<div class="panel panel-default"> 	
  <div class="panel-heading">
    <h3 class="panel-title">Discount Coupons</h3>
  </div>
  <div class="panel-body neg-margin-20">
    <div class="col-sm-12">
      <div class="table-responsive">
      <?php
        if ($order_total_modules->credit_selection() != '') {
          echo $order_total_modules->credit_selection();//ICW ADDED FOR CREDIT CLASS SYSTEM 
        }			
      ?>
      </div> 
    </div>     
  </div>
</div>
<div class="panel panel-default"> 	
  <div class="panel-heading">
    <h3 class="panel-title">Billing Address</h3>
  </div>
  <div class="panel-body">
    <p>Please choose from your address book where you would like the invoice to be sent to.</p>
    <!--<div class="col-sm-6">-->
    <address>
      <?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br>'); ?>
    </address>
    <?php echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">' . tep_template_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a>'; 
    ?>
    <!--</div>  
    <div class="col-sm-6">
    <?php
      //if ($order_total_modules->credit_selection()!='' ) 
      //{
      //echo $order_total_modules->credit_selection();//ICW ADDED FOR CREDIT CLASS SYSTEM 
      //}			
    ?>
    </div>-->
  </div>
</div>
<div class="panel panel-default">   
  <div class="panel-heading">
    <h3 class="panel-title">Payment Method</h3>
  </div>
  <div class="panel-body neg-margin-left-10 pay-method-selection">
  <?php
    //echo 'authtype:'.$order->info['authorizetype'];
    //echo '<pre>'; print_r($_SESSION['shipping']['id']);
    if ($order->info['total'] != 0){
      // RCO start
      if ($cre_RCO->get('checkoutpayment', 'paymentmodule') !== true) {}
      // RCO end
    } else {
      echo TEXT_ORDER_TOTAL_ZERO;
      $_SESSION['payment'] = 'freecharger';
    }
    // BOF: Lango Added for template MOD
    // EOF: Lango Added for template MOD
    // RCI code start
    echo $cre_RCI->get('checkoutpayment', 'billingtableright');
    // RCI code eof  
  ?>
  </div>
</div>
<div class="panel panel-default">   
  <div class="panel-heading">
    <h3 class="panel-title">Order Comments</h3>
  </div>
  <div class="panel-body">
    <label>Add Comments About Your Order</label>
    <?php echo tep_draw_textarea_field('comments', 'soft', '60', '5', isset($_SESSION['comments']) ? $_SESSION['comments'] : '',' class="form-control"'); ?>
  </div>
</div>   
<?php 
  echo $cre_RCI->get('checkoutpayment', 'insideformabovebuttons'); 
?> 
<div class="row">
  <div class="col-sm-6">
    <h5><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE; ?></h5>
    <p><?php echo TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></p>
  </div>
  <div class="col-sm-6 text-right">
    <button class="btn btn-primary">Continue</button>
    <?php 
      /*$max_weight_error_res	=	tep_db_fetch_array(tep_db_query("select configuration_value from configuration where configuration_id='3574'"));
      $max_weight_res		=	tep_db_fetch_array(tep_db_query("select configuration_value from configuration where configuration_id='3577'"));
      if ($total_weight <= $max_weight_res['configuration_value']) { 
        echo '<button class="btn btn-primary">Continue</button>';
      } else {
        echo '<a href="'.tep_href_link("shopping_cart.php?errormsg=".$max_weight_error_res['configuration_value']).'"><input type="button" class="btn btn-primary" value="Continue" /></a>';
      }*/	
    ?>
  </div>
</div> 
<?php echo $cre_RCI->get('checkoutpayment', 'insideformbelowbuttons'); ?> 
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
</div>
</form>
<script>
  $(document).ready(function() {
    $(".pay-method-selection").find(".main").css("padding", "10px 0 10px 0");
  });
</script>
<?php
  // RCI code start
  echo $cre_RCI->get('checkoutpayment', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>