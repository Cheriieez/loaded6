<?php
/*
  $Id: my_points_help.php, v 2.00 2006/JULY/06 17:41:03 dsa_ Exp $
  created by Ben Zukrel, Deep Silver Accessories
  http://www.deep-silver.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_MY_POINTS_HELP);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_MY_POINTS_HELP, '', 'NONSSL'));

   $content = CONTENT_MY_POINTS_HELP;
 //  $javascript = $content . '.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');


?>
<!-- The Javascript Below is required only if you want to use CSS FAQ display style. This is not the Standard for this contribution at the moment.//-->
<!-- A live example of the result can be seen in action at http://www.eboutik.net/my_points_help.php //-->
<script language="javascript"><!--
window.onload=show;

function show(id) {
var d = document.getElementById(id);
	for (var i = 1; i<=20; i++) {
		if (document.getElementById('answer_q'+i)) {document.getElementById('answer_q'+i).style.display='none';}
	}
if (d) {d.style.display='block';}
}
//--></script>