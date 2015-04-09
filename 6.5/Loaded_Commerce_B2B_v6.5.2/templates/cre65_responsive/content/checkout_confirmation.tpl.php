<?php
/*
  $Id: checkout_confirmation.tpl.php,v 1.0.0.0 2008/01/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('checkoutconfirmation', 'top');
  // RCI code eof 
?>    
<div class="col-sm-12"> 
  <h1 class="col-lg-12 gry_box2 y_clr">Order Confirmation</h1>
  <div class="clearfix"></div>
  <div class="table-responsive">
    <table class="table cartable">
      <thead>
        <tr>
          <th colspan="3">Product <a href="<?=tep_href_link(FILENAME_SHOPPING_CART)?>" class="pull-right"><i class="fa fa-edit fa-2x"></i></a></th>
        </tr>
      </thead>
      <?php
        for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
          echo '          <tr>' . "\n" .
          '            <td class="col-sm-9 col-md-7"><div class="media"><div class="media-body"><h5 class="media-heading">' . $order->products[$i]['name'].'</h5><ul>';
          if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
            for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
              echo '<li><strong> - ' . $order->products[$i]['attributes'][$j]['option'] . ':</strong> ' . $order->products[$i]['attributes'][$j]['value'] . ' ' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1)  . '</li>';
            }
          }
          echo '</ul></td>' . "\n";
          if (sizeof($order->info['tax_groups']) > 1) echo '<td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
          echo '            <td class="col-sm-1 col-md-1 text-right" colspan="2">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
          '          </tr>' . "\n";
        }
        if (MODULE_ORDER_TOTAL_INSTALLED) {
          $order_total_modules->process();
          echo $order_total_modules->output();
        }
        // RCI code start
        echo $cre_RCI->get('checkoutconfirmation', 'display');
        // RCI code eof     
      ?>      
    </table>
  </div>
  <div class="row">
    <div class="col-sm-6">        	
      <div class="panel panel-default"> 	
        <div class="panel-heading">
          <h3 class="panel-title">Billing Address</h3>
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
          <h3 class="panel-title">
            <?php 
              //if ($cre_RCO->get('checkoutconfirmation', 'editdeliveryaddresslink') !== true) 
              //{
              echo HEADING_DELIVERY_ADDRESS; 
              //}
            ?>
          </h3>
        </div>
        <div class="panel-body">
          <address>
            <?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?>
          </address>
        </div>
      </div>
    </div>
  </div>
  <?php
    if (is_array($payment_modules->modules)) {
      if ($confirmation = $payment_modules->confirmation()) {
        $col_size = '4';
      } else {
        $col_size = '6';
      }
    }
  ?>
  <div class="row">
    <div class="col-sm-<?php echo $col_size; ?>">
      <div class="panel panel-default"> 	
        <div class="panel-heading">
          <h3 class="panel-title"><?=HEADING_PAYMENT_METHOD?></h3>
        </div>
        <div class="panel-body">
          <p><?php echo $order->info['payment_method']; ?></p>
          <a href="<?=tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL')?>"><i class="fa fa-edit fa-2x"></i></a>
        </div>
      </div>
    </div>
    <?php
      if (is_array($payment_modules->modules)) {
        if ($confirmation = $payment_modules->confirmation()) {
          $payment_info = $confirmation['title'];
          if (!isset($_SESSION['payment_info'])) $_SESSION['payment_info'] = $payment_info;
        ?>    
    <div class="col-sm-<?php echo $col_size; ?>">
      <div class="panel panel-default"> 	
        <div class="panel-heading">
          <h3 class="panel-title">Payment Information</h3>
        </div>
        <div class="panel-body">
          <p><?php echo $confirmation['title']; ?></p>
          <address>
            <?php
              for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
                echo $confirmation['fields'][$i]['title'].' &nbsp;'. $confirmation['fields'][$i]['field'];
              }
            ?>
          </address>
        </div>
      </div>
    </div>
    <?php }} ?>
    <?php if ($order->info['shipping_method']) { ?>
    <div class="col-sm-<?php echo $col_size; ?>">
      <div class="panel panel-default"> 	
        <div class="panel-heading">
          <h3 class="panel-title">
            <?php 
              if ($cre_RCO->get('checkoutconfirmation', 'editshippingmethodlink') !== true) {
                echo HEADING_SHIPPING_METHOD; 
              }
            ?>
          </h3>
        </div>
        <div class="panel-body">
          <address>
            <?php echo $order->info['shipping_method']; ?>
          </address>
        </div>
      </div>
    </div> 
    <?php } ?>  
    <div class="clearfix"></div>
  </div>
  <div class="row">
    <?php if (tep_not_null($order->info['comments'])) { ?>
    <div class="col-sm-12">
      <div class="panel panel-default"> 	
        <div class="panel-heading">
          <h3 class="panel-title">
            <?php echo HEADING_ORDER_COMMENTS; ?>
          </h3>
        </div>
        <div class="panel-body">
          <address>
            <?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?>
          </address>
        </div>
      </div>
    </div> 
    <?php } ?>
  </div>
  <?php
    // added for PPSM
    $process_button_string = '';
    if (isset($_POST['payment']) && $_POST['payment'] == 'paypal_wpp_dp') { 
      $process_button_string = process_dp_button();
      if (defined('MODULE_PAYMENT_CRESECURE_TEST_MODE') && MODULE_PAYMENT_CRESECURE_TEST_MODE == 'True') {  
        //$this->form_action_url = 'https://dev-cresecure.net/securepayments/a1/cc_collection.php';  // cre only internal test url
        $form_action_url = 'https://sandbox-cresecure.net/securepayments/a1/cc_collection.php';  // sandbox url
      } else {   
        $form_action_url = 'https://cresecure.net/securepayments/a1/cc_collection.php';  // production url
      } 
    } else if (isset($$payment->form_action_url)) {
      $form_action_url = $$payment->form_action_url;
    } else {  
      $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
    }
    if (ACCOUNT_CONDITIONS_REQUIRED == 'false' ) {
      echo tep_draw_form('checkout_confirmation', $form_action_url, 'post','enctype="multipart/form-data"');
    } else {
      echo tep_draw_form('checkout_confirmation', $form_action_url, 'post','onsubmit="return checkCheckBox(this)" enctype="multipart/form-data"');
    }
    // added for PPSM
    if (isset($_POST['payment']) && $_POST['payment'] == 'paypal_wpp_dp') {
      echo $process_button_string;
    } else if (is_array($payment_modules->modules)) {
      echo $payment_modules->process_button();
    }
  ?>
  <div class="row">
    <div class="col-sm-6">
      <?php echo '<h5>'.TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</h5><p>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE.'</p>'; ?>
    </div>
    <div class="col-sm-6 text-right">
      <button class="btn btn-primary">Continue</button>
      <?php 
        /*$shweight	=	0;
        for ($i=0, $n=sizeof($order->products); $i<$n; $i++) 
        {
        $shweight	=	$shweight+$order->products[$i]['weight'];
        }
        $max_weight_error_res	=	tep_db_fetch_array(tep_db_query("select configuration_value from configuration where configuration_id='3574'"));
        $max_weight_res		=	tep_db_fetch_array(tep_db_query("select configuration_value from configuration where configuration_id='3577'"));
        if($shweight <= $max_weight_res['configuration_value'])
        { 
        echo '<button class="btn btn-primary">Continue</button>';
        }
        else
        {
        echo '<a href="'.tep_href_link("shopping_cart.php?errormsg=".$max_weight_error_res['configuration_value']).'"><input type="button" class="btn btn-primary" value="Continue" /></a>';
        }*/	
      ?>
    </div>
  </div>  
  <?php
    //RCI start
    echo $cre_RCI->get('checkoutconfirmation', 'menu');
    if ($cre_RCO->get('checkoutconfirmation', 'checkoutbar') !== true) {               
    ?>   
    <div class="stepwizard">
      <div class="stepwizard-row">
        <div class="stepwizard-step">
          <?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="btn btn-danger btn-circle">1</a>'; ?>
          <p><?php echo 'Delivery Information'; ?></p>
        </div>
        <div class="stepwizard-step">
          <?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="btn btn-danger btn-circle">2</a>'; ?>
          <p><?php echo 'Payment Information'; ?></p>
        </div>
        <div class="stepwizard-step">
          <?php echo '<button class="btn btn-primary btn-circle" type="button">3</button>'; ?>
          <p><?php echo 'Confirmation'; ?></p>
        </div>
        <div class="stepwizard-step">
          <?php echo '<button class="btn btn-danger btn-circle" type="button">4</button>'; ?>
          <p><?php echo 'Finished!'; ?></p>
        </div>
      </div>    
    </div>
    <?php
    }
  ?>
  </form>
</div>
<?php 
  // RCI code start
  echo $cre_RCI->get('checkoutconfirmation', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>