<?php
/**
  @name       usps.php (MVS Version)  
  @version    4.2.0.M | 06-12-2012 | datazen
  @author     Loaded Commerce Core Team
  @copyright  (c) 2012 loadedcommerce.com
  @license    GPL2
*/
class usps {
  var $code, $title, $description, $icon, $enabled;
 /**
  * class constructor
  *
  * @access public
  * @return void
  */    
  function usps() {
    global $order;
    
    $this->vendors_id           = (isset($_GET['vendors_id']) && !empty($_GET['vendors_id'])) ? (int)$_GET['vendors_id'] : NULL;  
    $this->code                 = 'usps';
    $this->title                = MODULE_SHIPPING_USPS_TEXT_TITLE;
    $this->description          = MODULE_SHIPPING_USPS_TEXT_DESCRIPTION;
    $this->icon                 = DIR_WS_ICONS . 'shipping_usps.gif';
    $this->delivery_country_id  = $order->delivery['country']['id'];
    $this->delivery_zone_id     = $order->delivery['zone_id'];   
  }
 /**
  * Get the sort order
  *
  * @param  string  $vendors_id The vendor ID 
  * @access public
  * @return integer
  */ 
  function sort_order($vendors_id = '1') {
    $sort_order = @constant ('MODULE_SHIPPING_USPS_SORT_ORDER_' . $vendors_id);
    if (isset ($sort_order)) {
      $this->sort_order = $sort_order;
    } else {
      $this->sort_order = '';
    }
    return $this->sort_order;
  }
 /**
  * Get the tax class
  *
  * @param  string  $vendors_id The vendor ID 
  * @access public
  * @return object
  */ 
  function tax_class($vendors_id = '1') {
    $this->tax_class = constant('MODULE_SHIPPING_USPS_TAX_CLASS_' . $vendors_id);
    return $this->tax_class;
  }
 /**
  * Check if the module is enabled
  *
  * @param  string  $vendors_id The vendor ID 
  * @access public
  * @return boolean
  */   
  function enabled($vendors_id = '1') {
    $this->enabled = false;
    $status = @constant('MODULE_SHIPPING_USPS_STATUS_' . $vendors_id);
 
    if (isset ($status) && $status != '') {
      $this->enabled = (($status == 'True') ? true : false);
    }
    
    if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_USPS_ZONE_' . $vendors_id) > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_USPS_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
      while ($check = tep_db_fetch_array($check_query)) {
        if ($check['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check['zone_id'] == $this->delivery_zone_id) {
          $check_flag = true;
          break;
        }
      }
      if ($check_flag == false) $this->enabled = false;
    }

    return $this->enabled;
  }
 /**
  * Chuck the shipping zone
  *
  * @param  string  $vendors_id The vendor ID 
  * @access public
  * @return boolean
  */ 
  function zones($vendors_id = '1') {
    if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_USPS_ZONE_' . $vendors_id) > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_USPS_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
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
  * Retrieve the shipping quote
  *
  * @param  string  $method     The shipping method 
  * @param  string  $mofule     Not Used 
  * @param  string  $vendors_id The vendor ID 
  * @access public
  * @return object
  */      
  function quote($method = '', $module = '', $vendors_id = '1') {
    global $order, $shipping_weight, $shipping_num_boxes, $currencies, $shipping;
    
    $iInfo = '';
    $methods = array();
    $shipping_weight = ($shipping_weight < 0.0625 ? 0.0625 : $shipping_weight);
    $this->pounds = (int)$shipping_weight;
    $this->ounces = ceil(round(16 * ($shipping_weight - $this->pounds)));
    $this->tax_class = $this->tax_class($vendors_id);
    $uspsQuote = $this->_getQuote($vendors_id);   
   
    if (isset($uspsQuote['Number'])) return false;
    
    if (!is_array($uspsQuote)) return array('module' => $this->title, 'error' => MODULE_SHIPPING_USPS_TEXT_ERROR);
    
    if ($order->delivery['country']['iso_code_2'] == 'US') {
      $dExtras = array();
      $dOptions = explode(', ', @constant('MODULE_SHIPPING_USPS_DMST_SERVICES_' . $vendors_id));
      foreach ($dOptions as $key => $val) {
       if(strlen($dOptions[$key]) > 1) {
        if ($dOptions[$key+1] == 'C' || $dOptions[$key+1] == 'S' || $dOptions[$key+1] == 'Y') $dExtras[$dOptions[$key]] = $dOptions[$key+1];
       }
      }   
    } else {
      $iExtras = array();
      $iOptions = explode(', ', @constant('MODULE_SHIPPING_USPS_INTL_SERVICES_' . $vendors_id));
      foreach ($iOptions as $key => $val) {
        if(strlen($iOptions[$key]) > 1) {
          if ($iOptions[$key+1] == 'C' || $iOptions[$key+1] == 'S' || $iOptions[$key+1] == 'Y') $iExtras[$iOptions[$key]] = $iOptions[$key+1];
        }
      }  
      if (@constant('MODULE_SHIPPING_USPS_REGULATIONS_' . $vendors_id) == 'True') {
        $iInfo =  '<div id="iInfo">' . 
                  '<div id="showInfo" class="ui-state-error" style="cursor:pointer; text-align:center;" onclick="$(\'#showInfo\').hide();$(\'#hideInfo, #Info\').show();">' . @constant('MODULE_SHIPPING_USPS_TEXT_INTL_SHOW_' . $vendors_id) . '</div>' .
                  '<div id="hideInfo" class="ui-state-error" style="cursor:pointer; text-align:center; display:none;" onclick="$(\'#hideInfo, #Info\').hide();$(\'#showInfo\').show();">' . @constant('MODULE_SHIPPING_USPS_TEXT_INTL_HIDE_' . $vendors_id) .'</div>' .
                  '<div id="Info" class="ui-state-highlight" style="display:none; padding:10px; max-height:200px; overflow:auto;">' . '<b>Prohibitions:</b><br>' . nl2br($uspsQuote['Package']['Prohibitions']) . '<br><br><b>Restrictions:</b><br>' . nl2br($uspsQuote['Package']['Restrictions']) . '<br><br><b>Observations:</b><br>' . nl2br($uspsQuote['Package']['Observations']) . '<br><br><b>CustomsForms:</b><br>' . nl2br($uspsQuote['Package']['CustomsForms']) . '<br><br><b>ExpressMail:</b><br>' . nl2br($uspsQuote['Package']['ExpressMail']) . '<br><br><b>AreasServed:</b><br>' . nl2br($uspsQuote['Package']['AreasServed']) . '<br><br><b>AdditionalRestrictions:</b><br>' . nl2br($uspsQuote['Package']['AdditionalRestrictions']) .'</div>' .
                  '</div>';
      }
    }
    if (isset($uspsQuote['Package']['Postage']) && tep_not_null($uspsQuote['Package']['Postage'])) {
      $PackageSize = 1;
    } else { 
      $PackageSize = ($order->delivery['country']['iso_code_2'] == 'US' ? sizeof($uspsQuote['Package']) : sizeof($uspsQuote['Package']['Service']));
    }

    for ($i=0; $i<$PackageSize; $i++) {
      $Services = array();
      $hiddenServices = array();
      $hiddenCost = 0;
      $handling = 0;
      $types = explode(', ', @constant('MODULE_SHIPPING_USPS_TYPES_' . $vendors_id));
      if (isset($uspsQuote['Package'][$i]['Error']) && tep_not_null($uspsQuote['Package'][$i]['Error'])) continue;
      $Package = ($PackageSize == 1 ? $uspsQuote['Package']['Postage'] : ($order->delivery['country']['iso_code_2'] == 'US' ? $uspsQuote['Package'][$i]['Postage'] : $uspsQuote['Package']['Service'][$i]));
      if ($order->delivery['country']['iso_code_2'] == 'US') {
        if (tep_not_null($Package['SpecialServices']['SpecialService'])) {
          foreach ($Package['SpecialServices']['SpecialService'] as $key => $val) {
            if (isset($dExtras[$val['ServiceName']]) && tep_not_null($dExtras[$val['ServiceName']]) && ((@constant('MODULE_SHIPPING_USPS_RATE_TYPE_' . $vendors_id) == 'Online' && $val['AvailableOnline'] == 'true') || (@constant('MODULE_SHIPPING_USPS_RATE_TYPE_' . $vendors_id) == 'Retail' && $val['Available'] == 'true'))) {         
              $val['ServiceAdmin'] = $dExtras[$val['ServiceName']];
              $Services[] = $val;
            }
            $cost = @constant('MODULE_SHIPPING_USPS_RATE_TYPE_' . $vendors_id) == 'Online' && tep_not_null($Package['CommercialRate']) ? $Package['CommercialRate'] : $Package['Rate'];
            $type = ($Package['MailService']);
          }
        }
      } else {
        foreach ($Package['ExtraServices']['ExtraService'] as $key => $val) {
         if (isset($iExtras[$val['ServiceName']]) && tep_not_null($iExtras[$val['ServiceName']]) && ((@constant('MODULE_SHIPPING_USPS_RATE_TYPE_' . $vendors_id) == 'Online' && $val['AvailableOnline'] == 'True') || (@constant('MODULE_SHIPPING_USPS_RATE_TYPE_' . $vendors_id) == 'Retail' && $val['Available'] == 'True'))) {         
           $val['ServiceAdmin'] = $iExtras[$val['ServiceName']];
           $Services[] = $val;
         }
         $cost = @constant('MODULE_SHIPPING_USPS_RATE_TYPE_' . $vendors_id) == 'Online' && tep_not_null($Package['CommercialPostage']) ? $Package['CommercialPostage'] : $Package['Postage'];
         $type = ($Package['SvcDescription']);
        }
      }
      if ($cost == 0) continue;
      foreach ($types as $key => $val) {
        if(!is_numeric($val) && $val == $type) {
          $minweight = $types[$key+1];
          $maxweight = $types[$key+2];
          $handling = $types[$key+3];
        }
      }
      foreach ($Services as $key => $val) {
       $sDisplay = $Services[$key]['ServiceAdmin'];
       if ($sDisplay == 'Y') $hiddenServices[] = array($Services[$key]['ServiceName'] => (@constant('MODULE_SHIPPING_USPS_RATE_TYPE_' . $vendors_id) == 'Online' ? $Services[$key]['PriceOnline'] : $Services[$key]['Price']));
      }
      foreach($hiddenServices as $key => $val) {
        foreach($hiddenServices[$key] as $key1 => $val1) {
          $hiddenCost += $val1;     
        }
      }  
      if ((($method == '' && in_array($type, $types)) || $method == $type) && $shipping_weight < $maxweight && $shipping_weight > $minweight) {
        $methods[] = array('id' => $type,
                           'title' => str_replace(array('RM', 'TM', '**'), array('&reg;', '&trade;', ''), $type),
                           'cost' => ($cost + $handling + $hiddenCost) * $shipping_num_boxes);   
      }
    }
    if (sizeof($methods) == 0) return false;
    if (sizeof($methods) > 1) {
      foreach($methods as $c=>$key) {
        $sort_cost[] = $key['cost'];
        $sort_id[] = $key['id'];
      }
      array_multisort($sort_cost, (@constant('MODULE_SHIPPING_USPS_RATE_SORTER_' . $vendors_id) == 'Ascending' ? SORT_ASC : SORT_DESC), $sort_id, SORT_ASC, $methods);
    }
    $this->quotes = array( 'id' => $this->code,
          'module' => $this->title . ' ' . $this->pounds . ' lbs, ' . $this->ounces . ' oz',
          'methods' => $methods,
          'tax' => $this->tax_class > 0 ? tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']) : null,
          'icon' => tep_not_null($this->icon) || tep_not_null($iInfo) ? (tep_not_null($this->icon) ? tep_image($this->icon, $this->title) : '') . (tep_not_null($iInfo) ?  '<br>' . $iInfo : '') : null);

    return $this->quotes;
  }
 /**
  * Internal function to retrieve individual quote
  *
  * @access public
  * @return string
  */    
  function _getQuote($vendors_id = '1') {
    global $order, $shipping_weight;
    
    if ($order->delivery['country']['iso_code_2'] == 'US') {
      $ZipDestination = substr(str_replace(' ', '', $order->delivery['postcode']), 0, 5);
      $request =	'<RateV4Request USERID="' . @constant('MODULE_SHIPPING_USPS_USERID_' . $vendors_id) . '">' .
      '<Revision>2</Revision>';
      $package_count = 0;
      foreach(explode(', ', @constant('MODULE_SHIPPING_USPS_TYPES_' . $vendors_id)) as $request_type) {
        if(is_numeric($request_type) || preg_match('#International#' , $request_type)) continue;
        $FirstClassMailType = '';
        $Container = 'VARIABLE';
        if (preg_match('#First\-Class#', $request_type)) {
          if ($shipping_weight > 13/16) { 
            continue;
          } else {
            $service = 'First-Class Mail';
            if ($request_type == 'First-Class MailRM Letter') { 
              $FirstClassMailType = 'LETTER';
            } elseif ($request_type == 'First-Class MailRM Large Envelope') { 
              $FirstClassMailType = 'FLAT';
            } else { 
              $FirstClassMailType = 'PARCEL';
            }
          }
        } elseif ($request_type == 'Media MailRM') {
          $service = 'MEDIA';
        } elseif ($request_type == 'Standard PostRM') {
          $service = 'STANDARD POST';
        } elseif (preg_match('#Priority MailRM#', $request_type)) {
          $service = 'PRIORITY COMMERCIAL';
          if ($request_type == 'Priority MailRM Flat Rate Envelope') $Container = 'FLAT RATE ENVELOPE';
          elseif ($request_type == 'Priority MailRM Legal Flat Rate Envelope') $Container = 'LEGAL FLAT RATE ENVELOPE';
          elseif ($request_type == 'Priority MailRM Padded Flat Rate Envelope') $Container = 'PADDED FLAT RATE ENVELOPE';
          elseif ($request_type == 'Priority MailRM Small Flat Rate Box') $Container = 'SM FLAT RATE BOX';
          elseif ($request_type == 'Priority MailRM Medium Flat Rate Box') $Container = 'MD FLAT RATE BOX';
          elseif ($request_type == 'Priority MailRM Large Flat Rate Box') $Container = 'LG FLAT RATE BOX';
          elseif ($request_type == 'Priority MailRM Regional Rate Box A') $Container = 'REGIONALRATEBOXA';
          elseif ($request_type == 'Priority MailRM Regional Rate Box B') $Container = 'REGIONALRATEBOXB';
          elseif ($request_type == 'Priority MailRM Regional Rate Box C') $Container = 'REGIONALRATEBOXC';
        } elseif (preg_match('#Express MailRM#', $request_type)) {
          $service = 'EXPRESS COMMERCIAL';
          if ($request_type == 'Express MailRM Flat Rate Envelope') $Container = 'FLAT RATE ENVELOPE';
          elseif ($request_type == 'Express MailRM Legal Flat Rate Envelope') $Container = 'LEGAL FLAT RATE ENVELOPE';
          elseif ($request_type == 'Express MailRM Flat Rate Boxes') $Container = 'FLAT RATE BOX';
        } else {
          continue;
        }
        $request .=	'<Package ID="' . $package_count . '">' .
        '<Service>' . $service . '</Service>' . 
        ($FirstClassMailType != '' ? '<FirstClassMailType>' . $FirstClassMailType . '</FirstClassMailType>' : '') .
        '<ZipOrigination>' . SHIPPING_ORIGIN_ZIP . '</ZipOrigination>' .
        '<ZipDestination>' . $ZipDestination . '</ZipDestination>' .
        '<Pounds>' . $this->pounds . '</Pounds>' .
        '<Ounces>' . $this->ounces . '</Ounces>' .
        '<Container>' . $Container . '</Container>' .
        '<Size>REGULAR</Size>' .
        '<Machinable>TRUE</Machinable>' .
        '</Package>';
        $package_count++;
      }
      $request .=	'</RateV4Request>';
      $request = 	'API=RateV4&XML=' . urlencode($request);
    } else {
      $request = 	'<IntlRateV2Request USERID="' . @constant('MODULE_SHIPPING_USPS_USERID_' . $vendors_id) . '">' .
      '<Revision>2</Revision>' .
      '<Package ID="0">' .
      '<Pounds>' . $this->pounds . '</Pounds>' .
      '<Ounces>' . $this->ounces . '</Ounces>' .
      '<MailType>All</MailType>' .
      '<GXG>' .
      '<POBoxFlag>N</POBoxFlag>' .
      '<GiftFlag>N</GiftFlag>' .
      '</GXG>' .
      '<ValueOfContents>' . ($order->info['subtotal'] + $order->info['tax']) . '</ValueOfContents>' .
      '<Country>' . tep_get_country_name($order->delivery['country']['id']) . '</Country>' .
      '<Container>RECTANGULAR</Container>' .
      '<Size>LARGE</Size>' .
      '<Width>2</Width>' .
      '<Length>10</Length>' .
      '<Height>6</Height>' .
      '<Girth>0</Girth>' .
      '<OriginZip>' . SHIPPING_ORIGIN_ZIP . '</OriginZip>' .
      '<CommercialFlag>N</CommercialFlag>' .
      '<ExtraServices>' .
      '<ExtraService>0</ExtraService>' .
      '<ExtraService>1</ExtraService>' .
      '<ExtraService>2</ExtraService>' .
      '<ExtraService>3</ExtraService>' .
      '<ExtraService>5</ExtraService>' .
      '<ExtraService>6</ExtraService>' .
      '</ExtraServices>' .
      '</Package>' .
      '</IntlRateV2Request>';
      $request = 	'API=IntlRateV2&XML=' . urlencode($request);
    }
    $body = '';
    $http = new httpClient();
    if ($http->Connect('production.shippingapis.com', 80)) {
      $http->addHeader('Host', 'production.shippingapis.com');
      $http->addHeader('User-Agent', 'osCommerce');
      $http->addHeader('Connection', 'Close');
      if ($http->Get('/shippingapi.dll?' . $request)) $body = preg_replace(array('/\&lt;sup\&gt;\&amp;reg;\&lt;\/sup\&gt;/', '/\&lt;sup\&gt;\&amp;trade;\&lt;\/sup\&gt;/', '/\" /', '/\",/', '/\"<br>/', '/<br>/'), array('RM', 'TM', '&quot;,', '&quot; ', '&quot;<br>', 'BREAK'), htmlspecialchars_decode($http->getBody()));
      //			mail(STORE_OWNER_EMAIL_ADDRESS, STORE_OWNER, $body);
      $http->Disconnect();
      return json_decode(json_encode(simplexml_load_string($body)),TRUE);
    } else {
      return false;
    }
  }
 /**
  * Install the module
  *
  * @access public
  * @return void
  */   
  function install($vendors_id = '1') {
    tep_db_query("ALTER TABLE " . TABLE_VENDOR_CONFIGURATION . " CHANGE `configuration_value` `configuration_value` TEXT NOT NULL, CHANGE `set_function` `set_function` TEXT NULL DEFAULT NULL");
    tep_db_query("update " . TABLE_VENDOR_CONFIGURATION . " SET configuration_value =  'true' where configuration_key = 'EMAIL_USE_HTML_" . $vendors_id . "'");
    tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable USPS Shipping', 'MODULE_SHIPPING_USPS_STATUS_" . $vendors_id . "', 'True', 'Do you want to offer USPS shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $vendors_id . "')");
    tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Enter the USPS User ID', 'MODULE_SHIPPING_USPS_USERID_" . $vendors_id . "', 'NONE', 'Enter the USPS USERID assigned to you.', '6', '0', now(),'" . $vendors_id . "')");
    tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Tax Class', 'MODULE_SHIPPING_USPS_TAX_CLASS_" . $vendors_id . "', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now(),'" . $vendors_id . "')");
    tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Shipping Zone', 'MODULE_SHIPPING_USPS_ZONE_" . $vendors_id . "', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now(),'" . $vendors_id . "')");
    tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Sort Order', 'MODULE_SHIPPING_USPS_SORT_ORDER_" . $vendors_id . "', '0', 'Sort order of display.', '6', '0', now(),'" . $vendors_id . "')");
    tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Shipping Methods (Domestic and International)',  'MODULE_SHIPPING_USPS_TYPES_" . $vendors_id . "',  '0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00, 0, 70, 0.00', '<b><u>Checkbox:</u></b> Select the services to be offered<br><b><u>Minimum Weight (lbs)</u></b>first input field<br><b><u>Maximum Weight (lbs):</u></b>second input field<br><br>USPS returns methods based on cart weights.  These settings will allow further control (particularly helpful for flat rate methods) but will not override USPS limits', '6', '0', 'tep_cfg_usps_services(array(\'First-Class MailRM Letter\', \'First-Class MailRM Large Envelope\', \'First-Class MailRM Parcel\', \'Media MailRM\', \'Standard PostRM\', \'Priority MailRM\', \'Priority MailRM Flat Rate Envelope\', \'Priority MailRM Legal Flat Rate Envelope\', \'Priority MailRM Padded Flat Rate Envelope\', \'Priority MailRM Small Flat Rate Box\', \'Priority MailRM Medium Flat Rate Box\', \'Priority MailRM Large Flat Rate Box\', \'Priority MailRM Regional Rate Box A\', \'Priority MailRM Regional Rate Box B\', \'Priority MailRM Regional Rate Box C\', \'Express MailRM\', \'Express MailRM Flat Rate Envelope\', \'Express MailRM Legal Flat Rate Envelope\', \'Express MailRM Flat Rate Boxes\', \'First-Class MailRM International Letter**\', \'First-Class MailRM International Large Envelope**\', \'First-Class Package International ServiceTM**\', \'Priority MailRM International\', \'Priority MailRM International Flat Rate Envelope**\', \'Priority MailRM International Small Flat Rate Box\', \'Priority MailRM International Medium Flat Rate Box\', \'Priority MailRM International Large Flat Rate Box\', \'Express MailRM International\', \'Express MailRM International Flat Rate Envelope\', \'Express MailRM International Flat Rate Boxes\', \'USPS GXGTM Envelopes**\', \'Global Express GuaranteedRM (GXG)**\'), ', now(),'" . $vendors_id . "')");
    tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Extra Services (Domestic)', 'MODULE_SHIPPING_USPS_DMST_SERVICES_" . $vendors_id . "', 'Certified MailRM, N, Insurance, N, Adult Signature Restricted Delivery, N, Registered without Insurance, N, Registered MailTM, N, Collect on Delivery, N, Return Receipt for Merchandise, N, Return Receipt, N, Certificate of Mailing, N, Express Mail Insurance, N, Delivery ConfirmationTM, N, Signature ConfirmationTM, N', 'Included in postage rates.  Not shown to the customer.', '6', '0', 'tep_cfg_usps_extraservices(array(\'Certified MailRM\', \'Insurance\', \'Adult Signature Restricted Delivery\', \'Registered without Insurance\', \'Registered MailTM\', \'Collect on Delivery\', \'Return Receipt for Merchandise\', \'Return Receipt\', \'Certificate of Mailing\', \'Express Mail Insurance\', \'Delivery ConfirmationTM\', \'Signature ConfirmationTM\'), ', now(),'" . $vendors_id . "')");
    tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Extra Services (International)', 'MODULE_SHIPPING_USPS_INTL_SERVICES_" . $vendors_id . "', 'Registered Mail, N, Insurance, N, Return Receipt, N, Restricted Delivery, N, Pick-Up, N, Certificate of Mailing, N', 'Included in postage rates.  Not shown to the customer.', '6', '0', 'tep_cfg_usps_extraservices(array(\'Registered Mail\', \'Insurance\', \'Return Receipt\', \'Restricted Delivery\', \'Pick-Up\', \'Certificate of Mailing\'), ', now(),'" . $vendors_id . "')");
    tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Retail pricing or Online pricing?', 'MODULE_SHIPPING_USPS_RATE_TYPE_" . $vendors_id . "', 'Online', 'Rates will be returned ONLY for methods available in this pricing type.  Applies to prices <u>and</u> add on services', '6', '0', 'tep_cfg_select_option(array(\'Retail\', \'Online\'), ', now(),'" . $vendors_id . "')");
    tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Rates Sort Order:', 'MODULE_SHIPPING_USPS_RATE_SORTER_" . $vendors_id . "', 'Ascending', 'Ascending: Low to High<br>Descending: High to Low', '6', '0', 'tep_cfg_select_option(array(\'Ascending\', \'Descending\'), ', now(),'" . $vendors_id . "')");
  }
 /**
  * Return the module configuration values
  *
  * @access public
  * @return array
  */  
  function keys($vendors_id = '1') {
    return array('MODULE_SHIPPING_USPS_STATUS_' . $vendors_id, 
                 'MODULE_SHIPPING_USPS_USERID_' . $vendors_id, 
                 'MODULE_SHIPPING_USPS_TAX_CLASS_' . $vendors_id, 
                 'MODULE_SHIPPING_USPS_ZONE_' . $vendors_id, 
                 'MODULE_SHIPPING_USPS_SORT_ORDER_' . $vendors_id, 
                 'MODULE_SHIPPING_USPS_TYPES_' . $vendors_id, 
                 'MODULE_SHIPPING_USPS_DMST_SERVICES_' . $vendors_id, 
                 'MODULE_SHIPPING_USPS_INTL_SERVICES_' . $vendors_id, 
                 'MODULE_SHIPPING_USPS_RATE_TYPE_' . $vendors_id, 
                 'MODULE_SHIPPING_USPS_RATE_SORTER_' . $vendors_id);
  }
 /**
  * Remove the module
  *
  * @access public
  * @return void
  */  
  function remove($vendors_id = '1') {
    tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '". $vendors_id ."' and configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
  }
 /**
  * Check the module status
  *
  * @access public
  * @return array
  */    
  function check() {
    if (!isset($this->_check)) {
      $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_USPS_STATUS_" . $this->vendors_id . "' and vendors_id = '" . $this->vendors_id . "'");
      $this->_check = tep_db_num_rows($check_query);
    }
    
    return $this->_check;
  } 
}
/**
* Create the shipping methods input array
*
* @access public
* @return string
*/  
function tep_cfg_usps_services($select_array, $key_value, $key = '') {
  $key_values = explode( ", ", $key_value);
  $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
  $string = '<b><div style="width:20px;float:left;text-align:center;">&nbsp;</div><div style="width:30px;float:left;text-align:center;">Min</div><div style="width:30px;float:left;text-align:center;">Max</div><div style="float:left;"></div><div style="width:50px;float:right;text-align:center;">Handling</div></b><div style="clear:both;"></div>';
  for ($i=0; $i<sizeof($select_array); $i++) {
    $string .= '<div id="' . $key . $i . '">';
    $string .= '<div style="width:20px;float:left;text-align:center;">' . tep_draw_checkbox_field($name, $select_array[$i], (in_array($select_array[$i], $key_values) ? 'CHECKED' : '')) . '</div>';
    if (in_array($select_array[$i], $key_values)) next($key_values);
    $string .= '<div style="width:30px;float:left;text-align:center;">' . tep_draw_input_field($name, current($key_values), 'size="1"') . '</div>';
    next($key_values);
    $string .= '<div style="width:30px;float:left;text-align:center;">' . tep_draw_input_field($name, current($key_values), 'size="1"') . '</div>';
    next($key_values);
    $string .= '<div style="float:left;">' . preg_replace(array('/RM/', '/TM/', '/International/', '/Envelope/', '/ Mail/', '/Large/', '/Medium/', '/Small/', '/First/', '/Legal/', '/Padded/', '/Flat Rate/', '/Regional Rate/', '/Express Guaranteed /'), array('', '', 'Intl', 'Env', '', 'Lg.', 'Md.', 'Sm.', '1st', 'Leg.', 'Pad.', 'F/R', 'R/R', 'Exp Guar'), $select_array[$i]) . '</div>';
    $string .= '<div style="width:50px;float:right;text-align:center;">$' . tep_draw_input_field($name, current($key_values), 'size="2"') . '</div>';
    next($key_values);
    $string .= '<div style="clear:both;"></div></div>';
  }
  
  return $string;
}
/**
* Create the extra services input array
*
* @access public
* @return string
*/ 
function tep_cfg_usps_extraservices($select_array, $key_value, $key = '') {
  $key_values = explode( ", ", $key_value);
  $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
  $string = '<b><div style="width:20px;float:left;text-align:center;">N</div><div style="width:20px;float:left;text-align:center;">Y</div></b><div style="clear:both;"></div>';
  for ($i=0; $i<sizeof($select_array); $i++) {
    $string .= tep_draw_hidden_field($name, $select_array[$i]);
    next($key_values);
    $string .= '<div id="' . $key . $i . '">';
    $string .= '<div style="width:20px;float:left;text-align:center;"><input type="checkbox" name="' . $name . '" value="N" ' . (current($key_values) == 'N' || current($key_values) == '' ? 'CHECKED' : '') . ' id="N-'.$key.$i.'" onClick="if(this.checked==1)document.getElementById(\'Y-'.$key.$i.'\').checked=false;else document.getElementById(\'Y-'.$key.$i.'\').checked=true;"></div>';
    $string .= '<div style="width:20px;float:left;text-align:center;"><input type="checkbox" name="' . $name . '" value="Y" ' . (current($key_values) == 'Y' ? 'CHECKED' : '') . ' id="Y-'.$key.$i.'" onClick="if(this.checked==1)document.getElementById(\'N-'.$key.$i.'\').checked=false;else document.getElementById(\'N-'.$key.$i.'\').checked=true;"></div>';
    next($key_values);
    $string .= preg_replace(array('/Signature/', '/without/', '/Merchandise/', '/TM/', '/RM/'), array('Sig', 'w/out', 'Merch.', '', ''), $select_array[$i]) . '<br>';
    $string .= '<div style="clear:both;"></div></div>';
  }
  
  return $string;
}
?>