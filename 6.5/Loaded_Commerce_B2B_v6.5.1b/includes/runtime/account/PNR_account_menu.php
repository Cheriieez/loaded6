<?php
/*
  $Id: PNR_account_menu.php,v 1.0.0 2008/05/22 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  if (MODULE_ADDONS_POINTS_STATUS == 'True') { // check that the points system is enabled
  ?>
  <!-- Points/Rewards Module V2.00 points_system_box_bof //-->
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><b><?php echo MY_POINTS_TITLE; ?></b></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            <td width="60"><?php echo tep_image(DIR_WS_IMAGES . 'money.gif', '', '60'); ?></td>
            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <?php
              $shopping_points = tep_get_shopping_points();
              global $currencies;
              if ($shopping_points > 0) {
              ?>
              <tr>
                <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'indicator.gif') .'&nbsp;&nbsp;'.  sprintf(MY_POINTS_CURRENT_BALANCE, number_format($shopping_points, POINTS_DECIMAL_PLACES), $currencies->format(tep_calc_shopping_pvalue($shopping_points))); ?></td>
              </tr>
              <?php
                }
              ?>
              <tr>
                <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_MY_POINTS, '', 'SSL') . '">' . MY_POINTS_VIEW . '</a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_MY_POINTS_HELP, '', 'SSL') . '">' . MY_POINTS_VIEW_HELP . '</a>'; ?></td>
              </tr>
            </table></td>
            <td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <!-- Points/Rewards Module V2.00 points_system_box_eof //-->
  <?php 
    }
  ?>