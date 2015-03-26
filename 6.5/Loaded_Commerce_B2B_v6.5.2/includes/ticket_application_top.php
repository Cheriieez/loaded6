<?php
/*
  $Id: ticket_application_top.php, v1.5 2003/04/25 21:37:11 hook Exp $

  Contribution based on:
  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution Central, Custom CRELoaded & osCommerce Programming
  http://www.contributioncentral.com
  Copyright (c) 2008 Contribution Central

  Released under the GNU General Public License
*/

// Added for timestamp option
  require(DIR_WS_FUNCTIONS . 'ticket_functions.php');

// define the database table names used in the contribution
  define('TABLE_TICKET_DEPARTMENT', 'ticket_department');
  define('TABLE_TICKET_PRIORITY', 'ticket_priority');
  define('TABLE_TICKET_TICKET', 'ticket_ticket');
  define('TABLE_USER_TRACKING', 'user_tracking');
  define('TABLE_TICKET_STATUS_HISTORY', 'ticket_status_history');
  define('TABLE_TICKET_STATUS', 'ticket_status');
  define('TABLE_CUSTOMER_PRIVATE_MESSAGE','customer_private_message');

// define the filenames used in the project
  define('CONTENT_TICKET_CREATE', 'ticket_create');
  define('FILENAME_TICKET_CREATE', CONTENT_TICKET_CREATE . '.php');
  define('CONTENT_TICKET_VIEW', 'ticket_view');
  define('FILENAME_TICKET_VIEW', CONTENT_TICKET_VIEW . '.php');
  define('FILENAME_SUPPORT', 'ticket_create.php');
  define('FILENAME_TICKETBOX', 'ticketbox.php');
  define('CONTENT_TICKET_SUPPORT', 'ticket_support');
  define('FILENAME_TICKET_SUPPORT', CONTENT_TICKET_SUPPORT . '.php');
  define('CONTENT_PRIVATE_MESSAGES', 'private_messages');
  define('FILENAME_PRIVATE_MESSAGES', CONTENT_PRIVATE_MESSAGES . '.php');

?>