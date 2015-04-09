<?php 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('contactus', 'top');
  // RCI code eof
  echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send', 'SSL'), 'post', 'class="form-horizontal"'); ?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADING_TITLE; ?></h1>
<div class="clearfix"></div>
<?php
  if ($messageStack->size('contact') > 0) { 
    echo '<p>' . $messageStack->output('contact') . '</p>'; 
  } 
  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
    echo '<p class="margin-top-20 margin-top-20">' .  TEXT_SUCCESS . '</p>'; 
    echo '<a class="btn btn-success" href="' . tep_href_link(FILENAME_DEFAULT) . '">' . IMAGE_BUTTON_CONTINUE . '</a>';
  } else {
  ?>
  <div class="margin-10">
    <?php 
      if (ACCOUNT_COMPANY == 'true') {
    ?>
    <div class="form-group">
      <label class="col-sm-3" for="company"><?php echo ENTRY_COMPANY; ?></label>
      <div class="col-sm-9"><?php echo tep_draw_input_field('company', '', 'class="form-control"'); ?></div>
      <div class="clearfix"></div>   
    </div>
    <?php
      }
    ?>
    <div class="form-group">
      <label class="col-sm-3" for="name"><?php echo ENTRY_NAME; ?></label>
      <div class="col-sm-9"><?php echo tep_draw_input_field('name', '', 'class="form-control"'); ?></div>
      <div class="clearfix"></div>
    </div>
    <div class="form-group">
      <label class="col-sm-3" for="telephone"><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
      <div class="col-sm-9"><?php echo tep_draw_input_field('telephone', '', 'class="form-control"'); ?></div>
      <div class="clearfix"></div>
    </div>
    <div class="form-group">
      <label class="col-sm-3" for="email"><?php echo ENTRY_EMAIL; ?></label>
      <div class="col-sm-9"><?php echo tep_draw_input_field('email', '', 'class="form-control"'); ?></div>
      <div class="clearfix"></div>
    </div>
    <?php
      $topic_array = array();
      $topic_array = array(array('id' => ENTRY_TOPIC_1, 'text' => ENTRY_TOPIC_1), 
                           array('id' => ENTRY_TOPIC_2, 'text' => ENTRY_TOPIC_2), 
                           array('id' => ENTRY_TOPIC_3, 'text' => ENTRY_TOPIC_3),
                           array('id' => ENTRY_TOPIC_4, 'text' => ENTRY_TOPIC_4)
                           );
    ?>
    <div class="form-group">
      <label class="col-sm-3" for="topic"><?php echo ENTRY_TOPIC; ?></label>
      <div class="col-sm-9"><?php echo tep_draw_pull_down_menu('topic', $topic_array, '', 'class="form-control"'); ?></div>
      <div class="clearfix"></div>
    </div>
    <div class="form-group">
      <label class="col-sm-3" for="subject"><?php echo ENTRY_SUBJECT; ?></label>
      <div class="col-sm-9"><?php echo tep_draw_input_field('subject', '', 'size="60" class="form-control"'); ?></div>
      <div class="clearfix"></div>
    </div>
    <div class="form-group">
      <label class="col-sm-3" for="enquiry"><?php echo ENTRY_ENQUIRY; ?></label>
      <div class="col-sm-9"><?php echo tep_draw_textarea_field('enquiry', 'soft', 30, 5, '', 'class="form-control"'); ?></div>
      <div class="clearfix"></div>
    </div>
    <?php
      if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On') {
        if (defined('VVC_CONTACT_US_ON_OFF') && VVC_CONTACT_US_ON_OFF == 'On'){
        ?>
        <div class="form-group">
          <label class="col-sm-3" for="visual_verify_code"><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?></label>
          <div class="col-sm-9">
            <div class="row col-sm-6">
              <?php echo tep_draw_input_field('visual_verify_code', '', 'class="form-control"'); ?>
            </div>
            <div class="text-right margin-top-5">
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
              echo('<img src="' . FILENAME_VISUAL_VERIFY_CODE_DISPLAY . '?vvc=' . $vvcode_oscsid . '" alt="' . VISUAL_VERIFY_CODE_CATEGORY . '">');
              
              // reCapctha for later
              // $publickey = "6LfiNf0SAAAAADiDgTgDgyksqu-rfykFMEaPzrjS"; // you got this from the signup page
              // echo recaptcha_get_html($publickey);             
            ?>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php
        }
      }
    ?>
    <div class="form-group">
      <label class="col-sm-3" for="urgent"><?php echo ENTRY_URGENT; ?></label>
      <div class="col-sm-9"><?php echo tep_draw_checkbox_field('urgent', '', '', '', 'class="form-control"'); ?></div>
      <div class="clearfix"></div>
    </div>
    <div class="form-group">
      <label class="col-sm-3" for="self"><?php echo ENTRY_SELF; ?></label>
      <div class="col-sm-9"><?php echo tep_draw_checkbox_field('self', '', '', '', 'class="form-control"'); ?></div>
      <div class="clearfix"></div>
    </div>
    <?php
      // RCI code start
      echo $cre_RCI->get('contactus', 'menu');
      // RCI code eof
    ?>
    <div class="form-group">
      <label class="col-sm-3" for="continue"></label>
      <div class="col-sm-9 text-right"><button class="btn btn-danger"><?php echo IMAGE_BUTTON_CONTINUE; ?></button></div>
      <div class="clearfix"></div>
    </div>
  </div>
  <?php
  }
?>
</form>
<?php 
  // RCI code start
  echo $cre_RCI->get('contactus', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>
