<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('advancedsearch', 'top');
// RCI code eof 
echo tep_draw_form('advanced_search', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get', 'onSubmit="return check_form(this);" class="form-horizontal"') . tep_hide_session_id(); ?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADING_TITLE_1; ?></h1>
<div class="clearfix"></div>
<div class="table-responsive">
<table class="table distable">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
<?php
  if ($messageStack->size('search') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('search'); ?></td>
      </tr>
      <tr>
        <td><?php //echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="infoBox">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
              <tr>
                <td>&nbsp;</td>
              </tr>
                             <td class="boxText"><div class="form-group"><label for="inputEmail3" class="col-sm-2 control-label"><?php echo HEADING_SEARCH_CRITERIA; ?></label><div class="col-sm-10"><?php echo tep_draw_input_field('keywords', '', 'class="form-control" style="width: 100%"'); ?></div>
  </div></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"></td>
            <td class="smallText" align="right"><?php echo '<input class="btn btn-danger" type="submit" value="Search">'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="mtop30">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="fieldKey"><div class="form-group"><label for="inputEmail3" class="col-sm-2 control-label"><?php echo ENTRY_CATEGORIES; ?></label><div class="col-sm-10"><?php echo tep_draw_pull_down_menu('categories_id', tep_get_categories(array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES))),'','class="form-control"'); ?></div>
  </div></td>
              </tr>
              <tr>
                <td class="fieldKey"><div class="form-group"><label for="inputEmail3" class="col-sm-2 control-label"><?php echo ENTRY_MANUFACTURERS; ?></label><div class="col-sm-10"><?php echo tep_draw_pull_down_menu('manufacturers_id', tep_get_manufacturers(array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS))),'','class="form-control"'); ?></div>
  </div></td>
              </tr>
              <tr>
                <td class="fieldKey"><div class="form-group"><label for="inputEmail3" class="col-sm-2 control-label"><?php echo ENTRY_PRICE_FROM; ?></label><div class="col-sm-10"><?php echo tep_draw_input_field('pfrom','','class="form-control"'); ?></div>
  </div></td>
              </tr>
              <tr>
                <td class="fieldKey"><div class="form-group"><label for="inputEmail3" class="col-sm-2 control-label"><?php echo ENTRY_PRICE_TO; ?></label><div class="col-sm-10"><?php echo tep_draw_input_field('pto','','class="form-control"'); ?></div>
  </div></td>
              </tr>
              <tr>
                <td class="fieldKey"><div class="form-group"><label for="inputEmail3" class="col-sm-2 control-label"><?php echo ENTRY_DATE_FROM; ?></label><div class="col-sm-10"><?php echo tep_draw_input_field('dfrom', DOB_FORMAT_STRING, 'class="form-control" onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"'); ?></div>
  </div></td>
              </tr>
              <tr>
                <td class="fieldKey"><div class="form-group"><label for="inputEmail3" class="col-sm-2 control-label"><?php echo ENTRY_DATE_TO; ?></label><div class="col-sm-10"><?php echo tep_draw_input_field('dto', DOB_FORMAT_STRING, 'class="form-control" onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"'); ?></div>
  </div></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
// RCI code start
echo $cre_RCI->get('advancedsearch', 'menu');
// RCI code eof 
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
    </table></div></form>
<?php
// RCI code start
echo $cre_RCI->get('advancedsearch', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof 
?>