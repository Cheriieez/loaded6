<?php
/*
  $Id: PNR_english_lang.php,v 1.0.0.0 2007/02/27 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  define('TABLE_CUSTOMERS_POINTS_PENDING', 'customers_points_pending');

  define('CONTENT_MY_POINTS', 'my_points');
  define('FILENAME_MY_POINTS', CONTENT_MY_POINTS . '.php');
  define('CONTENT_MY_POINTS_HELP', 'my_points_help');
  define('FILENAME_MY_POINTS_HELP', CONTENT_MY_POINTS_HELP . '.php');
  define('CONTENT_POINTS_IP_FRAUD', 'points_ip_fraud');
  define('FILENAME_POINTS_IP_FRAUD', CONTENT_POINTS_IP_FRAUD . '.php');

  define('BOX_INFORMATION_MY_POINTS_HELP', 'Point Program FAQ');
  define('REDEEM_SYSTEM_ERROR_POINTS_NOT', 'Points value are not enough to cover the cost of your purchase. Please select another payment method');
  define('REDEEM_SYSTEM_ERROR_POINTS_OVER', 'REDEEM POINTS ERROR ! Points value can not be over the total value. Please Re enter points');
  define('REFERRAL_ERROR_SELF', 'Sorry you can not refer yourself.');
  define('REFERRAL_ERROR_NOT_FOUND', 'The referral email address you entered was not found.');
  define('TEXT_POINTS_BALANCE', 'Your Points Info.');
  define('TEXT_POINTS', 'Points :');
  define('TEXT_VALUE', 'Value:');
  define('REVIEW_HELP_LINK', ' Write a Review and earn <b>$' . USE_POINTS_FOR_REVIEWS * REDEEM_POINT_VALUE . '</b> worth of points.<br />Please check the <a href="' . tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=13', 'NONSSL') . '" title="Reward Point Program FAQ"><u>Reward</u></a> Point Program FAQ for more information.');

  define('MY_POINTS_TITLE', 'My Points and Redemptions');
  define('MY_POINTS_VIEW', 'View my Points Balance and Points received.');
  define('MY_POINTS_VIEW_HELP', 'Reward Point Program FAQ.');
  define('MY_POINTS_CURRENT_BALANCE', 'Shopping Points Balance : %s points. Valued at : %s ');

  define('TABLE_HEADING_REDEEM_SYSTEM', 'Shopping Points Redemptions ');
  define('TABLE_HEADING_REFERRAL', 'Referral System');
  define('TEXT_REDEEM_SYSTEM_START', 'You have a credit balance of %s ,would you like to use it to pay for this order?<br />The estimated total of your purchase is: %s .');
  define('TEXT_REDEEM_SYSTEM_SPENDING', 'Tick here to use Maximum Points allowed for this order. (%s points %s)&nbsp;&nbsp;->');
  define('TEXT_REDEEM_SYSTEM_NOTE', '<font color="ff0000">Total Purchase is greater than the maximum points allowed, you will also need to choose a payment method</font>');
  define('TEXT_REFERRAL_REFERRED', 'If you were referred to us by a friend please enter their email address here. ');

  define('EMAIL_WELCOME_POINTS', '<li><b>Reward Point Program</b> - As part of our Welcome to new customers, we have credited your %s with a total of %s Shopping Points worth %s .' . "\n" . 'Please refer to the %s as conditions may apply.');
  define('EMAIL_POINTS_ACCOUNT', 'Shopping Points Accout');
  define('EMAIL_POINTS_FAQ', 'Reward Point Program FAQ');

  define('TEXT_WELCOME_POINTS_TITLE', 'As part of our Welcome to new customers, we have credited your  <a href="' . tep_href_link(FILENAME_MY_POINTS, '', 'SSL') . '"><u>Shopping Points Accout</u></a>  with a total of %s Shopping Points worth %s');
  define('TEXT_WELCOME_POINTS_LINK', 'Please refer to the  <a href="' . tep_href_link(FILENAME_MY_POINTS_HELP, '', 'NONSSL') . '"><u>Reward Point Program FAQ</u></a> as conditions may apply.');

  define('TEXT_PRODUCT_POINTS', '<b>Points Credit :</b> %s points Currently valued at %s');
  define('TEXT_PRODUCT_NO_POINTS', '<b>Points Credit :</b><font color="#FF0000"> No points awarded for discounted products.</font>');
  define('PRODUCTINFO_REVIEW_HELP_LINK', 'Please check the <a href="' . tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=13', 'NONSSL') . '" title="Reward Point Program FAQ"><u>Reward</u></a> Point Program FAQ for more information.');
  define('TEXT_PRODUCT_POINTS_HEADING', 'Points & Rewards Information');

?>