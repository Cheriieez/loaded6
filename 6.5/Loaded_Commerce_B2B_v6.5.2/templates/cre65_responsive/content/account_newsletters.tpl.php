<?php 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accountnewsletters', 'top');
  // RCI code eof    
  echo tep_draw_form('account_newsletter', tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); 
?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADING_TITLE; ?></h1>
<div class="clearfix"></div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo MY_NEWSLETTERS_TITLE; ?></h3>
  </div>
  <div class="panel-body">
    <div class="col-sm-1"><?php echo tep_draw_checkbox_field('newsletter_general', '1', (($newsletter['customers_newsletter'] == '1') ? true : false)); ?></div>
    <div class="col-sm-3"><b><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER; ?></b></div>
    <div class="col-sm-8"><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION; ?></div>
  </div>
</div> 
<?php
  // RCI code start
  echo $cre_RCI->get('accountnewsletters', 'menu');
  // RCI code eof   
?>
<div class="col-sm-6"><?php echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . IMAGE_BUTTON_BACK . '</a>'; ?></div><div class="col-sm-6"><button class="btn btn-danger pull-right"><?php echo BUTTON_CONTINUE; ?></button></div>
</form>
<?php
  // RCI code start
  echo $cre_RCI->get('accountnewsletters', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof   
?>