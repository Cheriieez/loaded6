<?php 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accountedit', 'top');
  
  // RCI code eof
  echo tep_draw_form('account_edit', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', 'onSubmit="return check_form(account_edit);" class="form-horizontal"') . tep_draw_hidden_field('action', 'process'); 
?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo MY_ACCOUNT_TITLE; ?></h1>
<div class="clearfix"></div>
<p class="text-right"><span class="red">*</span><?php echo TEXT_REQUIRED_INFORMATION; ?></p>
<?php if ($messageStack->size('account_edit') > 0) { echo $messageStack->output('account_edit'); } ?>
<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo MY_ACCOUNT_TITLE; ?></h3>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-6">
            <label for="firstname" class="control-label"><?php echo ENTRY_FIRST_NAME; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('firstname', $account['customers_firstname'], 'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '': ''); ?>
          </div>
          <div class="col-sm-6">
            <label for="lastname" class="control-label"><?php echo ENTRY_LAST_NAME; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('lastname', $account['customers_lastname'], 'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '': ''); ?>
          </div>
          <div class="col-sm-6">
            <label for="email_address" class="control-label"><?php echo ENTRY_EMAIL_ADDRESS; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('email_address', $account['customers_email_address'], 'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '': ''); ?>
          </div>
          <div class="col-sm-6">
            <label for="telephone" class="control-label"><?php echo ENTRY_TELEPHONE_NUMBER; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('telephone', $account['entry_telephone'], 'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '': ''); ?>
          </div>
          <div class="col-sm-6">
            <label for="fax" class="control-label"><?php echo ENTRY_FAX_NUMBER; ?> <span class="red">*</span></label>
            <?php echo tep_draw_input_field('fax', $account['entry_fax'], 'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '': ''); ?>
          </div>          
        </div>
      </div>
    </div>
    <?php if (ACCOUNT_COMPANY == 'true') { ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo CATEGORY_COMPANY; ?></h3>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-6">
            <label for="company" class="control-label"><?php echo ENTRY_COMPANY; ?></label>
            <?php echo tep_draw_input_field('company', $account['entry_company'], 'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '': ''); ?>
          </div>
          <div class="col-sm-6">
            <label for="company_tax_id" class="control-label"><?php echo ENTRY_COMPANY_TAX_ID; ?></label>
            <?php echo tep_draw_input_field('company_tax_id', $account['entry_company_tax_id'], 'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '': ''); ?>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
<div class="container-fluid form-group">
  <div class="row">
    <div class="col-sm-6 col-mg-6 col-lg-6 pull-left">
      <button class="btn btn-primary" onclick="location.href='<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>';"><?php echo IMAGE_BUTTON_BACK; ?></button>
    </div>
    <div class="col-sm-6 col-mg-6 col-lg-6 text-right">
      <button class="btn btn-danger"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
    </div>
    <div class="clear-fix"></div>
  </div>         
</div>
</form>
<?php
  // RCI code start
  echo $cre_RCI->get('accountedit', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>