<?php
/*
  $Id: mp_slide_show.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

  class mp_slide_show {
    var $title;

    function mp_slide_show() {
      $this->code = 'mp_slide_show';
      $this->title = (defined('MODULE_ADDONS_PCSLIDESHOW_TITLE')) ? MODULE_ADDONS_PCSLIDESHOW_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_PCSLIDESHOW_DESCRIPTION')) ? MODULE_ADDONS_PCSLIDESHOW_DESCRIPTION : '';
      if (defined('MODULE_ADDONS_PCSLIDESHOW_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_PCSLIDESHOW_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_PCSLIDESHOW_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_PCSLIDESHOW_STATUS', 
                   'MODULE_ADDONS_PCSLIDESHOW_MAX_DISPLAY_NEW_PRODUCTS',
                   'MODULE_ADDONS_PCSLIDESHOW_MAX_DISPLAY_SPECIALS',
                   'MODULE_ADDONS_PCSLIDESHOW_MAX_DISPLAY_BESTSELLERS',
                   'MODULE_ADDONS_PCSLIDESHOW_FX',
                   'MODULE_ADDONS_PCSLIDESHOW_EASING',
                   'MODULE_ADDONS_PCSLIDESHOW_SYNC',
                   'MODULE_ADDONS_PCSLIDESHOW_SPEED',
                   'MODULE_ADDONS_PCSLIDESHOW_TIMEOUT',
                   'MODULE_ADDONS_PCSLIDESHOW_PAUSE',
                   'MODULE_ADDONS_PCSLIDESHOW_RANDOM',
                   'MODULE_ADDONS_PCSLIDESHOW_IMAGE_QUALITY',
                   'MODULE_ADDONS_PCSLIDESHOW_MAX_IMAGE_HEIGHT',
                   'MODULE_ADDONS_PCSLIDESHOW_MAX_IMAGE_WIDTH'
                   );
    }


    function install() {
      global $languages_id;      
      
      // modify configuration table
      tep_db_query("ALTER TABLE `configuration` CHANGE `set_function` `set_function` VARCHAR( 2047 );");

      
      // insert module config group values
      tep_db_query("INSERT INTO configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES ('', 'Products Slideshow', 'Configuration options for Products Cycle Slideshow', 99, 0);");
      $group_id = tep_db_insert_id();
      
      // insert module config values
      tep_db_query("INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('', 'Enable Product Slide Show', 'MODULE_ADDONS_PCSLIDESHOW_STATUS', 'True', 'Select True to enable the Product Slide Show Module', '" . $group_id . "', '1', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT INTO configuration SET configuration_title='Items - Number of New Products', date_added=NOW(), sort_order='2', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_MAX_DISPLAY_NEW_PRODUCTS', configuration_value='2', configuration_description='<p>How many new products should be in the slideshow?</p><p><b>Note:</b> This is the maximum value. If fewer products are found, the found ones will be used.</p>', use_function = NULL, set_function = NULL;");
      tep_db_query("INSERT INTO configuration SET configuration_title='Items - Number of Specials', date_added=NOW(), sort_order='3', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_MAX_DISPLAY_SPECIALS', configuration_value='2', configuration_description='<p>How many specials should be in the slideshow?</p><p><b>Note:</b> This is the maximum value. If fewer products are found, the found ones will be used.</p>', use_function = NULL, set_function = NULL;");
      tep_db_query("INSERT INTO configuration SET configuration_title='Items - Number of Bestsellers', date_added=NOW(), sort_order='4', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_MAX_DISPLAY_BESTSELLERS', configuration_value='2', configuration_description='<p>How many bestsellers should be in the slideshow?</p><p><b>Note:</b> This is the maximum value. If fewer products are found, the found ones will be used.</p>', use_function = NULL, set_function = NULL;");
      tep_db_query("INSERT INTO configuration SET configuration_title='FX - Transition FX', date_added=NOW(), sort_order='5', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_FX', configuration_value='scrollHorz', configuration_description='<p>Which transition FX should be used?</p>', use_function = NULL, set_function = 'tep_cfg_select_option(array(\'blindX\', \'blindY\', \'blindZ\', \'cover\', \'curtainX\', \'curtainY\', \'fade\', \'fadeZoom\', \'growX\', \'growY\', \'scrollUp\', \'scrollDown\', \'scrollLeft\', \'scrollRight\', \'scrollHorz\', \'scrollVert\', \'shuffle\', \'slideX\', \'slideY\', \'toss\', \'turnUp\', \'turnDown\', \'turnLeft\', \'turnRight\', \'uncover\', \'wipe\', \'zoom\'),';");
      tep_db_query("INSERT INTO configuration SET configuration_title='FX - Easing', date_added=NOW(), sort_order='6', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_EASING', configuration_value='None', configuration_description='<p>Which easing style should be used?</p>', use_function = NULL, set_function = 'tep_cfg_select_option(array(\'None\',\'easeInQuad\', \'easeOutQuad\', \'easeInOutQuad\', \'easeInCubic\', \'easeOutCubic\', \'easeInOutCubic\', \'easeInQuart\', \'easeOutQuart\', \'easeInOutQuart\', \'easeInQuint\', \'easeOutQuint\', \'easeInOutQuint\', \'easeInSine\', \'easeOutSine\', \'easeInOutSine\', \'easeInExpo\', \'easeOutExpo\', \'easeInOutExpo\', \'easeInCirc\', \'easeOutCirc\', \'easeInOutCirc\', \'easeInElastic\', \'easeOutElastic\', \'easeInOutElastic\', \'easeInBack\', \'easeOutBack\', \'easeInOutBack\', \'easeInBounce\', \'easeOutBounce\', \'easeInOutBounce\'),';");
      tep_db_query("INSERT INTO configuration SET configuration_title='FX - Sync transitions?', date_added=NOW(), sort_order='7', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_SYNC', configuration_value='true', configuration_description='The sync option controls whether the slide transitions occur simultaneously. The default is true which means that the current slide transitions out as the next slide transitions in.', use_function = NULL, set_function = 'tep_cfg_select_option(array(\'true\', \'false\'),';");
      tep_db_query("INSERT INTO configuration SET configuration_title='FX - Transition Speed', date_added=NOW(), sort_order='8', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_SPEED', configuration_value='2000', configuration_description='<p>The duration of the transition in milliseconds</p>', use_function = NULL, set_function = NULL;");
      tep_db_query("INSERT INTO configuration SET configuration_title='FX - Timeout', date_added=NOW(), sort_order='9', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_TIMEOUT', configuration_value='8000', configuration_description='<p>The time in milliseconds between transitions</p>', use_function = NULL, set_function = NULL;");
      tep_db_query("INSERT INTO configuration SET configuration_title='General - Pause onMouseOver?', date_added=NOW(), sort_order='10', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_PAUSE', configuration_value='true', configuration_description='<p>Pause the slideshow on mouse over?</p>', use_function = NULL, set_function = 'tep_cfg_select_option(array(\'true\', \'false\'),';");
      tep_db_query("INSERT INTO configuration SET configuration_title='General - Display randomly?', date_added=NOW(), sort_order='11', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_RANDOM', configuration_value='false', configuration_description='<p>Display the items in random order?</p>', use_function = NULL, set_function = 'tep_cfg_select_option(array(\'true\', \'false\'),';");
      tep_db_query("INSERT INTO configuration SET configuration_title='General - Image Quality', date_added=NOW(), sort_order='12', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_IMAGE_QUALITY', configuration_value='85', configuration_description='<p>Which quality should the images in the slideshow have? (1-100)</p>', use_function = NULL, set_function = NULL;");
      tep_db_query("INSERT INTO configuration SET configuration_title='General - Image Height', date_added=NOW(), sort_order='13', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_MAX_IMAGE_HEIGHT', configuration_value='292', configuration_description='<p>Set image height for slide show.</p>', use_function = NULL, set_function = NULL;");
      tep_db_query("INSERT INTO configuration SET configuration_title='General - Image Width', date_added=NOW(), sort_order='14', configuration_group_id=" . $group_id . ", configuration_key='MODULE_ADDONS_PCSLIDESHOW_MAX_IMAGE_WIDTH', configuration_value='482', configuration_description='<p>Set image width for slide show.</p>', use_function = NULL, set_function = NULL;");
    }

    function remove() {     
      tep_db_query("DELETE FROM `configuration_group` WHERE configuration_group_title = 'Products Slideshow'");
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }  
?>