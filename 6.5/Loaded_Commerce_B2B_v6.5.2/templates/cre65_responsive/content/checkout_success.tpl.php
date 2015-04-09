<div class="col-sm-12">
  <?php echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL'), 'post', 'enctype="multipart/form-data"'); ?>
  <p class="text-success bg-success padding-10"><?php echo HEADING_TITLE; ?></p>
  <?php
    if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ADD_CHECKOUT_SUCCESS)) {
      require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ADD_CHECKOUT_SUCCESS);
    } else {
      require(DIR_WS_MODULES . FILENAME_ADD_CHECKOUT_SUCCESS);
    }
  ?>
  <?php
    if (MODULE_CHECKOUT_SUCCESS_INSTALLED) {
      $checkout_success_modules->process();
      echo $checkout_success_modules->output();
    }
  ?>
  <?php echo $cre_RCI->get('checkoutsuccess', 'insideformabovebuttons');  ?>
  <div class="row margin-bottom-20">
    <div class="col-sm-6"><?php echo '<a class="btn btn-danger" href="javascript:popupResponsiveWindow(\'' . tep_href_link('printresponsiveorder.php', tep_get_all_get_params(array('order_id')) . 'order_id=' . (int)$_GET['order_id'].'&customer_id='.(int)$customer_id, 'NONSSL') . '\')">' . tep_template_image_button('button_printorder.gif', IMAGE_BUTTON_PRINT_ORDER) . '</a>'; ?></div>
    <div class="col-sm-6 text-right"><button class="btn btn-primary">Continue</button></div>
    <div class="clearfix"></div>
  </div>
  <?php echo $cre_RCI->get('checkoutsuccess', 'insideformbelowbuttons'); ?>
  <div class="clearfix margin-top-30"></div>
  <div class="stepwizard">
    <div class="stepwizard-row">
      <div class="stepwizard-step">
        <button class="btn btn-danger btn-circle" type="button">1</button>
        <p><?php echo 'Delivery Information'; ?></p>
      </div>
      <div class="stepwizard-step">
        <button class="btn btn-danger btn-circle" type="button">2</button>
        <p><?php echo CHECKOUT_BAR_PAYMENT; ?></p>
      </div>
      <div class="stepwizard-step">
        <button class="btn btn-danger btn-circle" type="button">3</button>
        <p><?php echo CHECKOUT_BAR_CONFIRMATION; ?></p>
      </div>
      <div class="stepwizard-step">
        <button class="btn btn-primary btn-circle" type="button">4</button>
        <p><?php echo CHECKOUT_BAR_FINISHED; ?></p>
      </div> 
    </div>
  </div>
  <?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
  </form>
</div>
<?php 
  // RCI code start
  echo $cre_RCI->get('checkoutsuccess', 'bottom', false);
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>