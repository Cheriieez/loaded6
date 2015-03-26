<?php

/*
  $Id: ticket_department.php,v 1.3 2003/04/25 21:37:11 hook Exp $

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

define('HEADING_TITLE', 'Define Ticket Departments');
define('TABLE_HEADING_TEXT_DEPARTMENT', 'Departments');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_DISPLAY_NUMBER_OF_TEXT_DEPARTMENT', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Departments)');
define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_TEXT_DEPARTMENT_NAME', 'Ticket Departments:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new ticket department with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this ticket department?');
define('TEXT_INFO_HEADING_NEW_TEXT_DEPARTMENT', 'New Ticket Department');
define('TEXT_INFO_HEADING_EDIT_TEXT_DEPARTMENT', 'Edit Ticket Department');
define('TEXT_INFO_HEADING_DELETE_TEXT_DEPARTMENT', 'Delete Ticket Department');
define('ERROR_REMOVE_DEFAULT_TEXT_DEPARTMENT', 'Error: The default ticket department can not be removed. Please set another ticket department as default, and try again.');
define('ERROR_DEPARTMENT_USED_IN_TICKET', 'Error: This ticket department is currently used in tickets.');
define('ERROR_DEPARTMENT_USED_IN_HISTORY', 'Error: This ticket department is currently used in the ticket history.');

?>