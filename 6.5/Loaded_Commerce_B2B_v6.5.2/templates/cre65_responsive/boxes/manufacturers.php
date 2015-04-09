<?php
/*
  $Id: manufacturers.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$manufacture = new box_manufacturers();
$number_of_rows =  count($manufacture->rows);
if ($number_of_rows > 0) {
?>
<!-- manufacturers //-->
<div class="manufacturers-infobox">
  <div class="box-header infoBoxHeading infoBoxHeadingCenter">
    <?php echo BOX_HEADING_MANUFACTURERS; ?>
  </div>
  <div class="row margin-top-10 margin-bottom-10">
    <div class="col-lg-12">
      <ul class="box-manufacturers-selection">
        <li>
          <?php
            $manufacturers_array = array();
            $manufacturers_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
            foreach ($manufacture->rows as $manufacturers) {
              $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
              $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                             'text' => $manufacturers_name);
            }
            echo tep_draw_form('manufacturers', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get', 'name="manufacturers" class="form-inline no-margin-bottom" role="form"');
            echo tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($_GET['manufacturers_id']) ? (int)$_GET['manufacturers_id'] : ''), 'onChange="this.form.submit();" size="1" class="box-manufacturers-select form-control form-input-width" name="manufacturers"') . tep_hide_session_id();
            echo '</form>';
          ?>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- manufacturers eof//-->
<?php
}
?>