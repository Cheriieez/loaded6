<?php
/*
  $Id: quickupdates.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

  class quickupdates {
    var $title;

    function quickupdates() {
      $this->code = 'quickupdates';
      $this->title = (defined('MODULE_ADDONS_QPU_TITLE')) ? MODULE_ADDONS_QPU_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_QPU_DESCRIPTION')) ? MODULE_ADDONS_QPU_DESCRIPTION : '';
      if (defined('MODULE_ADDONS_QPU_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_QPU_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order  = (defined('MODULE_ADDONS_QPU_SORT_ORDER')) ? (int)MODULE_ADDONS_QPU_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_QPU_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_QPU_STATUS');
    }

    function install() {
      global $languages_id;
                  
      // insert module config values
      tep_db_query("INSERT IGNORE INTO `configuration_group` (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`) VALUES ('337', 'Quick Product Updates', 'Quick Product Updates Configuration Values', '337', '1')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable Quick Product Updater', 'MODULE_ADDONS_QPU_STATUS', 'True', 'Set this to True to enable the Quick Product Updater Module', '337', '1', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Modify the Model of the products', 'MODIFY_MODEL', 'true', 'Allow/Disallow the model displaying and modification.', '337', '2', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Modify the Name of the products', 'MODIFY_NAME', 'true', 'Allow/Disallow the name displaying and modification?', '337', '3', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Modify the Sort Order of the products', 'DISPLAY_SORT', 'true', 'Allow/Disallow the Products Sort Order displaying and modification.', '337', '4', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Modify the Status of the products', 'DISPLAY_STATUT', 'true', 'Allow/Disallow the Status displaying and modification.', '337', '5', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Modify the Weight of the products', 'DISPLAY_WEIGHT', 'true', 'Allow/Disallow the Weight displaying and modification.', '337', '6', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Modify the Quantity of the products', 'DISPLAY_QUANTITY', 'true', 'Allow/Disallow the Quantity displaying and modification.', '337', '7', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Modify the Manufacturer of the products', 'MODIFY_MANUFACTURER', 'true', 'Allow/Disallow the Manufacturer displaying and modification.', '337', '8', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Modify the Tax Class of the products', 'MODIFY_TAX', 'true', 'Allow/Disallow the Class of tax displaying and modification.', '337', '9', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Modify the Cost of the products (Only if Margin Reports Enabled)', 'DISPLAY_COST', 'false', 'Allow/Disallow the Products Cost displaying and modification. (Only set this to True if you have Margin Reports Enabled)', '337', '10', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Modify the MSRP of the products', 'DISPLAY_RETAIL_PRICE', 'false', 'Allow/Disallow the Products MSRP displaying and modification.', '337', '11', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Display the link to the Products Information page', 'DISPLAY_PREVIEW', 'true', 'Enable/Disable the display of the link to the Product Preview.', '337', '12', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Display the link to the Products Edit page', 'DISPLAY_EDIT', 'true', 'Enable/Disable the displaying of the link to the products edit page (Categories, Product Edit Screen).', '337', '13', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
    }

    function remove() {
      tep_db_query("DELETE FROM `configuration_group` WHERE configuration_group_id = '337'");
      tep_db_query("DELETE FROM `configuration` WHERE configuration_group_id = '337'");
    }
  }  
?>