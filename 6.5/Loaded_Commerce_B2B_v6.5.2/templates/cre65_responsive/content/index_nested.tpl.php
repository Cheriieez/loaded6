<?php
/*
  $Id: index_nested.tpl.php,v 1.2.0.0 2008/01/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('indexnested', 'top');
  // RCI code eof
  // added for CDS CDpath support
  $params = (isset($_GET['CDpath'])) ? '&CDpath=' . $_GET['CDpath'] : ''; 
  // Get the category information
  $category_query = tep_db_query("select cd.categories_name, cd.categories_heading_title, cd.categories_description, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . $languages_id . "'");
  $category = tep_db_fetch_array($category_query);

  if(tep_not_null($category['categories_heading_title'])){
    $heading_text = $category['categories_heading_title'];
  } else if(tep_not_null($category['categories_name'])){
    $heading_text = $category['categories_name'];
  } else {
    $heading_text = HEADING_TITLE;
  }
?>
<!-- Bof content.index_nested.tpl.php-->
<h1 class="col-lg-12 gry_box2 y_clr con_txt"><?php echo $heading_text; ?></h1>
<div class="clearfix"></div>
<div><?php echo $category['categories_description']; ?></div>
<div class="row">
<?php
  if (isset($cPath) && strpos('_', $cPath)) {
    // check to see if there are deeper categories within the current category
    $category_links = array_reverse($cPath_array);
    for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
      $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
      $categories = tep_db_fetch_array($categories_query);
      if ($categories['total'] < 1) {
        // do nothing, go through the loop
      } else {
        $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
        break; // we've found the deepest category the customer is in
      }
    }
  } else {
    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
  }

  $number_of_categories = tep_db_num_rows($categories_query);

  $rows = 0;
  while ($categories = tep_db_fetch_array($categories_query)) {
    $rows++;
    $cPath_new = tep_get_path($categories['categories_id'] . $params);
    $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
    if(file_exists(DIR_WS_IMAGES . $categories['categories_image']) && $categories['categories_image']!= '')
    {
      $img	=	DIR_WS_IMAGES . $categories['categories_image'];
    }
    else
    {
      $img	=	'templates/store/images/csoon.jpg';
    }
    echo '                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '"><div class="m-c-image"><img  alt=" " title="" src="'.$img.'" class="img-responsive"></div><div class="pro_catetxt text-center">' . $categories['categories_name'] . '</div></a></div>' . "\n";
  }
  echo '</div>';
  // needed for the new products module shown below
  $new_products_category_id = $current_category_id;

  // RCI code start
  echo $cre_RCI->get('indexnested', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>
<!-- Eof content.index_nested.tpl.php-->
