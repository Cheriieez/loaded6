<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('addressbookprocess', 'top');
// RCI code eof   
if (!isset($_GET['delete'])) echo tep_draw_form('addressbook', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($_GET['edit']) ? 'edit=' . $_GET['edit'] : ''), 'SSL'), 'post', 'class="form-horizontal" onSubmit="return check_form(addressbook);"'); ?>

<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php if (isset($_GET['edit'])) { echo HEADING_TITLE_MODIFY_ENTRY; } elseif (isset($_GET['delete'])) { echo HEADING_TITLE_DELETE_ENTRY; } else { echo HEADING_TITLE_ADD_ENTRY; } ?></h1>
<div class="clearfix"></div>
<?php  if ($messageStack->size('addressbook') > 0) { ?><p><?php echo $messageStack->output('addressbook'); ?></p><?php } ?>
<?php  if (isset($_GET['delete'])) { ?>

<div class="panel panel-default"> 	
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo DELETE_ADDRESS_TITLE; ?></h3>
  </div>
  <div class="panel-body">
	<div class="col-sm-6"><?php echo DELETE_ADDRESS_DESCRIPTION; ?></div>
    <div class="col-sm-6 text-right"><?php echo tep_address_label($_SESSION['customer_id'], $_GET['delete'], true, ' ', '<br>'); ?></div>
  </div>
</div>

<?php
    // RCI code start
    echo $cre_RCI->get('addressbookprocess', 'menu');
    // RCI code eof 
?>
	<div class="col-sm-6"><?php echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></div>
    <div class="col-sm-6"><?php echo '<a class="btn btn-danger pull-right" href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'] . '&amp;action=deleteconfirm', 'SSL') . '">' . tep_template_image_button('button_delete.gif', IMAGE_BUTTON_DELETE) . '</a>'; ?></div>

<?php
  } else {

      // include(DIR_WS_MODULES . FILENAME_ADDRESS_BOOK_DETAILS);
        if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ADDRESS_BOOK_DETAILS)) {
          require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ADDRESS_BOOK_DETAILS);
        } else {
          require(DIR_WS_MODULES . FILENAME_ADDRESS_BOOK_DETAILS);
        }

    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
	
?>
	  <div class="col-sm-6"><?php echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></div>
      <div class="col-sm-6"><?php echo tep_draw_hidden_field('action', 'update') . tep_draw_hidden_field('edit', $_GET['edit']) . '<button class="btn btn-danger pull-right">Update</button>'; ?></div>

<?php
    } else {
      if (sizeof($navigation->snapshot) > 0) {
        $back_link = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
      } else {
        $back_link = tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
      }
?>
<?php
// RCI code start
echo $cre_RCI->get('addressbookprocess', 'menu');
// RCI code eof 

?>
	  <div class="col-sm-6"><?php echo '<a href="' . $back_link . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></div>
      <div class="col-sm-6"><?php echo tep_draw_hidden_field('action', 'process') . tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></div>
<?php
    }
  }
  if (!isset($_GET['delete'])) echo '</form>';

// RCI code start
echo $cre_RCI->get('addressbookprocess', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof 
?>