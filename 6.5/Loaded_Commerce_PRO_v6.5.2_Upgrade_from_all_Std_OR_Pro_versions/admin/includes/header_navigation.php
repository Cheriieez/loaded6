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
  Latest Revision : $Revision: 4210 $

  Released under the GNU General Public License
*/

if (MENU_DHTML == True) {
  $menu_dhtml = MENU_DHTML;
  $box_files_list1a = array(  array('administrator'   , 'administrator.php', BOX_HEADING_ADMINISTRATOR),
                              array('configuration'   , 'configuration.php', BOX_HEADING_CONFIGURATION),
                              array('catalog'         , 'catalog.php', BOX_HEADING_CATALOG),
                              array('customers'       , 'customers.php' , BOX_HEADING_CUSTOMERS),
                              array('marketing'       , 'marketing.php', BOX_HEADING_MARKETING),
                              array('gv_admin'        , 'gv_admin.php' , BOX_HEADING_GV_ADMIN),
                              array('reports'         , 'reports.php' , BOX_HEADING_REPORTS),
                              array('data'            , 'data.php' , BOX_HEADING_DATA),
                              array('links'           , 'links.php' , BOX_HEADING_LINKS),
                              array('exporttools'     , 'export_tools.php', BOX_HEADING_EXPORTTOOLS),
                              array('tools'           , 'tools.php' , BOX_HEADING_TOOLS)
                          );
  $box_files_list1b = array();

  $box_files_list2a = array(array('information'     , 'information.php', BOX_CDS_HEADING),
                            array('articles'        , 'articles.php' , BOX_HEADING_ARTICLES),
                            array('design_controls' , 'design_controls.php' , BOX_HEADING_DESIGN_CONTROLS),
                            array('modules'         , 'modules.php' , BOX_HEADING_MODULES),
                            array('taxes'           , 'taxes.php' , BOX_HEADING_LOCATION_AND_TAXES),
                            array('localization'    , 'localization.php' , BOX_HEADING_LOCALIZATION)
                            );
  $box_files_list2b = array();                          
  if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') {
    $box_files_list2b = array_merge($box_files_list2b, array(array('fdm_library', 'fdm_library.php' , BOX_HEADING_LIBRARY)));
  }
  if (defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') {
    $box_files_list2b = array_merge($box_files_list2b, array(array('ticket', 'ticket.php', BOX_HEADING_TICKET)));
  }
  if (defined('MODULE_ADDONS_CTM_STATUS') && MODULE_ADDONS_CTM_STATUS == 'True') {
    $box_files_list2b = array_merge($box_files_list2b, array(array('testimonials', 'testimonials.php', BOX_HEADING_TESTIMONIALS)));
  }
  
  $box_files_list1 = array_merge($box_files_list1a, $box_files_list1b);
  $box_files_list2 = array_merge($box_files_list2a, $box_files_list2b);
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
}
?>