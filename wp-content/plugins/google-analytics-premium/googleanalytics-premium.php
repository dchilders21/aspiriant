<?php
/*
Plugin Name: Google Analytics by Yoast Premium
Plugin URI: https://yoast.com/wordpress/plugins/google-analytics/#utm_source=wordpress&utm_medium=plugin&utm_campaign=wpgaplugin&utm_content=v504
Description: This plugin makes it simple to add Google Analytics to your WordPress blog, adding lots of features, eg. error page, search result and automatic clickout and download tracking.
Author: Team Yoast
Version: 1.0.2
Requires at least: 3.8
Author URI: https://yoast.com/
License: GPL v3
Text Domain: yoast-google-analytics-premium
Domain Path: /languages

Google Analytics for WordPress
Copyright (C) 2008-2014, Team Yoast - support@yoast.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// This plugin was originally based on Rich Boakes' Analytics plugin: http://boakes.org/analytics, but has since been rewritten and refactored multiple times.


define( 'GAWP_VERSION', '5.1.2' );

define( 'GAWP_FILE', __FILE__ );

define( 'GAWP_PATH', plugin_basename( __FILE__ ) );

define( 'GAWP_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );


if ( ! class_exists( 'Yoast_GA_Autoload', false ) ) {
	require_once 'includes/class-autoload.php';
}

if ( ! class_exists( 'Yoast_GA_Premium_Autoload', false ) ) {
	require_once 'premium/class-ga-autoload.php';
}

if ( ! class_exists( 'Yoast_GA_Premium', false ) ) {
	Yoast_GA_Premium::init();
}

// Only require the needed classes
if ( is_admin() ) {
	global $yoast_ga_admin;
	$yoast_ga_admin = new Yoast_GA_Admin;

} else {
	global $yoast_ga_frontend;
	$yoast_ga_frontend = new Yoast_GA_Frontend;
}
