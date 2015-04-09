<?php
/*
  $Id: customer_gv.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

$customer_gv = new box_customer_gv();
if ($customer_gv->amount > 0) {
?>
<!-- customer_gv -->
<div class="gift-voucher-infobox">
  <div class="box-header infoBoxHeading infoBoxHeadingCenter">
    <?php echo BOX_HEADING_GIFT_VOUCHER; ?>
  </div>
  <div class="row margin-top-10 margin-bottom-10">
    <div class="col-lg-12">
      <ul class="box-gift-voucher">
        <li class="text-center">
          <?php
            echo GIFT_VOUCHER_ACCOUNT_BALANCE_1 . 
                 $currencies->format($customer_gv->amount) . 
                 GIFT_VOUCHER_ACCOUNT_BALANCE_2 . 
                 '<button class="btn btn-default" onclick="location.href="' . tep_href_link(FILENAME_GV_SEND) . '">' . GIFT_VOUCHER_ACCOUNT_BALANCE_3 . '</button>';
          ?>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- customer_gv eof//-->
<?php
}
?>
