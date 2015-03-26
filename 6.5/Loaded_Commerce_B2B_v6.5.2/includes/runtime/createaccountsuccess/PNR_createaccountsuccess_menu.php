<?php
/*
  $Id: PNR_createaccountsuccess_menu.php,v 1.0.0 2008/05/22 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $currencies; 
?>
<!-- Points/Rewards Module V2.00 bof-->
<?php  
  if ((MODULE_ADDONS_POINTS_STATUS == 'True') && (NEW_SIGNUP_POINT_AMOUNT > 0)) {
?>
<tr>
  <td class="main"><?php echo sprintf(TEXT_WELCOME_POINTS_TITLE, number_format(NEW_SIGNUP_POINT_AMOUNT,POINTS_DECIMAL_PLACES), $currencies->format(tep_calc_shopping_pvalue(NEW_SIGNUP_POINT_AMOUNT))); ?>.</td>
</tr>
<tr>
  <td class="main"><?php echo TEXT_WELCOME_POINTS_LINK; ?></td>
</tr>
<?php
  }
?>               
<!-- Points/Rewards Module V2.00 eof-->