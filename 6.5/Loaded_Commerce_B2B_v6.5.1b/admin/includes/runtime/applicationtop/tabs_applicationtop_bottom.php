<?php
/*
  $Id: tabs_applicationtop_bottom.php,v 1.0.0.0 2008/02/06 13:41:11 maestro Exp $

  CRELoaded osCommerce Templates
  http://www.creloadedoscommercetemplates.com

  Copyright (c) 2008 CRE Loaded osCommerce Templates
  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 
  function tep_get_products_tab_2($product_id, $language_id) {
    $product_query = tep_db_query("select products_tab_2 from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_tab_2'];
  }
  function tep_get_products_tab_3($product_id, $language_id) {
    $product_query = tep_db_query("select products_tab_3 from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_tab_3'];
  }
  function tep_get_products_tab_4($product_id, $language_id) {
    $product_query = tep_db_query("select products_tab_4 from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_tab_4'];
  }
  
  define('TEXT_PRODUCTS_TAB_DESCRIPTION_DEFAULT', 'Description');
  define('TEXT_PRODUCTS_TAB_2', 'Features');
  define('TEXT_PRODUCTS_TAB_3', 'Warranty');
  define('TEXT_PRODUCTS_TAB_4', 'Shipping');
?>