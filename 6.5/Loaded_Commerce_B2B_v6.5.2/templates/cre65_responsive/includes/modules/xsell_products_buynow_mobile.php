<?php
/**
  $Id: xsell_products.php, v1  2002/09/11
  osCommerce, Open Source E-Commerce Solutions
  <http://www.oscommerce.com>
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License
*/

  if (!isset($_SESSION['sppc_customer_group_id'])) {
    $customer_group_id = 'G';
  } else {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
  if ($_GET['products_id']) {
    $xsell_query = tep_db_query("select distinct p.products_id,
                                                 p.products_image, 
                                                 p.products_price, 
                                                 p.manufacturers_id,
                                                 pd.products_name,
                                                 p.products_tax_class_id,
                                                 if (isnull(pg.customers_group_price), p.products_price, pg.customers_group_price) as product_price,
                                                 p.products_date_added, 
                                                 p.products_image ,
                                                 xp.xsell_id
                                            from ((" . TABLE_PRODUCTS . " p
                                       left join " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . $customer_group_id . "')
                                       left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id),
                                                 " . TABLE_PRODUCTS_XSELL . " xp,
                                                 " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                           where xp.products_id = '" . (int)$_GET['products_id'] . "' 
                                             and p.products_id = xp.xsell_id
                                             and pd.products_id = xp.xsell_id
                                             and pd.language_id = '" . $languages_id . "' 
                                             and p.products_status = '1' 
                                             and p.products_group_access like '%" . $customer_group_id . "%'
                                        order by rand(), xp.products_id asc ");
    $num_products_xsell = tep_db_num_rows($xsell_query);
    if ($num_products_xsell >= 1) {
      echo '<div id="prod-related" class="carousel slide" data-ride="carousel">';  
      echo '<h3>Recommended Products</h3><div class="row"><div class="yslider">';

      require(DIR_WS_LANGUAGES . $language . '/modules/xsell_products.php');
    
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_XSELL_PRODUCTS);
      //new contentBoxHeading($info_box_contents);
      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($xsell = tep_db_fetch_array($xsell_query)) {
        $pf->loadProduct($xsell['products_id'], $languages_id);
        $xsell_price = $pf->getPriceStringShort();
        $s_buy_now = '';
        $hide_add_to_cart = hide_add_to_cart();
        if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
          $s_buy_now = '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action', 'cPath', 'products_id')) . 'action=buy_now&products_id=' . $xsell['products_id'] . '&cPath=' . tep_get_product_path($xsell['products_id']), 'NONSSL') . '" class="addcart"><i class="fa fa-shopping-cart fa-1x"></i>Add to cart</a>';
        }

        $img = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '"><img class="img-responsive" src="' . DIR_WS_IMAGES . str_replace("up_small", "up_large", $xsell['products_image']) . '"></a>';

        if ($col == 0) {
          $active = ' active';
        } else {
          $active = '';
        }
        echo '<div class="item' . $active . '">
                <div>
                  <div>
                    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">
                      ' . $xsell['products_name'] .'
                    </a>
                  </div>
                  <div>
                    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">
                      <b>$' . number_format($xsell['products_price'], 2) . '</b>
                    </a>
                  </div>
                </div>
                <div>' . $img . '</div>
                <div class="clearfix"></div>
              </div>';

        $col++;
      }
      echo '</div></div>';
    }
  }
?>