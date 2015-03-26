<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('testimonial', 'top');
// RCI code eof
?>                 
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
  if ($messageStack->size('testimonial') > 0) {
?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo $messageStack->output('testimonial'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
    </table></td>
  </tr>
  <?php
  }
    if(!isset($_GET['action'])){

// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <?php
          // BOF: Lango Added for template MOD
          if (MAIN_TABLE_BORDER == 'yes'){
          table_image_border_top(false, false, $header_text);
          }
          // EOF: Lango Added for template MOD
          ?>
            <tr>
              <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="100%" align="center" class="main">
                    <?php
                      if (isset($_GET['testimonial_id']) && $_GET['testimonial_id'] != '') {
                          if(!is_numeric($_GET['testimonial_id'])){
                    ?>
                    <script type="text/javascript">
                    <!--
                    window.location = "<?php echo tep_href_link(FILENAME_CUSTOMER_TESTIMONIALS,'','NONSSL');?>"
                    //-->
                    </script>
                    <?php
                          }
                        $testimonial_id = tep_db_prepare_input($_GET['testimonial_id']);
                        $full_testimonial = tep_db_query("select * FROM " . TABLE_CUSTOMER_TESTIMONIALS . " WHERE testimonials_id = '" . (int)$testimonial_id . "'");
                      } else {
                        $full_testimonial = tep_db_query("select * FROM " . TABLE_CUSTOMER_TESTIMONIALS . " WHERE status = '1' order by rand()");
                      }
                      while ($testimonials = tep_db_fetch_array($full_testimonial)) {
                        $testimonial_array[] = array('id' => $testimonials['testimonials_id'],
                                                     'author' => $testimonials['testimonials_name'],
                                                                                'location' => $testimonials['testimonials_location'],
                                                                                'title' => $testimonials['testimonials_title'],
                                                     'testimonial' => $testimonials['testimonials_html_text'],
                                                     'word_count' => tep_word_count($testimonials['testimonials_html_text'], ' '),
                                                     'url' => $testimonials['testimonials_url'],
                                                     'url_title' => $testimonials['testimonials_url_title']);
                      }
                      require(DIR_WS_MODULES . 'customer_testimonials.php');

                      function ct_sanitise($ctmsanitise) {
                        $ctmsanitise = preg_replace("/[^0-9]/i", "", $ctmsanitise);
                        return $ctmsanitise;
                      } 
                    ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          <?php
          // RCI code start
          echo $cre_RCI->get('testimonial', 'menu');
          // RCI code eof
          // BOF: Lango Added for template MOD
          if (MAIN_TABLE_BORDER == 'yes'){
          table_image_border_bottom();
          }
          // EOF: Lango Added for template MOD
          ?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

<?php
}
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TEXT_TESTIMONIALS_INTRO; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = TEXT_TESTIMONIALS_INTRO;
}
// EOF: Lango Added for template MOD
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD

if($_GET['action'] != 'success'){
?>

<tr>
  <form name="customer_testimonial" method="post" action="<?php echo tep_href_link(FILENAME_CUSTOMER_TESTIMONIALS, 'action=insert', 'NONSSL'); ?>"  >
  <td><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td width="20">&nbsp;</td>
      <td class="main"><?php echo TEXT_TESTIMONIALS_TITLE; ?></td>
      <td class="main"><?php echo tep_draw_input_field('testimonials_title',  $testimonials_title, '', true); ?></td>
    </tr>
    <tr>
      <td width="20">&nbsp;</td>
      <td class="main"><?php echo TEXT_TESTIMONIALS_NAME; ?></td>
      <td class="main"><?php echo tep_draw_input_field('testimonials_name', $testimonials_name, '', true); ?></td>
    </tr>
    <tr>
      <td width="20">&nbsp;</td>
      <td class="main" nowrap><?php echo TEXT_TESTIMONIALS_LOCATION; ?></td>
      <td class="main"><?php echo tep_draw_input_field('testimonials_location', $testimonials_location); ?></td>
    </tr>
    <tr>
      <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <tr>
      <td width="20">&nbsp;</td>
      <td valign="top" class="main"><?php echo TEXT_BANNERS_HTML_TEXT; ?></td>
      <td class="main"><?php echo tep_draw_textarea_field('html_text', 'soft', '64', '8', $html_text); ?></td>
    </tr>
    <!-- VISUAL VERIFY CODE start -->
    <?php if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
          if (defined('MODULE_ADDONS_CTM_VVC_ON_OFF') && MODULE_ADDONS_CTM_VVC_ON_OFF == 'On'){
?>
    <tr>
      <td width="20">&nbsp;</td>
      <td colspan="2" class="main"><b><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?></b></td>
    </tr>
    <tr>
      <td width="20">&nbsp;</td>
      <td colspan="2"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
                <tr>
                  <td class="main"><?php echo VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS; ?></td>
                  <td class="main"><?php echo tep_draw_input_field('visual_verify_code') . '&nbsp;' . '<span class="inputRequirement">' . VISUAL_VERIFY_CODE_ENTRY_TEXT . '</span>'; ?></td>
                  <td class="main"><?php
                      //can replace the following loop with $visual_verify_code = substr(str_shuffle (VISUAL_VERIFY_CODE_CHARACTER_POOL), 0, rand(3,6)); if you have PHP 4.3
                    $visual_verify_code = "";
                    for ($i = 1; $i <= rand(3,6); $i++){
                          $visual_verify_code = $visual_verify_code . substr(VISUAL_VERIFY_CODE_CHARACTER_POOL, rand(0, strlen(VISUAL_VERIFY_CODE_CHARACTER_POOL)-1), 1);
                     }
                     $vvcode_oscsid = tep_session_id();
                     tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . $vvcode_oscsid . "'");
                     $sql_data_array = array('oscsid' => $vvcode_oscsid, 'code' => $visual_verify_code);
                     tep_db_perform(TABLE_VISUAL_VERIFY_CODE, $sql_data_array);
                     $visual_verify_code = "";
                     echo('<img src="' . FILENAME_VISUAL_VERIFY_CODE_DISPLAY . '?vvc=' . $vvcode_oscsid . '" alt="' . VISUAL_VERIFY_CODE_CATEGORY . '">');
                  ?></td>
                  <td class="main"><?php echo VISUAL_VERIFY_CODE_BOX_IDENTIFIER; ?></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <!--  VISUAL VERIFY CODE stop   -->
<?php
 }
}
?>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr><td width="20">&nbsp;</td>
    <td colspan="2">
      <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right" class="main"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_INSERT) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMER_TESTIMONIALS, '', 'NONSSL') . '" ?></td>'; ?>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php
 }else {
?>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
    <td>
      <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="center" class="main"><b><?php echo TEXT_TESTIMONIALS_SUCCESSFUL; ?></b></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMER_TESTIMONIALS) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
}
// RCI code start
echo $cre_RCI->get('testimonial', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
        </table></td>
      </tr>
    </table>      </td>
      </tr>
    </table>
<?php 
    if (!isset($_GET['testimonial_id']) && $_GET['testimonial_id'] == '') { ?>
       </td>
      </tr>
    </table>
<?php
    }
// RCI code start
echo $cre_RCI->get('testimonial', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>