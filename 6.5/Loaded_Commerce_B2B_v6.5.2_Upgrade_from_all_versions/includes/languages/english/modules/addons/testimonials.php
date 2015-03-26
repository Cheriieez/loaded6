<?php
/*
  $Id: testimonials.php,v 1.0.0 2008/05/22 13:41:11 maestro Exp $

  Loaded Commerce, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  define('MODULE_ADDONS_CTM_TITLE', 'LC Customer Testimonials');
  $ctm_enabled_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'CUSTOMER_TESTIMONIALS_ENABLE'");
  $ctm_enabled = tep_db_fetch_array($ctm_enabled_query);
  if ($ctm_enabled['configuration_value'] == 'true') {
    define('MODULE_ADDONS_CTM_DESCRIPTION', '<p><b>LC Customer Testimonials</b></p>');
  } else {
    define('MODULE_ADDONS_CTM_DESCRIPTION', '<p><b>LC Customer Testimonials</b></p>');
  }
?>