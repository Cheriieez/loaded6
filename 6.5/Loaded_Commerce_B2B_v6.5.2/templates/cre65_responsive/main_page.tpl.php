<?php
/*
  $Id: main_page.tpl.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (file_exists(TEMPLATE_FS_CUSTOM_INCLUDES . 'languages/' . $language . '.php')) {
  require(TEMPLATE_FS_CUSTOM_INCLUDES . 'languages/' . $language . '.php');
}
if (file_exists(TEMPLATE_FS_CUSTOM_INCLUDES . 'functions/custom.php')) {
  require(TEMPLATE_FS_CUSTOM_INCLUDES . 'functions/custom.php');
}
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . 'index.php'; ?>">
  <?php
    if (file_exists(DIR_WS_INCLUDES . FILENAME_HEADER_TAGS)) {
      require(DIR_WS_INCLUDES . FILENAME_HEADER_TAGS);
    } else {
  ?>
  <title><?php echo TITLE; ?></title>
  <?php
    }
  ?>
  <!-- Google Font API -->
  <link rel="stylesheet" href="http<?php echo (($request_type == 'SSL') ? 's' : null); ?>://fonts.googleapis.com/css?family=Economica:400,400italic,700,700italic">
  <link rel="stylesheet" href="http<?php echo (($request_type == 'SSL') ? 's' : null); ?>://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <link rel="stylesheet" href="http<?php echo (($request_type == 'SSL') ? 's' : null); ?>://fonts.googleapis.com/css?family=Roboto:400,500,700">
  <!-- Font Awesome API -->
  <link rel="stylesheet" href="http<?php echo (($request_type == 'SSL') ? 's' : null); ?>://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <!-- Bootsrap core CSS -->
  <link rel="stylesheet" href="templates/cre65_responsive/css/bootstrap.css">
  <!-- Custom CSS of this site -->
  <link rel="stylesheet" href="templates/cre65_responsive/css/style.css">
  <?php
    // RCI code start
    echo $cre_RCI->get('stylesheet', 'cre65responsive');
    echo $cre_RCI->get('global', 'head'); 
    // RCI code eof
  ?>
  <script src="http<?php echo (($request_type == 'SSL') ? 's' : null); ?>://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="templates/cre65_responsive/js/bootstrap.min.js"></script>
  <?php
    if (isset($javascript) && file_exists(DIR_WS_JAVASCRIPT . basename($javascript))) { 
      require(DIR_WS_JAVASCRIPT . basename($javascript)); 
    }
    if (defined('PRODUCT_INFO_TAB_ENABLE') && PRODUCT_INFO_TAB_ENABLE == 'True' && basename($PHP_SELF) == FILENAME_PRODUCT_INFO) {
  ?>
  <script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>tabs/webfxlayout.js"></script>
  <link type="text/css" rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME;?>/tabs/tabpane.css">
  <script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>tabs/tabpane.js"></script>
  <?php
    }
  ?>
</head>
<body>
  <?php 
    if (file_exists(TEMPLATE_FS_CUSTOM_INCLUDES . FILENAME_WARNINGS)) {
      require(TEMPLATE_FS_CUSTOM_INCLUDES . FILENAME_WARNINGS);    
    } else {
      require(DIR_WS_INCLUDES . FILENAME_WARNINGS);
    }
    // RCI top
    echo $cre_RCI->get('mainpage', 'top'); 
  ?>
  <?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . FILENAME_HEADER); ?>
  <section class="container middle">
    <div class="row">
      <div class="col-lg-12 br_breadcrumbs">
        <?php echo $breadcrumb->trail(' &raquo; '); ?>
      </div>
      <div class="clearfix"></div>
      <div class="col-lg-12">
        <div class="row">
          <?php
            // set the format; 1, 2, or 3 columns
            if ((DISPLAY_COLUMN_LEFT == 'yes' && left_col_check() > 0) && (DISPLAY_COLUMN_RIGHT == 'yes' && right_col_check() > 0)) { // 3 cols
              $content_class = 'col-xs-12 col-sm-12 col-md-6 col-lg-6';
              $show_left = 'yes';
              $show_right = 'yes';
              $_SESSION['content_span'] = '6';      
            } else if ((DISPLAY_COLUMN_LEFT == 'yes' && left_col_check() > 0) && (DISPLAY_COLUMN_RIGHT == 'no')) { // 2 cols left
              $content_class = 'col-xs-12 col-sm-12 col-md-9 col-lg-9';
              $show_left = 'yes';
              $show_right = 'no'; 
              $_SESSION['content_span'] = '9';      
            } else if ((DISPLAY_COLUMN_LEFT == 'no' || left_col_check() < 1) && (DISPLAY_COLUMN_RIGHT == 'yes' && right_col_check() > 0)) { // 2 cols right
              $content_class = 'col-xs-12 col-sm-12 col-md-9 col-lg-9';
              $show_left = 'no';
              $show_right = 'yes'; 
              $_SESSION['content_span'] = '9';
            } else {
              $content_class = 'col-xs-12 col-sm-12 col-md-12 col-lg-12'; // 1 col
              $show_left = 'no';
              $show_right = 'no';
              $_SESSION['content_span'] = '12';
            } 
            if ($show_left == 'yes') {
              if (DOWN_FOR_MAINTENANCE == 'false' || DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF == 'false') {
          ?>
          <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 prod_list">
            <?php require(DIR_WS_INCLUDES . FILENAME_COLUMN_LEFT); ?>
          </div>
          <?php
              }
            }
            echo'<div class="' . $content_class . ' prod_tabs">';
            // main content
            if (isset($content_template) && file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/' . basename($content_template))) {
              require(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/' . basename($content_template));
            } else if (file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/' . $content . '.tpl.php')) {
              require(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/'. $content . '.tpl.php');
            } else if (isset($content_template) && file_exists(DIR_WS_CONTENT . basename($content_template)) ){
              require(DIR_WS_CONTENT . basename($content_template));
            } else {
              require(DIR_WS_CONTENT . $content . '.tpl.php');
            }
            echo '</div>'; 
            if ($show_right == 'yes') {
              if (DOWN_FOR_MAINTENANCE == 'false' || DOWN_FOR_MAINTENANCE_COLUMN_RIGHT_OFF == 'false') {
          ?>
          <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 prod_list">
            <?php require(DIR_WS_INCLUDES . FILENAME_COLUMN_RIGHT); ?>
          </div>
          <?php
              }
            }
          ?>
        </div>
      </div>
    </div>
  </section>
  <?php 
    require(DIR_WS_TEMPLATES . TEMPLATE_NAME .'/'.FILENAME_FOOTER);
    // RCI global footer
    echo $cre_RCI->get('global', 'footer');
  ?>
</body>
</html>