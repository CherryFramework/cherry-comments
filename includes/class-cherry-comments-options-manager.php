<?php
/**
 * Cherry Comments.
 *
 * @package    Cherry_Comments_Options_Manager
 * @subpackage Admin
 * @author     Cherry Team
 * @license    GPL-3.0+
 * @copyright  2012-2016, Cherry Team
 */

// If class `Cherry_Comments_Options_Manager` doesn't exists yet.
if ( ! class_exists( 'Cherry_Comments_Options_Manager' ) ) {

	/**
	 * Cherry_Comments_Options_Manager class.
	 */
	class Cherry_Comments_Options_Manager extends Cherry_Comments_Options {

		/**
		 * Plugin options.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $options = null;

		/**
		 * Plugin options default.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $options_default = null;

		/**
		 * Set plugin options.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function set_options() {
			if ( ! $this->options || empty( $this->options ) ) {
				$this->options_default = get_option( CHERRY_SEARCH_SLUG . '-default', false );
				$this->options         = get_option( CHERRY_SEARCH_SLUG, false );
				$this->options         = wp_parse_args( $this->options, $this->options_default );
			}
		}

		/**
		 * Get plugin options.
		 *
		 * @since 1.0.0
		 * @access private
		 * @return array
		 */
		private function get_options( $options_id, $default_value = null ) {
			$options = get_option( CHERRY_COMMENTS_SLUG, false );

			if ( $options && isset( $options[ $options_id ] ) ) {
				return $options[ $options_id ];
			} else if( $default_value ) {
				return $default_value;
			} else {
				return;
			}
		}

		/**
		 * Save plugin options.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		protected function save_options( $key = false, $data = array() ) {
			if ( ! empty( $data ) && is_array( $data ) && $key ) {
				update_option( $key, $data );
			}
		}

		/**
		 * Set default plugin options.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return array
		 */
		public function set_default_options_in_db() {
			$options_default    = $this->options_default;
			$db_options_default = get_option( CHERRY_COMMENTS_SLUG . '-default', array() );

			$result_array = array_diff_key( $options_default, $db_options_default );
			$reverse_result_array = array_diff_key( $db_options_default, $options_default );

			if ( ! empty( $result_array ) || ! empty( $reverse_result_array ) ) {
				$this->save_options( CHERRY_COMMENTS_SLUG . '-default' , $options_default );
				return $options_default;
			} else {
				return $db_options_default;
			}
		}

		/**
		 * Reset options option to default.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return array
		 */
		protected function reset_options() {
			$options_default = $this->set_default_options();

			if ( ! empty( $options_default ) ) {
				$this->save_options( CHERRY_COMMENTS_SLUG, $options_default );
			}

			return $options_default;
		}
	}
}
