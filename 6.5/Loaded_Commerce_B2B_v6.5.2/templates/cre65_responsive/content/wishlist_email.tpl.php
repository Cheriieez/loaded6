<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('wishlistemail', 'top');
// RCI code eof
		echo '<form method="post" action="wishlist_email.php?products_id=0&action=process" name="email_wish" class="form-horizontal">';
        echo tep_draw_hidden_field('products_name', (isset($product_info['products_name']) ? $product_info['products_name'] : ''));
?>

<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADING_TITLE; ?></h1>
<div class="clearfix"></div>
<p><?php if ($messageStack->size('friend') > 0) { echo $messageStack->output('friend'); }?></p>

<div class="panel panel-default mtop15"> 	
  <div class="panel-heading">
    <h3 class="panel-title"><?=FORM_TITLE_CUSTOMER_DETAILS?></h3>
  </div>
  <div class="panel-body">
  	<div class="form-group">
        <label class="col-sm-3" for="exampleInputEmail1"><?php echo FORM_FIELD_CUSTOMER_NAME; ?>
        </label>
        <div class="col-sm-9"><?php echo $from_name . tep_draw_hidden_field('from_name', $from_name,'class="form-control"'); ?></div>
    </div>
  	<div class="form-group">
        <label class="col-sm-3" for="exampleInputEmail1"><?php echo FORM_FIELD_CUSTOMER_EMAIL; ?>
        </label>
        <div class="col-sm-9"><?php echo $from_email_address . tep_draw_hidden_field('from_email_address', $from_email_address,'class="form-control"'); ?></div>
    </div>
  </div>
</div>
<div class="panel panel-default mtop15"> 	
  <div class="panel-heading">
    <h3 class="panel-title"><?=FORM_TITLE_FRIEND_DETAILS?></h3>
  </div>
  <div class="panel-body">
  	<div class="form-group">
        <label class="col-sm-3" for="exampleInputEmail1"><?php echo FORM_FIELD_FRIEND_NAME; ?>
        </label>
        <div class="col-sm-9"><?php echo tep_draw_input_field('to_name','','class="form-control"'); ?></div>
    </div>
  	<div class="form-group">
        <label class="col-sm-3" for="exampleInputEmail1"><?php echo FORM_FIELD_FRIEND_EMAIL; ?>
        </label>
        <div class="col-sm-9"><?php echo tep_draw_input_field('to_email_address',$friends_email_id,'class="form-control"'); ?></div>
    </div>
  </div>
</div>
<div class="panel panel-default mtop15"> 	
  <div class="panel-heading">
    <h3 class="panel-title"><?=FORM_FIELD_PRODUCTS?></h3>
  </div>
  <div class="panel-body">
  		<?php echo nl2br($wishliststring); ?>
  </div>
</div>
<div class="panel panel-default mtop15"> 	
  <div class="panel-heading">
    <h3 class="panel-title"><?=FORM_TITLE_FRIEND_MESSAGE?></h3>
  </div>
  <div class="panel-body">
  	<div class="form-group">
        <label class="col-sm-3" for="exampleInputEmail1"><?php echo FORM_TITLE_FRIEND_MESSAGE; ?>
        </label>
        <div class="col-sm-9"><?php echo tep_draw_textarea_field('message', 'soft', 40, 8, '','class="form-control"'); ?></div>
    </div>
  	
  </div>
</div>
<?php
// RCI code start
echo $cre_RCI->get('wishlistemail', 'menu');
// RCI code eof
?>
<div class="col-sm-6"><?php if ($valid_product == "true") { echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$_GET['products_id']) . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; }?></div>

<div class="col-sm-6"><button class="btn btn-danger pull-right">Continue</button></div>
</form>
<?php
// RCI code start
echo $cre_RCI->get('wishlistemail', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>