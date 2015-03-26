<?php
/*
  $Id: my_points.php, v 2.00 2006/JULY/06 17:41:03 dsa_ Exp $
  created by Ben Zukrel, Deep Silver Accessories
  http://www.deep-silver.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_MY_POINTS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_MY_POINTS, '', 'SSL'));
  
   $content = CONTENT_MY_POINTS;
  // $javascript = $content . '.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>
