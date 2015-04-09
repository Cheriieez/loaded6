<?php 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('passwordforgotten', 'top');
  // RCI code eof
  echo tep_draw_form('password_forgotten', tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL'));
   
  if ($messageStack->size('password_forgotten') > 0) {
    echo $messageStack->output('password_forgotten');
  }
?>
<div class="row  margin-bottom-15">
  <div class="col-sm-12">
    <h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADING_TITLE; ?></h1>
    <p class="padding-10"><?php echo TEXT_MAIN; ?></p>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo CATEGORY_RECOVERY_EMAIL; ?></h3>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-6">
            <label for="email_address" class="control-label"><?php echo ENTRY_EMAIL_ADDRESS; ?><span class="red">*</span></label>
            <?php echo tep_draw_input_field('email_address', '', 'class="form-control"'); ?>
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
      echo $cre_RCI->get('passwordforgotten', 'menu');
      // RCI code eof
    ?>
    <div class="form-group">
      <div class="col-sm-6 pull-left">
        <?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '" class="btn btn-primary">' . IMAGE_BUTTON_BACK . '</a>'; ?>
      </div>
      <div class="col-sm-6 text-right">
        <button class="btn btn-danger" type="submit"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
      </div>
    </div>
  </div>
</div>       
</form>
<?php 
  // RCI code start
  echo $cre_RCI->get('passwordforgotten', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>