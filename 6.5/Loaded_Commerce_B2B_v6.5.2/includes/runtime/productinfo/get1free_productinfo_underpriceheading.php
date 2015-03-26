<?php
/*
  $Id: get1free_productinfo_underpriceheading.php,v 1.1.0.0 2008/03/11 13:41:11 maestro Exp $

  Released under the GNU General Public License
*/
  // check if module enabled                                                                
  if (defined('MODULE_ADDONS_GET1FREE_STATUS') && MODULE_ADDONS_GET1FREE_STATUS == 'True') {
    // check for placement
    if(defined('GET1FREE_PRODUCTINFO_PLACEMENT') && GET1FREE_PRODUCTINFO_PLACEMENT == 'underpriceheading') {
      // start Get 1 Free
      if (isset($_SESSION['languages_id']) && $languages_id == '') {
        $languages_id = (int)$_SESSION['languages_id']; 
      }
      $name_query = tep_db_query("SELECT products_name FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE products_id = '" . (int)$_GET['products_id'] . "' AND language_id = '" . (int)$_SESSION['languages_id'] . "'");
      $name = tep_db_fetch_array($name_query);
      $get_1_free_query = tep_db_query("select pd.products_name,
                                               g1f.products_free_quantity,
                                               g1f.products_qualify_quantity,
                                               g1f.products_free_id
                                        from " . TABLE_GET_1_FREE . " g1f,
                                             " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                        where g1f.products_id = '" . (int)$_GET['products_id'] . "'
                                          and pd.products_id = g1f.products_free_id
                                          and pd.language_id = '" . (int)$languages_id . "'
                                          and status = '1'"
                                      );
      // If this product qualifies for free product(s) display promotional text
      if (tep_db_num_rows($get_1_free_query) > 0) {
        $free_product = tep_db_fetch_array($get_1_free_query);
        $free_url = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $free_product['products_free_id'], 'NONSSL') . '" target="_self" title="' . $free_product['products_name'] . '"><b>' . $free_product['products_name'] . '</b></a>';
        echo '<tr><td align="center" width="98%"><div id="get1free" style="padding:4px; border:1px dashed #ff0000; margin-top:-10px; background-color:pink; font-size:16px;">' . sprintf(TEXT_GET_1_FREE_PROMOTION, $free_product['products_qualify_quantity'], $name['products_name'], $free_product['products_free_quantity'], $free_url) . '</div></td></tr>';
      }
      // end Get 1 Free
    } //end placement check
  } // end check if enabled
?>