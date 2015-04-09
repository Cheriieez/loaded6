<?php
/*
  $Id: header.php,v 1.0 2008/06/23 00:18:17 datazen Exp $
  
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com
  
  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce
  
  Released under the GNU General Public License
*/
?>
<!-- Header Section -->
<header> 
  <link rel="SHORTCUT ICON" href="/favicon.ico" />
  <section class="top_info">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <span class="hidden-xs top">
            <a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo MENU_TEXT_HOME; ?></a>
            <a href="<?php echo tep_href_link(FILENAME_FEATURED_PRODUCTS, '', 'NONSSL'); ?>"><?php echo MENU_TEXT_FEATURED; ?></a>
            <a href="<?php echo tep_href_link(FILENAME_SPECIALS, '', 'NONSSL'); ?>"><?php echo MENU_TEXT_SPECIALS; ?></a>
            <a href="<?php echo tep_href_link(FILENAME_PRODUCTS_NEW, '', 'NONSSL'); ?>"><?php echo MENU_TEXT_NEW_PRODUCTS; ?></a>
            <a href="javascript:void(0);" class="ylwphone"><?php echo cre_site_branding('phone'); ?></a>
          </span>
          <!-- Only Mobile Display Content Logo & Search Start -->
          <div class="col-lg-12 top_mdisplay text-center blogo">
            <?php echo cre_site_branding('logo'); ?>
          </div>
          <div class="col-lg-12 top_mdisplay top_search mbot15">
            <?php echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get', 'style="margin:0;padding:0;"'); ?>
              <div class="form-group"> 
                <input type="Search" class="form-control" name="keywords" placeholder="Search">
                <button class="glyphicon glyphicon-search form-s-icon"></button> 
              </div> 
            </form>
          </div>
          <!-- Only Mobile Display Content Logo & Search End -->
          <ul class="list-inline top_mhide pull-right">  
            <li class="top_search">
              <?php echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get','style="margin:0;padding:0;"'); ?>
                <div class="form-group"> 
                  <input type="Search" class="form-control" name="keywords" placeholder="Search">
                  <button class="glyphicon glyphicon-search form-s-icon"></button> 
                </div>
              </form>
            </li>
            <li class="cust-checkout">
              <a href="shopping_cart.php">
                <i class="fa fa-shopping-cart"></i><?php echo MENU_TEXT_CART; ?>:<strong> <?php echo $cart->count_contents(); ?> <?php echo strtolower(MENU_TEXT_ITEMS); ?></strong>
              </a>
            </li> 
            <?php
              if (isset($_SESSION['customer_id'])) { 
                $login_link = '<li class="cust-logoff"><a href="' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . '" class="headerNavigation"><i class="fa fa-lock fa-1x"></i>' . MENU_TEXT_LOGOUT . '</a></li>';
                $acct_link = '<li class="cust-acct"><a href="' . tep_href_link('account.php', '', 'SSL') . '" class="headerNavigation"><i class="fa fa-user fa-1x"></i>My Account</a></li>';
              } else {
                $login_link = '<li class="cust-login"><a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '" class="headerNavigation"><i class="fa fa-lock fa-1x"></i>' . MENU_TEXT_LOGIN . '</a></li>'; 
                $acct_link = '<li class="cust-signup"><a href="' . tep_href_link('create_account.php', '', 'SSL') . '" class="headerNavigation"><i class="fa fa-user fa-1x"></i>Sign Up</a></li>'; 
              } 
              echo $login_link; 
              echo $acct_link;
            ?>
          </ul> 
        </div>
      </div>
    </div>                
  </section>
  <section class="top_logo hidden-xs">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center blogo">
          <?php echo cre_site_branding('logo'); ?>
        </div>
      </div>
    </div>
  </section>
  <div class="navbar navbar-inverse navbar-static-top main_nav" role="navigation">
    <div class="container">
      <!-- Only Mobile Display Content Top Links Start -->
      <div class="row collapse_top_info">
        <div class="top_info"> 
          <div class="col-lg-12">
            <ul class="list-inline">   
              <li>
                <a href="shopping_cart.php">
                  <i class="fa fa-shopping-cart fa-2x"></i><strong> <?php echo $cart->count_contents(); ?> items&nbsp;&nbsp;</strong>
                </a>
              </li>                    
              <?php
                if (isset($_SESSION['customer_id'])) { 
                  $login_link = '<li class="cust-logoff"><a href="' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . '" class="headerNavigation"><i class="fa fa-lock fa-1x"></i>' . MENU_TEXT_LOGOUT . '</a></li>';
                } else {
                  $login_link = '<li class="cust-login"><a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '" class="headerNavigation"><i class="fa fa-lock fa-1x"></i>' . MENU_TEXT_LOGIN . '</a></li>'; 
                } 
                echo $login_link;
              ?>
              <li class="cust-checkout"><a href="shopping_cart.php"><i class="fa fa-check fa-1x"></i>CheckOut</a></li>
              <?php
                if (isset($_SESSION['customer_id'])) { 
                  $login_link = '<li class="cust-acct"><a href="' . tep_href_link('account.php', '', 'SSL') . '" class="headerNavigation"><i class="fa fa-user fa-1x"></i>My Account</a></li>';
                } else {
                  $login_link = '<li class="cust-signup"><a href="' . tep_href_link('create_account.php', '', 'SSL') . '" class="headerNavigation"><i class="fa fa-user fa-1x"></i>Sign Up</a></li>'; 
                } 
                echo $login_link;
              ?> 
              <li class="support-phone"><i class="fa fa-phone fa-1x"></i><?php echo cre_site_branding('phone'); ?></li>
            </ul>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
      <!-- Only Mobile Display Content Top Links End -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only"><?php echo MENU_TEXT_TOGGLE_NAV; ?></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <?php
            foreach (get_top_categories() as $cat) {
              echo '<li><a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cat['id']) . '">' . $cat['text'] . '</a></li>';
            }
          ?>
        </ul>
      </div>
    </div>
  </div> 
</header>  
<!-- Header Section End -->