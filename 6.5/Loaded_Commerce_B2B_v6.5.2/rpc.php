<?php
  require('includes/application_top.php');	
  
  if (isset($_GET['action']) && $_GET['action']) {
    switch ($_GET['action']) {
      case 'cart_remove':
          $cart->remove($_REQUEST['pid']);
          tep_redirect(FILENAME_SHOPPING_CART);
        break;
    }
  }
  exit; 
?>