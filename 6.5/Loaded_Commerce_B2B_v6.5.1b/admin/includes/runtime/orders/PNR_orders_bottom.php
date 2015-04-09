<?php
/*
  $Id: PNR_editorders_updateorder.php,v 1.0.0.0 2007/08/16 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if (MODULE_ADDONS_POINTS_STATUS == 'True') { 
?>
            <!-- Points/Rewards Module V2.00 check_box_bof //-->
            <?php
              $p_status_query = tep_db_query("SELECT points_status FROM " . TABLE_CUSTOMERS_POINTS_PENDING . " WHERE points_status = 1 AND points_type = 'SP' AND orders_id = '" . $oID . "'");
              if (tep_db_num_rows($p_status_query)) {
                echo '<td class="main"><b>' . ENTRY_NOTIFY_POINTS . '</b>&nbsp;' . ENTRY_QUE_POINTS . tep_draw_checkbox_field('confirm_points', '', false) . '&nbsp;' . ENTRY_QUE_DEL_POINTS . tep_draw_checkbox_field('delete_points', '', false) . '&nbsp;&nbsp;</td>';
              }
            ?>
            <!-- Points/Rewards Module V2.00 check_box_eof //-->
<?php } ?>