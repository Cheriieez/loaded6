<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('advancedsearchresult', 'top');
// RCI code eof 
?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo 'Products meeting the search criteria' ?></h1>
<div class="clearfix"></div>

<?php
function replace_hyphen($keyword){
	if (strpos($keyword,'-') !== false) {
		return '"'.$keyword.'*"';
	}
	else
	{
		return $keyword.'*';
	}
}
function keyword_replace($key)
{
	$keys=explode(" ",$key);
	$new_keyword='';
	for($i=0;$i<count($keys);$i++)
	{
		if($i==0)
		{
			$new_keyword .= str_replace("'", "&#39;",tep_db_prepare_input(replace_hyphen($keys[$i])));
		}
		else
		{
			$new_keyword .= ' '.str_replace("'", "&#39;",tep_db_prepare_input(replace_hyphen($keys[$i])));
		}
		
	}
	return $new_keyword;
}
// create column list
  $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                       'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

  asort($define_list);

  $column_list = array();
  reset($define_list);
  while (list($key, $value) = each($define_list)) {
    if ($value > 0) $column_list[] = $key;
  }

// Eversun mod for sppp
  if(!isset($_SESSION['sppc_customer_group_id'])) {
    $customer_group_id = 'G';
  } else {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
// Eversun mod end for sppp
  
  $customer_group_array = array();
  if(!isset($_SESSION['sppc_customer_group_id'])) {
    $customer_group_array[] = 'G';
  } else {
    $customer_group_array = tep_get_customers_access_group($_SESSION['customer_id']);
  }
  
  $select_column_list = '';

  for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
    switch ($column_list[$i]) {
      case 'PRODUCT_LIST_MODEL':
        $select_column_list .= 'p.products_model, ';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $select_column_list .= 'm.manufacturers_name, ';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $select_column_list .= 'p.products_quantity, ';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $select_column_list .= 'p.products_image, ';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $select_column_list .= 'p.products_weight, ';
        break;
    }
  }
  $select_column_list .= 'p.products_model, ';

// Eversun mod for sppp
/*
  $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_id, pd.products_name, p.products_price, p.products_tax_class_id,
                        IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price,
                        IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    $select_str .= ", SUM(tr.tax_rate) as tax_rate ";
  }

  $from_str = "from " . TABLE_PRODUCTS . " p
               left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id),
                    " . TABLE_PRODUCTS_DESCRIPTION . " pd
               left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id,
                    " . TABLE_CATEGORIES . " c,
                    " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c";
*/

   $status_tmp_product_prices_table = false;
   $status_need_to_get_prices = false;
   // find out if sorting by price has been requested
   if ( (isset($_GET['sort'])) && (preg_match('/[1-8][ad]/', $_GET['sort'])) && (substr($_GET['sort'], 0, 1) <= sizeof($column_list)) ){
    $_sort_col = substr($_GET['sort'], 0 , 1);
    if ($column_list[$_sort_col-1] == 'PRODUCT_LIST_PRICE') {
      $status_need_to_get_prices = true;
      }
   }
   if (isset($search_keywords) && (sizeof($search_keywords) > 0)) {

    for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
      switch ($search_keywords[$i]) {
        default:
          $keyword = str_replace("'", "&#39;", tep_db_prepare_input($search_keywords[$i]));
		  if (strpos($keyword,"**")!==FALSE || strpos($keyword,".")!== FALSE){
			$keyword = str_replace("**"," ", $keyword);
			if(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $keyword))
			{
				$keyword = '"'.$keyword.'"';
			}
		  }
		  $splitkeyword	=	explode(" ",$keyword);
		  if(count($splitkeyword)>1)
		  {
		  	$newkeyword	=	'';
			for($j=0; $j<count($splitkeyword); $j++)
			{
				 //echo $splitkeyword[$j];e
				 if($j==0 && strpos($splitkeyword[$j],'"')==='0')
				 {
				 	$tempnewkeyword	= ltrim($splitkeyword[$j],'"');
					$newkeyword	.=	'"+'.$tempnewkeyword.' ';
				 }
				 else
				 {
				 	$newkeyword	.=	'+'.$splitkeyword[$j].' ';
				 }
			}
			$newkeyword	=	rtrim($newkeyword);
		  }
		  else
		  {
		  	$newkeyword	=	$keyword;
		  }		  
          break;
      }
    }
   }
   
   $select_str = "select distinct " . $select_column_list . "
   m.manufacturers_id,
   p.products_id,
   pd.products_name,
   pd.products_description,
   p.products_price,
   p.products_price1,
   p.products_price2,
   p.products_price3,
   p.products_price4,
   p.products_price5,
   p.products_price6,
   p.products_price7,
   p.products_price8,
   p.products_price9,
   p.products_price10,
   p.products_price11,
   p.products_price1_qty,
   p.products_price2_qty,
   p.products_price3_qty,
   p.products_price4_qty,
   p.products_price5_qty,
   p.products_price6_qty,
   p.products_price7_qty,
   p.products_price8_qty,
   p.products_price9_qty,
   p.products_price10_qty,
   p.products_price11_qty,
   p.products_qty_blocks,
   p.products_tax_class_id,
   IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price,
   IF(s.status, s.specials_new_products_price, p.products_price) as final_price,
   MATCH(pd.products_name) AGAINST('" . ($newkeyword) . "') as relevance,
   MATCH(p.products_model,p.product_attribute_data,p.sub_products_data) AGAINST('" . ($newkeyword) . "') as relevance2 ";
   //$select_str .= ",MATCH(p.products_model,p.product_attribute_data) AGAINST ('" . $_GET['keywords'] . "') as Relevance ";
   //$select_str .= ",MATCH(pd.products_name) AGAINST ('" . $_GET['keywords'] . "') as Relevance2 ";
   if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    $select_str .= ", SUM(tr.tax_rate) as tax_rate ";
  }

   $from_str = "from (((" . TABLE_PRODUCTS . " p
               left join " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . (int)$customer_group_id . "')
               left join " . TABLE_SPECIALS . " s on(s.products_id = p.products_id and s.status = 1 and s.customers_group_id = " . (int)$customer_group_id . ") )
               left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id )";

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    if (!isset($_SESSION['customer_country_id'])) {
      $_SESSION['customer_country_id'] = STORE_COUNTRY;
      $_SESSION['customer_zone_id'] = STORE_ZONE;
    }
    $from_str .= " left join " . TABLE_TAX_RATES . " tr on p.products_tax_class_id = tr.tax_class_id left join " . TABLE_ZONES_TO_GEO_ZONES . " gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$_SESSION['customer_country_id'] . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$_SESSION['customer_zone_id'] . "'),";
  } else { 
    $from_str .= " , ";
  }
  
  // modified for mysql 5 support - add tables after the left join tables
  $from_str .= TABLE_PRODUCTS_DESCRIPTION . " pd,
               " . TABLE_CATEGORIES . " c,
               " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c ";
  
  $where_str = " where p.products_status = 1
                   and p.products_id = pd.products_id
                   and pd.language_id = " . (int)$languages_id . "
                   and p.products_id = p2c.products_id
                   and p2c.categories_id = c.categories_id ";

  if (isset($_GET['categories_id']) && tep_not_null($_GET['categories_id'])) {
    if (isset($_GET['inc_subcat']) && ($_GET['inc_subcat'] == '1')) {
      $subcategories_array = array();
      tep_get_subcategories($subcategories_array, $_GET['categories_id']);

      $where_str .= " and p2c.products_id = p.products_id
                      and p2c.products_id = pd.products_id
                      and (p2c.categories_id = " . (int)$_GET['categories_id'];

      for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
        $where_str .= " or p2c.categories_id = " . (int)$subcategories_array[$i];
      }

      $where_str .= ")";
    } else {
      $where_str .= " and p2c.products_id = p.products_id
                      and p2c.products_id = pd.products_id
                      and pd.language_id = " . (int)$languages_id . "
                      and p2c.categories_id = " . (int)$_GET['categories_id'];
    }
  }

  if (isset($_GET['manufacturers_id']) && tep_not_null($_GET['manufacturers_id'])) {
    $where_str .= " and m.manufacturers_id = " . (int)$_GET['manufacturers_id'];
  }

  if (isset($search_keywords) && (sizeof($search_keywords) > 0)) {
    $where_str .= " and (";
    for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
      switch ($search_keywords[$i]) {
        case '(':
        case ')':
        case 'and':
        case 'or':
          $where_str .= " " . str_replace("'", "&#39;", tep_db_prepare_input($search_keywords[$i])) . " ";
          break;
        default:
          $keyword = str_replace("'", "&#39;", tep_db_prepare_input($search_keywords[$i]));
		  //echo $keyword; exit;
		  if (strpos($keyword,"**")!==FALSE || strpos($keyword,".")!== FALSE){
			$keyword = str_replace("**"," ", $keyword);
			if(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $keyword))
			{
				$keyword = '"'.$keyword.'"';
			}
		  }
		  
		  $splitkeyword	=	explode(" ",$keyword);
		  if(count($splitkeyword)>1)
		  {
		  	$newkeyword	=	'';
			for($j=0; $j<count($splitkeyword); $j++)
			{
				 //echo strpos($splitkeyword[$j],'"'); exit;
				 if($j==0 && strpos($splitkeyword[$j],'"')==='0')
				 {
				 	$tempnewkeyword	= ltrim($splitkeyword[$j],'"');
					$newkeyword	.=	'"+'.$tempnewkeyword.' ';
				 }
				 else
				 {
				 	$newkeyword	.=	'+'.$splitkeyword[$j].' ';
				 }
			}
			$newkeyword	=	rtrim($newkeyword);
		  }
		  else
		  {
		  	$newkeyword	=	$keyword;
		  }
		  $modelkeyword	=	rtrim($keyword,'"');
		  $modelkeyword	=	ltrim($modelkeyword,'"');
		  $where_str .= "MATCH(pd.products_name) AGAINST('" . ($newkeyword) . "' IN BOOLEAN MODE)";
		  $where_str .= " OR MATCH(p.products_model,p.product_attribute_data,p.sub_products_data) AGAINST('" . ($newkeyword) . "' IN BOOLEAN MODE)";
		  $where_str .= " OR p.products_model like '%" . ($modelkeyword) . "%' OR p.sub_products_data like '%" . ($modelkeyword) . "%'";
          break;
      }
    }
    $where_str .= " )";
  }

  if (tep_not_null($dfrom)) {
    $where_str .= " and p.products_date_added >= '" . tep_date_raw($dfrom) . "'";
  }

  if (tep_not_null($dto)) {
    $where_str .= " and p.products_date_added <= '" . tep_date_raw($dto) . "'";
  }

  if (tep_not_null($pfrom)) {
    if ($currencies->is_set($currency)) {
      $rate = $currencies->get_value($currency);

      $pfrom = $pfrom / $rate;
    }
  }

  if (tep_not_null($pto)) {
    if (isset($rate)) {
      $pto = $pto / $rate;
    }
  }

  if (DISPLAY_PRICE_WITH_TAX == 'true') {
    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . (double)$pto . ")";
  } else {
    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) <= " . (double)$pto . ")";
  }

  $where_str .= tep_get_access_sql('p.products_group_access', $customer_group_array);

// Eversun mod end for group_access check

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    $where_str .= " group by p.products_id, tr.tax_priority";
  }

  if ( (!isset($_GET['sort'])) || (!preg_match('/[1-8][ad]/', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > sizeof($column_list)) ) {
    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
        $_GET['sort'] = $i+1 . 'a';
        $order_str = ' order by relevance DESC, relevance2 DESC ';
		//$order_str = ' order by pd.products_name';
        break;
      }
    }
  } else {
    $sort_col = substr($_GET['sort'], 0 , 1);
    $sort_order = substr($_GET['sort'], 1);
    $order_str = ' order by (relevance)';
    /*switch ($column_list[$sort_col-1]) {
      case 'PRODUCT_LIST_MODEL':
        $order_str .= "p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_NAME':
        $order_str .= "pd.products_name " . ($sort_order == 'd' ? "desc" : "");
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $order_str .= "m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $order_str .= "p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_IMAGE':
        $order_str .= "pd.products_name";
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $order_str .= "p.products_weight " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_PRICE':
        $order_str .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
        break;
      default:
        // if no match, then there is no order by
        $order_str = '';
        break;
    }*/
  }
  
  $listing_sql = $select_str . $from_str . $where_str . $order_str;
	//echo $listing_sql;
//  require(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . 'product_listing_col.php')) {
            require(TEMPLATE_FS_CUSTOM_MODULES . 'product_listing_col.php');
        } else {
            require(DIR_WS_MODULES . 'product_listing_col.php');
        }
?>
        </td>
      </tr>
<?php
// RCI code start
echo $cre_RCI->get('advancedsearchresult', 'menu');
// RCI code eof 
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(array('sort', 'page')), 'NONSSL', true, false) . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
      </tr>
    </table>
<?php
// RCI code start
echo $cre_RCI->get('advancedsearchresult', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof 
?>