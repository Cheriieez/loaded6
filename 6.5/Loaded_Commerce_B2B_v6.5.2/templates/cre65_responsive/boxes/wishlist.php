<?php
/*
  $Id: wishlist.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (basename($PHP_SELF) != FILENAME_WISHLIST_SEND && basename($PHP_SELF) != FILENAME_WISHLIST) {
  $wishlist = new box_wishlist();
?> 
<!-- wishlist //-->
<div class="wishlist-infobox">
  <div class="box-header infoBoxHeading infoBoxHeadingCenter">
    <?php echo BOX_HEADING_WISHLIST; ?>
  </div>
  <div class="row margin-top-10 margin-bottom-10">
    <div class="col-lg-12"> 
      <?php
        if (count($wishlist->rows) > 0) {
          echo '<div class="margin-5">';
          foreach ($wishlist->rows as $product_id) {
            $products = $pf->loadProduct($product_id, $languages_id);
            echo '<p><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($products['products_id']) . '&products_id=' . $products['products_id'], 'NONSSL') . '">' . $products['products_name'] . '</a></p>';
            echo '<p class="text-center"><a class="btn btn-success margin-right-10" href="' . tep_href_link(FILENAME_WISHLIST, tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $products['products_id'] . '&cPath=' . tep_get_product_path($products['products_id']), 'NONSSL') . '">' . BOX_TEXT_MOVE_TO_CART . '</a>';
            echo '<a class="btn btn-danger" href="' . tep_href_link(FILENAME_WISHLIST, tep_get_all_get_params(array('action')) . 'action=remove_wishlist&pid=' . $products['products_id'], 'NONSSL') . '">' . BOX_TEXT_DELETE . '</a></b><p>';
          }
          echo '</div>';
        } else {
          echo '<p class="text-center">' . BOX_WISHLIST_EMPTY . '</p>';
        }
        echo '<p class="text-right margin-right-5 margin-bottom-5"><a href="' . tep_href_link(FILENAME_WISHLIST, '','NONSSL') . '">' . BOX_HEADING_CUSTOMER_WISHLIST . ' <i class="fa fa-gift red"></i></a></p>';
        echo '<p class="text-right margin-right-5"><a href="' . tep_href_link(FILENAME_WISHLIST_HELP, '','NONSSL') . '">' . BOX_HEADING_CUSTOMER_WISHLIST_HELP . ' <i class="fa fa-question"></i></a></p>'; // Normal link
      ?>
    </div>
  </div>
</div>
<?php
}
?>
<!-- wishlist eof//-->
