<?php
  if (B2B_REQUIRE_LOGIN == 'true') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => TEXT_REQUIRE_LOGIN_MESSAGE_HEADING );
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
    $returning_customer_info = '<div class="col-sm-12 top10"><div class="col-sm-6">'.tep_draw_form('login', tep_href_link(FILENAME_LOGIN, '', 'SSL'),'post').tep_draw_hidden_field('action','process').'<div class="panel panel-default">
    <div class="panel-heading">
    <h3 class="panel-title">Returning Customer</h3>
    </div><!--/panel-heading-->
    <div class="panel-body">
    <p>I am a returning customer.</p>
    <div class="form-group">
    <label for="exampleInputEmail1">E-Mail Address:</label>
    '. tep_draw_input_field('email_address','','class="form-control" id="exampleInputEmail1" placeholder="Email"').'
    </div><!--/form-group-->
    <div class="form-group">
    <label for="exampleInputPassword1">Password:</label>
    '. tep_draw_password_field('password','','class="form-control" id="exampleInputPassword1" placeholder="Password"') .'
    </div><!--/form-group-->
    <div class="form-group">
    <p><button class="btn btn-danger">Sign In</button></p>
    <p>
    <a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">Password forgotten? Click here.</a>
    </p>
    </div><!--/form-group-->
    </div>
    </div><!--/panel-->
    </form>
    </div>';
  }
  //===========================================================
  // RCI code start
  echo $cre_RCI->get('login', 'aboveloginbox');
  echo $returning_customer_info;
  // RCI code end

  //EOF: MaxiDVD Returning Customer Info SECTION
  //===========================================================

  // RCI code start
  echo $cre_RCI->get('login', 'belowloginbox');
  // RCI code end

  if (B2B_ALLOW_CREATE_ACCOUNT == "true") {
    echo '<div class="col-sm-6">
    <div class="panel panel-default">
    <div class="panel-heading">
    <h3 class="panel-title">New Customer</h3>
    </div><!--/panel-heading-->
    <div class="panel-body new-cust">
    <p>I am a new customer.</p>
    <p>By creating an account with Big Rig Chrome Shop you will be able to shop faster, be up to date on an order status, and keep track of the orders you have previously made.</p>
    <a class="btn btn-primary" href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_create_account.gif', IMAGE_BUTTON_CREATE_ACCOUNT) . '</a>
    </div><!--/panel-body-->
    </div><!--/panel-->
    </div></div>';
  }
?>