<?php
/*
  $Id: address_book_details.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/
  if (!isset($process)) $process = false;  
?>
<!--<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><b><?php //echo NEW_ADDRESS_TITLE; ?></b></td>
        <td class="inputRequirement" align="right"><?php //echo FORM_REQUIRED_INFORMATION; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td>-->
          <div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_FIRST_NAME; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('firstname', (isset($entry['entry_firstname']) ? $entry['entry_firstname'] : ''),'class="form-control"'); ?></div>
          </div>        
          <div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_LAST_NAME; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('lastname', (isset($entry['entry_lastname']) ? $entry['entry_lastname'] : ''),'class="form-control"'); ?></div>
          </div>        
          <div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('email_address', (isset($entry['entry_email_address']) ? $entry['entry_email_address'] : ''),'class="form-control"'); ?></div>
          </div>        
          <div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('telephone', (isset($entry['entry_telephone']) ? $entry['entry_telephone'] : ''),'class="form-control"'); ?></div>
          </div>        
          <div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_FAX_NUMBER; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('fax', (isset($entry['entry_fax']) ? $entry['entry_fax'] : ''),'class="form-control"'); ?></div>
          </div> 
<div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_COMPANY; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('company', (isset($entry['entry_company']) ? $entry['entry_company'] : ''),'class="form-control"'); ?></div>
          </div>           
<div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_STREET_ADDRESS; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('street_address', (isset($entry['entry_street_address']) ? $entry['entry_street_address'] : ''),'class="form-control"'); ?></div>
          </div>    
<div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo 'Address2'; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('suburb', (isset($entry['entry_suburb']) ? $entry['entry_suburb'] : ''),'class="form-control"'); ?></div>
          </div>   
<div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_POST_CODE; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('postcode', (isset($entry['entry_postcode']) ? $entry['entry_postcode'] : ''),'class="form-control"'); ?></div>
          </div>  
<div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_CITY; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('city', (isset($entry['entry_city']) ? $entry['entry_city'] : ''),'class="form-control"'); ?></div>
          </div>  
<div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_STATE; ?></label>
            <div class="col-sm-9"><span id="SHOWSTATE"><?php
	$zonesqry	=	tep_db_query("SELECT zone_country_id FROM `zones` GROUP BY `zone_country_id`");
	while($zonesres	=	tep_db_fetch_array($zonesqry))
	{
		$exists_zones[]	=	$zonesres['zone_country_id'];
	}		
	$state	=	tep_get_zone_name((isset($entry['entry_country_id']) ? $entry['entry_country_id'] : 0), (isset($entry['entry_zone_id']) ? $entry['entry_zone_id'] : 0 ), (isset($entry['entry_state']) ? $entry['entry_state'] : 0));
	  $country=($entry['entry_country_id'] != '')?$entry['entry_country_id']:'';
      //if ($country == '223') {
	  if(in_array($country,$exists_zones)){
        $zones_array = array();
        $zones_array[] = array('id' => '', 'text' => '');
        $zones_query = tep_db_query("select zone_code,zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array, $state, 'class="form-control"');
      } else {
        echo tep_draw_input_field('state',$entry['entry_country_id'],'class="form-control"');
      }


?></span></div>
          </div>  
<div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo ENTRY_COUNTRY; ?></label>
            <div class="col-sm-9"><?php echo tep_get_country_list('country', (isset($entry['entry_country_id']) ? $entry['entry_country_id'] : ''),'class="form-control" onchange="checkstate(this.value);"'); ?></div>
          </div>
<?php
  if ((isset($_GET['edit']) && (isset($_SESSION['customer_default_address_id'])) &&  ($_SESSION['customer_default_address_id'] != $_GET['edit'])) || (isset($_GET['edit']) == false) ) {
?><div class="form-group">
            <label class="col-sm-3" for="exampleInputEmail1"><?php echo SET_AS_PRIMARY; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_checkbox_field('primary', 'on', false, 'id="primary" class="form-control"'); ?></div>
          </div>
<?php
  }
?>
