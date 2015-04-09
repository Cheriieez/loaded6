<?php
/*
  $Id: cds_pages.php,v 1.2.0.0 2007/11/06 11:21:11 datazen Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('cdspages', 'top');
// RCI code eof
?>
<!-- cds_pages.tpl.php -->
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo ($heading_image != '') ? $heading_image : $heading_title; ?></h1>
<div class="clearfix"></div>
<div class="row">
  <div class="col-sm-12">
    <?php 
      $displayed = false;
      if (($listing_columns != 1) && (!isset($_GET['pID']))) {
        $product_insert = (isset($product_string) && $product_string != '') ? $product_string : '';
        if (strip_tags($product_insert . $product_string) != '') { 
		      echo $product_insert . $descr . $display_string; 
          $displayed = true;
        }
      }		  
    ?>
  </div>
  <div class="col-sm-12">
    <?php
      if (strip_tags($display_string) != '') {
        if ($listing_columns == 1) {          
          $product_insert = (isset($product_string) && $product_string != '') ? $product_string : '';
          if ((strip_tags($descr) != '') || ($product_insert != '')) {
	          echo '<td valign="top" width="40%" class="cds_category_description">'. $product_insert . $descr . '</td>';
          }
          echo '<td valign="top">'. $display_string . '</td>';
        } else {
          if (!$displayed) {
	          echo '<td valign="top" class="cds_category_description">'. $descr . $display_string .  '</td>';
          }
        }
    ?>
  </div>
  <div class="col-sm-12">
    <?php
      if (isset($acf_file) && $acf_file != '') {
	      @include_once($acf_file);
      }
    }
    ?>
  </div>
</div>
<?php
// RCI code start
echo $cre_RCI->get('global', 'bottom');
echo $cre_RCI->get('cdspages', 'bottom');
// RCI code eof
?>
<!-- cds_pages.tpl.php-eof -->