<?php

/*
  $Id: ticket_admin.php,v 1.3 2003/04/25 21:37:11 hook Exp $

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
 
define('HEADING_TITLE', 'Manage Support Staff');
define('TABLE_HEADING_TEXT_ADMIN', 'Members');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_DISPLAY_NUMBER_OF_TEXT_ADMIN', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Admins)');
define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_TEXT_ADMIN_NAME', 'Ticket Admins:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new Ticket Admin with his or her related data.');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this Ticket Admin?');
define('TEXT_INFO_HEADING_NEW_TEXT_ADMIN', 'New Ticket Admin');
define('TEXT_INFO_HEADING_EDIT_TEXT_ADMIN', 'Edit Ticket Admin');
define('TEXT_INFO_HEADING_DELETE_TEXT_ADMIN', 'Delete Ticket Admin');
define('ERROR_REMOVE_DEFAULT_TEXT_ADMIN', 'Error: The default Ticket Admin can not be removed. Please set another Ticket Admin as default, and then try again.');
define('ERROR_ADMIN_USED_IN_TICKET', 'Error: This Ticket Admin is currently used in tickets.');
define('ERROR_ADMIN_USED_IN_HISTORY', 'Error: This Ticket Admin department is currently used in the ticket history.');

?>