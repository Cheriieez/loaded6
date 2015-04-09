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
    <td width="100%" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
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
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <?php
          // BOF: Lango Added for template MOD
          } else {
            $header_text = HEADING_TITLE;
          }
          // EOF: Lango Added for template MOD
        ?>
        <tr>
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
                // BOF: Lango Added for template MOD
                if (MAIN_TABLE_BORDER == 'yes') {
                  table_image_border_top(false, false, $header_text);
                }
                // EOF: Lango Added for template MOD
                if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
              ?>
              <tr>
                <td valign="top">
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td rowspan=4 class="main"><?php echo tep_image(HTTPS_SERVER . '/images/table_background_man_on_board.gif', HEADING_TITLE, '0', '0', 'align="left"') ?></td>
                      <td class="main"><?php echo TEXT_SUCCESS; ?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo TEXT_YOUR_TICKET_ID . ' ' . $_GET['tlid']; ?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo TEXT_CHECK_YOUR_TICKET . '<br /><a href="' . tep_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $_GET['tlid'], 'SSL', false, false) . '">' . tep_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $_GET['tlid'], 'SSL', false, false) . '</a>'; ?></td>
                    </tr>
                    <tr>
                      <td valign ="bottom" align="right"><br /><a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'SSL'); ?>"><?php echo tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <?php
                } else {
                  if (!isset($_SESSION['customer_id'])) {
              ?>
              <tr>
                <td class="main" align="left" width="100%" valign="top">
                <?php
                  echo sprintf(TEXT_LOGIN, tep_href_link(FILENAME_TICKET_CREATE, 'login=yes', 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL')); 
                ?>
                </td>
              </tr>
              <?php
                } else {
              ?>
              <tr>
                <td class="main" align="left" width="100%" valign="top"><?php echo TEXT_FILL_OUT_FORM; ?></td>
              </tr>
              <?php
                }
              ?>
              <tr>
                <td>
                  <?php
                    echo tep_draw_form('contact_us', tep_href_link(FILENAME_TICKET_CREATE, 'action=send', 'SSL'));
                  ?>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td width="150" class="main"><?php echo ENTRY_NAME; ?></td>
                      <td class="main">
                      <?php
                        if (isset($_SESSION['customer_id'])) {
                          echo tep_draw_hidden_field('name', $customer['customers_firstname'] . ' ' . $customer['customers_lastname']) . $customer['customers_firstname'] . ' ' . $customer['customers_lastname']; 
                        } else {
                          echo tep_draw_input_field('name', ($error ? $name : $first_name)); if ($error_name) echo ENTRY_ERROR_NO_NAME;
                        }
                      ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo ENTRY_EMAIL; ?></td>
                      <td class="main">
                      <?php
                        if (isset($_SESSION['customer_id'])) {
                          echo tep_draw_hidden_field('email', $customer['customers_email_address']) . $customer['customers_email_address']; 
                        } else {
                          echo tep_draw_input_field('email', ($error ? $email : $email_address)); if ($error_email) echo ENTRY_EMAIL_ADDRESS_CHECK_ERROR; 
                        }
                      ?>
                      </td>
                    </tr>
                    <?php
                      if (TICKET_SHOW_CUSTOMERS_SUBJECT == 'true') {   
                    ?>
                    <tr>
                      <td class="main"><?php echo ENTRY_SUBJECT; ?></td>
                      <td class="main"><?php  echo tep_draw_input_field('subject', ($error ? $subject : $subject)); if ($error_subject) echo ENTRY_ERROR_NO_SUBJECT; ?></td>
                    </tr>
                    <?php
                      }
                      if (TICKET_SHOW_CUSTOMERS_ORDER_IDS == 'true' && isset($_SESSION['customer_id'])) {     
                        $customers_orders_query = tep_db_query("select orders_id, date_purchased from " . TABLE_ORDERS . " where customers_id = '" . tep_db_input($_SESSION['customer_id']) . "'");
                        if (isset($_GET['ticket_order_id'])) $ticket_preselected_order_id = $_GET['ticket_order_id'];
                          $orders_array[] = array('id' => '', 'text' => ' -- ' );
                          while ($customers_orders = tep_db_fetch_array($customers_orders_query)) {
                            $orders_array[] = array('id' => $customers_orders['orders_id'], 'text' => $customers_orders['orders_id'] . "  (" . tep_date_short($customers_orders['date_purchased']) . ")" );
                          }
                    ?>
                    <tr>
                      <td class="main"><?php echo ENTRY_ORDER; ?></td>
                     <td class="main"><?php echo  tep_draw_pull_down_menu('ticket_customers_orders_id', $orders_array, $ticket_preselected_order_id); ?></td>
                    </tr>
                    <?php
                      }
                      if (TICKET_CATALOG_USE_DEPARTMENT == 'true') {     
                    ?>
                    <tr>
                      <td class="main"><?php echo ENTRY_DEPARTMENT; ?></td>
                      <td class="main"><?php echo tep_draw_pull_down_menu('department', $ticket_departments, ($department ? $department : TICKET_DEFAULT_DEPARTMENT_ID) ); ?></td>
                    </tr>
                    <?php
                      } else {
                        echo tep_draw_hidden_field('department', TICKET_DEFAULT_DEPARTMENT_ID);
                      }
                      if (TICKET_CATALOG_USE_PRIORITY == 'true') {   
                    ?>
                    <tr>
                      <td class="main"><?php echo ENTRY_PRIORITY; ?></td>
                      <td class="main"><?php echo tep_draw_pull_down_menu('priority', $ticket_prioritys, ($priority ? $priority : TICKET_DEFAULT_PRIORITY_ID) ); ?></td>
                    </tr>
                    <?php
                      } else {
                        echo tep_draw_hidden_field('priority', TICKET_DEFAULT_PRIORITY_ID);
                      }
                    ?>
                    <tr>
                      <td class="main"><?php echo ENTRY_ENQUIRY; ?></td>
                      <td class="main"><?php echo tep_draw_textarea_field('enquiry', 'soft', ENQUIRY_TEXT_AREA_WIDTH, ENQUIRY_TEXT_AREA_HEIGHT, $enquiry); ?><br /><?php if ($error_enquiry) echo ENTRY_ERROR_NO_ENQUIRY; ?></td>
                    </tr>
                    <?php 
                      if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
                        if (defined('MODULE_ADDONS_CSMM_CREATE_VVC_ON_OFF') && MODULE_ADDONS_CSMM_CREATE_VVC_ON_OFF == 'On'){
                    ?>
                    <!-- VISUAL VERIFY CODE start -->
                    <tr>
                      <td width="20">&nbsp;</td>
                      <td colspan="2" class="main"><b><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?></b></td>
                    </tr>
                    <tr>
                      <td width="20">&nbsp;</td>
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
                      <td class="main" align="right" colspan="2"><br /><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                    </tr>
                  </table></form>
                </td>
              </tr>
              <?php
                }
                // BOF: Lango Added for template MOD
                if (MAIN_TABLE_BORDER == 'yes'){
                  table_image_border_bottom();
                }
                // EOF: Lango Added for template MOD
              ?>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php
  } else if ((defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') && (!isset($_SESSION['customer_id'])) || ((defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'true') && (defined('SUPPORT_NO_LOGIN') && SUPPORT_NO_LOGIN != 'true'))) {
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
                <td class="main">You must <a href="<?php echo tep_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><strong>Login</strong></a> to use the Support System</td>
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
