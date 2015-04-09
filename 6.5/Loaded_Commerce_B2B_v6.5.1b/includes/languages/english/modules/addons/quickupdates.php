<?php
/*
  $Id: quickupdates.php,v 1.0.0 2008/05/22 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  define('MODULE_ADDONS_QPU_TITLE', 'LC Quick Product Updater');
  $qpu_enabled_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_ADDONS_QPU_STATUS'");
  $qpu_enabled = tep_db_fetch_array($qpu_enabled_query);
  if ($qpu_enabled['configuration_value'] == 'True') {
    define('MODULE_ADDONS_QPU_DESCRIPTION', '<p><b>LC Quick Product Updater</b><br /><br />This module allows you to quickly update many products at once.<br /><br />The most valuable power of this module is the ability to update many different aspects of multiple products all from one screen. There is also dropdows for filtering the listing as needed for Categories, Manufacturers and Customer Groups.</p>');
  } else {
    define('MODULE_ADDONS_QPU_DESCRIPTION', '<p><b>LC Quick Product Updater</b><br /><br />This module allows you to quickly update many products at once.');
  }
?>