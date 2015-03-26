<?php
/*
  $Id: get1free.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

  class get1free {
    var $title;

    function get1free() {
      $this->code = 'get1free';
      $this->title = (defined('MODULE_ADDONS_GET1FREE_TITLE')) ? MODULE_ADDONS_GET1FREE_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_GET1FREE_DESCRIPTION')) ? MODULE_ADDONS_GET1FREE_DESCRIPTION : '';
      if (defined('MODULE_ADDONS_GET1FREE_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_GET1FREE_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order  = (defined('MODULE_ADDONS_GET1FREE_SORT_ORDER')) ? (int)MODULE_ADDONS_GET1FREE_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_GET1FREE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_GET1FREE_STATUS',
                   'GET1FREE_PRODUCTINFO_PLACEMENT');
    }

    function install() {
      global $languages_id;
                  
      // insert module config values
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable Get 1 FREE', 'MODULE_ADDONS_GET1FREE_STATUS', 'True', 'Enable the Get 1 FREE Module', '6', '999', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Get 1 FREE Page Location', 'GET1FREE_PRODUCTINFO_PLACEMENT', 'top', 'Select the page placement of the Get 1 FREE Notification.', '6', '999', now(), now(), NULL, 'tep_cfg_select_option(array(''top'', ''underpriceheading'', ''bottom''), ')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Get 1 FREE Table Definition', 'TABLE_GET_1_FREE', 'get_1_free', 'Define the Get 1 FREE Table Name.', '6', '999', now(), now(), NULL, 'tep_cfg_select_option(array(''get_1_free''), ')");
      
      // create database table
      tep_db_query("CREATE TABLE IF NOT EXISTS `get_1_free` (
                   `get_1_free_id` int(11) NOT NULL auto_increment,
                   `products_id` int(11) NOT NULL default '0',
                   `products_qualify_quantity` int(11) NOT NULL default '0',
                   `products_multiple` int(11) NOT NULL default '0',
                   `products_free_id` int(11) NOT NULL default '0',
                   `products_free_quantity` int(11) NOT NULL default '0',
                   `get_1_free_date_added` datetime default NULL,
                   `get_1_free_last_modified` datetime default NULL,
                   `get_1_free_expires_date` datetime default NULL,
                   `date_status_change` datetime default NULL,
                   `status` int(1) NOT NULL default '1',
                   PRIMARY KEY  (`get_1_free_id`)
                 ) ENGINE=MyISAM AUTO_INCREMENT=1;");
    }

    function remove() {
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'MODULE_ADDONS_GET1FREE_STATUS'");
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'GET1FREE_PRODUCTINFO_PLACEMENT'");
      //tep_db_query("DROP TABLE IF EXISTS `get_1_free`");
    }
  }  
?>