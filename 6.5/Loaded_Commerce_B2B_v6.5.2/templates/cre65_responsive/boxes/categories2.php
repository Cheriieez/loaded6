<?php
/*
  $Id: categories2.php,v 1.2 2008/06/23 00:18:17 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (count(explode("_", $cPath)) > 1) {
  if (isset($_GET['products_id']) && $_GET['products_id'] != '' && !isset($_GET['cPath'])) {
    $cPath_arr = array_reverse(explode("_", tep_get_product_path((int)$_GET['products_id'])));
    $cID = $cPath_arr[0];
  } else {
    $cPath_arr = array_reverse(explode("_", (($cPath) ? $cPath : $_GET['cPath'])));
    $cID = $cPath_arr[0];
  }
} else {
  $cID = $cPath;
}
?>
<!-- categories2 //-->
<div class="categories2-infobox">
  <div class="box-header infoBoxHeading infoBoxHeadingCenter">
    <?php echo BOX_HEADING_CATEGORIES2; ?>
  </div>
  <div class="row margin-top-10 margin-bottom-10">
    <div class="col-lg-12">
      <ul class="box-categories2-selection">
        <li>
          <?php echo '<form action="' . tep_href_link(FILENAME_DEFAULT, $params) . '" method="get" name="categories2" class="form-inline no-margin-bottom" role="form">' . tep_hide_session_id(); ?>
            <?php echo tep_draw_pull_down_menu('cPath', tep_get_categories(array(array('id' => '', 'text' => PULL_DOWN_DEFAULT))), $cID, 'size="1" onchange="this.form.submit();" class="box-categories2-select form-control form-input-width" name="cPath"'); ?>
          <?php echo '</form>'; ?>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- categories2_eof //-->