<?php
/*
  $Id: CCP_customers_listing.php, v 1.0.0.0 2008/02/01 maestro Exp $

  ContributionCentral, Custom CRE Loaded & osCommerce Programming
  http://www.contributioncentral.com
  Copyright (c) 2008 ContributionCentral

  Released under the GNU General Public License
*/

  global $cInfo;
  $rci .= '<br /><a href="' . tep_href_link(FILENAME_CHANGE_PASSWORD, 'selected_box=customers&customer=' . $cInfo->customers_id) . '">' . tep_image_button('button_change_password.png', IMAGE_CHANGE_PASSWORD) . '</a>';

?>