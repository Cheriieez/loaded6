<?php
/*
  $Id: highslide.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

  class highslide {
    var $title;

    function highslide() {
      $this->code = 'highslide';
      $this->title = (defined('MODULE_ADDONS_HIGHSLIDE_TITLE')) ? MODULE_ADDONS_HIGHSLIDE_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_HIGHSLIDE_DESCRIPTION')) ? MODULE_ADDONS_HIGHSLIDE_DESCRIPTION : '';
      if (defined('MODULE_ADDONS_HIGHSLIDE_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_HIGHSLIDE_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order  = (defined('MODULE_ADDONS_HIGHSLIDE_SORT_ORDER')) ? (int)MODULE_ADDONS_HIGHSLIDE_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_HIGHSLIDE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_HIGHSLIDE_STATUS');
    }

    function install() {
      global $languages_id;
                  
      // insert module config values
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable HighSlide Extra Images', 'MODULE_ADDONS_HIGHSLIDE_STATUS', 'True', 'Enable the HighSlide Extra Images Module', '6', '999', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
    }

    function remove() {
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'MODULE_ADDONS_HIGHSLIDE_STATUS'");
    }
  }  
?>