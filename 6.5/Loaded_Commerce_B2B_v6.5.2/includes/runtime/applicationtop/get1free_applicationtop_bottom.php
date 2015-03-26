<?php
/*
  $Id: livesupport_applicationtop_bottom.php,v 1.0.0.0 2008/02/06 13:41:11 ezfrontstores Exp $

  CRELoaded osCommerce Templates
  http://www.creloadedoscommercetemplates.com

  Copyright (c) 2008 CRE Loaded osCommerce Templates
  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  if (defined('MODULE_ADDONS_GET1FREE_STATUS') && MODULE_ADDONS_GET1FREE_STATUS == 'True') {
    define('TEXT_GET_1_FREE_PROMOTION', '<b>Special limited offer: Buy %u %s and get %u %s free!</b>');
    define('TEXT_FREE_ALT', 'Get 1 Free ');
    define('TEXT_FREE_ALT2', ' with the purchase of ');
    
    // auto expire get_1_free products
    require(DIR_WS_FUNCTIONS . 'get_1_free.php');
    tep_expire_get_1_free();
  }
?>