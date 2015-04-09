<style type="text/css">
                                		@import "includes/scr.css";
                                </style>
                                <script type="text/javascript" src="includes/domtab.js"></script>
                                <script type="text/javascript">
		                                document.write('<style type="text/css">');    
		                                document.write('div.domtab div{display: ;}<');
		                                document.write('/s'+'tyle>');    
                                </script>
                                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                                  <tr align="left">
                                    <td>
                                      <div <?php echo 'id="mainnavtabbed' . $i . '"'; ?> class="domtab">
                                        <ul class="domtabs">
                                          <li class="domtabsFrist"><a href="#t1<?php echo $i; ?>"><?php echo tep_not_null( TEXT_PRODUCTS_TAB_DESCRIPTION ) ? TEXT_PRODUCTS_TAB_DESCRIPTION : TEXT_PRODUCTS_TAB_DESCRIPTION_DEFAULT; ?></a></li>
                                          <li class="domtabsOther"><a href="#t2<?php echo $i; ?>"><?php echo tep_not_null(TEXT_PRODUCTS_TAB_2_TITLE ) ? TEXT_PRODUCTS_TAB_2_TITLE : ' &nbsp; '; ?></a></li>
                                          <li class="domtabsOther"><a href="#t3<?php echo $i; ?>"><?php echo tep_not_null(TEXT_PRODUCTS_TAB_3_TITLE ) ? TEXT_PRODUCTS_TAB_3_TITLE : ' &nbsp; '; ?></a></li>
                                          <li class="domtabsOther"><a href="#t4<?php echo $i; ?>"><?php echo tep_not_null(TEXT_PRODUCTS_TAB_4_TITLE ) ? TEXT_PRODUCTS_TAB_4_TITLE : ' &nbsp; '; ?></a></li>
                                        </ul>
                                      </div>
                                      <!-- Tab Description -->
                                      <div class="tabcontent">
                                        <a name="t1<?php echo $i; ?>" id="t1<?php echo $i; ?>"></a>
                                        <table border="0" cellspacing="0" cellpadding="0" width="100%" summary="description tabe">
                                          <tr align="left">
                                            <td>
                                            <?php 
                                              echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . ' ' . TEXT_PRODUCTS_TAB_DESCRIPTION; 
                                            ?>
                                            </td>
                                          </tr>
                                          <tr align="left">
                                            <td valign="top" class="main"><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <!-- Tab 2 -->
                                      <div class="tabcontent">
                                        <a name="t2<?php echo $i; ?>" id="t2<?php echo $i; ?>"></a>
                                        <table border="0" cellspacing="0" cellpadding="0" width="100%" summary="description tabe">
                                          <tr align="left">
                                            <td>
                                            <?php 
                                              echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . ' ' . TEXT_PRODUCTS_TAB_2_TITLE; 
                                            ?>
                                            </td>
                                          </tr>
                                          <tr align="left"> 
                                            <td valign="top" class="main"><?php echo tep_draw_textarea_field('products_tab_2[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_tab_2[$languages[$i]['id']]) ? $products_tab_2[$languages[$i]['id']] : tep_get_products_tab_2($pInfo->products_id, $languages[$i]['id']))); ?></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <!-- Tab 3 -->
                                      <div class="tabcontent">
                                        <a name="t3<?php echo $i; ?>" id="t3<?php echo $i; ?>"></a>
                                        <table border="0" cellspacing="0" cellpadding="0" width="100%" summary="description tabe">
                                          <tr align="left">
                                            <td>
                                            <?php 
                                              echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . ' ' . TEXT_PRODUCTS_TAB_3_TITLE; 
                                            ?>
                                            </td>
                                          </tr>
                                          <tr align="left">
                                            <td valign="top" class="main"><?php echo tep_draw_textarea_field('products_tab_3[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_tab_3[$languages[$i]['id']]) ? $products_tab_3[$languages[$i]['id']] : tep_get_products_tab_3($pInfo->products_id, $languages[$i]['id']))); ?></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <!-- Tab 4 -->
                                      <div class="tabcontent">
                                        <a name="t4<?php echo $i; ?>" id="t4<?php echo $i; ?>"></a>
                                        <table border="0" cellspacing="0" cellpadding="0" width="100%" summary="description tabe">
                                          <tr align="left">
                                            <td>
                                            <?php 
                                              echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . ' ' . TEXT_PRODUCTS_TAB_4_TITLE; 
                                            ?>
                                            </td>
                                          </tr>
                                          <tr align="left">
                                            <td valign="top" class="main"><?php echo tep_draw_textarea_field('products_tab_4[' . $languages[$i]['id'] . ']', 'soft', '120', '15', (isset($products_tab_4[$languages[$i]['id']]) ? $products_tab_4[$languages[$i]['id']] : tep_get_products_tab_4($pInfo->products_id, $languages[$i]['id']))); ?></td>
                                          </tr>
                                        </table>
                                      </div>
                                    </td>
                                  </tr>
                                </table>