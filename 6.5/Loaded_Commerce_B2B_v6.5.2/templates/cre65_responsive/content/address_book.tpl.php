<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('addressbook', 'top');
// RCI code eof   
?>
<h1 class="col-sm-12 gry_box2 y_clr con_txt"><?php echo HEADING_TITLE; ?></h1>
<div class="clearfix"></div>
<?php
  if ($messageStack->size('addressbook') > 0) {
   		echo '<p>'.$messageStack->output('addressbook').'</p>'; 
   }
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo PRIMARY_ADDRESS_TITLE; ?></h3>
    </div>
    <div class="panel-body">
    	<?php echo PRIMARY_ADDRESS_DESCRIPTION; ?>
        <div class="row mtop15"><div class="col-sm-12"><?php echo tep_address_label($_SESSION['customer_id'], (isset($_SESSION['customer_default_address_id']) ? (int)$_SESSION['customer_default_address_id'] : 0), true, ' ', '<br>'); ?></div></div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo 'Address Book Entries'; ?></h3>
    </div>
    <div class="panel-body">
        <?php
  $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' order by firstname, lastname");
  while ($addresses = tep_db_fetch_array($addresses_query)) {
    $format_id = tep_get_address_format_id($addresses['country_id']);

	echo '<div class="row mtop15"><div class="">';
	echo '<div class="col-sm-6">';
	echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); 	 
	if (isset($_SESSION['customer_default_address_id']) && $addresses['address_book_id'] == $_SESSION['customer_default_address_id'] ) echo '&nbsp;<small><i>' . PRIMARY_ADDRESS . '</i></small>'; 
	echo '<br>'.tep_address_format($format_id, $addresses, true, ' ', '<br>');
	echo '</div><div class="col-sm-6 text-right">';
	
	echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL') . '">' . tep_template_image_button('small_edit.gif', SMALL_IMAGE_BUTTON_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses['address_book_id'], 'SSL') . '">' . tep_template_image_button('small_delete.gif', SMALL_IMAGE_BUTTON_DELETE) . '</a>';
	
	echo '</div></div></div>';

  }
?>
        
    </div>
</div>
<div class="row">
  <div class="col-sm-6"><?php echo '<a class="btn btn-primary" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></div>
  <div class="col-sm-6 text-right"><?php if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) { echo '<a class="btn btn-danger " href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL') . '">' . tep_template_image_button('button_add_address.gif', IMAGE_BUTTON_ADD_ADDRESS) . '</a>';} ?></div>
</div>
<p class="padding-10"><?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></p>
    <?php
    // RCI code start
    echo $cre_RCI->get('addressbook', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof   
    ?>