<?php
/*
  $Id: ticket_create.php,v 1.5 2003/04/25 21:37:12 hook Exp $

  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!isset($_SESSION['customer_id']) && $_GET['login'] == 'yes') {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TICKET_CREATE);

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
 
  $email = tep_db_prepare_input(trim($_POST['email']));
  $name = tep_db_prepare_input($_POST['name']);
  $subject = tep_db_prepare_input($_POST['subject']);
  $enquiry = tep_db_prepare_input($_POST['enquiry']);
  $department = tep_db_prepare_input($_POST['department']);
  $priority = tep_db_prepare_input($_POST['priority']);
  $ticket_customers_orders_id = tep_db_prepare_input($_POST['ticket_customers_orders_id']);
  
  
// Customer is logged in:  
  if (tep_session_is_registered('customer_id')) {
    $customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
    $customer = tep_db_fetch_array($customer_query);
  }

// Form was submitted
  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
 // Check Subject length
    if (isset($subject) && strlen($subject) < TICKET_ENTRIES_SUBJECT_MIN_LENGTH ) {
      $error = true;
      $_GET['error_message'] = TICKET_PAGE_ERROR;
      $error_subject = true;
    }
  // Check Message length
    if (isset($enquiry) && strlen($enquiry) < TICKET_ENTRIES_ENQUIRY_MIN_LENGTH ) {
      $error = true;
      $_GET['error_message'] = TICKET_PAGE_ERROR;
      $error_enquiry = true;
    }
    
    if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On') {
      if (defined('MODULE_ADDONS_CSMM_CREATE_VVC_ON_OFF') && MODULE_ADDONS_CSMM_CREATE_VVC_ON_OFF == 'On') {
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
      $ticket_customers_id = '';
      // Get the customers_id
      if (tep_session_is_registered('customer_id')) {
        $ticket_customers_id = $customer_id;
      } else {
        $customerid_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_email_address='" . tep_db_input($email) . "'");
        if ($customerid = tep_db_fetch_array($customerid_query)) $ticket_customers_id = $customerid['customers_id'] ;
      }
      // generate LinkID
      $time = mktime();
      $ticket_link_id = '';
      if (TICKET_LINK_ID_LENGTH == '2') {
        for ($x=1; $x<2; $x++) {
          $ticket_link_id .= substr($time, $x, 1) . tep_create_random_value(1, $type = 'chars');
        }
      }
      if (TICKET_LINK_ID_LENGTH == '4') {
        for ($x=1; $x<3; $x++) {
          $ticket_link_id .= substr($time, $x, 1) . tep_create_random_value(1, $type = 'chars');
        }
      }
      if (TICKET_LINK_ID_LENGTH == '6') {
        for ($x=1; $x<4; $x++) {
          $ticket_link_id .= substr($time, $x, 1) . tep_create_random_value(1, $type = 'chars');
        }
      }
      if (TICKET_LINK_ID_LENGTH == '8') {
        for ($x=1; $x<5; $x++) {
          $ticket_link_id .= substr($time, $x, 1) . tep_create_random_value(1, $type = 'chars');
        }
      }
      if (TICKET_LINK_ID_LENGTH == '10') {
        for ($x=1; $x<6; $x++) {
          $ticket_link_id .= substr($time, $x, 1) . tep_create_random_value(1, $type = 'chars');
        }
      }
      if (TICKET_LINK_ID_LENGTH == '12') {
        for ($x=1; $x<7; $x++) {
          $ticket_link_id .= substr($time, $x, 1) . tep_create_random_value(1, $type = 'chars');
        }
      }
      if (TICKET_LINK_ID_LENGTH == '14') {
        for ($x=1; $x<8; $x++) {
          $ticket_link_id .= substr($time, $x, 1) . tep_create_random_value(1, $type = 'chars');
        }
      }
      if (TICKET_LINK_ID_LENGTH == '16') {
        for ($x=1; $x<9; $x++) {
          $ticket_link_id .= substr($time, $x, 1) . tep_create_random_value(1, $type = 'chars');
        }
      }
      if (TICKET_LINK_ID_LENGTH == '18') {
        for ($x=1; $x<10; $x++) {
          $ticket_link_id .= substr($time, $x, 1) . tep_create_random_value(1, $type = 'chars');
        }
      }
      if (TICKET_LINK_ID_LENGTH == '20') {
        for ($x=1; $x<11; $x++) {
          $ticket_link_id .= substr($time, $x, 1) . tep_create_random_value(1, $type = 'chars');
        }
      }
      
      $sql_data_array = array('ticket_link_id' => $ticket_link_id,
                              'ticket_customers_id' => $ticket_customers_id,
                              'ticket_customers_orders_id' => $ticket_customers_orders_id,
                              'ticket_customers_email' => $email,
                              'ticket_customers_name' => $name,
                              'ticket_subject' => $subject,
                              'ticket_status_id' => TICKET_DEFAULT_STATUS_ID,
                              'ticket_department_id' => $department,
                              'ticket_priority_id' => $priority,
                              'ticket_date_last_modified' => 'now()',
                              'ticket_date_last_customer_modified' => 'now()',
                              'ticket_date_created' => 'now()');

      tep_db_perform(TABLE_TICKET_TICKET, $sql_data_array);
                              $insert_id = tep_db_insert_id();
      
      $sql_data_array = array('ticket_id' => $insert_id,
                              'ticket_status_id' => TICKET_DEFAULT_STATUS_ID,
                              'ticket_priority_id' => $priority,
                              'ticket_department_id' => $department,
                              'ticket_date_modified' => 'now()',
                              'ticket_customer_notified' => '1',
                              'ticket_edited_by' => $name,
                              'ticket_comments' => $enquiry);
      tep_db_perform(TABLE_TICKET_STATUS_HISTORY, $sql_data_array); 
    // Email  Customer doesn't get the Message cause he should use the web
      $ticket_email_subject = TICKET_EMAIL_SUBJECT . $subject;
      $ticket_email_message = TICKET_EMAIL_MESAGE_HEADER . "\n\n" . tep_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $ticket_link_id, 'SSL', false, false) . "\n\n" . TICKET_EMAIL_TICKET_NR . " " . $ticket_link_id . "\n" . TICKET_EMAIL_MESAGE_FOOTER;
      tep_mail($name, $email, $ticket_email_subject, nl2br($ticket_email_message), STORE_NAME, SUPPORT_EMAIL_ADDRESS);
    // send emails to other people
      if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
       $ticket_email_message = TICKET_EMAIL_MESAGE_HEADER . "\n\n" . tep_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $ticket_link_id) . "\n\n" . $enquiry . TICKET_EMAIL_MESAGE_FOOTER . "\n\n" . $enquiry;
       tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $ticket_email_subject, nl2br($ticket_email_message), STORE_NAME, SUPPORT_EMAIL_ADDRESS);
      }
    // Send New Ticket SMS Message Notification bof
      if (SMS_TICKET_NOTIFY == 'true') {
        $to_email_address = SEND_EXTRA_TICKET_SMS_TO;
        $SMS_subject = 'New Support Ticket - ' . $subject; 
        $SMS_body = "\n" . 'Name: ' . $name . "\n\n" . 'Ticket ID: ' . $insert_id . "\n\n" . 'Message: ' . "\n" . $enquiry;
        tep_mail(STORE_OWNER, $to_email_address, $SMS_subject, $SMS_body, $name, $email);
      }
    // Send New Ticket SMS Message Notification eof
      tep_redirect(tep_href_link(FILENAME_TICKET_CREATE, 'action=success&tlid=' . $ticket_link_id ));
    }
  }

  $breadcrumb->add(NAVBAR_SUPPORT, tep_href_link(FILENAME_TICKET_SUPPORT));
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_TICKET_CREATE));

  $content = CONTENT_TICKET_CREATE;
  $javascript = $content . '.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
 
 ?>