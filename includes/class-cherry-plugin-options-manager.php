<?php
/**
 * Cherry Plugin.
 *
 * @package    Cherry_Plugin_Options_Manager
 * @subpackage Admin
 * @author     Cherry Team
 * @license    GPL-3.0+
 * @copyright  2012-2016, Cherry Team
 */

// If class `Cherry_Plugin_Options_Manager` doesn't exists yet.
if ( ! class_exists( 'Cherry_Plugin_Options_Manager' ) ) {

	/**
	 * Cherry_Plugin_Options_Manager class.
	 */
	class Cherry_Plugin_Options_Manager{

		/**
		 * Plugin slug.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $slug = null;

		/**
		 * Plugin options.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $options = array();

		/**
		 * Plugin options default.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $options_default = array();

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct( $slug ) {
			$this->slug = $slug;
		}

		/**
		 * Set default plugin options.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return array
		 */
		public function set_default_options_in_db() {
			$options_default = $this->get_options( true );
			$db_options_default = get_option( $this->slug . '-default', array() );

			$diff_key = array_diff_key( $db_options_default, $options_default );

			if ( ! empty( $diff_key ) ){
				$this->save_options( $this->slug . '-default' , $options_default );
			}

			return $options_default;
		}

		/**
		 * Get plugin options.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array
		 */
		public function get_option( $options_id = '', $get_default = false, $default_value = null ) {
			$options = get_option( $this->slug, array() );

			if ( ! empty( $options ) && isset( $options[ $options_id ] ) ) {
				return $options[ $options_id ];
			} else if( null !== $default_value ) {
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
		 * Reset options option to default.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return array
		 */
		protected function reset_options() {
			$options_default = $this->set_default_options_in_db();

			if ( ! $this->options || ! empty( $options_default ) ) {
				$this->save_options( $this->slug, $options_default );
			}

			return $options_default;
		}
	}
}
