<?php

/*
  $Id: ticket_view.php,v 1.3 2003/04/25 21:37:11 hook Exp $

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

define('HEADING_TITLE', 'Support Tickets');
define('HEADING_TITLE_STATUS','Ticket Status:');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_DEPARTMENT', 'Ticket Department:');
define('HEADING_TITLE_PRIORITY', 'Ticket Priority:');
define('TABLE_HEADING_ACTION', 'Info');
define('TABLE_HEADING_CUSTOMER_ID', 'Cid');
define('TABLE_HEADING_DATE', 'Updated');
define('TABLE_HEADING_DEPARTMENT', 'Department');
define('TABLE_HEADING_NAME', 'Name');
define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_ORDER_ID', 'OrderID');
define('TABLE_HEADING_PRIORITY', 'Priority');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_TICKET_SUBJECT', 'Subject');
define('TEXT_ALL_TICKETS', 'All');
define('TEXT_ALL_DEPARTMENTS', 'All');
define('TEXT_ALL_PRIORITYS', 'All');
define('TEXT_ADMIN', 'Admin');
define('TEXT_TICKET_BY', '<b>by:</b>');
define('TEXT_CUSTOMERS_EMAIL', '<b>Email:</b>');
define('TEXT_CUSTOMERS_ID', 'CustomerID:');
define('TEXT_CUSTOMERS_NAME', 'Name:');
define('TEXT_CUSTOMERS_ORDERS_ID', 'OrderID:');
define('TEXT_CUSTOMER_LOGIN_YES', 'Customer login required to view ticket');
define('TEXT_CUSTOMER_LOGIN_NO', 'Customer login not required to view ticket');
define('TEXT_COMMENT', '<b>Reply:</b>');
define('TEXT_DATE', 'Date:');
define('TEXT_DATE_TICKET_CREATED', 'Ticket Created: ');
define('TEXT_DATE_TICKET_LAST_MODIFIED', 'Last Change: '); 
define('TEXT_DATE_TICKET_LAST_CUSTOMER_MODIFIED', 'Last Customer Change:');
define('TEXT_DEPARTMENT', 'Department: ');
define('TEXT_DISPLAY_NUMBER_OF_TICKET', 'Display <b>%d</b> to <b>%d</b> (of <b>%d</b> Tickets)');
define('TEXT_INSERT', 'Insert');
define('TEXT_OPENED', '<b>Opened on:</b>');
define('TEXT_PRIORITY', 'Priority: ');
define('TEXT_REPLY', 'Reply');
define('TEXT_LAST_REPLY', 'Last Reply by: ( ');
define('TEXT_LAST_REPLY_END', ' )');
define('TEXT_STATUS', 'Status: ');
define('TEXT_TICKET_NR', '<b>Ticket Number: </b>');
define('TEXT_TICKET_SUBJECT', 'Ticket Subject :: ( ');
define('TEXT_TICKET_SUBJECT_END', ' )');
define('TEXT_INFO_HEADING_DELETE_TICKET', 'Are you sure you want to delete this ticket?');
define('TICKET_EMAIL_SUBJECT', 'Update of your ' . STORE_NAME . ' Support-Ticket.');
define('TICKET_EMAIL_MESAGE_HEADER', 'Your Support Ticket has been replied to. You can view the changes at:');
define('TICKET_EMAIL_MESAGE_FOOTER1', 'If you have more Questions, please continue to use our support ticket system.');
define('TICKET_EMAIL_MESAGE_FOOTER2', 'DO NOT REPLY TO THIS EMAIL.');
define('SUCCESS_TICKET_UPDATED', 'Ticket has been updated');
define('ERROR_TICKET_DOES_NOT_EXIST', 'Error: Ticket does not exist!');
define('WARNING_TICKET_NOT_UPDATED', 'Ticket has not been updated!');
define('WARNING_ENTRY_TO_SHORT', 'The length of the reply is too short!');

define('TABLE_HEADING_SORT', 'Sort listing by ');
define('TABLE_HEADING_SORT_TEXT_ASC', ' --> A-B-C From Top');
define('TABLE_HEADING_SORT_NUM_ASC', ' --> 1-2-3 From Top');
define('TABLE_HEADING_SORT_TEXT_DESC', ' --> Z-Y-X From Top');
define('TABLE_HEADING_SORT_NUM_DESC', ' --> 3-2-1 From Top');

define('TICKET_ICON_OPEN', 'Ticket Is Currently Open');
define('TICKET_ICON_ON_HOLD', 'Ticket Is Currently On Hold');
define('TICKET_ICON_CLOSED', 'Ticket Is Currently Closed');
define('TICKET_ICON_AWAITING_CUSTOMER', 'Ticket Is Currently Awaiting Customers Reply');

define('TICKET_TEXT_TITLE_ID', 'Customers ID');
define('TICKET_TEXT_TITLE_NAME', 'Edit Customer Info');
define('TICKET_TEXT_TITLE_ORDER', 'Edit Order Info');
define('ICON_EDIT_TICKET', 'Edit Ticket');
define('IMAGE_REMOVE_COMMENT', 'Remove Comment: ');

define('TEXT_TICKET_HINT_SUBJECT', '<b>Ticket Subject: </b>');
define('TEXT_TICKET_HINT_LAST_REPLY_BY', '<b>Last Reply By: </b>');
define('TEXT_TICKET_HINT_LAST_REPLY', '<b>Reply: </b>');
define('TEXT_TICKET_HINT_LAST_UPDATED', '<b>Last Activity: </b>');
define('TEXT_TICKET_HINT_LAST_CUSTOMER_UPDATED', '<b>Last Customer Activity: </b>');

?>