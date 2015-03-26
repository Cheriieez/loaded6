<?php
//check to see if there is actually anything to be done here
if ( ($product_info['products_image_sm_1'] != '') || ($product_info['products_image_xl_1'] != '') ||
     ($product_info['products_image_sm_2'] != '') || ($product_info['products_image_xl_2'] != '') ||
     ($product_info['products_image_sm_3'] != '') || ($product_info['products_image_xl_3'] != '') ||
     ($product_info['products_image_sm_4'] != '') || ($product_info['products_image_xl_4'] != '') ||
     ($product_info['products_image_sm_5'] != '') || ($product_info['products_image_xl_5'] != '') ||
     ($product_info['products_image_sm_6'] != '') || ($product_info['products_image_xl_6'] != '') ) {
?>
<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! //-->
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <tr>
      <td><table width="100%">
        <tr>
          <?php
              if (($product_info['products_image_sm_1'] != '') && ($product_info['products_image_xl_1'] == '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } elseif (($product_info['products_image_sm_1'] != '') && ($product_info['products_image_sm_1'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
          <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_1']) . '" class="highslide" onclick="return hs.expand(this,{headingText: \''.$product_info['products_name'].' - Extra Image #1\'})">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br>' . tep_template_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
          <div class="highslide-caption"><div style="text-align:center;margin-top:-2px;"><?php echo $the_product_info['products_head_desc_tag']; ?></div></div>
          <div id="closebutton" class="highslide-overlay closebutton" onClick="return hs.close(this)" title="Close"></div>
          </td>
          <?php
              } elseif (($products_info['products_image_sm_1'] == '') && ($product_info['products_image_xl_1'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_1'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } else {
          ?>
          <td>&nbsp;</td>
          <?php
              }

              if (($product_info['products_image_sm_2'] != '') && ($product_info['products_image_xl_2'] == '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } elseif (($product_info['products_image_sm_2'] != '') && ($product_info['products_image_sm_2'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
          <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_2']) . '" class="highslide" onclick="return hs.expand(this,{headingText: \''.$product_info['products_name'].' - Extra Image #2\'})">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br>' . tep_template_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
          <div class="highslide-caption"><div style="text-align:center;margin-top:-2px;"><?php echo $the_product_info['products_head_desc_tag']; ?></div></div>
          <div id="closebutton" class="highslide-overlay closebutton" onClick="return hs.close(this)" title="Close"></div>
          </td>
          <?php
              } elseif (($products_info['products_image_sm_2'] == '') && ($product_info['products_image_xl_2'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_2'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } else {
          ?>
          <td>&nbsp;</td>
          <?php
              }

              if (($product_info['products_image_sm_3'] != '') && ($product_info['products_image_xl_3'] == '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } elseif (($product_info['products_image_sm_3'] != '') && ($product_info['products_image_sm_3'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
          <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_3']) . '" class="highslide" onclick="return hs.expand(this,{headingText: \''.$product_info['products_name'].' - Extra Image #3\'})">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br>' . tep_template_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
          <div class="highslide-caption"><div style="text-align:center;margin-top:-2px;"><?php echo $the_product_info['products_head_desc_tag']; ?></div></div>
          <div id="closebutton" class="highslide-overlay closebutton" onClick="return hs.close(this)" title="Close"></div>
          </td>
          <?php
              } elseif (($products_info['products_image_sm_3'] == '') && ($product_info['products_image_xl_3'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_3'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } else {
          ?>
          <td>&nbsp;</td>
          <?php
              }
          ?>
        </tr>
        <tr>
          <?php
              if (($product_info['products_image_sm_4'] != '') && ($product_info['products_image_xl_4'] == '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } elseif (($product_info['products_image_sm_4'] != '') && ($product_info['products_image_sm_4'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
          <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_4']) . '" class="highslide" onclick="return hs.expand(this,{headingText: \''.$product_info['products_name'].' - Extra Image #4\'})">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br>' . tep_template_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
          <div class="highslide-caption"><div style="text-align:center;margin-top:-2px;"><?php echo $the_product_info['products_head_desc_tag']; ?></div></div>
          <div id="closebutton" class="highslide-overlay closebutton" onClick="return hs.close(this)" title="Close"></div>
          </td>
          <?php
              } elseif (($products_info['products_image_sm_4'] == '') && ($product_info['products_image_xl_4'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_4'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } else {
          ?>
          <td>&nbsp;</td>
          <?php
              }

              if (($product_info['products_image_sm_5'] != '') && ($product_info['products_image_xl_5'] == '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } elseif (($product_info['products_image_sm_5'] != '') && ($product_info['products_image_sm_5'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
          <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_5']) . '" class="highslide" onclick="return hs.expand(this,{headingText: \''.$product_info['products_name'].' - Extra Image #5\'})">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br>' . tep_template_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
          <div class="highslide-caption"><div style="text-align:center;margin-top:-2px;"><?php echo $the_product_info['products_head_desc_tag']; ?></div></div>
          <div id="closebutton" class="highslide-overlay closebutton" onClick="return hs.close(this)" title="Close"></div>
          </td>
          <?php
              } elseif (($products_info['products_image_sm_5'] == '') && ($product_info['products_image_xl_5'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_5'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } else {
          ?>
          <td>&nbsp;</td>
          <?php
              }

              if (($product_info['products_image_sm_6'] != '') && ($product_info['products_image_xl_6'] == '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } elseif (($product_info['products_image_sm_6'] != '') && ($product_info['products_image_sm_6'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
          <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_xl_6']) . '" class="highslide" onclick="return hs.expand(this,{headingText: \''.$product_info['products_name'].' - Extra Image #6\'})">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br>' . tep_template_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>'; ?>
          <div class="highslide-caption"><div style="text-align:center;margin-top:-2px;"><?php echo $the_product_info['products_head_desc_tag']; ?></div></div>
          <div id="closebutton" class="highslide-overlay closebutton" onClick="return hs.close(this)" title="Close"></div>
          </td>
          <?php
              } elseif (($products_info['products_image_sm_6'] == '') && ($product_info['products_image_xl_6'] != '')) {
          ?>
          <td align="center" class="smallText" width="33%">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_6'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
          <?php
              } else {
          ?>
          <td>&nbsp;</td>
          <?php
              }
          ?>
        </tr>
      </table></td>
    </tr>
<!-- // EOF MaxiDVD: Modified For Ultimate Images Pack! //-->
<?php
} // end of initial IF
?>
