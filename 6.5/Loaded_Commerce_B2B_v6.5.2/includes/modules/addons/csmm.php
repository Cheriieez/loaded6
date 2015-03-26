<?php
/*
  $Id: csmm.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

  class csmm {
    var $title;

    function csmm() {
      $this->code = "csmm";
      $this->title = (defined("MODULE_ADDONS_CSMM_TITLE")) ? MODULE_ADDONS_CSMM_TITLE : "";
      $this->description = (defined("MODULE_ADDONS_CSMM_DESCRIPTION")) ? MODULE_ADDONS_CSMM_DESCRIPTION : "";
      if (defined("MODULE_ADDONS_CSMM_STATUS")) {
        $this->enabled = ((MODULE_ADDONS_CSMM_STATUS == "True") ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order  = (defined("MODULE_ADDONS_CSMM_SORT_ORDER")) ? (int)MODULE_ADDONS_CSMM_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_CSMM_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array("MODULE_ADDONS_CSMM_STATUS");
    }

    function install() {
      global $languages_id;
      
      // remove old values
      tep_db_query('DELETE FROM configuration_group WHERE configuration_group_id = 429');
      tep_db_query('DELETE FROM configuration WHERE configuration_group_id = 429');
      tep_db_query('DELETE FROM configuration_group WHERE configuration_group_id = 437');
      tep_db_query('DELETE FROM configuration WHERE configuration_group_id = 437');
      
      // insert new group values
      tep_db_query('INSERT INTO configuration_group VALUES (429, "CSMM Configuration", "Customer Service Management Configuration Options", 429, 1)');
      tep_db_query('INSERT INTO configuration_group VALUES (437, "CSMM Hidden Configuration", "Customer Service Management Hidden Configuration Options", 437, 0)');
                  
      // insert module config values
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Enable Customer Service Management", "MODULE_ADDONS_CSMM_STATUS", "True", "Enable the Customer Service Management Module", 429, 1, now(), now(), NULL, "tep_cfg_select_option(array(""True"", ""False""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Open Customer Service to all Users", "SUPPORT_NO_LOGIN", "false", "Do not require Login to use Customer Service", 429, 2, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show CSMM Image in InfoBox.", "SUPPORT_SHOW_MAIN_IMAGE", "true", "Show the Customer Service Main Image in the CSMM InfoBox.", 429, 3, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Support E-Mail Address", "SUPPORT_EMAIL_ADDRESS", "' . STORE_OWNER_EMAIL_ADDRESS . '", "The E-mail address of our Tech Support Dept", 429, 4, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show Subject Entry Feild on Ticket Creation Page", "TICKET_SHOW_CUSTOMERS_SUBJECT", "true", "Set this to true if you want to show the Subject Entry Feild on the Ticket Creation Page", 429, 5, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show Order ID Dropdown on Ticket Creation Page", "TICKET_SHOW_CUSTOMERS_ORDER_IDS", "true", "Set this to true if you want to show the Order ID Dropdown on the Ticket Creation Page", 429, 6, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show the Ticket Status on Ticket Pages", "TICKET_CATALOG_USE_STATUS", "true", "Show Ticket Status", 429, 7, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show Department Value on Ticket Pages", "TICKET_CATALOG_USE_DEPARTMENT", "true", "Show Ticket Department", 429, 8, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show Priority Value on Ticket Pages", "TICKET_CATALOG_USE_PRIORITY", "true", "Show Ticket Priority", 429, 9, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Allow the customer to change the Ticket Status", "TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS", "false", "Set this to \"true\" if you wish to allow the customer to change the Ticket Status", 429, 10, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Allow the customer to change the Ticket Department", "TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT", "false", "Set this to \"true\" if you wish to allow the customer to change the Ticket Department", 429, 11, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Allow the customer to change the Ticket Priority", "TICKET_ALLOW_CUSTOMER_TO_CHANGE_PRIORITY", "false", "Set this to \"true\" if you wish to allow the customer to change the Ticket Priority", 429, 12, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show the Ticket Status in the Admin Ticket View", "TICKET_ADMIN_USE_STATUS", "true", "Show Ticket Status", 429, 13, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show the Ticket Department in the Admin Ticket View", "TICKET_ADMIN_USE_DEPARTMENT", "true", "Show Ticket Department", 429, 14, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show the Ticket Priority in the Admin Ticket View", "TICKET_ADMIN_USE_PRIORITY", "true", "Show Ticket Priority", 429, 15, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show the Ticket Subject in the Admin Ticket View", "TICKET_ADMIN_USE_SUBJECT", "true", "Show Ticket Subject", 429, 16, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show the Ticket Order ID in the Admin Ticket View", "TICKET_ADMIN_USE_ORDER_IDS", "true", "Show Ticket Order ID", 429, 17, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket View Search Results", "MAX_TICKET_SEARCH_RESULTS", 5, "Number of Tickets to list per page", 429, 18, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket View Page Links", "MAX_TICKET_PAGE_LINKS", 3, "Number of ""number"" links to use for Ticket View page-sets", 429, 19, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Subject Minimum Length", "TICKET_ENTRIES_SUBJECT_MIN_LENGTH", 3, "Minimum length of Ticket subject.", 429, 20, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Enquiry Minimum Length", "TICKET_ENTRIES_ENQUIRY_MIN_LENGTH", 10, "Minimum length of link Ticket enquiry.", 429, 21, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Admin Reply Minimum Length", "TICKET_ADMIN_ENTRIES_MIN_LENGTH", 10, "Minimum length of admin Ticket reply.", 429, 22, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Enquiry Text Area Width", "ENQUIRY_TEXT_AREA_WIDTH", 60, "Width of Ticket Create Text Area.", 429, 23, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Enquiry Text Area Height", "ENQUIRY_TEXT_AREA_HEIGHT", 15, "Height of Ticket Create Text Area.", 429, 24, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show FAQ\'s in Support InfoBox", "SUPPORT_SHOW_FAQ", "true", "Show the FAQ Categories in the Customer Service InfoBox.", 429, 25, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show Private Message Link in Support InfoBox", "SUPPORT_SHOW_MESSAGES", "true", "Show the Private Messages Link in the Customer Service InfoBox.", 429, 26, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show Date with TimeStamp", "SUPPORT_SHOW_DATETIME", "true", "Show Time Stamp with Date instead of only the Date.", 429, 27, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show Skype Buttons?", "TICKET_SHOW_SKYPE", "false", "Show Skype Contact Buttons in Customer Service Infobox.", 429, 28, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show Skype Call Option?", "SKYPE_SHOW_CALL", "false", "", 429, 29, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Show Skype Chat Option?", "SKYPE_SHOW_CHAT", "false", "", 429, 30, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Your Skype User Name.", "SKYPE_ID_NAME", "", "Your Skype User Name.", 429, 31, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Skype Button URL.", "SKYPE_BUTTON_LINK", "", "Example:<br>http://mystatus.skype.com/smallclassic/skypename<br>See:<br>http://www.skype.com/share/buttons/advanced.html.", 429, 32, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Link(URL) ID Length.", "TICKET_LINK_ID_LENGTH", "10", "Set this to the desired length of the Ticket Link(URL) ID. You <b>MUST</b> choose an <b>even</b> number between 2 and 20. Anything else will cause errors!", 429, 33, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Send Email alerts upon customer reply.", "TICKET_SEND_EMAIL_ALERT", "true", "Send Email alerts to the Support Email Address upon customer replies to tickets.", 429, 34, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Send Extra Order Email alerts upon customer reply.", "TICKET_SEND_EXTRA_EMAIL_ALERT", "true", "Send Email alerts to the Extra Order Email Address upon customer replies to tickets.", 429, 35, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "Enable SMS Ticket Messaging.", "SMS_TICKET_NOTIFY", "true", "Do you want to enable and receive SMS Ticket Notifications?", 429, 36, now(), now(), NULL, "tep_cfg_select_option(array(""true"", ""false""),")');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "SMS Ticket Notification Address", "SMS_TICKET_NOTIFY_ADDRESS", "", "Send Ticket Details via SMS using the following E-mails Addresses, in this format: Name 1 <email@address1>, Name 2 <email@address2><br>Example : Shop Owner <8015556789@vtext.com>", 429, 37, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Listing Subject Truncation Length", "TICKET_LISTING_SUBJECT_TRUNCATE", "18", "The length of the Subject text that shows on the tickets listing page.", 429, 38, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Listing Name Truncation Length", "TICKET_LISTING_NAME_TRUNCATE", "18", "The length of the Name text that shows on the tickets listing page.", 429, 39, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Listing Status Truncation Length", "TICKET_LISTING_STATUS_TRUNCATE", "18", "The length of the Status text that shows on the tickets listing page.", 429, 40, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Listing Priority Truncation Length", "TICKET_LISTING_PRIORITY_TRUNCATE", "18", "The length of the Priority text that shows on the tickets listing page.", 429, 41, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Listing Department Truncation Length", "TICKET_LISTING_DEPT_TRUNCATE", "18", "The length of the Department text that shows on the tickets listing page.", 429, 42, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Ticket Listing Auto-Refresh Time. (Length in Seconds)", "TICKET_LISTING_PAGE_REFRESH", "90", "The length of time for the Auto-Refresh on the tickets listing page.", 429, 43, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "VVC on Ticket Create", "MODULE_ADDONS_CSMM_CREATE_VVC_ON_OFF", "On", "Enable the VVC code on the Ticket Create page.", 429, 44, now(), now(), NULL, "tep_cfg_select_option(array(""On"", ""Off""),")');   
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ("", "VVC on Ticket View", "MODULE_ADDONS_CSMM_VIEW_VVC_ON_OFF", "On", "Enable the VVC code on the Ticket View page.", 429, 45, now(), now(), NULL, "tep_cfg_select_option(array(""On"", ""Off""),")');   
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Default Ticket Status ID (DO NOT EDIT).", "TICKET_DEFAULT_STATUS_ID", "1", "Default Ticket Status ID (DO NOT EDIT).", 437, 1, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Default Ticket Admin Status ID (DO NOT EDIT).", "TICKET_DEFAULT_ADMIN_STATUS_ID", "1", "Default Ticket Admin Status ID (DO NOT EDIT).", 437, 2, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Default Customer Reply Status.", "TICKET_CUSTOMER_REPLY_STATUS_ID", "1", "Default Customer Reply Status.", 437, 3, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Default Ticket Priority ID.", "TICKET_DEFAULT_PRIORITY_ID", "1", "Default Ticket Priority ID.", 437, 4, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Default Ticket Department ID.", "TICKET_DEFAULT_DEPARTMENT_ID", "3", "Default Ticket Department ID.", 437, 5, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Default Ticket Reply ID.", "TICKET_DEFAULT_REPLY_ID", "1", "Default Ticket Reply ID.", 437, 6, now(), now())');
      tep_db_query('INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ("", "Default Ticket Admin ID.", "TICKET_DEFAULT_ADMIN_ID", "0", "Default Ticket Admin ID.", 437, 7, now(), now())');
      
      // get the default template id
      $defaultTemplateName = tep_db_fetch_array(tep_db_query("SELECT configuration_value FROM configuration WHERE configuration_key = 'DEFAULT_TEMPLATE'"));
      $defaultTemplateId = tep_db_fetch_array(tep_db_query("SELECT template_id FROM template WHERE template_name = '" . $defaultTemplateName['configuration_value'] . "'"));
      $templateID = $defaultTemplateId['template_id'];
      
      // Set the infobox configuration
      tep_db_query('INSERT INTO infobox_configuration VALUES (' . (int)$templateID . ', 429429, "ticket.php", "BOX_HEADING_SUPPORT", "yes", "left", 29, now(), now(), "Customer Service", "infobox", "#F39800")');
      tep_db_query('INSERT INTO infobox_heading VALUES ("429429", 1, "Customer Service")');

      // Set the File Access configuration
      tep_db_query('INSERT INTO admin_files VALUES ("429", "ticket.php", 1, 0, 1)');
      tep_db_query('INSERT INTO admin_files VALUES ("", "ticket_admin.php", 0, 429, 1)');
      tep_db_query('INSERT INTO admin_files VALUES ("", "ticket_department.php", 0, 429, 1)');
      tep_db_query('INSERT INTO admin_files VALUES ("", "ticket_priority.php", 0, 429, 1)');
      tep_db_query('INSERT INTO admin_files VALUES ("", "ticket_reply.php", 0, 429, 1)');
      tep_db_query('INSERT INTO admin_files VALUES ("", "ticket_status.php", 0, 429, 1)');
      tep_db_query('INSERT INTO admin_files VALUES ("", "ticket_view.php", 0, 429, 1)');

      // Create Tables
      tep_db_query("CREATE TABLE IF NOT EXISTS ticket_admins (
        ticket_admin_id int(11) NOT NULL default '1',
        ticket_language_id int(11) NOT NULL default '0',
        ticket_admin_name varchar(255) NOT NULL default '',
        PRIMARY KEY  (ticket_admin_id,ticket_language_id)
      ) ENGINE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS ticket_department (
        ticket_department_id int(5) NOT NULL default '1',
        ticket_language_id int(11) NOT NULL default '0',
        ticket_department_name varchar(60) NOT NULL default '',
        PRIMARY KEY  (ticket_department_id,ticket_language_id)
      ) ENGINE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS ticket_priority (
        ticket_priority_id int(11) NOT NULL default '1',
        ticket_language_id int(11) NOT NULL default '0',
        ticket_priority_name varchar(60) NOT NULL default '',
        PRIMARY KEY  (ticket_priority_id,ticket_language_id)
      ) ENGINE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS ticket_reply (
        ticket_reply_id int(11) NOT NULL default '1',
        ticket_language_id int(11) NOT NULL default '0',
        ticket_reply_name varchar(255) NOT NULL default '',
        ticket_reply_text text NOT NULL,
        PRIMARY KEY  (ticket_reply_id,ticket_language_id)
      ) ENGINE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS ticket_status (
        ticket_status_id int(5) NOT NULL default '1',
        ticket_language_id int(11) NOT NULL default '0',
        ticket_status_name varchar(60) NOT NULL default '',
        PRIMARY KEY  (ticket_status_id, ticket_language_id)
      ) ENGINE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS ticket_status_history (
        ticket_status_history_id int(11) NOT NULL auto_increment,
        ticket_id int(11) NOT NULL default '0',
        ticket_status_id int(5) NOT NULL default '1',
        ticket_priority_id int(5) NOT NULL default '1',
        ticket_department_id int(5) NOT NULL default '1',
        ticket_date_modified datetime NOT NULL default '0000-00-00 00:00:00',
        ticket_customer_notified int(1) default '0',
        ticket_comments text,
        ticket_edited_by varchar(64) NOT NULL default '',
        PRIMARY KEY  (ticket_status_history_id)
      ) ENGINE=MyISAM AUTO_INCREMENT=10;");

      tep_db_query("CREATE TABLE IF NOT EXISTS ticket_ticket (
        ticket_id int(11) NOT NULL auto_increment,
        ticket_link_id varchar(32) NOT NULL default '',
        ticket_customers_id int(12) NOT NULL default '0',
        ticket_customers_orders_id int(11) NOT NULL default '0',
        ticket_customers_email varchar(96) NOT NULL default '',
        ticket_customers_name varchar(96) NOT NULL default '',
        ticket_subject varchar(96) NOT NULL default '',
        ticket_status_id int(5) NOT NULL default '1',
        ticket_department_id int(5) NOT NULL default '1',
        ticket_priority_id int(5) NOT NULL default '1',
        ticket_date_created datetime NOT NULL default '0000-00-00 00:00:00',
        ticket_date_last_modified datetime NOT NULL default '0000-00-00 00:00:00',
        ticket_date_last_customer_modified datetime NOT NULL default '0000-00-00 00:00:00',
        ticket_login_required tinyint(4) NOT NULL default '0',
        PRIMARY KEY  (ticket_id)
      ) ENGINE=MyISAM AUTO_INCREMENT=6;");

      tep_db_query("CREATE TABLE IF NOT EXISTS customer_private_message (
        message_id int(11) NOT NULL,
        customers_id int(11) NOT NULL,
        message_desc LONGTEXT NOT NULL,
        message_write_date datetime default NULL,
        message_stat char(3) NOT NULL default 'No',
        message_forall char(1) NOT NULL,
        PRIMARY KEY (message_id,customers_id)
      ) ENGINE=MyISAM;");

      // Create Admin
      tep_db_query('INSERT INTO ticket_admins VALUES (0, 1, "Customer Service Admin")');

      // Create Departments
      tep_db_query('INSERT INTO ticket_department VALUES (1, 1, "Sales")');
      tep_db_query('INSERT INTO ticket_department VALUES (2, 1, "Marketing")');
      tep_db_query('INSERT INTO ticket_department VALUES (3, 1, "Support")');
      tep_db_query('INSERT INTO ticket_department VALUES (4, 1, "Affiliate")');

      // Create Priorities
      tep_db_query('INSERT INTO ticket_priority VALUES (1, 1, "Low")');
      tep_db_query('INSERT INTO ticket_priority VALUES (2, 1, "Medium")');
      tep_db_query('INSERT INTO ticket_priority VALUES (3, 1, "High")');
      tep_db_query('INSERT INTO ticket_priority VALUES (4, 1, "Urgent")');
      tep_db_query('INSERT INTO ticket_priority VALUES (5, 1, "Emergency")');

      // Create Statuses
      tep_db_query('INSERT INTO ticket_status VALUES (1, 1, "Open")');
      tep_db_query('INSERT INTO ticket_status VALUES (2, 1, "On Hold")');
      tep_db_query('INSERT INTO ticket_status VALUES (3, 1, "Closed")');
      tep_db_query('INSERT INTO ticket_status VALUES (4, 1, "Awaiting Customer Reply")');
    }

    function remove() { 
      // remove configuration group values
      tep_db_query("DELETE FROM configuration_group WHERE configuration_group_id = 429");
      tep_db_query("DELETE FROM configuration_group WHERE configuration_group_id = 437");
      tep_db_query("DELETE FROM configuration WHERE configuration_group_id = 429");
      tep_db_query("DELETE FROM configuration WHERE configuration_group_id = 437");
      
      // remove infobox values
      tep_db_query("DELETE FROM infobox_heading WHERE box_heading = 'Customer Service'"); 
      tep_db_query("DELETE FROM infobox_configuration WHERE infobox_define = 'BOX_HEADING_SUPPORT'");
      
      // remove admin files values
      tep_db_query("DELETE FROM admin_files WHERE admin_files_to_boxes = '429'");
      tep_db_query("DELETE FROM admin_files WHERE admin_files_name = 'ticket.php'");
      
      // drop the tables from the database
      tep_db_query("DROP TABLE IF EXISTS ticket_admins");
      tep_db_query("DROP TABLE IF EXISTS ticket_department");
      tep_db_query("DROP TABLE IF EXISTS ticket_priority");
      tep_db_query("DROP TABLE IF EXISTS ticket_reply");
      tep_db_query("DROP TABLE IF EXISTS ticket_status");
      tep_db_query("DROP TABLE IF EXISTS ticket_status_history");
      tep_db_query("DROP TABLE IF EXISTS ticket_ticket");
      tep_db_query("DROP TABLE IF EXISTS customer_private_message");
    }
  }  
?>