<?php
/*
  $Id: product_quantity_table.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$maxprices = defined('PRODUCT_QTY_PRICE_LEVEL') ? (int)PRODUCT_QTY_PRICE_LEVEL : 11;

if ($maxprices > 0 && $maxprices <= 11) {

$product_query = tep_db_query("select  products_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price9, products_price10, products_price11, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_price9_qty, products_price10_qty, products_price11_qty,products_tax_class_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$_GET['products_id'] . "'");
$product = tep_db_fetch_array($product_query);
$product_tax_id = $product['products_tax_class_id'];

// store the prices
$product_prices[0] = $product['products_price'];
$product_prices[1] = $product['products_price1'];
$product_prices[2] = $product['products_price2'];
$product_prices[3] = $product['products_price3'];
$product_prices[4] = $product['products_price4'];
$product_prices[5] = $product['products_price5'];
$product_prices[6] = $product['products_price6'];
$product_prices[7] = $product['products_price7'];
$product_prices[8] = $product['products_price8'];
$product_prices[9] = $product['products_price9'];
$product_prices[10] = $product['products_price10'];
$product_prices[11] = $product['products_price11'];

// check to see if there logically correct data supplied
if (($product_prices[1] < $product_prices[0] && $product_prices[1] != 0) &&
    ($product['products_price1_qty'] > 1)
   ) {
  
  $qtys = array(0 => 0, 1 => $product['products_price1_qty']);
  $prices = array(0 => $product_prices[0], 1 => $product_prices[1]);
  
  if ($maxprices > 1) {
    for ($i = 2; $i <= $maxprices; ++$i) {
      if (($product['products_price'.$i.'_qty'] > $qtys[$i-1]) &&
          ($product_prices[$i] < $prices[$i-1])) {
        $qtys[$i] = $product['products_price'.$i.'_qty'];
        $prices[$i] = $product_prices[$i];
      } else {
        break;
      }
    }
  }
  
  $quantitytable = '<!-- QTY TABLE GENERATION BOF -->' . "\n";
  $quantitytable .= '<table border="0" cellspacing="1" cellpadding="2" width="100%">' . "\n"; 
  $quantitytable .= '  <tr>' . "\n"; 
  $quantitytable .= '    <td class="smalltext">' . TEXT_QUANTITY_BASE . '</td>' . "\n"; 
  $quantitytable .= '    <td class="smalltext" align="right" valign="top">' . $currencies->display_price(sprintf("%01.2f",$prices[0]),tep_get_tax_rate($product_tax_id)) . '</td>' . "\n"; 
  $quantitytable .= '  </tr>' . "\n";
  
  for ($i = 1; $i <= $maxprices; ++$i) {
    if ( ! isset($qtys[$i]))  break;
    
    $range = $i == 1 ? $qtys[$i] : $qtys[$i]+1;
    $range .= ' - ';
    $range .= isset($qtys[$i+1]) ? $qtys[$i+1] : 'Above';
    
    $quantitytable .= '  <tr>' . "\n"; 
    $quantitytable .= '    <td class="smalltext">' . $range . '</td>' . "\n"; 
    $quantitytable .= '    <td class="smalltext" align="right" valign="top">' . $currencies->display_price(sprintf("%01.2f",$prices[$i]),tep_get_tax_rate($product_tax_id)) . '</td>' . "\n"; 
    $quantitytable .= '  </tr>' . "\n";
    
    // force a second table if there is enough rows
    if ($i == 5) {
      $quantitytable .= '</table>' . "\n";
      $quantitytable .= '</td><td valign="top">' . "\n";
      $quantitytable .= '<table border="0" align="right" cellspacing="0" cellpadding="0" width="100%">' . "\n";
    }
    
  }
  $quantitytable .= '</table>' . "\n";
  $quantitytable .= '<!-- QTY TABLE GENERATION EOF -->' . "\n";
  
  echo '<tr><td>' . "\n";
  $info_box_contents = array();
  $info_box_contents[] = array('text' => TEXT_QUALITY_PRICE_CHART);
  new contentBoxHeading($info_box_contents);

  $info_box_contents[0][0] = array('align' => 'center',
                                   'params' => 'valign="top"',
                                   'text' => $quantitytable);
  new contentBox($info_box_contents, true, true);
    
  if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                );
    new contentBoxFooter($info_box_contents);
  }
  echo '</td></tr>' . "\n";
  
  // clean up variables
  unset($qtys, $prices, $quantitytable, $info_box_contents);
}
// clean up variables
unset($product_query, $product, $product_prices);
}
?>