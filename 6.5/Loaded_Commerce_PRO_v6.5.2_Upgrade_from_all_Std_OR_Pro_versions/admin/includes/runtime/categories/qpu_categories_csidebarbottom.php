<?php
/*
  $Id: qpu_categories_csidebarbuttons.php, 2008/02/01 maestro

  ContributionCentral, Custom CRE Loaded & osCommerce Programming
  http://www.contributioncentral.com
  Copyright (c) 2008 ContributionCentral

  Released under the GNU General Public License
*/
  global $cInfo;
  ($row_by_page) ? define('MAX_DISPLAY_ROW_BY_PAGE' , $row_by_page ) : $row_by_page = MAX_DISPLAY_SEARCH_RESULTS;
  define('MAX_DISPLAY_ROW_BY_PAGE' , MAX_DISPLAY_SEARCH_RESULTS );
  $rci .= '&nbsp;<a href="' . tep_href_link(FILENAME_QUICK_UPDATES, 'row_by_page=' . $row_by_page . '&cPath=' . $cInfo->categories_id) . '">' . tep_image_button('button_quick_update.png', IMAGE_CATEGORIES_QUICK_UPDATE) . '</a>&nbsp;';

?>