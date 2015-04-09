<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('wishlist', 'top');
// RCI code eof
?>
<script type="text/javascript">
  function validateEmail(){	
    var inputvalue = document.tell_a_friend.send_to.value;   
    var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;    
    if(pattern.test(inputvalue)){         
      return true;   
    }else{  
      alert("Please enter valid email address");
      return false; 
    }
  }
</script>
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADING_TITLE; ?></h1>
<div class="clearfix"></div>
<?php
$wishlist_sql = "select * from " . TABLE_WISHLIST . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id > 0 order by products_name";
$wishlist_split = new splitPageResults($wishlist_sql, MAX_DISPLAY_WISHLIST_PRODUCTS);
$wishlist_query = tep_db_query($wishlist_split->sql_query);

$info_box_contents = array();
if (tep_db_num_rows($wishlist_query)) {
  $product_ids = '';
  while ($wishlist = tep_db_fetch_array($wishlist_query)) {
	$product_ids .= $wishlist['products_id'] . ',';
  }
  $product_ids = substr($product_ids, 0, -1);

  $products_query = tep_db_query("select pd.products_id, pd.products_name, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, p.products_parent_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id in (" . $product_ids . ") and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by products_name");
  //echo "select pd.products_id, pd.products_name, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id in (" . $product_ids . ") and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by products_name";
  echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_WISHLIST, tep_get_all_get_params(array('action')) . 'action=add_del_products_wishlist'));
  ?>
  <div>
                    <?php
                  $col = 0;
                  while ($products = tep_db_fetch_array($products_query)) {
                    $col++;
					if($products['products_image']=='' || !file_exists(DIR_WS_IMAGES.$products['products_image']))
					{
						if($products['products_parent_id']>0)	
						{
							$par_products_res = tep_db_fetch_array(tep_db_query("select products_image from products where products_id='".$products['products_parent_id']."'"));
							$image	=	$par_products_res['products_image'];
						}
					}
					else
					{
						$image	=	$products['products_image'];
					}
                    ?>
                    <div class="mbot15"><div class="col-sm-3 imgdiv"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($products['products_id']) . '&amp;products_id=' . $products['products_id'], 'NONSSL'); ?>"><img src="<?=DIR_WS_IMAGES.$image?>" /></a></div>
                    <div class="col-sm-6"><b><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($products['products_id']) . '&amp;products_id=' . $products['products_id'], 'NONSSL'); ?>"><?php echo $products['products_name']; ?></a></b><br><?php

                          $attributes_addon_price = 0;
                          $tmp_prodid = tep_subproducts_parent($products['products_id']);
						  $pf->loadProduct($products['products_id'],$languages_id);
						  $products_price = $pf->getPriceStringShort();

                          echo BOX_TEXT_PRICE . '&nbsp;' . $products_price. '<br>';
                          ?></div>
                    <div class="col-sm-3"><?php echo BOX_TEXT_DELETE; ?>&nbsp;<?php echo tep_draw_checkbox_field('del_wishprod[]',$products['products_id']); ?><br><font color="BD1415"><b><?php echo BOX_TEXT_MOVE_TO_CART ?></b></font>&nbsp;<?php echo  tep_draw_checkbox_field('add_wishprod[]',$products['products_id']); ?></div> <div class="clearfix"></div></div>

                    <?php
                  } //end while
                  ?>
                 
  </div>
  <?php if ( ($wishlist_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3') ) ) { ?>
  <!--<div class="col-sm-6"><?php //echo $wishlist_split->display_count(TEXT_DISPLAY_NUMBER_OF_WISHLIST); ?></div>-->
  <style>
  .wish_pagelist hr{ margin-bottom:0px;}
  .wish_pagelist .col-lg-12{ padding:0px;}
  .imgdiv img{ max-width:100%; padding:3px; border:1px solid #ccc;}
  </style>
  <div class="col-sm-12 wish_pagelist"><?php echo $wishlist_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div>
  <div class="clearfix"></div>
    <div class="col-sm-4"><a class="btn btn-primary" href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>"><?php echo tep_template_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING); ?></a></div>
  <div class="col-sm-4  text-center"><a class="btn btn-primary" href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>"><?php echo tep_template_image_button('button_view_cart.gif', IMAGE_BUTTON_VIEW_CART); ?></a></div>
  <div class="col-sm-4"><button class="btn btn-danger pull-right">Continue</button></div>
  </form>
  <div class="clearfix"></div>
<div class="panel panel-default mtop15"> 	
  <div class="panel-heading">
    <h3 class="panel-title"><?=BOX_HEADING_SEND_WISHLIST?></h3>
  </div>
  <div class="panel-body">
	<?php
		echo tep_draw_form('tell_a_friend', tep_href_link(FILENAME_WISHLIST_SEND, '', 'NONSSL', false), 'get','onSubmit="return validateEmail();"');
		echo tep_draw_input_field('send_to', '', 'size="20" class="form-control"') . '<br> <button class="btn btn-danger">Email your Wishlist to a friend</button>' . tep_draw_hidden_field('products_ids', isset($_GET['products_ids'])) . tep_hide_session_id();
		echo '</form>';
	
	?>
  </div>
</div>           
 </div>
 <?php
              }

            } else { // Nothing in the customers wishlist
            ?>             
<div class="col-sm-12"><?php echo BOX_TEXT_NO_ITEMS;?><br>
<a class="btn btn-primary pull-right" href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>"><?php echo tep_template_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING); ?></a></div>

<?php
}
// RCI code start
echo $cre_RCI->get('wishlist', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>