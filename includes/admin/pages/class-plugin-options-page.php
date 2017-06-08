<?php
/**
 * Sets up the plugin option page.
 *
 * @package    Cherry_Comments
 * @subpackage Admin
 * @author     Cherry Team
 * @license    GPL-3.0+
 * @copyright  2012-2016, Cherry Team
 */

// If class `Cherry_Comments_Options_Page` doesn't exists yet.
if ( ! class_exists( 'Cherry_Comments_Options_Page' ) ) {

	/**
	 * Cherry_Comments_Options_Page class.
	 */
	class Cherry_Comments_Options_Page extends Cherry_Comments_Options {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance = null;

		/**
		 * Instance of the class Cherry_Interface_Builder.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private $builder = null;

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->builder = cherry_comments()->get_core()->modules['cherry-interface-builder'];

			parent::__construct();
			$this->render_page();
		}

		/**
		 * Render plugin options page.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function render_page() {
			$this->builder->register_form( $this->form );

			$this->builder->register_section( $this->section );

			$this->builder->register_component( $this->component_tab );

			$this->builder->register_settings( $this->tabs );

			//$this->builder->register_html( $this->info );

			$this->builder->register_control( $this->options );

			$this->builder->register_control( $this->buttons );

			$this->builder->render();
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}
}
