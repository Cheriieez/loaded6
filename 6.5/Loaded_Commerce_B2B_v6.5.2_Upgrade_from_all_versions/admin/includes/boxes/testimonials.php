<?php
/*
  $Id: testimonials.php,v 1.3 2007/09/03 meastro Exp $

  Contribution Central, Custom CRE Loaded Programming
  http://www.contributioncentral.com
  Copyright (c) 2007 Contribution Central

  Released under the GNU General Public License
*/
if (defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') {
?>
<!-- testimonials //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TESTIMONIALS,
                     'link'  => tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'selected_box=testimonials'));

  if ($_SESSION['selected_box'] == 'testimonials' || MENU_DHTML == 'True') {
      $returned_rci_testimonials_top = $cre_RCI->get('testimonials', 'boxestop');
      $returned_rci_testimonials_bottom = $cre_RCI->get('testimonials', 'boxesbottom');
      $contents[] = array('text'  => $returned_rci_testimonials_top .
                                     tep_admin_files_boxes(FILENAME_TESTIMONIALS_MANAGER,  BOX_TESTIMONIALS_TESTIMONIALS_MANAGER) .
                                     $returned_rci_testimonials_bottom);
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- testimonials_eof //-->
<?php } ?>