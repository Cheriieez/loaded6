<?php
  /*
  $Id: printresponsiveorder.php,v 1.1 2003/01 xaglo

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  */

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ORDERS_PRINTABLE);

  if (file_exists(TEMPLATE_FS_CUSTOM_INCLUDES . 'languages/' . $language . '.php')) {
    require(TEMPLATE_FS_CUSTOM_INCLUDES . 'languages/' . $language . '.php');
  }

  // get the order so we know what we are working with
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order((int)$_GET['order_id']);

  $authorized = false;
  if (isset($_SESSION['noaccount']) && $order->customer['id'] == 0) {
    $authorized = true;
  } else if ($_SESSION['customer_id'] == $order->customer['id']) {
    $authorized = true;
  } else if ($order->info['payment_method'] == 'worldpay_junior') {
    $authorized = true;
  } else if ($_SESSION['customer_id'] == '') {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  if ($authorized) {
    $payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". (int)$_GET['order_id'] . "'");
    $payment_info = tep_db_fetch_array($payment_info_query);
    $_SESSION['payment_info'] = isset($payment_info['payment_info']) ? $payment_info['payment_info'] : '';
  }
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="http://prositedemos.com/65b2b/index.php">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Loaded Commerce</title>
<!-- Google Font API -->
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Economica:400,400italic,700,700italic">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,500,700">
<!-- Font Awesome API -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<!-- Bootsrap core CSS -->
<link rel="stylesheet" href="templates/cre65_responsive/css/bootstrap.css">
<!-- Custom CSS of this site -->
<link rel="stylesheet" href="templates/cre65_responsive/css/style.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript">
  if (typeof jQuery == 'undefined') {
    //alert('You are running a local copy of jQuery!');
    document.write(unescape("%3Cscript src='includes/javascript/jquery-1.6.2.min.js' type='text/javascript'%3E%3C/script%3E"));
  }
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="templates/cre65_responsive/js/bootstrap.min.js"></script>
</head>
<body>
<section class="container middle">
  <?php if ($authorized) { ?>
  <div class="row margin-top-15 margin-bottom-15">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <script type="text/javascript">
        if (window.print) {
          document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
        }
        else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
      </script>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
      <p class="main"><a href="javascript:window.close();"><img src="images/close_window.jpg"></a></p>
    </div>
  </div>
  <div class="row margin-bottom-15">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><?php echo nl2br(STORE_NAME_ADDRESS); ?></div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right"><?php echo tep_image(DIR_WS_IMAGES . '/logo/' .  STORE_LOGO, STORE_NAME); ?></div>
  </div>
  <div class="row margin-bottom-15">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><strong><?php echo TITLE_PRINT_ORDER . ' #' . (int)$_GET['order_id']; ?></strong></div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></div>
  </div>
  <div class="row margin-bottom-15">               
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <?php echo '<b>' . ENTRY_PAYMENT_METHOD . '</b> ' . $order->info['payment_method']; ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <?php echo $_SESSION['payment_info']; ?>
    </div>
  </div>
  <div class="row margin-bottom-15">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <?php echo '<b>' . ENTRY_DATE_PURCHASED . '</b> ' . $order->info['date_purchased']; ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <?php echo $_SESSION['payment_info']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <div class="panel panel-default">   
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo ENTRY_SOLD_TO; ?></h3>
        </div>
        <div class="panel-body">
          <div class="col-sm-12">
            <?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '&nbsp;', '<br>'); ?>
          </div>     
        </div>
      </div>      
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <div class="panel panel-default">   
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo ENTRY_SHIP_TO; ?></h3>
        </div>
        <div class="panel-body">
          <div class="col-sm-12">
            <?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '&nbsp;', '<br>'); ?>
          </div>     
        </div>
      </div>      
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="panel panel-default">   
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo TABLE_HEADING_PRODUCTS; ?></h3>
        </div>                                             
        <div class="panel-body neg-margin-20 padding-top-20">
          <div class="col-sm-12">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr class="">
                    <td colspan="2"><?php echo TEXT_NAME; ?></td>
                    <td><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
                    <td align="right"><?php echo TABLE_HEADING_TAX; ?></td>
                    <td align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
                    <td align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
                    <td align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
                      echo '      <tr>' . "\n" .
                           '        <td valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
                           '        <td valign="top">' . $order->products[$i]['name'] . '<br>';
                                    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
                                      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
                                        echo '<nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i><br></small></nobr>';
                                      }
                                    }
                      echo '        </td>' . "\n" .
                           '        <td valign="top">' . $order->products[$i]['model'] . '</td>' . "\n";
                      echo '        <td align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
                           '        <td align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
                           '        <td align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
                           '        <td align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
                      echo '      </tr>' . "\n";
                    }
                  ?>
                </tbody>
              </table>
            </div>            
          </div>     
        </div>
      </div>      
    </div>
  </div>
  <div class="row margin-bottom-15 margin-right-10">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <?php
        for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
          echo '          <div class="row padding-bottom-5">' . "\n" .
               '            <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11 text-right">' . "\n" .
               '              ' . $order->totals[$i]['title'] . "\n" .
               '            </div>' . "\n" . 
               '            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 padding-0 text-right">' . "\n" .
               '              ' . $order->totals[$i]['text'] . "\n" .
               '            </div>' . "\n" .
               '          </div>' . "\n";
        }
      ?>
    </div>
  </div>
  <?php } else { ?>
  <div class="row margin-top-20">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="panel panel-default">   
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo TEXT_HEADING_ACCESS_ERROR; ?></h3>
        </div>                                             
        <div class="panel-body neg-margin-20 padding-top-20">
          <div class="col-sm-12">
            <p class="padding-10"><?php echo ENTRY_ACCESS_ERROR; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>    
  <?php } ?>
</section>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="templates/cre65_responsive/js/bootstrap.min.js"></script>
<script src="templates/cre65_responsive/js/custom.js"></script>
<script src="templates/cre65_responsive/js/docs.min.js"></script> 
<script type="text/javascript">
  /** 
    Thanks to CSS Tricks for pointing out this bit of jQuery
    http://css-tricks.com/equal-height-blocks-in-rows/
    It's been modified into a function called at page load and then each time the page is resized. 
    One large modification was to remove the set height before each new calculation. 
  */ 
  equalheight = function(container) {
    var currentTallest = 0,
        currentRowStart = 0,
        rowDivs = new Array(),
        $el,
        topPosition = 0;
    $(container).each(function() {
      $el = $(this);
      $($el).height('auto')
      topPostion = $el.position().top;
      if (currentRowStart != topPostion) {
        for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
          rowDivs[currentDiv].height(currentTallest);
        }
        rowDivs.length = 0; // empty the array
        currentRowStart = topPostion;
        currentTallest = $el.height();
        rowDivs.push($el);
      } else {
        rowDivs.push($el);
        currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
      }
      for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
        rowDivs[currentDiv].height(currentTallest);
      }
    });
  }  
  $(window).load(function() {
    equalheight('.thumbnail');
    equalheight('.productListingHolder');
  });
  $(window).resize(function(){
    equalheight('.thumbnail');
    equalheight('.productListingHolder');
  });
  function fnToggle() {
    $('input[type="checkbox"]').each(function () {
      if ($(this).is(':checked'))
        $(this).prop('checked', false);
      else
        $(this).prop('checked', true);
    });
    return false;
  }
  $(document).ready(function() {
    $(".blogo img").addClass("img-responsive");
  });
</script>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>