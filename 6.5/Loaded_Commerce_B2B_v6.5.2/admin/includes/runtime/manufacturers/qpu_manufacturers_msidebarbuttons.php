<?php
/*
  $Id: qpu_categories_csidebarbuttons.php, 2008/02/01 maestro

  ContributionCentral, Custom CRE Loaded & osCommerce Programming
  http://www.contributioncentral.com
  Copyright (c) 2008 ContributionCentral

  Released under the GNU General Public License
*/

  global $mInfo;
  isset($row_by_page) ? define('MAX_DISPLAY_ROW_BY_PAGE', $row_by_page) : $row_by_page = MAX_DISPLAY_SEARCH_RESULTS;
  define('MAX_DISPLAY_ROW_BY_PAGE', MAX_DISPLAY_SEARCH_RESULTS);
  $rci .= '<a href="' . tep_href_link(FILENAME_QUICK_UPDATES, 'row_by_page=' . $row_by_page . '&manufacturer=' . $mInfo->manufacturers_id) . '">' . tep_image_button('button_quick_update.png', IMAGE_MANUFACTURER_QUICK_UPDATE) . '</a>';

?>