<?php
defined( 'ABSPATH' ) OR exit;

/*
/--------------------------------------------------------------------\
|                                                                    |
| License: GLP Version 3                                             |
|                                                                    |
| Magic Liquidizer Responsive Table - Make HTML Table Responsive.    |
| Copyright (C) 2014, Elvin Deza,                                    |
| http://innovedesigns.com/                                          |
| All rights reserved.                                               |
|                                                                    |
| By using the software, you agree to be bound by the terms of		 | 		
| this license.														 |
| 																	 |
|                                                                    |
\--------------------------------------------------------------------/
*/
function liquidizer_table_uninstall() {
	if ( !current_user_can( 'activate_plugins' ) ) {
        return;
    }
	if ( !is_multisite() ) {
			delete_option('liquidizer_lite_wp_table');
			delete_option('liquidizer_lite_wp_which_table_element');
			delete_option('liquidizer_lite_wp_table_width');

	} else {
        	delete_site_option('liquidizer_lite_wp_table');
			delete_site_option('liquidizer_lite_wp_which_table_element');
			delete_site_option('liquidizer_lite_wp_table_width');
	}
}	
