<?php
/*
  $Id: get1free_applicationtop_bottom.php, v1.5 2003/04/25 21:37:11 hook Exp $

  Released under the GNU General Public License
*/

  define('TABLE_GET_1_FREE', 'get_1_free');
  define('FILENAME_GET_1_FREE', 'get_1_free.php');
  define('BOX_CATALOG_GET_1_FREE', 'Get 1 Free');
  
  function tep_draw_get1free_pull_down($name, $parameters = '', $exclude = '') {
    global $currencies, $languages_id;

    if ($exclude == '') {
      $exclude = array();
    }

    $select_string = '<select name="' . $name . '"';

    if ($parameters) {
      $select_string .= ' ' . $parameters;
    }

    $select_string .= ' onChange="this.form.txtpflag.value=1;">';

    $fields = mysql_list_fields(DB_DATABASE, 'customers');
    $columns = mysql_num_fields($fields);
    for ($i = 0; $i < $columns; $i++) {
      $field_array[] = mysql_field_name($fields, $i);
    }
    if (in_array('customers_group_id', $field_array)) {
      // Eversun mod for sppc and qty price breaks
      $all_groups = array();
      $customers_groups_query = tep_db_query("select customers_group_name, customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
      while ($existing_groups = tep_db_fetch_array($customers_groups_query)) {
        $all_groups[$existing_groups['customers_group_id']] = $existing_groups['customers_group_name'];
      }
      // Eversun mod end for sppc and qty price breaks  
    }
    $products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_price
                                    FROM " . TABLE_PRODUCTS . " p,
                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                    WHERE p.products_id = pd.products_id
                                      and pd.language_id = '" . (int)$languages_id . "'
                                      and (p.products_status = '1'
                                          or (p.products_status <> '1' and p.products_parent_id <> 0))
                                    ORDER BY products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      if (in_array('customers_group_id', $field_array)) {
        // Eversun mod for sppc and qty price breaks
        if (!in_array($products['products_id'], $exclude)) {
          $price_query = tep_db_query("select customers_group_price, customers_group_id from " . TABLE_PRODUCTS_GROUPS . " where products_id = " . $products['products_id']);
          $product_prices = array();
          while ($prices_array = tep_db_fetch_array($price_query)) {
            $product_prices[$prices_array['customers_group_id']] = $prices_array['customers_group_price'];
          }
          reset($all_groups);
          $price_string = "";
          $sde = 0;
          while (list($sdek, $sdev) = each($all_groups)) {
            if (!in_array((int)$products['products_id'] . ":" . (int)$sdek, $exclude)) {
              if ($sde) $price_string .= ", ";
              $price_string .= $sdev . ": " . $currencies->format(isset($product_prices[$sdek]) ? $product_prices[$sdek] : $products['products_price']);
              $sde = 1;
            }
          }
          $g1fID_query = tep_db_query("SELECT products_free_id FROM get_1_free WHERE get_1_free_id = '" . (int)$_GET['fID'] . "'");
          while ($g1fID = tep_db_fetch_array($g1fID_query)) {
            if ($g1fID['products_free_id'] == $products['products_id']) {
              $strtmp =  ' selected';
            } else {
              $strtmp = '';
            }
          }
          $select_string .= '<option value="' . $products['products_id'] . '" '.$strtmp.'>' . $products['products_name'] . ' (' . $price_string . ')</option>\n';
        }
      } else {
        if (!in_array($products['products_id'], $exclude)) {
          $price_query = tep_db_fetch_array(tep_db_query("select products_price from " . TABLE_PRODUCTS . " where products_id = " . $products['products_id']));
          $g1fID_query = tep_db_query("SELECT products_free_id FROM get_1_free WHERE get_1_free_id = '" . (int)$_GET['fID'] . "'");
          while ($g1fID = tep_db_fetch_array($g1fID_query)) {
            if ($g1fID['products_free_id'] == $products['products_id']) {
              $strtmp =  ' selected';
            } else {
              $strtmp = '';
            }
          }
          $select_string .= '<option value="' . $products['products_id'] . '" '.$strtmp.'>' . $products['products_name'] . ' (' . $currencies->format($price_query['products_price']) . ')</option>\n';
        }
      }
      // Eversun mod end for sppc and qty price breaks
    }

    $select_string .= '</select><input type ="hidden" name = "txtpflag" value = "0">';

    return $select_string;
  }
?>