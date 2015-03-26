<?php
/*
  $Id: ticket_create.php,v 1.5 2003/04/25 21:37:12 hook Exp $

  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TICKET_CREATE);

  $ticket_departments = array();
  $ticket_department_array = array();
  $ticket_department_query = tep_db_query("select ticket_department_id, ticket_department_name from " . TABLE_TICKET_DEPARTMENT . " where ticket_language_id = '" . $languages_id . "'");
  while ($ticket_department = tep_db_fetch_array($ticket_department_query)) {
    $ticket_departments[] = array('id' => $ticket_department['ticket_department_id'],
                               'text' => $ticket_department['ticket_department_name']);
    $ticket_department_array[$ticket_department['ticket_department_id']] = $ticket_department['ticket_department_name'];
  }
  $ticket_prioritys = array();
  $ticket_priority_array = array();
  $ticket_priority_query = tep_db_query("select ticket_priority_id, ticket_priority_name from " . TABLE_TICKET_PRIORITY . " where ticket_language_id = '" . $languages_id . "'");
  while ($ticket_priority = tep_db_fetch_array($ticket_priority_query)) {
    $ticket_prioritys[] = array('id' => $ticket_priority['ticket_priority_id'],
                                'text' => $ticket_priority['ticket_priority_name']);
    $ticket_priority_array[$ticket_priority['ticket_priority_id']] = $ticket_priority['ticket_priority_name'];
  }
 
  $email = tep_db_prepare_input(trim($_POST['email']));
  $name = tep_db_prepare_input($_POST['name']);
  $subject = tep_db_prepare_input($_POST['subject']);
  $enquiry = tep_db_prepare_input($_POST['enquiry']);
  $department = tep_db_prepare_input($_POST['department']);
  $priority = tep_db_prepare_input($_POST['priority']);
  $ticket_customers_orders_id = tep_db_prepare_input($_POST['ticket_customers_orders_id']);
  
  


// Form was submitted
  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
 // Check Subject length
    if (isset($subject) && strlen($subject) < TICKET_ENTRIES_SUBJECT_MIN_LENGTH ) {
        $error = true;
        $error_subject = true;
      }
  // Check Message length
    if (isset($enquiry) && strlen($enquiry) < TICKET_ENTRIES_ENQUIRY_MIN_LENGTH ) {
        $error = true;
        $error_enquiry = true;
      }
    if ($error == false) {
      $ticket_customers_id = '';
    // Get the customers_id
      $ticket_customers_id = tep_db_prepare_input($_POST['ticket_customer_id']) ;
         // generate LInkID
      $time = mktime();
      $ticket_link_id = '';
      for ($x=3;$x<10;$x++) {
        $ticket_link_id .= substr($time,$x,1) . tep_create_random_value(1, $type = 'chars');
      }
      
      $sql_data_array = array('ticket_link_id' => $ticket_link_id,
                              'ticket_customers_id' => $ticket_customers_id,
                              'ticket_customers_orders_id' => $ticket_customers_orders_id,
                              'ticket_customers_email' => $email,
                              'ticket_customers_name' => $name,
                              'ticket_subject' => $subject,
                              'ticket_status_id' => TICKET_DEFAULT_STATUS_ID,
                              'ticket_department_id' => $department,
                              'ticket_priority_id' => $priority,
                              'ticket_login_required' => TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT,
                              'ticket_date_last_modified' => 'now()',
                              'ticket_date_last_customer_modified' => 'now()',
                              'ticket_date_created' => 'now()');

      tep_db_perform(TABLE_TICKET_TICKET, $sql_data_array);
                              $insert_id = tep_db_insert_id();
      
      $sql_data_array = array('ticket_id' => $insert_id,
                              'ticket_status_id' => TICKET_DEFAULT_STATUS_ID,
                              'ticket_priority_id' => $priority,
                              'ticket_department_id' => $department,
                              'ticket_date_modified' => 'now()',
                              'ticket_customer_notified' => '1',
                              'ticket_edited_by' => $name,
                              'ticket_comments' => $enquiry);
      tep_db_perform(TABLE_TICKET_STATUS_HISTORY, $sql_data_array); 
    // Email  Customer doesn't get the Message cause he should use the web
      $ticket_email_subject = TICKET_EMAIL_SUBJECT . $subject;
      $ticket_email_message = TICKET_EMAIL_MESAGE_HEADER . "\n\n" . tep_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $ticket_link_id, 'NONSSL',false,false) . "\n\n" . TICKET_EMAIL_TICKET_NR . " " . $ticket_link_id . "\n" . TICKET_EMAIL_MESAGE_FOOTER;
      tep_mail($name, $email, $ticket_email_subject, nl2br($ticket_email_message), STORE_NAME, SUPPORT_EMAIL_ADDRESS);
    // send emails to other people
      if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
       $ticket_email_message = TICKET_EMAIL_MESAGE_HEADER . "\n\n" . tep_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $ticket_link_id) . "\n\n" . $enquiry . TICKET_EMAIL_MESAGE_FOOTER . "\n\n" . $enquiry;
       tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $ticket_email_subject,nl2br($ticket_email_message), STORE_NAME, SUPPORT_EMAIL_ADDRESS);
      }
      tep_redirect(tep_href_link(FILENAME_TICKET_CREATE, 'action=success&tlid=' . $ticket_link_id ));
    }
  }
/*
  $Id: stats_products_viewed.php,v 1.1.1.1 2004/03/04 23:38:59 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

?><head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr><td>
	<!-- left_navigation //-->
	<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
	<!-- left_navigation_eof //-->
    </td><td valign="top">
<?  $content = CONTENT_TICKET_CREATE;

  if (defined('SUPPORT_ENABLED') && SUPPORT_ENABLED == 'true') {  
?>
<table border="0" width="97%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
  // BOF: Lango Added for template MOD
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
  //EOF: Lango Added for template MOD
?>
<tr>
 <td width="96%" valign="top">
   <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
         <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" valign="top"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table>
       </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
        // BOF: Lango Added for template MOD
        } else {
        $header_text = HEADING_TITLE;
        }
        // EOF: Lango Added for template MOD
      ?>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <?php
              // BOF: Lango Added for template MOD
              if (MAIN_TABLE_BORDER == 'yes') {
                table_image_border_top(false, false, $header_text);
              }
              // EOF: Lango Added for template MOD
            ?>
            <?php
              if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
            ?>
            <tr>
              <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
              <td class="main">&nbsp;</td>
              <td class="main"></td>
            </tr>
            <tr>
              <td rowspan=4 class="main" width="400"><?php echo tep_image(DIR_WS_IMAGES . 'loaded_header_logo.gif', HEADING_TITLE, '0', '0', 'align="left"') ?></td>
              <td class="main"><?php echo TEXT_SUCCESS; ?></td>
            </tr>
            <tr>
              <td class="main"><?php echo TEXT_YOUR_TICKET_ID . ' ' . $_GET['tlid']; ?></td>
            </tr>
            <tr>
              <td class="main"><?php echo TEXT_CHECK_YOUR_TICKET . '<br><a href="' . tep_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $_GET['tlid'], 'NONSSL',false,false) . '">' . tep_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $_GET['tlid'], 'NONSSL',false,false) . '</a>'; ?></td>
            </tr>
            <tr>
              <td valign ="bottom" align="right"><br><a href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>"><?php echo tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a></td>
            </tr>
          </table>
        </td>
      </tr>
      <?php
        } else {
      ?>
       <tr>
        <td class="main" align="left" width="100%" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td class="main" align="left" width="100%" valign="top"><?php echo TEXT_FILL_OUT_FORM; ?></td>
      </tr>
      <?php
        }
      ?>
      <tr>
        <td>
        <?php
           echo tep_draw_form('contact_us', FILENAME_TICKET_CREATE,'action=send','POST', '','SSL');
        ?>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td width="150" class="main"><?php echo ENTRY_NAME; ?>&nbsp;</td>
            <td class="main">
            <?php
               
                  echo tep_draw_input_field('name', ($error ? $name : $first_name)); if ($error_name) echo ENTRY_ERROR_NO_NAME;
             
            ?>
            </td>
            <td class="main">&nbsp;</td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL; ?>&nbsp;</td>
            <td class="main">
       <?
                  echo tep_draw_input_field('email', ($error ? $email : $email_address)); if ($error_email) echo ENTRY_EMAIL_ADDRESS_CHECK_ERROR; 
              
            ?>
            </td>
          </tr>
          <?php
              if (TICKET_SHOW_CUSTOMERS_SUBJECT == 'true') {   
          ?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBJECT; ?>&nbsp;</td>
            <td class="main"><?php  echo tep_draw_input_field('subject', ($error ? $subject : $subject)); if ($error_subject) echo ENTRY_ERROR_NO_SUBJECT; ?></td>
            <td>&nbsp;</td>
          </tr>
          <?php
              }

              if (TICKET_SHOW_CUSTOMERS_ORDER_IDS == 'true' ) {     
                $customers_orders_query = tep_db_query("select orders_id, date_purchased from " . TABLE_ORDERS . " where customers_id = '" . tep_db_input($customer_id) . "'");
                if (isset($_GET['ticket_order_id'])) $ticket_preselected_order_id = $_GET['ticket_order_id'];
                $orders_array[] = array('id' => '', 'text' => ' -- ' );
                while ($customers_orders = tep_db_fetch_array($customers_orders_query)) {
                  $orders_array[] = array('id' => $customers_orders['orders_id'], 'text' => $customers_orders['orders_id'] . "  (" . tep_datetime_short($customers_orders['date_purchased']) . ")" );
              }
          
          ?>
          <tr>
            <td class="main"><?php echo ENTRY_ORDER; ?>&nbsp;</td>
            <td class="main"><?php echo  $_GET['oID'] .tep_draw_hidden_field('ticket_customers_orders_id', $_GET['oID']).tep_draw_hidden_field('ticket_customer_id', $_GET['cID']); ?></td>
            <td>&nbsp;</td>
          </tr>

          <?php
              }
              if (TICKET_CATALOG_USE_DEPARTMENT == 'true') {     
          ?>
          <tr>
            <td class="main"><?php echo ENTRY_DEPARTMENT; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_pull_down_menu('department', $ticket_departments, ($department ? $department : TICKET_DEFAULT_DEPARTMENT_ID) ); ?></td>
            <td>&nbsp;</td>
          </tr>
          <?php
              } else {
                echo tep_draw_hidden_field('department', TICKET_DEFAULT_DEPARTMENT_ID);
              }
              if (TICKET_CATALOG_USE_PRIORITY == 'true') {   
          ?>
          <tr>
            <td class="main"><?php echo ENTRY_PRIORITY; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_pull_down_menu('priority', $ticket_prioritys, ($priority ? $priority : TICKET_DEFAULT_PRIORITY_ID) ); ?></td>
            <td>&nbsp;</td>
          </tr>
          <?php
              } else {
                echo tep_draw_hidden_field('priority', TICKET_DEFAULT_PRIORITY_ID);
              }
          ?>
          <tr>
            <td colspan=3 class="main"><br><?php echo ENTRY_ENQUIRY; ?></td>
          </tr>
          <tr>
            <td align="left" colspan=3><?php echo tep_draw_textarea_field('enquiry', 'soft', 100, 20, $enquiry); ?><br><?php if ($error_enquiry) echo ENTRY_ERROR_NO_ENQUIRY; ?></td>
          </tr>
          <tr>
            <td colspan=3 class="main" align="right"><br><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
          </tr>
        </table>
      </form>
      </td>
      </tr>
<?
}
        // BOF: Lango Added for template MOD
          if (MAIN_TABLE_BORDER == 'yes'){
            table_image_border_bottom();
          }
        // EOF: Lango Added for template MOD
      ?>
      </table>
    </td>
  </tr>
</table>
</table></td>
 </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
