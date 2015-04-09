<?php
/*
  $Id: pages.php,v 2.0 2008/07/08 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

?>
<!-- pages_eof //-->
<div class="pages-infobox">
  <div class="box-header infoBoxHeading infoBoxHeadingCenter">
    <?php echo BOX_HEADING_PAGES; ?>
  </div>
  <div class="row margin-top-10 margin-bottom-10">
    <div class="col-lg-12">
      <div class="box-pages margin-10">          
        <?php
          require_once(DIR_WS_FUNCTIONS . FILENAME_CDS_FUNCTIONS);
          echo cre_get_responsive_box_string();                                 
        ?>
      </div>
    </div>
  </div>
</div>
<!-- pages_eof //-->