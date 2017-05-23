<?php
/**
 * Sets up the admin functionality for the plugin.
 *
 * @package    Cherry_Comments
 * @subpackage Admin
 * @author     Cherry Team
 * @license    GPL-3.0+
 * @copyright  2012-2016, Cherry Team
 */

// If class `Cherry_Comments_Admin` doesn't exists yet.
if ( ! class_exists( 'Cherry_Comments_Admin' ) ) {

	/**
	 * Cherry_Comments_Admin class.
	 */
	class Cherry_Comments_Admin {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance = null;

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Include libraries from the `includes/admin`
			add_action( 'init', array( $this, 'includes' ), 9999 );

			// Set default options
			add_action( 'admin_init', array( 'Cherry_Comments_Options_Manager', 'set_default_options_in_db' ) );

			// Load the admin menu.
			add_action( 'admin_menu', array( $this, 'menu' ) );

			// Load admin stylesheets.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			// Load admin JavaScripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Include libraries from the `includes/admin`.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function includes() {
			require_once( trailingslashit( CHERRY_COMMENTS_DIR ) . 'includes/admin/class-ajax-handlers.php' );
			// Include plugin pages.
			require_once( trailingslashit( CHERRY_COMMENTS_DIR ) . 'includes/admin/pages/class-plugin-options-page.php' );
		}

		/**
		 * Register the admin menu.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function menu() {
			add_submenu_page(
				'cherry-comments',
				esc_html__( 'Options Example', 'cherry-comments' ),
				esc_html__( 'Options Example', 'cherry-comments' ),
				'edit_theme_options',
				'cherry-comments-options-page',
				array( 'Cherry_Comments_Options_Page', 'get_instance' )
			);
		}

		/**
		 * Write default settings to database.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function set_default_options() {
			$cherry_comments_options = new Cherry_Comments_Options_Manager();
			$cherry_comments_options -> set_default_options_in_db();
		}
		/**
		 * Enqueue admin stylesheets.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string $hook The current admin page.
		 * @return void
		 */
		public function enqueue_styles( $hook ) {
			if ( Cherry_Comments_Admin::is_plugin_page() ) {
				wp_enqueue_style(
					'cherry-comments-admin',
					esc_url( CHERRY_COMMENTS_URI . 'assets/admin/css/min/cherry-comments-admin.min.css' ),
					array(), CHERRY_COMMENTS_VERSION,
					'all'
				);
			}
		}

		/**
		 * Enqueue admin JavaScripts.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string $hook The current admin page.
		 * @return void
		 */
		public function enqueue_scripts( $hook ) {
			if ( Cherry_Comments_Admin::is_plugin_page() ) {
				wp_enqueue_script(
					'cherry-comments-admin',
					esc_url( CHERRY_COMMENTS_URI . 'assets/admin/js/min/cherry-comments-admin.min.js' ),
					array( 'cherry-js-core' ),
					CHERRY_COMMENTS_VERSION,
					true
				);
			}
		}

		/**
		 * Check current plugin page.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return bool
		 */
		public static function is_plugin_page() {
			$screen = get_current_screen();

			return ( ! empty( $screen->base ) && false !== strpos( $screen->base, CHERRY_COMMENTS_SLUG ) ) ? true : false ;
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

if ( ! function_exists( 'cherry_comments_admin' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function cherry_comments_admin() {
		return Cherry_Comments_Admin::get_instance();
	}
}

cherry_comments_admin();
