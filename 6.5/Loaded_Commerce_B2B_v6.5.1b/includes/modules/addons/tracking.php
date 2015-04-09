<?php
/*
  $Id: tracking.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

  class tracking {
    var $title;

    function tracking() {
      $this->code = 'tracking';
      $this->title = (defined('MODULE_ADDONS_OPT_TITLE')) ? MODULE_ADDONS_OPT_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_OPT_DESCRIPTION')) ? MODULE_ADDONS_OPT_DESCRIPTION : '';
      if (defined('MODULE_ADDONS_OPT_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_OPT_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order  = (defined('MODULE_ADDONS_OPT_SORT_ORDER')) ? (int)MODULE_ADDONS_OPT_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_OPT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_OPT_STATUS',
                   'ORDER_TRACKING_UPS',
                   'ORDER_TRACKING_FEDEX',
                   'ORDER_TRACKING_USPS',
                   'ORDER_TRACKING_CANADAPOST',
                   'ORDER_TRACKING_POSTDANMARK',
                   'ORDER_TRACKING_NEWZEALANDPOST');
    }

    function install() {
      global $languages_id;
                  
      // insert module config values
      tep_db_query("INSERT IGNORE INTO `configuration_group` (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`) VALUES ('479', 'Order Tracking', 'Order Package Tracking Plus Configuration Values', '479', '1')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable Order Package Tracking', 'MODULE_ADDONS_OPT_STATUS', 'True', '', '479', '1', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      
      // enable the different shipment tracking methods
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable UPS Tracking', 'ORDER_TRACKING_UPS', 'True', '&nbsp;', '479', '3', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable FedEx Tracking', 'ORDER_TRACKING_FEDEX', 'True', '', '479', '4', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable USPS Tracking', 'ORDER_TRACKING_USPS', 'True', '', '479', '5', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable Canada Post Tracking', 'ORDER_TRACKING_CANADAPOST', 'True', '', '479', '6', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable Post Denmark Tracking', 'ORDER_TRACKING_POSTDANMARK', 'True', '', '479', '7', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable New Zealand Post Tracking', 'ORDER_TRACKING_NEWZEALANDPOST', 'True', '', '479', '8', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");

      // alter the orders table
      $fields = mysql_list_fields(DB_DATABASE, 'orders');
      $columns = mysql_num_fields($fields);
      for ($i = 0; $i < $columns; $i++) {$field_array[] = mysql_field_name($fields, $i);}
      if (!in_array('shipment_track_num', $field_array)) {
        tep_db_query("ALTER TABLE orders ADD shipment_track_num varchar(50) NOT NULL default ''");
        tep_db_query("ALTER TABLE orders ADD shipment_method varchar(50) NOT NULL default ''");
      }
      
      // insert the shipment methods
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('1', '1', '0', 'FedEx Priority to Canada', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('2', '1', '0', 'FedEx Priority to USA', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('3', '1', '0', 'FedEx Priority International', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('4', '1', '0', 'Canada Xpresspost Post shipping', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('5', '1', '0', 'Canada USA Xpresspost shipping USA', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('6', '1', '0', 'Canada Post Standard Airmail shipping', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('7', '1', '0', 'UPS Ground', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('8', '1', '0', 'UPS Overnight', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('9', '1', '0', 'USPS Parcel Post', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('10', '1', '0', 'USPS Priority Mail', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('11', '1', '0', 'New Zealand Courier Post', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('1', '2', '0', 'FedEx Priority to Canada', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('2', '2', '0', 'FedEx Priority to USA', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('3', '2', '0', 'FedEx Priority International', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('4', '2', '0', 'Canada Xpresspost Post shipping', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('5', '2', '0', 'Canada USA Xpresspost shipping USA', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('6', '2', '0', 'Canada Post Standard Airmail shipping', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('7', '2', '0', 'UPS Ground', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('8', '2', '0', 'UPS Overnight', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('9', '2', '0', 'USPS Parcel Post', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('10', '2', '0', 'USPS Priority Mail', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('11', '2', '0', 'New Zealand Courier Post', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('1', '3', '0', 'FedEx Priority to Canada', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('2', '3', '0', 'FedEx Priority to USA', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('3', '3', '0', 'FedEx Priority International', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('4', '3', '0', 'Canada Xpresspost Post shipping', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('5', '3', '0', 'Canada USA Xpresspost shipping USA', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('6', '3', '0', 'Canada Post Standard Airmail shipping', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('7', '3', '0', 'UPS Ground', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('8', '3', '0', 'UPS Overnight', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('9', '3', '0', 'USPS Parcel Post', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('10', '3', '0', 'USPS Priority Mail', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('11', '3', '0', 'New Zealand Courier Post', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('1', '4', '0', 'FedEx Priority to Canada', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('2', '4', '0', 'FedEx Priority to USA', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('3', '4', '0', 'FedEx Priority International', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('4', '4', '0', 'Canada Xpresspost Post shipping', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('5', '4', '0', 'Canada USA Xpresspost shipping USA', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('6', '4', '0', 'Canada Post Standard Airmail shipping', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('7', '4', '0', 'UPS Ground', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('8', '4', '0', 'UPS Overnight', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('9', '4', '0', 'USPS Parcel Post', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('10', '4', '0', 'USPS Priority Mail', now())");
      tep_db_query("INSERT IGNORE INTO `orders_ship_methods` (`ship_methods_id`, `ship_method_language`, `ship_method_sort`, `ship_method`, `date_added`) VALUES ('11', '4', '0', 'New Zealand Courier Post', now())");

    }

    function remove() {
      tep_db_query("DELETE FROM `configuration_group` WHERE configuration_group_id = '479'");
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
      tep_db_query("DELETE FROM `orders_ship_methods`");
      tep_db_query("ALTER TABLE `orders` DROP COLUMN `shipment_track_num`");
      tep_db_query("ALTER TABLE `orders` DROP COLUMN `shipment_method`");
    }
  }  
?>