<?php
/*
  $Id: functions.php,v 1.0 2015/03/26 00:18:17 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require_once('includes/application_top.php');
  
  function get_top_categories() {
    global $languages_id;

    $cats = array();
    $cats_query = tep_db_query("select c.categories_id, 
                                       cd.categories_name 
                                  from " . TABLE_CATEGORIES . " c, 
                                       " . TABLE_CATEGORIES_DESCRIPTION . " cd 
                                 where c.categories_id = cd.categories_id 
                                   and c.parent_id = '0' 
                                   and cd.language_id = '" . (int)$languages_id . "';");
    while ($categories = tep_db_fetch_array($cats_query)) {
      $cats[] = array('id' => $categories['categories_id'],
                      'text' => $categories['categories_name']);
    }
    
    return $cats;
  }
  
  function get_custom_branding($show = '') {
    global $languages_id;
    
    $custom_brand_info = tep_db_fetch_array(tep_db_query("SELECT store_brand_telephone, 
                                                                 store_brand_fax, 
                                                                 store_brand_name  
                                                            FROM " . TABLE_BRANDING_DESCRIPTION . "
                                                           WHERE language_id = " . $languages_id . ";")); 
    switch($show){ 
      case 'phone':
        $custom_info = $custom_brand_info['store_brand_telephone'];
        break;
      case 'fax':
        $custom_info = $custom_brand_info['store_brand_fax'];
        break;
      case 'name':
        $custom_info = $custom_brand_info['store_brand_name'];
        break;
    }
    return $custom_info;                                            
  }   

  function cre_build_responsive_box_string() {
    global $level, $subvalue;
    
    $this_box_string = '';
    //$sub_indicator = (defined('CDS_TEXT_SUBS_INDICATOR')) ? CDS_TEXT_SUBS_INDICATOR : '';
    $sub_indicator = '<i class="fa fa-chevron-right margin-left-10 font-size-11"></i>';
    if ($subvalue[$level]['type'] == 'c') {
      $id = cre_get_cds_category_path($subvalue[$level]['ID']);
      if ($subvalue[$level]['url'] != '') {
        $separator = (strpos($subvalue[$level]['url'], '?')) ? '&amp;' : '?';
        $this_box_link = ($subvalue[$level]['append'] == true) ? $subvalue[$level]['url'] . $separator . 'CDpath=' . $id : $subvalue[$level]['url'];
        $this_box_target = ($subvalue[$level]['target'] != '') ? 'target="' . $subvalue[$level]['target'] . '"' : '';
      } else {
        $this_box_link = tep_href_link(FILENAME_PAGES, 'CDpath=' . $id);
        $this_box_target = '';
      }
    } else {
      $this_box_link = tep_href_link(FILENAME_PAGES, 'pID=' . $subvalue[$level]['ID'] . '&amp;CDpath=' . cre_get_cds_page_path($subvalue[$level]['ID']));
      $this_box_target = '';
    }  
    $this_box_string .= '<a href="' . $this_box_link . '"' . $this_box_target . '>';
    $this_box_string .= ($subvalue[$level]['selected'] == true) ? '<strong>' : '';
    $this_box_string .= $subvalue[$level]['name'];
    $this_box_string .= ($subvalue[$level]['selected'] == true) ? '</strong>' : '';
    $this_box_string .= ($subvalue[$level]['subs'] == true) ? $sub_indicator : '';
    $this_box_string .= '</a>';
    $this_box_string .= '<br class="margin-bottom-15">';
     
    return $this_box_string;
  }

  function cre_get_responsive_box_string() {
    global $languages_id, $level, $subvalue;
                                            
    $box_string = '';
    $this_id = 0;
    $level = 0;
    $spacer = '&nbsp;&nbsp;&nbsp;';
    //$sub_indicator = (defined('CDS_TEXT_SUBS_INDICATOR')) ? CDS_TEXT_SUBS_INDICATOR : '';
    $sub_indicator = '<i class="fa fa-chevron-right margin-left-10 font-size-11"></i>';
                                                                    
    // get the box array
    $box_array = cre_get_box_array($this_id);
    
    while (list($key, $value) = each($box_array)) {
      // level 0
      if ($value['type'] == 'c') {
        $id = cre_get_cds_category_path($value['ID']);
        if ($value['url'] != '') {
          $separator = (strpos($value['url'], '?')) ? '&amp;' : '?';
          $box_link = ($value['append'] == true) ? $value['url'] . $separator . 'CDpath=' . $id : $value['url'];
          $box_target = ($value['target'] != '') ? 'target="' . $value['target'] . '"' : '';
        } else {
          $box_link = tep_href_link(FILENAME_PAGES, 'CDpath=' . $id);
          $box_target = '';
        }
      } else {
        $box_link = tep_href_link(FILENAME_PAGES, 'pID=' . $value['ID'] . '&amp;CDpath=' . cre_get_cds_page_path($value['ID']));
        $box_target = '';
      }   
      $box_string .= '<a href="' . $box_link . '"' . $box_target . '>';
      $box_string .= ($value['selected'] == true) ? '<strong>' : '';
      $box_string .= $value['name'];
      $box_string .= ($value['selected'] == true) ? '</strong>' : '';
      $box_string .= ($value['subs'] == true) ? $sub_indicator : '';
      $box_string .= '</a>';
      $box_string .= '<br class="margin-bottom-15">';

      // level 1
      if ($value['selected'] == true) {
        $sub_box_array1 = cre_get_box_array($value['ID']);
        $level++;
        while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array1)) {
          $box_string .= str_repeat($spacer, 1) . cre_build_responsive_box_string();
          // level 2
          if ($subvalue[$level]['selected'] == true) {          
            $sub_box_array2 = cre_get_box_array($subvalue[$level]['ID']);
                      $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
            if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
              if (array_key_exists(($subkey[$level] + 1), $sub_box_array2) ) { continue; } else { break; }
            }
            $level++;
            while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array2)) {
              $box_string .= str_repeat($spacer, 2) . cre_build_responsive_box_string();
              // level 3
              if ($subvalue[$level]['selected'] == true) {                        
                $sub_box_array3 = cre_get_box_array($subvalue[$level]['ID']);
                        $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                  if (array_key_exists(($subkey[$level] + 1), $sub_box_array3) ) { continue; } else { break; }
                }                           
                $level++;
                while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array3)) {
                  $box_string .= str_repeat($spacer, 3) . cre_build_responsive_box_string();
                  // level 4
                  if ($subvalue[$level]['selected'] == true) {
                    $sub_box_array4 = cre_get_box_array($subvalue[$level]['ID']);
                        $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                    if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                      if (array_key_exists(($subkey[$level] + 1), $sub_box_array4) ) { continue; } else { break; }
                    }
                    $level++;
                    while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array4)) {
                      $box_string .= str_repeat($spacer, 4) . cre_build_responsive_box_string();           
                      // level 5
                      if ($subvalue[$level]['selected'] == true) {
                        $sub_box_array5 = cre_get_box_array($subvalue[$level]['ID']);
                                      $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                        if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                          if (array_key_exists(($subkey[$level] + 1), $sub_box_array5) ) { continue; } else { break; }
                        }
                        $level++;
                        while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array5)) {
                          $box_string .= str_repeat($spacer, 5) . cre_build_responsive_box_string();
                          // level 6
                          if ($subvalue[$level]['selected'] == true) {
                            $sub_box_array6 = cre_get_box_array($subvalue[$level]['ID']);
                            $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                            if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                              if (array_key_exists(($subkey[$level] + 1), $sub_box_array6) ) { continue; } else { break; }
                            }
                            $level++;
                            while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array6)) {
                              $box_string .= str_repeat($spacer, 6) . cre_build_responsive_box_string();   
                              // level 7
                              if ($subvalue[$level]['selected'] == true) {
                                $sub_box_array7 = cre_get_box_array($subvalue[$level]['ID']);
                                            $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                                if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                                  if (array_key_exists(($subkey[$level] + 1), $sub_box_array7) ) { continue; } else { break; }
                                }
                                $level++;
                                while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array7)) {
                                  $box_string .= str_repeat($spacer, 7) . cre_build_responsive_box_string();                                               
                                  // level 8
                                  if ($subvalue[$level]['selected'] == true) {
                                    $sub_box_array8 = cre_get_box_array($subvalue[$level]['ID']);
                                                $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                                    if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                                      if (array_key_exists(($subkey[$level] + 1), $sub_box_array8) ) { continue; } else { break; }
                                    }
                                    $level++;
                                    while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array8)) {
                                      $box_string .= str_repeat($spacer, 8) . cre_build_responsive_box_string();                                               
                                      // level 9
                                      if ($subvalue[$level]['selected'] == true) {
                                        $sub_box_array9 = cre_get_box_array($subvalue[$level]['ID']);
                                          $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                                        if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                                          if (array_key_exists(($subkey[$level] + 1), $sub_box_array9) ) { continue; } else { break; }
                                        }
                                        $level++;
                                        while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array9)) {
                                          $box_string .= str_repeat($spacer, 9) . cre_build_responsive_box_string();                                               
                                        } // end while9       
                                      }                                                                       
                                    } // end while8
                                  }                                                               
                                } // end while7
                              }                                                       
                            } // end while6       
                          }
                        } // end while5
                      }                                       
                    } // end while4
                  }                                           
                } // end while3
              }                         
            } // end while2   
          }
        } // end while1
      }       
    }
                                    
    return $box_string;
  }
  
  function left_col_check() {
    $left_column = tep_db_fetch_array(tep_db_query("SELECT infobox_id   
                                                      FROM " . TABLE_INFOBOX_CONFIGURATION . "
                                                     WHERE template_id = " . TEMPLATE_ID . "
                                                       and infobox_display = 'yes'
                                                       and display_in_column = 'left'"));
    return sizeof($left_column['infobox_id']);
  }
  
  function right_col_check() {
    $right_column = tep_db_fetch_array(tep_db_query("SELECT infobox_id   
                                                       FROM " . TABLE_INFOBOX_CONFIGURATION . "
                                                      WHERE template_id = " . TEMPLATE_ID . "
                                                        and infobox_display = 'yes'
                                                        and display_in_column = 'right'"));
    return sizeof($right_column['infobox_id']);
  } 
  
  class responsiveSplitPageResults {
    var $sql_query, $number_of_rows, $current_page_number, $number_of_pages, $number_of_rows_per_page, $page_name;

    /* class constructor */
    function responsiveSplitPageResults($query, $max_rows, $count_key = '*', $page_holder = 'page') {
      $this->sql_query = strtolower($query);  // force the quesry to all lower case
      $this->page_name = $page_holder;

      if (isset($_GET[$page_holder])) {
        $page = $_GET[$page_holder];
      } elseif (isset($_POST[$page_holder])) {
        $page = $_POST[$page_holder];
      } else {
        $page = '';
      }

      if (empty($page) || !is_numeric($page)) $page = 1;
      $this->current_page_number = $page;

      if ($max_rows <= 0){
        $max_rows = '1';
      }

      $this->number_of_rows_per_page = $max_rows;

      // SQL statements that have a "having" clause must be processed
      // as the full SQL statment.  Otherwise, a shorten version may be used.
      if (strpos($this->sql_query, ' having') === false) {
        $pos_to = strlen($this->sql_query);
        $pos_from = strpos($this->sql_query, ' from', 0);

        $pos_group_by = strpos($this->sql_query, ' group by', $pos_from);
        if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;

        $pos_order_by = strpos($this->sql_query, ' order by', $pos_from);
        if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

        if (strpos($this->sql_query, 'distinct') || strpos($this->sql_query, 'group by')) {
          $count_string = 'distinct ' . tep_db_input($count_key);
        } else {
          $count_string = tep_db_input($count_key);
        }

        $count_query = tep_db_query("select count(" . $count_string . ") as total " . substr($this->sql_query, $pos_from, ($pos_to - $pos_from)));
        $count = tep_db_fetch_array($count_query);

        $this->number_of_rows = $count['total'];
      } else {
        $count_query = tep_db_query($this->sql_query);
        $this->number_of_rows = tep_db_num_rows($count_query);
      }

      $this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);

      if ($this->current_page_number > $this->number_of_pages) {
        $this->current_page_number = $this->number_of_pages;
      }

      $offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));
      //newer version of mysql can not handle neg number in limit, temp fix
      if ($offset < '0'){
        $offset = '1';
      }
      $this->sql_query .= " limit " . $offset . ", " . $this->number_of_rows_per_page;
    }

    /* class functions */
    // display responsive-split-page-number-links
    function display_responsive_links($max_page_links, $parameters = '') {
      global $PHP_SELF, $request_type;

      $display_links_string = '';

      if ($max_page_links <= 0){
        $max_page_links = '1';
      }

      $class = '';
      // BOM Mod:allow for a call when there are no rows to be displayed
      if ($this->number_of_pages > 0) {

        if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';

        // previous button - not displayed on first page
        if ($this->current_page_number > 1) $display_links_string .= '<li><a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">&laquo;</a></li>';
        
        // check if number_of_pages > $max_page_links
        $cur_window_num = intval($this->current_page_number / $max_page_links);
        if ($this->current_page_number % $max_page_links) $cur_window_num++;

        $max_window_num = intval($this->number_of_pages / $max_page_links);
        if ($this->number_of_pages % $max_page_links) $max_window_num++;

        // previous window of pages
        if ($cur_window_num > 1) $display_links_string .= '<li><a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a></li>';

        // page nn button
        for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
          if ($jump_to_page == $this->current_page_number) {
            $display_links_string .= '<li><a class="disabled">' . $jump_to_page . '</a></li>';
          } else {
            $display_links_string .= '<li><a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a></li>';
          }
        }

        // next window of pages
        if ($cur_window_num < $max_window_num) $display_links_string .= '<li><a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a></li>';

        // next button
        if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '<li><a href="' . tep_href_link(basename($PHP_SELF), $parameters . 'page=' . ($this->current_page_number + 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' ">&raquo;</a></li>';

      } else { // if zero rows, then simply say that
        $display_links_string .= '<li>0</li>';
      }
      // EMO Mod
      return $display_links_string;
    }

    // display responsive number of total products found
    function display_responsive_count($text_output) {
      $to_num = ($this->number_of_rows_per_page * $this->current_page_number);
      if ($to_num > $this->number_of_rows) $to_num = $this->number_of_rows;

      $from_num = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

      if ($to_num == 0) {
        $from_num = 0;
      } else {
        $from_num++;
      }

      return sprintf($text_output, $from_num, $to_num, $this->number_of_rows);
    }
  }
?>