<?php
/*
  $Id: change_password.php,v 3.0 11/23/2007 kymstion

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/


  require('includes/application_top.php');

  // Include the password functions
  require_once(DIR_WS_FUNCTIONS . 'password_funcs.php');

  // Include the language definitions
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHANGE_PASSWORD);
  
//
// POST target -- the POST form has been sent to change a password
// If a password change has been submitted, check the results for errors
  $pass = 0;
  if ($_POST['customer_id'] != '') {
    $customer_id = (int)$_POST['customer_id'];

    if ($_POST['new_password'] == '' && $_POST['repeat_password'] == '') {  // Use generated password
      $pass = 1;
      $new_password = $_POST['auto_password'];
    } elseif ($_POST['new_password'] == $_POST['repeat_password']) {  // Use custom password
      $pass = 1;
      $new_password = $_POST['new_password'];
    } elseif (empty($_POST['new_password'])) {  // Missing password
      $pass = 2;
    } elseif (empty($_POST['repeat_password'])) {  // Missing repeat password
      $pass = 2;
    } elseif ($_POST['new_password'] != $_POST['repeat_password']) {  // Mismatched passwords
      $pass = 3;
    }

// If all is well, make the changes to the database
    if ($pass == 1) {
      mysql_query("UPDATE " . TABLE_CUSTOMERS . "
                   SET customers_password='" . tep_encrypt_password ($new_password) . "'
                   WHERE customers_id='" . $customer_id . "'
                ");

// Get the customer's information for the success message
      $customer_name_query = mysql_query("SELECT customers_firstname,
                                                 customers_lastname
                                          FROM " . TABLE_CUSTOMERS . "
                                          WHERE customers_id='" . $customer_id . "'
                                       ");
      $customer_name = mysql_fetch_array ($customer_name_query);
     }
  }
// End POST section

//
// GET target -- a GET form has been sent
// Build a SQL string from the Search or Customer variables
  $search_string = '';
  if (isset ($_GET['search']) && strlen ($_GET['search']) > 1)  {
    $keywords = tep_db_input (tep_db_prepare_input ($_GET['search']));
    $search_string = "where customers_lastname like '%" . $keywords . "%' or customers_firstname like '%" . $keywords . "%' or customers_email_address like '%" . $keywords . "%'";

  } elseif (isset ($_GET['customer'])) {
    $customer_id = (int)$_GET['customer'];
    $search_string = "WHERE customers_id='" . $customer_id . "'";
  }
// End GET section

//
// Variable fields to insert into the page
// Build an array of customers for the select pulldown
  $customer_data_query = mysql_query("SELECT customers_id,
                                             customers_firstname,
                                             customers_lastname,
                                             customers_email_address
                                      FROM " . TABLE_CUSTOMERS . "
                                           " . $search_string . "
                                      ORDER BY customers_lastname, customers_firstname
                                  ");
  $customers_array = array();
  while ($customer_data = mysql_fetch_array ($customer_data_query) ) {
    $customers_array[] = array('id' => $customer_data['customers_id'],
                               'text' =>  $customer_data['customers_lastname'] . ', ' . $customer_data['customers_firstname'] . ' (' . $customer_data['customers_email_address'] . ')'
                              );
  }

// Set the correct message to display for password change or errors
  $message = '';
  switch ($pass) {
    case 1:
      $message = '<b><font color=#009900>';
      $message .= CUSTOMER_PASSWORD . $customer_name['customers_firstname'] . ' ' . $customer_name['customers_lastname'];
      $message .= PASSWORD_UPDATED . '&nbsp;' . $new_password . '<br>' . PASSWORD_UPDATED_REMINDER;
      $message .= '</b></font><br>' . tep_black_line();
      break;
    case 2:
      $message = '<b><font color=#ff0000>'. PLEASE_NEW_PASSWORD . PLEASE_REPEAT . '</b></font>';
      break;
    case 3:
      $message = '<b><font color=#ff0000>'. ERROR_NEW_PASSWORD .  PLEASE_REPEAT . '</b></font>';
      break;
    case 0:
    default:
      $message = '&nbsp;';
      break;
  }

// Set up the search form
  $search_form = tep_draw_input_field ('search');
  $search_form .= tep_draw_hidden_field ('selected_box', 'ticket');
  $search_form .= tep_hide_session_id();

// Generate a random password and add it to the form
  $auto_password = tep_create_random_value (ENTRY_PASSWORD_MIN_LENGTH);
  $auto_form = tep_draw_hidden_field ('auto_password', $auto_password) . $auto_password;


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
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/javascript/color_picker/picker-dec.js"></script>
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
        <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php if ($message != '&nbsp;') { ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo $message; ?></td>
          </tr>
        </table></td>
      </tr>
<?php } ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_form ('search', FILENAME_CHANGE_PASSWORD, '', 'get'); ?>
        <table border="0" width="450px" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo SEARCH; ?></b></td>
            <td class="main"><?php echo $search_form; ?></td>
            <td class="main" rowspan="2" align="top"><?php echo TEXT_SEARCH_INSTRUCTION; ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><?php echo tep_image_submit ('button_search.gif', IMAGE_SEARCH); ?></td>
          </tr>
        </form></table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_form ('password', FILENAME_CHANGE_PASSWORD, 'selected_box=ticket', 'POST'); ?>
        <table border=0 width="450px" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo SELECT_CUSTOMER; ?></b></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('customer_id', $customers_array);; ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo AUTO_PASSWORD; ?></b></td>
            <td class="main"><?php echo $auto_form; ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo NEW_PASSWORD; ?></b></td>
            <td class="main"><?php echo tep_draw_password_field('new_password'); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo REPEAT_NEW_PASSWORD; ?></b></td>
            <td class="main"><?php echo tep_draw_password_field('repeat_password'); ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
            <td><?php echo tep_image_submit ('button_change_password.gif', IMAGE_CHANGE_PASSWORD); ?></td>
          </tr>
        </form></table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>