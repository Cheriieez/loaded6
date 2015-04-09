<?php 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accountpassword', 'top');
  // RCI code eof   
  echo tep_draw_form('account_password', tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'), 'post', 'class="form-horizontal" onSubmit="return check_form(account_password);"') . tep_draw_hidden_field('action', 'process'); 
?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADING_TITLE; ?></h1>
<div class="clearfix"></div>
<p><?php echo $messageStack->output('account_password'); ?></p>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo MY_PASSWORD_TITLE; ?></h3>
  </div>
  <div class="panel-body">
    <div class="form-group">
      <label class="col-sm-3 control-label"><?php echo ENTRY_PASSWORD_CURRENT; ?></label>
      <div class="col-sm-7"><?php echo tep_draw_password_field('password_current', '', 'class="form-control"') . '</div>' . (tep_not_null(ENTRY_PASSWORD_CURRENT_TEXT) ? '<div class="col-sm-2 red-txt">' . ENTRY_PASSWORD_CURRENT_TEXT . '</div>' : ''); ?>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label"><?php echo ENTRY_PASSWORD_NEW; ?></label>
      <div class="col-sm-7"><?php echo tep_draw_password_field('password_new', '', 'class="form-control"') . '</div>' . (tep_not_null(ENTRY_PASSWORD_NEW_TEXT) ? '<div class="col-sm-2 red-txt">' . ENTRY_PASSWORD_NEW_TEXT . '</div>' : ''); ?>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></label>
      <div class="col-sm-7"><?php echo tep_draw_password_field('password_confirmation', '', 'class="form-control"') . '</div>' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<div class="col-sm-2  red-txt">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</div>' : ''); ?>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
<div class="col-sm-12">
  <div class="col-sm-6"><?php echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></div>
  <div class="col-sm-6 col-sm-6 text-right"><button class="btn btn-danger pull-right">Continue</button></div>
</div>
<?php
  // RCI code start
  echo $cre_RCI->get('accountpassword', 'menu');
  // RCI code eof   
?>
</form>
<?php
  // RCI code start
  echo $cre_RCI->get('accountpassword', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof   
?>