<?php
/*
  $Id: header_navigation.php,v 1.1.1.1 2004/03/04 23:39:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

    Chain Reaction Works, Inc
  Copyright &copy; 2005 - 2006 Chain Reaction Works, Inc.

  Last Modified by $Author$
  Last Modifed on : $Date$
  Latest Revision : $Revision: 6108 $

  Released under the GNU General Public License
*/
global $selected_box, $menu_dhtml;
if (MENU_DHTML == True) {
  $box_files_list1a = array(array('administrator', 'administrator.php', BOX_HEADING_ADMINISTRATOR),
                            array('configuration', 'configuration.php', BOX_HEADING_CONFIGURATION),
                            array('catalog', 'catalog.php', BOX_HEADING_CATALOG),
                            array('customers', 'customers.php' , BOX_HEADING_CUSTOMERS));

  $box_files_list1b = array(array('design_controls' , 'design_controls.php' , BOX_HEADING_DESIGN_CONTROLS),
                            array('gv_admin', 'gv_admin.php' , BOX_HEADING_GV_ADMIN),
                            array('data', 'data.php' , BOX_HEADING_DATA));

  $box_files_list2a = array(array('information', 'information.php', BOX_CDS_HEADING),
                            array('affiliate', 'affiliate.php', BOX_HEADING_AFFILIATE),
                            array('articles', 'articles.php' , BOX_HEADING_ARTICLES));

  $box_files_list2b = array(array('marketing', 'marketing.php', BOX_HEADING_MARKETING),
                            array('links', 'links.php' , BOX_HEADING_LINKS),
                            array('modules', 'modules.php' , BOX_HEADING_MODULES),
                            array('reports', 'reports.php' , BOX_HEADING_REPORTS),
                            array('taxes', 'taxes.php' , BOX_HEADING_LOCATION_AND_TAXES));
    
  $box_files_list3 = array(array('b2bsettings', 'b2bsettings.php', BOX_HEADING_B2BSETTINGS),
                           array('exporttools', 'export_tools.php', BOX_HEADING_EXPORTTOOLS),
                           array('localization', 'localization.php' , BOX_HEADING_LOCALIZATION),
                           array('tools', 'tools.php', BOX_HEADING_TOOLS));

  if (MVS_STATUS == 'true') {  
    $box_files_list3 = array_merge($box_files_list3,array(array('vendors', 'vendors.php', BOX_HEADING_VENDORS)));
  }  
  if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') {
    $box_files_list3 = array_merge($box_files_list3, array(array('fss_menu', 'FSS_boxes_menu.php', BOX_HEADING_FSS)));
  }  
  if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') {
    $box_files_list3 = array_merge($box_files_list3, array(array('fdm_library', 'fdm_library.php', BOX_HEADING_LIBRARY)));
  }  
  if (defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') {
    $box_files_list3 = array_merge($box_files_list3, array(array('ticket', 'ticket.php', BOX_HEADING_TICKET)));
  }  
  if (defined('MODULE_ADDONS_CTM_STATUS') && MODULE_ADDONS_CTM_STATUS == 'True') {
    $box_files_list3 = array_merge($box_files_list3, array(array('testimonials', 'testimonials.php', BOX_HEADING_TESTIMONIALS)));
  }

  // RCI start
  $returned_rci_first_menu = $cre_RCI->get('boxes', 'dhtmlmenufirst', false);
  $new = str_replace(ord(60), "", $returned_rci_first_menu);
  $box_files_rci_first_menu = array(explode(", ", $new));
  
  $returned_rci_second_menu = $cre_RCI->get('boxes', 'dhtmlmenusecond', false);
  $new = str_replace(ord(60), "", $returned_rci_second_menu);
  $box_files_rci_second_menu = array(explode(", ", $new));
  
  $returned_rci_third_menu = $cre_RCI->get('boxes', 'dhtmlmenuthird', false);
  $new = str_replace(ord(60), "", $returned_rci_third_menu);
  $box_files_rci_third_menu = array(explode(", ", $new));
  
  if ($returned_rci_first_menu == '') {
    $box_files_list1 = array_merge($box_files_list1a, $box_files_list1b);
  } else {
    $box_files_list1 = array_merge($box_files_list1a, $box_files_rci_first_menu, $box_files_list1b);
  }
  if ($returned_rci_second_menu == '') {
    $box_files_list2 = array_merge($box_files_list2a, $box_files_list2b);
  } else {
    $box_files_list2 = array_merge($box_files_list2a, $box_files_rci_second_menu, $box_files_list2b);
  }
  // RCI eof

  echo '<!-- Menu bar #1. --> <div class="menuBar" style="width:100%;">';
  foreach($box_files_list1 as $item_menu) {
    if (tep_admin_check_boxes($item_menu[1]) == true) {
      echo "<a class=\"menuButton\" onmouseover=\"buttonMouseover(event, '".$item_menu[0]."Menu');\">".$item_menu[2]."</a>";
      require(DIR_WS_BOXES . $item_menu[1]);
    }
  }
  echo "</div>";
  
  echo '<!-- Menu bar #2. --> <div class="menuBar" style="width:100%;">';
  foreach($box_files_list2 as $item_menu) {
    if (tep_admin_check_boxes($item_menu[1]) == true) {
      echo "<a class=\"menuButton\" onmouseover=\"buttonMouseover(event, '".$item_menu[0]."Menu');\">".$item_menu[2]."</a>";
      require(DIR_WS_BOXES . $item_menu[1]);
    }
  }
  echo "</div>";
  
  echo '<!-- Menu bar #3. --> <div class="menuBar" style="width:100%;">';
  foreach($box_files_list3 as $item_menu) {
    if (tep_admin_check_boxes($item_menu[1]) == true) {
      echo "<a class=\"menuButton\" onmouseover=\"buttonMouseover(event, '".$item_menu[0]."Menu');\">".$item_menu[2]."</a>";
      if ($item_menu[1] == 'FSS_boxes_menu.php') {
        require(DIR_WS_INCLUDES . "runtime/boxes/" . $item_menu[1]);
      } else {
        require(DIR_WS_BOXES . $item_menu[1]); 
      }
    }
  }
  echo "</div>";
}
?>