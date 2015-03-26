<?php
/*
  $Id: tabs.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

  class tabs {
    var $title;

    function tabs() {
      $this->code = 'tabs';
      $this->title = (defined('MODULE_ADDONS_TABS_TITLE')) ? MODULE_ADDONS_TABS_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_TABS_DESCRIPTION')) ? MODULE_ADDONS_TABS_DESCRIPTION : '';
      if (defined('MODULE_ADDONS_TABS_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_TABS_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order  = (defined('MODULE_ADDONS_TABS_SORT_ORDER')) ? (int)MODULE_ADDONS_TABS_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_TABS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_TABS_STATUS', 'TEXT_PRODUCTS_TAB_DESCRIPTION','TEXT_PRODUCTS_TAB_2_TITLE','TEXT_PRODUCTS_TAB_3_TITLE','TEXT_PRODUCTS_TAB_4_TITLE');
    }

    function install() {
      global $languages_id;
                  
      // insert module config values
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable Product Tabs for edit screen in admin.', 'MODULE_ADDONS_TABS_STATUS', 'True', 'Enable the Product Tabs Module for Admin Edit Product Screen.', '6', '1', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Product Description Tab.', 'TEXT_PRODUCTS_TAB_DESCRIPTION', 'Overview', 'Title Text For Product Description Tab.', '6', '2', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', '2nd Tab.', 'TEXT_PRODUCTS_TAB_2_TITLE', 'Features', 'Title Text For 2nd Tab', '6', '3', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', '3rd Tab.', 'TEXT_PRODUCTS_TAB_3_TITLE', 'Specifications', 'Title Text For 3rd Tab', '6', '4', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', '4th Tab.', 'TEXT_PRODUCTS_TAB_4_TITLE', 'Warranty', 'Title Text For 4th Tab', '6', '4', now(), now(), NULL, NULL)");

      
      // make database modifications
    $check_column = tep_db_query("SHOW COLUMNS FROM `products_description`");
    $numColumns = tep_db_num_rows($check_column); 
    $x = 0; 
    while ($x < $numColumns) { 
        $colname = mysql_fetch_row($check_column); 
        $col[] = $colname[0]; 
        $x++; 
    } 
	if(!in_array('products_tab_2', $col)){
		tep_db_query("ALTER TABLE `products_description` ADD `products_tab_2` TEXT DEFAULT NULL AFTER `products_description`;");
	}
	if(!in_array('products_tab_3', $col)){
		tep_db_query("ALTER TABLE `products_description` ADD `products_tab_3` TEXT DEFAULT NULL AFTER `products_tab_2`;");
	} 
	if(!in_array('products_tab_4', $col)){
		tep_db_query("ALTER TABLE `products_description` ADD `products_tab_4` TEXT DEFAULT NULL AFTER `products_tab_3`;");
	}
    }


    function remove() {
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");     
    }
  }  
?>