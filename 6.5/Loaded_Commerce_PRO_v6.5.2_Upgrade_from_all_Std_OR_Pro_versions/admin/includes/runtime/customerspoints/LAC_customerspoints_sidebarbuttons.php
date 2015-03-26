<?php
/*
  $Id: LAC_customers_listing.php, v 1.0.0.0 2008/02/01 maestro Exp $

  ContributionCentral, Custom CRE Loaded & osCommerce Programming
  http://www.contributioncentral.com
  Copyright (c) 2008 ContributionCentral

  Released under the GNU General Public License
*/

  global $cInfo;
  $rci .= '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'cID=' . $cInfo->customers_id . '&action=login') . '" target="_blank" onclick="if ( event.stopPropagation ) event.stopPropagation(); else if ( window.event ) window.event.cancelBubble = true;">' . tep_image_button('button_login_as_customer.png', '<img src="images/key.png" width="10" height="10" />&nbsp;Login as Customer') . '</a>';

?>