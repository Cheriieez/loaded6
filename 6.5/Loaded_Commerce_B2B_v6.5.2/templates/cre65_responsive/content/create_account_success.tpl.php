<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('createaccountsuccess', 'top');
// RCI code eof
?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt">Your Account Has Been Created!</h1>
<div class="clearfix"></div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Your Account Has Been Created!</h3>
  </div>
  <div class="panel-body">
	  <?php                    
      if ((defined(ACCOUNT_EMAIL_CONFIRMATION)) && (ACCOUNT_EMAIL_CONFIRMATION == 'true')) {
        echo TEXT_ACCOUNT_CREATED_NEEDS_VALIDATE; 
      } else {
        echo TEXT_ACCOUNT_CREATED_NO_VALIDATE; 
      }
      ?>
  </div>
</div>
<?php echo $cre_RCI->get('createaccountsuccess', 'menu'); ?>
<div class="row">
    <div class="col-sm-12 text-right"><?php echo '<a href="' . $origin_href . '" class="btn btn-primary">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></div>
</div>
<?php 
// RCI code start
echo $cre_RCI->get('createaccountsuccess', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>