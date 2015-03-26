<?php
/*
  $Id: CTM_functions.php,v 1.3 2007/09/03 23:39:53 maestro Exp $

  Contribution Central, Custom CRE Loaded Programming
  http://www.contributioncentral.com
  Copyright (c) 2007 Contribution Central

  Released under the GNU General Public License
*/

// Sets the status of a testimonial
  function tep_set_testimonials_status($testimonials_id, $status) {
    if ($status == '1') {
      return tep_db_query("update " . TABLE_CUSTOMER_TESTIMONIALS . " set status = '1' where testimonials_id = '" . $testimonials_id . "'");
    } elseif ($status == '0') {
      return tep_db_query("update " . TABLE_CUSTOMER_TESTIMONIALS . " set status = '0' where testimonials_id = '" . $testimonials_id . "'");
    } else {
      return -1;
    }
  }

?>