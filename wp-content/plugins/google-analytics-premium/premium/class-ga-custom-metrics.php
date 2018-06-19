<?php

if ( ! class_exists( 'Yoast_GA_Custom_Metrics' ) ) {

	class Yoast_GA_Custom_Metrics extends Yoast_GA_Custom_Definitions {

		/**
		 * The Google Analytics limit for Custom Metrics
		 *
		 * @var int
		 */
		private static $custom_metrics_limit = 20;

		/**
		 * Init the custom metrics class
		 */
		public static function init_custom_metrics() {
			add_action( 'yst_ga_custom_tabs-tab', array( 'Yoast_GA_Custom_Metrics', 'hook_custom_metrics_tab' ) );
			add_action( 'yst_ga_custom_tabs-content', array( 'Yoast_GA_Custom_Metrics', 'hook_custom_metrics_page' ) );
		}

		/**
		 * Add the custom metrics fields to the GA admin
		 */
		public static function hook_custom_metrics_tab() {
			require_once 'admin/pages/custom-metrics-tab.php';
		}

		/**
		 * Add the custom metrics page to the GA admin
		 */
		public static function hook_custom_metrics_page() {
			global $yoast_ga_admin;
			require_once 'admin/pages/custom-metrics-page.php';
		}
	}

}