<?php
/*
  $Id: customers_improved.php, v1.3b 2006/04/26 23:12:52 kremit Exp $

Customers Improved v1.4.2

Copyright (c) 2005 Wesley Haines
<kremit AT wrpn.net>, http://wrpn.net/


  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if(isset($_POST['orderby'])) $orderby = tep_db_prepare_input($_POST['orderby']);
  if(isset($_POST['sort'])) $sort = tep_db_prepare_input($_POST['sort']);
  if(!$orderby) $orderby = 'lastname';
  if(!$sort) $sort = 'ASC';

  $error = false;
  $processed = false;

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update':
        $customers_id = tep_db_prepare_input($_GET['cID']);
        $customers_private_messages = tep_db_prepare_input($_POST['customers_private_messages']);

      if ($error == false) {

        $sql_data_array = array('customers_private_messages' => $customers_private_messages);

        tep_db_perform(TABLE_CUSTOMER_PRIVATE_MESSAGE, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "'");

        tep_db_query("update " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customers_id . "'");

       }
   
        tep_redirect(tep_href_link(FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_id));

        break;
    case 'createnew':
        $customers_id = tep_db_prepare_input($_GET['cID']);
        $customers_private_messages = tep_db_prepare_input($_POST['customers_private_messages']);
        $ifall = tep_db_prepare_input($_GET['All']);
        if ($error == false) {
          if ($ifall == 1) {
            $customer_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where 1");
            while ($customermessage = tep_db_fetch_array($customer_query)) {
              $customerprivatemessage_query = tep_db_query("select * from " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " where customers_id = " . $customermessage['customers_id'] . " and message_forall = 1 ORDER BY message_id DESC LIMIT 1");
              $privatemessage12 = tep_db_fetch_array($customerprivatemessage_query);
              $messageid = $privatemessage12['message_id'];
              if ($messageid == '' || $messageid == 0) { 
                $messageid = 100; 
              } else { 
                $messageid = $privatemessage12['message_id'] + 1; 
              }
              tep_db_query("insert into " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " (message_id, message_desc, message_write_date, customers_id, message_forall) values(" . $messageid . ", '" . tep_db_input($customers_private_messages) . "', now(),'" . $customermessage['customers_id'] . "', '" . $ifall . "')");
            }
          } else {
            $customerprivatemessage_query = tep_db_query("select * from " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " where customers_id = " . (int)$customers_id . " and message_forall = 0 ORDER BY message_id DESC LIMIT 1");
            $privatemessage12 = tep_db_fetch_array($customerprivatemessage_query);
            $messageid = $privatemessage12['message_id'] + 1;
            if ($messageid >= 100) { 
              $messageid = $messageid % 100; 
            } 
            tep_db_query("insert into " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " (message_id, message_desc, message_write_date, customers_id) values(" . $messageid . ", '" . tep_db_input($customers_private_messages) . "', now(), '" . (int)$customers_id . "')");
          }
        }
        tep_redirect(tep_href_link(FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_id));
        break;

       case 'deleteconfirm':
        $customers_id = tep_db_prepare_input($_GET['cID']);
        $deletemessage_id = tep_db_prepare_input($_GET['mID']);
        $forall = tep_db_prepare_input($_GET['All']);
        
        if ($forall == 1) {
          $customer_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where 1");
          while ($customermessage = tep_db_fetch_array($customer_query)) {
            tep_db_query("delete from " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " where customers_id = '" . $customermessage['customers_id'] . "'and message_forall = 1 and message_id = '" . (int)$deletemessage_id . "'");
          }   
        } else {
          tep_db_query("delete from " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " where customers_id = '" . (int)$customers_id . "'and message_id = '" . (int)$deletemessage_id . "'");
        }

        tep_redirect(tep_href_link(FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('cID', 'action'))));
        break;
      default:
        /*$customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_fax, c.customers_newsletter, c.customers_default_address_id, c.customers_private_messages  from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$_GET['cID'] . "'");
        $customers = tep_db_fetch_array($customers_query);
        $cInfo = new objectInfo($customers);*/
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script>
function InsertContent(tid) {
  if(document.getElementById(tid).style.display == "none") {
    document.getElementById(tid).style.display = "";
  } else { 
    document.getElementById(tid).style.display = "none";
  }
}
</script>
<script language="javascript" src="includes/general.js"></script>
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="2">
    <?php
      if ($action == 'create' || $action == 'createnew') {
        $message_status = array(array('id' => '1', 'text' => ENTRY_MESSAGE_READ),
                                  array('id' => '0', 'text' => ENTRY_MESSAGE_NO_READ));
                                  
        $pmNameQuery = tep_db_fetch_array(tep_db_query("SELECT customers_firstname, customers_lastname FROM customers WHERE customers_id = '" . (int)$_GET['cID'] . "'"));
        if ($_GET['All'] == 0) {
          $pmName = $pmNameQuery['customers_firstname'] . ' ' . $pmNameQuery['customers_lastname'];
        } else {
          $pmName = 'All Customers';
        }
    ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" valign="top"><?php echo HEADING_TITLE_CREATE . $pmName; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'message.png', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('messages', FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('action')) . 'action=createnew', 'post', ''); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_MESSAGES; ?></td>
      </tr>
      <tr>
        <td class="formArea">
  <table border="0" cellspacing="2" cellpadding="2">
        
<?php
              // BOF Private Messages v1.0
              ?>
<tr>
<td valign="top" class="main"><?php echo HEADING_CUSTOMERS_PRIVATE_MESSAGES ?></td>
<td class="main">
<?php
              if ($processed == true) {
              echo $cInfo->customers_private_messages . tep_draw_hidden_field('customers_private_messages');
              } else {
              echo tep_draw_textarea_field('customers_private_messages', 'soft', '75', '5', '');
              }
              ?></td>
</tr>
<?php
 // EOF Private Messages v1.0
            ?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_insert.gif', 'Add New Message') . ' <a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
<?php } else if ($action == 'edit' || $action == 'update') { 
  
    $message_status = array(array('id' => '1', 'text' => ENTRY_MESSAGE_READ),
                              array('id' => '0', 'text' => ENTRY_MESSAGE_NO_READ));
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>                              
      </tr>
      <tr><?php echo tep_draw_form('messages', FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('action')) . 'action=createnew', 'post', ''); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_MESSAGES; ?></td>
      </tr>
      <tr>
        <td class="formArea">
  <table border="0" cellspacing="2" cellpadding="2">
        
<?php
              // BOF Private Messages v1.0
              ?>
<tr>
<td valign="top" class="main"><?php echo HEADING_CUSTOMERS_PRIVATE_MESSAGES ?></td>
<td class="main">
<?php
              if ($processed == true) {
              echo $cInfo->customers_private_messages . tep_draw_hidden_field('customers_private_messages');
              } else {
              echo tep_draw_textarea_field('customers_private_messages', 'soft', '75', '5', '');
              }
              ?></td>
</tr>
<?php
 // EOF Private Messages v1.0
            ?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_update.gif', 'createnew') . ' <a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
<?php
  } else { $cus = array('1','2');
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo tep_draw_form('search', FILENAME_PRIVATE_MESSAGES, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="smallText" align="right">
             <div style="float:right; width:255px;">  
              <div style="float:left;"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search', $_GET['search']) . ' &nbsp;Reset</div>
              <div style="float:right; padding-top:3px; width:16px;"><a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'cancel.png', 'Reset the List'); ?></a></div>
             </div>
            </td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td>
        <?php 
          echo '<a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('cID', 'All', 'action')) . 'cID=0&All=1&action=create') . '">'; ?><?php echo tep_image_button('button_insert.gif', IMAGE_BUTTON_INSERT_ALL); 
        ?>
        </td>
      </tr>
<?php

if($action == 'confirm') {
  echo '<tr><td width="100%"><div class="messageStackWarning" style="margin: 1em 0; padding: 5px;"><b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER .
  '</b><br> '. TEXT_DELETE_CUSTOMER . ' <b> ' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname.'</b>&nbsp;<a class="splitPageLink" href="' .
  tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=deleteconfirm') .
  '">' . TEXT_DELETE_ACCOUNT . '</a>&nbsp;/&nbsp;<a class="splitPageLink" href="' .
  tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action'))) .
  '">' . TEXT_DELETE_ACCOUNT_CANCEL . '</a></div></td></tr>';
}
/*
Function to print table headers based on current sort pattern
$name = Full name of header, usually defined in language files
$id = sort word used in URL
$current_dir = current sort direction (ASC or DESC)
*/
function print_sort( $name, $id, $default_sort ) {
 global $orderby, $sort;

 if( isset( $orderby ) && ( $orderby == $id ) ) {
  if( $sort == 'ASC' ) {
   $to_sort = 'DESC';
  } else {
   $to_sort = 'ASC';
  }
 } else {
  $to_sort = $default_sort;
 }
 $return = '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'orderby=' . $id . '&amp;sort='. $to_sort) . '">' . $name . '</a>';
 if( $orderby == $id ) {
   $return .= '&nbsp;<img src="images/arrow_' . ( ( $to_sort == 'DESC' ) ? 'down' : 'up' ) . '.png" width="10" height="13" border="0" alt="" />';
 }
 return $return;
}
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent" nowrap></td>
              </tr>

<?php

$search = '';

// Setup column sorting
if($orderby == 'lastname') {
 $db_orderby = 'c.customers_lastname ' . $sort . ', c.customers_firstname';
} elseif($orderby == 'firstname') {
 $db_orderby = 'c.customers_firstname ' . $sort . ', c.customers_lastname';
} elseif($orderby == 'date_created') {
 $db_orderby = 'date_account_created ' . $sort . ', c.customers_lastname';
} elseif($orderby == 'date_login') {
 $db_orderby = 'last_logon ' . $sort . ', c.customers_lastname';

} elseif($orderby == 'extra_field') {
 $db_orderby = 'value ' . $sort . ', c.customers_lastname';

} elseif($orderby == 'num_logins') {
 $db_orderby = 'num_logons ' . $sort . ', c.customers_lastname';
} elseif($orderby == 'dob') {
 $db_orderby = 'customers_dob ' . $sort . ', c.customers_lastname';
} elseif($orderby == 'state') {
 $db_orderby = 'country ' . $sort . ', state ' . $sort . ', city ' . $sort . ', c.customers_lastname';
} else {
 $db_orderby = 'c.customers_lastname ASC, c.customers_firstname';
}
if(!$sort) $sort = 'ASC';

    if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
      $search = "where c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or c.customers_email_address like '%" . $keywords . "%'";
    }

    $customers_query_raw = "select c.customers_id, 
                                   c.customers_lastname, 
                                   c.customers_firstname, 
                                   c.customers_email_address, 
                                   a.entry_telephone, 
                                   c.customers_dob, 
                                   ci.customers_info_date_of_last_logon as last_logon, 
                                   ci.customers_info_number_of_logons as num_logons, 
                                   ci.customers_info_date_account_created as date_account_created, 
                                   a.entry_city as city, 
                                   a.entry_state as state_alt, 
                                   z.zone_name as state, 
                                   ctry.countries_iso_code_2 as country,
                                   c.customers_newsletter, 
                                   a.entry_country_id 
                              from " . TABLE_CUSTOMERS . " c 
                         left join " . TABLE_ADDRESS_BOOK . " a 
                                on c.customers_id = a.customers_id 
                               and c.customers_default_address_id = a.address_book_id 
                         left join " . TABLE_CUSTOMERS_INFO . " ci 
                                on c.customers_id = ci.customers_info_id 
                         left join " . TABLE_COUNTRIES . " ctry 
                                on a.entry_country_id = ctry.countries_id 
                         left join " . TABLE_ZONES . " z 
                                on a.entry_zone_id = z.zone_id 
                                   " . $search . " 
                          order by " . $db_orderby . " 
                                   " . $sort;

    $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
    $customers_query = tep_db_query($customers_query_raw);
    while ($customers = tep_db_fetch_array($customers_query)) {
      $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers['customers_id'] . "'");
      $info = tep_db_fetch_array($info_query);

   $privatemessage_query = tep_db_query("select count(*) as number_of_messages from " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " where customers_id = '" . (int)$customers['customers_id'] . "'");
      $privatemessage = tep_db_fetch_array($privatemessage_query);
 
 
 
      if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $customers['customers_id']))) && !isset($cInfo)) {
        $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$customers['entry_country_id'] . "'");
        $country = tep_db_fetch_array($country_query);

        $reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers['customers_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);

        $customer_info = array_merge((array)$country, (array)$info, (array)$reviews);
  

        $cInfo_array = array_merge((array)$customers, (array)$customer_info);
        $cInfo = new objectInfo($cInfo_array);
      }
?>
 <tr class="dataTableRow">
 <td class="dataTableContent" colspan="2" style="border-left:1px solid #999999; border-right:1px solid #999999;"><div style="float:left; padding-top:5px;"> &nbsp;
 <?php
 if ($privatemessage['number_of_messages'] > 0) {
 ?>
  <a href="javascript:InsertContent('<?php echo $customers['customers_id']; ?>');">+/-</a>&nbsp;&nbsp;
 <?php } ?>
    <?php echo ucwords($customers['customers_lastname']) .' '. ucwords($customers['customers_firstname']) . ' ('. $privatemessage['number_of_messages'] .')'; ?>
    </div>
    <div style="float:right;">
      <?php echo
 '<a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('cID', 'All', 'action')) .
 'cID=' . $customers['customers_id'] . '&All=0&action=create') . '">'; ?><?php echo tep_image_button('button_insert.gif', IMAGE_BUTTON_INSERT . ' to ' . $customers['customers_firstname']); ?></a>
    </div>
    <div style="clear:both;"></div>
 <?php
 if ($privatemessage['number_of_messages'] > 0)
 {
 ?>
  <DIV id="<?php echo $customers['customers_id']; ?>" style="display: none">
  <fieldset><legend>Current Messages</legend><table border="0" width="100%" cellpadding="0" cellspacing="0" style="padding:10px;">
  
   <?php
    $customerprivatemessage_query = tep_db_query("select * from " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " where customers_id = " . $customers['customers_id'] . " ORDER BY message_id");
    while ($privatemessage1 = tep_db_fetch_array($customerprivatemessage_query))
    {
     echo '<tr class="dataTableHeadingRow">';   
     echo '<td class="dataTableHeadingContent" width="1">Delete</td>'; 
     echo '<td class="dataTableHeadingContent">' . TABLE_HEADING_PRIVATE_MESSAGE_ID . '</td>';
     echo '<td class="dataTableHeadingContent">' . TABLE_HEADING_PRIVATE_MESSAGE_DESC .'</td>';
     echo '<td class="dataTableHeadingContent">' . TABLE_HEADING_PRIVATE_MESSAGE_DATE . '</td>';   
     echo '<td class="dataTableHeadingContent">Has Viewed</td>';
     if ($privatemessage1['message_forall'] == 1) {
       echo '<td class="dataTableHeadingContent">Delete</td>';
     } else {
       echo '<td class="dataTableHeadingContent">&nbsp;</td>';
     } 
     echo '</tr>';
     echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">';
     echo '<td class="dataTableContent" align="center" valign="top"style="border-left:1px solid #999999;">';
     echo '<a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('cID', 'mID', 'All', 'action')) . 
          'cID=' . $customers['customers_id'] . '&mID=' . $privatemessage1['message_id'] . '&All=0&action=deleteconfirm') . '">'. tep_image(DIR_WS_IMAGES . 'cancel.png', 'Delete this Message') . '</a>';
     echo '</td>';
     echo '<td class="dataTableContent" valign="top">&nbsp;';
     echo $privatemessage1['message_id'];
     echo '</td><td class="dataTableContent" valign="top" width="50%">';
     echo $privatemessage1['message_desc'];
     echo '</td><td class="dataTableContent" valign="top">';
     echo $privatemessage1['message_write_date'];
     echo '</td><td class="dataTableContent" valign="top">';
     echo $privatemessage1['message_stat'];
     if ($privatemessage1['message_forall'] == 1) {
       echo '</td><td class="dataTableContent" valign="top" style="border-right:1px solid #999999;">';
       echo '<a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES, tep_get_all_get_params(array('cID', 'mID', 'All', 'action')) .
       'cID=' . $customers['customers_id'] . '&mID=' . $privatemessage1['message_id'] . '&All=1&action=deleteconfirm') . '">'. tep_image(DIR_WS_IMAGES . 'cancel.png', 'Delete this Message for All Customers') . '</a>&nbsp; For All';
     } else {
       echo '</td><td class="dataTableContent" valign="top" style="border-right:1px solid #999999;">&nbsp;';
     }
     echo '</td>';
     echo '</tr>';
    }
    ?>
  </table></fieldset>
  </div>
 <?php
 }
 ?>
 </td>
<?php
    } 
?>
              <tr>
                <td colspan="10"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>

<?php
    if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGE) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>

</table></td>
<!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>