<?php
/*
  $Id: testimonials.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

  class testimonials {
    var $title;

    function testimonials() {
      $this->code = 'testimonials';
      $this->title = (defined('MODULE_ADDONS_CTM_TITLE')) ? MODULE_ADDONS_CTM_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_CTM_DESCRIPTION')) ? MODULE_ADDONS_CTM_DESCRIPTION : '';
      if (defined('MODULE_ADDONS_CTM_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_CTM_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order  = (defined('MODULE_ADDONS_CTM_SORT_ORDER')) ? (int)MODULE_ADDONS_CTM_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_CTM_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_CTM_STATUS',
                   'MODULE_ADDONS_CTM_MAINPAGE_TRUNCATE',
                   'MODULE_ADDONS_CTM_VVC_ON_OFF',
                   'MODULE_ADDONS_CTM_EMAIL_NOTIFICATION');
    }

    function keys2() {
      return array('TABLE_CUSTOMER_TESTIMONIALS',
                   'FILENAME_CUSTOMER_TESTIMONIALS',
                   'CONTENT_CUSTOMER_TESTIMONIALS',
                   'FILENAME_TESTIMONIALS_MANAGER');
    }


    function install() {
      global $languages_id;      
      
      // create the table
      tep_db_query("CREATE TABLE IF NOT EXISTS `customer_testimonials` (`testimonials_id` int(5) NOT NULL auto_increment,
                                                                        `testimonials_title` varchar(64) NOT NULL default '',
                                                                        `testimonials_html_text` longtext NOT NULL,
                                                                        `testimonials_name` varchar(50) NOT NULL default '',
                                                                        `testimonials_location` varchar(70) NOT NULL default '',
                                                                        `date_added` varchar(50) NOT NULL default '',
                                                                        `status` tinyint(1) NOT NULL default '1',
                                                                         PRIMARY KEY  (testimonials_id)
                                                                       ) ENGINE=MyISAM;");
      
      // insert module config group values
      tep_db_query("INSERT IGNORE INTO `configuration_group` (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`) VALUES ('525', 'CTM Configuration', 'Customer Testimonials Configuration Options', '525', '0')");
      $group_id = tep_db_insert_id();
      tep_db_query("INSERT IGNORE INTO `configuration_group` (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`) VALUES ('526', 'CTM Configuration', 'Customer Testimonials Configuration Options', '526', '0')");
      $group_id1 = tep_db_insert_id();
      
      // insert module config values
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable Customer Testimonials', 'MODULE_ADDONS_CTM_STATUS', 'True', 'Select True to enable the Customer Testimonials Module', '" . $group_id . "', '1', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Mainpage Module Truncation', 'MODULE_ADDONS_CTM_MAINPAGE_TRUNCATE', '500', 'The number of charchters to display before truncation in the mainpage module.', '" . $group_id . "', '2', now(), now(), NULL, NULL)");
      //tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Hack Attempt Warning', 'MODULE_ADDONS_CTM_HACKING_WARNING', 'False', 'Show Hacking Attempt Warning Box?', '" . $group_id . "', '3', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable VVC', 'MODULE_ADDONS_CTM_VVC_ON_OFF', 'On', 'Enable Visual Varification Code?', '" . $group_id . "', '4', now(), now(), NULL, 'tep_cfg_select_option(array(''On'', ''Off''),')");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Send Notification Emails', 'MODULE_ADDONS_CTM_EMAIL_NOTIFICATION', 'False', 'Set this to True to send the store admin an email when a new testimonial is submitted for approval.', '" . $group_id . "', '5', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', '', 'TABLE_CUSTOMER_TESTIMONIALS', 'customer_testimonials', '', '" . $group_id1 . "', '2', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', '', 'FILENAME_CUSTOMER_TESTIMONIALS', 'customer_testimonials.php', '', '" . $group_id1 . "', '3', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', '', 'CONTENT_CUSTOMER_TESTIMONIALS', 'customer_testimonials', '', '" . $group_id1 . "', '4', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', '', 'FILENAME_TESTIMONIALS_MANAGER', 'testimonials_manager.php', '', '" . $group_id1 . "', '5', now(), now(), NULL, NULL)");
      
      // insert infobox values
      $infobox_id_check = tep_db_fetch_array(tep_db_query("SELECT MAX(infobox_id) as current_infobox_id FROM infobox_configuration"));
      $next_infobox_id =   $infobox_id_check['current_infobox_id'] + 1;
      
      $template_id_check = tep_db_fetch_array(tep_db_query("SELECT template_id FROM template WHERE template_name = '" . DEFAULT_TEMPLATE . "'"));
      $template_id = $template_id_check['template_id'];
      
      tep_db_query("INSERT INTO infobox_configuration VALUES ('" . $template_id . "', '" . $next_infobox_id . "', 'customer_testimonials.php', 'BOX_HEADING_TESTIMONIALS', 'no', 'left', 525, now(), now(), 'Customer Testimonials', 'infobox', '#F39800')");
      tep_db_query("INSERT INTO infobox_heading VALUES ('" . $next_infobox_id . "', '1', 'Customer Testimonials')");
      
      // insert the first testimonial
      tep_db_query("INSERT INTO customer_testimonials VALUES ('', 'Quisque eu nisi ipsum', 'Sed a scelerisque augue. Curabitur imperdiet aliquam sem, eu egestas turpis interdum vitae. Sed iaculis molestie diam, et ullamcorper eros tempor et. In ultrices metus ac mauris cursus feugiat.', 'Lorem Ipsum', 'Florida, USA', now(), '1')");
      
      // set the admin file access permissions
      tep_db_query("INSERT INTO admin_files VALUES ('', 'testimonials.php', '1', '0', '1')");
      tep_db_query("INSERT INTO admin_files VALUES ('', 'testimonials_manager.php', '0', '" . tep_db_insert_id() . "', '1')");
      
    }

    function remove() {     
      // remove module config values
      tep_db_query("DELETE FROM `configuration_group` WHERE configuration_group_title = 'CTM Configuration'");
      tep_db_query("DELETE FROM `admin_files` WHERE admin_files_name = '" . FILENAME_CUSTOMER_TESTIMONIALS . "'");
      
      $infobox_id_check = tep_db_fetch_array(tep_db_query("SELECT infobox_id FROM infobox_configuration WHERE infobox_define = 'BOX_HEADING_TESTIMONIALS'"));
      $infobox_id = $infobox_id_check['current_infobox_id'];
      tep_db_query("DELETE FROM `infobox_heading` WHERE infobox_id = '" . $infobox_id . "'");
      tep_db_query("DELETE FROM `infobox_configuration` WHERE infobox_id = '" . $infobox_id . "'");

      
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key in ('" . implode("', '", $this->keys2()) . "')");
      
      tep_db_query("UPDATE template SET module_one = '' WHERE module_one = 'customer_testimonials.php'");
      tep_db_query("UPDATE template SET module_two = '' WHERE module_two = 'customer_testimonials.php'");
      tep_db_query("UPDATE template SET module_three = '' WHERE module_three = 'customer_testimonials.php'");
      tep_db_query("UPDATE template SET module_four = '' WHERE module_four = 'customer_testimonials.php'");
      tep_db_query("UPDATE template SET module_five = '' WHERE module_five = 'customer_testimonials.php'");
      tep_db_query("UPDATE template SET module_six = '' WHERE module_six = 'customer_testimonials.php'");
      //tep_db_query("DROP TABLE IF EXISTS `customer_testimonials`");
    }
  }  
?>