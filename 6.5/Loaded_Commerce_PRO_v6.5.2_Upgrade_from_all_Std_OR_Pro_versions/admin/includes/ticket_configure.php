<?php
/*
  $Id: ticket_configure.php,v 1.3 2003/04/25 21:37:11 hook Exp $

  OSC-SupportTicketSystem
  Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Settings:
  define ('TICKET_ENTRIES_MIN_LENGTH','5');  // Min Length of the Entries
  define ('TICKET_ADMIN_NAME','Ticket Admin');

// These settings only work properly if also set in catalog! If they are true in the catalog, they should be true here too!
  define('TICKET_USE_STATUS','true'); // Show status 
  define('TICKET_USE_DEPARTMENT','true');  // Use Department
  define('TICKET_USE_PRIORITY','true');  // Use Priority
  define('TICKET_USE_ORDER_IDS','true');  // Use OrderIDs 
  define('TICKET_USE_SUBJECT','true');  // Show Subject 

// Change the use of TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT per ticket via admin
// if you set this to true you can allow / notallow registered customers to view tickets without beeing logged in 
  define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT','true');

// Stylesheet used:
  define('TICKET_STYLESHEET','<style type="text/css">
<!--
TABLE.ticket {
  border-collapse:collapse;
  border-color: #bbc3d3;
  border-style: solid;
  border-width: 2px;
}
TD.ticketInfoBoxHeading {
  font-family: Verdana, Arial, sans-serif;
  font-size: 10px;
  font-weight: bold;
  background: #bbc3d3;
  color: #ffffff;
  border:2px #bbc3d3 solid; 
}
TD.ticketSmallText {
  font-family: Verdana, Arial, sans-serif;
  font-size: 10px;
  border:2px #bbc3d3 solid; 
}
TEXTAREA.ticket {
  width: 100%;
  font-family: Verdana, Arial, sans-serif;
  font-size: 11px;
}
-->
</style>'); 
?>