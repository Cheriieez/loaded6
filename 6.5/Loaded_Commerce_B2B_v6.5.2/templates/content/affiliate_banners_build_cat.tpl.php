<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('affiliatebannersbuildcat', 'top');
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
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
          <tr>
            <td class="main" align="center"> <?php echo TEXT_INFORMATION; ?></td>
          </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
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
?>
          <tr>
            <td class="infoBoxHeading" align="center"><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER . ' ' . (isset($affiliate_banners['affiliate_banners_title']) ? $affiliate_banners['affiliate_banners_title'] : ''); ?></td>
          </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
          <tr>
            <td class="smallText" align="center"><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_INFO . tep_draw_form('individual_banner', tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD_CAT) ) . "\n" . tep_draw_input_field('individual_banner_id', '', 'size="5"') . "&nbsp;&nbsp;" . tep_template_image_submit('button_affiliate_build_a_link.gif', IMAGE_BUTTON_BUILD_A_LINK); ?></form></td>
          </tr>
     <tr>
       <td class="smallText" align="center"><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_VALIDCATS) . '\')"><b>' . TEXT_AFFILIATE_VALIDPRODUCTS . '</b></a>'; ?>&nbsp;&nbsp;<?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_VIEW;?><br><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_HELP;?></td>
     </tr>
     <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ((isset($_POST['individual_banner_id']) && tep_not_null($_POST['individual_banner_id'])) || (isset($_GET['individual_banner_id']) && tep_not_null($_GET['individual_banner_id']))) {

    if (tep_not_null($_POST['individual_banner_id'])) $individual_banner_id = $_POST['individual_banner_id'];
    if (isset($_GET['individual_banner_id'])) $individual_banner_id = $_GET['individual_banner_id'];
    $affiliate_pbanners_values = tep_db_query("select c.categories_image, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$individual_banner_id . "' and cd.categories_id = '" . (int)$individual_banner_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    if ($affiliate_pbanners = tep_db_fetch_array($affiliate_pbanners_values)) {
       if(!isset($_SESSION['affiliate_id'])) {
         $affiliate_id = 0;
       } else {
         $affiliate_id = (int)$_SESSION['affiliate_id'];
       }
      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1:
        $link_img = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&cPath=' . $individual_banner_id . '" target="_blank"><img src="' . (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_pbanners['categories_image'] . '" border="0" alt="' . $affiliate_pbanners['categories_name'] . '"></a>';
        $link_txt = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&cPath=' . $individual_banner_id . '" target="_blank">' . $affiliate_pbanners['categories_name'] . '</a>';
      break;
      case 2:
   // Link to Products
        $link_img = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&cPath=' . $individual_banner_id . '" target="_blank"><img src="' . (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_pbanner_id=' . $individual_banner_id . '" border="0" alt="' . $affiliate_pbanners['categories_name'] . '"></a>';
        $link_txt = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&cPath=' . $individual_banner_id . '" target="_blank">' . $affiliate_pbanners['categories_name'] . '</a>';
      break;
     }
}
echo '     <tr>' . "\n";
echo '      <td valign="top">' . "\n";
            $info_box_contents = array();
            $info_box_contents[] = array('text' => TEXT_AFFILIATE_NAME . '&nbsp; ' . $affiliate_pbanners['categories_name']);
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
  echo tep_draw_separator('pixel_trans.gif', '100%', '10'); 

// RCI code start
echo $cre_RCI->get('affiliatebannersbuildcat', 'menu');
// RCI code eof

// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
</form>
        </table></td>
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
echo $cre_RCI->get('affiliatebannersbuildcat', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>