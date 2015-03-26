<?php
/*
  $Id: message.php,v 1.1.1.1 2006/08/18 23:41:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class message {
    var $title, $output;

// class constructor
    function message() {
      $this->code = 'message';
      $this->title = MODULE_CHECKOUT_SUCCESS_MESSAGE_TITLE;
      $this->description = MODULE_CHECKOUT_SUCCESS_MESSAGE_DESCRIPTION;
      if (defined('MODULE_CHECKOUT_SUCCESS_MESSAGE_STATUS')) {
        $this->enabled = ((MODULE_CHECKOUT_SUCCESS_MESSAGE_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order = MODULE_CHECKOUT_SUCCESS_MESSAGE_SORT_ORDER;
      $this->output = array();
    }

    function process() {
      global $languages_id;

      if (!$this->enabled) { return; }

      $output_text ='';
      $pID = MODULE_CHECKOUT_SUCCESS_MESSAGE_PAGE;
      $pages_page_query = tep_db_query("select pages_title, pages_body from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$pID . "' and language_id = '" . (int)$languages_id . "'");
      if ($pages_page = tep_db_fetch_array($pages_page_query)) {
        if (MODULE_CHECKOUT_SUCCESS_MESSAGE_TABLE_BORDER == 'True') {
          $output_text .= '<tr><td valign="top" width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td bgcolor="#99AECE"><table width="100%" border="0" cellspacing="0" cellpadding="1">';
          $output_text .= '<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="1"><tr><td bgcolor="#f8f8f9"><table width="100%" border="0" cellspacing="0" cellpadding="4"><tr><td>';
        }
        $output_text .= '<tr><td><table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';
        if (MODULE_CHECKOUT_SUCCESS_MESSAGE_DISPLAY_TITLE == 'True') {
          $output_text .= '<td class="pageHeading">' . $pages_page['pages_title'] . '</td>';
        }
        $output_text .= '</tr></table></td></tr>';
        $output_text .= '<tr><td class="main">' . $pages_page['pages_body'] . '</td></tr>';
        if (MODULE_CHECKOUT_SUCCESS_MESSAGE_TABLE_BORDER == 'True') {
          $output_text .= '</td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr><tr><td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td></tr>';
        }
        $this->output[] = array('text' => $output_text);
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_CHECKOUT_SUCCESS_MESSAGE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function install() {
      global $languages_id;

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Message Module?', 'MODULE_CHECKOUT_SUCCESS_MESSAGE_STATUS', 'True', 'Do you want to enable the Message Checkout Success module?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Table Border?', 'MODULE_CHECKOUT_SUCCESS_MESSAGE_TABLE_BORDER', 'False', 'Display output within a table border on checkout success?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Message Page', 'MODULE_CHECKOUT_SUCCESS_MESSAGE_PAGE', '0', 'Select the Page that you want to display on checkout success.<br><br>NOTE: The page used as a message should be marked in-active so that it is not also found when browsing the catalog.<br>', '6', '2', 'tep_cfg_pull_down_message_page(', 'tep_get_message_page_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Page Title?', 'MODULE_CHECKOUT_SUCCESS_MESSAGE_DISPLAY_TITLE', 'False', 'Also display the Page Title?', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CHECKOUT_SUCCESS_MESSAGE_SORT_ORDER', '0', 'Sort order of display.', '6', '4', now())");     
    }

    function keys() {
      return array('MODULE_CHECKOUT_SUCCESS_MESSAGE_STATUS', 
                       'MODULE_CHECKOUT_SUCCESS_MESSAGE_TABLE_BORDER', 
                       'MODULE_CHECKOUT_SUCCESS_MESSAGE_PAGE', 
                       'MODULE_CHECKOUT_SUCCESS_MESSAGE_DISPLAY_TITLE', 
                       'MODULE_CHECKOUT_SUCCESS_MESSAGE_SORT_ORDER');
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }

  function tep_cfg_pull_down_message_page($pages_id, $key = '') {
      global $languages_id;

      $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

      $pages_array = array(array('id' => '0', 'text' => TEXT_MESSAGE_DEFAULT));
      $pages_query = tep_db_query("select pages_id, pages_title from " . TABLE_PAGES_DESCRIPTION . " where language_id = '" . (int)$languages_id . "' order by pages_title");
      while ($pages = tep_db_fetch_array($pages_query)) {
        $pages_array[] = array('id' => $pages['pages_id'],
                                        'text' => $pages['pages_title']);
      }
      return tep_draw_pull_down_menu($name, $pages_array, $pages_id);
    }

    function tep_get_message_page_name($pages_id, $language_id = '') {
      global $languages_id;

      if ($pages_id < 1) return TEXT_MESSAGE_DEFAULT;

      if (!is_numeric($language_id)) $language_id = $languages_id;

      $page_query = tep_db_query("select pages_title from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$pages_id . "' and language_id = '" . (int)$language_id . "'");
      $page = tep_db_fetch_array($page_query);

      return $page['pages_title'];
    } 
?>