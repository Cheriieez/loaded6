<?php
/*
  $Id: ticket_view.php,v 1.6 2003/07/13 20:22:02 hook Exp $

  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  /*if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }*/
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TICKET_VIEW);
  $ticket_departments = array();
  $ticket_department_array = array();
  $ticket_department_query = tep_db_query("select ticket_department_id, ticket_department_name from " . TABLE_TICKET_DEPARTMENT . " where ticket_language_id = '" . $languages_id . "'");
  while ($ticket_department = tep_db_fetch_array($ticket_department_query)) {
    $ticket_departments[] = array('id' => $ticket_department['ticket_department_id'],
                               'text' => $ticket_department['ticket_department_name']);
    $ticket_department_array[$ticket_department['ticket_department_id']] = $ticket_department['ticket_department_name'];
  }

  $ticket_prioritys = array();
  $ticket_priority_array = array();
  $ticket_priority_query = tep_db_query("select ticket_priority_id, ticket_priority_name from " . TABLE_TICKET_PRIORITY . " where ticket_language_id = '" . $languages_id . "'");
  while ($ticket_priority = tep_db_fetch_array($ticket_priority_query)) {
    $ticket_prioritys[] = array('id' => $ticket_priority['ticket_priority_id'],
                               'text' => $ticket_priority['ticket_priority_name']);
    $ticket_priority_array[$ticket_priority['ticket_priority_id']] = $ticket_priority['ticket_priority_name'];
  }

  $ticket_statuses = array();
  $ticket_status_array = array();
  $ticket_status_query = tep_db_query("select ticket_status_id, ticket_status_name from " . TABLE_TICKET_STATUS . " where ticket_language_id = '" . $languages_id . "'");
  while ($ticket_status = tep_db_fetch_array($ticket_status_query)) {
    $ticket_statuses[] = array('id' => $ticket_status['ticket_status_id'],
                               'text' => $ticket_status['ticket_status_name']);
    $ticket_status_array[$ticket_status['ticket_status_id']] = $ticket_status['ticket_status_name'];
  }
  
  $enquiry = tep_db_prepare_input($_POST['enquiry']);
  $status = tep_db_prepare_input($_POST['status']);
  $priority = tep_db_prepare_input($_POST['priority']);
  $department = tep_db_prepare_input($_POST['department']);
  if (isset($_POST['tlid'])) $tlid = tep_db_prepare_input($_POST['tlid']);
  if (isset($_GET['tlid'])) $tlid = tep_db_prepare_input($_GET['tlid']);
  if (strlen($tlid) < 10) unset($tlid);
// Form was submitted
  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send') && isset($tlid) ) {
    // Check Message length
    if (isset($enquiry) && strlen($enquiry) < TICKET_ENTRIES_ENQUIRY_MIN_LENGTH ) {
      $error = true;
      $_GET['error_message'] = TICKET_PAGE_ERROR;
      $error_enquiry = true; 
    }
    if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On') {
      if (defined('MODULE_ADDONS_CSMM_VIEW_VVC_ON_OFF') && MODULE_ADDONS_CSMM_VIEW_VVC_ON_OFF == 'On') {
        $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . " where oscsid = '" . tep_session_id() . "'");
        $code_array = tep_db_fetch_array($code_query);
        // remove the visual verify code associated with this session to clean database and ensure new results
        tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid = '" . tep_session_id() . "'");
        if ( isset($_POST['visual_verify_code']) && tep_not_null($_POST['visual_verify_code']) && 
          isset($code_array['code']) &&  tep_not_null($code_array['code']) && 
          // make the check case sensitive
          strcmp($_POST['visual_verify_code'], $code_array['code']) == 0) {
          // match is good, no message or error.
        } else {
          $error = true;
          $_GET['error_message'] = VISUAL_VERIFY_CODE_ENTRY_ERROR;
        }
      }
    }
    if ($error == false) {
      $ticket_id_query = tep_db_query("select ticket_id, ticket_link_id, ticket_customers_name, ticket_customers_email from " . TABLE_TICKET_TICKET . " where ticket_link_id = '" . tep_db_input($tlid) . "'");
      $ticket_id = tep_db_fetch_array($ticket_id_query);
      if ($ticket_id['ticket_id']) {
        if (TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS == 'false' && TICKET_CUSTOMER_REPLY_STATUS_ID > 0 ) $status = TICKET_CUSTOMER_REPLY_STATUS_ID;
        $sql_data_array = array('ticket_id' => $ticket_id['ticket_id'],
                                'ticket_status_id' => $status,
                                'ticket_priority_id' => $priority,
                                'ticket_department_id' => $department,
                                'ticket_date_modified' => 'now()',
                                'ticket_customer_notified' => '0',
                                'ticket_edited_by' => $ticket_id['ticket_customers_name'],
                                'ticket_comments' => $enquiry);
        tep_db_perform(TABLE_TICKET_STATUS_HISTORY, $sql_data_array);         
        $sql_data_array = array('ticket_status_id' => $status,
                                'ticket_priority_id' => $priority,
                                'ticket_department_id' => $department,
                                'ticket_date_last_modified' => 'now()',
                                'ticket_date_last_customer_modified' => 'now()');
        tep_db_perform(TABLE_TICKET_TICKET, $sql_data_array, 'update', 'ticket_id = \'' . $ticket_id['ticket_id'] . '\'');        
        $_GET['info_message'] = TICKET_MESSAGE_UPDATED;
        // Email  Customer doesn't get the Message cause he should use the web
        $email = $ticket_id['ticket_customers_email'];
        $name = $ticket_id['ticket_customers_name'];
        if (TICKET_SEND_EMAIL_ALERT == 'true') {
          $ticket_email_message = TICKET_EMAIL_REPLY_HEADER . "\n\n" . tep_href_link('admin/' . FILENAME_TICKET_VIEW, 'selected_box=ticket&tID=' . $ticket_id['ticket_id'] . '&action=edit') . "\n\n" . TICKET_EMAIL_MESAGE_FOOTER;
          tep_mail(STORE_NAME, SUPPORT_EMAIL_ADDRESS, TICKET_EMAIL_REPLY_SUBJECT, nl2br($ticket_email_message), $name, $email);
        }
        // send emails to other people
        if ((SEND_EXTRA_ORDER_EMAILS_TO != '') && (TICKET_SEND_EXTRA_EMAIL_ALERT == 'true')) {
          $ticket_email_message = TICKET_EMAIL_REPLY_HEADER . "\n\n" . tep_href_link('admin/' . FILENAME_TICKET_VIEW, 'selected_box=ticket&tID=' . $ticket_id['ticket_id'] . '&action=edit') . "\n\n" . TICKET_EMAIL_MESAGE_FOOTER;
          tep_mail(STORE_NAME, SEND_EXTRA_ORDER_EMAILS_TO, TICKET_EMAIL_REPLY_SUBJECT, nl2br($ticket_email_message), $name, $email);
        }
        // Send Ticket Reply SMS Message Notification bof
        if (SMS_TICKET_NOTIFY == 'true') {
          $to_email_address = SEND_EXTRA_TICKET_SMS_TO;
          $SMS_subject = 'New Support Ticket Reply - ' . $ticket_id['ticket_subject']; 
          $SMS_body = "\n" . 'Name: ' . $ticket_id['ticket_customers_name'] . "\n\n" . 'Ticket ID: ' . $ticket_id['ticket_id'] . "\n\n" . 'Message: '  . "\n" . $enquiry;
          tep_mail(STORE_OWNER, $to_email_address, $SMS_subject, $SMS_body, STORE_OWNER, $ticket_id['ticket_customers_email']);
        }
        // Send Ticket Reply SMS Message Notification eof
        $enquiry = '';
        tep_redirect(tep_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $ticket_id['ticket_link_id']));
      }
    }  
  }

  $breadcrumb->add(NAVBAR_SUPPORT, tep_href_link(FILENAME_TICKET_SUPPORT));
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_TICKET_VIEW));

  $content = CONTENT_TICKET_VIEW;
  $javascript = $content . '.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>