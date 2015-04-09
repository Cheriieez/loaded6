<?php
/*
  $Id: loginbox.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGINBOX);
if ((!strstr($PHP_SELF, 'login.php')) && (!strstr($PHP_SELF, 'create_account.php')) && !isset($_SESSION['customer_id']))  {
  if (!isset($_SESSION['customer_id'])) {
?>
<!-- loginbox //-->
<div class="loginbox-infobox">
  <div class="box-header infoBoxHeading infoBoxHeadingCenter">
    <?php echo BOX_HEADING_LOGIN; ?>
  </div>
  <div class="row margin-top-10 margin-bottom-10">
    <div class="col-lg-12">
      <ul class="box-loginbox">
        <li> 
          <form name="login" method="post" action="<?php echo tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'); ?>">
            <div class="form-group">
              <label for="email_address"><?php echo BOX_LOGINBOX_EMAIL; ?></label>
              <input type="text" name="email_address" maxlength="96" size="20" class="form-control">
            </div>
            <div class="form-group">
              <label for="password"><?php echo BOX_LOGINBOX_PASSWORD; ?></label>
              <?php echo tep_draw_password_field('password', '', 'maxlength="40" size="20" autocomplete="off" class="form-control"'); ?>
            </div>
            <input type="submit" value="<?php echo IMAGE_BUTTON_LOGIN; ?>" class="btn btn-danger pull-right">
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- loginbox eof//-->
<?php
  } 
} else {
  if (isset($_SESSION['customer_id'])) {
    $pwa_query = tep_db_query("select purchased_without_account from " . TABLE_CUSTOMERS . " where customers_id = '" . $_SESSION['customer_id'] . "'");
    $pwa = tep_db_fetch_array($pwa_query);
    if ($pwa['purchased_without_account'] == '0') {
?>
<!-- loginbox //-->
<div class="loginbox-infobox">
  <div class="box-header infoBoxHeading infoBoxHeadingCenter">
    <?php echo BOX_HEADING_LOGIN_BOX_MY_ACCOUNT; ?>
  </div>
  <div class="row margin-top-10 margin-bottom-10">
    <div class="col-lg-12">
      <ul class="box-loginbox">
        <?php
          echo '<li class="neg-margin-bottom-10"><a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . LOGIN_BOX_MY_ACCOUNT . '</a></li>';
          echo '<li class="neg-margin-bottom-10"><a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_EDIT . '</a></li>';
          echo '<li class="neg-margin-bottom-10"><a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_HISTORY . '</a></li>';
          echo '<li class="neg-margin-bottom-10"><a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . LOGIN_BOX_ADDRESS_BOOK . '</a></li>';
          echo '<li class="neg-margin-bottom-10"><a href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'NONSSL') . '">' . LOGIN_BOX_PRODUCT_NOTIFICATIONS . '</a></li>';
          echo '<li class="neg-margin-bottom-10"><a href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '">' . LOGIN_BOX_LOGOFF . '</a></li>';
        ?>
      </ul>
    </div>
  </div>
</div>
<!-- loginbox eof//-->
<?php
    }
  }
}
?>