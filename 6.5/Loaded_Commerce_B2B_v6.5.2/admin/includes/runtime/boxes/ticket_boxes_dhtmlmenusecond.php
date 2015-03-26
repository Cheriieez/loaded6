<?php
/*
  $Id: ticket_boxes_dhtmlmenufirst.php,v 1.0.0.0 2008/02/20 maestro

  ContributionCentral, Custom CRE Loaded & osCommerce Programming
  http://www.contributioncentral.com
  Copyright (c) 2008 ContributionCentral

  Released under the GNU General Public License
*/
$is_651 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 5 && INSTALLED_PATCH == 1) ? true : false;
if (!$is_651) {
  if (defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') {
    $rci = "ticket, ticket.php, Customer Service";
  }
}
?>