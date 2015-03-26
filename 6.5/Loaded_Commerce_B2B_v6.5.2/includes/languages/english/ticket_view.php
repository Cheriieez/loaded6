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


define('HEADING_TITLE', 'View Support Ticket');
define('NAVBAR_TITLE', 'View Support Ticket');
define('NAVBAR_SUPPORT', 'Support System');
define('TABLE_HEADING_NR','Ticket');
define('TABLE_HEADING_SUBJECT','Subject');
define('TABLE_HEADING_STATUS','Status');
define('TABLE_HEADING_DEPARTMENT','Department');
define('TABLE_HEADING_PRIORITY','Priority');
define('TABLE_HEADING_CREATED','Opened');
define('TABLE_HEADING_LAST_MODIFIED','Last Change');
define('TEXT_TICKET_BY', 'By:');
define('TEXT_COMMENT','Reply:');
define('TEXT_DATE','Date:');
define('TEXT_DEPARTMENT','Department:');
define('TEXT_DISPLAY_NUMBER_OF_TICKETS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tickets)');
define('TEXT_PRIORITY','Priority:');
define('TEXT_OPENED', 'Opened:');
define('TEXT_STATUS', 'Status:');
define('TEXT_TICKET_NR','Ticket Number:');
define('TEXT_CUSTOMERS_ORDERS_ID','Order ID:');
define('TEXT_VIEW_TICKET_NR','<strong>Ticket Number<strong>');
define('TICKET_PAGE_ERROR', 'There are errors on the page. Please check for specific instructions.');
define('TICKET_MESSAGE_UPDATED','Your Ticket Has Been Updated.');
define('TEXT_VIEW_TICKET_LOGIN','<a href="%s">To view your ticket, you have to log in here:</a>');
define('ENTRY_ERROR_NO_ENQUIRY','&nbsp;<small><font color="#FF0000">Enquiry must be at least ' . TICKET_ENTRIES_ENQUIRY_MIN_LENGTH . ' characters long!</font></small>');

define('TICKET_EMAIL_REPLY_SUBJECT', 'A Support Ticket has been updated updated at ' . STORE_NAME);
define('TICKET_EMAIL_REPLY_HEADER', 'A Support Ticket has been updated at ' . STORE_NAME . '. You can view the reply at the following url:');
define('TICKET_EMAIL_MESAGE_FOOTER', 'Please login to your Ticket System and promptly attend to this request.');

?>