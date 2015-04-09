<?php
/*
  $Id: PNR_checkoutconfirmation_top.php,v 1.0.0 2008/05/22 13:41:11 maestro Exp $

  Loaded Commerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2008 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (MODULE_ADDONS_POINTS_STATUS == 'True') { 
  global $order;
  if (isset($_SESSION['point_covers']) && $order->info['payment_method'] == '') {
    $order->info['payment_method'] = 'Points/Rewards';
  }
}
?>