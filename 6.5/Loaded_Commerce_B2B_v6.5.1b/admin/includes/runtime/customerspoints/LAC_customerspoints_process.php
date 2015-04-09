<?php

global $action;

    switch ($action) {
	  case 'login':
		if ( $customers_id = intval($_GET['cID']) )
		{
			$check_customer = tep_db_fetch_array(tep_db_query('SELECT c.customers_id, c.customers_firstname, c.customers_email_address, c.customers_default_address_id, c.customers_group_id,
					a.entry_country_id, a.entry_zone_id,
					cg.customers_group_show_tax, cg.customers_group_tax_exempt, cg.group_hide_show_prices, cg.allow_add_to_cart
				FROM ' . TABLE_CUSTOMERS . ' c
					LEFT JOIN ' . TABLE_ADDRESS_BOOK . ' a ON (a.customers_id = c.customers_id AND a.address_book_id = c.customers_default_address_id)
					LEFT JOIN ' . TABLE_CUSTOMERS_GROUPS . ' cg ON (cg.customers_group_id = c.customers_group_id)
				WHERE c.customers_id = ' . $customers_id . ''));

			if ( $check_customer )
			{
				tep_session_name('osCsid');

				$_SESSION['customer_id'] = $check_customer['customers_id'];
			  
				$_SESSION['customer_default_address_id'] = $check_customer['customers_default_address_id'];
				$_SESSION['customer_first_name'] = $check_customer['customers_firstname'];

				$_SESSION['customer_country_id'] = intval($check_customer['entry_country_id']);
				$_SESSION['customer_zone_id'] = intval($check_customer['entry_zone_id']);

				$_SESSION['sppc_customer_group_id'] = $check_customer['customers_group_id'];

				$_SESSION['sppc_customer_group_show_tax'] = intval($check_customer['customers_group_show_tax']);
				$_SESSION['sppc_customer_group_tax_exempt'] = intval($check_customer['customers_group_tax_exempt']);
				$_SESSION['group_hide_show_prices'] = intval($check_customer['group_hide_show_prices']);
				$_SESSION['allow_add_to_cart'] = intval($check_customer['allow_add_to_cart']);

				$_SESSION['logged_by_admin'] = 1;

				header('Location: ' . DIR_WS_HTTP_CATALOG . 'account.php' . '?' . tep_session_name() . '=' . tep_session_id()); exit;
			}
		}
		break;
	}

?>