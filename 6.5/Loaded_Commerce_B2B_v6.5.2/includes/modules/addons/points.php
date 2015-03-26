<?php
/*
  $Id: points.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

  class points {
    var $title;

    function points() {
      $this->code = 'points';
      $this->title = (defined('MODULE_ADDONS_POINTS_TITLE')) ? MODULE_ADDONS_POINTS_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_POINTS_DESCRIPTION')) ? MODULE_ADDONS_POINTS_DESCRIPTION : '';
      if (defined('MODULE_ADDONS_POINTS_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_POINTS_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order  = (defined('MODULE_ADDONS_POINTS_SORT_ORDER')) ? (int)MODULE_ADDONS_POINTS_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_POINTS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_POINTS_STATUS');
    }

    function install() {
      global $languages_id;      
      
      // create the table
      tep_db_query("CREATE TABLE IF NOT EXISTS customers_points_pending (unique_id INT(11) NOT NULL AUTO_INCREMENT, 
                                                                         customer_id INT(11) NOT NULL DEFAULT '0',
                                                                         orders_id INT(11) NOT NULL DEFAULT '0',
                                                                         points_pending DECIMAL(15,2) NOT NULL DEFAULT '0.00',    
                                                                         points_comment VARCHAR(200),
                                                                         date_added DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', 
                                                                         points_status INT(1) NOT NULL DEFAULT '1', 
                                                                         points_type VARCHAR(2) NOT NULL DEFAULT 'SP', 
                                                                         PRIMARY KEY  (unique_id)
                                                                         ) ENGINE=MyISAM;");
                                                                         
      // check the customers table in case this is upgrade 
      $fields = mysql_list_fields(DB_DATABASE, TABLE_CUSTOMERS);
      $columns = mysql_num_fields($fields);
      for ($i = 0; $i < $columns; $i++) {
        $field_array[] = mysql_field_name($fields, $i);
      }
      if (!in_array('customers_shopping_points', $field_array)) {
        // alter the customers table only if needed
        tep_db_query("ALTER TABLE customers ADD customers_shopping_points DECIMAL(15, 2) NOT NULL DEFAULT '0.00', ADD customers_points_expires DATE NULL DEFAULT NULL, ADD customers_points_ip varchar(15) NOT NULL default ''");
        tep_db_query("UPDATE `customers` set customers_points_expires = DATE_ADD(NOW(), INTERVAL '12' MONTH) WHERE customers_points_expires IS NULL");
      }
            
      // insert module config group values
      tep_db_query("INSERT IGNORE INTO configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES ('519', 'Points & Rewards', 'Points & Rewards Configuration Options', '519', '1')");
      
      // insert module config values
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Enable Points & Rewards system', 'MODULE_ADDONS_POINTS_STATUS', 'True', 'Enable the Points & Rewards System so customers can earn points for orders made?', '519', '1', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Enable Redemptions system', 'USE_REDEEM_SYSTEM', 'true', 'Enable customers to Redeem points at checkout?', '519', '2', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Points per 1 Dollar purchase', 'POINTS_PER_AMOUNT_PURCHASE', '1', 'No. of points awarded for each 1 Dollar spent.<br>(Currency defined according to admin DEFAULT currency)', '519', '3', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'The value of 1 point when Redeemed', 'REDEEM_POINT_VALUE', '0.1', 'The value of one point.<br>(point value currency defined according to admin DEFAULT currency)', '519', '4', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Points Decimal Places', 'POINTS_DECIMAL_PLACES', '0', 'Pad the points value this amount of decimal places', '519', '5', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Auto Credit Pending Order Points', 'POINTS_AUTO_ON', '', 'Enable Auto Credit Pending Order Points and set a days period before the reward points will actually added to customers account.<br>For same day set to 0(zero).<br>To disable this option leave empty.', '519', '6', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Auto Credit Pending Review Points', 'POINTS_REVIEWS_AUTO_ON', 'false', 'Enable Auto Credit Pending Review Points.', '519', '7', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Auto Expires Points', 'POINTS_AUTO_EXPIRES', '12', 'Set a month period before points will auto Expires.<br>To disable this option leave empty.', '519', '8', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Points Expires Auto Remainder', 'POINTS_EXPIRES_REMIND', '30', 'Enable Points Expires Auto Remainder and set the numbers of days prior points expiration for the script to run.(Auto Expires Points must be enabled)<br>To disable this option leave empty.', '519', '9', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Award points for Shipping', 'USE_POINTS_FOR_SHIPPING', 'false', 'Enable customers to earn points for shipping fees?', '519', '10', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Award points for Tax', 'USE_POINTS_FOR_TAX', 'false', 'Enable customers to earn points for Tax?', '519', '11', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Award points for Specials', 'USE_POINTS_FOR_SPECIALS', 'true', 'Enable customers to earn points for items already discounted?<br>When set to false, Points awarded only on items with full price', '519', '12', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Award points for orders with Redeemed Points', 'USE_POINTS_FOR_REDEEMED', 'true', 'When order made with Redeemed Points. Enable customers to earn points for the amount spend other then points?<br>When set to false, customers will NOT awarded even if only part of the payment made by points.', '519', '13', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Award points for Products Reviews', 'USE_POINTS_FOR_REVIEWS', '50', 'If you want to award points when customers add Product Review, set the points amount to be given or leave empty to disable this option', '519', '14', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Enable and set points for Referral System', 'USE_REFERRAL_SYSTEM', '100', 'Do you want to Enable the Referral System and award points when customers refer someone?<br>Set the amount of points to be given or leave empty to disable this option.', '519', '15', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Enable Products Model Restriction', 'RESTRICTION_MODEL', '', 'Restriction of Points by Products Model.<br>Set a comma separated list of Product Models to Restrict or leave empty to allow points for all Product Models.', '519', '16', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Enable Products ID Restriction', 'RESTRICTION_PID', '', 'Restriction of Points by Product ID.<br>Set a comma separated list of Product IDs to Restrict or leave empty to allow Points for all Products IDs.', '519', '17', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Enable Categories ID Restriction', 'RESTRICTION_PATH', '', 'Restriction of Products by Categories ID.<br>Set a comma separated list of Category IDs to Restrict or leave empty to allow points for all Categories.', '519', '18', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Enable Products Price Restriction', 'REDEMPTION_DISCOUNTED', 'false', 'When customers redeem points, do you want to exclude items already discounted ?<br>Redemptions enabled only on items with full price', '519', '19', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'If you wish to limit points before Redemptions, set points limit to', 'POINTS_LIMIT_VALUE', '0', 'Set the No. of points nedded before they can be redeemed. set to 0 if you wish to disable it', '519', '20', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'If you wish to limit points to be use per order, set points Max to', 'POINTS_MAX_VALUE', '1000', 'Set the Maximum No. of points customer can redeem per order. to avoid points maximum limit, set to high No. ', '519', '21', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Restrict Points Redemption For Minimum Purchase Amount', 'POINTS_MIN_AMOUNT', '', 'Enter the Minimum Purchase Amount(total cart contain) required before Redemptions enabled.<br>Leave empty for no Restriction', '519', '22', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'New signup customers Welcome Points amount', 'NEW_SIGNUP_POINT_AMOUNT', '100', 'Set the Welcome Points amount to be auto-credited for New signup customers. set to 0 if you wish to disable it', '519', '23', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Maximum number of points records to display', 'MAX_DISPLAY_POINTS_RECORD', '20', 'Set the Maximum number of points records to display per page in my_points.php page', '519', '24', NOW(), NOW(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Display Points information in Product info page', 'DISPLAY_POINTS_INFO', 'true', 'Do you want to show Points information Product info page?', '519', '25', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Display Points information in Orders Listing page', 'DISPLAY_ORDERS_POINTS_INFO', 'true', 'Do you want to show Points information in the Order Listing page?', '519', '26', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Keep Records of Redeemed Points', 'DISPLAY_POINTS_REDEEMED', 'true', 'Do you want to keep records of all Points redeemed?', '519', '27', NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Save referrer', 'KEEP_REFERRER_ID', 'true', 'Save the customer referrer and don''t ask everytime to customer?', 519, 30, NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Auto Update Order Status', 'POINTS_AUTO_UPDATE_ORDER_STATUS', 'false', 'Auto update the order status to \"Allow Download\" or other desired status if Points Covers the order total.<br><br>This is sometimes needed for digital products to allow the customer access to the file upon checkout success.', 519, 30, NOW(), NOW(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");      
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('', 'Set Completed Order Status', 'MODULE_PAYMENT_REDEMPTIONS_ORDER_STATUS_COMPLETE_ID', '0', 'For Completed orders, set the status of orders made with this points to this value', '519', '32', NOW(), NOW(), 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(')");
      
      // install the order total module for the cutomer
      tep_db_query("INSERT IGNORE INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('', 'Sort Order', 'MODULE_ORDER_TOTAL_REDEMPTIONS_SORT_ORDER', '740', 'Sort order of display.', '6', '519', now())");
      
      // set the admin file access permissions
      tep_db_query("INSERT INTO admin_files VALUES ('', 'customers_points.php', '0', '5', '1')");
      tep_db_query("INSERT INTO admin_files VALUES ('', 'customers_points_orders.php', '0', '5', '1')");
      tep_db_query("INSERT INTO admin_files VALUES ('', 'customers_points_pending.php', '0', '5', '1')");
      
    }

    function remove() {     
      // remove module config values
      tep_db_query("DELETE FROM configuration_group WHERE configuration_group_id = '519'");
      tep_db_query("DELETE FROM configuration WHERE configuration_group_id = '519'");
      tep_db_query("DELETE FROM configuration WHERE configuration_key = 'MODULE_ORDER_TOTAL_REDEMPTIONS_SORT_ORDER'");
      tep_db_query("DELETE FROM admin_files WHERE admin_files_name = 'customers_points.php'");
      tep_db_query("DELETE FROM admin_files WHERE admin_files_name = 'customers_points_orders.php'");
      tep_db_query("DELETE FROM admin_files WHERE admin_files_name = 'customers_points_pending.php'");
      tep_db_query("DROP TABLE IF EXISTS customers_points_pending");
      tep_db_query("ALTER TABLE customers DROP COLUMN customers_shopping_points, DROP COLUMN customers_points_expires, DROP COLUMN customers_points_ip");
      tep_db_query("DELETE FROM configuration WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }  
?>
