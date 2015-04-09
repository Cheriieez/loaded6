<?php
/*
  $Id: footer.php,v 1.0 2008/06/23 00:18:17 datazen Exp $
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com
  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/
  // RCI top
  echo $cre_RCI->get('footer', 'top');
  if (DOWN_FOR_MAINTENANCE == 'false' || DOWN_FOR_MAINTENANCE_FOOTER_OFF == 'false') {
?>
<!-- Footer Section -->
<footer>
  <div class="f_top">
    <div class="container">
      <div class="row">	
        <div class="col-lg-6 col-md-5 col-sm-5">
          <?php echo cre_site_branding('slogan'); ?>
        </div>
        <div class="col-lg-6 col-md-7 col-sm-7 f_top_form text-right">
          <img src="templates/cre65_responsive/images/f_cards.png" width="200" alt="<?php echo TEXT_PAYMENT_TYPES; ?>" title="<?php echo TEXT_PAYMENT_TYPES; ?>" class="img-responsive pull-right">
          <!-- Newsletter Signup for later /////////
          <form class="" action="mail_test.php">
            <div class="col-md-7 input-group pull-right">
              <input type="" class="form-control nl_email" placeholder="Sign Up for Our Newsletter" name="email">
              <div class="input-group-btn">
                <button class="btn btn-danger nl_submit" type="button"><?php //echo TEXT_SUBSCRIBE; ?></button>
              </div>
            </div>
            <span class="text-warning" id="nl_result"></span>
          </form>-->
        </div>
      </div>
    </div>
  </div>
  <div class="container f_mid margin-top-20 padding-bottom-10">
    <div class="row">
      <div class="col-sm-3">
        <h3><?php echo MENU_TEXT_MEMBERS; ?></h3>
        <ul class="list-unstyled">
          <?php
            if (isset($_SESSION['customer_id'])) { 
              $login_link = '<li><a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . MENU_TEXT_MEMBERS . '</a></li>';
            } else {
              $login_link = '<li><a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . MENU_TEXT_LOGIN . '</a></li>'; 
            } 
            echo $login_link;
          ?>           
          <li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'); ?>"><?php echo TEXT_MY_ORDERS; ?></a></li>                
          <li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'); ?>"><?php echo TEXT_MY_ACCT_INFO; ?></a></li>                
          <li><a href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'); ?>"><?php echo TEXT_MY_ADDRESS_BOOK; ?></a></li>                
          <li><a href="<?php echo tep_href_link(FILENAME_WISHLIST, '', 'SSL'); ?>"><?php echo TEXT_MY_WISHLIST; ?></a></li>                
          <li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'); ?>"><?php echo TEXT_MY_NOTIFICATIONS; ?></a></li>                
          <li><a href="<?php echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'); ?>"><?php echo TEXT_FORGOT_PASS; ?></a></li>                
        </ul>
      </div>
      <div class="col-sm-3">
        <h3><?php echo TEXT_PRODUCT_LINKS; ?></h3>
        <ul class="list-unstyled">
          <li><a href="<?php echo tep_href_link(FILENAME_FEATURED_PRODUCTS, '', 'NONSSL'); ?>"><?php echo MENU_TEXT_FEATURED; ?></a></li>
          <li><a href="<?php echo tep_href_link(FILENAME_PRODUCTS_NEW, '', 'NONSSL'); ?>"><?php echo MENU_TEXT_NEW_PRODUCTS; ?></a></li>
          <li><a href="<?php echo tep_href_link(FILENAME_SPECIALS, '', 'NONSSL'); ?>"><?php echo MENU_TEXT_SPECIALS; ?></a></li>
          <li><a href="<?php echo tep_href_link(FILENAME_UPCOMING_PRODUCTS, '', 'NONSSL'); ?>"><?php echo TEXT_UPCOMING_PRODUCTS; ?></a></li>
          <li><a href="<?php echo tep_href_link(FILENAME_ALLPRODS, '', 'NONSSL'); ?>"><?php echo TEXT_ALL_PRODUCTS; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h3><?php echo TEXT_MORE_INFO; ?></h3>
        <ul class="list-unstyled">
          <li><a href="<?php echo tep_href_link(FILENAME_FAQ, '', 'NONSSL'); ?>"><?php echo TEXT_FAQS; ?></a></li>
          <li><a href="<?php echo tep_href_link(FILENAME_LINKS, '', 'NONSSL'); ?>"><?php echo TEXT_LINKS; ?></a></li>
          <li><a href="<?php echo tep_href_link(FILENAME_REVIEWS, '', 'NONSSL'); ?>"><?php echo TEXT_REVIEWS; ?></a></li>
          <li><a href="<?php echo tep_href_link(FILENAME_ARTICLES, '', 'NONSSL'); ?>"><?php echo TEXT_ARTICLES; ?></a></li>
          <li><a href="<?php echo tep_href_link(FILENAME_CONTACT_US, '', 'NONSSL'); ?>"><?php echo MENU_TEXT_CONTACT_US; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h3><?php echo TEXT_STORE_INFO; ?></h3>
        <p><?php echo get_custom_branding('name'); ?></p>
        <p><?php echo STORE_NAME_ADDRESS; ?></p>
        <p><?php 
          if (get_custom_branding('phone') != '') {
            echo 'Tel: ' . get_custom_branding('phone') . '<br />'; 
          } 
          if (get_custom_branding('fax') != '') {
            echo 'Fax: ' . get_custom_branding('fax') . '<br />'; 
          } 
          if (cre_site_branding('phone') != '') {
            echo 'Toll-Free: ' . cre_site_branding('phone') . '<br />'; 
          }  
        ?></p>
      </div>
    </div>
  </div>
  <!--<div class="f_bot">
    <div class="container">
      <div class="row">  
        <div class="col-xs-6 col-sm-9 col-md-8 col-lg-6 margin-top-5">
          <?php //echo TEXT_COPYRIGHT . date("Y") . ' ' . get_custom_branding('name') . TEXT_ALL_RIGHTS_RESERVED; ?>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-4 col-lg-6 pull-right">
          <img src="templates/cre65_responsive/images/f_cards.png" width="200" alt="<?php //echo TEXT_PAYMENT_TYPES; ?>" title="<?php //echo TEXT_PAYMENT_TYPES; ?>" class="img-responsive pull-right">
        </div>
      </div>
    </div>
  </div>-->
</footer>
<?php
  }
  // RCI bottom
  echo $cre_RCI->get('footer', 'bottom');
?>    
<script src="http<?php echo (($request_type == 'SSL') ? 's' : null); ?>://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
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

  function checkstate(country) {
    var url="getstate.php?country="+country;
    $.get(url, function(data) {
      $('#SHOWSTATE').html(data);
    });
  }
  function removeFromCart(p){
    location.href="rpc.php?pid="+p+"&action=cart_remove";
  }
  function fnToggle() {
    $('input[type="checkbox"]').each(function () {
      if ($(this).is(':checked'))
        $(this).prop('checked', false);
      else
        $(this).prop('checked', true);
    });
    return false;
  }
  function popupResponsiveWindow(url) {
    window.open(url, 'popupResponsiveWindow', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=800, height=900, screenX=150, screenY=150, top=150, left=150');
  }
  $(document).ready(function() {
    $(".blogo img").addClass("img-responsive");
  });
</script>