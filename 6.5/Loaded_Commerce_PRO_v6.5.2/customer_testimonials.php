<?php
/*
  Id: customer_testimonials.php,v 1.1.1.1 2011/07/01 23:37:52 wa4u Exp 

  Contribution Central, Custom CRE Loaded Programming
  http://www.contributioncentral.com
  Copyright (c) 2007 Contribution Central

  Released under the GNU General Public License
*/
ini_set('display_errors','on');
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CUSTOMER_TESTIMONIALS);
  
      if (isset($_GET['action']) && tep_not_null($_GET['action'])) {
      if($_GET['action'] == 'insert') {
        $testimonials_id = tep_db_prepare_input($_POST['testimonials_id']);
        $testimonials_title = tep_db_prepare_input($_POST['testimonials_title']);
        $testimonials_name = tep_db_prepare_input($_POST['testimonials_name']);
        $testimonials_location = tep_db_prepare_input($_POST['testimonials_location']);
        $html_text = tep_db_prepare_input($_POST['html_text']);

        $testimonials_error = false;
        if (empty($testimonials_title)) {
          $messageStack->add('testimonial', ERROR_TESTIMONIALS_TITLE_REQUIRED, 'error');
          $testimonials_error = true;
        }
        if (empty($testimonials_name)) {
          $messageStack->add('testimonial', ERROR_TESTIMONIALS_NAME_REQUIRED, 'error');
          $testimonials_error = true;
        }
        if (empty($html_text)) {
          $messageStack->add('testimonial', ERROR_TESTIMONIALS_DESCRIPTION_REQUIRED, 'error');
          $testimonials_error = true;
        }
        
        if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
            if (defined('MODULE_ADDONS_CTM_VVC_ON_OFF') && MODULE_ADDONS_CTM_VVC_ON_OFF == 'On') {
                $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . " where oscsid = '" . tep_session_id() . "'");
                $code_array = tep_db_fetch_array($code_query);
                tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'"); //remove the visual verify code associated with this session to clean database and ensure new results
                if ( isset($_POST['visual_verify_code']) && tep_not_null($_POST['visual_verify_code']) && 
                     isset($code_array['code']) &&  tep_not_null($code_array['code']) && 
                     strcmp($_POST['visual_verify_code'], $code_array['code']) == 0) {   //make the check case sensitive
                     //match is good, no message or error.
                } else {
                    $testimonials_error = true;
                    $messageStack->add('testimonial', VISUAL_VERIFY_CODE_ENTRY_ERROR);
                }
            }
        }

        if (!$testimonials_error) {
          $sql_data_array = array('testimonials_title' => $testimonials_title,
                                  'testimonials_name' => $testimonials_name,
                                  'testimonials_location' => $testimonials_location,
                                  'testimonials_html_text' => $html_text,
                                  'date_added' => 'now()',
                                  'status' => '0');

            tep_db_perform(TABLE_CUSTOMER_TESTIMONIALS, $sql_data_array);
            $testimonials_id = tep_db_insert_id();
            if (defined('MODULE_ADDONS_CTM_EMAIL_NOTIFICATION') && MODULE_ADDONS_CTM_EMAIL_NOTIFICATION == 'True') {
              $email_text = TESTIMONIAL_NOTIFICATION_TEXT . $testimonials_name . "\n" .
                            TESTIMONIAL_NOTIFICATION_TEXT_2 . "\n\n" .
                            TESTIMONIAL_NOTIFICATION_TITLE . $testimonials_title . "\n\n" .
                            TESTIMONIAL_NOTIFICATION_HTML_TEXT . $html_text . "\n\n" .
                            TESTIMONIAL_NOTIFICATION_APPROVE;
              tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, TESTIMONIAL_NOTIFICATION_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            }

          
          //$messageStack->add_session('testimonial', TEXT_TESTIMONIALS_SUCCESSFUL, 'success');
          $testimonials_id = '';
          $testimonials_title = '';
          $testimonials_name = '';
          $testimonials_location = '';
          $html_text = '';
          tep_redirect(tep_href_link(FILENAME_CUSTOMER_TESTIMONIALS,'action=success','NONSSL'));
        }
      }
    }

  

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CUSTOMER_TESTIMONIALS, '', 'NONSSL'));
  $content = CONTENT_CUSTOMER_TESTIMONIALS;
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>