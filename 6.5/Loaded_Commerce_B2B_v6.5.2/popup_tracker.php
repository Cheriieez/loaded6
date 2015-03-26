<?php
/*
  $Id: popup_traker.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

	require('includes/application_top.php');
    $tracknumbers = $_GET['tracknumbers'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo STORE_NAME; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'off' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script type="text/javascript" language="javascript"><!--
	function pageSubmit() {
		document.forms[0].submit();
	}
// -->
</script>
</head>
<body onload=pageSubmit();>
	<?php
    if($type == '') {
      $type = $_GET['type'];
    } 
    if (preg_match('/USPS/i',$type)) /* for usps shipping */ { ?>
		<FORM NAME="tracking" ACTION="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do" target="_self" method="post" NAME="getTrackNum">
        <?php
            if (isset($_GET[tep_session_name()])) {
                echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            }
        ?>
			<INPUT TYPE="hidden" size="35" NAME="strOrigTrackNum" value="<?php echo $tracknumbers?>"><br>
			<!-- below line is needed for usps -->
			<INPUT TYPE="hidden" NAME=CAMEFROM VALUE=OK>
		</FORM>
	<?php } else if (preg_match('/UPS/i',$type)) /* for ups shipping */ { ?>
		<form NAME="tracking" METHOD="GET" ACTION="http://wwwapps.ups.com/etracking/tracking.cgi" target="_self">
        <?php
            if (isset($_GET[tep_session_name()])) {
                echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            }
        ?>
			<INPUT TYPE="hidden" NAME="InquiryNumber1" value="<?php echo $tracknumbers ?>" >
			<INPUT TYPE="hidden" NAME="TypeOfInquiryNumber" value="T">
			<INPUT TYPE="hidden" NAME="UPS_HTML_Version" Value="3.0">
			<INPUT TYPE="hidden" NAME="IATA" value="us">
			<INPUT TYPE="hidden" NAME="Lang" value="en">
		</FORM>
	<?php } else if (preg_match('/Fedex/i',$type)) /* for fedex shipping */ { ?>
		<FORM NAME="tracking" method="post" ACTION="http://www.fedex.com/Tracking" target="_self">
        <?php
            if (isset($_GET[tep_session_name()])) {
                echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            }
        ?>
			<INPUT TYPE="hidden" NAME="tracknumbers" value="<?php echo $tracknumbers ?>">
			<!-- below lines are needed for fedex -->
			<INPUT TYPE="hidden" NAME="action" VALUE="track">
			<INPUT TYPE="hidden" NAME="language" VALUE="english">
			<INPUT TYPE="hidden" NAME="cntry_code" VALUE="us">
			<INPUT TYPE="hidden" NAME="mps" VALUE="y">
		</FORM>
	<?php } else if (preg_match('/Xpresspost/i',$type)) /* for canada post shipping */ { ?>
		<FORM NAME="tracking" ACTION="https://em.canadapost.ca/emo/basicPin.do?" TARGET="new" METHOD="POST">
			<INPUT TYPE="hidden" NAME="trackingCode" VALUE="PIN">
			<INPUT TYPE="hidden" NAME="action" VALUE="query">
			<INPUT TYPE="hidden" NAME="trackingId" VALUE="<?php echo $tracknumbers ?>">
		</FORM>
	<?php } else if (preg_match('/Airmail/i',$type)) /* for canada airmail post shipping */ { ?>
		<FORM NAME="tracking" ACTION="https://em.canadapost.ca/emo/basicPin.do?" TARGET="new" METHOD="POST">
			<INPUT TYPE="hidden" NAME="trackingCode" VALUE="PIN">
			<INPUT TYPE="hidden" NAME="action" VALUE="query">
			<INPUT TYPE="hidden" NAME="trackingId" VALUE="<?php echo $tracknumbers ?>">
		</FORM>
	<?php } else if (preg_match('/Danmark/i',$type)) /* for post danmark shipping */ { ?>
		<FORM ACTION="http://www.postdanmark.dk/tracktrace/TrackTrace.do?" TARGET="new" METHOD="POST" NAME="TrackTraceForm">
			<INPUT TYPE="hidden" NAME="i_lang" VALUE="INE"> <!-- delete this line for Danish language, English is default -->
			<INPUT TYPE="hidden" NAME="barcode" VALUE="<?php echo $tracknumbers ?>">
		</FORM>
	<?php } else if (preg_match('/Zealand/i',$type)) /* for new zealand post shipping */ { ?>
		<FORM NAME="Form1" method="post" action="http://www.track.courierpost.co.nz/trackntrace_response.asp?" id="Form1">
			<INPUT TYPE="hidden" name="requestmode" value="customer" />
			<INPUT TYPE="hidden" NAME="txtItemID" value="<?php echo $tracknumbers?>" id="txtItemID">
		</FORM>
    <?php 
  } 
?>
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');  
?>