<?php
/*
  $Id: PNR_editorders_updateorder.php,v 1.0.0.0 2007/08/16 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if (MODULE_ADDONS_POINTS_STATUS == 'True') { 
  global $oID; 
  // Points/Rewards Module V2.00 BOF
  if ((isset($_POST['confirm_points']) && ($_POST['confirm_points'] == 'on')) || (isset($_POST['delete_points']) && ($_POST['delete_points'] == 'on'))) {
 	  $comments = ENTRY_CONFIRMED_POINTS  . $comments; 	        
		  $customer_query = tep_db_query("SELECT customer_id, points_pending from " . TABLE_CUSTOMERS_POINTS_PENDING . " WHERE points_status = 1 AND points_type = 'SP' AND orders_id = '" . $oID . "'");
		  $customer_points = tep_db_fetch_array($customer_query);
    if (tep_db_num_rows($customer_query)) {
      if (tep_not_null(POINTS_AUTO_EXPIRES)) {
        $expire  = date('Y-m-d', strtotime('+ '. POINTS_AUTO_EXPIRES .' month'));
	       tep_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_shopping_points = customers_shopping_points + '". $customer_points['points_pending'] ."', customers_points_expires = '". $expire ."' WHERE customers_id = '". (int)$customer_points['customer_id'] ."'");
      } else {
	       tep_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_shopping_points = customers_shopping_points + '". $customer_points['points_pending'] ."' WHERE customers_id = '". (int)$customer_points['customer_id'] ."'");
      }

      if (isset($_POST['delete_points']) && ($_POST['delete_points'] == 'on')) {
        tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_POINTS_PENDING . " WHERE orders_id = '" . $oID . "' AND points_type = 'SP' LIMIT 1");
      }
      if (isset($_POST['confirm_points']) && ($_POST['confirm_points'] == 'on')) {
	       tep_db_query("UPDATE " . TABLE_CUSTOMERS_POINTS_PENDING . " SET points_status = 2 WHERE orders_id = '" . $oID . "' AND points_type = 'SP' LIMIT 1");
      }
    }
  }
  // Points/Rewards Module V2.00 EOF
}
?>