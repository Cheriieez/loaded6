<?php
/*
  Id: account.php,v 1.1.1.1 2009/11/04 15:16:30 WGS/Paul Fahey/Mahesh Sawant Exp

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Copyright &copy; 2003-2009 Chain Reaction Works, Inc.

  Last Modified by : $AUTHOR$
  La Revision  : $REVISION$
  Last Revision Date : $DATE$
  License :  GNU General Public License 2.0

  http://creloaded.com
  http://creforge.com

*/
  require('includes/application_top.php');
  // RCI code start
  echo $cre_RCI->get('global', 'top', false);
  echo $cre_RCI->get('email_export', 'top', false); 
  // RCI code eof  

  $cgroup = (isset($_POST['group']) ? $_POST['group'] :'');
  $newsletter = (isset($_POST['newsl']) ? $_POST['newsl'] : '');
  $fdate = (isset($_POST['fdate']) ? $_POST['fdate'] : '');
  $fmonth = (isset($_POST['fmnt']) ? $_POST['fmnt'] : '');
  $fyear = (isset($_POST['fyear']) ? $_POST['fyear'] : '');
  $tdate = (isset($_POST['tdate']) ? $_POST['tdate'] : '');
  $tmonth = (isset($_POST['tmnt']) ? $_POST['tmnt'] : '');
  $tyear = (isset($_POST['tyear']) ? $_POST['tyear'] : '');
  $account = (isset($_POST['account']) ? $_POST['account'] : '');

  // create start and end dates 
  $strdate = '';
  $strdateflag = 0;
  if(($fdate) && (tep_not_null($fdate))){
    $sdate = $fdate;
    $strdateflag = 1;
  }else{
    $sdate = 1;
  }
   if(($fmonth) && (tep_not_null($fmonth))){
    $smonth = $fmonth;
    $strdateflag = 1;
  }else{
    $smonth = 1;
  }
   if(($fyear) && (tep_not_null($fyear))){
    $syear = $fyear;
    $strdateflag = 1;
  }else{
    $syear = date('Y');
  }

   if ($strdateflag) {
    $strdate = mktime(0, 0, 0, $smonth, $sdate, $syear);
  } else {
    $strdate = mktime(0, 0, 0, date("m"), 1, date("Y"));
  }

  // End date 
  $enddate = '';
  $enddateflag = 0;
  if(($tdate) && (tep_not_null($tdate))){
    $edate = $tdate;
    $enddateflag = 1;
  }else{
    $edate = 1;
  }
   if(($tmonth) && (tep_not_null($tmonth))){
    $emonth = $tmonth;
    $enddateflag = 1;
  }else{
    $emonth = 1;
  }
   if(($tyear) && (tep_not_null($tyear))){
    $eyear = $tyear;
    $enddateflag = 1;
  }else{
    $eyear = date('Y');
  }
   if ($enddateflag) {
    $enddate = mktime(0, 0, 0, $emonth, $edate, $eyear);
  } else {
    $enddate = mktime(0, 0, 0, date("m"), 1, date("Y"));
  }
  $savename = 'Cust_details'.date('Ymd').'.csv';
  // create sql 
  $sql_string  = "SELECT c.customers_firstname, c.customers_gender, c.customers_lastname, c.purchased_without_account, c.customers_dob, cg.customers_group_name, c.customers_email_address, c.customers_newsletter, ci.customers_info_date_account_created, sum( op.final_price * op.products_quantity ) AS order_total, count( o.orders_id ) AS count, max( o.date_purchased ) AS value
                           FROM ".TABLE_CUSTOMERS_GROUPS." cg,".TABLE_CUSTOMERS_INFO." ci, ".TABLE_CUSTOMERS." c
                           LEFT JOIN ".TABLE_ORDERS." o ON o.customers_id = c.customers_id
                           LEFT JOIN ".TABLE_ORDERS_PRODUCTS." op ON op.orders_id = o.orders_id";

  $sql_string .= " WHERE ci.customers_info_id = c.customers_id AND c.customers_group_id = cg.customers_group_id AND ci.customers_info_date_account_created BETWEEN '" . tep_db_input(date("Y-m-d H:i:s", $strdate)) . "' AND '" . tep_db_input(date("Y-m-d H:i:s", $enddate)) . "'";
  //Customer Groups
  if($cgroup <> 'all'){
    $sql_string .= "AND c.customers_group_id ='".$cgroup."'";
  }else{
    $sql_string .= "";
  }
  // Registered Clients
  if($account == 'yes'){
    $sql_string .= " AND c.purchased_without_account = '0'";
   }elseif($account == 'no'){
  $sql_string .= " AND c.purchased_without_account = '1'";
  }else{
    $sql_string .= " ";
  }
  // Newsletter 
  if($newsletter == 'yes'){
    $sql_string .= " AND c.customers_newsletter = '1'";
   }elseif($newsletter == 'no'){
  $sql_string .= " AND c.customers_newsletter = '0'";
  }else{
    $sql_string .= " ";
  }
  $sql_string .= "GROUP BY c.customers_id";
  $sql_raw = tep_db_query($sql_string);
   if(isset($_POST['savecsv'])){
     $data = '';
     if($_POST['savecsv']) $data = $_POST['savecsv']."\n";
    //$data = TXT_HEADING ."\n";
    while($result = tep_db_fetch_array($sql_raw)){
      $data .= $result['customers_firstname'].","; 
            $data .= $result['customers_lastname'].",";
            $data .= $result['customers_email_address'].",";
      $data .= tep_date_short($result['customers_dob']).",";
      $data .= $result['customers_group_name'].",";
      if($result['purchased_without_account'] == '0') {  //Registered/Nonregistered clients
        $data .= "Yes ,";
      }else{
        $data .= "No ,";
      }
      if($result['customers_newsletter'] == '0') {  //Newsletter
        $data .= "No ,";
      }else{
        $data .= "Yes ,";
      }
      $data .= tep_date_short($result['customers_info_date_account_created']).","; 
      if($result['customers_gender'] == 'f') {  //Gender
        $data .= "Female ,";
      }elseif($result['customers_gender'] == 'm'){
        $data .= "Male ,";
      }else{
        $data .= "N/A ,";
      }

      if($result['count'] == '0'){
        $data .= "N/A ,";
          $data .= "0 ,";
          $data .= "N/A \n";
      }else{
        $data .= $result['value'].",";
          $data .= $result['count'].",";
          $data .= "$".$result['order_total']."\n";
      }
     }
  header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
  header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  header("Content-Type: Application/octet-stream"); 
  header("Content-Disposition: attachment; filename=$savename"); 
  echo $data; 
  exit;  
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>
<script type="text/javascript">
  if (typeof jQuery == 'undefined') {
    //alert('You are running a local copy of jQuery!');
    document.write(unescape("%3Cscript src='includes/javascript/jquery-1.6.2.min.js' type='text/javascript'%3E%3C/script%3E"));
  }
</script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=300%,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<style type="text/css">

#hintbox{ /*CSS for pop up hint box */
position:absolute;
top: 0;
background-color: lightyellow;
width: 150px; /*Default width of hint.*/ 
padding: 3px;
border:1px solid black;
font:normal 11px Verdana;
line-height:18px;
z-index:100;
border-right: 3px solid black;
border-bottom: 3px solid black;
visibility: hidden;
}

.hintanchor{ /*CSS for link that shows hint onmouseover*/
font-weight: bold;
color: navy;
margin: 3px 8px;
}

</style>

<script type="text/javascript">

/***********************************************
* Show Hint script- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/
    
var horizontal_offset="9px" //horizontal offset of hint box from anchor link

/////No further editting needed

var vertical_offset="0" //horizontal offset of hint box from anchor link. No need to change.
var ie=document.all
var ns6=document.getElementById&&!document.all

function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=(whichedge=="rightedge")? parseInt(horizontal_offset)*-1 : parseInt(vertical_offset)*-1
if (whichedge=="rightedge"){
var windowedge=ie && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-30 : window.pageXOffset+window.innerWidth-40
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure+obj.offsetWidth+parseInt(horizontal_offset)
}
else{
var windowedge=ie && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetHeight
}
return edgeoffset
}

function showhint(menucontents, obj, e, tipwidth){
if ((ie||ns6) && document.getElementById("hintbox")){
dropmenuobj=document.getElementById("hintbox")
dropmenuobj.innerHTML=menucontents
dropmenuobj.style.left=dropmenuobj.style.top=-500
if (tipwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=tipwidth
}
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+obj.offsetWidth+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+"px"
dropmenuobj.style.visibility="visible"
obj.onmouseout=hidetip
}
}

function hidetip(e){
dropmenuobj.style.visibility="hidden"
dropmenuobj.style.left="-500px"
}

function createhintbox(){
var divblock=document.createElement("div")
divblock.setAttribute("id", "hintbox")
document.body.appendChild(divblock)
}

if (window.addEventListener)
window.addEventListener("load", createhintbox, false)
else if (window.attachEvent)
window.attachEvent("onload", createhintbox)
else if (document.getElementById)
window.onload=createhintbox

</script>
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
      <!-- left_navigation //-->
      <?php 
        require(DIR_WS_INCLUDES . 'column_left.php');
      ?>
      <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo TITLE_HEADING; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
        <?php
    $data_string = TXT_B2B_HEADING."\n";
      echo "\n". tep_draw_form('cust_detail', 'email_export.php', '','POST', 'NONSSL');
            echo "\n". tep_draw_hidden_field('savecsv', $data_string, '');
    ?>
         <td>
            <table border="0" width="90%" cellspacing="0" cellpadding="2">
              <tr>
               <td class="smallText" align="left"><?php echo TEXT_DESC; ?></td>
</tr>
</table>
<?php
$cust_group = tep_db_query("SELECT customers_group_name, customers_group_id  FROM                  `".TABLE_CUSTOMERS_GROUPS."`");

$group_array  = array(array('id' => 'all', 'text' => 'All'));
while($result_group = tep_db_fetch_array($cust_group)){
  $group_array[] = array('id' => $result_group['customers_group_id'] , 'text' => $result_group['customers_group_name']);
}
/*
echo "<pre>";
print_r($group_array);
echo "</pre>"; */ 
$d_array = array(array('id' => 'yes', 'text' => 'Yes'), 
                     array('id' => 'no', 'text' => 'No'), 
           array('id' => 'all', 'text' => 'All')) ;
 $newsl_array = $d_array;
 $cust_array = $d_array;
  ?>
  <!-- BOF Custom work here -->
  <table border="0" cellpadding="5" cellspacing="2" width="100%">
    <tr>
      <td width="50" align="center" bgcolor="#ECEFFF"><a href="#" class="hintanchor" onMouseover="showhint('YES the customer has ticked the newsletter on their account. No means they did not. Or choose All', this, event, '150px')">[?]</a></td>
      <td width="200" bgcolor="#ECEFFF"><?php echo "<b>".GEN_TXT."</b>"; ?> </td>
      <td bgcolor="#ECEFFF"><?php echo tep_draw_pull_down_menu('newsl', $newsl_array); ?></td>
      </tr>
    <tr>
      <td width="50" align="center" bgcolor="#ECEFFF"><a href="#" class="hintanchor" onMouseover="showhint('Please choose a Group you have setup or ALL groups.', this, event, '150px')">[?]</a></td>
      <td width="200" bgcolor="#ECEFFF"><?php echo "<b>".CGRP_TXT."</b>" ?></td>
      <td bgcolor="#ECEFFF"><?php echo tep_draw_pull_down_menu('group', $group_array); ?></td>
      </tr>
    <tr>
    <td width="50" bgcolor="#ECEFFF">&nbsp;</td>
  <td width="200" bgcolor="#ECEFFF"><b><?php echo FRM_TXT; ?></b></td>
   <?php
    //start the date
    $raw_date = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
    ?>
    <td bgcolor="#ECEFFF"><select name="fdate" size="1">
      <?php $j = 1;
  for($i = 1; $i <= 31; $i++){
  ?>
      <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
      <?php
      }
?>
  </select><select name="fmnt" size="1">
  <?php
$m = date("n", $raw_date - 60* 60 * 24);
for($i = 1; $i < 13; $i++){
  ?>
    <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
  <?php
    }
?>
  </select><select name="fyear" size="1">
  <?php
$y = date("Y") - date("Y", $raw_date - 60* 60 * 24);
for ($i = 10; $i >= 0; $i--) {
  ?>
    <option<?php if ($y == $i) echo " selected"; ?>><?php echo
date("Y") - $i; ?></option><?php
    }
?>
</select></td>
    </tr>
     <td width="50" bgcolor="#ECEFFF">&nbsp;</td>
   <td width="200" bgcolor="#ECEFFF"><b><?php echo TO_TXT; ?></b></td>
    <td bgcolor="#ECEFFF"><select name="tdate" size="1">
      <?php 
  $j = date("j", $raw_date - 60* 60 * 24);
  for($i = 1; $i <= 31; $i++){
  ?>
      <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
      <?php
      }
?>
  </select><select name="tmnt" size="1">
  <?php
$m = date("n", $raw_date - 60* 60 * 24);
for($i = 1; $i < 13; $i++){
  ?>
    <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
  <?php
    }
?>
  </select><select name="tyear" size="1">
  <?php
$y = date("Y") - date("Y", $raw_date - 60* 60 * 24);
for ($i = 10; $i >= 0; $i--) {
  ?>
    <option<?php if ($y == $i) echo " selected"; ?>><?php echo
date("Y") - $i; ?></option><?php
    }
?>
</select></td>
    </tr>
<!--  <tr>
    <td width="35%"></td>
  <td width="10%"></td>
    <td width="30%">  </td>
    <td width="50%"></td>
  </tr>
    <tr>
    <td width="35%"></td>
  <td width="10%"></td>
    <td width="30%">  </td>
    <td width="50%"></td>
  </tr> -->
   <tr>
     <td width="50" align="center" bgcolor="#ECEFFF"><a href="#" class="hintanchor" onMouseover="showhint('YES selects only people that have Created an Account. NO selects Customers that used Purchase without account. All selects Both.', this, event, '150px')">[?]</a></td>
     <td width="200" bgcolor="#ECEFFF"><?php echo "<b>".ACT_TXT."</b>" ?></td>
     <td bgcolor="#ECEFFF"><?php echo tep_draw_pull_down_menu('account', $cust_array); ?></td>
     </tr>
   <tr>
     <td width="50" bgcolor="#ECEFFF"></td>
     <td width="200" bgcolor="#ECEFFF"></td>
     <td bgcolor="#ECEFFF"><?php echo tep_image_submit('submit.png', 'Save') ?></td>
     </tr>
  </table>
  </form>
<?php
// RCI code start
echo $cre_RCI->get('email_export', 'bottom'); 
echo $cre_RCI->get('global', 'bottom');                                      
// RCI code eof
?>
  <!-- EOF Custom work -->
<!-- body_text_eof //-->
     </tr>
   </table>
  </td>
</tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php 
  require(DIR_WS_INCLUDES . 'footer.php'); 
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>