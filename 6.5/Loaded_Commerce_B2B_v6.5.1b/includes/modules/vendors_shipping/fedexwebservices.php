<?php
/**
  @name       fedexwebservices.php   
  @version    9.4.1 | 05-17-2012 | datazen
  @author     Loaded Commerce Core Team
  @copyright  (c) 2012 loadedcommerce.com
  @license    GPL2
*/
class fedexwebservices {
/**
 * public variables
 */
 var $code, $title, $description, $icon, $sort_order, $enabled, $tax_class, $fedex_key, $fedex_pwd, $fedex_act_num, $fedex_meter_num, $country;
 /**
  * class constructor
  *
  * @access public
  * @return void
  */
  function fedexwebservices() {
    global $order;

    $this->vendors_id       = (isset($_GET['vendors_id']) && !empty($_GET['vendors_id'])) ? (int)$_GET['vendors_id'] : NULL;  
    $this->code             = "fedexwebservices";
    $this->title            = MODULE_SHIPPING_FEDEX_WEB_SERVICES_TEXT_TITLE;
    $this->handling_fee     = MODULE_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE;
    $this->icon 			         = DIR_WS_ICONS . 'shipping_fedex.gif';
    $this->ok_to_install    = (@extension_loaded('soap')) ? TRUE : FALSE;
    if ($this->ok_to_install) {
      $this->description    = MODULE_SHIPPING_FEDEX_WEB_SERVICES_TEXT_DESCRIPTION;
    } else {
      $this->description    = MODULE_SHIPPING_FEDEX_WEB_SERVICES_TEXT_NO_SOAP_DESCRIPTION;
    }
   
    if (defined("SHIPPING_ORIGIN_COUNTRY")) {
      if ((int)SHIPPING_ORIGIN_COUNTRY > 0) {
        $countries_array = $this->get_countries(SHIPPING_ORIGIN_COUNTRY, true);
        $this->country = $countries_array['countries_iso_code_2'];
      } else {
        $this->country = SHIPPING_ORIGIN_COUNTRY;
      }
    } else {
      $this->country = STORE_ORIGIN_COUNTRY;
    }
    $this->delivery_country_id = $order->delivery['country']['id'];
    $this->delivery_zone_id = $order->delivery['zone_id'];    
  }
  
  // MVS ADD
  function sort_order($vendors_id = '1') {
    $sort_order = @constant ('MODULE_SHIPPING_FEDEX_WEB_SERVICES_SORT_ORDER_' . $vendors_id);
    if (isset ($sort_order)) {
      $this->sort_order = $sort_order;
    } else {
      $this->sort_order = '';
    }
    return $this->sort_order;
  }

  function tax_class($vendors_id = '1') {
    $this->tax_class = constant('MODULE_SHIPPING_FEDEX_WEB_SERVICES_TAX_CLASS_' . $vendors_id);
    return $this->tax_class;
  }
  
  function enabled($vendors_id = '1') {
    $this->enabled = false;
    $status = @constant('MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATUS_' . $vendors_id);
 
    if (isset ($status) && $status != '') {
      $this->enabled = (($status == 'True') ? true : false);
    }
    if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_FEDEX_WEB_SERVICES_ZONE_' . $vendors_id) > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_FEDEX_WEB_SERVICES_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
      while ($check = tep_db_fetch_array($check_query)) {
        if ($check['zone_id'] < 1) {
          $check_flag = true;
          break;
        }
         elseif ($check['zone_id'] == $this->delivery_zone_id) {
          $check_flag = true;
          break;
          }
      }

      if ($check_flag == false) {
        $this->enabled = false;
      }
    }

    return $this->enabled;
  }

  function zones($vendors_id = '1') {
    if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_FEDEX_WEB_SERVICES_ZONE_' . $vendors_id) > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_FEDEX_WEB_SERVICES_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
      while ($check = tep_db_fetch_array($check_query)) {
        if ($check['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check['zone_id'] == $this->delivery_zone_id) {
          $check_flag = true;
          break;
        } 
      }

      if ($check_flag == false) {
        $this->enabled = false;
      }
    }
    return $this->enabled;
  }
  /**
  * Get the quote from Fedex API
  *
  * @param  string  $method  service type
  * @access public
  * @return array
  */
  function quote($method = '', $module = '', $vendors_id = '1') {
    global $shipping_weight, $shipping_num_boxes, $cart, $order;
  
    $this->vendors_id       = $vendors_id;
    $this->weight_type      = @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_WEIGHT_ . $this->vendors_id);
    $this->fedex_key        = @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_KEY_ . $this->vendors_id);
    $this->fedex_pwd        = @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_PWD_ . $this->vendors_id);
    $this->fedex_act_num    = @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_ACT_NUM_ . $this->vendors_id);
    $this->fedex_meter_num  = @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_METER_NUM_ . $this->vendors_id); 
    @define('MODULE_SHIPPING_FEDEX_WEB_SERVICES_INSURE_' . $this->vendors_id, 0);   
    
    if (defined('MODULE_SHIPPING_FEDEX_WEB_SERVICES_SERVER_' . $this->vendors_id) && @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_SERVER_ . $this->vendors_id) == 'Test') {
      $path_to_wsdl = DIR_FS_CATALOG . DIR_WS_MODULES . 'vendors_shipping/fedexwebservices/RateService_v9_test.wsdl';
    } else {
      $path_to_wsdl = DIR_FS_CATALOG . DIR_WS_MODULES . 'vendors_shipping/fedexwebservices/RateService_v9.wsdl';
    }

    ini_set("soap.wsdl_cache_enabled", "0");
    $client = new SoapClient($path_to_wsdl, array('trace' => 1));
    $this->types = array();
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_PRIORITY_ . $this->vendors_id) == 'True') {
      $this->types['INTERNATIONAL_PRIORITY'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
      $this->types['EUROPE_FIRST_INTERNATIONAL_PRIORITY'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
    }
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_ECONOMY_ . $this->vendors_id) == 'True') {
      $this->types['INTERNATIONAL_ECONOMY'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
    }  
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_STANDARD_OVERNIGHT_ . $this->vendors_id) == 'True') {
      $this->types['STANDARD_OVERNIGHT'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
    }
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_FIRST_OVERNIGHT_ . $this->vendors_id) == 'True') {
      $this->types['FIRST_OVERNIGHT'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
    }
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_PRIORITY_OVERNIGHT_ . $this->vendors_id) == 'True') {
      $this->types['PRIORITY_OVERNIGHT'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
    }
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_2DAY_ . $this->vendors_id) == 'True') {
      $this->types['FEDEX_2_DAY'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
    }
    // because FEDEX_GROUND also is returned for Canadian Addresses, we need to check if the country matches the store country and whether international ground is enabled
    if ((@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_GROUND_ . $this->vendors_id) == 'True' && $order->delivery['country']['id'] == STORE_COUNTRY) || (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_GROUND_ . $this->vendors_id) == 'True' && ($order->delivery['country']['id'] != STORE_COUNTRY) && @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_GROUND_ . $this->vendors_id) == 'True')) {
      $this->types['FEDEX_GROUND'] = array('icon' => '', 'handling_fee' => ($order->delivery['country']['id'] == STORE_COUNTRY ? @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE_ . $this->vendors_id) : @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE_ . $this->vendors_id)));
      $this->types['GROUND_HOME_DELIVERY'] = array('icon' => '', 'handling_fee' => ($order->delivery['country']['id'] == STORE_COUNTRY ? @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_HOME_DELIVERY_HANDLING_FEE_ . $this->vendors_id) : @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE_ . $this->vendors_id)));
    }
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_GROUND_ . $this->vendors_id) == 'True') {
      $this->types['INTERNATIONAL_GROUND'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE_ . $this->vendors_id));
    }
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_SAVER_ . $this->vendors_id) == 'True') {
      $this->types['FEDEX_EXPRESS_SAVER'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
    }
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_FREIGHT_ . $this->vendors_id) == 'True') {
      $this->types['FEDEX_FREIGHT'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
      $this->types['FEDEX_NATIONAL_FREIGHT'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
      $this->types['FEDEX_1_DAY_FREIGHT'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
      $this->types['FEDEX_2_DAY_FREIGHT'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
      $this->types['FEDEX_3_DAY_FREIGHT'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
      $this->types['INTERNATIONAL_ECONOMY_FREIGHT'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
      $this->types['INTERNATIONAL_PRIORITY_FREIGHT'] = array('icon' => '', 'handling_fee' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE_ . $this->vendors_id));
    }

    // customer details
    $street_address = $order->delivery['street_address'];
    $street_address2 = $order->delivery['suburb'];
    $city = $order->delivery['city'];
    $state = tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], '');
    if ($state == "QC") $state = "PQ";
    $postcode = str_replace(array(' ', '-'), '', $order->delivery['postcode']);
    //$country_id = $order->delivery['country']['iso_code_2'];
    $delivery_country_arr = $this->get_countries($this->delivery_country_id, true);
    $country_id = $delivery_country_arr['countries_iso_code_2'];
    $totals = $order->info['subtotal'] = $cart->total;
    $this->_setInsuranceValue($totals);
            
    $request['WebAuthenticationDetail'] = array('UserCredential' =>
                                          array('Key' => $this->fedex_key, 'Password' => $this->fedex_pwd));
    $request['ClientDetail'] = array('AccountNumber' => $this->fedex_act_num, 'MeterNumber' => $this->fedex_meter_num);
    $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v9 using PHP ***');
    $request['Version'] = array('ServiceId' => 'crs', 'Major' => '9', 'Intermediate' => '0', 'Minor' => '0');
    $request['ReturnTransitAndCommit'] = true;
    $request['RequestedShipment']['DropoffType'] = $this->_setDropOff(); // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
    $request['RequestedShipment']['ShipTimestamp'] = date('c');
    $request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
    $request['RequestedShipment']['TotalInsuredValue']=array('Ammount'=> $this->insurance, 'Currency' => $_SESSION['currency']);
    $request['WebAuthenticationDetail'] = array('UserCredential' => array('Key' => $this->fedex_key, 'Password' => $this->fedex_pwd));
    $request['ClientDetail'] = array('AccountNumber' => $this->fedex_act_num, 'MeterNumber' => $this->fedex_meter_num);

    $request['RequestedShipment']['Shipper'] = array('Address' => array(
                                                     'StreetLines' => array(@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_1_ . $this->vendors_id), utf8_encode(@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_2_ . $this->vendors_id))), // Origin details
                                                     'City' => utf8_encode(@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_CITY_ . $this->vendors_id)),
                                                     'StateOrProvinceCode' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATE_ . $this->vendors_id),
                                                     'PostalCode' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_POSTAL_ . $this->vendors_id),
                                                     'CountryCode' => $this->country));          

    $request['RequestedShipment']['Recipient'] = array('Address' => array (
                                                       'StreetLines' => array(utf8_encode($street_address), utf8_encode($street_address2)), // customer street address
                                                       'City' => utf8_encode($city), //customer city
                                                       'PostalCode' => $postcode, //customer postcode
                                                       'CountryCode' => $country_id,
                                                       'Residential' => ($order->delivery['company'] != '' ? false : true))); //customer county code
    if (in_array($country_id, array('US', 'CA'))) {
      $request['RequestedShipment']['Recipient']['StateOrProvinceCode'] = $state;
    }

    $request['RequestedShipment']['ShippingChargesPayment'] = array('PaymentType' => 'SENDER',
                                                                    'Payor' => array('AccountNumber' => $this->fedex_act_num, // payor's account number
                                                                    'CountryCode' => $this->country));
    $request['RequestedShipment']['RateRequestTypes'] = 'LIST';
    $request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';
    $request['RequestedShipment']['RequestedPackageLineItems'] = array();
    
    $dimensions_failed = false;

    // default method for calculating number of packages
    if ($shipping_weight == 0) $shipping_weight = 0.1;
    for ($i=0; $i<$shipping_num_boxes; $i++) {
      $request['RequestedShipment']['RequestedPackageLineItems'][] = array('Weight' => array('Value' => $shipping_weight,
                                                                                             'Units' => @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_WEIGHT_ . $this->vendors_id)));
    }

    $request['RequestedShipment']['PackageCount'] = $shipping_num_boxes;
    
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_SATURDAY_ . $this->vendors_id)== 'True') {
      $request['RequestedShipment']['ServiceOptionType'] = 'SATURDAY_DELIVERY';
    }
    
    if (@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_SIGNATURE_OPTION_ . $this->vendors_id) >= 0 && $totals >= @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_SIGNATURE_OPTION_ . $this->vendors_id)) { 
      $request['RequestedShipment']['SpecialServicesRequested'] = 'SIGNATURE_OPTION'; 
    }

    $response = $client->getRates($request);
    
    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' && is_array($response->RateReplyDetails) || is_object($response->RateReplyDetails)) {
      if (is_object($response->RateReplyDetails)) {
        $response->RateReplyDetails = get_object_vars($response->RateReplyDetails);
      }

      $show_box_weight = " (Total items: " . $shipping_num_boxes . ' pcs. Total weight: '.number_format($shipping_weight * $shipping_num_boxes,2).' '.strtolower($this->weight_type).'s)';
      $this->quotes = array('id' => $this->code,
                            'module' => $this->title . $show_box_weight,
                            'info' => $this->info());

      $methods = array();

      foreach ($response->RateReplyDetails as $rateReply) {
        if ($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount > 0) {
          if (array_key_exists($rateReply->ServiceType, $this->types) && ($method == '' || str_replace('_', '', $rateReply->ServiceType) == $method)) {
            if (MODULE_SHIPPING_FEDEX_WEB_SERVICES_RATES == 'LIST') {
              foreach ($rateReply->RatedShipmentDetails as $ShipmentRateDetail) {
                if ($country_id == 'US') {
                  $payor_list = 'PAYOR_LIST_PACKAGE';
                } else {
                  $payor_list = 'PAYOR_LIST_SHIPMENT';
                }
                if ($ShipmentRateDetail->ShipmentRateDetail->RateType == $payor_list) {
                  $cost = $ShipmentRateDetail->ShipmentRateDetail->TotalNetCharge->Amount;
                  $cost = (float)round(preg_replace('/[^0-9.]/', '',  $cost), 2);
                }
              }
            } else {
              $cost = $rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount;
              $cost = (float)round(preg_replace('/[^0-9.]/', '',  $cost), 2);
            }
            if (in_array($rateReply->ServiceType, array('GROUND_HOME_DELIVERY', 'FEDEX_GROUND', 'INTERNATIONAL_GROUND'))) {
              $transitTime = ' (' . str_replace(array('_', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteeen'), array(' ', 1,2,3,4,5,6,7,8,9,10,11,12,13,14), strtolower($rateReply->TransitTime)) . ')';
            }
            $methods[] = array('id' => str_replace('_', '', $rateReply->ServiceType),                                                   
                               'title' => ucwords(strtolower(str_replace('_', ' ', $rateReply->ServiceType))) . $transitTime,     
                               'cost' => $cost + (strpos($this->types[$rateReply->ServiceType]['handling_fee'], '%') ? ($cost * (float)$this->types[$rateReply->ServiceType]['handling_fee'] / 100) : (float)$this->types[$rateReply->ServiceType]['handling_fee']));
          }
        }
      }

      $this->quotes['methods'] = $methods;
      
      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
    } else {
      $message = 'Error in processing transaction.<br /><br />';
      foreach ($response -> Notifications as $notification) {
        if(is_array($response -> Notifications)) {
          $message .= $notification->Severity;
          $message .= ': ';
          $message .= $notification->Message . '<br />';
        } else {
          $message .= $notification->Message . '<br />';
        }
      }
      $this->quotes = array('module' => $this->title,
                            'error'  => $message);
    }
    // Fedex cannot ship to po box 
    if (preg_match("/^P(.+)O(.+)BOX/i", $order->delivery['street_address']) || 
        preg_match("/^PO BOX/i",$order->delivery['street_address']) || 
        preg_match("/^P(.+)O(.+)BOX/i", $order->delivery['suburb']) || 
        preg_match("/^[A-Z]PO/i", $order->delivery['street_address']) || 
        preg_match("/^[A-Z]PO/i",$order->delivery['suburb'])) {
        $this->quotes = array('module' => $this->title,
                              'error' => '<font size=+2 color=red><b>Federal Express cannot ship to Post Office Boxes.<b></font><br>Use the Change Address button above to use a FedEx accepted street address.'); }

    if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

    return $this->quotes;
  }
 /**
  * Return expanded info in FEAC 
  *
  * @access public
  * @return string
  */   
  function info() { 
    return @constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INFO_ . $this->vendors_id); // add a description here or leave blank to disable
  }
 /**
  * Internal function to set insurance value
  *
  * @param  decimal $order_amount The order total  
  * @access public
  * @return integer
  */   
  function _setInsuranceValue($order_amount){
    if ($order_amount > (float)@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_INSURE_ . $this->vendors_id)) {
      $this->insurance = sprintf("%01.2f", $order_amount);
    } else {
      $this->insurance = 0;
    }
  }
 /**
  * Set the drop off type
  *
  * @access public
  * @return string
  */   
  function _setDropOff() {
    switch(@constant(MODULE_SHIPPING_FEDEX_WEB_SERVICES_DROPOFF_ . $this->vendors_id)) {
      case '1':
        return 'REGULAR_PICKUP';
        break;
      case '2':
        return 'REQUEST_COURIER';
        break;
      case '3':
        return 'DROP_BOX';
        break;
      case '4':
        return 'BUSINESS_SERVICE_CENTER';
        break;
      case '5':
        return 'STATION';
        break;
    }
  }
 /**
  * Check the module status
  *
  * @access public
  * @return array
  */   
  function check() {
    if (!isset($this->_check)) {
      $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATUS_" . $this->vendors_id . "' and vendors_id = '" . $this->vendors_id . "'");
      $this->_check = tep_db_num_rows($check_query);
    }
    return $this->_check;
  }  
 /**
  * Install the module
  *
  * @access public
  * @return void
  */   
  function install($vendors_id = '1') {
    $this->vendors_id = $vendors_id;
    if ($this->ok_to_install) {
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) VALUES ('Enable FedEx Web Services','MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATUS_" . $this->vendors_id . "','True','Do you want to offer FedEx shipping?','6','0','tep_cfg_select_option(array(\'True\',\'False\'),',now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('FedEx Web Services Key', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_KEY_" . $this->vendors_id . "', '', 'Enter FedEx Web Services Key', '6', '3', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('FedEx Web Services Password', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PWD_" . $this->vendors_id . "', '', 'Enter FedEx Web Services Password', '6', '3', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('FedEx Account Number', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ACT_NUM_" . $this->vendors_id . "', '', 'Enter FedEx Account Number', '6', '3', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('FedEx Meter Number', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_METER_NUM_" . $this->vendors_id . "', '', 'Enter FedEx Meter Number', '6', '4', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Weight Units', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_WEIGHT_" . $this->vendors_id . "', 'LB', 'Weight Units:', '6', '10', 'tep_cfg_select_option(array(\'LB\', \'KG\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('First line of street address', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_1_" . $this->vendors_id . "', '', 'Enter the first line of your ship-from street address, required', '6', '20', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Second line of street address', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_2_" . $this->vendors_id . "', '', 'Enter the second line of your ship-from street address, leave blank if you do not need to specify a second line', '6', '21', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('City name', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_CITY_" . $this->vendors_id . "', '', 'Enter the city name for the ship-from street address, required', '6', '22', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('State or Province name', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATE_" . $this->vendors_id . "', '', 'Enter the 2 letter state or province name for the ship-from street address, required for Canada and US', '6', '23', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Postal code', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_POSTAL_" . $this->vendors_id . "', '', 'Enter the postal code for the ship-from street address, required', '6', '24', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Phone number', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PHONE_" . $this->vendors_id . "', '', 'Enter a contact phone number for your company, required', '6', '25', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Drop off type', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_DROPOFF_" . $this->vendors_id . "', '1', 'Dropoff type (1 = Regular pickup, 2 = request courier, 3 = drop box, 4 = drop at BSC, 5 = drop at station)?', '6', '30', 'tep_cfg_select_option(array(\'1\',\'2\',\'3\',\'4\',\'5\'),', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Express Saver', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_SAVER_" . $this->vendors_id . "', 'True', 'Enable FedEx Express Saver', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Standard Overnight', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_STANDARD_OVERNIGHT_" . $this->vendors_id . "', 'True', 'Enable FedEx Express Standard Overnight', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable First Overnight', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_FIRST_OVERNIGHT_" . $this->vendors_id . "', 'True', 'Enable FedEx Express First Overnight', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Priority Overnight', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PRIORITY_OVERNIGHT_" . $this->vendors_id . "', 'True', 'Enable FedEx Express Priority Overnight', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable 2 Day', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_2DAY_" . $this->vendors_id . "', 'True', 'Enable FedEx Express 2 Day', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable International Priority', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_PRIORITY_" . $this->vendors_id . "', 'True', 'Enable FedEx Express International Priority', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable International Economy', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_ECONOMY_" . $this->vendors_id . "', 'True', 'Enable FedEx Express International Economy', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Ground', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_GROUND_" . $this->vendors_id . "', 'True', 'Enable FedEx Ground', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable International Ground', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_GROUND_" . $this->vendors_id . "', 'True', 'Enable FedEx International Ground', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Freight', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_FREIGHT_" . $this->vendors_id . "', 'True', 'Enable FedEx Freight', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Saturday Delivery', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SATURDAY_" . $this->vendors_id . "', 'False', 'Enable Saturday Delivery', '6', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Domestic Ground Handling Fee', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE_" . $this->vendors_id . "', '', 'Add a domestic handling fee or leave blank (example: 15 or 15%)', '6', '25', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Home Delivery Handling Fee', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_HOME_DELIVERY_HANDLING_FEE_" . $this->vendors_id . "', '', 'Add a home delivery handling fee or leave blank (example: 15 or 15%)', '6', '25', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Domestic Express Handling Fee', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_" . $this->vendors_id . "', '', 'Add a domestic handling fee or leave blank (example: 15 or 15%)', '6', '25', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('International Ground Handling Fee', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE_" . $this->vendors_id . "', '', 'Add an international handling fee or leave blank (example: 15 or 15%)', '6', '25', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('International Express Handling Fee', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE_" . $this->vendors_id . "', '', 'Add an international handling fee or leave blank (example: 15 or 15%)', '6', '25', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) VALUES ('FedEx Rates','MODULE_SHIPPING_FEDEX_WEB_SERVICES_RATES_" . $this->vendors_id . "','LIST','FedEx Rates','6','0','tep_cfg_select_option(array(\'LIST\',\'ACCOUNT\'),',now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Signature Option', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SIGNATURE_OPTION_" . $this->vendors_id . "', '-1', 'Require a signature on orders greater than or equal to (set to -1 to disable):', '6', '25', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Shipping Zone', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ZONE_" . $this->vendors_id . "', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '98', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Tax Class', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_TAX_CLASS_" . $this->vendors_id . "', '0', 'Use the following tax class on the shipping fee.', '6', '25', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Web Services Mode', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SERVER_" . $this->vendors_id . "', 'Production', 'For testing using a developer key set to \"Test\". Otherwise set to \"Production\".', '6', '10', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now(),'" . $this->vendors_id . "')");
      tep_db_query ("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Sort Order', 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SORT_ORDER_" . $this->vendors_id . "', '0', 'Sort order of display.', '6', '99', now(),'" . $this->vendors_id . "')");
    }
  }
 /**
  * Remove the module
  *
  * @access public
  * @return void
  */   
  function remove($vendors_id = '1') {
    $this->vendors_id = $vendors_id;
    tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '". $vendors_id ."' and configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
  }
 /**
  * Return the module configuration values
  *
  * @access public
  * @return array
  */   
  function keys($vendors_id = '1') {
    return array('MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATUS_' . $vendors_id,
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_KEY_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PWD_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ACT_NUM_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_METER_NUM_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_WEIGHT_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_1_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ADDRESS_2_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_CITY_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_STATE_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_POSTAL_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PHONE_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_DROPOFF_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_SAVER_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_STANDARD_OVERNIGHT_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_FIRST_OVERNIGHT_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_PRIORITY_OVERNIGHT_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_2DAY_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_PRIORITY_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_ECONOMY_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_GROUND_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_FREIGHT_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INTERNATIONAL_GROUND_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SATURDAY_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_TAX_CLASS_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_HANDLING_FEE_' . $vendors_id, 
		               'MODULE_SHIPPING_FEDEX_WEB_SERVICES_HOME_DELIVERY_HANDLING_FEE_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_EXPRESS_HANDLING_FEE_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_HANDLING_FEE_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_INT_EXPRESS_HANDLING_FEE_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_RATES_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SIGNATURE_OPTION_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_ZONE_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SERVER_' . $vendors_id, 
                 'MODULE_SHIPPING_FEDEX_WEB_SERVICES_SORT_ORDER_' . $vendors_id
                 );
  }
 /**
  * Get country info
  *
  * @param  integer $countries_id   The countries_id
  * @param  boolean $with_iso_codes True = include ISO codes in the return  
  * @access public
  * @return void
  */ 
  function get_countries($countries_id = '', $with_iso_codes = false) {
    $countries_array = array();
    if (tep_not_null($countries_id)) {
      if ($with_iso_codes == true) {
        $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' order by countries_name");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name'],
                                 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
      } else {
        $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name']);
      }
    } else {
      $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
      while ($countries_values = tep_db_fetch_array($countries)) {
        $countries_array[] = array('countries_id' => $countries_values['countries_id'],
        'countries_name' => $countries_values['countries_name']);
      }
    }

			return $countries_array;
		}
}
