<?php 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('checkoutshipping', 'top');
  // RCI code eof
  echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); 

  //echo $_SESSION['shipping']['id'];
  if (isset($_GET['shipping_error'])) {  
    $error['error'] = TEXT_CHOOSE_SHIPPING_METHOD ;
  ?>
  <p><?php echo tep_output_string_protected($error['error']); ?></p>
  <?php
  }
?>
<h1 class="col-lg-12 gry_box2 y_clr con_txt">Delivery Information</h1>
<p class="top20">Please choose from your address book where you would like the items to be delivered to.</p>
<div class="panel panel-default"> 	
  <div class="panel-heading">
    <h3 class="panel-title">Delivery Information</h3>
  </div>
  <div class="panel-body">
    <address>
      <?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['sendto'], true, ' ', '<br>'); ?>  
    </address>
    <?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '" class="btn btn-primary">' . tep_template_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a>'; ?>
  </div>
</div>
<div class="panel panel-default"> 	
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></h3>
  </div>
  <div class="panel-body">
    <?php
      //MVS start
      if (tep_count_shipping_modules() > 0 || MVS_STATUS == 'true') {
        if (MVS_STATUS == 'true') {    
          require(DIR_WS_MODULES . 'vendor_shipping.php');
        } else {
        ?>
        <div class="col-lg-12 table-responsive shippngtable">
          <table class="table cartable">
            <?php
              if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
              ?>
              <tr>
                <td colspan="2"><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></td>
              </tr>
              <?php
              } elseif ($free_shipping == false) {
              ?>
              <tr>
                <td colspan="4"><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></td>
              </tr>
              <?php
              }
              if ($free_shipping == true) {
              ?>
              <tr>
                <td colspan="4" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      <td class="main" colspan="3"><b><?php echo FREE_SHIPPING_TITLE; ?></b>&nbsp;<?php echo (isset($quotes[$i]['icon']) ? $quotes[$i]['icon'] : ''); ?></td>
                      <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    </tr>
                    <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, 0)">
                      <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      <td class="main" width="100%"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format($freeshipping_over_amount)) . tep_draw_hidden_field('shipping', 'free_free'); ?></td>
                      <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    </tr>
                  </table></td>
              </tr>
              <?php
              } else {
                $radio_buttons = 0;
                // echo '<pre>'; print_r($quotes);
                $n=sizeof($quotes);
                for($i=($n-1); $i>=0; $i--) {
                ?>
                <tr>
                  <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <?php if ($quotes[$i]['module'] != '') { ?>
                    <tr>
                    <td colspan="5">
                      <b><?php echo $quotes[$i]['module']; ?></b>&nbsp;<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?></td>
                    </tr>
                    <?php
                    } 
                    if (isset($quotes[$i]['error'])) {
                    ?>
                    <tr>
                      <td colspan="5"><?php echo $quotes[$i]['error']; ?></td>
                    </tr>
                    <?php
                    } else {
                      for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
                        // set the radio button to be checked if it is the method chosen
                        $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $_SESSION['shipping']['id']) ? true : false);
                        if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {
                          echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                        } else {
                          echo '<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                        }
                      ?>
                      <td class="main" width="75%" colspan="3"><?php echo $quotes[$i]['methods'][$j]['title']; ?></td>
                      <?php
                        if ( ($n > 1) || ($n2 > 1) ) {
                          $cost1	=	$currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0)));
                          if($quotes[$i]['id'] != 'upsxml')
                            $cost	=	($cost1=='$0.00')?' - - - ':$cost1;
                          else
                            $cost	=	$cost1;
                        ?>
                        <td class="main" width="100"><?php echo $cost; ?></td>
                        <td class="main" align="right">
                          <div class="radio">
                            <label>
                              <?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked,'class=""'); ?>
                            </label>
                          </div>
                        </td>
                        <?php
                        } else {
                          $cost1	=	$currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0)));
                          if($quotes[$i]['id'] != 'upsxml')
                            $cost	=	($cost1=='$0.00')?' - ':$cost1;
                          else
                            $cost	=	$cost1;
                        ?>
                        <td class="main" width="100"><?php echo $cost; ?></td>
                        <td class="main" align="right"><?php echo tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></td>
                        <?php
                        }
                      ?>
                    </tr>
                    <?php
                      $radio_buttons++;
                    }
                  }
                ?>
              </table></td>
              </tr>
              <?php
              }
            }
          ?>
          </table>
          <?php			
          }
        }
        // echo '<pre>'; print_r($_POST); print_r($_SESSION);
      ?> 
      <script>
        $(document).ready(function(){
          $('input:radio[name=shipping]').click(function() {
            var thisName = $(this).attr('name');
            var ship = $('input[name=' + thisName + ']:radio:checked').val();
            if (ship == 'flat_flat') {
              $('.rush_div').css("display", "block");
            } else {
              $('.rush_div').css("display", "none");
            }
          });
        });
        function rush_check() {
          var allchecked = 0;
          if (document.getElementById('return_check').checked) allchecked = 1;
          if (allchecked == 0) {
            alert('Please Accept the return policy');
            return false;
          }
          var rcss	=	$('.rush_div').css("display");
          var rval	=	$('input:text[name=rush_media]').val();
          if (rcss == 'none') {
            document.forms["checkout_address"].submit();
          } else if (rcss == 'block' && rval.length > 0) {
            document.forms["checkout_address"].submit();
          } else {
            alert('Please enter conveinant  contact information.');
          }
        }
      </script>
      <?php if(isset($_SESSION['shipping'][id]) && $_SESSION['shipping'][id]=='flat_flat'){$divstyle='block';}else{$divstyle='none';}?>
      <div class="rush_div form-group" style="display:<?=$divstyle?>;">
        <b><font color="#d9534f">Rush Orders:</font> Do you require Rush Shipping? A Big Rig Chrome Shop sales representative will be in contact with you to discuss your options. Online orders will not be processed until charges are approved by the customer.  Please include your requested delivery date along with what specific parts are requiring Rush Shipping in the comment section of the checkout.<br /><br /><font color="#d9534f">International Orders(Outside the USA):</font> Shipping charges to be determined. A Big Rig Chrome Shop sales representative will contact you by email with the estimated shipping charges before processing your order. Shipping charges must be approved by customer before order is processed.<br /><br /><font color="#d9534f">Oversize Items:</font> Shipping charges to be determined. Items that ship oversize can include freight as well. A Big Rig Chrome Shop sales representative will contact you with shipping charges before processing your order. Shipping charges must be approved by customer before order is processed.<br /><br /></b>
        <br>
        <label><font color="#d9534f">Please enter most convenient contact information: (Home, Cell, Email)</font></label>
        <?php echo tep_draw_input_field('rush_media',$_SESSION['rush_media'],'class="form-control rush_input"'); ?> 
      </div>
    </div>
  </div>
</div>
<div class="panel panel-default">   
  <div class="panel-heading">
    <h3 class="panel-title">Order Comments</h3>
  </div>
  <div class="panel-body">
    <div class="form-group">
      <label><?php echo TABLE_HEADING_COMMENTS; ?></label>
      <?php echo tep_draw_textarea_field('comments', 'soft', '60', '5', '', 'class="form-control"'); ?>    
    </div>
  </div>
</div>
<div class="panel panel-default">   
  <div class="panel-heading">
    <h3 class="panel-title">Terms & Conditions</h3>
  </div>
  <div class="panel-body">
    <div class="form-group">
      <label><?php echo '<input type="checkbox"return" id="return_check">'.' I agree with the <a href="'.tep_href_link("pages.php","pID=5").'" target="_blank">Return Policy</a>'; ?></label>
    </div>
  </div>
</div>  
<?php
  //RCI above buttons
  echo $cre_RCI->get('checkoutshipping', 'insideformabovebuttons');
?>  
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 cleafix">
  <div class="checkout-procedure">
    <div class="col-sm-6"><h5>Continue Checkout Procedureto confirm this order.</h5></div>
    <div class="col-sm-6 text-right">
      <!--<button class="btn btn-primary">Continue</button>-->
      <input type="button" class="btn btn-primary" value="Continue" onclick="javascript:rush_check()" />
    </div>
  </div>
  <?php
    //RCI below buttons
    echo $cre_RCI->get('checkoutshipping', 'insideformbelowbuttons');
  ?>
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-top-15 cleafix">  
    <div class="stepwizard">
      <div class="stepwizard-row">
        <div class="stepwizard-step">
          <button type="button" class="btn btn-primary btn-circle">1</button>
          <p>Delivery Information</p>
        </div>
        <div class="stepwizard-step">
          <button type="button" class="btn btn-danger btn-circle">2</button>
          <p>Payment Information</p>
        </div>
        <div class="stepwizard-step">
          <button type="button" class="btn btn-danger btn-circle">3</button>
          <p>Confirmation</p>
        </div>
        <div class="stepwizard-step">
          <button type="button" class="btn btn-danger btn-circle">4</button>
          <p>Finished!</p>
        </div> 
      </div>
    </div>
  </div>
</div>
</form>
<?php 
  // RCI code start
  echo $cre_RCI->get('checkoutshipping', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>