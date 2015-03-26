<?php
/*
  $Id: quick_updates.php 2006/09/14 13:55:34 maestro Exp $

  Based on the original script contributed by Burt (burt@xwww.co.uk)
        and by Henri Bredehoeft (hrb@nermica.net)

  CRE Contributions, Low Cost, High Quality oscommerce contributions ported to CRE v6.2 by maestro
  CRE Loaded   http://crecontributions.com

  Released under the GNU General Public License
*/

define('WARNING_MESSAGE','Update your changes <b>BEFORE</b> changing the listing display (for example: sorting, changing page number, selecting categories or manufacturers)');
define('TOP_BAR_TITLE', 'Quick Product Updater');
define('HEADING_TITLE', 'Quick Product Updater');
define('TEXT_MARGE_INFO','Modify by commercial margin, if you check this box, your products will be stroked at the rates given. To stroke to 25%, means that the price is marked up of 33%.');
define('TEXT_PRODUCTS_UPDATED', 'Item(s)&nbsp;Updated!');
define('TEXT_IMAGE_PREVIEW','Preview&nbsp;Item');
define('TEXT_IMAGE_SWITCH_EDIT','Switch&nbsp;to&nbsp;Complete&nbsp;Edit');
define('TEXT_QTY_UPDATED', 'Value(s)&nbsp;changed');
define('TEXT_INPUT_SPEC_PRICE','<b>(+/-) Value or rate :</b>');
define('TEXT_SPEC_PRICE_INFO1','ie : 10, 15%, -20, -25%');
define('TEXT_SPEC_PRICE_INFO2','<b>Note: </b>Uncheck prices you don\'t want to update.');
define('TEXT_MAXI_ROW_BY_PAGE', 'Maximum&nbsp;lines&nbsp;per&nbsp;page');
define('TEXT_SPECIALS_PRODUCTS', 'Special&nbsp;Price!');
define('TEXT_ALL_MANUFACTURERS', 'All&nbsp;Manufacturers');
define('TEXT_ASCENDINGLY', 'Ascendingly');
define('TEXT_DESCENDINGLY', 'Descendingly');
define('TEXT_SORT_ALL', 'Sort ');
define('NO_TAX_TEXT','- N/A -');
define('NO_MANUFACTURER','- N/A -');
define('TABLE_HEADING_CATEGORIES', 'Category');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Name');
define('TABLE_HEADING_SORTIERUNG', 'Sort&nbsp;Order');
define('TABLE_HEADING_PRICE', 'Price');
define('TABLE_HEADING_COST', 'Item&nbsp;Cost');
define('TABLE_HEADING_RETAIL_PRICE', 'MSRP');
define('TABLE_HEADING_TAX', 'Tax&nbsp;Status');
define('TABLE_HEADING_WEIGHT', 'Weight');
define('TABLE_HEADING_QUANTITY', 'Quantity');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_MANUFACTURERS', 'Manufacturers');
define('DISPLAY_CATEGORIES', 'Select&nbsp;Category:');
define('DISPLAY_MANUFACTURERS', 'Select&nbsp;manufacturer');
define('PRINT_TEXT', 'Print&nbsp;This&nbsp;Page');
define('TOTAL_COST', 'TTC');
define('TEXT_EDIT', 'Edit');
define('DISPLAY_CUSTOMERS_GROUPS', 'Select Customer Group');


// mod for sppc and qty price breaks
define('TEXT_PRODUCTS_PRICE_GRP', 'Quantity Price Breaks');
define('TEXT_PRODUCTS_PRICE', 'Retail Price:');
define('TEXT_PRODUCTS_GROUPS', 'Groups:');
define('TEXT_PRODUCTS_BASE', 'Base');
define('TEXT_PRODUCTS_ABOVE', 'Above');
define('TEXT_PRODUCTS_QTY', 'Qty');
define('TEXT_PRODUCTS_QTY_BLOCKS', 'Quantity Blocks:');
define('TEXT_PRODUCTS_QTY_BLOCKS_INFO', '(can only order in blocks of X quantity)');
define('TEXT_PRODUCTS_SPPP_NOTE', 'Note that if a field is filled, but the checkbox is unchecked no price will be inserted.<br>If a price is already inserted in the database, but the checkbox unchecked it will be removed from the database.');
define('TEXT_PRODUCTS_QTY_DISCOUNT', '10');
define('TEXT_PRODUCTS_FILTER', 'Filter');
?>