<?php

if ( ! class_exists( 'Yoast_GA_Custom_Definitions_Universal' ) ) {

	class Yoast_GA_Custom_Definitions_Universal extends Yoast_GA_Custom_Definitions {

		/**
		 * Init the custom definitions universal frontend class
		 */
		public static function init_custom_definitions_universal() {
			if ( isset(parent::$ga_options['custom_dimensions']) && count( parent::$ga_options['custom_dimensions'] ) >= 1 ) {
				add_action( 'wp', array( 'Yoast_GA_Custom_Dimensions', 'set_dimensions' ) );
				add_action( 'wp', array( 'Yoast_GA_Custom_Definitions_Universal', 'output_custom_dimensions' ) );
			}
		}

		/**
		 * Output the custom dimensions, this function is hooked in WordPress
		 */
		public static function output_custom_dimensions() {
			add_action( 'yoast-ga-push-array-universal', array( 'Yoast_GA_Custom_Definitions_Universal', 'output_custom_definitions' ) );
		}

		/**
		 * Output the custom dimensions in the Universal tracking code
		 *
		 * @link https://developers.google.com/analytics/devguides/collection/analyticsjs/custom-dims-mets
		 *
		 * @param $gaq_push
		 *
		 * @return array
		 */
		public static function output_custom_definitions( $gaq_push ) {
			$last_key = array_pop( $gaq_push );

			if ( count( Yoast_GA_Custom_Dimensions::get_dimensions() ) >= 1 ) {
				foreach ( Yoast_GA_Custom_Dimensions::get_dimensions() as $dimension ) {
					$gaq_push[] = "'set', 'dimension" . $dimension['id'] . "', '" . $dimension['value'] . "'";
				}
			}

			$gaq_push[] = $last_key;

			return $gaq_push;
		}

	}

}