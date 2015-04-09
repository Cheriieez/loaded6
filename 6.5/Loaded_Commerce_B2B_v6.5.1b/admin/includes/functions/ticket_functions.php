<?php
/*
  $Id: ticket_functions.php,v 1.5 2003/04/25 21:37:11 hook Exp $

  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  function tep_get_ticket_status_name($ticket_status_id, $language_id = '') {
    global $languages_id;

    if ($ticket_status_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $languages_id;

    $status_query = tep_db_query("select ticket_status_name from " . TABLE_TICKET_STATUS . " where ticket_status_id = '" . $ticket_status_id . "' and ticket_language_id = '" . $language_id . "'");
    $status = tep_db_fetch_array($status_query);

    return $status['ticket_status_name'];
  }

  function tep_get_ticket_admin_name($ticket_admin_id, $language_id = '') {
    global $languages_id;

    //if ($ticket_admin_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $languages_id;

    $status_query = tep_db_query("select ticket_admin_name from " . TABLE_TICKET_ADMIN . " where ticket_admin_id = '" . $ticket_admin_id . "' and ticket_language_id = '" . $language_id . "'");
    $admin = tep_db_fetch_array($status_query);

    return $admin['ticket_admin_name'];
  }

  function tep_get_ticket_department_name($ticket_department_id, $language_id = '') {
    global $languages_id;

    if ($ticket_department_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $languages_id;

    $status_query = tep_db_query("select ticket_department_name from " . TABLE_TICKET_DEPARTMENT . " where ticket_department_id = '" . $ticket_department_id . "' and ticket_language_id = '" . $language_id . "'");
    $department = tep_db_fetch_array($status_query);

    return $department['ticket_department_name'];
  }

function tep_get_ticket_priority_name($ticket_priority_id, $language_id = '') {
    global $languages_id;

    if ($ticket_priority_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $languages_id;

    $status_query = tep_db_query("select ticket_priority_name from " . TABLE_TICKET_PRIORITY . " where ticket_priority_id = '" . $ticket_priority_id . "' and ticket_language_id = '" . $language_id . "'");
    $priority = tep_db_fetch_array($status_query);

    return $priority['ticket_priority_name'];
  }
  function tep_get_ticket_reply_name($ticket_reply_id, $language_id = '') {
    global $languages_id;

    if ($ticket_reply_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $languages_id;

    $status_query = tep_db_query("select ticket_reply_name from " . TABLE_TICKET_REPLY . " where ticket_reply_id = '" . $ticket_reply_id . "' and ticket_language_id = '" . $language_id . "'");
    $reply = tep_db_fetch_array($status_query);

    return $reply['ticket_reply_name'];
  }
  function tep_get_ticket_reply_text($ticket_reply_id, $language_id = '') {
    global $languages_id;

    if ($ticket_reply_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $languages_id;

    $status_query = tep_db_query("select ticket_reply_text from " . TABLE_TICKET_REPLY . " where ticket_reply_id = '" . $ticket_reply_id . "' and ticket_language_id = '" . $language_id . "'");
    $reply = tep_db_fetch_array($status_query);

    return $reply['ticket_reply_text'];
  }
?>
