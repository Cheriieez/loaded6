<?php
/*
  $Id: PNR_checkoutconfirmation_billingtableright.php,v 1.1.0.0 2008/01/03 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

// Points/Rewards Module V2.00 Redeemption box bof
if (MODULE_ADDONS_POINTS_STATUS == 'True') {
  $orders_total = tep_count_customer_orders();

  echo points_selection();
	
  if (tep_not_null(USE_REFERRAL_SYSTEM)) {
	   if ($orders_total < 1) {
      echo referral_input();
    }
  }
}
// Points/Rewards Module V2.00 Redeemption box eof

?>