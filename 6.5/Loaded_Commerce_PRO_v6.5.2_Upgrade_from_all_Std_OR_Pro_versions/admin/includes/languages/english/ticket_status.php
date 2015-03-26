<?php

/*
  $Id: ticket_status.php,v 1.3 2003/04/25 21:37:11 hook Exp $

  Contribution based on:
  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  CRELoaded, Open Source E-Commerce Software
  http://creloaded.com

  Copyright (c) 2007 CRELoaded

  Contribution Central, Custom CRELoaded and osCommerce Programming
  http://contributioncentral.com

  Copyright (c) 2007 Contribution Central

  ported to creloaded by: maestro
  translations via "http://translation2.paralink.com" by: maestro  

  Released under the GNU General Public License
*/
 
define('HEADING_TITLE', 'Define Ticket Status');
define('TABLE_HEADING_TEXT_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_DEFAULT_REPLY', 'Default Customer-Answer-Status');
define('TEXT_DISPLAY_NUMBER_OF_TEXT_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tickets status)');
define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_TEXT_STATUS_NAME', 'Ticket Status:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new status with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this ticket status?');
define('TEXT_INFO_HEADING_NEW_TEXT_STATUS', 'New Ticket Status');
define('TEXT_INFO_HEADING_EDIT_TEXT_STATUS', 'Edit Ticket Status');
define('TEXT_INFO_HEADING_DELETE_TEXT_STATUS', 'Delete Ticket Status');
define('TEXT_SET_DEFAULT_REPLY', 'New status if the customer replies');
define('TEXT_SET_ADMIN_DEFAULT', 'New status if the admin replies');
define('TEXT_ADMIN_DEFAULT', 'Default Admin-Answer-Status');
define('ERROR_REMOVE_DEFAULT_TEXT_STATUS', 'Error: The default ticket status can not be removed. Please set another ticket status as default, and try again.');
define('ERROR_STATUS_USED_IN_TICKET', 'Error: This ticket status is currently used in ticket.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Error: This status is currently used in the status history.');

?>