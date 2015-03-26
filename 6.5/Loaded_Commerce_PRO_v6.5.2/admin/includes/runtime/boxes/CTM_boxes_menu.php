<?php
/*
  $Id: CTM_boxes_menu.php,v 1.3 2007/09/03 meastro Exp $

  Contribution Central, Custom CRE Loaded Programming
  http://www.contributioncentral.com
  Copyright (c) 2007 Contribution Central

  Released under the GNU General Public License
*/

global $selected_box, $menu_dhtml;
if (defined('MODULE_ADDONS_CTM_STATUS') && MODULE_ADDONS_CTM_STATUS == 'True') {
  if (tep_admin_check_boxes('testimonials.php') == true) {
    $heading = array();
    $contents = array();

    $heading[] = array('text'  => BOX_HEADING_TESTIMONIALS,
                       'link'  => tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'selected_box=testimonials'));

    if ($_SESSION['selected_box'] == 'testimonials' || MENU_DHTML == 'True') {
      $contents[] = array('text' => tep_admin_files_boxes(FILENAME_TESTIMONIALS_MANAGER,  BOX_TESTIMONIALS_TESTIMONIALS_MANAGER));
    }

    $box = new box;
    if (MENU_DHTML == 'True') {
      echo $box->menuBox($heading, $contents);
      return ;
    } else {
      $interm = $box->menuBox($heading, $contents);
      $rci .= '<!-- testimonials //-->' . "\n";
      $rci .= '<tr>' . "\n";
      $rci .= '<td>' . "\n";
      $rci .= $interm;
      $rci .= '</td>' . "\n";
      $rci .= '</tr>' . "\n";
      $rci .= '<!-- testimonials eof //-->' . "\n";
    }
  }
}
?>