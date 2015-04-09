<?php
/*
  $Id: ticket.php,v 1.5 2003/04/25 21:37:11 hook Exp $

  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') { 
?>
<!-- customer_service //-->
<tr>
  <td>
  <?php
    $heading = array();
    $contents = array();

    $heading[] = array('text'  => BOX_HEADING_TICKET,
                       'link'  => tep_href_link(FILENAME_TICKET_VIEW, 'selected_box=ticket'));

    if ($_SESSION['selected_box'] == 'ticket' || MENU_DHTML == 'True') { 
      //RCI start
      $returned_rci_ticket_top = $cre_RCI->get('ticket', 'boxestop');
      $returned_rci_ticket_bottom = $cre_RCI->get('ticket', 'boxesbottom');
      $contents[] = array('text'  => $returned_rci_ticket_top .
                                     tep_admin_files_boxes('', BOX_TICKET_MENU) .
                                     tep_admin_files_boxes(FILENAME_TICKET_VIEW, BOX_TICKET_VIEW, 'NONSSL', '', '2') .
                                     tep_admin_files_boxes(FILENAME_TICKET_REPLY, BOX_TICKET_REPLY, 'NONSSL', '', '2') .
                                     tep_admin_files_boxes(FILENAME_TICKET_ADMIN, BOX_TICKET_ADMIN, 'NONSSL', '', '2') .
                                     tep_admin_files_boxes(FILENAME_TICKET_DEPARTMENT, BOX_TICKET_DEPARTMENT, 'NONSSL', '', '2') .
                                     tep_admin_files_boxes(FILENAME_TICKET_STATUS, BOX_TICKET_STATUS, 'NONSSL', '', '2') .
                                     tep_admin_files_boxes(FILENAME_TICKET_PRIORITY, BOX_TICKET_PRIORITY, 'NONSSL', '', '2') .
                                     tep_admin_files_boxes('', BOX_TICKET_EXTRA) .
                                     tep_admin_files_boxes(FILENAME_PRIVATE_MESSAGES, BOX_TICKET_MESSAGES, 'NONSSL', '', '2') .
                                     tep_admin_files_boxes(FILENAME_CHANGE_PASSWORD, BOX_CUSTOMERS_CHANGE_PASSWORD, 'NONSSL', '', '2') .
                                     tep_admin_files_boxes('', BOX_TICKET_CONFIGURATION) .
                                     tep_admin_files_boxes(FILENAME_CONFIGURATION, 'Configuration', 'NONSSL', 'gID=429', '2') .
                                     tep_admin_files_boxes('', BOX_TICKET_BACKUP_RESTORE) .
                                     tep_admin_files_boxes(FILENAME_TICKET_BACKUP_RESTORE, BOX_TICKET_BACKUP_RESTORE, 'NONSSL', '', '2') .
                                     $returned_rci_ticket_bottom);
      //RCI eof
    }

    $box = new box;
    echo $box->menuBox($heading, $contents);
  ?>
  </td>
</tr>
<!-- customer_service_eof //-->
<?php } ?>