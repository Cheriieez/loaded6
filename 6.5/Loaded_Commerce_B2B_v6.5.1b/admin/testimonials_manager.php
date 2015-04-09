<?php
/*
  $Id: testimonials_manager.php,v 1.3 2007/09/03 meastro Exp $

  Contribution Central, Custom CRE Loaded Programming
  http://www.contributioncentral.com
  Copyright (c) 2007 Contribution Central

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/functions/CTM_functions.php');

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          tep_set_TESTIMONIALS_status($_GET['tID'], $_GET['flag']);
          $messageStack->add_session(SUCCESS_TESTIMONIALS_STATUS_UPDATED, 'success');
        } else {
          $messageStack->add_session(ERROR_UNKNOWN_STATUS_FLAG, 'error');
        }

        tep_redirect(tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $_GET['tID']));
        break;
      case 'insert':
      case 'update':
        $testimonials_id = tep_db_prepare_input($_POST['testimonials_id']);
        $testimonials_title = tep_db_prepare_input($_POST['testimonials_title']);
        $testimonials_name = tep_db_prepare_input($_POST['testimonials_name']);
        $testimonials_location = tep_db_prepare_input($_POST['testimonials_location']);
        $html_text = tep_db_prepare_input($_POST['html_text']);

        $testimonials_error = false;
        
        if (empty($testimonials_title)) {
          $messageStack->add(ERROR_TESTIMONIALS_TITLE_REQUIRED, 'error');
          $testimonials_error = true;
        }
        if (empty($testimonials_name)) {
          $messageStack->add(ERROR_TESTIMONIALS_NAME_REQUIRED, 'error');
          $testimonials_error = true;
        }
        if (empty($html_text)) {
          $messageStack->add(ERROR_TESTIMONIALS_DESCRIPTION_REQUIRED, 'error');
          $testimonials_error = true;
        }

        if (!$testimonials_error) {
          $sql_data_array = array('testimonials_title' => $testimonials_title,
                                  'testimonials_name' => $testimonials_name,
                  'testimonials_location' => $testimonials_location,                                  
                                  'testimonials_html_text' => $html_text);

          if ($_GET['action'] == 'insert') {
            $insert_sql_data = array('date_added' => 'now()',
                                     'status' => '1');
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_CUSTOMER_TESTIMONIALS, $sql_data_array);
            $testimonials_id = tep_db_insert_id();
            $messageStack->add_session(SUCCESS_TESTIMONIALS_INSERTED, 'success');
          } elseif ($_GET['action'] == 'update') {
            tep_db_perform(TABLE_CUSTOMER_TESTIMONIALS, $sql_data_array, 'update', 'testimonials_id = \'' . $testimonials_id . '\'');
            $messageStack->add_session(SUCCESS_TESTIMONIALS_UPDATED, 'success');
          }
          tep_redirect(tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $testimonials_id));
        } else {
          $_GET['action'] = 'new';
        }
        break;
      case 'deleteconfirm':
        $testimonials_id = tep_db_prepare_input($_GET['tID']);

        tep_db_query("delete from " . TABLE_CUSTOMER_TESTIMONIALS . " where testimonials_id = '" . tep_db_input($testimonials_id) . "'");

        $messageStack->add_session(SUCCESS_TESTIMONIALS_REMOVED, 'success');

        tep_redirect(tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page']));
        break;
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <!-- START added by maestro for testimonials approval reporting -->
      <!--tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr class="dataTableHeadingRow">
            <td valign="top" class="dataTableHeadingContent">&nbsp;<b><?php echo HEADING_APPROVALS; ?></b></td>
          </tr>
        </table></td>
      </tr -->
      <tr>
        <td class="main">
        <?php
          $testimonials_approved_query = tep_db_query("select count(*) as testimonials_approved from " . TABLE_CUSTOMER_TESTIMONIALS . " where status = '1'");
          $testimonials_approved = tep_db_fetch_array($testimonials_approved_query);
          //added for debugging
          //print_r($testimonials_approved['testimonials_approved']);

          $testimonials_not_approved_query = tep_db_query("select count(*) as testimonials_not_approved from " . TABLE_CUSTOMER_TESTIMONIALS . " where status = '0'");
          $testimonials_not_approved = tep_db_fetch_array($testimonials_not_approved_query);
          //added for debugging
          //print_r($testimonials_not_approved['testimonials_not_approved']);

          //$latest_testimonial_query = tep_db_query("select date_added from " . TABLE_CUSTOMER_TESTIMONIALS . " where date_added = (select max(date_added) from " . TABLE_CUSTOMER_TESTIMONIALS . ")");
          //$latest_testimonial = tep_db_fetch_array($latest_testimonial_query);
          //print_r($latest_testimonial);

          echo TEXT_TESTIMONIALS_APPROVED_START . $testimonials_approved['testimonials_approved'] . TEXT_TESTIMONIALS_APPROVED_MIDDLE . $testimonials_not_approved['testimonials_not_approved'] . TEXT_TESTIMONIALS_APPROVED_END;
        ?>
        </td>
      </tr>
      <!-- END added by maestro for testimonials approval reporting -->
<?php
  if ($_GET['action'] == 'new') {
    $form_action = 'insert';
    if ($_GET['tID']) {
      $tID = tep_db_prepare_input($_GET['tID']);
      $form_action = 'update';

      $testimonials_query = tep_db_query("select * from " . TABLE_CUSTOMER_TESTIMONIALS . " where testimonials_id = '" . tep_db_input($tID) . "'");
      $testimonials = tep_db_fetch_array($testimonials_query);

      $tInfo = new objectInfo($testimonials);
    } elseif ($_POST) {
      $bInfo = new objectInfo($_POST);
    } else {
      $bInfo = new objectInfo(array());
    }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('new_banner', FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"'); if ($form_action == 'update') echo tep_draw_hidden_field('testimonials_id', $tID); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_TESTIMONIALS_TITLE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('testimonials_title', $tInfo->testimonials_title, '', true); ?></td>
          </tr>
            <td class="main"><?php echo TEXT_TESTIMONIALS_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('testimonials_name', $tInfo->testimonials_name, '', true); ?></td>
          </tr>
                <tr>
            <td class="main"><?php echo TEXT_TESTIMONIALS_LOCATION; ?></td>
            <td class="main"><?php echo tep_draw_input_field('testimonials_location', $tInfo->testimonials_location); ?></td>
          </tr>          
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td valign="top" class="main"><?php echo TEXT_BANNERS_HTML_TEXT; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('html_text', 'soft', '60', '5', $tInfo->testimonials_html_text); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2" class="main" valign="top" nowrap align="center"><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $_GET['tID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TESTIMONIALS_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TESTIMONIALS_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TESTIMONIALS_DESCRIPTION; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $testimonials_query_raw = "select * from " . TABLE_CUSTOMER_TESTIMONIALS . " order by testimonials_id";
    $testimonials_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $testimonials_query_raw, $testimonials_query_numrows);
    $testimonials_query = tep_db_query($testimonials_query_raw);
    while ($testimonials = tep_db_fetch_array($testimonials_query)) {
      if (((!$_GET['tID']) || ($_GET['tID'] == $testimonials['testimonials_id'])) && (!$tInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
        $tInfo = new objectInfo($testimonials);
      }

      if ( (is_object($tInfo)) && ($testimonials['testimonials_id'] == $tInfo->testimonials_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->testimonials_id) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $testimonials['testimonials_id']) . '\'">' . "\n";
      }
?>
                <td nowrap class="dataTableContent"><?php echo $testimonials['testimonials_id']; ?></td>
                <td nowrap class="dataTableContent"><?php echo $testimonials['testimonials_name']; ?></td>
                <td class="dataTableContent"><?php echo substr($testimonials['testimonials_html_text'], 0, 100); ?>...</td>
                <td class="dataTableContent" align="right">
                <?php
                  if ($testimonials['status'] == '1') {
                    echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Active', 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $testimonials['testimonials_id'] . '&action=setflag&flag=0') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Inactive', 10, 10) . '</a>';
                  } else {
                    echo '<a href="' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $testimonials['testimonials_id'] . '&action=setflag&flag=1') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Active', 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Inactive', 10, 10);
                  }
                ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($tInfo)) && ($testimonials['testimonials_id'] == $tInfo->testimonials_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $testimonials['testimonials_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $testimonials_split->display_count($testimonials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TESTIMONIALS); ?></td>
                    <td class="smallText" align="right"><?php echo $testimonials_split->display_links($testimonials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=new') . '">' . tep_image_button('button_new_testimonial.gif', IMAGE_NEW_TESTIMONIAL) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'delete':
      $heading[] = array('text' => '<b>' . $tInfo->testimonials_title . '</b>');

      $contents = array('form' => tep_draw_form('testimonials', FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->testimonials_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $tInfo->testimonials_title . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $_GET['tID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($tInfo)) {
        $heading[] = array('text' => '<b>' . $tInfo->testimonials_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->testimonials_id . '&action=new') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->testimonials_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('align' => 'center', 'text' => '<a target="_blank" href="' . tep_href_link('../' . FILENAME_CUSTOMER_TESTIMONIALS, 'testimonial_id=' . $_GET['tID']) . '">' . tep_image_button('button_view_in_catalog.gif', IMAGE_VIEW_IN_CATALOG) . '</a>');
        $contents[] = array('text' =>  '<br><b>' . TEXT_TESTIMONIALS_TITLE . '</b>: ' . $tInfo->testimonials_title);
        $contents[] = array('text' =>  '<br><b>' . TEXT_TESTIMONIALS_NAME . '</b>: ' . $tInfo->testimonials_name);
        $contents[] = array('text' =>  '<br><b>' . TEXT_TESTIMONIALS_LOCATION . '</b>: ' . $tInfo->testimonials_location);
        $contents[] = array('text' => '<br><b>' . TEXT_BANNERS_HTML_TEXT . '</b>:<br>' . $tInfo->testimonials_html_text . '<br>');
        $contents[] = array('text' => '<br>' . TEXT_TESTIMONIALS_DATE_ADDED . ' ' . tep_date_short($tInfo->date_added));
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
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>