<?php
/*
  $Id: Order_Info_Check.php,v 0.52 2002/09/21 hpdl Exp $
  by Cheng

  OSCommerce v2.2 CVS (09/17/02)

  Modified versions of create_account.php and related
  files.  Allowing 'purchase without account'.        
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License
*/
  
  if (!isset($is_read_only)) {
    $is_read_only = false;
  }
  if (!isset($errorguest )) {
    $errorguest = false;
  }
  if (!isset($processed)) {
    $processed = false;
  }
  if (!isset($account['customers_gender'])) {
    $account['customers_gender'] = '';
  }
  if (!isset($is_read_only)) {
    $is_read_only = false;
  }
  if (!isset($errorguest)) {
    $errorguest = false;
  }
  if (ACCOUNT_GENDER == 'true') {
    $male = ($account['customers_gender'] == 'm') ? true : false;
    $female = ($account['customers_gender'] == 'f') ? true : false;
?>
<div class="form-group">
  <label for="gender"><?php echo ENTRY_GENDER; ?></label>
  &nbsp;
  <?php
    if ($is_read_only) {
      echo ($account['customers_gender'] == 'm') ? MALE : FEMALE;
    } elseif ($errorguest) {
      if ($entry_gender_error) {
        echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
      } else {
        echo ($gender == 'm') ? MALE : FEMALE;
        echo tep_draw_hidden_field('gender');
      }
    } else {
      echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;<span class="red">*</span>';
    }
  ?>
</div>
<?php
  }
?>
<div class="form-group">
  <label for="firstname"><?php echo ENTRY_FIRST_NAME; ?> <span class="red">*</span></label>
  <?php
    if ($is_read_only) {
      echo $account['customers_firstname'];
    } elseif ($errorguest) {
      if ($entry_firstname_error) {
        echo tep_draw_input_field('firstname', '', 'class="form-control"');
      } else {
        echo $firstname . tep_draw_hidden_field('firstname');
      }
    } else {
      echo tep_draw_input_field('firstname', (isset($account['customers_firstname']) ? $account['customers_firstname'] : ''), 'class="form-control"');
    }
  ?>
</div>
<div class="form-group">
  <label for="lastname"><?php echo ENTRY_LAST_NAME; ?> <span class="red">*</span></label>
  <?php
    if ($is_read_only) {
      echo $account['customers_lastname'];
    } elseif ($errorguest) {
      if ($entry_lastname_error) {
        echo tep_draw_input_field('lastname', '', 'class="form-control"');
      } else {
        echo $lastname . tep_draw_hidden_field('lastname');
      }
    } else {
      echo tep_draw_input_field('lastname', (isset($account['customers_lastname']) ? $account['customers_lastname'] : ''), 'class="form-control"');
    }
  ?>
</div>
<?php
  if (ACCOUNT_DOB == 'true') {
?>
<div class="form-group">
  <label for="dob"><?php echo ENTRY_DATE_OF_BIRTH; ?> <span class="red">*</span></label>
  <?php
    if ($is_read_only) {
      echo tep_date_short($account['customers_dob']);
    } elseif ($errorguest) {
      if ($entry_date_of_birth_error) {
        echo tep_draw_input_field('dob');
      } else {
        echo $dob . tep_draw_hidden_field('dob');
      }
    } else {
      echo tep_draw_input_field('dob', (isset($account['customers_dob']) ? tep_date_short($account['customers_dob']) : ''), 'class="form-control"') ;
    }
  ?>
</div>
<?php
  }
?>
<div class="form-group">
  <label for="email_address"><?php echo ENTRY_EMAIL_ADDRESS; ?> <span class="red">*</span></label>
  <?php
    if ($is_read_only) {
      echo $account['customers_email_address'];
    } elseif ($errorguest) {
      if ($entry_email_address_error) {
        echo tep_draw_input_field('email_address', '', 'class="form-control"');
      } elseif ($entry_email_address_check_error) {
        echo tep_draw_input_field('email_address', '', 'class="form-control"');
      } elseif ($entry_email_address_exists) {
        echo tep_draw_input_field('email_address', '', 'class="form-control"');
      } else {
        echo $email_address . tep_draw_hidden_field('email_address');
      }
    } else {
      echo tep_draw_input_field('email_address', (isset($account['customers_email_address']) ? $account['customers_email_address'] : ''), 'class="form-control"');
    }
  ?>
</div>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>  
<div class="form-group">
  <label for="company"><?php echo ENTRY_COMPANY; ?></label>
  <?php
    if ($is_read_only) {
      echo $account['entry_company'];
    } elseif ($errorguest) {
      if ($entry_company_error) {
        echo tep_draw_input_field('company', '', 'class="form-control"');
      } else {
        echo $company . tep_draw_hidden_field('company');
      }
    } else {
      echo tep_draw_input_field('company', (isset($account['entry_company']) ? $account['entry_company'] : ''), 'class="form-control"');
    }
  ?>
</div>
<?php
  }
?>
<div class="form-group">
  <label for="street_address"><?php echo ENTRY_STREET_ADDRESS; ?> <span class="red">*</span></label>
  <?php
    if ($is_read_only) {
      echo $account['entry_street_address'];
    } elseif ($errorguest) {
      if ($entry_street_address_error) {
        echo tep_draw_input_field('street_address', '', 'class="form-control"');
      } else {
        echo $street_address . tep_draw_hidden_field('street_address');
      }
    } else {
      echo tep_draw_input_field('street_address', (isset($account['entry_street_address']) ? $account['entry_street_address']: ''), 'class="form-control"');
    }
  ?>
</div>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
<div class="form-group">
  <label for="suburb"><?php echo ENTRY_SUBURB; ?></label>
  <?php
    if ($is_read_only) {
      echo $account['entry_suburb'];
    } elseif ($errorguest) {
      if ($entry_suburb_error) {
        echo tep_draw_input_field('suburb', '', 'class="form-control"');
      } else {
        echo $suburb . tep_draw_hidden_field('suburb');
      }
    } else {
      echo tep_draw_input_field('suburb', (isset($account['entry_suburb']) ? $account['entry_suburb'] : ''), 'class="form-control"');
    }
  ?>
</div>
  <?php
  }
?>
<div class="form-group">
  <label for="city"><?php echo ENTRY_CITY; ?> <span class="red">*</span></label>
  <?php
    if ($is_read_only) {
      echo $account['entry_city'];
    } elseif ($errorguest) {
      if ($entry_city_error) {
        echo tep_draw_input_field('city', '', 'class="form-control"');
      } else {
        echo $city . tep_draw_hidden_field('city');
      }
    } else {
      echo tep_draw_input_field('city', (isset($account['entry_city']) ? $account['entry_city'] : ''), 'class="form-control"');
    }
  ?>
</div>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
<div class="form-group">
  <label for="state"><?php echo ENTRY_STATE; ?> <span class="red">*</span></label>
  <span id="SHOWSTATE">
    <?php
      if (!isset($country)) {
        $country = '';
      }
      if (!isset($zone_id)) {
        $zone_id = '';
      }
      if (!isset($state)) {
        $state = '';
      }
      $state = tep_get_zone_name($country, $zone_id, $state);
      if ($is_read_only) {
        echo tep_get_zone_name($account['entry_country_id'], $account['entry_zone_id'], $account['entry_state']);
      } elseif ($errorguest) {
        if ($entry_state_error) {
          if ($entry_state_has_zones) {
            $zones_array = array();
            $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by zone_name");
            while ($zones_values = tep_db_fetch_array($zones_query)) {
              $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
            }
            echo tep_draw_pull_down_menu('state', $zones_array, '', 'class="form-control"');
          } else {
            echo tep_draw_input_field('state', '', 'class="form-control"');
          }
        } else {
          echo $state . tep_draw_hidden_field('zone_id') . tep_draw_hidden_field('state');
        }
      } else {
        //echo tep_draw_input_field('state', tep_get_zone_name($account['entry_country_id'], (isset($account['entry_zone_id']) ? $account['entry_zone_id'] : 0), (isset($account['entry_state']) ? $account['entry_state'] : 0)),'class="form-control"');
        $zones_array = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '223' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array, '', 'class="form-control"');
      }
    ?>
  </span>
</div>
<?php
  }
?>      
<div class="form-group">
  <label for="postcode"><?php echo ENTRY_POST_CODE; ?> <span class="red">*</span></label>
  <?php
    if ($is_read_only) {
      echo $account['entry_postcode'];
    } elseif ($errorguest) {
      if ($entry_post_code_error) {
        echo tep_draw_input_field('postcode', '', 'maxlength="10" class="form-control"');
      } else {
        echo $postcode . tep_draw_hidden_field('postcode');
      }
    } else {
      echo tep_draw_input_field('postcode', (isset($account['entry_postcode']) ? $account['entry_postcode'] : ''), 'maxlength="10" class="form-control"');
    }
  ?>
</div>
<div class="form-group">
  <label for="country"><?php echo ENTRY_COUNTRY; ?> <span class="red">*</span></label>
  <?php
    if ($is_read_only) {
      echo tep_get_country_name($account['entry_country_id']);
    } elseif ($errorguest) {
      if ($entry_country_error) {
        echo tep_get_country_list('country', '', 'class="form-control" onchange="checkstate(this.value);"');
      } else {
        echo tep_get_country_name($country) . tep_draw_hidden_field('country');
      }
    } else {
      echo tep_get_country_list('country', (isset($account['entry_country_id']) ? $account['entry_country_id'] : ''), 'class="form-control" onchange="checkstate(this.value);"');
    }
  ?>
</div>
<div class="form-group">
  <label for="telephone"><?php echo ENTRY_TELEPHONE_NUMBER; ?> <span class="red">*</span></label>
  <?php
    if ($is_read_only) {
      echo $account['customers_telephone'];
    } elseif ($errorguest) {
      if ($entry_telephone_error) {
        echo tep_draw_input_field('telephone', '', 'class="form-control"');
      } else {
        echo $telephone . tep_draw_hidden_field('telephone');
      }
    } else {
      echo tep_draw_input_field('telephone', (isset($account['customers_telephone']) ? $account['customers_telephone'] : ''), 'class="form-control"');
    }
  ?>
</div>
<div class="form-group">
  <label for="fax"><?php echo ENTRY_FAX_NUMBER; ?></label>
  <?php
    if ($is_read_only) {
      echo $account['customers_fax'];
    } elseif ($processed) {
      echo $fax . tep_draw_hidden_field('fax');
    } else {
      echo tep_draw_input_field('fax', (isset($account['customers_fax']) ? $account['customers_fax'] : ''), 'class="form-control"');
    }
  ?>
</div>
