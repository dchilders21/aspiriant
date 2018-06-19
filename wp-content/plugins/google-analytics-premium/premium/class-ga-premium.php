<?php

if ( ! class_exists( 'Yoast_GA_Premium' ) ) {

	class Yoast_GA_Premium {

		/**
		 * Store the GA options
		 *
		 * @var array
		 */
		public static $ga_options = array();

		/**
		 * Initialize the premium functionality.
		 *
		 * This method will call specific method for initialising the admin or frontend functionality.
		 *
		 * It will even add the hook for default options.
		 */
		public static function init() {
			self::$ga_options = Yoast_GA_Options::instance()->get_options();

			if ( is_admin() ) {
				self::init_admin();
			} else {
				self::init_frontend();
			}

			add_filter( 'yst_ga_default-ga-values', array( 'Yoast_GA_Premium', 'add_default_options' ), 10, 2 );
		}

		/**
		 * Initialize the admin
		 *
		 * Adding action for the advanced tab and include the custom definition classes
		 */
		protected static function init_admin() {
			add_action( 'yst_ga_advanced-tab', array( 'Yoast_GA_Premium', 'settings_advanced_tab' ) );

			add_action( 'init', array( 'Yoast_GA_Custom_Definitions', 'init_custom_definitions' ) );
			add_action( 'init', array( 'Yoast_GA_Custom_Dimensions', 'init_custom_dimensions' ) );

			new Yoast_Product_GA_Premium();
		}

		/**
		 * Initialize the frontend
		 *
		 * Adding hook for getting
		 */
		protected static function init_frontend() {
			add_action( 'yst_tracking', array( 'Yoast_GA_Adsense', 'add_tracking_actions' ) );

			add_action( 'plugins_loaded', array( 'Yoast_GA_Custom_Definitions', 'init_custom_definitions' ) );

			if ( self::$ga_options['enable_universal'] == 1 ) {
				require_once 'frontend/classes/class-ga-custom-definitions-universal.php';

				add_action( 'plugins_loaded', array( 'Yoast_GA_Custom_Definitions_Universal', 'init_custom_definitions_universal' ) );
			}
		}

		/**
		 * Getting the contents of advanced tab page.
		 */
		public static function settings_advanced_tab() {
			global $yoast_ga_admin;
			require_once 'admin/pages/advanced-tab.php';
		}

		/**
		 * Adding the vars to the options
		 *
		 * These options come from class-options, which is calling this action-hook. Specific premium options can be
		 * added within this method
		 *
		 * @param array  $options
		 * @param string $prefix
		 *
		 * @return array
		 */
		public static function add_default_options( $options, $prefix ) {
			$options[$prefix]['track_adsense']     = null;
			$options[$prefix]['custom_dimensions'] = array();
			$options[$prefix]['custom_metrics']    = array();

			return $options;
		}

	}

}