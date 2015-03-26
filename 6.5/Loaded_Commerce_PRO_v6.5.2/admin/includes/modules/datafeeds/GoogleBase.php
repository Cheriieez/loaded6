<?php
/*
  $Id: GoogleBase.php,v 1.0.0 2009/10/01 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  class GoogleBase {
    var $title, $code, $file;

    function GoogleBase() {
      $this->code = 'GoogleBase';
      $this->title = 'Google Base'; 
      $this->description = 'Google Base Feed Generator Class';
      $this->ftp_server = 'uploads.google.com';
    }

    function buildFeedHead($creFeed_store_description = ''){
        $content['xml'] = '<?xml version="1.0" encoding="UTF-8" ?>';
        $content['rss'] = '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
        $content['channel'] = '<channel>';
        $content['title'] = '<title>' . cre_stripInvalidXml(STORE_NAME, true) . '</title>';
        $content['link'] = '<link>' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . '</link>';
        $content['description'] = '<description>' . cre_stripInvalidXml($creFeed_store_description, true) . '</description>';
        $content['managingEditor'] = '<managingEditor>' . STORE_OWNER_EMAIL_ADDRESS . ' (' . cre_stripInvalidXml(STORE_NAME, true) . ')</managingEditor>';
        $content['generator'] = '<generator>' . PROJECT_VERSION . '</generator>' . "\n";
        
        return $content;
    }

    function buildFeedFoot(){
        $content["e_channel"] = '</channel>';
        $content["e_rss"] = '</rss>';
        
        return $content;
    }

    function buildFeedNodes($data, $lang_id){
        global $imageURL;
        $content['item'] = '<item>';   
        $content['title'] = ' <title>' . cre_stripInvalidXml(strlen($data['name']) > 70 ? substr($data['name'], 0, 70) : $data['name']) . '</title>';
        if($data['mfg_name'] != ''){
            $content['brand'] = ' <g:brand>' . cre_stripInvalidXml($data['mfg_name']) . '</g:brand>';
        }
         $content['condition'] = ' <g:condition>new</g:condition>';
         // Product types
         $cPath = tep_get_product_path((int)$data['id']);
         $cPath_array = explode('_', $cPath);
         $product_type = '';
         for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
             $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$lang_id . "'");
             if (tep_db_num_rows($categories_query) > 0) {
                 $categories = tep_db_fetch_array($categories_query);
                 $product_type .= cre_stripInvalidXml($categories['categories_name']) . ' &gt; ';
             } else {
                 break;
             }
         }
         $content['product_type'] = ' <g:product_type>' . substr($product_type,0,-6) . '</g:product_type>';
         if($data['weight'] != 0){
             $content['weight'] = ' <g:weight>' . $data['weight'] . '</g:weight>';
         }
         $content['id'] = ' <g:id>' . $data['id'] . cre_stripInvalidXml($data['model']) . '</g:id>';
         $image_name = str_replace($imageURL,'',$data['image_url']);
         if($image_name != ''){
             $content['image_url'] = ' <g:image_link>' . $data['image_url'] . '</g:image_link>';
         } else {
             $content['image_url'] = ' <g:image_link>' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'no_picture.gif</g:image_link>';
        }
         $content['link'] = ' <link>' . tep_feeder_href_link(FILENAME_PRODUCT_INFO,'cPath=' . tep_get_product_path($data['id']) . '&amp;products_id=' . $data['id'] . '&amp;language=' . $data['lang_id'],'NONSSL') . '</link>';
         $content['price'] = ' <g:price>' . $data['price'] . '</g:price>';
         $content['mpn'] =  ' <g:mpn>' . $data['id'] . (tep_not_null($data['model']) ? '-' . cre_stripInvalidXml($data['model']) : '')  . '</g:mpn>';
         
         //Acceptable values are: Cash, Check, Visa, MasterCard, AmericanExpress, Discover, and WireTransfer.
         // need automation to turn on / off
         $content['payment_accepted'] = ' <g:payment_accepted>Cash</g:payment_accepted>';
         $content['payment_accepted'] = ' <g:payment_accepted>Check</g:payment_accepted>';
         $content['payment_accepted'] = ' <g:payment_accepted>Visa</g:payment_accepted>';
         $content['payment_accepted'] = ' <g:payment_accepted>MasterCard</g:payment_accepted>';
         $content['payment_accepted'] = ' <g:payment_accepted>AmericanExpress</g:payment_accepted>';
         $content['payment_accepted'] = ' <g:payment_accepted>Discover</g:payment_accepted>';
         $content['payment_accepted'] = ' <g:payment_accepted>WireTransfer</g:payment_accepted>';
         $content['quentity'] = ' <g:quantity>' . $data['quantity'] . '</g:quantity>';
         $content['description'] = ' <description>' . cre_stripInvalidXml(cre_get_description($data['id'], $data['lang_id']), true) . '</description>';
         $content['e_item'] = '</item>' . "\n";
         
         return $content;
    }
}//class
?>