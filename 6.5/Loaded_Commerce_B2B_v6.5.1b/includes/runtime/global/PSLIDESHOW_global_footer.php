<?php
  if(defined('MODULE_ADDONS_PCSLIDESHOW_STATUS') && MODULE_ADDONS_PCSLIDESHOW_STATUS == 'True') {
    global $content;
    if ($content == CONTENT_INDEX_DEFAULT) {
    ?>
    <link rel="stylesheet" type="text/css" href="includes/slideshow.css">
    <script type="text/javascript" src="includes/javascript/jquery.cycle.all.min.js"></script>
    <script type="text/javascript" src="includes/javascript/jquery.easing.1.3.js"></script>
    <script type="text/javascript">
    <!-- 
      function onPCS1Before() { 
        $('#PCS1Output').animate({ 
            opacity: 0.0
          }, 1000 );
      } 
      function onPCS1After() { 
        $('#PCS1Output').html($(this).attr("alt"));
        $('#PCS1Output').animate({ 
            opacity: 1.0
          }, 500 );
      }
      $(document).ready(function(){
          // Inizialize ProductsCycleSlideshow
          $('#PCS1').cycle({ 
              fx:     '<?php echo MODULE_ADDONS_PCSLIDESHOW_FX?>',<?php echo MODULE_ADDONS_PCSLIDESHOW_EASING != 'None' ? "\n easing: '" . MODULE_ADDONS_PCSLIDESHOW_EASING . "',\n" : ''?>
              sync:   <?php echo MODULE_ADDONS_PCSLIDESHOW_SYNC == 'true' ? '1' : '0' ?>,
              speed:  <?php echo MODULE_ADDONS_PCSLIDESHOW_SPEED ?>,
              timeout: <?php echo MODULE_ADDONS_PCSLIDESHOW_TIMEOUT ?>,
              pause:    <?php echo MODULE_ADDONS_PCSLIDESHOW_PAUSE == 'true' ? '1' : '0' ?>,
              random:  <?php echo MODULE_ADDONS_PCSLIDESHOW_RANDOM == 'true' ? '1' : '0' ?>,
              pager:  '#PCS1Pager',
              before:  onPCS1Before, 
              after:   onPCS1After
          }); 
      });
    -->
    </script>
    <?php
    }
  }
?>