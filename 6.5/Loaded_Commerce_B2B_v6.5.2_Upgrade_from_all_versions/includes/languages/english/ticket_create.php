<?php

/*
  $Id: ticket_create.php,v 1.3 2003/04/25 21:37:11 hook Exp $

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

define('HEADING_TITLE', 'New Support Ticket');
define('NAVBAR_TITLE', 'Create Support Ticket');
define('NAVBAR_SUPPORT', 'Support System');
define('TEXT_SUCCESS', 'We have received your enquiry.');
define('TEXT_YOUR_TICKET_ID','Your Ticket Number:');
define('TEXT_CHECK_YOUR_TICKET','You can view your ticket at the following link:');
define('TEXT_LOGIN', 'If you are already a customer, please <a href="%s"><b><u>login here</u></b></a> before opening a new ticket. This results in faster support.<hr>'); 
define('TICKET_EMAIL_SUBJECT', 'New Support Ticket: ');
define('TICKET_EMAIL_MESAGE_HEADER','Your enquiry reached us successfully. We will respond after working on it. You can view your enquiry at following link:');
define('TICKET_EMAIL_MESAGE_FOOTER','Thank you for your enquiry. Please do not respond to this email. Instead use our Support Ticket System.');
define('TICKET_EMAIL_TICKET_NR','Your Ticket Number:');
define('ENTRY_NAME', 'Full Name:');
define('ENTRY_EMAIL', 'Your eMail:');
define('ENTRY_ENQUIRY', 'Enquiry:');
define('ENTRY_ERROR_NO_NAME','&nbsp;<small><font color="#FF0000">Please enter your full name!</font></small>');
define('ENTRY_ERROR_NO_SUBJECT','&nbsp;<small><font color="#FF0000">Subject must be at least ' . TICKET_ENTRIES_SUBJECT_MIN_LENGTH . ' characters long!</font></small>');
define('ENTRY_ERROR_NO_ENQUIRY','&nbsp;<small><font color="#FF0000">Enquiry must be at least ' . TICKET_ENTRIES_ENQUIRY_MIN_LENGTH . ' characters long!</font></small>');
define('ENTRY_ORDER','OrderID (Date of Order):');
define('ENTRY_SUBJECT','Ticket Subject:');
define('ENTRY_DEPARTMENT','Choose a Department:');
define('ENTRY_PRIORITY','Set the Priority:');
define('TEXT_FILL_OUT_FORM', 'Please fill out the form below with as much information as you can. This will help us to resolve your issues and requests faster and more efficiently.<hr>');
define('TICKET_WARNING_ENQUIRY_TOO_SHORT', 'Error: Your Enquiry is too short. It must be at least ' . TICKET_ENTRIES_ENQUIRY_MIN_LENGTH . ' characters long.');
define('TICKET_PAGE_ERROR', 'There are errors on the page. Please check for specific instructions.');

?>