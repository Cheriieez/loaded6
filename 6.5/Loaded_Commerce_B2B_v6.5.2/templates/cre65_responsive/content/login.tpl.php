<?php 
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('login', 'top');
  // RCI code eof
  echo '<div class="col-sm-12">
          <h1 class="col-lg-12 gry_box2 y_clr margin-bottom-15">Account Login</h1>
          <div class="clear"></div>
        </div>';
  if ($messageStack->size('login') > 0) {
    echo '<div class="col-sm-12 margin-bottom-10">';
    echo $messageStack->output('login'); 
    echo '</div>';
  }
  if (PWA_ON == 'false') {
    if (file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PWA_ACC_LOGIN)) {
      require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PWA_ACC_LOGIN);
    } else {     
      require(DIR_WS_MODULES . FILENAME_PWA_ACC_LOGIN);
    }
  } else {       
    if (file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PWA_PWA_LOGIN)) {
      require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PWA_PWA_LOGIN);
    } else {     
      require(DIR_WS_MODULES . FILENAME_PWA_PWA_LOGIN);
    }
  }
  // RCI code start
  echo $cre_RCI->get('login', 'insideformbelowbuttons');
  // RCI code eof    
  // RCI code start
  echo $cre_RCI->get('login', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
?>