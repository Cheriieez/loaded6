<?php
/*
  $Id: ccp_functions.php, v1.0.0.0 2008/02/20 maestro

  ContributionCentral, Custom CRE Loaded & osCommerce Programming
  http://www.contributioncentral.com
  Copyright (c) 2008 ContributionCentral

  Released under the GNU General Public License
*/

//Change password contribution -- copied from catalog/includes/functions/general.php
if (!function_exists('tep_create_random_value')) {
  function tep_create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    while (strlen($rand_value) < $length) {
      if ($type == 'digits') {
        $char = tep_rand(0,9);
      } else {
        $char = chr(tep_rand(0,255));
      }
      if ($type == 'mixed') {
        if (preg_match('/^[a-z0-9]$/i', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (preg_match('/^[a-z]$/i', $char)) $rand_value .= $char;
      } elseif ($type == 'digits') {
        if (preg_match('/^[0-9]$/', $char)) $rand_value .= $char;
      }
    }
    return $rand_value;
  }
}

// Hide form elements
if (!function_exists('tep_hide_session_id')) {
  function tep_hide_session_id() {
    global $session_started, $SID;

    if (($session_started == true) && tep_not_null($SID)) {
      return tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
  }
}

?>