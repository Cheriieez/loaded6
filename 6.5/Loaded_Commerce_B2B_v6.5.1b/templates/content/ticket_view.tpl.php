<?php
  if ((defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') && (isset($_SESSION['customer_id'])) || ((defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') && (defined('SUPPORT_NO_LOGIN') && SUPPORT_NO_LOGIN == 'true'))) {  
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
    // BOF: Lango Added for template MOD
    if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
    //EOF: Lango Added for template MOD
  ?>
  <tr>
    <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td class="pageHeading" align="right"></td>
        </tr>
      </table>
    </td>
  </tr>
  <?php
    // BOF: Lango Added for template MOD
    } else {
      $header_text = HEADING_TITLE;
    }
    // EOF: Lango Added for template MOD

    // BOF: Lango Added for template MOD
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_top(false, false, $header_text);
    }
    // EOF: Lango Added for template MOD
  ?>
  <tr>
    <?php
      // Show Specific Ticket  
      if (!isset($tlid)) {
    ?>
    <td>
      <table align="left" width="100%" border="0" cellspacing="0" cellpadding="0">
        <?php echo tep_draw_form('ticket_view', tep_href_link(FILENAME_TICKET_VIEW, 'action=send', 'SSL'), 'get') . "\n"; ?>
        <tr>
          <td class="main" align="left"><?php echo '&nbsp;' . tep_draw_input_field('tlid'); ?><?php echo '&nbsp;&nbsp;' . TEXT_VIEW_TICKET_NR; ?></td>
          <td class="main" align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '&nbsp;'; ?></td>
        </tr></form>
      </table>
    </td>
  </tr>
  <?php
    if (isset($_SESSION['customer_id'])) {
      $customers_tickets_raw = "select * from " . TABLE_TICKET_TICKET . " where ticket_customers_id = '" . tep_db_prepare_input($customer_id) . "' order by ticket_date_last_modified desc";
      $customers_tickets_split = new splitPageResults($customers_tickets_raw, MAX_TICKET_SEARCH_RESULTS);
      if ($customers_tickets_split->number_of_rows > 0 ) {
  ?>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td class="infoBoxHeading" align="left"><?php echo TABLE_HEADING_NR; ?></td>
          <?php 
            if (TICKET_SHOW_CUSTOMERS_SUBJECT == 'true') { echo '            <td class="infoBoxHeading" align="left">' . TABLE_HEADING_SUBJECT . '</td>'; }
            if (TICKET_CATALOG_USE_STATUS == 'true') {     echo '            <td class="infoBoxHeading">' . TABLE_HEADING_STATUS . '</td>'; }
            if (TICKET_CATALOG_USE_DEPARTMENT == 'true') { echo '            <td class="infoBoxHeading">' . TABLE_HEADING_DEPARTMENT . '</td>'; }
            if (TICKET_CATALOG_USE_PRIORITY == 'true') {   echo '            <td class="infoBoxHeading">' . TABLE_HEADING_PRIORITY . '</td>'; }
          ?>
          <td class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_CREATED; ?></td>
          <td class="infoBoxHeading" align="right"><?php echo TABLE_HEADING_LAST_MODIFIED; ?></td>
        </tr>              
        <?php
          $customers_tickets_query = tep_db_query ($customers_tickets_split->sql_query);
          $number_of_tickets = 0;
          while ($customers_tickets = tep_db_fetch_array($customers_tickets_query)) {
            $number_of_tickets++;
            if (($number_of_tickets / 2) == floor($number_of_tickets / 2)) {
              echo '         <tr class="productListing-even">' . "\n";
            } else {
              echo '          <tr class="productListing-odd">' . "\n";
            }
        ?>
          <td class="smallText" align="left">
          <?php 
            echo '<a href="' . tep_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $customers_tickets['ticket_link_id']) . '">' . $customers_tickets['ticket_link_id'] . '</a>';
          ?>
          </td>
          <?php
            if (TICKET_SHOW_CUSTOMERS_SUBJECT == 'true') { echo '            <td class="smallText" align="left">' . $customers_tickets['ticket_subject'] . '</td>'; }
            if (TICKET_CATALOG_USE_STATUS == 'true') {     echo '            <td class="smallText">' . $ticket_status_array[$customers_tickets['ticket_status_id']] . '</td>'; }
            if (TICKET_CATALOG_USE_DEPARTMENT == 'true') { echo '            <td class="smallText">' . $ticket_department_array[$customers_tickets['ticket_department_id']] . '</td>'; }
            if (TICKET_CATALOG_USE_PRIORITY == 'true') {   echo '            <td class="smallText">' . $ticket_priority_array[$customers_tickets['ticket_priority_id']] . '</td>'; }
          ?>
          <td class="smallText" align="right"><?php echo tep_date_short($customers_tickets['ticket_date_created']); ?></td>
          <td class="smallText" align="right"><?php echo tep_date_short($customers_tickets['ticket_date_last_modified']); ?></td>
        </tr>
        <?php
          }
          if ($customers_tickets_split->number_of_rows > 0) {
        ?>
        <tr>
          <td colspan="7">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText"><?php echo $customers_tickets_split->display_count(TEXT_DISPLAY_NUMBER_OF_TICKETS); ?></td>
                <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE; ?> <?php echo $customers_tickets_split->display_links(MAX_TICKET_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
        <?php
          }
        ?>
      </table>
    </td>
  </tr>
  <?php
      }
    }
  }
  if (isset($tlid)) {
    $ticket_query = tep_db_query("select * from " . TABLE_TICKET_TICKET . " where ticket_link_id = '" . tep_db_input($tlid) . "'");
    $ticket = tep_db_fetch_array($ticket_query);
    // Check if Customer is allowed to view ticket:
    if ($ticket['ticket_customers_id'] > 1 && $ticket['ticket_login_required'] == '1' && !isset($_SESSION['customer_id']) ) {
      // Customer must be logged in to view ticket:
  ?>
  <tr>
    <td align="center">
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main"><?php echo sprintf(TEXT_VIEW_TICKET_LOGIN, tep_href_link(FILENAME_TICKET_VIEW, 'login=yes&tlid=' . $tlid, 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL')); ?></td>
        </tr>
      </table>
    </td>
  </tr>
  <?php  
    } else {
      // Customer is allowed to view ticket
      $ticket_status_query = tep_db_query("select * from " . TABLE_TICKET_STATUS_HISTORY . " where ticket_id = '". tep_db_input($ticket['ticket_id']) . "' order by ticket_date_modified desc");
  ?>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="2" class="main" align="left"><h3 style="margin:0;"><b><?php echo TABLE_HEADING_SUBJECT . '</b>: ' . $ticket['ticket_subject']; ?></h3></td>
        </tr> 
        <tr>
          <td class="main" colspan="2" align="left">
          <?php 
            echo '<b>' . TEXT_OPENED . '</b> ' . tep_date_short($ticket['ticket_date_created']) . ' ' . TEXT_TICKET_BY . ' ' . $ticket['ticket_customers_name'] . "<br>";
            echo '<b>' . TEXT_TICKET_NR . '</b>&nbsp;' . $ticket['ticket_link_id'];
            if ($ticket['ticket_customers_orders_id'] > 0) echo '<br /><b>' . TEXT_CUSTOMERS_ORDERS_ID . '</b>&nbsp;' . $ticket['ticket_customers_orders_id'];
          ?>
          </td>
        </tr>
        <tr>
          <td></td>
        </tr>  
        <?php     
          while ($ticket_status = tep_db_fetch_array($ticket_status_query)) {
         ?>
        <tr valign="top" class="<?php echo $cell_color; ?>">
          <td class="main" style="border-top:1px solid #cccccc; padding:10px;">
          <?php
            echo '<b>' . $ticket_status['ticket_edited_by'] . '</b><br />';
            echo TEXT_DATE . '&nbsp;' .  tep_date_short($ticket_status['ticket_date_modified']) . '<br />';
            if (TICKET_CATALOG_USE_STATUS == 'true') {
              echo TEXT_STATUS . '&nbsp;' .  $ticket_status_array[$ticket_status['ticket_status_id']] . '<br />';
            }
            if (TICKET_CATALOG_USE_DEPARTMENT == 'true') {
              echo TEXT_DEPARTMENT . '&nbsp;' .  $ticket_department_array[$ticket_status['ticket_department_id']] . '<br />';
            }
            if (TICKET_CATALOG_USE_PRIORITY == 'true') {
              echo TEXT_PRIORITY . '&nbsp;' .  $ticket_priority_array[$ticket_status['ticket_priority_id']] . '<br />';
            }
            $ticket_last_used_status = $ticket_status['ticket_status_id'];
            $ticket_last_used_department = $ticket_status['ticket_department_id'];
            $ticket_last_used_priority = $ticket_status['ticket_priority_id'];
          ?>
          </td>
          <td class="main" style="border-top:1px solid #cccccc; padding:10px;"><?php echo nl2br($ticket_status['ticket_comments']); ?></td>
        </tr>
        <?php
          }
        ?>
        <tr>
          <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
        </tr>
        <?php
          // BOF: Lango Added for template MOD
          if (MAIN_TABLE_BORDER == 'yes') {
            table_image_border_bottom();
          }
          // EOF: Lango Added for template MOD

          echo tep_draw_form('ticket_view', tep_href_link(FILENAME_TICKET_VIEW, 'action=send', 'SSL')); 
          echo tep_draw_hidden_field('tlid', $tlid);

          // BOF: Lango Added for template MOD
          if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
            $header_text = '&nbsp;'
          //EOF: Lango Added for template MOD
        ?>
        <tr>
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo TEXT_COMMENT; ?></td>
                <td class="pageHeading" align="right"></td>
              </tr>
            </table>
          </td>
        </tr>
        <?php
          // BOF: Lango Added for template MOD
          } else {
            $header_text = TEXT_COMMENT;
          }
          // EOF: Lango Added for template MOD

          // BOF: Lango Added for template MOD
          if (MAIN_TABLE_BORDER == 'yes'){
            table_image_border_top(false, false, $header_text);
          }
          // EOF: Lango Added for template MOD
        ?>
        <tr>
          <td colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <?php 
                //echo '<div class="main"><b>' . TEXT_COMMENT . '</b></div>';
                echo '<br />';
                if (TICKET_CATALOG_USE_STATUS == 'true' && TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS == 'true') {
                  echo '<tr><td class="main" align="left" width="100%">' . TEXT_STATUS . '&nbsp;' . tep_draw_pull_down_menu('status', $ticket_statuses, ($ticket_last_used_status ? $ticket_last_used_status : TICKET_DEFAULT_STATUS_ID) ) . '</td></tr>';
                } else {
                  echo tep_draw_hidden_field('status', ($ticket_last_used_status ? $ticket_last_used_status : TICKET_DEFAULT_STATUS_ID) );
                }
                if (TICKET_CATALOG_USE_DEPARTMENT == 'true' && TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT == 'true') {
                  echo '<tr><td class="main" align="left" width="100%">' . TEXT_DEPARTMENT . '&nbsp;' . tep_draw_pull_down_menu('department', $ticket_departments, ($ticket_last_used_department ? $ticket_last_used_department : TICKET_DEFAULT_DEPARTMENT_ID) ) . '</td></tr>';
                } else {
                  echo tep_draw_hidden_field('department', ($ticket_last_used_department ? $ticket_last_used_department : TICKET_DEFAULT_DEPARTMENT_ID) );
                }
                if (TICKET_CATALOG_USE_PRIORITY == 'true' && TICKET_ALLOW_CUSTOMER_TO_CHANGE_PRIORITY == 'true') {
                  echo '<tr><td class="main" align="left" width="100%">' . TEXT_PRIORITY . '&nbsp;' . tep_draw_pull_down_menu('priority', $ticket_prioritys, ($ticket_last_used_priority ? $ticket_last_used_priority : TICKET_DEFAULT_PRIORITY_ID) ) . '</td></tr>';
                } else {
                  echo tep_draw_hidden_field('priority', ($ticket_last_used_priority ? $ticket_last_used_priority : TICKET_DEFAULT_PRIORITY_ID) );
                }
                $area_width = ENQUIRY_TEXT_AREA_WIDTH;
                $area_height = ENQUIRY_TEXT_AREA_HEIGHT;
              ?>      
              <tr>
                <td width="100%" class="main" align="left" colspan="2"><?php echo tep_draw_textarea_field('enquiry', 'soft', $area_width, $area_height, $enquiry); ?><br /><?php if ($error_enquiry) echo ENTRY_ERROR_NO_ENQUIRY; ?></td>
              </tr>
              <?php 
                if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
                  if (defined('MODULE_ADDONS_CSMM_VIEW_VVC_ON_OFF') && MODULE_ADDONS_CSMM_VIEW_VVC_ON_OFF == 'On'){
              ?>
              <!-- VISUAL VERIFY CODE start -->
              <tr>
                <td colspan="2" class="main"><b><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?></b></td>
              </tr>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                    <tr class="infoBoxContents">
                      <td><table border="0" cellspacing="2" cellpadding="2">
                          <tr>
                            <td class="main"><?php echo VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS; ?></td>
                            <td class="main"><?php echo tep_draw_input_field('visual_verify_code') . '&nbsp;' . '<span class="inputRequirement">' . VISUAL_VERIFY_CODE_ENTRY_TEXT . '</span>'; ?></td>
                            <td class="main">
                            <?php
                              // can replace the following loop with $visual_verify_code = substr(str_shuffle (VISUAL_VERIFY_CODE_CHARACTER_POOL), 0, rand(3,6)); if you have PHP 4.3
                              $visual_verify_code = "";
                              for ($i = 1; $i <= rand(3,6); $i++){
                               $visual_verify_code = $visual_verify_code . substr(VISUAL_VERIFY_CODE_CHARACTER_POOL, rand(0, strlen(VISUAL_VERIFY_CODE_CHARACTER_POOL)-1), 1);
                              }
                              $vvcode_oscsid = tep_session_id();
                              tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . $vvcode_oscsid . "'");
                              $sql_data_array = array('oscsid' => $vvcode_oscsid, 'code' => $visual_verify_code);
                              tep_db_perform(TABLE_VISUAL_VERIFY_CODE, $sql_data_array);
                              $visual_verify_code = "";
                              echo('<img src="' . FILENAME_VISUAL_VERIFY_CODE_DISPLAY . '?vvc=' . $vvcode_oscsid . '" alt="' . VISUAL_VERIFY_CODE_CATEGORY . '">');
                            ?>
                            </td>
                            <td class="main"><?php echo VISUAL_VERIFY_CODE_BOX_IDENTIFIER; ?></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <!--  VISUAL VERIFY CODE stop   -->
              <?php
                  }
                }
              ?>
              <tr>
                <td colspan="2" class="main" align="left"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
              </tr></form>
            </table>
          </td>
        </tr>
      </table> 
      <?php
          }
        }
      ?>
    </td>
  </tr>
  <?php
    // BOF: Lango Added for template MOD
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_bottom();
    }
    // EOF: Lango Added for template MOD
  ?>
</table>
<?php
  } else if ((defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') && (!isset($_SESSION['customer_id'])) || ((defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') && (defined('SUPPORT_NO_LOGIN') && SUPPORT_NO_LOGIN != 'true'))) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
    // BOF: Lango Added for template MOD
    if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
      $header_text = '&nbsp;'
    //EOF: Lango Added for template MOD
  ?>
  <tr>
    <td width="100%" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"></td>
              </tr>
              <tr>
                <td class="main">You must <a href="<?php echo tep_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><strong>Login</strong></a> to view your Support Tickets</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php
    }
  } else if (defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS != 'True') {
?>
<table border="0" width="97%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
    // BOF: Lango Added for template MOD
    if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
      $header_text = '&nbsp;'
    //EOF: Lango Added for template MOD
  ?>
  <tr>
    <td width="96%" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"></td>
              </tr>
              <tr>
                <td class="main">The Support System is currently <b>Disabled</b></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php
    }
  }
?>