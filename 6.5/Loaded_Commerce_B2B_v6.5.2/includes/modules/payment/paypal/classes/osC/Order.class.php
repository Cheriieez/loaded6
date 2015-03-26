<?php
/*
  $Id: Order.class.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

class PayPal_osC_Order {

  function PayPal_osC_Order() {
  }

  function setCommonVars($osC) {
    $this->setContentType($osC['content_type']);
    $this->setPaymentTitle($osC['payment_title']);
    $this->setLanguage($osC['language']);
    $this->setLanguageID($osC['language_id']);
    $this->setBillTo($osC['billto']);
    $this->setSendTo($osC['sendto']);
    $this->currency = $osC['currency'];
    $this->currency_value = $osC['currency_value'];
    $this->affiliate_id = $osC['affiliate_id'];
    $this->affiliate_clickthroughs_id = $osC['affiliate_clickthroughs_id'];
    $this->affiliate_date = $osC['affiliate_date'];
    $this->affiliate_browser = $osC['affiliate_browser'];
    $this->affiliate_ipaddress = $osC['affiliate_ipaddress'];
  }

  function loadTransactionSessionInfo($txn_sign) {
    $txn_signature = tep_db_prepare_input($txn_sign);
    $orders_session_query = tep_db_query("select orders_id, content_type, payment_title, language, language_id, billto, sendto, currency, currency_value, payment_amount, payment_currency, affiliate_id, affiliate_clickthroughs_id, affiliate_date, affiliate_browser, affiliate_ipaddress from " . TABLE_ORDERS_SESSION_INFO . " where txn_signature ='" . tep_db_input($txn_signature) . "' limit 1");
    if(tep_db_num_rows($orders_session_query)) {
      $orders_session = tep_db_fetch_array($orders_session_query);
      $this->setCommonVars($orders_session);
      $this->setOrderID($orders_session['orders_id']);
      $this->payment_amount = $orders_session['payment_amount'];
      $this->payment_currency = $orders_session['payment_currency'];
    }
  }

  function loadOrdersSessionInfo() {
    $orders_session_query = tep_db_query("select content_type, payment_title, language, language_id, billto, sendto, currency, currency_value, affiliate_id, affiliate_clickthroughs_id, affiliate_date, affiliate_browser, affiliate_ipaddress from " . TABLE_ORDERS_SESSION_INFO . " where orders_id ='" . (int)$this->orderID . "' limit 1");
    if(tep_db_num_rows($orders_session_query)) {
      $orders_session = tep_db_fetch_array($orders_session_query);
      $this->setCommonVars($orders_session);
    }
  }

  function removeOrdersSession() {
    tep_db_query("delete from " . TABLE_ORDERS_SESSION_INFO . " where orders_id = '" . (int)$this->orderID . "'");
  }

  function updateOrderStatus($order_status = MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID) {
    // update the order's status
    tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($order_status) . "', last_modified = now() where orders_id = '" . (int)$this->orderID . "'");
  }

  function updateProducts(&$order) {
    // initialized for the email confirmation
    $this->products_ordered = '';
    $subtotal = 0;
    $total_tax = 0;
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    // Stock Update - Joao Correia
      if (STOCK_LIMITED == 'true') {
        if (DOWNLOAD_ENABLED == 'true') {
          $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                              FROM " . TABLE_PRODUCTS . " p
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               ON p.products_id=pa.products_id
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                               ON pa.products_attributes_id=pad.products_attributes_id
                              WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
          // Will work with only one option for downloadable products
          // otherwise, we have to build the query dynamically with a loop
          $products_attributes = $order->products[$i]['attributes'];
          if (is_array($products_attributes)) {
            $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
          }
          $stock_query = tep_db_query($stock_query_raw);
        } else {
          $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        }
        if (tep_db_num_rows($stock_query) > 0) {
          $stock_values = tep_db_fetch_array($stock_query);
          // do not decrement quantities if products_attributes_filename exists
          if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
            $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
          } else {
            $stock_left = $stock_values['products_quantity'];
          }
          tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
            tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          }
        }
      }

      // Update products_ordered (for bestsellers list)
      tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

      //------insert customer choosen option to order--------
      $attributes_exist = '0';
      $products_ordered_attributes = '';
      if (isset($order->products[$i]['attributes'])) {
        $attributes_exist = '1';
        for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
          if (DOWNLOAD_ENABLED == 'true') {
            $attributes_query = "select poptt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                                 from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT . " poptt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                 left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                  on pa.products_attributes_id=pad.products_attributes_id
                                 where pa.products_id = '" . $order->products[$i]['id'] . "'
                                  and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                  and pa.options_id = popt.products_options_id
                                  and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                  and pa.options_values_id = poval.products_options_values_id
          and poptt.products_options_text_id = popt.products_options_id
                                  and poptt.language_id = '" . $this->languageID . "'
                                  and poval.language_id = '" . $this->languageID . "'";
            $attributes = tep_db_query($attributes_query);
          } else {
            $attributes = tep_db_query("select poptt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT . " poptt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and poptt.products_options_text_id = popt.products_options_id and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and poptt.language_id = '" . $this->languageID . "' and poval.language_id = '" . $this->languageID . "'");
          }
          $attributes_values = tep_db_fetch_array($attributes);
          if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename']) ) {
            $sql_data_array = array('orders_id' => $this->orderID,
                                    'orders_products_id' => $order->products[$i]['orders_products_id'],
                                    'orders_products_filename' => $attributes_values['products_attributes_filename'],
                                    'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                                    'download_count' => $attributes_values['products_attributes_maxcount']);
            tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
          }
          $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
        }
      }
      //------insert customer choosen option eof ----
      $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
      $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
      $total_cost += $total_products_price;

      //$currency_price = $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']);
      $products_ordered_price = $this->displayPrice($order->products[$i]['final_price'],$order->products[$i]['tax'],$order->products[$i]['qty']);

      $this->products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $products_ordered_price . $products_ordered_attributes . "\n";
    }
  }

  function displayPrice($amount, $tax, $qty = 1) {
    global $currencies;

    if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0) ) {
      return $currencies->format(tep_add_tax($amount, $tax) * $qty, true, $this->currency, $this->currency_value);
    }

    return $currencies->format($amount * $qty, true, $this->currency, $this->currency_value);
  }


  function getCustomerComments() {
    $orders_history_query = tep_db_query("select comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$this->orderID . "' order by date_added limit 1");
    if (tep_db_num_rows($orders_history_query)) {
      $orders_history = tep_db_fetch_array($orders_history_query);
      return $orders_history['comments'];
    }
    return false;
  }


  function setLanguage($lng) {
    $this->language = $lng;
  }

  function setLanguageID($id) {
    $this->languageID = $id;
  }

  function setOrderID($id) {
    $this->orderID = $id;
  }

  function setBillTo($id) {
    $this->billTo = $id;
  }

  function setSendTo($id) {
    $this->sendTo = $id;
  }

  function setPaymentTitle($title) {
    $this->paymentTitle = $title;
  }

  function setContentType($type) {
    $this->contentType = $type;
  }

  function setCheckoutProcessLanguageFile($filename) {
    $this->checkoutProcessLanguageFile = $filename;
  }

  function setAccountHistoryInfoURL($url) {
    $this->accountHistoryInfoURL= $url;
  }


   function notifyCustomer(&$order) {
    $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
    tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified) values (" . (int)$this->orderID . ", '" . MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID . "', now(), '" . $customer_notification . "')");

    // lets start with the email confirmation
    include($this->checkoutProcessLanguageFile);

    $email_order = STORE_NAME . "\n" .
                   EMAIL_SEPARATOR . "\n" .
                   EMAIL_TEXT_ORDER_NUMBER . ' ' . (int)$this->orderID . "\n" .
                   EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$this->orderID, 'SSL', false) . "\n" .
                   EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

    $customerComments = $this->getCustomerComments();

    if ($customerComments)
      $email_order .= tep_db_output($customerComments) . "\n\n";


    $email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
                    EMAIL_SEPARATOR . "\n" .
                    $this->products_ordered .
                    EMAIL_SEPARATOR . "\n";

    for ($i=0, $n=sizeof($order->totals); $i<$n; $i++)
      $email_order .= strip_tags($order->totals[$i]['title']) . ' ' . strip_tags($order->totals[$i]['text']) . "\n";


    if ($order->content_type != 'virtual') {

      $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .
                           EMAIL_SEPARATOR . "\n";

      if ($order->delivery['company'])
        $email_order .= $order->delivery['company'] . "\n";

      $email_order .= $order->delivery['name'] . "\n" .
                      $order->delivery['street_address'] . "\n";

      if ($order->delivery['suburb'])
        $email_order .= $order->delivery['suburb'] . "\n";

      $email_order .= $order->delivery['city'] . ', ' . $order->delivery['postcode'] . "\n";

      if ($order->delivery['state'])
        $email_order .= $order->delivery['state'] . ', ';

      $email_order .= $order->delivery['country'] . "\n";
    }

    $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
                           EMAIL_SEPARATOR . "\n";

    if ($order->billing['company'])
      $email_order .= $order->billing['company'] . "\n";

    $email_order .= $order->billing['name'] . "\n" .
                    $order->billing['street_address'] . "\n";

    if ($order->billing['suburb'])
      $email_order .= $order->billing['suburb'] . "\n";

    $email_order .= $order->billing['city'] . ', ' . $order->billing['postcode'] . "\n";

    if ($order->billing['state'])
      $email_order .= $order->billing['state'] . ', ';

    $email_order .= $order->billing['country'] . "\n\n";

    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
                    EMAIL_SEPARATOR . "\n" .
                    $this->paymentTitle . "\n\n";

    tep_mail($order->customer['name'],$order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');

    // send emails to other people
    if (SEND_EXTRA_ORDER_EMAILS_TO != '')
      tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT,  $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');


    // multi-vendor shipping
    if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
      if ((defined('MVS_VENDOR_EMAIL_WHEN') && MVS_VENDOR_EMAIL_WHEN == 'Catalog') || 
          (defined('MVS_VENDOR_EMAIL_WHEN') && MVS_VENDOR_EMAIL_WHEN == 'Both') ) {

        $status=$order->info['orders_status_number'];

        if (isset($status)) {

           
          $order_sent_query = tep_db_query("select vendor_order_sent, vendors_id from " . TABLE_ORDERS_SHIPPING . " where orders_id = '" . (int)$this->orderID . "'");

          while ($order_sent_data = tep_db_fetch_array($order_sent_query)) {

            $order_sent_ckeck = $order_sent_data['vendor_order_sent'];
            $vendors_id = $order_sent_data['vendors_id'];

            if ($order_sent_ckeck == 'no') {
              $status='';
              $oID=(int)$this->orderID;
              $vendor_order_sent = false;
              $status= $order->info['orders_status_number'];
     


              $this->vendors_email($vendors_id, $oID, $status, $vendor_order_sent);
            }
          }
        }
      }
    }
    // multi-vendor shipping eof//


  }//end function notifyCustomer

  //MVS Start  
  function vendors_email($vendors_id, $oID, $status, $vendor_order_sent) {
    $vendor_order_sent =  false;
    $debug='no';
    $vendor_order_sent = 'no';
    $index2 = 0;
    //let's get the Vendors
    $vendor_data_query = tep_db_query("select v.vendors_id, v.vendors_name, v.vendors_email, v.vendors_contact, v.vendor_add_info, v.vendor_street, v.vendor_city, v.vendor_state, v.vendors_zipcode, v.vendor_country, v.account_number, v.vendors_status_send, os.shipping_module, os.shipping_method, os.shipping_cost, os.shipping_tax, os.vendor_order_sent from " . TABLE_VENDORS . " v,  " . TABLE_ORDERS_SHIPPING . " os where v.vendors_id=os.vendors_id and v.vendors_id='" . $vendors_id . "' and os.orders_id='" . (int)$oID . "' and v.vendors_status_send='" . $status . "'");

    while ($vendor_order = tep_db_fetch_array($vendor_data_query)) {
      $vendor_products[$index2] = array('Vid' => $vendor_order['vendors_id'],
                                        'Vname' => $vendor_order['vendors_name'],
                                        'Vemail' => $vendor_order['vendors_email'],
                                        'Vcontact' => $vendor_order['vendors_contact'],
                                        'Vaccount' => $vendor_order['account_number'],
                                        'Vstreet' => $vendor_order['vendor_street'],
                                        'Vcity' => $vendor_order['vendor_city'],
                                        'Vstate' => $vendor_order['vendor_state'],
                                        'Vzipcode' => $vendor_order['vendors_zipcode'],
                                        'Vcountry' => $vendor_order['vendor_country'],
                                        'Vaccount' => $vendor_order['account_number'],                               
                                        'Vinstructions' => $vendor_order['vendor_add_info'],
                                        'Vmodule' => $vendor_order['shipping_module'],                               
                                        'Vmethod' => $vendor_order['shipping_method']);
      if ($debug == 'yes') {
        echo 'The vendor query: ' . $vendor_order['vendors_id'] . '<br>';
      }
      $index = 0;
      $vendor_orders_products_query = tep_db_query("select o.orders_id, o.orders_products_id, o.products_model, o.products_id, o.products_quantity, o.products_name, p.vendors_id,  p.vendors_prod_comments, p.vendors_prod_id, p.vendors_product_price from " . TABLE_ORDERS_PRODUCTS . " o, " . TABLE_PRODUCTS . " p where p.vendors_id='" . (int)$vendor_order['vendors_id'] . "' and o.products_id=p.products_id and o.orders_id='" . $oID . "' order by o.products_name");
      while ($vendor_orders_products = tep_db_fetch_array($vendor_orders_products_query)) {
        $vendor_products[$index2]['vendor_orders_products'][$index] = array(
                                  'Pqty' => $vendor_orders_products['products_quantity'],
                                  'Pname' => $vendor_orders_products['products_name'],
                                  'Pmodel' => $vendor_orders_products['products_model'],
                                  'Pprice' => $vendor_orders_products['products_price'],
                                  'Pvendor_name' => $vendor_orders_products['vendors_name'],
                                  'Pcomments' => $vendor_orders_products['vendors_prod_comments'],
                                  'PVprod_id' => $vendor_orders_products['vendors_prod_id'],
                                  'PVprod_price' => $vendor_orders_products['vendors_product_price'],
                                  'spacer' => '-');
        if ($debug == 'yes') {
          echo 'The products query: ' . $vendor_orders_products['products_name'] . "\n";
        }
        $subindex = 0;
        $vendor_attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$vendor_orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($vendor_attributes_query)) {
          while ($vendor_attributes = tep_db_fetch_array($vendor_attributes_query)) {
            $vendor_products[$index2]['vendor_orders_products'][$index]['vendor_attributes'][$subindex] = array('option' => $vendor_attributes['products_options'],
                                                                                                                'value' =>          $vendor_attributes['products_options_values'],
                                                                                                                'prefix' => $vendor_attributes['price_prefix'],
                                                                                                                'price' => $vendor_attributes['options_values_price']);
            $subindex++;
          }
        }
        $index++;
      }
      $index2++;
      // let's build the email
      // Get the delivery address
      $delivery_address_query = tep_db_query("select distinct delivery_company, delivery_name, delivery_street_address, delivery_city, delivery_state, delivery_postcode from " . TABLE_ORDERS . " where orders_id='" . $oID ."'") ;
      $vendor_delivery_address_list = tep_db_fetch_array($delivery_address_query);
      if ($debug == 'yes') {
        echo 'The number of vendors: ' . sizeof($vendor_products) . "\n";
      }
      $email='';
   
      for ($l=0, $m=sizeof($vendor_products); $l<$m; $l++) {

        $vendor_country = tep_get_country_name($vendor_products[$l]['Vcountry']);
        $order_number= $oID;
        $vendors_id=$vendor_products[$l]['Vid'];
        $the_email=$vendor_products[$l]['Vemail'];
        $the_name=$vendor_products[$l]['Vname'];
        $the_contact=$vendor_products[$l]['Vcontact'];
        $email=  "\n" . 'To: ' . $the_contact . "\n" . $the_name . "\n" . $the_email . "\n" .
        $vendor_products[$l]['Vstreet'] . "\n" .
        $vendor_products[$l]['Vcity'] .', ' .
        $vendor_products[$l]['Vstate'] .'  ' .
        $vendor_products[$l]['Vzipcode'] . ' ' . $vendor_country . "\n\n" . EMAIL_SEPARATOR . "\n" . 'Special Comments or Instructions:  ' . $vendor_products[$l]['Vinstructions'] . "\n\n" . EMAIL_SEPARATOR . "\n" . 'From: ' . STORE_OWNER . "\n" . STORE_NAME_ADDRESS . "\n" . 'Accnt #: ' . $vendor_products[$l]['Vaccount'] . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" .  EMAIL_SEPARATOR . "\n\n" . ' Shipping Method: ' .  $vendor_products[$l]['Vmodule'] . ' -- '  .  $vendor_products[$l]['Vmethod'] .  "\n" .  EMAIL_SEPARATOR . "\n\n" . 'Dropship deliver to:' . "\n" .
        $vendor_delivery_address_list['delivery_company'] . "\n" .
        $vendor_delivery_address_list['delivery_name'] . "\n" .
        $vendor_delivery_address_list['delivery_street_address'] . "\n" .
        $vendor_delivery_address_list['delivery_city'] .', ' .
        $vendor_delivery_address_list['delivery_state'] . ' ' . $vendor_delivery_address_list['delivery_postcode'] . "\n\n" ;
        $email .= 'Products:' . "\n" . EMAIL_SEPARATOR . "\n";
        for ($i=0, $n=sizeof($vendor_products[$l]['vendor_orders_products']); $i<$n; $i++) {
          $product_attribs ='';
          if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0)) {
            for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++) {
              $product_attribs .= '     ' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['option'] . ': ' .  $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['value'] . "\n";
            }
          }
          if (tep_not_null($vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'])) {
            $prod_module = ' (' . $vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'] . ')';
          } else {
            $prod_module = '';
          }
          $email .= $vendor_products[$l]['vendor_orders_products'][$i]['Pqty'] .
                            ' X ' . $vendor_products[$l]['vendor_orders_products'][$i]['Pname'] . $prod_module . "\n" . $product_attribs . "\n";
        }
                        
        tep_mail($the_name, $the_email, EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID ,  $email .  '<br>', STORE_NAME, STORE_OWNER_EMAIL_ADDRESS)  ;
      $vendor_order_sent = 'yes';
        tep_db_query("update " . TABLE_ORDERS_SHIPPING . " set vendor_order_sent = '" . tep_db_input($vendor_order_sent) . "' where orders_id = '" . (int)$oID . "'  and vendors_id = '" . (int)$vendors_id . "'");
        if ($debug == 'yes') {
          echo 'The $email(including headers:' . "\n" . 'Vendor Email Addy' . $the_email . "\n" . 'Vendor Name' . $the_name . "\n" . 'Vendor Contact' . $the_contact . "\n" . 'Body--' . "\n" . $email . "\n";
        }
      }
    }
    return true;
  }
  //MVS END
  
  function setOrderPaymentID($payment_id,$oID='') {
      $order_id = !empty($oID) ? $oID : $this->orderID;
      tep_db_query("update " . TABLE_ORDERS . " set payment_id = '" . (int)$payment_id . "' where orders_id = '" . (int)$order_id . "'");
  }

  function removeCustomersBasket($customer_id) {
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "'");
  }

}//end class
?>
