<?php
/*
  $Id: shopping_cart.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
//declare and intilize variables
$products = '';
$cart_contents_string = '';
$new_products_id_in_cart = '';
if (basename($PHP_SELF) != FILENAME_SHOPPING_CART) {
?>
<!-- shopping_cart //-->
<div class="shopping-cart-infobox">
  <div class="box-header infoBoxHeading infoBoxHeadingCenter">
    <?php echo BOX_HEADING_SHOPPING_CART; ?>
  </div>
  <div class="row margin-top-10 margin-bottom-10">
    <div class="col-lg-12">
      <ul class="box-shopping-cart">
        <li class="margin-right-5">
          <?php
            if ($cart->count_contents() > 0) {
              $cart_contents_string = '<div class="row">';
              $products = $cart->get_products();
              for ($i=0, $n=sizeof($products); $i<$n; $i++) {
                $cart_contents_string .= '<div class="text-right col-lg-12">';
                $db_sql = "select products_parent_id from " . TABLE_PRODUCTS . " where products_id = " . (int)$products[$i]['id'];
                $products_parent_id = tep_db_fetch_array(tep_db_query($db_sql));
                if ((int)$products_parent_id['products_parent_id'] != 0) {
                  $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_parent_id['products_parent_id']) . '">';
                } else {
                  $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';
                }
                $cart_contents_string .= $products[$i]['name'] . '</a><div>';
                if (isset($_SESSION['new_products_id_in_cart']) && ($_SESSION['new_products_id_in_cart'] == $products[$i]['id'])) {
                  unset($_SESSION['new_products_id_in_cart']);
                }
              }
              $sub_total = $cart->show_total();
              if ($sub_total == 0) {
                $sub_total = 'Free';
              } else {
                $sub_total = $currencies->format($cart->show_total());
              }
              $cart_contents_string .= '<hr class="margin-top-10 margin-bottom-10">';
              $cart_contents_string .= '<p class="text-right">' . $sub_total . '</p>';
              $cart_contents_string .= '<p class="text-right"><button class="btn btn-danger" onclick="location.href=\''. tep_href_link(FILENAME_CHECKOUT_SHIPPING) . '\'">' . IMAGE_BUTTON_CHECKOUT . '</button></p>';
            } else {
              $cart_contents_string .= '<div class="text-right col-lg-12 padding-right-0 margin-bottom-5">' . BOX_SHOPPING_CART_EMPTY . '</div>';
            }
            
            if (isset($_SESSION['gv_id'])) {
              $gv_query = tep_db_query("select coupon_amount from " . TABLE_COUPONS . " where coupon_id = '" . $_SESSION['gv_id'] . "'");
              $coupon = tep_db_fetch_array($gv_query);
              $cart_contents_string .= '<hr class="margin-top-10 margin-bottom-10">';
              $cart_contents_string .= '<p class="text-right">' . VOUCHER_REDEEMED . ' ' . $currencies->format($coupon['coupon_amount']) . '</p>';
            }
            
            if (isset($_SESSION['cc_id']) && tep_not_null($_SESSION['cc_id'])) {
              $cart_coupon_query = tep_db_query("select coupon_code, coupon_type from " . TABLE_COUPONS . " where coupon_id = '" . (int)$_SESSION['cc_id'] . "'");
              $cart_coupon_info = tep_db_fetch_array($cart_coupon_query);
              $cart_contents_string .= '<hr class="margin-top-10 margin-bottom-10">';
              $cart_contents_string .= '<p class="text-right">' . CART_COUPON . ' ' . $cart_coupon_info['coupon_code'] . ' <a href="javascript:couponpopupWindow(\'' . tep_href_link(FILENAME_POPUP_COUPON_HELP, 'cID=' . $_SESSION['cc_id']) . '\')"><i class="fa fa-question-circle margin-left-5" title="' . CART_COUPON_INFO . '"></i></a></p>';
              if ($cart_coupon_info['coupon_type'] == 'F') {
                $cart_contents_string .= '<p class="text-right">Free Shipping</p>';
              }
            }
            
            if (isset($_SESSION['customer_id'])) {
              $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $_SESSION['customer_id'] . "'");
              $gv_result = tep_db_fetch_array($gv_query);
              if ($gv_result['amount'] > 0 ) {
                $cart_contents_string .= '<hr class="margin-top-10 margin-bottom-10">';
                $cart_contents_string .= '<p class="text-right">' . VOUCHER_BALANCE . ' ' . $currencies->format($gv_result['amount']) . '</p>';
                $cart_contents_string .= '<p class="text-right"><button class="btn btn-default" onclick="location.href=\''. tep_href_link(FILENAME_GV_SEND) . '\'">' . BOX_SEND_TO_FRIEND . '</button></p>';
              }
            }
            
            echo $cart_contents_string;
          ?>
        </li>
      </ul>
    </div>
  </div>
  <script type="text/javascript">
  <!--//
    function couponpopupWindow(url) {
      window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
    }
  //-->
  </script>
</div>
<!-- shopping_cart_eof //-->
<?php } ?>