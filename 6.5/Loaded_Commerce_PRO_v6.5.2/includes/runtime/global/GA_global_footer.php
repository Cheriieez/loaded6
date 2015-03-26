<?php
/*
  $Id: GA_global_footer.php,v 1.0.0.0 2008/02/14 13:41:11 datazen Exp $
      -->  updated to asynchronous on 2012/06/22 16:08:05 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $request_type, $languages_id, $content;

$rci = '<!-- Google Analytics Footer RCI start -->' . "\n";
if (defined('GOOGLEANALYTICS_UA_NUMBER') && (GOOGLEANALYTICS_UA_NUMBER != 'UA-' && GOOGLEANALYTICS_UA_NUMBER != '')) {
  $rci .= '<script type="text/javascript">' . "\n";
  $rci .= '  (function() {' . "\n";
  $rci .= '    var ga = document.createElement(\'script\');' . "\n";
  $rci .= '    ga.type = \'text/javascript\';' . "\n";
  $rci .= '    ga.async = true;' . "\n";
  $rci .= '    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';' . "\n";
  $rci .= '    var s = document.getElementsByTagName(\'script\')[0];' . "\n";
  $rci .= '    s.parentNode.insertBefore(ga, s);' . "\n";
  $rci .= '  })();' . "\n";
  $rci .= '</script>' . "\n";
} else { // show analytics code not active only on view source
  $rci .= '<!-- Google Analytics Not Active! -->' . "\n"; 
}
$rci .= '<!-- Google Analytics Footer RCI end -->' . "\n"; 

return $rci;
?>