<?php
/*
  $Id: product_info.tpl.php,v 1.2.0.0 2008/01/22 13:41:11 datazen Exp $
  
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com
  
  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  
  Released under the GNU General Public License
*/
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('productinfotabs', 'top');
  // RCI code eof
  
  $product_subproducts_check = tep_has_product_subproducts((int)$_GET['products_id']);
  echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action', 'products_id', 'id')) . 'action=add_product' . '&' . $params), 'post', 'enctype="multipart/form-data" id="myForm" onsubmit="return func_chk_subproducts('.(($product_subproducts_check)?1:0).');"'); 
  $product_info_query = tep_db_query("select p.products_id, 
                                             pd.products_name, 
                                             pd.products_description, 
                                             p.products_model, 
                                             p.products_quantity, 
                                             p.products_image, 
                                             p.products_image_med, 
                                             p.products_image_lrg, 
                                             p.products_image_sm_1, 
                                             p.products_image_xl_1, 
                                             p.products_image_sm_2, 
                                             p.products_image_xl_2, 
                                             p.products_image_sm_3, 
                                             p.products_image_xl_3, 
                                             p.products_image_sm_4, 
                                             p.products_image_xl_4, 
                                             p.products_image_sm_5, 
                                             p.products_image_xl_5, 
                                             p.products_image_sm_6, 
                                             p.products_image_xl_6, 
                                             pd.products_url, 
                                             p.products_price, 
                                             p.products_tax_class_id, 
                                             p.products_date_added, 
                                             p.products_date_available, 
                                             p.manufacturers_id 
                                             from " . TABLE_PRODUCTS . " p, 
                                             " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                             where p.products_status = '1' 
                                             and p.products_id = '" . (int)$_GET['products_id'] . "' 
                                             and pd.products_id = p.products_id 
                                             and pd.language_id = '" . (int)$languages_id . "'");
  $product_info = tep_db_fetch_array($product_info_query);
  
  tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$product_info['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
  $products_name = '<h1 class="pageHeading">' . $product_info['products_name'] . '</h1>';
  if ($product_has_sub > '0'){ // if product has sub products
    $products_price ='';// if you like to show some thing in place of price add here
  } else {
    $pf->loadProduct($product_info['products_id'],$languages_id);
    $products_price = $pf->getPriceStringShort();
  }
?>
<div class="col-lg-12 prod_wrapper">
  <div class="row">
    <div class="col-sm-4">
      <div class="product-big-img">
        <a href="#myModal" id="img_show">
          <?php 
            if (tep_not_null($product_info['products_image']) || tep_not_null($product_info['products_image_med'])) {
              // BOF MaxiDVD: Modified For Ultimate Images Pack!
              if ($product_info['products_image_med'] != '') {
                $new_image = $product_info['products_image_med'];
                $image_width = MEDIUM_IMAGE_WIDTH;
                $image_height = MEDIUM_IMAGE_HEIGHT;
              } else {
                $new_image = $product_info['products_image'];
                $image_width = SMALL_IMAGE_WIDTH;
                $image_height = SMALL_IMAGE_HEIGHT;
              }
            }
          ?>
          <img src="<?php echo DIR_WS_IMAGES.$new_image; ?>" id="get_img" class="img-responsive"><span class="enlarge"></span>
        </a>   
      </div>

      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <p><img alt="" src="<?=DIR_WS_IMAGES.$product_info['products_image_lrg']?>" id="get_img_show" class="img-responsive"></p>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
      
      <div id="productcarousel" class="carousel slide">
        <div class="carousel-inner">
          <div class="item active">
            <div class="row">
              <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"><a href="#" class="thumbnail" onClick="return sel_img('<?=DIR_WS_IMAGES.$product_info['products_image_lrg']?>')"><img src="<?=DIR_WS_IMAGES.$product_info['products_image']?>" alt="" class="img-responsive"></a></div>
              <?php if($product_info['products_image_sm_1'] != ''){ ?>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"><a href="#" class="thumbnail"  onclick="return sel_img('images/<?=$product_info['products_image_sm_1']?>')"><img src="images/<?=$product_info['products_image_sm_1']?>" alt="" class="img-responsive"></a></div>
                <?php } ?>
              <?php if($product_info['products_image_sm_2'] != ''){ ?>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"><a href="#" class="thumbnail"  onclick="return sel_img('images/<?=$product_info['products_image_sm_2']?>')"><img src="images/<?=$product_info['products_image_sm_2']?>" alt="" class="img-responsive"></a></div>
                <?php } ?>
              <?php if($product_info['products_image_sm_3'] != ''){ ?>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"><a href="#" class="thumbnail"  onclick="return sel_img('images/<?=$product_info['products_image_sm_3']?>')"><img src="images/<?=$product_info['products_image_sm_3']?>" alt="" class="img-responsive"></a></div>
                <?php } ?>
            </div><!--/row-->
          </div><!--/item-->
          <?php if($product_info['products_image_sm_4'] != '' || $product_info['products_image_sm_5'] != '' || $product_info['products_image_sm_6'] != ''){ ?>
            <div class="item">
              <div class="row">
                <?php if($product_info['products_image_sm_4'] != ''){ ?>
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"><a href="#" class="thumbnail"  onclick="return sel_img('images/<?=$product_info['products_image_sm_4']?>')"><img src="images/<?=$product_info['products_image_sm_4']?>" alt="" class="img-responsive"></a></div>
                  <?php } ?>
                <?php if($product_info['products_image_sm_5'] != ''){ ?>
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"><a href="#" class="thumbnail"  onclick="return sel_img('images/<?=$product_info['products_image_sm_5']?>')"><img src="images/<?=$product_info['products_image_sm_5']?>" alt="" class="img-responsive"></a></div>
                  <?php } ?>
                <?php if($product_info['products_image_sm_6'] != ''){ ?>
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"><a href="#" class="thumbnail"  onclick="return sel_img('images/<?=$product_info['products_image_sm_6']?>')"><img src="images/<?=$product_info['products_image_sm_6']?>" alt="" class="img-responsive"></a></div>
                  <?php } ?>
              </div>
            </div>
            <?php } ?>
        </div>                    
        <?php if($product_info['products_image_sm_4'] != '' || $product_info['products_image_sm_5'] != '' || $product_info['products_image_sm_6'] != ''){ ?>
          <a class="left carousel-control" data-slide="prev" href="#productcarousel">
            <i class="fa fa-angle-left"></i>
          </a>
          <a class="right carousel-control" data-slide="next" href="#productcarousel">
            <i class="fa fa-angle-right"></i>
          </a>
          <?php } ?>
      </div>  
      <?php if($product_info['products_image_sm_1'] != '' || $product_info['products_image_sm_2'] != '' || $product_info['products_image_sm_3'] != '' || $product_info['products_image_sm_4'] != ''){} ?>
    </div>
    <div class="col-sm-8 bigrig_pro">
      <h1 class="col-sm-12 gry_box2 y_clr con_txt"><?=$product_info['products_name']?></h1>
      <h2 class="col-sm-12 bigrig_pro_price"><?=$products_price?></h2>
      <?php 
        $attritubecheck  =  tep_db_query("SELECT * FROM `products_attributes` pt, products_options po WHERE pt.options_id=po.products_options_id and pt.products_id=".$_GET['products_id']);
        $attributecount  =  tep_db_num_rows($attritubecheck);
        if ($attributecount>0) {
        ?>
        <div class="col-sm-12">
          <?php $bannertextres  =  tep_db_fetch_array(tep_db_query("select banners_html_text,banners_title,banners_url from banners where banners_id = '16'"));
          ?>
          <?=$bannertextres['banners_html_text']?>
          <div class="clearfix"></div>
          <hr>   
        </div> 
        <div class="clearfix"></div>
        <?php
          $pattr_qty  =  tep_db_query("select pa.products_id, pa.options_id, po.options_type, pot.products_options_name from products_attributes pa, products_options po, products_options_text pot where po.products_options_id=pa.options_id and po.products_options_id=pot.products_options_text_id and  po.options_type in (0,2) and pa.products_id='".$_GET['products_id']."' group by pa.options_id");
          $j=0;
          while($pattres  =  tep_db_fetch_array($pattr_qty)) {
            echo'<input type="hidden" name="option'.$j.'" value="'.$pattres['options_id'].'">';
            echo'<input type="hidden" name="opname'.$j.'" value="'.$pattres['products_options_name'].'">';
            echo'<input type="hidden" name="optype'.$j.'" value="'.$pattres['options_type'].'">';
            $j++;
          }
          
          $products_id_tmp = $product_info['products_id'];
          if (tep_subproducts_parent($products_id_tmp)) {
            $products_id_query = tep_subproducts_parent($products_id_tmp);
          } else {
            $products_id_query = $products_id_tmp;
          }
          if ($product_has_sub > '0') {
            if ((defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'False')) { 
              // 2.a) PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES = False 
              //        -- Show attributes to main product only
              $load_attributes_for = $products_id_query;
              //$load_attributes_for = $products_id_query;
              include(DIR_WS_MODULES . 'product_info/product_attributes.php');
            } else {
              // 2.b) PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES = True
              //        -- Show attributes to sub product only
            }
          } else {
            // show attributes for parent only
            $load_attributes_for = $products_id_query;
            include(DIR_WS_MODULES . 'product_info/product_attributes.php');
          }
        } else {
          echo'<input type="hidden" name="option0" value="null">';
          echo'<input type="hidden" name="opname0" value="null">';
          echo'<input type="hidden" name="optype0" value="null">';
        }
      ?>  
      <script>
        $(document).ready(function() {
            $(document).on("click",".radiocheck",function(){
              var err      =  false;
              //alert(err);

              var opt1    =  $("input[name='option0']").val();
              var question1  =  'Please select '+$("input[name='opname0']").val();
              var type1    =  $("input[name='optype0']").val();
              var alertmsg  =  '';
              if (opt1 !== null && opt1 !== undefined)
              {
                var attr1  =  'id['+opt1+']';
                if(type1==2)
                {
                  if($("input:radio[name='"+attr1+"']:checked").length ==0) 
                  {
                    alertmsg  +=  question1+"\n";
                    var err  =  true;
                  }
                }
                else if(type1==0)
                {
                  if($("select[name='"+attr1+"']").val() == '') 
                  {
                    alertmsg  +=  question1+"\n";
                    var err  =  true;
                  }
                }
              }
              var opt2    =  $("input[name='option1']").val();
              var question2  =  'Please select '+$("input[name='opname1']").val();
              var type2    =  $("input[name='optype1']").val();
              if (opt2 !== null && opt2 !== undefined)
              {
                var attr2  =  'id['+opt2+']';
                if(type2==2)
                {
                  if($("input:radio[name='"+attr2+"']:checked").length ==0) 
                  {
                    alertmsg  +=  question2+"\n";
                    var err  =  true;
                  }
                }
                else if(type2==0)
                {
                  if($("select[name='"+attr2+"']").val() == '') 
                  {
                    alertmsg  +=  question2+"\n";
                    var err  =  true;
                  }
                }
              }
              var opt3    =  $("input[name='option2']").val();
              var question3  =  'Please select '+$("input[name='opname2']").val();
              var type3    =  $("input[name='optype2']").val();
              if (opt3 !== null && opt3 !== undefined)
              {
                var attr3  =  'id['+opt3+']';
                if(type3==2)
                {
                  if($("input:radio[name='"+attr3+"']:checked").length ==0) 
                  {
                    alertmsg  +=  question3+"\n";
                    var err  =  true;
                  }
                }
                else if(type3==0)
                {
                  if($("select[name='"+attr3+"']").val() == '') 
                  {
                    alertmsg  +=  question3+"\n";
                    var err  =  true;
                  }
                }
              }
              var opt4    =  $("input[name='option3']").val();
              var question4  =  'Please select '+$("input[name='opname3']").val();
              var type4    =  $("input[name='optype3']").val();
              if (opt4 !== null && opt4 !== undefined)
              {
                var attr4  =  'id['+opt4+']';
                if(type4==2)
                {
                  if($("input:radio[name='"+attr4+"']:checked").length ==0) 
                  {
                    alertmsg  +=  question4+"\n";
                    var err  =  true;
                  }
                }
                else if(type4==0)
                {
                  if($("select[name='"+attr4+"']").val() == '') 
                  {
                    alertmsg  +=  question4+"\n";
                    var err  =  true;
                  }
                }
              }
              var opt5    =  $("input[name='option4']").val();
              var question5  =  'Please select '+$("input[name='opname4']").val();
              var type5    =  $("input[name='optype4']").val();
              if (opt5 !== null && opt5 !== undefined)
              {
                var attr5  =  'id['+opt5+']';
                if(type5==2)
                {
                  if($("input:radio[name='"+attr5+"']:checked").length ==0) 
                  {
                    alertmsg  +=  question5+"\n";
                    var err  =  true;
                  }
                }
                else if(type5==0)
                {
                  if($("select[name='"+attr5+"']").val() == '') 
                  {
                    alertmsg  +=  question5+"\n";
                    var err  =  true;
                  }
                }
              }
              var opt6    =  $("input[name='option5']").val();
              var question6  =  'Please select '+$("input[name='opname5']").val();
              var type6    =  $("input[name='optype5']").val();
              if (opt6 !== null && opt6 !== undefined)
              {
                var attr6  =  'id['+opt6+']';
                if(type6==2)
                {
                  if($("input:radio[name='"+attr6+"']:checked").length ==0) 
                  {
                    alertmsg  +=  question6+"\n";
                    var err  =  true;
                  }
                }
                else if(type6==0)
                {
                  if($("select[name='"+attr6+"']").val() == '') 
                  {
                    alertmsg  +=  question6+"\n";
                    var err  =  true;
                  }
                }
              }
              var opt7    =  $("input[name='option6']").val();
              var question7  =  'Please select '+$("input[name='opname6']").val();
              var type7    =  $("input[name='optype6']").val();
              if (opt7 !== null && opt7 !== undefined)
              {
                var attr7  =  'id['+opt7+']';
                if(type7==2)
                {
                  if($("input:radio[name='"+attr7+"']:checked").length ==0) 
                  {
                    alertmsg  +=  question7+"\n";
                    var err  =  true;
                  }
                }
                else if(type7==0)
                {
                  if($("select[name='"+attr7+"']").val() == '') 
                  {
                    alertmsg  +=  question7+"\n";
                    var err  =  true;
                  }
                }
              }
              if(err==false)
              {
                $('form#myForm').submit();
              }
              else
              {
                alert(alertmsg);
              }
            });
        });
      </script>

      <div class="clearfix"></div>
      <!-- sub products -->
      <hr>
      <?php
        if (STOCK_ALLOW_CHECKOUT =='false') {
          $allowcriteria = "";
        }
        // get sort order
        $csort_order = tep_db_fetch_array(tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'SUB_PRODUCTS_SORT_ORDER'"));
        $select_order_by = '';
        switch (strtoupper($csort_order['configuration_value'])) {
          case 'MODEL':
            $select_order_by .= 'sp.products_model';
            break;
          case 'NAME':
            $select_order_by .= 'spd.products_name';
            break;
          case 'PRICE':
            $select_order_by .= 'sp.products_price';
            break;
          case 'QUANTITY':
            $select_order_by .= 'sp.products_quantity';
            break;
          case 'WEIGHT':
            $select_order_by .= 'sp.products_weight';
            break;
          case 'SORT ORDER':
            $select_order_by .= 'sp.sort_order';
            break;
          case 'LAST ADDED':
            $select_order_by .= 'sp.products_date_added';
            break;
          default:
            $select_order_by .= 'sp.products_model';
            break;
        }
        $valid_to_checkout = true;
        if (STOCK_CHECK == 'true') {
          $stock_check = tep_check_stock((int)$_GET['products_id'], 1);
          if (tep_not_null($stock_check) && (STOCK_ALLOW_CHECKOUT == 'false')) {
            $valid_to_checkout = false;
          }
        }
        $hide_add_to_cart = hide_add_to_cart();
        $sub_products_query = tep_db_query("select sp.products_id, sp.products_quantity, sp.products_price, sp.products_tax_class_id, sp.products_image, spd.products_name, spd.products_blurb, sp.products_model from " . TABLE_PRODUCTS . " sp, " . TABLE_PRODUCTS_DESCRIPTION . " spd where sp.products_quantity > 0 and sp.products_parent_id = " . (int)$product_info['products_id'] . " and spd.products_id = sp.products_id and spd.language_id = " . (int)$languages_id . " order by " . $select_order_by);
        if ( tep_db_num_rows($sub_products_query) > 0 ) {
          if (defined('PRODUCT_INFO_SUB_PRODUCT_DISPLAY') && PRODUCT_INFO_SUB_PRODUCT_DISPLAY == 'In Listing') {
            include(DIR_WS_MODULES . 'product_info/sub_products_listing.php');
          } else if ( PRODUCT_INFO_SUB_PRODUCT_DISPLAY == 'Drop Down'){
            include(DIR_WS_MODULES . 'product_info/sub_products_dropdown.php');
          }
          if ((PRODUCT_INFO_SUB_PRODUCT_PURCHASE == 'Multi') || (PRODUCT_INFO_SUB_PRODUCT_PURCHASE == 'Single' && PRODUCT_INFO_SUB_PRODUCT_DISPLAY == 'Drop Down') || ($product_has_sub == 0)){
            if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
              if ($valid_to_checkout == true) {
                echo '<button class="btn addcart onlybutton btn-lg radiocheck pull-right mtop15 mbot15" type="button" '.$onlybut.'><i class="fa fa-shopping-cart fa-2x"></i>Add to Cart</button>';
              }
            }
          }
        }
        // sub product_eof
      ?>
      <script>
        $(document).ready(function(){
          $('.check_subcls').click (function ()
            {
              if($(this).is (':checked'))
              {
                var qtyval  =  $("#v").val();
                var pid = $(this).attr('rel');
                $("#sub_products_qty_"+pid).val(qtyval);
              }
              else
              {
                var pid = $(this).attr('rel');
                $("#sub_products_qty_"+pid).val(0);
              }
          });
          $(document).on("change","#v",function(){
            var qtyval  =  $("#v").val();
            $("input[name='sub_products_qty[]']").val(qtyval);

            $(".sub_p_cls").each(function(){
              var pid =   $(this).attr("value");
              var qid  =  'sub_products_qty_'+pid
              var cid  =  'sub_add_checksub_products_qty_'+pid
              var isChecked = $('#chkSelect').is(':checked');
              if($("#sub_add_checksub_products_qty_"+pid).is(':checked'))
              {

              }
              else
              {
                $("#sub_products_qty_"+pid).val(0);
              }
            });        
          });
          $(document).on('click','img.minus',function()
            {

              var qtyval  =  parseInt($("#v").val())-1;
              if(qtyval<0)
              {
                qtyval=0;
              }
              //$("#v").val(qtyval);
              $("input[name='sub_products_qty[]']").val(qtyval);

              $(".sub_p_cls").each(function(){
                var pid =   $(this).attr("value");
                var qid  =  'sub_products_qty_'+pid
                var cid  =  'sub_add_checksub_products_qty_'+pid
                var isChecked = $('#chkSelect').is(':checked');
                if($("#sub_add_checksub_products_qty_"+pid).is(':checked'))
                {

                }
                else
                {
                  $("#sub_products_qty_"+pid).val(0);
                }
              });    
          });

          $(document).on('click','img.plus',function()
            {
              var qtyval  =  parseInt($("#v").val())+1;
              $("input[name='sub_products_qty[]']").val(qtyval);      

              $(".sub_p_cls").each(function(){
                var pid =   $(this).attr("value");
                var qid  =  'sub_products_qty_'+pid
                var cid  =  'sub_add_checksub_products_qty_'+pid
                var isChecked = $('#chkSelect').is(':checked');
                if($("#sub_add_checksub_products_qty_"+pid).is(':checked'))
                {

                }
                else
                {
                  $("#sub_products_qty_"+pid).val(0);
                }
              });
          });    
        });
      </script>
      <?php
        if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
          echo tep_draw_hidden_field('products_id', $product_info['products_id']);
          if ($valid_to_checkout == true && !$product_subproducts_check) {
      ?>
      <!-- sub products eof -->                               
      <div class="col-sm-12">
        <div class="col-xs-12 col-sm-6 pull-left">
          <h3 class="quant_title">Qty</h3>
          <div class="quant">
            <img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>minus.gif" class="minus" rel="-1">
            <input type="text" size="10" id="v" name="cart_quantity" value="1" class="input-text qty text qty-input">                    
            <img src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>plus.gif" class="plus" rel="-1">
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 text-right info-model"><?=$product_info['products_model']?></div>
      </div><!--form-group--> 
      <div class="clearfix"></div>
      <hr>   
      <div class="col-sm-12 margin-bottom-20">
        <?php
              echo '<button class="btn addcart onlybutton btn-lg radiocheck" type="button" '.$onlybut.'><i class="fa fa-shopping-cart fa-2x"></i>Add to Cart</button>';
              
              if ($product_check['total'] > 0) {
                if (DESIGN_BUTTON_WISHLIST == 'true') {
                  echo '<script type="text/javascript"><!--' . "\n";
                  echo 'function addwishlist() {' . "\n";
                  echo 'document.cart_quantity.action=\'' . str_replace('&amp;', '&', tep_href_link(FILENAME_PRODUCT_INFO, 'action=add_wishlist' . '&' . $params)) . '\';' . "\n";
                  echo 'document.cart_quantity.submit();' . "\n";
                  echo '}' . "\n";
                  echo '--></script>' . "\n";
                  echo '<a href="javascript:addwishlist()" class="pro_wishlist"><i class="fa fa-heart pro_ficon fa-2x"></i><span class="wish-text">Add to Wishlist</span></a>';
                }
              }
            }
          }
        ?>
      </div>
    </div>
  </div>
  <div class="row margin-bottom-20">
    <div class="col-lg-12 pro_tabs">
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab_description"><h2><?php echo TEXT_TAB_PRODUCT_INFO;?></h2></a></li>
        <?php
          if (defined('MODULE_ADDONS_TABS_STATUS') && MODULE_ADDONS_TABS_STATUS == 'True') {
            $product_tabs_query = tep_db_query("select products_tab_2, products_tab_3, products_tab_4 from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_info['products_id'] . "'");
            $product_tab = tep_db_fetch_array($product_tabs_query);
            if (tep_not_null($product_tab['products_tab_2'])) {
        ?>
        <li><a data-toggle="tab" href="#tab_2"><h2><?php echo tep_not_null(TEXT_PRODUCTS_TAB_2_TITLE ) ? TEXT_PRODUCTS_TAB_2_TITLE : ' &nbsp; ';?></h2></a></li>
        <?php
            }
            if (tep_not_null($product_tab['products_tab_3'])) {
        ?>
        <li><a data-toggle="tab" href="#tab_3"><h2><?php echo tep_not_null(TEXT_PRODUCTS_TAB_3_TITLE ) ? TEXT_PRODUCTS_TAB_3_TITLE : ' &nbsp; ';?></h2></a></li>
        <?php
            }
            if (tep_not_null($product_tab['products_tab_4'])) {
        ?>
        <li><a data-toggle="tab" href="#tab_4"><h2><?php echo tep_not_null(TEXT_PRODUCTS_TAB_4_TITLE ) ? TEXT_PRODUCTS_TAB_4_TITLE : ' &nbsp; ';?></h2></a></li>
        <?php
            }
          }
          // Extra Products Fields are checked and presented
          // We need this instead of module, module can't hide tab if no records :-( 
          $extra_fields_query = tep_db_query("SELECT pef.products_extra_fields_status as status, 
                                                     pef.products_extra_fields_name as name, 
                                                     ptf.products_extra_fields_value as value 
                                                FROM ". TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS ." ptf, 
                                                     ". TABLE_PRODUCTS_EXTRA_FIELDS ." pef 
                                               WHERE ptf.products_id='".(int)$product_info['products_id']."' 
                                                 and ptf.products_extra_fields_value <> '' 
                                                 and ptf.products_extra_fields_id = pef.products_extra_fields_id 
                                                 and (pef.languages_id='0' or pef.languages_id='".$languages_id."') 
                                            ORDER BY products_extra_fields_order");
          if (tep_db_num_rows($extra_fields_query) > 0) {
        ?>
        <li><a data-toggle="tab" href="#tab_extra_fields"><h2><?php echo TEXT_TAB_PRODUCT_EXTRA_FIELDS;?></h2></a></li>
        <?php 
          }
          $product_manufacturer_query = tep_db_query("select m.manufacturers_id, 
                                                             m.manufacturers_name, 
                                                             m.manufacturers_image, 
                                                             mi.manufacturers_url 
                                                        from " . TABLE_MANUFACTURERS . " m 
                                                   left join " . TABLE_MANUFACTURERS_INFO . " mi 
                                                          on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), 
                                                             " . TABLE_PRODUCTS . " p 
                                                       where p.products_id = '" . (int)$product_info['products_id'] . "' 
                                                         and p.manufacturers_id = m.manufacturers_id");
          if (tep_db_num_rows($product_manufacturer_query)) { 
        ?>
        <li><a data-toggle="tab" href="#tab_manufacturers"><h2><?php echo TEXT_TAB_PRODUCT_MANUFACTURER;?></h2></a></li>
        <?php
          }
          $reviews_query = tep_db_query("select r.reviews_id, 
                                                rd.reviews_text as reviews_text, 
                                                r.reviews_rating, 
                                                r.date_added, 
                                                p.products_id, 
                                                pd.products_name, 
                                                p.products_image, 
                                                r.customers_name 
                                           from " . TABLE_REVIEWS . " r, 
                                                " . TABLE_REVIEWS_DESCRIPTION . " rd, 
                                                " . TABLE_PRODUCTS . " p, 
                                                " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                          where p.products_status = '1' 
                                            and p.products_id = '" . (int)$_GET['products_id'] . "' 
                                            and p.products_id = r.products_id 
                                            and r.reviews_id = rd.reviews_id 
                                            and p.products_id = pd.products_id 
                                            and pd.language_id = '" . (int)$languages_id . "' 
                                            and rd.languages_id = '" . (int)$languages_id . "' 
                                       order by rand(), r.reviews_id DESC 
                                          limit " . PRODUCT_INFO_TAB_NUM_REVIEWS);
          if (tep_db_num_rows($reviews_query) > 0) { 
            include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_REVIEWS);
        ?>
        <li><a data-toggle="tab" href="#tab_reviews"><h2><?php echo TEXT_TAB_PRODUCT_REVIEWS;?></h2></a></li>
        <?php
          }
        ?>
        <li><a data-toggle="tab" href="#tab_extra_info"><h2><?php echo TEXT_TAB_PRODUCT_EXTRA_INFO;?></h2></a></li>
      </ul>
      <div class="tab-content padding-bottom-15 padding-top-15">
        <div id="tab_description" class="tab-pane fade in active">
          <div class="col-md-12">
            <?php echo cre_clean_product_description($product_info['products_description']); ?>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php
          if (defined('MODULE_ADDONS_TABS_STATUS') && MODULE_ADDONS_TABS_STATUS == 'True') {
            if (tep_not_null($product_tab['products_tab_2'])) {
        ?>
        <div id="tab_2" class="tab-pane fade">
          <div class="col-md-12">
            <?php echo cre_clean_product_description($product_tab['products_tab_2']); ?>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php
            }
            if (tep_not_null($product_tab['products_tab_3'])) {
        ?>
        <div id="tab_3" class="tab-pane fade">
          <div class="col-md-12">
            <?php echo cre_clean_product_description($product_tab['products_tab_3']); ?>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php
            }
            if (tep_not_null($product_tab['products_tab_4'])) {
        ?>
        <div id="tab_4" class="tab-pane fade">
          <div class="col-md-12">
            <?php echo cre_clean_product_description($product_tab['products_tab_4']); ?>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php
            }
          }
          if (tep_db_num_rows($extra_fields_query) > 0) {
        ?>
        <div id="tab_extra_fields" class="tab-pane fade">
          <div class="col-md-12">
            Extra Fields
          </div>
          <div class="clearfix"></div>
        </div>
        <?php
          }
          if (tep_db_num_rows($product_manufacturer_query)) {
        ?>
        <div id="tab_manufacturers" class="tab-pane fade">
          <div class="col-md-12">
            <?php
              while ($manufacturer = tep_db_fetch_array($product_manufacturer_query)) {
                if (tep_not_null($manufacturer['manufacturers_image'])) 
                  echo tep_image(DIR_WS_IMAGES . $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name']) . '<br> <br>';
                echo '<strong>' . BOX_HEADING_MANUFACTURER_INFO . '</strong><br>';
                if (tep_not_null($manufacturer['manufacturers_url'])) {
                  echo '<span class="main">&bull; <a href="' . tep_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $manufacturer['manufacturers_id']) . '" target="_blank">' . sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer['manufacturers_name']) . '</a></span><br>';
                }
                echo '<span class="main">&bull; <a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '">' . BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . '</span><br>';
              }
            ?>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php
          }
          if (tep_db_num_rows($reviews_query) > 0) {
        ?>
        <div id="tab_reviews" class="tab-pane fade">
          <div class="col-md-12">
            <table cellpadding="0" cellspacing="0" border="0" style="width:100%">
            <?php
             while ($reviews = tep_db_fetch_array($reviews_query)) {
             ?>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main"><?php echo '<span class="smallText">' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</span>'; ?></td>
                      <td class="smallText" align="right"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added'])); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2" valign="top" class="main"><?php echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 60, '-<br>') . ((strlen($reviews['reviews_text']) >= 100) ? '..' : '') . '<br><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</i>'; ?></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
              <?php
              }
              ?>
              <tr>
                <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php  if(tep_db_num_rows($reviews_query) == PRODUCT_INFO_TAB_NUM_REVIEWS ) { echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params() . $params) . '">' . tep_template_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS) . '</a>'; } ?></td>
              </tr>
            </table>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php
          }
        ?>
        <div id="tab_extra_info" class="tab-pane fade">
          <div class="col-md-12">
            <?php
              if (tep_not_null($product_info['products_url'])) {
                echo '<span class="main">' . sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT,  'action=url&amp;goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)) . '</span><br>';
              } 
                   
              echo '<span class="main"><strong>' . BOX_HEADING_REVIEWS . '</strong></span><br>';
              $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$_GET['products_id'] . "'");
              $reviews = tep_db_fetch_array($reviews_query);
              if ($reviews['count'] > 0) {
                echo '<span class="main">' . TEXT_CURRENT_REVIEWS . ' ' . $reviews['count'];
                echo '<span class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . (int)$_GET['products_id']) . '">' . BOX_REVIEWS_READ_REVIEW . '</a></span><br>';
              } else {
                echo '<span class="main">' . BOX_REVIEWS_NO_REVIEWS . '</span><br>';
              }
              echo '<span class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . (int)$_GET['products_id']) . '">' . BOX_REVIEWS_WRITE_REVIEW .'</a></span><br>';
              echo '<br><br>';
              echo '<span class="main"><strong>' . TAB_EXTRA_INFORMATIONS . '</strong></span><br>';
              if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
                echo '<span class="main">' . sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])) . '</span>';
              } else {
                echo '<span class="main">' . sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])) . '</span>';
              }
              if (isset($_SESSION['customer_id'])) {
                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
                $check = tep_db_fetch_array($check_query);
                $notification_exists = (($check['count'] > 0) ? true : false);
              } else {
                $notification_exists = false;
              }
              if ($notification_exists == true) {
                echo '<br><span class="main"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY_REMOVE, tep_get_products_name((int)$_GET['products_id'])) .'</a></span><br>';
              } else {
                echo '<br><span class="main"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY, tep_get_products_name((int)$_GET['products_id'])) .'</a></span><br>';
              }
              echo '<span class="main"><a href="' . tep_href_link(FILENAME_TELL_A_FRIEND, 'products_id=' . (int)$_GET['products_id'], 'NONSSL') . '">' . BOX_TELL_A_FRIEND_TEXT . '</a></span><br>';
            ?>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>
<script>
  $(document).ready(function(){
    $(document).on("click",".qr_submit",function(){
      var crname      =  $(".qr_name").val();
      var crsubject    =  $(".qr_subject").val();
      var cremail      =  $(".qr_email").val();
      var crphone      =  $(".qr_phone").val();
      var crmessage    =  $(".qr_message").val();
      if(cremail != '' && cremail != null)
      {
        var param  =  'name='+crname+'&subject='+crsubject+'&email='+cremail+'&phone='+crphone+'&message='+crmessage;
        $.ajax({url:"quote_request.php?"+param,success:function(result){
          $("#qr_result").html(result);
        }});  
      }
      else
      {
        alert("Please enter your Email Id");
      }
    })
    $("[bgcolor='#cccccc']").parent().remove();
  })
</script>
<?php
  // RCI code start
  //echo $cre_RCI->get('global', 'bottom');
?>