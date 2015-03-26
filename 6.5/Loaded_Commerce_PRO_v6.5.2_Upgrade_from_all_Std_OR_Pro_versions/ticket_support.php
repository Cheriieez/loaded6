<?php
/*
  $Id: ticket_support.php,v 1.5 2003/04/25 21:37:12 hook Exp $

  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  /*if (!tep_session_is_registered('customer_id') && $_GET['login']=="yes") {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }*/

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TICKET_SUPPORT);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_TICKET_SUPPORT));

  $content = CONTENT_TICKET_SUPPORT;
  $javascript = $content . '.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
 
 ?>