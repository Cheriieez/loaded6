<?php
/*
  $Id: PNR_productreviewsinfo_top.php,v 1.1 2007/07/30 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- Points/Rewards Module V2.00 bof //-->
<?php
  if ((MODULE_ADDONS_POINTS_STATUS == 'True') && (tep_not_null(USE_POINTS_FOR_REVIEWS))) {
?>
    <table width="100%">
      <tr>
        <td class="main">
          <table width="100%" cellpadding="5" cellspacing="0" border="0">
            <tr>
              <td><?php echo REVIEW_HELP_LINK; ?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
<?php
  }
?>
<!-- Points/Rewards Module V2.00 eof //-->