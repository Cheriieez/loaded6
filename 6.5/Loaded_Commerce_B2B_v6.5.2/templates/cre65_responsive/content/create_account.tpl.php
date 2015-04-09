<?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('createaccount', 'top');
  // RCI code eof
  echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'onSubmit="return check_form(create_account);" enctype="multipart/form-data" class="form-horizontal"') . tep_draw_hidden_field('action', 'process'); 
?>
<h1 class="col-lg-12 gry_box2 y_clr mbot15"><?php echo HEADING_TITLE; ?></h1>
<p class="text-right">
  <span class="red">*</span> <?php echo TEXT_REQUIRED_INFORMATION; ?>
</p>
<?php
  if ($messageStack->size('create_account') > 0) {
    echo $messageStack->output('create_account');  
  }
?>
<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title"><?php echo CATEGORY_PERSONAL; ?></h3></div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-4">
            <label class="control-label" for="firstname"><?php echo ENTRY_FIRST_NAME; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('firstname', '', 'class="form-control"'); ?>
          </div>
          <div class="col-sm-4">
            <label class="control-label" for="lastname"><?php echo ENTRY_LAST_NAME; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('lastname', '', 'class="form-control"'); ?>
          </div>
          <div class="col-sm-4">
            <label class="control-label" for="email_address"><?php echo ENTRY_EMAIL_ADDRESS; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('email_address', '', 'class="form-control"'); ?>
          </div>
        </div>
      </div>
    </div>
    <?php if (ACCOUNT_COMPANY == 'true') { ?>
    <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title"><?php echo CATEGORY_COMPANY; ?></h3></div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-6">
            <label class="control-label" for="company"><?php echo ENTRY_COMPANY; ?></label>
            <?php echo tep_draw_input_field('company', '', 'class="form-control"'); ?>
          </div>
          <div class="col-sm-6">
            <label class="control-label" for="company_tax_id"><?php echo ENTRY_COMPANY_TAX_ID; ?></label>
            <?php echo tep_draw_input_field('company_tax_id', '', 'class="form-control"'); ?>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title"><?php echo CATEGORY_ADDRESS; ?></h3></div>
      <div class="panel-body">
        <div class="form-group">
          <?php
            if (ACCOUNT_SUBURB == 'true') {
              $addcolsize1 = '4';
            } else {
              $addcolsize1 = '6';
            }
          ?>
          <div class="col-sm-<?php echo $addcolsize1; ?>">
            <label class="control-label" for="street_address"><?php echo ENTRY_STREET_ADDRESS; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('street_address', '', 'class="form-control"'); ?>
          </div>
          <?php if (ACCOUNT_SUBURB == 'true') { ?>
          <div class="col-sm-<?php echo $addcolsize1; ?>">
            <label class="control-label" for="suburb"><?php echo ENTRY_SUBURB; ?></label>
            <?php echo tep_draw_input_field('suburb', '', 'class="form-control"'); ?>
          </div>
          <?php } ?>
          <div class="col-sm-<?php echo $addcolsize1; ?>">
            <label class="control-label" for="city"><?php echo ENTRY_CITY; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('city', '', 'class="form-control"'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php
            if (ACCOUNT_STATE == 'true') {
              $addcolsize2 = '4';
            } else {
              $addcolsize2 = '6';
            }
            if (ACCOUNT_STATE == 'true') {
          ?>
          <div class="col-sm-<?php echo $addcolsize2; ?>">
            <label class="control-label" for="state"><?php echo ENTRY_STATE; ?> <span class="red">*</span></label>
            <span id="SHOWSTATE">
              <?php
                $zones_array = array();
                $selected_zone = '';
                $zones_array[] = array('id' => '', 'text' => '');
                $zones_query = tep_db_query("select zone_name, zone_code from " . TABLE_ZONES . " where zone_country_id = '223' order by zone_name");
                while ($zones_values = tep_db_fetch_array($zones_query)) {
                  $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
                  if (strtolower($state) == strtolower($zones_values['zone_code']) || strtolower($state) == strtolower($zones_values['zone_name'])) {
                    $selected_zone = $zones_values['zone_name'];
                  }
                }
                echo tep_draw_pull_down_menu('state', $zones_array, $selected_zone, 'class="form-control"');
              ?>
            </span>
          </div>
          <?php } ?>
          <div class="col-sm-<?php echo $addcolsize2; ?>">
            <label class="control-label" for="postcode"><?php echo ENTRY_POST_CODE; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('postcode', '', 'class="form-control" maxlength="10"'); ?>
          </div>
          <div class="col-sm-<?php echo $addcolsize2; ?>">
            <label class="control-label" for="country"><?php echo ENTRY_COUNTRY; ?> <span class="red">*</span></label>
            <?php echo tep_get_country_list('country', '223', 'class="form-control", onchange="checkstate(this.value);"'); ?>
          </div>
        </div>      
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title"><?php echo CATEGORY_CONTACT; ?></h3></div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-6">
            <label class="control-label" for="telephone"><?php echo ENTRY_TELEPHONE_NUMBER; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('telephone', '', 'class="form-control"'); ?>
          </div>
          <div class="col-sm-6">
            <label class="control-label" for="fax"><?php echo ENTRY_FAX_NUMBER; ?></label>
            <?php echo tep_draw_input_field('fax', '', 'class="form-control"'); ?>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo CATEGORY_OPTIONS; ?></h3>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-12">
            <div class="checkbox">
              <label class="control-label" for="newsletter"><?php echo ENTRY_NEWSLETTER; ?></label>
              <?php echo tep_draw_checkbox_field('newsletter', '1', true,''); ?>
              <?php echo '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo CATEGORY_PASSWORD; ?></h3>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-6">
            <label for="password" class="control-label"><?php echo ENTRY_PASSWORD; ?><span class="red">*</span></label>
            <?php echo tep_draw_password_field('password', '', 'class="form-control"'); ?>
          </div>
          <div class="col-sm-6">
            <label for="confirmation" class="control-label"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?><span class="red">*</span></label>
            <?php echo tep_draw_password_field('confirmation', '', 'class="form-control"'); ?>
          </div>
        </div> 
      </div>
    </div>
    <?php
      if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On') {
        if (defined('VVC_CONTACT_US_ON_OFF') && VVC_CONTACT_US_ON_OFF == 'On'){
    ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo CATEGORY_VERIFICATION; ?></h3>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-6">
            <label for="visual_verify_code" class="control-label"><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('visual_verify_code', '', 'class="form-control"'); ?>
          </div>
          <div class="col-sm-6">
            <label for="visual_verify_image" class="control-label">Verification Image</label><br />
            <?php
              $visual_verify_code = ""; 
              for ($i = 1; $i <= rand(3,6); $i++){
                $visual_verify_code = $visual_verify_code . substr(VISUAL_VERIFY_CODE_CHARACTER_POOL, rand(0, strlen(VISUAL_VERIFY_CODE_CHARACTER_POOL)-1), 1);
              }
              $vvcode_oscsid = tep_session_id();
              tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . $vvcode_oscsid . "'");
              $sql_data_array = array('oscsid' => $vvcode_oscsid, 'code' => $visual_verify_code);
              tep_db_perform(TABLE_VISUAL_VERIFY_CODE, $sql_data_array);
              $visual_verify_code = "";
              echo('<img class="margin-top-3" src="' . FILENAME_VISUAL_VERIFY_CODE_DISPLAY . '?vvc=' . $vvcode_oscsid . '" alt="' . VISUAL_VERIFY_CODE_CATEGORY . '">');
              
              // reCapctha for later
              // $publickey = "6LfiNf0SAAAAADiDgTgDgyksqu-rfykFMEaPzrjS"; // you got this from the signup page
              // echo recaptcha_get_html($publickey);             
            ?>            
          </div>
          <div class="clearfix"></div>
        </div> 
      </div>
    </div>
    <?php
        }
      }
      // RCI code start
      echo $cre_RCI->get('createaccount', 'menu');
      // RCI code eof
    ?>
    <div class="form-group">
      <div class="col-sm-12 text-right">
        <button class="btn btn-danger"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
      </div>
    </div>
  </div>
</div>
</form>
<?php
  // RCI code start
  echo $cre_RCI->get('createaccount', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>
