<?php
/*
  $Id: customer_testimonials.php,v 1.3 2011/12/08 Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
  Contributed by http://www.seen-online.co.uk
*/
?>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<?php
  if (sizeof($testimonial_array) == '0') {
?>
  <tr>
    <td class="main"><?php echo TEXT_NO_TESTIMONIALS; ?></td>
  </tr>
  <?php
  } else {
    for($i=0; $i<sizeof($testimonial_array); $i++) {
  ?>
  <tr width="100%">
    <td valign="top">
    <?php
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' =>  '<a href="' . tep_href_link(FILENAME_CUSTOMER_TESTIMONIALS, 'testimonial_id=' . $testimonial_array[$i]['id'], 'NONSSL') . '"><b>' . $testimonial_array[$i]['title'] . '</b></a>'); 
      new contentBoxHeading($info_box_contents);
 
      $info_box_contents = array();
      $info_box_contents[][] = array('align' => 'left',
                                     'params' => 'class="smallText" width="100%" valign="top"',
                                     'text' => $testimonial_array[$i]['testimonial'] . '<p align="right">' . '-- ' . $testimonial_array[$i]['author'] . (empty($testimonial_array[$i]['location']) ? '' : '  from ' . $testimonial_array[$i]['location'] ) . '</p>');
      
      new contentBox($info_box_contents, true, true);
      if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){  
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                     'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                     );
        new contentboxFooter($info_box_contents);
      }   
    ?>
    </td>
  </tr>
  <?php
    if (($i+1) != sizeof($testimonial_array)) {
  ?>
  <tr>
    <td class="main">&nbsp;</td>
  </tr>
  <?php
      }
    }
  }
      if ($_GET['testimonial_id'] != '') {
?>
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
</table>
<?php
      }
?>