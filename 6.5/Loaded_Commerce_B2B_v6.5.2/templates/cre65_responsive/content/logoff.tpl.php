<?php 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('logoff', 'top');
  // RCI code eof
?>    
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADER_TITLE_LOGOFF; ?></h1>
<div class="clearfix"></div>
<p class="padding-15"><?php echo TEXT_MAIN; ?></p>
<div class="row">
  <div class="col-sm-12 text-right"><a href="index.php" class="btn btn-primary"><?php echo IMAGE_BUTTON_CONTINUE; ?></a></div>
</div>
<?php 
  // RCI code start
  echo $cre_RCI->get('logoff', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>