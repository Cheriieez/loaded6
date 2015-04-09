<?php
/*
  $Id: product_listing_col.php,v 1.1.1.1 2004/03/04 23:41:11 ccwjr Exp $
*/
  
  //declare variables and initialize
  // added for CDS CDpath support
  $params = (isset($_GET['CDpath'])) ? '&CDpath=' . $_GET['CDpath'] : ''; 
  $row = 0;
  $column = 0;
  $show	=	($_GET['show'] != '')?$_GET['show']:MAX_DISPLAY_SEARCH_RESULTS;
  $listing_split = new responsiveSplitPageResults($listing_sql, $show, 'p.products_id');
  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {

    if (PRODUCT_LIST_FILTER > 0) {
      if (isset($_GET['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name
                             from " . TABLE_PRODUCTS . " p, 
                                  " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, 
                                  " . TABLE_CATEGORIES . " c, 
                                  " . TABLE_CATEGORIES_DESCRIPTION . " cd 
                            where p2c.categories_id = c.categories_id 
                              and p.products_id = p2c.products_id 
                              and cd.categories_id = c.categories_id 
                              and cd.language_id = '" . (int)$languages_id . "' 
                              and p.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' 
                              and p.products_status = '1' 
                         order by cd.categories_name";
      } else {
        $filterlist_sql= "select distinct m.manufacturers_id as id, 
                                          m.manufacturers_name as name 
                                     from " . TABLE_PRODUCTS . " p, 
                                          " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, 
                                          " . TABLE_MANUFACTURERS . " m 
                                    where p.products_status = '1' 
                                      and p.manufacturers_id = m.manufacturers_id 
                                      and p.products_id = p2c.products_id 
                                      and p2c.categories_id = '" . (int)$current_category_id . "' 
                                 order by m.manufacturers_name";
      }
      $filterlist_query = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist_query) > 1) {
        /*echo '' . tep_draw_form('filter', FILENAME_DEFAULT, 'get', 'class="form-inline pull-right"') . TEXT_SHOW . '&nbsp;';
        if (isset($_GET['manufacturers_id'])) {
        echo tep_draw_hidden_field('manufacturers_id', (int)$_GET['manufacturers_id']);
        $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
        } else {
        echo tep_draw_hidden_field('cPath', $cPath);
        $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
        }
        echo tep_draw_hidden_field('sort', (isset($_GET['sort']) ? $_GET['sort'] : ''));
        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
        $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
        echo tep_draw_pull_down_menu('filter_id', $options, (isset($_GET['filter_id']) ? $_GET['filter_id'] : ''), 'onchange="this.form.submit()"');
        echo '</form>' . "\n";*/
      }
    }
  ?>
  <div class="cleafix"></div>
  <div class="col-lg-12">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 margin-top-10">
        <?php echo $listing_split->display_responsive_count(TEXT_DISPLAY_RESPONSIVE_NUMBER_OF_PRODUCTS); ?>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 text-right">
        <ul class="pagination margin-top-0 margin-bottom-0">
          <?php echo $listing_split->display_responsive_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
        </ul>
      </div>
    </div>
  </div>
  <?php
  }
  $list_box_contents = array();
  if ($listing_split->number_of_rows > 0) {
    $listing_query = tep_db_query($listing_split->sql_query);
    $row = 0;
    $column = 0;
    $no_of_listings = tep_db_num_rows($listing_query);
    while ($_listing = tep_db_fetch_array($listing_query)) {
      $listing[] = $_listing;
    }
    for ($x = 0; $x < $no_of_listings; $x++) {
      $product_contents = array();
      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';
        $lc_text = '';
        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing[$x]['products_model'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_NAME':
            $lc_align = '';
            if (isset($_GET['manufacturers_id'])) {
              $lc_text = '<p>' . $listing[$x]['products_name'] . cre_products_blurb($listing[$x]['products_id']).'</p>';
            } else {
              $lc_text = '<p><a href="'.tep_href_link('product_info.php','products_id='.$listing[$x]['products_id']).'">' . $listing[$x]['products_name'] . cre_products_blurb($listing[$x]['products_id']).'</a></p>';
            }
            if (tep_not_null(cre_products_blurb($listing[$x]['products_id']))) {
              $lc_text .= '<br><div class="blurbs">' . $listing[$x]['products_blurb'] . '</div>';
            } else {
              $lc_text .= '';
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = '';
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing[$x]['manufacturers_id'] . $params) . '">' . $listing[$x]['manufacturers_name'] . '</a>&nbsp;';
            break;
          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'right';
            $pf->loadProduct($listing[$x]['products_id'],$languages_id);
            $lc_text = $pf->getPriceStringShort();
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing[$x]['products_quantity'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing[$x]['products_weight'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            if (isset($_GET['manufacturers_id'])) {
              $lc_text = '<div class="prod-img"><img class="img-responsive" src="'.DIR_WS_IMAGES . $listing[$x]['products_image'].'"></div>';
            } else {
              $lc_text = '<div class="prod-img"><img class="img-responsive" src="'.DIR_WS_IMAGES . $listing[$x]['products_image'].'"></div>';
            }
            break;
          case 'PRODUCT_LIST_DATE_EXPECTED':
            $duedate= str_replace("00:00:00", "" , $listing[$x]['products_date_available']);
            $lc_align = 'center';
            $lc_text = '&nbsp;' .  $duedate . '&nbsp;';
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            $valid_to_checkout= true;
            if (STOCK_CHECK == 'true') {
              $stock_check = tep_check_stock((int)$listing[$x]['products_id'], 1);
              if (tep_not_null($stock_check) && (STOCK_ALLOW_CHECKOUT == 'false')) {
                $valid_to_checkout = false;
              }
            }

            if ($valid_to_checkout == true) {
              $hide_add_to_cart = hide_add_to_cart();
              $lc_text = '';
              if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
                $lc_align = 'center';
                $lc_text = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&amp;products_id=' . $listing[$x]['products_id'] . '&amp;cPath=' . tep_get_product_path($listing[$x]['products_id']) . $params) . '" class="addcart pull-right btn btn-success margin-top-5"><i class="fa fa-shopping-cart fa-1x margin-right-5"></i>Add to cart</a>';
              }
            }
            break;
        }
        $product_contents[] = $lc_text;
      }                
      $product_contents[4] = $listing[$x]['products_description'];
      $product_contents[5] = $listing[$x]['products_id'];
      $product_contents[6] = $listing[$x]['products_name'];
      $product_contents[7] = $listing[$x]['products_model'];
      $lc_text = $product_contents;
      $list_box_contents[] = $lc_text;
      $column++;
      if ($column >= COLUMN_COUNT) {
        $row++;
        $column = 0;
      }
    }
    echo '<div class="tab-content">
            <div id="sec_featured" class="tab-pane fade in active">
              <div class="col-lg-12 neg-margin-top-10">
                <div class="row">';
                foreach ($list_box_contents as $value) {
                  $desc1	=	strip_tags($value[4]);
                  $desc	=	substr($desc1, 0, 150);
                  $desc	=	($desc != '') ? $desc . '...' : '';	
                  $desc	=	'<a href="' . tep_href_link("product_info.php", "products_id=" . $value[5]) . '">' . $desc . '</a>';	
                  echo '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                          <div class="prod-box productListingHolder">
                            <div class="prod-img">
                              <a href="' . tep_href_link("product_info.php", "products_id=" . $value[5]) . '">
                                ' . $value[0] . '
                              </a>
                            </div>
                            <div class="prod-desc">
                              <div class="prod-box-name"><h4>' . $value[6] . '</h4></div>
                              <span class="price pull-left">' . $value[2] . '</span>
                              <p class="buy-now-listing">' . $value[3] . '</p>
                            </div>
                          </div>
                        </div>';
                }
    echo '      </div>
              </div>
            </div>
          </div>';
  } else {
    echo '<div class="tab-content">
            <div id="sec_featured" class="tab-pane fade in active">
              <div class="col-lg-12 neg-margin-top-10">
                <p class="margin-top-20">' . TEXT_NO_PRODUCTS . '</p>
              </div>
            </div>
          </div>';
  }
  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
  ?>
  <div class="cleafix"></div>
  <div class="col-lg-12">
    <div class="row margin-top-15 margin-bottom-10">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 margin-top-10">
        <?php echo $listing_split->display_responsive_count(TEXT_DISPLAY_RESPONSIVE_NUMBER_OF_PRODUCTS); ?>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 text-right">
        <ul class="pagination margin-top-0 margin-bottom-0">
          <?php echo $listing_split->display_responsive_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
        </ul>
      </div>
    </div>
  </div>
  <?php
  }
?>