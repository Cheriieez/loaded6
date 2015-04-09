<?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('privatemessages', 'top');
  // RCI code eof
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
    ?>
    <!-- BEGIN Private Message -->
    <tr>
      <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo PRIVATE_MESSAGES_TITLE; ?></b></td>
          </tr>
          <tr class="infoBoxContents">
            <td>
              <table border="0" width="100%" cellspacing="1" cellpadding="2">
                <tr>
                  <td>
                  <?php
                    if (isset($_GET['action']) && ($_GET['action'] == 'deletemessage')) {
                        $mid = $_GET['mID'];
                        tep_db_query("delete from " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " where customers_id ='" . (int)$customer_id . "' and message_id ='" . $mid ."'");
                    }
                    $customerprivatemessage_query = tep_db_query("select * from " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " where customers_id = " . (int)$customer_id . " ORDER BY message_stat,message_id");
                    while ($privatemessage1 = tep_db_fetch_array($customerprivatemessage_query)) {
                        if ($privatemessage1['message_stat'] != 'Yes') {
                            tep_db_query("UPDATE " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " SET message_stat = ('Yes') where customers_id ='" . (int)$customer_id . "' and message_id ='" . $privatemessage1['message_id'] . "'"); 
                        }
                        if ($privatemessage1['message_forall'] == 1) {
                            $all = TEXT_PRIVATE_MESSAGE_TO_ALL;
                        } else {
                            $all = '';
                        }
                        echo '<tr>';
                        echo '<td class="main">' . TABLE_HEADING_PRIVATE_MESSAGE_DESC .' <b>' . $privatemessage1['message_id'] .'</b> <strong>' . $all .'</strong></td>';
                        echo '<td class="main" align="right">' . TABLE_HEADING_PRIVATE_MESSAGE_STATUS . ' <b>(' . $privatemessage1['message_stat'] . ')</b></td>';			
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td colspan="2">';
                        echo '<table border="0" width="100%" cellspacing="0" cellpadding="3" style="border-style: solid; border-width: 1px; padding: 0" bordercolor="#B6B7CB">';
                        echo '<tr bgcolor="#FFFFFF">';
                        echo '<td class="main">';
                        echo $privatemessage1['message_desc'];
                        echo '</td>';
                        echo '</tr>';
                        echo '</table>';
                        echo '</td>';
                        echo '</tr>';
                        echo '<tr><td colspan="2" align="right"><a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('mID', 'action')) .
                             'mID=' . $privatemessage1['message_id'] . '&action=deletemessage') . '">' . tep_template_image_button('button_delete.gif', IMAGE_BUTTON_DELETE) . '</a></td></tr>';
                        echo '<tr><td class="main" colspan="2"></td></tr>';
                    }
                  ?></form>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <?php
    // RCI code start
    echo $cre_RCI->get('privatemessages', 'menu');
    // RCI code eof
    // BOF: Lango Added for template MOD
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_bottom();
    }
    // EOF: Lango Added for template MOD
    ?>
  </table>
<?php
  // RCI code start
  echo $cre_RCI->get('privatemessages', 'bottom');
  echo $cre_RCI->get('global', 'top');
  // RCI code eof
?>