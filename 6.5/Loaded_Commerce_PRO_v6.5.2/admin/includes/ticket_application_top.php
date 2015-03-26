<?php
/*
  $Id: ticket_application_top.php,v 1.5 2003/04/25 21:37:11 hook Exp $

  Contribution based on:
  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution Central, Custom CRELoaded & osCommerce Programming
  http://www.contributioncentral.com
  Copyright (c) 2008 Contribution Central

  Released under the GNU General Public License
*/

  require(DIR_WS_INCLUDES . 'ticket_configure.php');
  require(DIR_WS_FUNCTIONS . 'ticket_functions.php');
  require(DIR_WS_INCLUDES . 'ccp_filenames.php');
  require(DIR_WS_FUNCTIONS . 'ccp_functions.php');

// define the database table names used in the contribution
  define('TABLE_TICKET_ADMIN', 'ticket_admins');
  define('TABLE_TICKET_DEPARTMENT', 'ticket_department');
  define('TABLE_TICKET_PRIORITY', 'ticket_priority');
  define('TABLE_TICKET_REPLY', 'ticket_reply');
  define('TABLE_TICKET_STATUS', 'ticket_status');
  define('TABLE_TICKET_STATUS_HISTORY', 'ticket_status_history');
  define('TABLE_TICKET_TICKET', 'ticket_ticket');
  define('TABLE_USER_TRACKING', 'user_tracking');
  define('TABLE_CUSTOMER_PRIVATE_MESSAGE','customer_private_message');

// define the filenames used in the project
  define('FILENAME_TICKET_ADMIN', 'ticket_admin.php');
  define('FILENAME_TICKET_CREATE', 'ticket_create.php');
  define('FILENAME_TICKET_DEPARTMENT', 'ticket_department.php');
  define('FILENAME_TICKET_PRIORITY', 'ticket_priority.php');
  define('FILENAME_TICKET_REPLY', 'ticket_reply.php');
  define('FILENAME_TICKET_STATUS', 'ticket_status.php');
  define('FILENAME_TICKET_VIEW', 'ticket_view.php');
  define('FILENAME_PRIVATE_MESSAGES','private_messages.php');
  define('FILENAME_CHANGE_PASSWORD','change_password.php');
  define('FILENAME_CUSTOMER_SERVICE_USER_MANUAL','csmm_user_manual.php');
  define('FILENAME_TICKET_BACKUP_RESTORE','csmm_backup_restore.php');
  define('FILENAME_PRIVATE_MESSAGE', 'private_messages.php');

// define the mobile filenames used in the project
  define('FILENAME_MOBILE_TICKET_ADMIN', 'mobile_ticket_admin.php');
  define('FILENAME_MOBILE_TICKET_DEPARTMENT', 'mobile_ticket_department.php');
  define('FILENAME_MOBILE_TICKET_PRIORITY', 'mobile_ticket_priority.php');
  define('FILENAME_MOBILE_TICKET_REPLY', 'mobile_ticket_reply.php');
  define('FILENAME_MOBILE_TICKET_STATUS', 'mobile_ticket_status.php');
  define('FILENAME_MOBILE_TICKET_VIEW', 'mobile_ticket_view.php');
  define('FILENAME_MOBILE_LOGIN', 'mobile_login.php');
  define('FILENAME_MOBILE_LOGOFF', 'mobile_logoff.php');

?>