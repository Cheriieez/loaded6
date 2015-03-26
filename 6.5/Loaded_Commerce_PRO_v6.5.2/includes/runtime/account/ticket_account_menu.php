<?php if (MODULE_ADDONS_CSMM_STATUS == 'True') { ?> 
  <!-- BEGIN Private Messages  -->
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>     
  <tr>
    <td class="main">&nbsp;<b><?php echo PRIVATE_MESSAGES_TITLE; ?></b></td>
  </tr>
  <tr>
    <td>
      <table align="center" border="0" width="99%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <?php
              if (isset($_GET['action']) && ($_GET['action'] == 'deletemessage')) {
                  tep_db_query("UPDATE " . TABLE_CUSTOMERS . " SET customers_private_messages = ('') where customers_id ='" . (int)$customer_id . "'");
                  echo "<tr><td class=\"main\">" . PRIVATE_MESSAGES_DELETED . "</td></tr>";
                  echo "<tr><td class=\"main\">" . PRIVATE_MESSAGES_NO . "</td></tr>";
              } else {
                  $cID = $_SESSION['customer_id'];
                  $privatemessage_query = tep_db_query("select count(*) as number_of_messages from " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " where customers_id = '" . $cID . "' and message_stat != 'Yes'");
                  $privatemessage = tep_db_fetch_array($privatemessage_query);
                  //print_r($privatemessage['number_of_messages']);
                    if ($privatemessage['number_of_messages'] > 0) {
                      echo '<tr>
                              <td width="10">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>
                              <td width="60">' . tep_image(DIR_WS_IMAGES . 'message.png', '', '60') . '</td>
                              <td width="10">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>
                              <td>
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td class="main">' . tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' ' . PRIVATE_MESSAGES_YES_START . ' ' . $privatemessage['number_of_messages'] . ' ' . PRIVATE_MESSAGES_YES_END . '</td></tr>
                                  </tr>
                                  <tr>
                                    <td class="main">' . tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES) .'">' . PRIVATE_MESSAGES_NO . '</a></td></tr>
                                  </tr>
                                </table>
                              </td>
                              <td width="10" align="right">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>
                            </tr>';
                    } else {
                      echo '<tr>
                              <td width="10">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>
                              <td width="60">' . tep_image(DIR_WS_IMAGES . 'message.png', '', '60') . '</td>
                              <td width="10">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>
                              <td>
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td class="main">' . tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' ' . PRIVATE_MESSAGES_NO_NEW . '</td></tr>
                                  </tr>
                                  <tr>
                                    <td class="main">' . tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES) .'">' . PRIVATE_MESSAGES_NO . '</a></td></tr>
                                  </tr>
                                </table>
                              </td>
                              <td width="10" align="right">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>
                            </tr>';
                    }
              }
            ?>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<!-- END Private Messages -->
<?php } ?>