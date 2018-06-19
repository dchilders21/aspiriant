<?php

if ( ! class_exists( 'Yoast_GA_Custom_Dimensions' ) ) {

	class Yoast_GA_Custom_Dimensions extends Yoast_GA_Custom_Definitions {

		/**
		 * Holds the dimensions with the correct content before output
		 *
		 * @var array
		 */
		private static $dimensions = array();

		/**
		 * Init the custom dimensions class
		 */
		public static function init_custom_dimensions() {
			add_action( 'yst_ga_custom_dimensions_tab-content', array( 'Yoast_GA_Custom_Dimensions', 'hook_custom_dimensions_page' ) );

			wp_enqueue_script( 'custom_dimensions', GAWP_URL . '/premium/admin/js/custom_dimensions.min.js' );
		}

		/**
		 * Add the custom dimensions fields to the GA admin
		 */
		public static function hook_custom_dimensions_page() {
			global $yoast_ga_admin;

			// Getting the options again, because it was saved before
			$options = Yoast_GA_Options::instance()->get_options();

			$custom_dimensions       = isset( $options['custom_dimensions'] ) ? $options['custom_dimensions'] : array();
			$custom_dimensions_types = self::custom_dimensions_types();
			$custom_dimensions_usage = count( $custom_dimensions );
			$custom_dimensions_limit = count( $custom_dimensions_types );

			// Assign the Universal state to
			if ( $options['enable_universal'] == 1 ) {
				$universal = true;
			}
			else {
				$universal = false;
			}

			require_once 'admin/pages/custom-dimensions-page.php';
		}

		/**
		 * The current supported custom dimensions types (Key name is the matching name for the functions)
		 *
		 * @return array
		 */
		private static function custom_dimensions_types() {
			return array(
				'logged_in'    => 'Logged in',
				'post_type'    => 'Post type',
				'author'       => 'Author',
				'category'     => 'Category',
				'published_at' => 'Published at',
			);
		}

		/**
		 * Convert the current dimension into a value, by calling the correct function
		 */
		public static function set_dimensions() {
			foreach ( parent::$ga_options['custom_dimensions'] as $dimension ) {
				call_user_func( array( 'Yoast_GA_Custom_Dimensions', $dimension['type'] ), $dimension );
			}
		}

		/**
		 * Handle the logged in custom dimensions
		 *
		 * @param array $dimension
		 */
		public static function logged_in( $dimension ) {
			if ( is_user_logged_in() ) {
				$value = 'true';
			} else {
				$value = 'false';
			}

			self::add_dimension( $dimension, $value );
		}

		/**
		 * Handle the post type in custom dimensions
		 *
		 * @param array $dimension
		 */
		public static function post_type( $dimension ) {
			if ( is_singular() ) {
				$post_type = get_post_type( get_the_ID() );

				if ( $post_type ) {
					self::add_dimension( $dimension, $post_type );
				}
			}
		}

		/**
		 * Handle the author in custom dimensions
		 *
		 * @param array $dimension
		 */
		public static function author( $dimension ) {
			if ( is_singular() ) {
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
					}
				}

				$firstname = get_the_author_meta( 'user_firstname' );
				$lastname  = get_the_author_meta( 'user_lastname' );

				if ( ! empty( $firstname ) || ! empty( $lastname ) ) {
					$value = trim( $firstname . ' ' . $lastname );
				} else {
					$value = '';
				}

				self::add_dimension( $dimension, $value );
			}
		}

		/**
		 * Handle the category in custom dimensions
		 *
		 * @param array $dimension
		 */
		public static function category( $dimension ) {
			if ( is_singular() ) {
				$categories = get_the_category( get_the_ID() );

				if ( $categories ) {
					foreach ( $categories AS $category ) {
						$category_names[] = $category->slug;
					}

					self::add_dimension( $dimension, implode( ',', $category_names ) );
				}
			}
		}

		/**
		 * Handle the published at in custom dimensions
		 *
		 * @param array $dimension
		 */
		public static function published_at( $dimension ) {
			if( is_singular() ) {
				self::add_dimension( $dimension, get_the_date( 'c' ) );
			}
		}

		/**
		 * Return the custom dimensions
		 *
		 * @return array
		 */
		public static function get_dimensions() {
			return self::$dimensions;
		}

		/**
		 * Add a dimension (before output to Universal, not to save a new one)
		 *
		 * @param array  $dimension
		 * @param string $value
		 */
		private static function add_dimension( $dimension, $value ) {
			self::$dimensions[] = array(
				'type'  => $dimension['type'],
				'id'    => $dimension['id'],
				'value' => $value,
			);
		}

		/**
		 * Call this function to reset the dimensions (Good for testing purposes)
		 */
		public static function reset_dimensions() {
			self::$dimensions = array();
		}
	}

}