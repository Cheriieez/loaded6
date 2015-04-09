<?php 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('shoppingcart', 'top');
  // RCI code eof
  if ($cart->count_contents() > 0) {
    echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product'));
  }
  if (isset($_GET['errormsg'])) {
    echo '<p class="bg-danger" style="padding:2px 5px;">'.$_GET['errormsg'].'</p>';
  }
?>
<div class="row-fluid">
  <div class="account-login">
    <div class="sideblocks">
      <h1 class="col-lg-12 gry_box2 y_clr con_txt"><?php echo HEADING_TITLE; ?></h1>
    </div>
    <div class="clearfix"></div>
    <?php if ($cart->count_contents() > 0) { ?>    
      <div class="row-fluid">
        <div class="content span12">
          <div class="row-fluid accregister">
            <?php 

              if ($cart->count_contents() > 0) {
                echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product'));
              }
            ?>
            <div class="new-users span12">
              <?php
                $info_box_contents = array();
                $info_box_contents[0][] = array('align' => 'center',
                  'params' => 'class="productListing-heading"',
                  'text' => TABLE_HEADING_REMOVE);

                $info_box_contents[0][] = array('params' => 'class="productListing-heading"',
                  'text' => TABLE_HEADING_PRODUCTS);

                $info_box_contents[0][] = array('align' => 'center',
                  'params' => 'class="productListing-heading"',
                  'text' => TABLE_HEADING_QUANTITY);

                $info_box_contents[0][] = array('align' => 'right',
                  'params' => 'class="productListing-heading"',
                  'text' => TABLE_HEADING_UNIT_PRICE);

                $info_box_contents[0][] = array('align' => 'right',
                  'params' => 'class="productListing-heading"',
                  'text' => TABLE_HEADING_TOTAL);

                $any_out_of_stock = 0;
                $products = $cart->get_products();
                // echo '<pre>'; print_r($products); exit;

                for ($i=0, $n=sizeof($products); $i<$n; $i++) {
                  $db_sql = "select products_parent_id from " . TABLE_PRODUCTS . " where products_id = " . (int)$products[$i]['id'];
                  $products_parent_id = tep_db_fetch_array(tep_db_query($db_sql));
                  // Push all attributes information in an array
                  if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
                    $attribute_product_id = (int)$products[$i]['id'];
                    if ((int)$products_parent_id['products_parent_id'] != 0) {
                      $attribute_product_id = (int)$products_parent_id['products_parent_id'];
                    }

                    reset($products[$i]['attributes']);
                    while (list($option, $value) = each($products[$i]['attributes'])) {
                      if ( ! is_array($value) ) {
                        $attributes = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, ov.products_options_values_name, op.options_values_price, op.price_prefix
                          from " . TABLE_PRODUCTS_ATTRIBUTES . " op,   
                          " . TABLE_PRODUCTS_OPTIONS_VALUES . " ov, 
                          " . TABLE_PRODUCTS_OPTIONS . " o,
                          " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot 
                          where op.products_id = " . $attribute_product_id . "
                          and op.options_values_id = " . $value . "
                          and op.options_id = " . $option . "
                          and ov.products_options_values_id = op.options_values_id
                          and ov.language_id = " . (int)$languages_id . "
                          and o.products_options_id = op.options_id
                          and ot.products_options_text_id = o.products_options_id
                          and ot.language_id = " . (int)$languages_id . "
                        ");
                        $attributes_values = tep_db_fetch_array($attributes);

                        $products[$i][$option][$value]['products_options_name'] = $attributes_values['products_options_name'];
                        $products[$i][$option][$value]['options_values_id'] = $value;
                        $products[$i][$option][$value]['products_options_values_name'] = $attributes_values['products_options_values_name'];
                        $products[$i][$option][$value]['options_values_price'] = $attributes_values['options_values_price'];
                        $products[$i][$option][$value]['price_prefix'] = $attributes_values['price_prefix'];

                      } elseif ( isset($value['c'] ) ) {
                        foreach ($value['c'] as $v) {
                          $attributes = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, ov.products_options_values_name, op.options_values_price, op.price_prefix
                            from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                            " . TABLE_PRODUCTS_OPTIONS_VALUES . " ov,
                            " . TABLE_PRODUCTS_OPTIONS . " o,
                            " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                            where op.products_id = " . $attribute_product_id . "
                            and op.options_values_id = " . $v . "
                            and op.options_id = " . $option . "
                            and ov.products_options_values_id = op.options_values_id
                            and ov.language_id = " . (int)$languages_id . "
                            and o.products_options_id = op.options_id
                            and ot.products_options_text_id = o.products_options_id
                            and ot.language_id = " . (int)$languages_id . "
                          ");
                          $attributes_values = tep_db_fetch_array($attributes);

                          $products[$i][$option][$v]['products_options_name'] = $attributes_values['products_options_name'];
                          $products[$i][$option][$v]['options_values_id'] = $v;
                          $products[$i][$option][$v]['products_options_values_name'] = $attributes_values['products_options_values_name'];
                          $products[$i][$option][$v]['options_values_price'] = $attributes_values['options_values_price'];
                          $products[$i][$option][$v]['price_prefix'] = $attributes_values['price_prefix'];
                        }

                      } elseif ( isset($value['t'] ) ) {
                        $attributes = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, op.options_values_price, op.price_prefix
                          from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                          " . TABLE_PRODUCTS_OPTIONS . " o,
                          " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                          where op.products_id = " . $attribute_product_id . "
                          and op.options_id = " . $option . "
                          and o.products_options_id = op.options_id
                          and ot.products_options_text_id = o.products_options_id
                          and ot.language_id = " . (int)$languages_id . "
                        ");
                        $attributes_values = tep_db_fetch_array($attributes);

                        $products[$i][$option]['t']['products_options_name'] = $attributes_values['products_options_name'];
                        $products[$i][$option]['t']['options_values_id'] = '0';
                        $products[$i][$option]['t']['products_options_values_name'] = $value['t'];
                        $products[$i][$option]['t']['options_values_price'] = $attributes_values['options_values_price'];
                        $products[$i][$option]['t']['price_prefix'] = $attributes_values['price_prefix'];
                      }
                    }
                  }
                }

                for ($i=0, $n=sizeof($products); $i<$n; $i++) {
                  if (($i/2) == floor($i/2)) {
                    $info_box_contents[] = array('params' => 'class="productListing-even"');
                  } else {
                    $info_box_contents[] = array('params' => 'class="productListing-odd"');
                  }
                  $db_sql = "select products_parent_id from " . TABLE_PRODUCTS . " where products_id = " . (int)$products[$i]['id'];
                  $products_parent_id = tep_db_fetch_array(tep_db_query($db_sql));
                  $cur_row = sizeof($info_box_contents) - 1;
                  //echo $products_parent_id['products_parent_id']
                  if ($products_parent_id['products_parent_id'] != 0) {
                    if($products[$i]['image'] != ''){
                      $pimg	=	$products[$i]['image'];
                    } else {
                      $par_pro_res=tep_db_fetch_array(tep_db_query("select products_image from products where products_id=".$products_parent_id['products_parent_id']));
                      $pimg	=	$par_pro_res['products_image'];
                    }
                    $img = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_parent_id['products_parent_id']) . '"><img src="'.DIR_WS_IMAGES. $pimg.'" alt="'.$products[$i]['name'].'" style="max-height: 150px;max-width: 150px;"></a>';
                  } else {
                    $img = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><img src="'.DIR_WS_IMAGES. $products[$i]['image'].'" alt="'.$products[$i]['name'].'" style="max-height: 150px;max-width: 150px;"></a>';
                  }
                  $info_box_contents[$cur_row][] = array('align' => 'center',
                    'params' => 'class="productListing-data" valign="top"',
                    'text' => $img);

                  ///////////////////////////////////////////////////////////////////////////////////////////////////////
                  // MOD begin of sub product
                  $pdesc	=	tep_db_fetch_array(tep_db_query("select products_description from products_description where products_id='".$products[$i]['id']."'"));
                  if ((int)$products_parent_id['products_parent_id'] != 0) {
                    $products_name = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_parent_id['products_parent_id']) . '"><b>' . $products[$i]['name'] . '</b></a><br>SKU:'.$products[$i]['model'].'<br><small>'.$pdesc['products_description'].'</small>';
                  } else {
                    $products_name = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><b>' . $products[$i]['name'] . '</b></a><br>SKU:'.$products[$i]['model'].'<br><small></small>';
                  }

                  if (STOCK_CHECK == 'true') {
                    $stock_check = tep_check_stock((int)$products[$i]['id'], $products[$i]['quantity']);
                    if (tep_not_null($stock_check)) {
                      $any_out_of_stock = 1;
                      $products_name .= $stock_check;
                    }
                  }
                  if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
                    reset($products[$i]['attributes']);
                    while (list($option, $value) = each($products[$i]['attributes'])) {
                      if ( !is_array($value) ) {
                        if ($products[$i][$option][$value]['options_values_price'] > 0 ){
                          $attribute_price = $products[$i][$option][$value]['price_prefix']  . $currencies->display_price($products[$i][$option][$value]['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']));
                        } else {
                          $attribute_price ='';
                        }
                        $products_name .= '<br>' . ' - ' . '<small><i>' . $products[$i][$option][$value]['products_options_name'] . ' : ' . $products[$i][$option][$value]['products_options_values_name'] . '&nbsp;&nbsp;&nbsp;' .$attribute_price . '</i></small>';
                      } else {
                        if ( isset($value['c']) ) {
                          foreach ( $value['c'] as $v ) {
                            if ($products[$i][$option][$v]['options_values_price'] > 0 ){
                              $attribute_price = $products[$i][$option][$v]['price_prefix']  . $currencies->display_price($products[$i][$option][$v]['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']));
                            } else {
                              $attribute_price ='';
                            }
                            $products_name .= '<br>' . ' - ' . '<small><i>' . $products[$i][$option][$v]['products_options_name'] . ' : ' . $products[$i][$option][$v]['products_options_values_name'] . '&nbsp;&nbsp;&nbsp;' .$attribute_price . '</i></small>';
                          }
                        } elseif ( isset($value['t']) ) {
                          if ($products[$i][$option]['t']['options_values_price'] > 0 ){
                            $attribute_price = $products[$i][$option]['t']['price_prefix']  . $currencies->display_price($products[$i][$option]['t']['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']));
                          } else {
                            $attribute_price ='';
                          }
                          $products_name .= '<br>' . ' - ' . '<small><i>' . $products[$i][$option]['t']['products_options_name'] . ' : ' . $value['t'] . '&nbsp;&nbsp;&nbsp;' . $attribute_price . '</i></small>';
                        }
                      }
                    }
                  }

                  /*$products_name .= '    </td>' .
                  '  </tr>' .
                  '</table>';*/

                  $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data"',
                    'text' => $products_name);

                  $info_box_contents[$cur_row][] = array('align' => 'center',
                    'params' => 'class="productListing-data" valign="top"',
                    'text' => tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4" maxlength="4" class="form-control"' ) . tep_draw_hidden_field('products_id[]', $products[$i]['id_string']));

                  $info_box_contents[$cur_row][] = array('align' => 'right',
                    'params' => 'class="productListing-data" valign="top"',
                    'text' => $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id'])));

                  $info_box_contents[$cur_row][] = array('align' => 'right',
                    'params' => 'class="productListing-data" valign="top"',
                    'text' => $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']));

                  $info_box_contents[$cur_row][] = array('align' => 'right',
                    'params' => 'class="productListing-data" valign="top"',
                    'text' => $products[$i]['id_string']);    
                }

                // new productListingBox($info_box_contents);
                // echo'<pre>'; print_r($info_box_contents);
              ?>
              <div class="table-responsive">
                <table class="table table-bordered cartable">
                <thead>
                  <tr>
                    <th width="20%" class="hide-below-480">Products</th>
                    <th width="44%">Product Name</th>
                    <th width="12%" class="hide-below-480">Price Per.</th>
                    <th width="12%">Qty</th>
                    <th width="12%">Total</th>
                  </tr>
                </thead>
                <?php  	  
                  //echo '<pre>'; print_r($info_box_contents);
                  for ($i=1; $i<count($info_box_contents);$i++) {
                    echo '<tr>
                    <td class="hide-below-480"><div class="img-placeholder imgmax"><a href="#">'.$info_box_contents[$i][0]['text'].'</a></div></td>
                    <td class="productListing-data">'.$info_box_contents[$i][1]['text'].'</td>
                    <td class="hide-below-480">'.$info_box_contents[$i][3]['text'].'</td>
                    <td><div class="qty-btngroup qty_mview">'.$info_box_contents[$i][2]['text']; ?>
                  <i class="fa fa-trash margin-left-5 cursor-pointer" onclick="removeFromCart('<?=$info_box_contents[$i][5]['text']?>')"></i></div>
                <?php
                  echo '</td> <td>'.$info_box_contents[$i][4]['text'].'</td></tr>';
                }
                // RCI code start
                $offset_amount = 0;
                $returned_rci = $cre_RCI->get('shoppingcart', 'offsettotal');      
                if (trim(strip_tags($returned_rci)) != NULL) {
                  echo $returned_rci;
                  echo '    <tr>' . "\n";
                  echo '      <td style="padding: 0px;" colspan="5"><div class="cart_total_price">' . SUB_TITLE_TOTAL . '<span>' . $currencies->format($cart->show_total() + $offset_amount) . '</span></div></td>' . "\n";
                  echo '    </tr>' . "\n";
                } else {    
                  echo '<tr>' . "\n";
                  echo '  <td style="padding: 0px;" colspan="5"><div class="cart_total_price">' . SUB_TITLE_TOTAL . '<span>' . $currencies->format($cart->show_total()) . '</span></div></td>' . "\n";
                  echo '</tr>' . "\n";
                }		          
              ?>          
              </table>
              <?php
                if ($any_out_of_stock == 1) {
                  if (STOCK_ALLOW_CHECKOUT == 'true') {
                    $valid_to_checkout = true;
                    echo '<p>'.OUT_OF_STOCK_CAN_CHECKOUT.'</p>';
                  } else {
                    $valid_to_checkout= false;
                    echo '<p>'.OUT_OF_STOCK_CANT_CHECKOUT.'</p>';
                  }
                }
                echo $cre_RCI->get('shoppingcart', 'insideformabovebuttons');	
              ?>
            </div>
            <div class=" margin-bottom-15">
              <div class="row three-buttons">
                <div class="col-xs-4 text-left"><?php echo '<button class="btn">Update</button>'; ?></div>
                <?php
                  if (RETURN_CART == 'L'){
                    $back = sizeof($navigation->path)-2;
                    if (isset($navigation->path[$back])) {
                      /***** Fix ********/
                      $link_vars_post = tep_array_to_string($navigation->path[$back]['post'], array('cart_quantity','id'));
                      $link_vars_get = tep_array_to_string($navigation->path[$back]['get'], array('action'));

                      $return_link_vars = '';
                      if($link_vars_get != '' && $link_vars_post !=''){
                        $return_link_vars = $link_vars_get . '&' . $link_vars_post;
                      } else if($link_vars_get != '' && $link_vars_post == ''){
                        $return_link_vars = $link_vars_get;
                      } else if($link_vars_get == '' && $link_vars_post != ''){
                        $return_link_vars = $link_vars_post;
                      }

                      $nav_link = '<a href="' . tep_href_link($navigation->path[$back]['page'], $return_link_vars, $navigation->path[$back]['mode']) . '" class="btn-success">' . IMAGE_BUTTON_CONTINUE_SHOPPING . '</a>';
                      //$nav_link = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_template_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
                      /***** fix end ****/
                    }
                  } else if ((RETURN_CART == 'C') || (isset($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'], 'wishlist'))){
                    if (!stristr($_SERVER['HTTP_REFERER'], 'wishlist')) {
                      $products = $cart->get_products();
                      $products = array_reverse($products);
                      $cat = tep_get_product_path($products[0]['id']) ;
                      $cat1= 'cPath=' . $cat;
                      if ($products == '') {
                        $back = sizeof($navigation->path)-2;
                        if (isset($navigation->path[$back])) {
                          $nav_link = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '" class="btn btn-success">' . IMAGE_BUTTON_CONTINUE_SHOPPING . '</a>';
                        }
                      } else {
                        $nav_link = '<a href="' . tep_href_link(FILENAME_DEFAULT, $cat1) . '" class="btn btn-success">' . IMAGE_BUTTON_CONTINUE_SHOPPING . '</a>'  ;
                      }
                    } else {
                      $nav_link = '<a href="' . tep_href_link(FILENAME_DEFAULT) . '" class="btn btn-success">' . IMAGE_BUTTON_CONTINUE_SHOPPING . '</a>'  ;
                    }
                  } else if (RETURN_CART == 'P') { 
                    $products = $cart->get_products();
                    $products = array_reverse($products);
                    if ($products == '') {
                      $back = sizeof($navigation->path)-2;
                      if (isset($navigation->path[$back])) {
                        $nav_link = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '" class="btn btn-success">' . IMAGE_BUTTON_CONTINUE_SHOPPING . '</a>';
                      }
                    } else {
                      $nav_link = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[0]['id']) . '" class="btn btn-success">' . IMAGE_BUTTON_CONTINUE_SHOPPING . '</a>';
                    }
                  }
                ?>                        
                <div class="col-xs-4 text-center"><?php echo $nav_link; ?></div>
                <div class="col-xs-4 text-right"><?php
                  if ($valid_to_checkout == true) {
                    echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="btn btn-danger btn_clr">' . IMAGE_BUTTON_CHECKOUT . '</a>';
                  }
                  ;?>
                </div>
              </div>
            </div>
            </form>
            <?php echo $cre_RCI->get('shoppingcart', 'insideformbelowbuttons'); ?>
            <?php
              //RCI start
              echo $cre_RCI->get('shoppingcart', 'logic');
              //RCI end

              // WebMakers.com Added: Shipping Estimator
              if ((SHIPPING_SKIP == 'No' || SHIPPING_SKIP == 'If Weight = 0') && $cart->weight > 0) {
                if (SHOW_SHIPPING_ESTIMATOR == 'true') {
                  include(DIR_WS_LANGUAGES . $language . '/modules/shipping_estimator.php');
                  // always show shipping table
                ?>
                <div class="table-responsive">
                  <div class="sideblocks">
                    <h1 class="col-lg-12 gry_box2 y_clr con_txt"><?php echo SHIPPING_OPTIONS; ?></h1>
                  </div>
                  <div class="clearfix"></div>
                  <table class="table">
                    <?php      
                      if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_SHIPPING_ESTIMATOR)) {
                        require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_SHIPPING_ESTIMATOR);
                      } else {
                        require(DIR_WS_MODULES . FILENAME_SHIPPING_ESTIMATOR);
                      }
                    ?>
                  </table>
                </div>
                <?php
                }
              }
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php } else { ?>   
    <div class="row-fluid">
      <div class="content span12">
        <div class="row-fluid accregister">
          <div class="new-users span12">
            <p>
              <?php 
                if (isset($_GET['hide_add_to_cart_error']) &&     (int)$_GET['hide_add_to_cart_error'] == 1) { echo TEXT_HIDE_ADD_TO_CART_ERROR; 	} else {
                  echo TEXT_CART_EMPTY; 
                }
                echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '" class="btn btn-success pull-right sign">' . IMAGE_BUTTON_CONTINUE . '</a>'; 
              ?>
            </p>
          </div>
        </div>
      </div>
    </div>          
    <?php  } ?>   
</div>
</div>
<?php 
  // RCI code start
  echo $cre_RCI->get('shoppingcart', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>