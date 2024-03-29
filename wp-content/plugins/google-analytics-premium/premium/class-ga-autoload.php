<?php

if ( ! class_exists( 'Yoast_GA_Premium_Autoload' ) ) {

	class Yoast_GA_Premium_Autoload {

		private static $classes = null;

		/**
		 * Setting the autoload
		 *
		 * If given $class is in array self::$class it will be included, if else there will be done nothing
		 *
		 * @param string $class
		 */
		public static function autoload( $class ) {

			$include_path = dirname( GAWP_FILE );

			if ( self::$classes === null ) {

				self::$classes = array(
					'yoast_ga_adsense'             => 'premium/class-ga-adsense',
					'yoast_ga_premium'             => 'premium/class-ga-premium',
					'yoast_ga_custom_definitions'  => 'premium/class-ga-custom-definitions',
					'yoast_ga_custom_dimensions'   => 'premium/class-ga-custom-dimensions',

					// License manager
					'yoast_product_ga_premium'     => 'premium/admin/class-product-ga-premium',
				);
			}

			$class_name = strtolower( $class );
			if ( isset( self::$classes[$class_name] ) ) {
				require_once( $include_path . '/' . self::$classes[$class_name] . '.php' );
			}
		}
	}

	// register class autoloader
	spl_autoload_register( array( 'Yoast_GA_Premium_Autoload', 'autoload' ) );

}

