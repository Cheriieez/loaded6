<?php
/*
  $Id: ticket_view.php,v 1.6 2003/07/13 20:22:02 hook Exp $

  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $ticket_admins = array();
  $ticket_admin_array = array();
  $ticket_admin_query = tep_db_query("select ticket_admin_id, ticket_admin_name from " . TABLE_TICKET_ADMIN . " where ticket_language_id = '" . $languages_id . "'");
  while ($ticket_admin = tep_db_fetch_array($ticket_admin_query)) {
    $ticket_admins[] = array('id' => $ticket_admin['ticket_admin_id'],
                               'text' => $ticket_admin['ticket_admin_name']);
    $ticket_admin_array[$ticket_admin['ticket_admin_id']] = $ticket_admin['ticket_admin_name'];
  }


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

  $ticket_statuses = array();
  $ticket_status_array = array();
  $ticket_status_query = tep_db_query("select ticket_status_id, ticket_status_name from " . TABLE_TICKET_STATUS . " where ticket_language_id = '" . $languages_id . "'");
  while ($ticket_status = tep_db_fetch_array($ticket_status_query)) {
    $ticket_statuses[] = array('id' => $ticket_status['ticket_status_id'],
                               'text' => $ticket_status['ticket_status_name']);
    $ticket_status_array[$ticket_status['ticket_status_id']] = $ticket_status['ticket_status_name'];
  }

// START - added for delete reply feature
  $ticket_comments = array();
  $ticket_comment_array = array();
  $ticket_comment_query = tep_db_query("select ticket_id, ticket_status_history_id from " . TABLE_TICKET_STATUS_HISTORY . " where ticket_id = '" . $tID . "'");
  while($ticket_comment = tep_db_fetch_array($ticket_comment_query)) {
    $ticket_comments[] = array('id' => $ticket_comment['ticket_id'],
                               'text' => $ticket_comment['ticket_status_history_id']);
    $ticket_comment_array[$ticket_comment['ticket_id']] = $ticket_comment['ticket_status_history_id'];
  }
// END - added for delete reply feature

  switch ($_GET['action']) {
    case 'update_ticket':
      $error = false;
      $tID = tep_db_prepare_input($_GET['tID']);
      $enquiry = tep_db_prepare_input($_POST['enquiry']);
      $admin = tep_db_prepare_input($_POST['admin']);
      $status = tep_db_prepare_input($_POST['status']);
      $priority = tep_db_prepare_input($_POST['priority']);
      $department = tep_db_prepare_input($_POST['department']);
      $ticket_login_required = tep_db_prepare_input($_POST['ticket_login_required']);
    // Check Message length
      if (strlen($enquiry) < TICKET_ADMIN_ENTRIES_MIN_LENGTH ) {
        $error = true;
        $messageStack->add_session(WARNING_ENTRY_TO_SHORT,  'warning');
      }
    // Check if Ticket exists
      $ticket_update_query = tep_db_query("select ticket_customers_email, ticket_customers_name, ticket_link_id  from " . TABLE_TICKET_TICKET . " where ticket_id = '" . $tID . "'");
      $ticket_update = tep_db_fetch_array($ticket_update_query);
      if (!$ticket_update['ticket_customers_email']) {
        $error = true;
        $messageStack->add_session(WARNING_TICKET_NOT_UPDATED ."AA", 'warning');
      }
      if ($error == false) {
       $sql_data_array = array('ticket_id' => $tID,
                          'ticket_status_id' => $status,
                          'ticket_priority_id' => $priority,
                          'ticket_department_id' => $department,
                          'ticket_date_modified' => 'now()',
                          'ticket_customer_notified' => '0',
                          'ticket_edited_by' => $ticket_admin_array[$admin],
                          'ticket_comments' => $enquiry);
        tep_db_perform(TABLE_TICKET_STATUS_HISTORY, $sql_data_array);
        $sql_data_array = array('ticket_date_last_modified' => 'now()',
                          'ticket_status_id' => $status,
                          'ticket_priority_id' => $priority,
                          'ticket_department_id' => $department,
                          'ticket_login_required' => $ticket_login_required);
        tep_db_perform(TABLE_TICKET_TICKET, $sql_data_array,'update','ticket_id=\'' . $tID . '\'');  
        // Email  Customer doesn't get the Message cause he should use the web
        $ticket_email_subject = TICKET_EMAIL_SUBJECT . $subject;
        $ticket_email_message = TICKET_EMAIL_MESAGE_HEADER . "\n\n" . tep_catalog_href_link(FILENAME_TICKET_VIEW, 'tlid=' . $ticket_update['ticket_link_id'], 'SSL', false, false) . "\n\n" . TICKET_EMAIL_MESAGE_FOOTER1 . "\n\n" . TICKET_EMAIL_MESAGE_FOOTER2;
        tep_mail($ticket_update['ticket_customers_name'], $ticket_update['ticket_customers_email'], $ticket_email_subject, nl2br($ticket_email_message), STORE_OWNER, SUPPORT_EMAIL_ADDRESS);

        $ticket_updated = true;
      }
      if ($ticket_updated) {
        $messageStack->add_session(SUCCESS_TICKET_UPDATED, 'success');
      } else {
        $messageStack->add_session(WARNING_TICKET_NOT_UPDATED, 'warning');
      }

      tep_redirect(tep_href_link(FILENAME_TICKET_VIEW, tep_get_all_get_params(array('action')) . 'action=edit'));
      break;
    case 'deleteconfirm':
      $tID = tep_db_prepare_input($_GET['tID']);
      tep_db_query ("delete from " . TABLE_TICKET_TICKET . " where ticket_id='" . $tID . "'");
      tep_db_query ("delete from " . TABLE_TICKET_STATUS_HISTORY . " where ticket_id='" . $tID . "'");
      tep_redirect(tep_href_link(FILENAME_TICKET_VIEW, tep_get_all_get_params(array('tID', 'action'))));
      break;
// START - added for delete reply feature
    case 'removecommentconfirm':
      tep_db_query ("delete from " . TABLE_TICKET_STATUS_HISTORY . " where ticket_status_history_id = '" . $_GET['hID'] . "'");
      tep_redirect(tep_href_link(FILENAME_TICKET_VIEW, 'tID=' . $_GET['tID'] . '&action=edit'));
      break;
// END - added for delete reply feature
  }

  if ( ($_GET['action'] == 'edit') && ($_GET['tID']) ) {
      $tID = tep_db_prepare_input($_GET['tID']);
  
      $ticket_query = tep_db_query("select * from " . TABLE_TICKET_TICKET . " where ticket_id = '" . tep_db_input($tID) . "'");
      $ticket_exists = true;
      if (!tep_db_num_rows($ticket_query)) {
        $ticket_exists = false;
        $messageStack->add(sprintf(ERROR_TICKET_DOES_NOT_EXIST, $tID), 'error');
      }
    }
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php echo TICKET_STYLESHEET; ?> 
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<link type="text/css" rel="StyleSheet" href="includes/index.css" />
<link type="text/css" rel="StyleSheet" href="includes/helptip.css" />
<script type="text/javascript" src="includes/javascript/helptip.js"></script>
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
      if (($_GET['action'] == 'edit') && ($ticket_exists)) {
        $ticket = tep_db_fetch_array($ticket_query);
        $ticket_status_query = tep_db_query("select * from " . TABLE_TICKET_STATUS_HISTORY . " where ticket_id = '". tep_db_input($ticket['ticket_id']) . "' order by ticket_status_history_id DESC");
    ?>
      <tr>
        <td><table class="ticket" width="100%" border="1" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan=2 class="ticketInfoBoxHeading" align="left"><b><?php echo TEXT_TICKET_SUBJECT . $ticket['ticket_subject'] . TEXT_TICKET_SUBJECT_END; ?></b></td>
          </tr> 
          <tr>
            <td class="ticketSmallText" align="left">
            <?php
              echo TEXT_OPENED . ' ' . tep_datetime_short($ticket['ticket_date_created']) . '&nbsp;&nbsp;&nbsp;' . tep_image(DIR_WS_ICONS . 'user.gif') . '&nbsp;' . TEXT_TICKET_BY . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS, 'cID=' . $ticket['ticket_customers_id'] . '&action=edit') . '">' . $ticket['ticket_customers_name'] . "</a><br>";
              echo TEXT_CUSTOMERS_EMAIL . ' <a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $ticket['ticket_customers_email']) . '">' . $ticket['ticket_customers_email'] . "</a><br>";
              echo TEXT_TICKET_NR . '&nbsp;' . $ticket['ticket_link_id'];
              if ($ticket['ticket_customers_orders_id'] > 0 && TICKET_USE_ORDER_IDS == 'true') echo '<br>' . TEXT_CUSTOMERS_ORDERS_ID . '&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS, 'oID=' . $ticket['ticket_customers_orders_id'] . '&action=edit') . '"><u>' . $ticket['ticket_customers_orders_id'] . '</u></a>';
            ?>
            </td>
            <td class="main" align="right" valign="top" style="padding: 5px;"><?php echo '<a href="' . tep_href_link(FILENAME_TICKET_VIEW, 'tID=' . $_GET['tID'] . '&page=' . $_GET['page']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> <a href="' . tep_href_link(FILENAME_TICKET_VIEW) . '">' . tep_image_button('button_view_all_tickets.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>  
          <?php     
            while ($ticket_status = tep_db_fetch_array($ticket_status_query)) {
          ?>
          <tr>
            <td class="ticketSmallText" width="35%">
            <?php
              // START - modified for delete reply feature
                  echo tep_draw_form('tickets', FILENAME_TICKET_VIEW, tep_get_all_get_params(array('tID', 'action')) . 'tID=' . $tID . '&hID=' . $ticket_status['ticket_status_history_id'] . '&action=removecomment');
                  echo TEXT_LAST_REPLY . '<b>' . $ticket_status['ticket_edited_by'] . '</b>' . TEXT_LAST_REPLY_END . '<br>';
                  echo TEXT_DATE . '&nbsp;' .  tep_datetime_short($ticket_status['ticket_date_modified']) . '<br>';
                  if (TICKET_ADMIN_USE_STATUS == 'true') echo TEXT_STATUS . '&nbsp;' .  $ticket_status_array[$ticket_status['ticket_status_id']] . '<br>';
                  if (TICKET_ADMIN_USE_DEPARTMENT == 'true') echo TEXT_DEPARTMENT . '&nbsp;' .  $ticket_department_array[$ticket_status['ticket_department_id']] . '<br>';
                  if (TICKET_ADMIN_USE_PRIORITY == 'true') echo TEXT_PRIORITY . '&nbsp;' .  $ticket_priority_array[$ticket_status['ticket_priority_id']] . '<br>';
                  $ticket_last_used_status = $ticket_status['ticket_status_id'];
                  $ticket_last_used_department = $ticket_status['ticket_department_id'];
                  $ticket_last_used_priority = $ticket_status['ticket_priority_id'];
                  echo '<table cellspacing="0" cellpadding="0" width="100%"><tr><td valign="top" align="left">Comment ID: ' . $ticket_status['ticket_status_history_id'] . '<td><td align="right">' . tep_image_submit('button_remove_comment.gif', IMAGE_REMOVE_COMMENT . '&nbsp;' . $ticket_status['ticket_status_history_id']) . '</td></tr></table>';
            ?>
            </td>
            <td align=left valign="top" class="ticketSmallText"><?php echo nl2br($ticket_status['ticket_comments']); ?></td>
          </tr></form>  
          <?php
            }
            // END - added for delete reply feature
            echo tep_draw_form('status', FILENAME_TICKET_VIEW, tep_get_all_get_params(array('action')) . 'action=update_ticket');
          ?>
          <tr>
            <td class="ticketSmallText" valign="top" align="right">
            <?php 
                  echo '            ' . TEXT_COMMENT . '<br><br>';
                  echo '            ' . TEXT_ADMIN . '&nbsp;' . tep_draw_pull_down_menu('admin', $ticket_admins, ($ticket_last_used_admin ? $ticket_last_used_admins : TICKET_DEFAULT_ADMIN_ID) ) . "<br><br>";
                  if (TICKET_USE_STATUS=='true') echo '            ' . TEXT_STATUS . '&nbsp;' . tep_draw_pull_down_menu('status', $ticket_statuses, TICKET_DEFAULT_ADMIN_STATUS_ID) . "<br><br>";
                  if (TICKET_USE_DEPARTMENT=='true') echo '            ' . TEXT_DEPARTMENT . '&nbsp;' . tep_draw_pull_down_menu('department', $ticket_departments, ($ticket_last_used_department ? $ticket_last_used_department : TICKET_DEFAULT_DEPARTMENT_ID) ) . "<br><br>";
                  if (TICKET_USE_PRIORITY=='true') echo '            ' . TEXT_PRIORITY . '&nbsp;' . tep_draw_pull_down_menu('priority', $ticket_prioritys, ($ticket_last_used_priority ? $ticket_last_used_priority : TICKET_DEFAULT_PRIORITY_ID) ) . "<br><br>";
                  echo '            ' . TEXT_REPLY . '&nbsp;' ;
                  $reply_query = tep_db_query("select ticket_reply_id, ticket_reply_name, ticket_reply_text from " . TABLE_TICKET_REPLY . " where ticket_language_id = '" . $languages_id . "'");
                  echo ' <select name="dummy" size="1">';
                  while ($reply = tep_db_fetch_array($reply_query)) {
                    echo '            <option value="' . $reply['ticket_reply_text'] . '"';
                    if (TICKET_DEFAULT_REPLY_ID == $reply['ticket_reply_id']) echo ' selected';
                    echo '>';
                    echo $reply['ticket_reply_name'] . '</option>' . "\n";  
            
                  }
                  echo '             </select>';
                  echo '             <input type="button" name="insert" value="' . TEXT_INSERT . '" onclick="document.status.enquiry.value = document.status.enquiry.value + document.status.dummy.value">';
            ?>
            </td>
            <td  class="ticketSmallText" ><?php echo tep_draw_textarea_field('enquiry', 'soft', 50, 13,'','class="ticket"'); ?></td>
          </tr>
          <tr>
            <td colspan=2 class="main" align="right" style="padding: 5px;"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_TICKET_VIEW, 'tID=' . $_GET['tID'] . '&page=' . $_GET['page']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> <a href="' . tep_href_link(FILENAME_TICKET_VIEW) . '">' . tep_image_button('button_view_all_tickets.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
          </form>
        </table></td>
      </tr>
      <?php
        // START added for delete reply feature
        } else if ($_GET['action'] == 'removecomment') {
          $comment_query = tep_db_query("select ticket_comments from " . TABLE_TICKET_STATUS_HISTORY . " where ticket_status_history_id = '" . $_GET['hID'] . "'");
          $comment = tep_db_fetch_array($comment_query);
          $heading = array();
          $contents = array();
          switch ($_GET['action']) {
            case 'removecomment':
              $heading[] = array('text' => '<b>Are you sure that you wish to delete this comment?</b>');
              $contents = array('form' => tep_draw_form('removecomment', FILENAME_TICKET_VIEW, tep_get_all_get_params(array('action')) . 'action=removecommentconfirm'));
              $contents[] = array('align' => 'left', 'text' => '<b>Reply ID: ' . $_GET['hID'] . '</b><br /><br />');
              $contents[] = array('align' => 'left', 'text' => $comment['ticket_comments'] . '<br /><br />');
              $contents[] = array('align' => 'center', 'text' => tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' &nbsp; <a href="' . tep_href_link(FILENAME_TICKET_VIEW, 'tID=' . $_GET['tID'] . '&action=edit') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
              $contents[] = array('form' => '</form>');
            break;
          }
          if (tep_not_null($contents)) {
            echo '<tr><td width="400" valign="top">' . "\n";
            $box = new box;
            echo $box->infoBox($heading, $contents);
            echo '</td></tr>' . "\n";
          }
        // END added for delete reply feature
        } else {
      ?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="4" class="pageHeading" valign="top"><?php echo HEADING_TITLE; ?></td>
          </tr>
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('status', FILENAME_TICKET_VIEW, '', 'get'); ?>
                <?php 
                    if (TICKET_USE_STATUS=='true') echo '<td class="smallText" align="right">' . HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_TICKETS)), $ticket_statuses), '', 'onChange="this.form.submit();"') . '</td>';
                    if (TICKET_USE_DEPARTMENT=='true') echo '<td class="smallText" align="right">&nbsp;' . HEADING_TITLE_DEPARTMENT . ' ' . tep_draw_pull_down_menu('department', array_merge(array(array('id' => '', 'text' => TEXT_ALL_DEPARTMENTS)), $ticket_departments), '', 'onChange="this.form.submit();"') . '</td>'; 
                    if (TICKET_USE_PRIORITY=='true') echo '<td class="smallText" align="right">&nbsp;' . HEADING_TITLE_PRIORITY . ' ' . tep_draw_pull_down_menu('priority', array_merge(array(array('id' => '', 'text' => TEXT_ALL_PRIORITYS)), $ticket_prioritys), '', 'onChange="this.form.submit();"') . '</td>'; 
                ?>
              </form><?php echo tep_draw_form('search', FILENAME_TICKET_VIEW, '', 'get'); ?>
                <td class="smallText" align="right">
                <?php echo '&nbsp;' . HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?> 
                </td>
              </form></tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <?php
        //Modified/Added sorting ability by maestro bof -------------------------------------> 
          if (isset($_GET[tep_session_name()])) {
            $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
          } else {
            $oscid = '';
          }
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><nobr><a href="<?php echo "$PHP_SELF?Sort=id-asc" . $oscid; ?>" title="<?php echo TABLE_HEADING_SORT . TABLE_HEADING_ID . TABLE_HEADING_SORT_NUM_ASC; ?>">+</a>&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;<a href="<?php echo "$PHP_SELF?Sort=id-desc" . $oscid; ?>" title="<?php echo TABLE_HEADING_SORT . TABLE_HEADING_ID . TABLE_HEADING_SORT_NUM_DESC; ?>">-</a>&nbsp;&nbsp;</nobr></td>
                <td class="dataTableHeadingContent"><a href="<?php echo "$PHP_SELF?Sort=name-asc" . $oscid; ?>" title="<?php echo TABLE_HEADING_SORT . TABLE_HEADING_NAME . TABLE_HEADING_SORT_TEXT_ASC; ?>">+</a>&nbsp;<?php echo TABLE_HEADING_NAME; ?>&nbsp;<a href="<?php echo "$PHP_SELF?Sort=name-desc" . $oscid; ?>" title="<?php echo TABLE_HEADING_SORT . TABLE_HEADING_NAME . TABLE_HEADING_SORT_TEXT_DESC; ?>">-</a>&nbsp;&nbsp;</td>
                <td class="dataTableHeadingContent" align="left"><nobr><a href="<?php echo "$PHP_SELF?Sort=customersID-asc" . $oscid; ?>" title="<?php echo TABLE_HEADING_SORT . TABLE_HEADING_CUSTOMER_ID . TABLE_HEADING_SORT_NUM_ASC; ?>">+</a>&nbsp;<?php echo TABLE_HEADING_CUSTOMER_ID; ?>&nbsp;<a href="<?php echo "$PHP_SELF?Sort=customersID-desc" . $oscid; ?>" title="<?php echo TABLE_HEADING_SORT . TABLE_HEADING_CUSTOMER_ID . TABLE_HEADING_SORT_NUM_DESC; ?>">-</a>&nbsp;&nbsp;</nobr></td>
                <td class="dataTableHeadingContent" align="left"><nobr><a href="<?php echo "$PHP_SELF?Sort=lastchange-asc" . $oscid; ?>" title="<?php echo TABLE_HEADING_SORT . TABLE_HEADING_DATE . TABLE_HEADING_SORT_NUM_ASC; ?>">+</a>&nbsp;<?php echo TABLE_HEADING_DATE; ?>&nbsp;<a href="<?php echo "$PHP_SELF?Sort=lastchange-desc" . $oscid; ?>" title="<?php echo TABLE_HEADING_SORT . TABLE_HEADING_DATE . TABLE_HEADING_SORT_NUM_DESC; ?>">-</a>&nbsp;&nbsp;</nobr></td>
                <?php
                    if (TICKET_ADMIN_USE_SUBJECT == 'true') echo '<td class="dataTableHeadingContent" align="left"><nobr><a href="' . $PHP_SELF . '?Sort=subject-asc' . $oscid . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_TICKET_SUBJECT . TABLE_HEADING_SORT_TEXT_ASC . '">+</a>&nbsp;' .  TABLE_HEADING_TICKET_SUBJECT . '&nbsp;<a href="' . $PHP_SELF . '?Sort=subject-desc' . $oscid . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_TICKET_SUBJECT . TABLE_HEADING_SORT_TEXT_DESC . '">-</a>&nbsp;&nbsp;</nobr></td>';
                    if (TICKET_ADMIN_USE_ORDER_IDS == 'true') echo '<td class="dataTableHeadingContent" align="left"><nobr><a href="' . $PHP_SELF . '?Sort=order-asc' . $oscid . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_ORDER_ID . TABLE_HEADING_SORT_NUM_ASC . '">+</a>&nbsp;' . TABLE_HEADING_ORDER_ID . '&nbsp;<a href="' . $PHP_SELF . '?Sort=order-desc' . $oscid . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_ORDER_ID . TABLE_HEADING_SORT_NUM_DESC . '">-</a>&nbsp;&nbsp;</nobr></td>';
                    if (TICKET_ADMIN_USE_STATUS == 'true') echo '<td class="dataTableHeadingContent" align="left"><nobr><a href="' . $PHP_SELF . '?Sort=status-asc' . $oscid . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_STATUS . TABLE_HEADING_SORT_TEXT_ASC . '">+</a>&nbsp;' . TABLE_HEADING_STATUS . '&nbsp;<a href="' . $PHP_SELF . '?Sort=status-desc' . $oscid . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_STATUS . TABLE_HEADING_SORT_TEXT_DESC . '">-</a>&nbsp;&nbsp;</nobr></td>';
                    if (TICKET_ADMIN_USE_PRIORITY == 'true') echo '<td class="dataTableHeadingContent" align="left"><nobr><a href="' . $PHP_SELF . '?Sort=priority-asc' . $oscid . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_PRIORITY . TABLE_HEADING_SORT_TEXT_ASC . '">+</a>&nbsp;' . TABLE_HEADING_PRIORITY . '&nbsp;<a href="' . $PHP_SELF . '?Sort=priority-desc' . $oscid . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_PRIORITY . TABLE_HEADING_SORT_TEXT_DESC . '">-</a>&nbsp;&nbsp;</nobr></td>';
                    if (TICKET_ADMIN_USE_DEPARTMENT == 'true') echo '<td class="dataTableHeadingContent" align="left"><nobr><a href="' . $PHP_SELF . '?Sort=department-asc' . $oscid . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_DEPARTMENT . TABLE_HEADING_SORT_TEXT_ASC . '">+</a>&nbsp;' . TABLE_HEADING_DEPARTMENT . '&nbsp;<a href="' . $PHP_SELF . '?Sort=department-desc' . $oscid . '" title="' . TABLE_HEADING_SORT . TABLE_HEADING_DEPARTMENT . TABLE_HEADING_SORT_TEXT_DESC . '">-</a>&nbsp;&nbsp;</nobr></td>';
                ?>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
                   if (isset($_GET['Sort'])) {
                     $Sort = $_GET['Sort'];
                     tep_session_register('Sort');
                   }              
                   switch ($Sort) {
                       case "id-asc":
                         $sort .= "ticket_id";
                       break;
                       case "id-desc":
                         $sort .= "ticket_id DESC";
                       break;
                       case "name-asc":
                         $sort .= "ticket_customers_name";
                       break;
                       case "name-desc":
                         $sort .= "ticket_customers_name DESC";
                       break;
                       case "customersID-asc":
                         $sort .= "ticket_customers_id";
                       break;
                       case "customersID-desc":
                         $sort .= "ticket_customers_id DESC";
                       break;
                       case "lastchange-asc":
                         $sort .= "ticket_date_last_customer_modified";
                       break;
                       case "lastchange-desc":
                         $sort .= "ticket_date_last_customer_modified DESC";
                       break;
                       case "subject-asc":
                         $sort .= "ticket_subject";
                       break;
                       case "subject-desc":
                         $sort .= "ticket_subject DESC";
                       break;
                       case "order-asc":
                         $sort .= "ticket_customers_orders_id";
                       break;
                       case "order-desc":
                         $sort .= "ticket_customers_orders_id DESC";
                       break;
                       case "status-asc":
                         $sort .= "ticket_status_id";
                       break;
                       case "status-desc":
                         $sort .= "ticket_status_id DESC";
                       break;
                       case "priority-asc":
                         $sort .= "ticket_priority_id";
                       break;
                       case "priority-desc":
                         $sort .= "ticket_priority_id DESC";
                       break;
                       case "department-asc":
                         $sort .= "ticket_department_id";
                       break;
                       case "department-desc":
                         $sort .= "ticket_department_id DESC";
                       break;
                       default:
                       $sort .= "ticket_date_last_customer_modified DESC";
                   }
                 //Modified/Added sorting ability by maestro eof -------------------------------------> 

                  $ticket_query_raw  = "select * from " . TABLE_TICKET_TICKET . " ";
                  if ($_GET['status'] || $_GET['department'] || $_GET['priority'] ) {
                    $sql_and = 'false';
                    $ticket_query_raw .= "where ";
                    if ($_GET['status']) {
                      $ticket_query_raw .= " ticket_status_id = '" . $_GET['status'] . "' ";
                      $sql_and = 'true';
                    }
                    if ($_GET['department']) {
                      if ($sql_and == 'true') $ticket_query_raw .= " and ";
                      $ticket_query_raw .= " ticket_department_id = '" . $_GET['department'] . "' ";
                      $sql_and = 'true';
                    }      
                    if ($_GET['priority']) {
                      if ($sql_and == 'true') $ticket_query_raw .= " and ";
                      $ticket_query_raw .= " ticket_priority_id = '" . $_GET['priority'] . "' ";
                      $sql_and = 'true';
                    }
                  } else if ($_GET['search']) {
                    $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
                    $ticket_query_raw .= " where ticket_id like '%" . $keywords . "%' or ticket_link_id like '%" . $keywords . "%' or ticket_customers_email  like '%" . $keywords . "%' or ticket_customers_name  like '%" . $keywords . "%' or ticket_subject  like '%" . $keywords . "%' ";
                  }
                  $ticket_query_raw .= "ORDER BY $sort";
                  
                  $ticket_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $ticket_query_raw, $ticket_query_numrows);
                  $ticket_query = tep_db_query($ticket_query_raw);

                  //Added for truncation START ------------>
                  function truncateSubject($subject, $subjectMaxLength) {  
                    $newSubject = substr($subject, 0, $subjectMaxLength); 
                    return $newSubject;  
                  }
                  function truncateName($name, $nameMaxLength) {  
                    $newName = substr($name, 0, $nameMaxLength); 
                    return $newName;  
                  }
                  function truncateStatus($status, $statusMaxLength) {  
                    $newStatus = substr($status, 0, $statusMaxLength); 
                    return $newStatus;  
                  }
                  function truncatePriority($priority, $priorityMaxLength) {  
                    $newPriority = substr($priority, 0, $priorityMaxLength); 
                    return $newPriority;  
                  }
                  function truncateDepartment($department, $departmentMaxLength) {  
                    $newDepartment = substr($department, 0, $departmentMaxLength); 
                    return $newDepartment;  
                  }
                  //Added for truncation END ------------>

                  while ($ticket = tep_db_fetch_array($ticket_query)) {

                  //Added for truncation START ------------>
                    // we assign the actual db info to a var
                    $subject = strip_tags($ticket['ticket_subject']);
                    $name = $ticket['ticket_customers_name'];
                    $status = $ticket_status_array[$ticket['ticket_status_id']];
                    $priority = $ticket_priority_array[$ticket['ticket_priority_id']];
                    $department = $ticket_department_array[$ticket['ticket_department_id']];

                    // we get the set maximum length of the vars
                    $subjectMaxLength = TICKET_LISTING_SUBJECT_TRUNCATE;
                    $nameMaxLength = TICKET_LISTING_NAME_TRUNCATE;
                    $statusMaxLength = TICKET_LISTING_STATUS_TRUNCATE;
                    $priorityMaxLength = TICKET_LISTING_PRIORITY_TRUNCATE;
                    $departmentMaxLength = TICKET_LISTING_DEPT_TRUNCATE;

                    //we assign the new lengths of the vars to a new var
                    $newSubject = truncateSubject($subject, $subjectMaxLength);
                    $newName = truncateName($name, $nameMaxLength);
                    $newStatus = truncateStatus($status, $statusMaxLength);
                    $newPriority = truncatePriority($priority, $priorityMaxLength);
                    $newDepartment = truncateDepartment($department, $departmentMaxLength);

                    //we check to see if the original data is longer than the new var and add "..." to denote it is truncated in the listing
                    if (strlen($ticket['ticket_subject']) > TICKET_LISTING_NAME_TRUNCATE) {
                      $newSubject .= '<font style="font-size: 6px;"> ...</font>';
                    }
                    if (strlen($ticket['ticket_customers_name']) > TICKET_LISTING_NAME_TRUNCATE) {
                      $newName .= '<font style="font-size: 6px;"> ...</font>';
                    }
                    if (strlen($ticket_status_array[$ticket['ticket_status_id']]) > TICKET_LISTING_NAME_TRUNCATE) {
                      $newStatus .= '<font style="font-size: 6px;"> ...</font>';
                    }
                    if (strlen($ticket_priority_array[$ticket['ticket_priority_id']]) > TICKET_LISTING_PRIORITY_TRUNCATE) {
                      $newPriority .= '<font style="font-size: 6px;"> ...</font>';
                    }
                    if (strlen($ticket_department_array[$ticket['ticket_department_id']]) > TICKET_LISTING_DEPT_TRUNCATE) {
                      $newDepartment .= '<font style="font-size: 6px;"> ...</font>';
                    }
                  //Added for truncation END ------------>

                    if (((!$_GET['tID']) || ($_GET['tID'] == $ticket['ticket_id'])) && (!$tInfo)) {
                      $tInfo = new objectInfo($ticket);
                    }
                    if ( (is_object($tInfo)) && ($ticket['ticket_id'] == $tInfo->ticket_id) ) {
                      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TICKET_VIEW, tep_get_all_get_params(array('tID', 'action')) . 'tID=' . $tInfo->ticket_id . '&action=edit') . '\'">' . "\n";
                    } else {
                      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TICKET_VIEW, tep_get_all_get_params(array('tID')) . 'tID=' . $ticket['ticket_id']) . '\'">' . "\n";
                    }
                    $time = tep_datetime_short($ticket['ticket_date_last_modified']);
                    $ticket_hint_data_query = tep_db_query("SELECT ticket_comments, ticket_edited_by, ticket_date_modified FROM " . TABLE_TICKET_STATUS_HISTORY . " WHERE ticket_id = '" . $ticket['ticket_id'] . "' ORDER BY ticket_date_modified DESC LIMIT 1");
                    $ticket_hint_data = tep_db_fetch_array($ticket_hint_data_query);
                    //echo $ticket_hint_data['ticket_comments'];
                    $hint_subject = $ticket['ticket_subject'];
                    $new_hint_subject = str_replace("'", "", $hint_subject);
                    $ticket_hint = TEXT_TICKET_HINT_SUBJECT . $new_hint_subject . '<br />' . TEXT_TICKET_HINT_LAST_UPDATED . tep_datetime_short($ticket['ticket_date_last_modified']) . '<br />' . TEXT_TICKET_HINT_LAST_CUSTOMER_UPDATED . tep_datetime_short($ticket['ticket_date_last_customer_modified']) . '<br />' . TEXT_TICKET_HINT_LAST_REPLY_BY . $ticket_hint_data['ticket_edited_by'] . '<br />' . TEXT_TICKET_HINT_LAST_REPLY . strip_tags(htmlspecialchars(str_replace("\r", "&#13;", str_replace("\n", "&#10;", str_replace("'", "&#39;", $ticket_hint_data['ticket_comments'])))));
              ?>
                <!-- style="background-image: url('images/icons/ticket_tag.gif'); background-repeat: no-repeat; background-position: 2px 3px;"-->
                <td class="dataTableContent"><a href="<?php echo tep_href_link(FILENAME_TICKET_VIEW, tep_get_all_get_params(array('tID', 'action')) . 'tID=' . $ticket['ticket_id'] . '&action=edit'); ?>" onMouseover="showhint('<?php echo $ticket_hint; ?>', this, event, '400px'); return false"><?php echo tep_image(DIR_WS_ICONS . 'ticket_tag.gif'); ?></a>&nbsp;<b><?php echo $ticket['ticket_id']; ?></b>&nbsp;</td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_TICKET_VIEW, tep_get_all_get_params(array('tID', 'action')) . 'tID=' . $ticket['ticket_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'user.gif', ICON_EDIT_TICKET) . '</a>&nbsp;<span title="' . $ticket['ticket_customers_name'] . '">' . $newName . '</span>'; ?></td>
                <td class="dataTableContent">&nbsp; &nbsp;<?php echo ($ticket['ticket_customers_id'] > 0) ? ('<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'cID=' . $ticket['ticket_customers_id'] . '&action=edit') . '"><span title="' . TICKET_TEXT_TITLE_NAME . '">' . $ticket['ticket_customers_id'] . '</span></a>')  : '--'; ?>&nbsp;</td>
                <td class="dataTableContent"><?php echo '&nbsp; &nbsp;<span title="' . $time . '">' . tep_date_short($ticket['ticket_date_last_modified']); ?></span></td>
                <?php
                      if (TICKET_ADMIN_USE_SUBJECT == 'true') echo '                <td class="dataTableContent"><span title="' . strip_tags($ticket['ticket_subject']) . '">&nbsp; &nbsp;' . $newSubject . '</span>&nbsp;</td>';
                      if (TICKET_ADMIN_USE_ORDER_IDS == 'true') echo '                <td class="dataTableContent">&nbsp; &nbsp;' . (($ticket['ticket_customers_orders_id'] > 0) ? ('<a href="' . tep_href_link(FILENAME_ORDERS, 'oID=' . $ticket['ticket_customers_orders_id'] . '&action=edit') . '"><span title="' . TICKET_TEXT_TITLE_ORDER . '">' . $ticket['ticket_customers_orders_id'] . '</span></a>') : '--') . '&nbsp;</td>';
                      if (TICKET_ADMIN_USE_STATUS == 'true') echo '                <td class="dataTableContent">&nbsp; &nbsp;<span title="' . $ticket_status_array[$ticket['ticket_status_id']] . '">' . $newStatus . '</span>&nbsp;</td>';
                      if (TICKET_ADMIN_USE_PRIORITY == 'true') echo '                <td class="dataTableContent">&nbsp; &nbsp;<span title="' . $ticket_priority_array[$ticket['ticket_priority_id']] . '">' . $newPriority . '</span></td>';
                      if (TICKET_ADMIN_USE_DEPARTMENT == 'true') echo '                <td class="dataTableContent">&nbsp; &nbsp;<span title="' . $ticket_department_array[$ticket['ticket_department_id']] . '">' . $newDepartment . '</span></td>';
                ?>
                <td class="dataTableContent" align="right">
                <?php 
                  if ( (is_object($tInfo)) && ($ticket['ticket_id'] == $tInfo->ticket_id) ) {
                    echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
                  } else { 
                    echo '<a href="' . tep_href_link(FILENAME_TICKET_VIEW, tep_get_all_get_params(array('tID')) . 'tID=' . $ticket['ticket_id']) . '">' . tep_image(DIR_WS_ICONS . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                  }
                ?>
                &nbsp;
                </td>
              </tr>
              <?php
                  }
              ?>    
              <tr>
                <td colspan="10"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $ticket_split->display_count($ticket_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TICKET); ?></td>
                    <td class="smallText" align="right"><?php echo $ticket_split->display_links($ticket_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'tID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <?php
              $heading = array();
              $contents = array();
              switch ($_GET['action']) {
                case 'delete':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_TICKET . '</b>');
            
                  $contents = array('form' => tep_draw_form('orders', FILENAME_TICKET_VIEW, tep_get_all_get_params(array('tID', 'action')) . 'tID=' . $tInfo->ticket_id . '&action=deleteconfirm'));
                  $contents[] = array('align' => 'left', 'text' => $tInfo->ticket_subject);
                  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_TICKET_VIEW) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                  $contents[] = array('form' => '</form>');
                  break;
                default:
                  if (is_object($tInfo)) {
            
                    $heading[] = array('text' => '<b>[' . $tInfo->ticket_id . ']&nbsp;&nbsp;' . $tInfo->ticket_subject . '</b>');
            
                    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_TICKET_VIEW, tep_get_all_get_params(array('tID', 'action')) . 'tID=' . $tInfo->ticket_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_TICKET_VIEW, tep_get_all_get_params(array('tID', 'action')) . 'tID=' . $tInfo->ticket_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
                    $contents[] = array('text' => '<br>' . TEXT_DATE_TICKET_CREATED . ' ' . tep_datetime_short($tInfo->ticket_date_created));
                    if (tep_not_null($tInfo->ticket_date_last_modified)) $contents[] = array('text' => TEXT_DATE_TICKET_LAST_MODIFIED . ' ' . tep_datetime_short($tInfo->ticket_date_last_modified));
                    if (tep_not_null($tInfo->ticket_date_last_customer_modified)) $contents[] = array('text' => TEXT_DATE_TICKET_LAST_CUSTOMER_MODIFIED . ' ' . tep_datetime_short($tInfo->ticket_date_last_customer_modified));
                    if (TICKET_ADMIN_USE_STATUS=='true') $contents[] = array('text' => '<br>' . TEXT_STATUS . ' ' . $ticket_status_array[$tInfo->ticket_status_id]);
                    if (TICKET_ADMIN_USE_PRIORITY=='true') $contents[] = array('text' => '<br>' . TEXT_PRIORITY . ' ' . $ticket_priority_array[$tInfo->ticket_priority_id]);
                    if (TICKET_ADMIN_USE_DEPARTMENT=='true') $contents[] = array('text' => '<br>' . TEXT_DEPARTMENT . ' ' . $ticket_department_array[$tInfo->ticket_department_id]);  
                    $contents[] = array('text' => '<br>' . TEXT_TICKET_NR . ' ' . $tInfo->ticket_link_id);  
                    $contents[] = array('text' => '<br>' . TEXT_CUSTOMERS_NAME . ' ' . $tInfo->ticket_customers_name); 
                    $contents[] = array('text' => '<br>' . TEXT_CUSTOMERS_EMAIL . ' ' . $tInfo->ticket_customers_email);
                    if ($tInfo->ticket_customers_id > 0) $contents[] = array('text' => TEXT_CUSTOMERS_ID . ' ' . '<a href="' . tep_href_link(FILENAME_CUSTOMERS,"cID=" . $tInfo->ticket_customers_id ."&action=edit") . '">' . $tInfo->ticket_customers_id . '</a>');  
                    if (TICKET_ADMIN_USE_ORDER_IDS=='true') $contents[] = array('text' => '<br>' . TEXT_CUSTOMERS_ORDERS_ID . ' ' . '<a href="' . tep_href_link(FILENAME_ORDERS,"oID=" . $tInfo->ticket_customers_orders_id ."&action=edit") . '">' . $tInfo->ticket_customers_orders_id . '</a>');  
                  }
                  break;
              }
            
              if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                echo '            <td width="25%" valign="top">' . "\n";

                $box = new box;
                echo $box->infoBox($heading, $contents);
            
                echo '            </td>' . "\n";
              }
            ?>
          </tr>
        </table></td>
      </tr>
      <?php
        }
      ?>
      </tr>
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
<?php if (!isset($tID)) { ?>
<div id="ticketViewTop" style="position:absolute; top:0; width:100%;">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" style="padding: 4px 10px 4px 10px; background:pink;">
    <tr>
      <td>
      <?php      
        // Tickets At-A-Glance START
        $all_tickets_query = tep_db_query("select count(*) as count from " . TABLE_TICKET_TICKET);
        if ($all_tickets) {
            $all_tickets = tep_db_fetch_array($all_tickets_query);
            $count = $all_tickets['count'];
        } else {
            $count = 'None';
        }
        echo '<nobr><a href="' . tep_href_link(FILENAME_TICKET_VIEW, 'selected_box=ticket') . '">All</a>: ' . $count;
        $ticket_status_query = tep_db_query("select ticket_status_name, ticket_status_id from " . TABLE_TICKET_STATUS . " where ticket_language_id = '" . $languages_id . "'");
        while ($ticket_status = tep_db_fetch_array($ticket_status_query)) {
          $ticket_ticket_query = tep_db_query("select count(*) as count from " . TABLE_TICKET_TICKET . " where ticket_status_id = '" . $ticket_status['ticket_status_id'] . "'");
          $ticket_ticket = tep_db_fetch_array($ticket_ticket_query);
          echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_TICKET_VIEW, 'selected_box=ticket&status=' . $ticket_status['ticket_status_id']) . '">' . $ticket_status['ticket_status_name'] . '</a>: ' . $ticket_ticket['count'] . '</nobr>';
        }
        // Tickets At-A-Glance END
      ?>
      </td>
      <td align="right">
      <?php
        if (tep_not_null(TICKET_LISTING_PAGE_REFRESH) && (TICKET_LISTING_PAGE_REFRESH > '0')) {
          include('includes/javascript/refresh.js.php');
        }
      ?>
      </td>
    </tr>
  </table>
</div>
<?php } ?>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>