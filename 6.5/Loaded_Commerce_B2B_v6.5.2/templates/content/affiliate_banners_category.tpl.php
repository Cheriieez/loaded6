<?php
$affiliate_id = $_SESSION['affiliate_id'];
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('affiliatebannerscategory', 'top');
// RCI code eof
?>

<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
  <tr>
    <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'affiliate_links.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td class="main" align="center"><?php echo TEXT_INFORMATION; ?></td>
  </tr>
  <?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
  <tr>
    <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
    <tr>
      <td valign="top"><?php
  if (tep_db_num_rows($affiliate_banners_values)) {
echo '<table width="100%">';

    while ($affiliate_banners = tep_db_fetch_array($affiliate_banners_values)) {
      $affiliate_categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $affiliate_banners['affiliate_category_id'] . "' and language_id = '" . (int)$languages_id . "'");
      $affiliate_categories = tep_db_fetch_array($affiliate_categories_query);
      $prod_id = $affiliate_banners['affiliate_category_id'];
      $ban_id = $affiliate_banners['affiliate_banners_id'];
      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1: // Link to Categories
          if ($prod_id > 0) {
            $link_img = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&cPath=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_banners['affiliate_banners_image'] . '" border="0" alt="' . $affiliate_categories['categories_name'] . '"></a>';
            $link_txt = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&cPath=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank">' . $affiliate_categories['categories_name'] . '</a>';
      }
          break;
        case 2: // Link to Categories
          if ($prod_id > 0) {
            $link_img = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&cPath=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_categories['categories_name'] . '"></a>';
            $link_txt = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&cPath=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank">' . $affiliate_categories['categories_name'] . '</a>';
      }
          break;
      }

          if ($prod_id > 0) {
echo '     <tr>' . "\n";
echo '      <td valign="top">' . "\n";

            $info_box_contents = array();
            $info_box_contents[] = array('text' => TEXT_AFFILIATE_NAME . '&nbsp; ' . $affiliate_banners['affiliate_banners_title']);
            new contentBoxHeading($info_box_contents);
            $info_box_contents = array();
            $info_box_contents[] = array('text' =>  '<center><b>' . TEXT_IMAGE . '</b></center>' );
            $info_box_contents[] = array('text' =>  '<center>' . $link_img . '</center>' );
            $info_box_contents[] = array('text' =>  '<center>' . TEXT_AFFILIATE_INFO . '</center>');
            $info_box_contents[] = array('text' =>  '<center><textarea cols="120" rows="5" onFocus="this.select()">' . $link_img . '</textarea></center>' );
            $info_box_contents[] = array('text' =>  '<center><b>' . TEXT_VERSION . '</b> ' . $link_txt . '</center>');
            $info_box_contents[] = array('text' =>  '<center>' . TEXT_AFFILIATE_INFO . '</center>');
            $info_box_contents[] = array('text' =>  '<center><textarea cols="120" rows="5" onFocus="this.select()">' . $link_txt . '</textarea></center>' );
            
            new contentBox($info_box_contents, true, true, $column_location);
          
          if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){ 
              $info_box_contents = array();
              $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
              new contentBoxFooter($info_box_contents);
          }
echo  '</td>' . "\n";
echo '    </tr>' . "\n";
  }
}
echo '</table>';
      } else { //if no banners show message.
echo '<tr>' . "\n";
echo '  <td>' . TEXT_AFFILIATE_NO_BANNERS . '</td>' . "\n";
echo '</tr>' . "\n";
}
  ?>
    </td>
    </tr>
    
    <?php
// RCI code start
echo $cre_RCI->get('affiliatebannerscategory', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
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
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_CENTRAL,'','SSL') . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
// RCI code start
echo $cre_RCI->get('affiliatebannerscategory', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>