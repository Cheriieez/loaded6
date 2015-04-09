<?php
/*
  $Id: ticket_status.php,v 1.6 2003/07/13 20:22:02 hook Exp $

  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  switch ($_GET['action']) {
    case 'insert':
    case 'save':
      $ticket_status_id = tep_db_prepare_input($_GET['oID']);

      $languages = tep_get_languages();
      $ticket_status_name_array = $_POST['ticket_status_name'];
      for ($i = 0; $i < sizeof($languages); $i++) {
        $ticket_language_id = $languages[$i]['id'];
        $sql_data_array = array('ticket_status_name' => tep_db_prepare_input($ticket_status_name_array[$ticket_language_id]));

        if ($_GET['action'] == 'insert') {
          if (!tep_not_null($ticket_status_id)) {
            $next_id_query = tep_db_query("select max(ticket_status_id) as ticket_status_id from " . TABLE_TICKET_STATUS . "");
            $next_id = tep_db_fetch_array($next_id_query);
            $ticket_status_id = $next_id['ticket_status_id'] + 1;
          }

          $insert_sql_data = array('ticket_status_id' => $ticket_status_id,
                                   'ticket_language_id' => $ticket_language_id);
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          tep_db_perform(TABLE_TICKET_STATUS, $sql_data_array);
        } elseif ($_GET['action'] == 'save') {
          tep_db_perform(TABLE_TICKET_STATUS, $sql_data_array, 'update', "ticket_status_id = '" . tep_db_input($ticket_status_id) . "' and ticket_language_id = '" . $ticket_language_id . "'");
        }
      }

      if ($_POST['default'] == 'on') {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($ticket_status_id) . "' where configuration_key = 'TICKET_DEFAULT_STATUS_ID'");
      }
      if ($_POST['default2'] == 'on') {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($ticket_status_id) . "' where configuration_key = 'TICKET_CUSTOMER_REPLY_STATUS_ID'");
      }
      if ($_POST['default3'] == 'on') {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($ticket_status_id) . "' where configuration_key = 'TICKET_DEFAULT_ADMIN_STATUS_ID'");
      }

      tep_redirect(tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&oID=' . $ticket_status_id));
      break;
    case 'deleteconfirm':
      $oID = tep_db_prepare_input($_GET['oID']);

      $ticket_status_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'TICKET_DEFAULT_STATUS_ID'");
      $ticket_status = tep_db_fetch_array($ticket_status_query);
      if ($ticket_status['configuration_value'] == $oID) {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'TICKET_DEFAULT_STATUS_ID'");
      }
      $ticket_status_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'TICKET_CUSTOMER_REPLY_STATUS_ID'");
      $ticket_status = tep_db_fetch_array($ticket_status_query);
      if ($ticket_status['configuration_value'] == $oID) {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'TICKET_CUSTOMER_REPLY_STATUS_ID'");
      }
      $ticket_status_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'TICKET_DEFAULT_ADMIN_STATUS_ID'");
      $ticket_status = tep_db_fetch_array($ticket_status_query);
      if ($ticket_status['configuration_value'] == $oID) {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'TICKET_DEFAULT_ADMIN_STATUS_ID'");
      }
      tep_db_query("delete from " . TABLE_TICKET_STATUS . " where ticket_status_id = '" . tep_db_input($oID) . "'");

      tep_redirect(tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page']));
      break;
    case 'delete':
      $oID = tep_db_prepare_input($_GET['oID']);
      $status_query = tep_db_query("select count(*) as count from " . TABLE_TICKET_TICKET . " where ticket_status_id = '" . tep_db_input($oID) . "'");
      $status = tep_db_fetch_array($status_query);

      $remove_status = true;
      if ($oID == TICKET_DEFAULT_STATUS_ID) {
        $remove_status = false;
        $messageStack->add(ERROR_REMOVE_DEFAULT_TEXT_STATUS, 'error');
      } elseif ($status['count'] > 0) {
        $remove_status = false;
        $messageStack->add(ERROR_STATUS_USED_IN_TICKET, 'error');
      } else {
        $history_query = tep_db_query("select count(*) as count from " . TABLE_TICKET_STATUS_HISTORY . " where ticket_status_id = '" . tep_db_input($oID) . "'");
        $history = tep_db_fetch_array($history_query);
        if ($history['count'] > 0) {
          $remove_status = false;
          $messageStack->add(ERROR_STATUS_USED_IN_HISTORY, 'error');
        }
      }
      break;
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" valign="top"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TEXT_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $ticket_status_query_raw = "select ticket_status_id, ticket_status_name from " . TABLE_TICKET_STATUS . " where ticket_language_id = '" . $languages_id . "' order by ticket_status_id";
  $ticket_status_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $ticket_status_query_raw, $ticket_status_query_numrows);
  $ticket_status_query = tep_db_query($ticket_status_query_raw);
  while ($ticket_status = tep_db_fetch_array($ticket_status_query)) {
    if (((!$_GET['oID']) || ($_GET['oID'] == $ticket_status['ticket_status_id'])) && (!$oInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
      $oInfo = new objectInfo($ticket_status);
    }

    if ( (is_object($oInfo)) && ($ticket_status['ticket_status_id'] == $oInfo->ticket_status_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->ticket_status_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&oID=' . $ticket_status['ticket_status_id']) . '\'">' . "\n";
    }

    if (TICKET_DEFAULT_STATUS_ID == $ticket_status['ticket_status_id'] || TICKET_CUSTOMER_REPLY_STATUS_ID == $ticket_status['ticket_status_id'] || TICKET_DEFAULT_ADMIN_STATUS_ID == $ticket_status['ticket_status_id'] ) {
      echo '                <td class="dataTableContent">' . $ticket_status['ticket_status_name'] . ((TICKET_DEFAULT_STATUS_ID == $ticket_status['ticket_status_id']) ? ' <b>(' .  TEXT_DEFAULT . ')</b>' : '') . '  ' . ((TICKET_CUSTOMER_REPLY_STATUS_ID == $ticket_status['ticket_status_id']) ? ' <b>(' .  TEXT_DEFAULT_REPLY . ')</b>' : '') . '  ' . ((TICKET_DEFAULT_ADMIN_STATUS_ID == $ticket_status['ticket_status_id']) ? ' <b>(' .  TEXT_ADMIN_DEFAULT . ')</b>' : '') . '</td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $ticket_status['ticket_status_name'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent" align="right"><?php if ( (is_object($oInfo)) && ($ticket_status['ticket_status_id'] == $oInfo->ticket_status_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&oID=' . $ticket_status['ticket_status_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $ticket_status_split->display_count($ticket_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TEXT_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $ticket_status_split->display_links($ticket_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (substr($_GET['action'], 0, 3) != 'new') {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_TEXT_STATUS . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $ticket_status_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $ticket_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('ticket_status_name[' . $languages[$i]['id'] . ']');
      }

      $contents[] = array('text' => '<br>' . TEXT_INFO_TEXT_STATUS_NAME . $ticket_status_inputs_string);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default2') . ' ' . TEXT_SET_DEFAULT_REPLY);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default3') . ' ' . TEXT_SET_ADMIN_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_TEXT_STATUS . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->ticket_status_id  . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

      $ticket_status_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $ticket_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('ticket_status_name[' . $languages[$i]['id'] . ']', tep_get_ticket_status_name($oInfo->ticket_status_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br>' . TEXT_INFO_TEXT_STATUS_NAME . $ticket_status_inputs_string);
      if (TICKET_DEFAULT_STATUS_ID != $oInfo->ticket_status_id) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      if (TICKET_CUSTOMER_REPLY_STATUS_ID != $oInfo->ticket_status_id) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default2') . ' ' . TEXT_SET_DEFAULT_REPLY);
      if (TICKET_DEFAULT_ADMIN_STATUS_ID != $oInfo->ticket_status_id) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default3') . ' ' . TEXT_SET_ADMIN_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->ticket_status_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_TEXT_STATUS . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->ticket_status_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $oInfo->ticket_status_name . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->ticket_status_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($oInfo)) {
        $heading[] = array('text' => '<b>' . $oInfo->ticket_status_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->ticket_status_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_TICKET_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->ticket_status_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

        $ticket_status_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $ticket_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_get_ticket_status_name($oInfo->ticket_status_id, $languages[$i]['id']);
        }

        $contents[] = array('text' => $ticket_status_inputs_string);
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
