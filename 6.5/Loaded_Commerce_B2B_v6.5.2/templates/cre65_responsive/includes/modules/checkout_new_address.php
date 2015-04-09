<?php
/*
  $Id: checkout_new_address.php,v 1.1.1.1 2004/03/04 23:41:09 ccwjr Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/
  if (!isset($process)) $process = false;
?>
<script>
 function checkstate(country)
{
	var url="getstate.php?country="+country;
	$.get(url, function(data) {
	$('#SHOWSTATE').html(data);
	});
}
</script>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_FIRST_NAME; ?></label>
    <div class="col-sm-9"><?php echo tep_draw_input_field('firstname','','class="form-control" id="exampleInputEmail1" placeholder="Enter First Name"'); ?></div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_LAST_NAME; ?></label>
    <div class="col-sm-9"><?php echo tep_draw_input_field('lastname','','class="form-control" id="exampleInputEmail1" placeholder="Enter Last Name"'); ?></div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
    <div class="col-sm-9"><?php echo tep_draw_input_field('email_address','','class="form-control" id="exampleInputEmail1" placeholder="Enter Email"'); ?></div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
    <div class="col-sm-9"><?php echo tep_draw_input_field('telephone','','class="form-control" id="exampleInputEmail1" placeholder="Enter Phone Number"'); ?></div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_FAX_NUMBER; ?></label>
    <div class="col-sm-9"><?php echo tep_draw_input_field('fax','','class="form-control" id="exampleInputEmail1" placeholder="Enter Fax Number"'); ?></div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_STREET_ADDRESS; ?></label>
   <div class="col-sm-9"> <?php echo tep_draw_input_field('street_address','','class="form-control" id="exampleInputEmail1" placeholder="Enter Address"'); ?></div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo 'Address2'; ?></label>
    <div class="col-sm-9"><?php echo tep_draw_input_field('suburb','','class="form-control" id="exampleInputEmail1" placeholder="Enter Address2"'); ?></div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_POST_CODE; ?></label>
    <div class="col-sm-9"><?php echo tep_draw_input_field('postcode','','class="form-control" id="exampleInputEmail1" placeholder="Enter Zip Code"'); ?></div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_CITY; ?></label>
    <div class="col-sm-9"><?php echo tep_draw_input_field('city','','class="form-control" id="exampleInputEmail1" placeholder="Enter City"'); ?></div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_STATE; ?></label>
    <span class="col-sm-9" id="SHOWSTATE">
    <?php
       $zones_array = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '223' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
		echo tep_draw_pull_down_menu('state', $zones_array,'','class="form-control"');
    ?>
    </span>
  </div>
  <div class="form-group">
    <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_COUNTRY; ?></label>
   <div class="col-sm-9"> <?php echo tep_get_country_list('country','223','class="form-control" onchange="javascript:checkstate(this.value)"'); ?></div>
  </div>

