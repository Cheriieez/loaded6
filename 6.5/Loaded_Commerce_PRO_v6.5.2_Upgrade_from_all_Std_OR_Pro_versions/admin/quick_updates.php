<?php
/*
  $Id: quick_updates.php 2006/09/14 13:55:34 maestro Exp $

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  // define row by page value
  if ($_GET['row_by_page'] != '') {
    define('MAX_DISPLAY_ROW_BY_PAGE', $_GET['row_by_page']);
    $row_by_page = MAX_DISPLAY_ROW_BY_PAGE;  
  } else {
    define('MAX_DISPLAY_ROW_BY_PAGE', 10);
    $row_by_page = MAX_DISPLAY_ROW_BY_PAGE;  
  }
    
  if (isset($_GET['manufacturer'])) {
   $manufacturer = (int)$_GET['manufacturer'];
  }
  if (isset($_GET['sort_by'])) {
    $sort_by = $_GET['sort_by'];
  }
  if (isset($_GET['page'])) {
    $page = $_GET['page'];
  }
  
  $fields = mysql_list_fields(DB_DATABASE, 'customers');
  $columns = mysql_num_fields($fields);
  for ($i = 0; $i < $columns; $i++) {
    $field_array[] = mysql_field_name($fields, $i);
  }
  
  if (in_array('customers_group_id', $field_array)) {
    if (isset($_GET['customers_group_id'])) {
      $customers_group_id = (int)$_GET['customers_group_id'];
    } else {
      $customers_group_id = 0;
    }
  }
  
  // Tax Row
  $tax_class_array = array(array('id' => '0', 'text' => NO_TAX_TEXT));
  $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
  while ($tax_class = tep_db_fetch_array($tax_class_query)) {
    $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                               'text' => $tax_class['tax_class_title']);
  }

  //Info Row pour le champ fabriquant
  $manufacturers_array = array(array('id' => '0', 'text' => NO_MANUFACTURER));
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
    $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                   'text' => $manufacturers['manufacturers_name']);
  }

  // Display the list of the manufacturers
  function manufacturers_list() {
    global $manufacturer;

    $manufacturers_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name from " . TABLE_MANUFACTURERS . " m order by m.manufacturers_name ASC");
    $return_string = '<select name="manufacturer" onChange="this.form.submit();">';
    $return_string .= '<option value="' . 0 . '">' . TEXT_ALL_MANUFACTURERS . '</option>';
    while($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $return_string .= '<option value="' . $manufacturers['manufacturers_id'] . '"';
      if($manufacturer && $manufacturers['manufacturers_id'] == $manufacturer) $return_string .= ' SELECTED';
      $return_string .= '>' . $manufacturers['manufacturers_name'] . '</option>';
    }
    $return_string .= '</select>';
    return $return_string;
  }
  
  if (in_array('customers_group_id', $field_array)) {
    // display the customer groups dropdown
    function customers_groups_list() {
      global $customers_group_id;

      $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id");
      $return_string = '<select name="customers_group_id" onChange="this.form.submit();">';
      while ($customers_groups = tep_db_fetch_array($customers_group_query)) {
        $return_string .= '<option value="' . $customers_groups['customers_group_id'] . '"';
        if ($customers_group_id && $customers_groups['customers_group_id'] == $customers_group_id) $return_string .= ' SELECTED';
        $return_string .= '>' . $customers_groups['customers_group_name'] . '</option>';
      }
      $return_string .= '</select>';
      return $return_string;
    }
  }

  // Uptade database
  switch ($_GET['action']) {
    case 'update' :
      $count_update=0;
      $item_updated = array();
      if ($_POST['product_new_model']) {
        foreach($_POST['product_new_model'] as $id => $new_model) {
          if (trim($_POST['product_new_model'][$id]) != trim($_POST['product_old_model'][$id])) {
            $count_update++;
            $item_updated[$id] = 'updated';
            mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_model='" . $new_model . "', products_last_modified=now() WHERE products_id=$id");
          }
        }
      }
      if ($_POST['product_new_name']) {
        foreach($_POST['product_new_name'] as $id => $new_name) {
          if (trim($_POST['product_new_name'][$id]) != trim($_POST['product_old_name'][$id])) {
            $count_update++;
            $item_updated[$id] = 'updated';
            mysql_query("UPDATE " . TABLE_PRODUCTS_DESCRIPTION . " SET products_name='" . $new_name . "' WHERE products_id=$id and language_id=" . $languages_id);
            mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_last_modified=now() WHERE products_id=$id");
          }
        }
      }
      $pfields = mysql_list_fields(DB_DATABASE, 'products');
      $pcolumns = mysql_num_fields($pfields);
      for ($i = 0; $i < $pcolumns; $i++) {
        $pfield_array[] = mysql_field_name($pfields, $i);
      }
      if (in_array('sort_order', $pfield_array)) {
        if ($_POST['product_new_sort_order']) {
          foreach($_POST['product_new_sort_order'] as $id => $new_sort_order) {
            if ($_POST['product_new_sort_order'][$id] != $_POST['product_old_sort_order'][$id]) {
              $count_update++;
              $item_updated[$id] = 'updated';
              mysql_query("UPDATE " . TABLE_PRODUCTS . " SET sort_order=$new_sort_order, products_last_modified=now() WHERE products_id=$id");
            }
          }
        }
      }
      if ($_POST['product_new_price']) {
        foreach ($_POST['product_new_price'] as $id => $new_price) {
          if ($_POST['product_new_price'][$id] != $_POST['product_old_price'][$id] && $_POST['update_price'][$id] == 'yes') {
            $count_update++;
            $item_updated[$id] = 'updated';
            if (in_array('customers_group_id', $field_array)) { 
              if ($_POST['customers_group_id'] == '0') {
                mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_price = '" . $new_price . "', products_last_modified = now() WHERE products_id = '" . (int)$id . "'");
              } else {
                if ($_POST['cg_price_in_db'][$id] == 'yes') {
                  if (trim($_POST['product_new_price'][$id]) == '') {
                    mysql_query("DELETE FROM " . TABLE_PRODUCTS_GROUPS . " WHERE products_id='" . (int)$id . "' AND customers_group_id = '" . (int)$_POST['customers_group_id'] ."'");    
                  } else {
                    mysql_query("UPDATE " . TABLE_PRODUCTS_GROUPS . " SET customers_group_price='" . $new_price . "' WHERE products_id='" . (int)$id . "' AND customers_group_id = '" . (int)$_POST['customers_group_id'] ."'");
                  }
                } elseif ($_POST['cg_price_in_db'][$id] == 'no') {
                  mysql_query("INSERT INTO " . TABLE_PRODUCTS_GROUPS . " SET products_id='" . (int)$id . "', customers_group_price='" . $new_price . "', customers_group_id = '" . (int)$_POST['customers_group_id'] ."'");
                }
              }
            } else {
              mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_price = '" . $new_price . "', products_last_modified = now() WHERE products_id = '" . (int)$id . "'"); 
            }            
          }
        }
      }
      if ($_POST['product_new_weight']) {
        foreach($_POST['product_new_weight'] as $id => $new_weight) {
          if ($_POST['product_new_weight'][$id] != $_POST['product_old_weight'][$id]) {
            $count_update++;
            $item_updated[$id] = 'updated';
            mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_weight=$new_weight, products_last_modified=now() WHERE products_id=$id");
          }
        }
      }
      if ($_POST['product_new_cost']) {
        foreach($_POST['product_new_cost'] as $id => $new_cost) {
          if ($_POST['product_new_cost'][$id] != $_POST['product_old_cost'][$id]) {
            $count_update++;
            $item_updated[$id] = 'updated';
            mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_cost=$new_cost, products_last_modified=now() WHERE products_id=$id");
          }
        }
      }
      if ($_POST['product_new_msrp']) {
        foreach($_POST['product_new_msrp'] as $id => $new_msrp) {
          if ($_POST['product_new_msrp'][$id] != $_POST['product_old_msrp'][$id]) {
            $count_update++;
            $item_updated[$id] = 'updated';
            mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_msrp=$new_msrp, products_last_modified=now() WHERE products_id=$id");
          }
        }
      } 
      if ($_POST['product_new_quantity']) {
        foreach($_POST['product_new_quantity'] as $id => $new_quantity) {
          if ($_POST['product_new_quantity'][$id] != $_POST['product_old_quantity'][$id]) {
            $count_update++;
            $item_updated[$id] = 'updated';
            mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_quantity=$new_quantity, products_last_modified=now() WHERE products_id=$id");
          }
        }
      }
      if ($_POST['product_new_manufacturer']) {
        foreach($_POST['product_new_manufacturer'] as $id => $new_manufacturer) {
          if ($_POST['product_new_manufacturer'][$id] != $_POST['product_old_manufacturer'][$id]) {
            $count_update++;
            $item_updated[$id] = 'updated';
            mysql_query("UPDATE " . TABLE_PRODUCTS . " SET manufacturers_id=$new_manufacturer, products_last_modified=now() WHERE products_id=$id");
          }
        }
      }
      if ($_POST['product_new_status']) {
        foreach($_POST['product_new_status'] as $id => $new_status) {
          if ($_POST['product_new_status'][$id] != $_POST['product_old_status'][$id]) {
            $count_update++;
            $item_updated[$id] = 'updated';
            tep_set_product_status($id, $new_status);
            mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_last_modified=now() WHERE products_id=$id");
          }
        }
      }
      if ($_POST['product_new_tax']) {
        foreach($_POST['product_new_tax'] as $id => $new_tax_id) {
          if ($_POST['product_new_tax'][$id] != $_POST['product_old_tax'][$id]) {
            $count_update++;
            $item_updated[$id] = 'updated';
            mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_tax_class_id=$new_tax_id, products_last_modified=now() WHERE products_id=$id");
          }
        }
      }

      if ($_POST['product_new_tax']) {
        foreach($_POST['product_new_tax'] as $id => $new_tax_id) {
          if ($_POST['product_new_tax'][$id] != $_POST['product_old_tax'][$id]) {
            $count_update++;
            $item_updated[$id] = 'updated';
            mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_tax_class_id=$new_tax_id, products_last_modified=now() WHERE products_id=$id");
          }
        }
      }

      if (in_array('customers_group_id', $field_array)) {
        // Quantity Price Break or Customer Group Price updates -  BOF    
        if ($_POST['sppcprice']) {
          foreach($_POST['sppcprice'] as $pid => $qty_break_price_array) { 
           $i = 1;
           foreach($qty_break_price_array as $id => $qty_break_price) {
             if ($_POST['sppcprice'][$pid][$id] != $_POST['sppcprice_old'][$pid][$id] ) {
               $count_update++;  
               $item_updated[$id] = 'updated';
               if($i==1) $old_price = $qty_break_price;

               if ($qty_break_price == 0 || $qty_break_price > $old_price) {
                 $qty_break_price = $old_price;
               } else {
                 $old_price = $qty_break_price;
               }  
               if($_POST['customers_group_id'] > 0) {
                 if($id == 0) {                 
                   mysql_query("UPDATE " . TABLE_PRODUCTS_GROUPS . " SET customers_group_price = $qty_break_price WHERE products_id = $pid  and customers_group_id = '" . $_POST['customers_group_id'] . "'");
                 } else {                
                   mysql_query("UPDATE " . TABLE_PRODUCTS_GROUPS . " SET customers_group_price$id = $qty_break_price WHERE products_id = $pid  and customers_group_id = '" . $_POST['customers_group_id'] . "'");
                 }
               } else {
                 if($id == 0) {                 
                   mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_price = $qty_break_price, products_last_modified = now() WHERE products_id = $pid");
                 } else {                 
                   mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_price$id = $qty_break_price, products_last_modified = now() WHERE products_id = $pid");
                 }
               }
               $i++;            
             }
           }         
           for ( ; $i <= 11; $i++) {
             if ($_POST['sppcprice'][$pid][$id] != $_POST['sppcprice_old'][$pid][$id]) {
               $count_update++;  
               $item_updated[$id] = 'updated';
               if($_POST['customers_group_id'] > 0) {
                 mysql_query("UPDATE " . TABLE_PRODUCTS_GROUPS . " SET customers_group_price$i = $qty_break_price WHERE products_id = $pid and customers_group_id = '" . $_POST['customers_group_id'] . "' ");
               } else {
                 mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_price$id = $qty_break_price, products_last_modified = now() WHERE products_id = $pid '");
               }
             }
           }
          }
        }
        // Quantity Price Break or Customer Group Price updates -  EOF
      }
      $count_item = array_count_values($item_updated);
      if ($count_item['updated'] > 0) $messageStack->add($count_item['updated'] . ' ' . TEXT_PRODUCTS_UPDATED . " $count_update " . TEXT_QTY_UPDATED, 'success');
    break;

    /*case 'calcul' :
      if ($_POST['spec_price']) $preview_global_price = 'true';
    break;*/
  }

  // explode string parameters from preview product
  if ($info_back && $info_back!="-") {
    $infoback = explode('-', $info_back);
    $sort_by = $infoback[0];
    $page =  $infoback[1];
    $current_category_id = $infoback[2];
    $row_by_page = $infoback[3];
    $manufacturer = $infoback[4];
    if (in_array('customers_group_id', $field_array)) {
      $customers_group_id = $infoback[5];
    }
  }

  // define the step for rollover lines per page
  $row_bypage_array = array(array());
  for ($i = 10; $i <= 200 ; $i = $i+10) {
    $row_bypage_array[] = array('id' => $i,
                                'text' => $i);
  }

  // Let's start displaying page with forms
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
<script language="javascript"><!--
  var browser_family;
  var up = 1;

  if (document.all && !document.getElementById)
    browser_family = "dom2";
  else if (document.layers)
    browser_family = "ns4";
  else if (document.getElementById)
    browser_family = "dom2";
  else
    browser_family = "other";

--></script>
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body>  
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
    <td valign="top" class="page-container">
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
               <td class="pageHeading" colspan="3" valign="top"><?php echo HEADING_TITLE; ?></td>
               <td class="pageHeading" align="right">
               <?php
                 if ($current_category_id != 0) {
                   $image_query = tep_db_query("select c.categories_image from " . TABLE_CATEGORIES . " c where c.categories_id=" . $current_category_id);
                   $image = tep_db_fetch_array($image_query);
                   echo tep_image(DIR_WS_CATALOG . DIR_WS_IMAGES . $image['categories_image'], '', 40);
                 } else {
                   if ($manufacturer) {
                     $image_query = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id=" . $manufacturer);
                     $image = tep_db_fetch_array($image_query);
                     echo tep_image(DIR_WS_CATALOG . DIR_WS_IMAGES . $image['manufacturers_image'], '', 40);
                   }
                 }
               ?>
               </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="center">
            <fieldset style="background:#F3F9FB"><legend ><?php echo TEXT_PRODUCTS_FILTER ;?> </legend>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border:0px solid #cccccc;" bgcolor="#F3F9FB" height= "40" >
              <tr align="left">
                <td valign="middle">
                  <table width="100%" cellspacing="0" cellpadding="0" border="0">                    
                    <tr align="center">
                      <td class="smalltext">
                      <?php
                        echo tep_draw_form('row_by_page', FILENAME_QUICK_UPDATES, '', 'get'); 
                        echo tep_draw_hidden_field('manufacturer', $manufacturer); 
                        echo tep_draw_hidden_field('cPath', $current_category_id);
                        if (in_array('customers_group_id', $field_array)) {
                          echo tep_draw_hidden_field('customers_group_id', $customers_group_id);
                        }
                      ?>
                      </td>
                      <td class="smallText">
                      <?php 
                        echo TEXT_MAXI_ROW_BY_PAGE . '&nbsp;&nbsp;' . tep_draw_pull_down_menu('row_by_page', $row_bypage_array, $row_by_page, 'onChange="this.form.submit();"');
                        echo tep_hide_session_id();
                      ?></form>
                      </td>
                      <?php 
                        echo tep_draw_form('categorie', FILENAME_QUICK_UPDATES, '', 'get'); 
                        echo tep_draw_hidden_field('row_by_page', $row_by_page); 
                        echo tep_draw_hidden_field('manufacturer', $manufacturer); 
                      ?>
                      <td class="smallText" align="center" valign="top">
                      <?php 
                        echo DISPLAY_CATEGORIES . '&nbsp;&nbsp;' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"'); 
                        echo tep_hide_session_id();
                      ?></form> 
                      </td>
                      <?php 
                        echo tep_draw_form('manufacturers', FILENAME_QUICK_UPDATES, '', 'get'); 
                        echo tep_draw_hidden_field('row_by_page', $row_by_page); 
                        echo tep_draw_hidden_field('cPath', $current_category_id);
                      ?>
                      <td class="smallText" align="center" valign="top">
                      <?php
                        echo DISPLAY_MANUFACTURERS . '&nbsp;&nbsp' . manufacturers_list();
                        echo tep_hide_session_id(); 
                      ?></form>
                      </td>
                      <?php if (in_array('customers_group_id', $field_array)) { ?>
                      <td class="smallText" align="center" valign="top">
                      <?php 
                        echo tep_draw_form('customers_groups', FILENAME_QUICK_UPDATES, '', 'get'); 
                        echo tep_draw_hidden_field( 'row_by_page', $row_by_page); 
                        echo tep_draw_hidden_field( 'cPath', $current_category_id); 
                        echo tep_draw_hidden_field( 'manufacturer', $manufacturer);
                        echo DISPLAY_CUSTOMERS_GROUPS . '&nbsp;&nbsp' . customers_groups_list(); 
                        echo tep_hide_session_id(); 
                      ?></form>
                      </td>
                      <?php } ?>
                    </tr>
                  </table>
                </td>
              </tr>
            </table></fieldset>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" height="33">
              <tr>
                <?php if (in_array('customers_group_id', $field_array)) { ?>
                <form name="update" method="POST" action="<?php echo "$PHP_SELF?action=update&page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer&customers_group_id=$customers_group_id"; ?>">
                <?php } else { ?>
                <form name="update" method="POST" action="<?php echo "$PHP_SELF?action=update&page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer"; ?>">
                <?php } ?>
                <td class="smalltext" align="middle"><font color="red"><?php echo WARNING_MESSAGE; ?></font></td>
                <?php 
                  echo "<td class=\"pageHeading\" align=\"right\">" . '
                          <script language="javascript">
                          <!--
                            switch (browser_family) {
                              case "dom2":
                              case "ie4":
                                  document.write(\'<div id="descDiv">\');
                                break;
                              default:
                                  document.write(\'<ilayer id="descDiv"><layer id="descDiv_sub">\');
                                break;
                            }
                          -->
                          </script></td>' . "\n";
                ?>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="100%" valign="top">
                  <table border="0" bordercolor="#FF0000" width="100%" cellspacing="0" cellpadding="2">
                    <tr width="100%" class="dataTableHeadingRow">
                    <!-- Model -->
                      <td class="dataTableHeadingContent">
                      <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_model ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_MODEL . ' ' . TEXT_ASCENDINGLY) . "</a>
                      <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_model DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_MODEL . ' ' . TEXT_DESCENDINGLY)."</a>
                      </div><div style=\"float:left;\">&nbsp;"  .TABLE_HEADING_MODEL . "</div></td>"; ?>
                    <!-- Name -->
                      <td class="dataTableHeadingContent">  
                      <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=pd.products_name ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS . ' ' . TEXT_ASCENDINGLY) . "</a>
                      <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=pd.products_name DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS . ' ' . TEXT_DESCENDINGLY)."</a>
                      </div><div style=\"float:left;\">&nbsp;"  .TABLE_HEADING_PRODUCTS . "</div></td>"; ?>
                    <?php 
                      if (in_array('sort_order', $pfield_array)) {  
                        if (DISPLAY_SORT == 'true') { ?>
                    <!-- Sort Order -->
                      <td class="dataTableHeadingContent">
                      <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.sort_order ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_SORTIERUNG . TEXT_ASCENDINGLY) . "</a>
                      <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.sort_order DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_SORTIERUNG . ' ' . TEXT_DESCENDINGLY)."</a>
                      </div><div style=\"float:left;\">&nbsp;"  .TABLE_HEADING_SORTIERUNG . "</div></td>"; ?>
                    <?php } } if (DISPLAY_STATUT == 'true') { ?>
                    <!-- Status -->
                      <td class="dataTableHeadingContent">
                      <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_status ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . 'Off ' . TEXT_ASCENDINGLY) . "</a>
                      <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_status DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . 'On ' . TEXT_ASCENDINGLY) . "</a>
                      </div><div style=\"float:left;\">&nbsp;Status</td>" ; ?>
                    <?php } if (DISPLAY_WEIGHT == 'true') { ?>
                    <!-- Weight -->
                      <td class="dataTableHeadingContent">
                      <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_weight ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_WEIGHT . ' ' . TEXT_ASCENDINGLY) . "</a>
                      <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_weight DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_WEIGHT . ' ' . TEXT_DESCENDINGLY)."</a>
                      </div><div style=\"float:left;\">&nbsp;" . TABLE_HEADING_WEIGHT . "</div></td>"; ?>
                    <?php } if (DISPLAY_QUANTITY == 'true') { ?>
                    <!-- Quantity -->
                      <td class="dataTableHeadingContent">
                      <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_quantity ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_QUANTITY . ' ' . TEXT_ASCENDINGLY) . "</a>
                      <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_quantity DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                      </div><div style=\"float:left;\">&nbsp;" . TABLE_HEADING_QUANTITY . "</div></td>"; ?>
                    <?php } if (MODIFY_MANUFACTURER == 'true') { ?>
                    <!-- Manufacturer -->
                       <td class="dataTableHeadingContent">
                       <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=manufacturers_id ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_MANUFACTURERS . ' ' . TEXT_ASCENDINGLY) . "</a>
                       <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=manufacturers_id DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_MANUFACTURERS . ' ' . TEXT_DESCENDINGLY)."</a>
                       </div><div style=\"float:left;\">&nbsp;" . TABLE_HEADING_MANUFACTURERS . "</div></td>"; ?>
                    <?php } if (DISPLAY_COST == 'true') { ?>
                    <!-- Cost (Only With MaRrgin Reports) -->
                       <td class="dataTableHeadingContent">
                       <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_cost ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >" . tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE_EK . ' ' . TEXT_ASCENDINGLY) . "</a>
                       <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_cost DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >" . tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE_EK . ' ' . TEXT_DESCENDINGLY)."</a>
                       </div><div style=\"float:left;\">&nbsp;" . TABLE_HEADING_COST . "</div></td>"; ?>
                    <?php } if (DISPLAY_RETAIL_PRICE == 'true') { ?>
                    <!-- MSRP -->
                       <td class="dataTableHeadingContent">
                       <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_msrp ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >" . tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE_EK . ' ' . TEXT_ASCENDINGLY) . "</a>
                       <a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_msrp DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >" . tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE_EK . ' ' . TEXT_DESCENDINGLY)."</a>
                       </div><div style=\"float:left;\">&nbsp;" . TABLE_HEADING_RETAIL_PRICE . "</div></td>"; ?>
                    <?php } if (MODIFY_TAX == 'true') { ?>
                    <!-- Tax -->
                       <td class="dataTableHeadingContent">
                       <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_tax_class_id ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES  . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_TAX . ' ' . TEXT_ASCENDINGLY) . "</a>
                       <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_tax_class_id DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) . "\" >" . tep_image(DIR_WS_IMAGES  . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_TAX . ' ' . TEXT_DESCENDINGLY)."</a>
                       </div><div style=\"float:left;\">&nbsp;" . TABLE_HEADING_TAX . "</div></td>"; ?>
                    <?php } ?>
                    <!-- Price -->
                       <td class="dataTableHeadingContent">
                       <?php echo "<div style=\"padding-top:2px; float:left;\"><a href=\"" . tep_href_link(FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_price ASC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >" . tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE . ' ' . TEXT_ASCENDINGLY) . "</a>
                       <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath=' . $current_category_id . '&sort_by=p.products_price DESC&page=' . $page . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id) ."\" >" . tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE . ' ' . TEXT_DESCENDINGLY)."</a>
                       </div><div style=\"float:left;\">&nbsp;" . TABLE_HEADING_PRICE . "</div></td>"; ?>
                    <?php if (DISPLAY_PREVIEW == 'true') { ?>
                    <!-- Preview -->
                       <td class="dataTableHeadingContent" align="center">Preview</td>
                    <?php } if (DISPLAY_EDIT == 'true') { ?>
                    <!-- Full Edit -->
                       <td class="dataTableHeadingContent" align="center"><?php echo TEXT_EDIT; ?></td>
                    <?php } ?>
                    </tr>
                    <?php
                      // control string sort page
                      if ($sort_by && !preg_match('/order by/', $sort_by)) $sort_by = 'order by ' . $sort_by ;
                      
                      // define the string parameters for good back preview product
                      if (in_array('customers_group_id', $field_array)) {
                        $origin = FILENAME_QUICK_UPDATES . "?info_back=$sort_by-$page-$current_category_id-$row_by_page-$manufacturer-$customers_group_id";
                      } else {
                        $origin = FILENAME_QUICK_UPDATES . "?info_back=$sort_by-$page-$current_category_id-$row_by_page-$manufacturer";
                      }
                    
                      // controle lenght (lines per page)
                      $split_page = $page;
                      if ($split_page > 1) $rows = $split_page * MAX_DISPLAY_ROW_BY_PAGE - MAX_DISPLAY_ROW_BY_PAGE;

                      // select categories
                      $products_msrp_field = (DISPLAY_RETAIL_PRICE == 'true') ? "p.products_msrp," : "";
                      $products_cost_field = (DISPLAY_COST == 'true') ? "p.products_cost," : "";
                      if (in_array('sort_order', $pfield_array)) {
                        $sort_order = ', p.sort_order';
                      } else {
                        $sort_order = '';
                      }
                      if ($current_category_id == 0) {
                        if ($manufacturer) {
                          $products_query_raw = "select p.products_id, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, " . $products_msrp_field . " p.products_price, " . $products_cost_field . " p.products_tax_class_id" . $sort_order . " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '$languages_id' and p.manufacturers_id = " . $manufacturer . " $sort_by ";
                        } else {
                          $products_query_raw = "select p.products_id, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, " . $products_msrp_field . " p.products_price, " . $products_cost_field . " p.products_tax_class_id" . $sort_order . " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '$languages_id' $sort_by ";
                        }
                      } else {
                        if ($manufacturer) {
                          $products_query_raw = "select p.products_id, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, " . $products_msrp_field . " p.products_price, " . $products_cost_field . " p.products_tax_class_id" . $sort_order . " from  " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION .  " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pc where p.products_id = pd.products_id and pd.language_id = '$languages_id' and p.products_id = pc.products_id and pc.categories_id = '" . $current_category_id . "' and p.manufacturers_id = " . $manufacturer . " $sort_by ";
                        } else {
                          $products_query_raw = "select p.products_id, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, " . $products_msrp_field . " p.products_price, " . $products_cost_field . " p.products_tax_class_id" . $sort_order . " from  " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION .  " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pc where p.products_id = pd.products_id and pd.language_id = '$languages_id' and p.products_id = pc.products_id and pc.categories_id = '" . $current_category_id . "' $sort_by ";
                        }
                      }

                      // page splitter and display each products info
                      $products_split = new splitPageResults($split_page, MAX_DISPLAY_ROW_BY_PAGE, $products_query_raw, $products_query_numrows);
                      $products_query = tep_db_query($products_query_raw);
                      while ($_products = tep_db_fetch_array($products_query)) {
                        $products[] = $_products;
                        $list_of_products_ids[] = $_products['products_id'];
                      }

                      if (tep_not_null($list_of_products_ids)) {
                        if (in_array('customers_group_id', $field_array)) {
                          if (isset($customers_group_id) && $customers_group_id != '0' && $customers_group_id != '') {
                            $pg_query = tep_db_query("select pg.products_id, customers_group_price as price from " . TABLE_PRODUCTS_GROUPS . " pg where products_id in ('".implode("','",$list_of_products_ids)."') and pg.customers_group_id = '".$customers_group_id."' ");
                            while ($pg_array = tep_db_fetch_array($pg_query)) {
                              $new_prices[] = array ('products_id' => $pg_array['products_id'], 'products_price' => $pg_array['price']);
                            }

                            for ($x = 0; $x < count($list_of_products_ids); $x++) {
                              // delete products_price (retail) first
                              $products[$x]['products_price'] = '';
                              // need to know whether a customer group price is in the table products_groups or not 
                              // (for choosing update or insert)
                              $products[$x]['cg_price_in_db'] = 'no';
                              // replace products prices with those from customers_group table
                              if (!empty($new_prices)) {
                                for ($i = 0; $i < count($new_prices); $i++) {
                                  if ($products[$x]['products_id'] == $new_prices[$i]['products_id'] ) {
                                    $products[$x]['products_price'] = $new_prices[$i]['products_price'];
                                    $products[$x]['cg_price_in_db'] = 'yes';
                                  }
                                } // end for ($i = 0; $i < count($new_prices); $i++)
                              } // end if(!empty($new_prices))
                            } // end for ($x = 0; $x < count($list_of_products_ids); $x++)
                          } // end if (isset($customers_group_id) && $customers_group_id != '0')
                        }
                       
                        // now make sure we get all the specials_id and specials_prices in one query instead of one by one
                        if (in_array('customers_group_id', $field_array)) {
                          if (isset($customers_group_id) && $customers_group_id != '0' && $customers_group_id != '') {
                            $specials_query = tep_db_query("select products_id, specials_id from " . TABLE_SPECIALS . " where products_id in ('".implode("','",$list_of_products_ids)."') and status = '1' and customers_group_id = '" .$customers_group_id. "'");
                          } else {
                            $specials_query = tep_db_query("select products_id, specials_id from " . TABLE_SPECIALS . " where products_id in ('".implode("','",$list_of_products_ids)."') and status = '1' and customers_group_id = '0'");
                          }
                        }
                        while ($specials_array = tep_db_fetch_array($specials_query)) {
                          $new_s_prices[] = array ('products_id' => $specials_array['products_id'], 'specials_id' => $specials_array['specials_id']);
                        }
                        // put in the specials id's
                        for ($x = 0; $x < count($list_of_products_ids); $x++) {
                          // make sure a value for special price and specials_id is added
                          $products[$x]['specials_id'] = '';
                          if (!empty($new_s_prices)) {
                            for ($i = 0; $i < count($new_s_prices); $i++) {
                              if ($products[$x]['products_id'] == $new_s_prices[$i]['products_id'] ) {
                                $products[$x]['specials_id'] = $new_s_prices[$i]['specials_id'];
                              }
                            } // end for ($i = 0; $i < count($new_prices); $i++)
                          } // end if(!empty($new_s_prices))   
                        } // end ($x = 0; $x < count($list_of_products_ids); $x++)
                       
                        // debug:   echo '<pre>products array'; print_r($products);
                       
                        for ($x = 0; $x < count($list_of_products_ids); $x++) {   
                          $rows++;
                          if (strlen($rows) < 2) {
                            $rows = '0' . $rows;
                          }
                          $price = $products[$x]['products_price'];

                          // Check Tax_rate for displaying TTC
                          $tax_query = tep_db_query("select r.tax_rate, c.tax_class_title from " . TABLE_TAX_RATES . " r, " . TABLE_TAX_CLASS . " c where r.tax_class_id=" . $products[$x]['products_tax_class_id'] . " and c.tax_class_id=" . $products[$x]['products_tax_class_id']);
                          $tax_rate = tep_db_fetch_array($tax_query);
                          if ($tax_rate['tax_rate'] == '') $tax_rate['tax_rate'] = 0;
                          // SPPC v1.0: added && DISPLAY_MANUFACTURER == 'true'
                          if (MODIFY_MANUFACTURER == 'false' && DISPLAY_MANUFACTURER == 'true') {
                            $manufacturer_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id=" . $products[$x]['manufacturers_id']);
                            // mixing of global manufacturer and local manufacturer in original quick_updates
                            // change original $manufacturer to another variable
                            $products_manufacturer = tep_db_fetch_array($manufacturer_query);
                          }
                          // display infos per row
                          // SPPC v1.1 this.style.cursor='hand' changed to this.style.cursor='pointer' (valid CSS)
                          
                          echo '<tr class="dataTableRow" onmouseover="';
                          echo 'this.className=\'dataTableRowOver\';" onmouseout="'; 
                          echo 'this.className=\'dataTableRow\'">';

                          if (MODIFY_MODEL == 'true') {
                            echo "<td class=\"smallText\" style=\"border-left: 1px solid #999999;\"><input type=\"text\" size=\"8\" name=\"product_new_model[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_model']."\" onkeyup = \"javascript:hide_pegination();\"></td>\n";
                          } else {
                            echo "<td class=\"smallText\" style=\"border-left: 1px solid #999999; padding-left:10px;\">" . $products[$x]['products_model'] . "</td>\n";
                          }
                          if (MODIFY_NAME == 'true') {
                            echo "<td class=\"smallText\"><input type=\"text\" size=\"15\" name=\"product_new_name[".$products[$x]['products_id']."]\" value=\"".str_replace("\"","&quot;",$products[$x]['products_name'])."\" onkeyup = \"javascript:hide_pegination();\"></td>\n";
                          } else {
                            echo "<td class=\"smallText\" style=\"padding-left:10px;\">".$products[$x]['products_name']."</td>\n";
                          }
                          if (in_array('sort_order', $pfield_array)) {
                            if (DISPLAY_SORT == 'true') echo "<td class=\"smallText\" align=\"left\"><input type=\"text\" size=\"2\" name=\"product_new_sort_order[" . $products[$x]['products_id'] . "]\" value=\"" . $products[$x]['sort_order'] . "\" onkeyup = \"javascript:hide_pegination();\"></td>\n";
                          }
                          if (DISPLAY_STATUT == 'true') {
                            if ($products[$x]['products_status'] == '1') {
                              echo "<td class=\"smallText\" style=\"white-space: nowrap;\">
                              <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr><td>
                              <input type=\"radio\" name=\"product_new_status[".$products[$x]['products_id']."]\" value=\"1\" onclick = \"javascript:hide_pegination();\" checked >On</td><td><input  type=\"radio\" name=\"product_new_status[".$products[$x]['products_id']."]\" value=\"0\" onclick = \"javascript:hide_pegination();\">Off
                              </td></tr></table>
                              </td>\n";
                            } else {
                              echo "<td class=\"smallText\" style=\"white-space: nowrap;\">
                              <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr><td>
                              <input type=\"radio\" style=\"background-color: #EEEEEE\" name=\"product_new_status[".$products[$x]['products_id']."]\" value=\"1\" onclick = \"javascript:hide_pegination();\">On&nbsp;&nbsp;<input type=\"radio\" style=\"background-color: #EEEEEE\" name=\"product_new_status[".$products[$x]['products_id']."]\" value=\"0\" onclick = \"javascript:hide_pegination();\" checked >Off
                              </td></tr></table>
                              </td>\n";
                            }
                          }
                          if (DISPLAY_WEIGHT == 'true') {
                            echo "<td class=\"smallText\"><input type=\"text\" size=\"5\" name=\"product_new_weight[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_weight']."\" onkeyup = \"javascript:hide_pegination();\"></td>\n";
                          }
                          if (DISPLAY_QUANTITY == 'true') {
                            echo "<td class=\"smallText\"><input type=\"text\" size=\"3\" name=\"product_new_quantity[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_quantity']."\" onkeyup = \"javascript:hide_pegination();\"></td>\n";
                          }
                          if (MODIFY_MANUFACTURER == 'true') {
                            echo "<td class=\"smallText\">".tep_draw_pull_down_menu("product_new_manufacturer[".$products[$x]['products_id']."]", $manufacturers_array, $products[$x]['manufacturers_id'],'onchange="hide_pegination();" ')."</td>\n";
                          }
                          if (DISPLAY_COST == 'true') echo "<td class=\"smallText\"><input type=\"text\" size=\"8\" name=\"product_new_cost[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_cost']."\" onkeyup = \"javascript:hide_pegination();\"></td>";
                          if (DISPLAY_RETAIL_PRICE == 'true') echo "<td class=\"smallText\"><input type=\"text\" size=\"8\" name=\"product_new_msrp[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_msrp']."\" onkeyup = \"javascript:hide_pegination();\"></td>";
                          // get the specials products list
                          if (MODIFY_TAX == 'true') {
                            echo "<td class=\"smallText\">". tep_draw_pull_down_menu("product_new_tax[". $products[$x]['products_id'] ."]", $tax_class_array, $products[$x]['products_tax_class_id'], 'onchange="hide_pegination()"  ')."</td>\n";
                          }
                          if (tep_not_null($products[$x]['specials_id'])) {
                            echo "<td class=\"smallText\" style=\"white-space: nowrap;\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"8\" name=\"product_new_price[".$products[$x]['products_id']."]\" value=\"".$products[$x]['products_price']."\" style=\"background-color: lightyellow;\" disabled >&nbsp;<a target=blank href=\"".tep_href_link (FILENAME_SPECIALS, 'sID='.$products[$x]['specials_id']).'&action=edit'."\">". tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_SPECIALS_PRODUCTS) ."</a></td>\n";
                          } else { 
                            require_once(DIR_WS_CLASSES . 'PriceFormatter.php');  
                            $pf = new PriceFormatter();
                            $products_check1 = $pf->loadProduct($products[$x]['products_id']);
                            
                            if (in_array('customers_group_id', $field_array)) {
                              $attributes_query = tep_db_query("select customers_group_id, customers_group_price, customers_group_price1, customers_group_price2, customers_group_price3, customers_group_price4, customers_group_price5, customers_group_price6, customers_group_price7, customers_group_price8, customers_group_price9, customers_group_price10, customers_group_price11 from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $products[$x]['products_id'] . "' and customers_group_id = '" . $customers_group_id . "' order by customers_group_id");
                              $attributes = tep_db_fetch_array($attributes_query);
                              if ($attributes || $customers_group_id == 0) {
                                echo "<td class=\"smallText\" style=\"white-space: nowrap;\"><table border = '0' width = '100%'><tr><td width = '70%'>".$price."</td><td><img src = '".DIR_WS_IMAGES."money.png' onclick = 'javascript:toggle1(\"".$products[$x]['products_id']."\");' ></td></tr></table></td>\n";
                              } else {                              
                                echo "<td class=\"smallText\" style=\"white-space: nowrap;\"><input type=\"text\" size=\"6\" name=\"product_new_price[".$products[$x]['products_id']."]\" "; 
                                echo "onkeyup=\"hide_pegination();\""; 
                                echo " value=\"".$price ."\" style=\"background-color: lightyellow;\">".tep_draw_hidden_field('update_price['.$products[$x]['products_id'].']','yes').tep_image(DIR_WS_IMAGES . 'money-off.png', ''). "</td>\n";
                              }
                            } else {                                      
                              echo "<td class=\"smallText\" style=\"white-space: nowrap;\"><input type=\"text\" size=\"6\" name=\"product_new_price[".$products[$x]['products_id']."]\" onkeyup=\"hide_pegination();\" value=\"".$price ."\" style=\"background-color: lightyellow;\">".tep_draw_hidden_field('update_price['.$products[$x]['products_id'].']','yes') . "</td>\n";
                            }                           
                          } // end if-else (tep_not_null($products[$x]['specials_id']))
                          // links to preview or full edit
                          if (DISPLAY_PREVIEW == 'true') {
                            echo "<td class=\"smallText\" align=\"center\"><a href=\"". tep_href_link (FILENAME_CATEGORIES, 'pID='. $products[$x]['products_id'] .'&action=new_product_preview&read=only&sort_by='. $sort_by .'&page='. $split_page .'&origin='. $origin)."\">". tep_image(DIR_WS_IMAGES . 'icon_preview.gif', TEXT_IMAGE_PREVIEW) ."</a></td>\n";
                          } // end if(DISPLAY_PREVIEW == 'true')
                          if (DISPLAY_EDIT == 'true') {
                            echo "<td class=\"smallText\" align=\"center\" style=\"border-right: 1px solid #999999;\"><a href=\"". tep_href_link (FILENAME_CATEGORIES, 'pID='. $products[$x]['products_id'] .'&cPath='. $categories_products[0] .'&action=new_product')."\">". tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', TEXT_IMAGE_SWITCH_EDIT) ."</a></td>\n";
                          } // end if (DISPLAY_EDIT == 'true')

                          if (in_array('customers_group_id', $field_array)) {
                            // Quantity Price Break or Customer Group Price updates -  BOF
                            echo '<tr class="dataTableRow" id="hidethis_'.$products[$x]['products_id'].'" style = "display:none;background:#F3F9FB">';
                            echo "<td class=\"smallText\" style=\"border-left: 1px solid #999999;border-bottom: 1px solid #999999; padding-left:10px;padding-top:8px;\" colspan = \"10\">";
                            
                            echo '<fieldset><legend>'. TEXT_PRODUCTS_PRICE_GRP.'</legend>
                              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr valign="top">
                                  <td width="50%">
                                    <table  border="0" cellspacing="2" cellpadding="2">';
                                      $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '".$customers_group_id."' AND group_status = '1' AND group_price = '1' order by customers_group_id");

                                      $header = false;
                                      while ($customers_group = tep_db_fetch_array($customers_group_query)) {
                                        if (tep_db_num_rows($customers_group_query) > 0) {
                                          $attributes_query = tep_db_query("select customers_group_id, customers_group_price, customers_group_price1, customers_group_price2, customers_group_price3, customers_group_price4, customers_group_price5, customers_group_price6, customers_group_price7, customers_group_price8, customers_group_price9, customers_group_price10, customers_group_price11 from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $products[$x]['products_id'] . "' and customers_group_id = '" . $customers_group['customers_group_id'] . "' order by customers_group_id");
                                          $attributes = tep_db_fetch_array($attributes_query);
                                        } 
                                        
                                      ?>
                                      <tr>
                                        <td class="main">
                                        <?php // only change in version 4.1.1
                                          echo $customers_group['customers_group_name'].'&nbsp;'.TABLE_HEADING_PRICE;
                                        ?>&nbsp;
                                        </td>
                                        <td class="main">
                                        <?php

                                          
                                        if ($attributes) {
                                          echo tep_draw_input_field('sppcprice[' . $products[$x]['products_id'] . '][0]', $attributes['customers_group_price'], 'size="4" onkeyup = "javascript:hide_pegination();"').'<font color="red">*</font>';
                                          echo tep_draw_hidden_field('sppcprice_old[' . $products[$x]['products_id'] . '][0]', $attributes['customers_group_price'], 'size="4"');
                                        } else {
                                          echo tep_draw_input_field('sppcprice[' . $products[$x]['products_id'] . '][0]', $price , 'size="4" onkeyup = "javascript:hide_pegination();"').'<font color="red">*</font>';
                                          echo tep_draw_hidden_field('sppcprice_old[' . $products[$x]['products_id'] . '][0]', $price , 'size="4"');
                                        }  
                                        ?>
                                      </td>
                                      <?php
                                      for ($i = 1; $i <= PRODUCT_QTY_PRICE_LEVEL; $i++) {
                                        echo '<td class="main">';
                                        if ($attributes) {
                                          echo tep_draw_input_field('sppcprice[' . $products[$x]['products_id'] . '][' . $i . ']', $attributes['customers_group_price' . $i], 'size="4" onkeyup = "javascript:hide_pegination();"');
                                          echo tep_draw_hidden_field('sppcprice_old[' . $products[$x]['products_id'] . '][' . $i . ']', $attributes['customers_group_price' . $i], 'size="4"');
                                        }  else {
                                          echo tep_draw_input_field('sppcprice[' . $products[$x]['products_id'] . '][' . $i . ']', $pf->price[$i] , 'size="4" onkeyup = "javascript:hide_pegination();"');
                                          echo tep_draw_hidden_field('sppcprice_old[' . $products[$x]['products_id'] . '][' . $i . ']', $pf->price[$i] , 'size="4"');
                                        }
                                        echo '</td>';
                                        echo '<td class="main">&nbsp;</td>';
                                      }
                                      ?>
                                    </tr>
                                    <?php
                                    } // end while ($customers_group = tep_db_fetch_array($customers_group_query))
                                    ?>
                                  </table>
                                </td>
                              </tr>
                            </table>
                            <?php
                            echo '</fieldset>'; 
                            echo "</td>\n";
                            echo "<td class=\"smallText\" align=\"center\" style=\"border-right: 1px solid #999999;border-bottom: 1px solid #999999;\"></td></tr>\n";
                            // Quantity Price Break or Customer Group Price updates -  EOF
                          }

                          
                          // Hidden parameters for cache old values
                          if (MODIFY_NAME == 'true') {
                            echo tep_draw_hidden_field('product_old_name['.$products[$x]['products_id'].'] ',$products[$x]['products_name']);
                          } // end if (MODIFY_NAME == 'true')
                          if (MODIFY_MODEL == 'true') {
                            echo tep_draw_hidden_field('product_old_model['.$products[$x]['products_id'].'] ',$products[$x]['products_model']);
                          } // end if (MODIFY_MODEL == 'true')
                          echo tep_draw_hidden_field('product_old_sort_order[' . $products[$x]['products_id'] . ']', $products[$x]['sort_order']);
                          echo tep_draw_hidden_field('product_old_status['. $products[$x]['products_id'] .']',$products[$x]['products_status']);
                          echo tep_draw_hidden_field('product_old_quantity['. $products[$x]['products_id'] .']',$products[$x]['products_quantity']);
                          if (MODIFY_MANUFACTURER == 'true') {
                            echo tep_draw_hidden_field('product_old_manufacturer['. $products[$x]['products_id'] .']',$products[$x]['manufacturers_id']);
                          } // end if (MODIFY_MANUFACTURER == 'true')    
                          echo tep_draw_hidden_field('product_old_weight['. $products[$x]['products_id'] .']',$products[$x]['products_weight']);
                          if (DISPLAY_COST == 'true') echo tep_draw_hidden_field('product_old_cost['.$products[$x]['products_id'].']',$products[$x]['products_cost']);
                          if (DISPLAY_RETAIL_PRICE == 'true') echo tep_draw_hidden_field('product_old_msrp['.$products[$x]['products_id'].']',$products[$x]['products_msrp']);
                          echo tep_draw_hidden_field('product_old_price['. $products[$x]['products_id'] .']',$products[$x]['products_price']);
                          echo tep_draw_hidden_field('cg_price_in_db['. $products[$x]['products_id'] .']',$products[$x]['cg_price_in_db']);   
                          if (MODIFY_TAX == 'true') {
                            echo tep_draw_hidden_field('product_old_tax['. $products[$x]['products_id'] .']',$products[$x]['products_tax_class_id']);
                          } // end if (MODIFY_TAX == 'true')
                        } // end for ($x = 0; $x < count($list_of_products_ids); $x++)
                        // hidden display parameters (only once)
                        echo tep_draw_hidden_field( 'row_by_page', $row_by_page);
                        echo tep_draw_hidden_field( 'sort_by', $sort_by);
                        echo tep_draw_hidden_field( 'page', $split_page);
                        if (in_array('customers_group_id', $field_array)) {
                          if (isset($customers_group_id) && $customers_group_id !='') {
                            echo tep_draw_hidden_field( 'customers_group_id', $customers_group_id);
                          } else {
                            echo tep_draw_hidden_field( 'customers_group_id', '0'); 
                          }
                        }
                      } // end if (tep_not_null($list_of_products_ids)
                      echo "</table>\n";
                    ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td align="right">
                  <?php
                    // display bottom page buttons
                    //echo '<a href="javascript:window.print()">' . tep_image_button('button_print.gif', PRINT_TEXT) . '</a>';
                    echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
                    if (in_array('customers_group_id', $field_array)) {
                      echo '<a href="' . tep_href_link(FILENAME_QUICK_UPDATES,"row_by_page=".$row_by_page."&customers_group_id=" . $customers_group_id . "") . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
                    } else {
                      echo '<a href="' . tep_href_link(FILENAME_QUICK_UPDATES,"row_by_page=".$row_by_page) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
                    }
                    $manufacturer = tep_db_prepare_input($_GET['manufacturer']);
                  ?>
                  </td>
                </tr>
                <?php echo tep_hide_session_id(); ?>
                </form>
                <td>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_ROW_BY_PAGE, $split_page, TEXT_DISPLAY_NUMBER_OF_PRODUCTS);  ?></td>
                    <?php if (in_array('customers_group_id', $field_array)) { ?>
                    <td class="smallText" align="right"><div id='pegination'><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_ROW_BY_PAGE, MAX_DISPLAY_PAGE_LINKS, $split_page, '&cPath='. $current_category_id .'&sort_by='.$sort_by . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer . '&customers_group_id=' . $customers_group_id); ?></div></td>
                    <?php } else { ?>
                    <td class="smallText" align="right"><div id='pegination'><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_ROW_BY_PAGE, MAX_DISPLAY_PAGE_LINKS, $split_page, '&cPath='. $current_category_id .'&sort_by='.$sort_by . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer); ?></div></td>
                    <?php } ?>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
    <!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<script language="javascript">
function toggle1(arg) {  
  if( document.getElementById("hidethis_"+arg).style.display=='none' ){
    document.getElementById("hidethis_"+arg).style.display = '';
  } else {
    document.getElementById("hidethis_"+arg).style.display = 'none';
  }
}
function hide_pegination() {
  document.getElementById("pegination").style.display = 'none';
}
</script>