<?php
/*
  $Id: customer_testimonials.php,v 1.3 2003/12/08 Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
  Contributed by http://www.seen-online.co.uk
*/

define('BOX_HEADING_CUSTOMER_TESTIMONIALS', 'Testimonials');
define('HEADING_TITLE', 'Customer Testimonials');
define('IMAGE_BUTTON_INSERT', 'Submit');
define('NAVBAR_TITLE', 'Customer Testimonials');
define('ERROR_TESTIMONIALS_TITLE_REQUIRED', ' Testimonial Title required ');
define('ERROR_TESTIMONIALS_NAME_REQUIRED', ' Testimonial Name required ');
define('ERROR_TESTIMONIALS_DESCRIPTION_REQUIRED', ' Testimonial Description required ');
define('ERROR_HEADER', 'ERROR(s): ');
define('TABLE_HEADING_TESTIMONIALS_ID', 'ID');
define('TABLE_HEADING_TESTIMONIALS_NAME', 'Name');
define('TABLE_HEADING_TESTIMONIALS_URL', 'URL');
define('TABLE_HEADING_TESTIMONIALS_DESCRIPTION', 'Testimonial');
define('TEXT_TESTIMONIAL_BY', 'Testimonial by');
define('TEXT_CLICK_TO_VIEW', 'Click to view');
define('TEXT_NO_TESTIMONIALS', 'There are no Testimonials.');
define('TEXT_LINK_TESTIMONIALS', 'Click here to view all Testimonials');
define('TEXT_LINK_TO_PAGE', 'Click here for additional information');
define('TEXT_TESTIMONIALS_SUCCESSFUL','Thank you for submitting your testimonial. We will get to it shortly.');
define('TEXT_TESTIMONIALS_TITLE', 'Subject:');
define('TEXT_TESTIMONIALS_URL_TITLE', 'Product Name:');
define('TEXT_TESTIMONIALS_URL', 'Product Webpage:');
define('TEXT_TESTIMONIALS_NAME', 'Your name:');
define('TEXT_TESTIMONIALS_LOCATION', 'State, Country:');
define('TEXT_TESTIMONIALS_DATE_ADDED', 'This Testimonial was added:');
define('TEXT_TESTIMONIALS_INTRO', 'Send us your own Testimonial');
define('TEXT_BANNERS_HTML_TEXT', 'Description:');
define('TEXT_READ_MORE', 'Read More ');
define('TEXT_TESTIM_BY', 'By:');
define('TEXT_HACKING_ATTEMPT', '<center><div style="width: 80%; border: 1px solid red; color: red; background-color: #ffffcc; padding: 10px; font-size: 10pt;"><b>HACKING ATTEMPT ON QUERYSTRING!</b><p />Logging IP ... ' . $_SERVER['REMOTE_ADDR'] . '<br />Logging host ... ' . $hostname . '<br />Your information is being automatically emailed to the sysadmin of this server and the FBI Online Fraud Detection Division!</div><br /></center>');

//Email Notification Section
define('TESTIMONIAL_NOTIFICATION_SUBJECT', 'Customer Testimonial Submission.');
define('TESTIMONIAL_NOTIFICATION_TEXT', 'A Customer Testimonial has been submitted by ');
define('TESTIMONIAL_NOTIFICATION_TEXT_2', 'With the following details:');
define('TESTIMONIAL_NOTIFICATION_NO_URL', '#');
define('TESTIMONIAL_NOTIFICATION_TITLE', 'Title: ');
define('TESTIMONIAL_NOTIFICATION_HTML_TEXT', 'Description: ');
define('TESTIMONIAL_NOTIFICATION_URL_TEXT', 'URL: ');
define('TESTIMONIAL_NOTIFICATION_NO_URL_TITLE', 'No url posted');
define('TESTIMONIAL_NOTIFICATION_APPROVE', 'Please take a few moments to login to your store admin and approve or delete this testimonial.');

?>