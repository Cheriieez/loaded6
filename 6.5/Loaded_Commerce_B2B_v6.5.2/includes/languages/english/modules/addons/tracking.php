<?php
/*
  $Id: tracking.php,v 1.0.0 2008/05/22 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  define('MODULE_ADDONS_OPT_TITLE', 'LC Order Package Tracking');
  $opt_enabled_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'ORDER_TRACKING_ENABLE'");
  $opt_enabled = tep_db_fetch_array($opt_enabled_query);
  if ($opt_enabled['configuration_value'] == 'true') {
    define('MODULE_ADDONS_OPT_DESCRIPTION', '<p><b>LC Order Package Tracking</b><br /><br />This module allows you to apply a package tracking shipment number to a specific order.<br /><br />Adding a tracking number to an order will make a tracking button visible on the account history info page for your customers to simply click and view the carrier\'s tracking page for that specific order.</p><br /><p style="padding:5px; text-align:justify; border:1px solid #d3d3d3; background-color:#f3f3f3; margin-top:-1em;"><b>Please note:</b> If you remove this module <i>(un-install)</i> it will completely wipe out your tracking data for <b>ALL CUSTOMERS</b>!!!<br /><br />You may wish to disable <i>(set to false)</i> and do an SQL backup of your store\'s data before removing this module? This will preserve your Package Tracking data for later recovery.<br /><br />Make sure you are certain you wish to un-install this module and completely erase all data related to order package tracking <b><font color="red">BEFORE YOU DO THIS</font></b>!!!</p>');
  } else {
    define('MODULE_ADDONS_OPT_DESCRIPTION', '<p><b>LC Order Package Tracking</b><br /><br />This module allows you to apply a package tracking shipment number to a specific order.');
  }
?>