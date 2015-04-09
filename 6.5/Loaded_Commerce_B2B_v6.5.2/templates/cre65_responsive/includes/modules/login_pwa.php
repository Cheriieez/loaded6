<?php
  if (B2B_REQUIRE_LOGIN=='true') {
    echo '<tr>
            <td class=\"main\">';
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => TEXT_REQUIRE_LOGIN_MESSAGE_HEADING);
    new contentBoxHeading($info_box_contents);
    
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => TEXT_REQUIRE_LOGIN_MESSAGE);
    new contentBox($info_box_contents, true, true);
    
    if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){  
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
      );
      new contentboxFooter($info_box_contents);
    }
    echo '</td></tr>';
  }

  //BOF: MaxiDVD Returning Customer Info SECTION
  //===========================================================
  $returning_customer_title = HEADING_RETURNING_CUSTOMER; // DDB - 040620 - PWA - change TEXT by HEADING
  if ($setme != '') {
    $returning_customer_info = "
    <!--Confirm Block-->
    <td width=\"50%\" height=\"100%\" valign=\"top\"><table border=\"0\" width=\"100%\" height=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"infoBox\">
    <tr class=\"infoBoxContents\">
    <td>
    <table border=\"0\" width=\"100%\" height=\"100%\" cellspacing=\"0\" cellpadding=\"2\">
    <tr>
    <td colspan=\"2\">".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td>
    </tr>
    <tr>
    <td class=\"main\" colspan=\"2\">".TEXT_YOU_HAVE_TO_VALIDATE."</td>
    </tr>
    <tr>
    <td colspan=\"2\">".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td>
    </tr>
    <tr>
    <td class=\"main\"><b>". ENTRY_EMAIL_ADDRESS."</b></td>
    <td class=\"main\">". tep_draw_input_field('email_address')."</td>
    </tr>
    <tr>
    <td class=\"main\"><b>". ENTRY_VALIDATION_CODE."</b></td>
    <td class=\"main\">".tep_draw_input_field('pass').tep_draw_input_field('password',$_POST['password'],'','hidden')."</td>
    </tr>
    <tr>
    <td colspan=\"2\">".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td>
    </tr>
    <tr>
    <td class=\"smallText\" colspan=\"2\">". '<a href="' . tep_href_link('validate_new.php', '', 'SSL') . '">' . TEXT_NEW_VALIDATION_CODE . '</a>'."</td>
    </tr>
    <tr>
    <td colspan=\"2\">". tep_draw_separator('pixel_trans.gif', '100%', '10')."</td>
    </tr>
    <tr>
    <td colspan=\"2\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">
    <tr>
    <td width=\"10\">". tep_draw_separator('pixel_trans.gif', '10', '1')."</td>
    <td align=\"right\">".tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE)."</td>
    <td width=\"10\">".tep_draw_separator('pixel_trans.gif', '10', '1')."</td>
    </tr>
    </table>
    </table></td>
    </tr>
    </table></form></td>
    <!--Confirm Block END-->
    ";  
  } else {
    $returning_customer_info = '
    <div class="col-sm-6 margin-top-10">'.
      tep_draw_form('login', tep_href_link(FILENAME_LOGIN, '', 'SSL'), 'post').
        tep_draw_hidden_field('action', 'process').'
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">' . HEADING_RETURNING_CUSTOMER . '</h3>
          </div>
          <div class="panel-body">
            <p>' . TEXT_RETURNING_CUSTOMER . '</p>
            <div class="form-group">
              <label for="email_address">E-Mail Address:</label>
              ' . tep_draw_input_field('email_address','','class="form-control" id="email_address" placeholder="' . substr(ENTRY_EMAIL_ADDRESS, 0, -1) . '"') . '
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              ' . tep_draw_password_field('password', '', 'class="form-control" id="password" placeholder="' . substr(ENTRY_PASSWORD, 0, -1) . '"') . '
            </div>
            <div class="form-group">
              <p><button class="btn btn-danger">' . IMAGE_BUTTON_LOGIN . '</button></p>
              <p><a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a></p>
            </div>
          </div>
        </div>
      </form>';
  }
  //===========================================================
  // RCI code start
  echo $cre_RCI->get('login', 'aboveloginbox');
  // RCI code end
?>
<!-- login_pwa -->
<?php
  echo $returning_customer_info;						   
  echo $cre_RCI->get('login', 'belowloginbox');
  // RCI code end
  if (B2B_ALLOW_CREATE_ACCOUNT == "true") {
    echo '<div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">' . HEADING_NEW_CUSTOMER . '</h3>
              </div>
              <div class="panel-body new-cust">
                <p>' . TEXT_NEW_CUSTOMER . '</p>
                <p>' . TEXT_NEW_CUSTOMER_INTRODUCTION . '</p>
                <a class="btn btn-primary" href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . IMAGE_BUTTON_CREATE_ACCOUNT . '</a>
              </div>
            </div>';
  }
  echo '</div>';
  if (B2B_REQUIRE_LOGIN == 'false') {
  ?>
  <script type="text/javascript">
    function check_guest_form() {
      var a = document.forms["account_edit"]["firstname"].value;
      if (a == null || a == "" || a.length < 2) {
        alert("<?php echo ENTRY_FIRST_NAME_ERROR; ?>");
        return false;
      }
      var b = document.forms["account_edit"]["lastname"].value;
      if (b == null || b == "" || b.length < 2) {
        alert("<?php echo ENTRY_LAST_NAME_ERROR; ?>");
        return false;
      }
      var c = document.forms["account_edit"]["email_address"].value;
      var atpos = c.indexOf("@");
      var dotpos = c.lastIndexOf(".");
      if (atpos < 1 || dotpos < atpos+2 || dotpos+2 >= c.length) {
        alert("<?php echo ENTRY_EMAIL_ADDRESS_BLANK_ERROR; ?>");
        return false;
      }
      var d = document.forms["account_edit"]["street_address"].value;
      if (d == null || d == "" || d.length < 5) {
        alert("<?php echo ENTRY_STREET_ADDRESS_ERROR; ?>");
        return false;
      }
      var e = document.forms["account_edit"]["city"].value;
      if (e == null || e == "" || e.length < 3) {
        alert("<?php echo ENTRY_CITY_ERROR; ?>");
        return false;
      }
      var f = document.forms["account_edit"]["state"].value;
      if (f == null || f == "" || f.length < 2) {
        alert("<?php echo ENTRY_STATE_ERROR_SELECT; ?>");
        return false;
      }
      var g = document.forms["account_edit"]["postcode"].value;
      if (g == null || g == "" || g.length < 4) {
        alert("<?php echo ENTRY_POST_CODE_ERROR; ?>");
        return false;
      }	
      var h = document.forms["account_edit"]["country"].value;
      if (h == null || h == "") {
        alert("<?php echo ENTRY_COUNTRY_ERROR; ?>");
        return false;
      }
      var i = document.forms["account_edit"]["telephone"].value;
      if (i == null || i == "" || i.length < 1) {
        alert("<?php echo ENTRY_TELEPHONE_NUMBER_ERROR; ?>");
        return false;
      }
      return true;
    }
  </script>
  <form name="account_edit" method="post" <?php echo 'action="' . tep_href_link('Order_Info_Process.php', '', 'SSL') . '"'; ?> onsubmit="return check_guest_form();">
    <input type="hidden" name="action" value="process" role="form">
    <div class="col-sm-6 margin-top-10">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo HEADING_CHECKOUT; ?></h3>
        </div>
        <div class="panel-body new-cust">
          <?php
            $email_address = tep_db_prepare_input(isset($_GET['email_address']));
            $account['entry_country_id'] = STORE_COUNTRY;
            if (file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ORDER_INFO_CHECK)) {
              require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ORDER_INFO_CHECK);
            } else {
              require(DIR_WS_MODULES . FILENAME_ORDER_INFO_CHECK);
            }
          ?>
          <input class="btn btn-danger" type="submit" value="<?php echo IMAGE_BUTTON_CONTINUE; ?>">
        </div>
      </div>
    </div>
  </form>
  <?php
  }
?>
