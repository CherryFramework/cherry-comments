<?php
/**
 * Plugin options.
 *
 * @package    Cherry_Comments
 * @subpackage Admin
 * @author     Cherry Team
 * @license    GPL-3.0+
 * @copyright  2012-2016, Cherry Team
 */

// If class `Cherry_Comments_Options` doesn't exists yet.
if ( ! class_exists( 'Cherry_Comments_Options' ) ) {

	/**
	 * Cherry_Comments_Options class.
	 */
	class Cherry_Comments_Options extends Cherry_Plugin_Options_Manager {

		/**
		 * Form on options page.
		 *
		 * @since 1.0.0
		 * @var array
		 * @access protected
		 */
		protected $form = null;

		/**
		 * Section on options page.
		 *
		 * @since 1.0.0
		 * @var array
		 * @access public
		 */
		public $section = null;

		/**
		 * Tab component on options page.
		 *
		 * @since 1.0.0
		 * @var array
		 * @access public
		 */
		public $component_tab = null;

		/**
		 * Tabs on options page.
		 *
		 * @since 1.0.0
		 * @var array
		 * @access public
		 */
		public $tabs = null;

		/**
		 * Info section on options page.
		 *
		 * @since 1.0.0
		 * @var array
		 * @access public
		 */
		public $info = null;

		/**
		 * Submit buttons on options page.
		 *
		 * @since 1.0.0
		 * @var array
		 * @access protected
		 */
		protected $buttons = null;

		/**
		 * Instance of the class Cherry_Utility.
		 *
		 * @since 1.0.0
		 * @var object
		 * @access private
		 */
		private $utility = null;

		/**
		 * HTML spinner.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access private
		 */
		private $spinner = '<span class="loader-wrapper"><span class="loader"></span></span>';

		/**
		 * Dashicons.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access private
		 */
		private $button_icon = '<span class="dashicons dashicons-yes icon"></span>';

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var object
		 * @access private
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
			cherry_comments()->get_core()->init_module( 'cherry-utility', array() );
			$this->utility = cherry_comments()->get_core()->modules['cherry-utility']->utility;

			parent::__construct( CHERRY_COMMENTS_SLUG );
			$this->set_component();

			add_action( 'admin_init', array( $this, 'set_default_options_in_db' ) );
		}


		/**
		 * Function has plugin options.
		 *
		 * @since 1.0.0
		 * @access private
		 * @return void
		 */
		private function set_component( $get_default = false ) {
			$this->form = array(
				'chery-search-options-form' => array(),
			);

			$this->section = array(
				'cherry-comments-section' => array(
					'type'          => 'section',
					'parent'        => 'chery-search-options-form',
					'title'         => '<span class="dashicons dashicons-search"></span>' . esc_html__( 'Cherry Search Settings', 'cherry-comments' ),
				),
			);

			$this->component_tab = array(
				'cherry-comments-tab'   => array(
					'type'           => 'component-tab-vertical',
					'parent'         => 'cherry-comments-section',
				),
			);

			$this->tabs = array(
				'main'            => array(
					'type'   => 'options',
					'parent' => 'cherry-comments-tab',
					'scroll' => true,
					'title'  => esc_html__( 'Main options', 'cherry-comments' ),
				),
				'query_options'  => array(
					'type'   => 'options',
					'parent' => 'cherry-comments-tab',
					'scroll' => true,
					'title'  => esc_html__( 'Search results options', 'cherry-comments' ),
				),
				'visual_options' => array(
					'type'   => 'options',
					'parent' => 'cherry-comments-tab',
					'scroll' => true,
					'title'  => esc_html__( 'Visual options', 'cherry-comments' ),
				),
				'notices' => array(
					'type'   => 'options',
					'parent' => 'cherry-comments-tab',
					'scroll' => true,
					'title'  => esc_html__( 'Notifications', 'cherry-comments' ),
				),
				'submite_buttons' => array(
					'type'   => 'options',
					'parent' => 'cherry-comments-section',
				),
			);

			$this->info = array(
				'form_html'  => array(
					'type'       => 'html',
					'parent'     => 'main',
					'class'      => 'cherry-control info-block',
					'html'       => sprintf(
						'<p>%1$s</p><ol><li>%2$s</li><li>%3$s</li><li>%4$s</li></ol>',
						esc_html__( 'In case you need to add Cherry Search on your website, you can do it in several ways:', 'cherry-comments' ),
						esc_html__( 'Enable a "Replace the standard search" option', 'cherry-comments' ),
						esc_html__( 'Add Cherry Search using this shortcode', 'cherry-comments' ) . ' <code class ="cherry-code-example">' . htmlspecialchars( '[cherry_comments_form]' ) . '</code>',
						esc_html__( 'Add PHP code to the necessaryfiles of your theme:', 'cherry-comments' ) . '<code class ="cherry-code-example">' . htmlspecialchars( '<?php if ( function_exists( \'cherry_get_search_form\' ) ) { cherry_get_search_form(); } ?>' ) . '</code>'
					),
				),
			);

			$this->options = $this->get_options();

			$this->buttons = array(
// Submite buttons
				'cherry-reset-buttons'  => array(
					'type'          => 'button',
					'parent'        => 'submite_buttons',
					'content'       => '<span class="text">' . esc_html__( 'Reset', 'cherry-comments' ) . '</span>' . $this->spinner . $this->button_icon,
					'view_wrapping' => false,
					'form'          => 'chery-search-options-form',
				),
				'cherry-save-buttons'  => array(
					'type'          => 'button',
					'parent'        => 'submite_buttons',
					'style'         => 'success',
					'content'       => '<span class="text">' . esc_html__( 'Save', 'cherry-comments' ) . '</span>' . $this->spinner . $this->button_icon,
					'view_wrapping' => false,
					'form'          => 'chery-search-options-form',
				),
			);
		}

		/**
		 * Function set plugin options.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		protected function get_options( $get_default = false ) {
			return array(
// Main Settings
				'change_standard_search' => array(
					'type'         => 'switcher',
					'parent'       => 'main',
					'title'        => esc_html__( 'Replace the standard search form.', 'cherry-comments' ),
					'description'  => esc_html__( 'This option allows to replace all the standard search forms on your website.', 'cherry-comments' ),
					'value'        => $this->get_option( 'change_standard_search', $get_default, true ),
					'toggle'       => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
					),
				),
				'search_button_icon' => array(
					'type'        => 'iconpicker',
					'parent'      => 'main',
					'title'       => esc_html__( 'Search Button Icon.', 'cherry-comments' ),
					'description' => esc_html__( 'This option sets search button text.', 'cherry-comments' ),
					'value'       => $this->get_option( 'search_button_icon', $get_default, '' ),
					'auto_parse'  => true,
					'icon_data'   => apply_filters( 'cherry_comments_button_icon', $this->get_icons_set() ),
				),
				'search_button_text' => array(
					'type'        => 'text',
					'parent'      => 'main',
					'title'       => esc_html__( 'Search Button Text.', 'cherry-comments' ),
					'description' => esc_html__( 'This option sets search button text.', 'cherry-comments' ),
					'value'       => $this->get_option( 'search_button_text', $get_default, '' ),
				),
				'search_placeholder_text' => array(
					'type'        => 'text',
					'parent'      => 'main',
					'title'       => esc_html__( 'Caption / Placeholder text.', 'cherry-comments' ),
					'description' => esc_html__( 'This option sets placeholder text in input field.', 'cherry-comments' ),
					'value'       => $this->get_option( 'search_placeholder_text', $get_default, esc_html__( 'Search', 'cherry-comments' ) ),
				),

// Search Query Settings
				'search_source' => array(
					'type'        => 'select',
					'parent'      => 'query_options',
					'title'       => esc_html__( 'Search in.', 'cherry-comments' ),
					'description' => esc_html__( 'You can select particular search areas. If nothing is selected in the option, search will be made over the entire site.', 'cherry-comments' ),
					'multiple'    => true,
					'filter'      => true,
					'value'       => $this->get_option( 'search_source', $get_default, array( 'any' ) ),
					'options'     => $this->get_search_source(),
					'placeholder' => esc_html__( 'Selected search source.', 'cherry-comments' ),
				),
				'exclude_source_category' => array(
					'type'        => 'select',
					'parent'      => 'query_options',
					'title'       => esc_html__( 'Exclude categories from search results.', 'cherry-comments' ),
					'description' => esc_html__( 'This option allows to set categories in which search will not be made.', 'cherry-comments' ),
					'multiple'    => true,
					'filter'      => true,
					'value'       => $this->get_option( 'exclude_source_category', $get_default, 'projects' ),
					'options'     => $this->utility->satellite->get_terms_array( $this->get_categories() ),
					'placeholder' => esc_html__( 'Not selected categories.', 'cherry-comments' ),
				),
				'exclude_source_tags' => array(
					'type'        => 'select',
					'parent'      => 'query_options',
					'title'       => esc_html__( 'Exclude tags from search results.', 'cherry-comments' ),
					'description' => esc_html__( 'This option allows to set tags in which search will not be made.', 'cherry-comments' ),
					'multiple'    => true,
					'filter'      => true,
					'value'       => $this->get_option( 'exclude_source_tags', $get_default, '' ),
					'options'     => $this->utility->satellite->get_terms_array( $this->get_tags() ),
					'placeholder' => esc_html__( 'Not selected tags.', 'cherry-comments' ),
				),
				'exclude_source_post_format' => array(
					'type'        => 'select',
					'parent'      => 'query_options',
					'title'       => esc_html__( 'Exclude post types from search results.', 'cherry-comments' ),
					'description' => esc_html__( 'This option allows to post types in which search will not be made.', 'cherry-comments' ),
					'multiple'    => true,
					'filter'      => true,
					'value'       => $this->get_option( 'exclude_source_post_format', $get_default, '' ),
					'options'     => $this->utility->satellite->get_terms_array( 'post_format' ),
					'placeholder' => esc_html__( 'Not selected post formats.', 'cherry-comments' ),
				),
				'limit_query' => array(
					'type'        => 'stepper',
					'parent'      => 'query_options',
					'title'       => esc_html__( 'Number of results displayed in one search query.', 'cherry-comments' ),
					'description' => esc_html__( 'This option will allow you to limit the number of displayed search results. If the overall number of results will exceeed the previously set limit, the "load more" button will come up..', 'cherry-comments' ),
					'value'       => $this->get_option( 'limit_query', $get_default, 5 ),
					'max_value'   => 50,
					'min_value'   => 0,
					'step_value'  => 1,
				),
				'results_order_by' => array(
					'type'    => 'radio',
					'parent'  => 'query_options',
					'title'   => esc_html__( 'Sort search results by:', 'cherry-comments' ),
					'value'   => $this->get_option( 'results_order_by', $get_default, 'date' ),
					'options' => array(
						'date'          => array(
							'label' => esc_html__( 'Date', 'cherry-comments' ),
						),
						'title'         => array(
							'label' => esc_html__( 'Title', 'cherry-comments' ),
						),
						'autohr'        => array(
							'label' => esc_html__( 'Author', 'cherry-comments' ),
						),
						'modified'      => array(
							'label' => esc_html__( 'Last modified', 'cherry-comments' ),
						),
						'comment_count' => array(
							'label' => esc_html__( 'Number of Comments (descending)', 'cherry-comments' ),
						),
					),
				),
				'results_order'           => array(
					'type'    => 'radio',
					'parent'  => 'query_options',
					'title'   => esc_html__( 'Filter results by: ', 'cherry-comments' ),
					'value'   => $this->get_option( 'results_order', $get_default, 'asc' ),
					'options' => array(
						'asc'  => array(
							'label' => esc_html__( 'Asc', 'cherry-comments' ),
						),
						'desc' => array(
							'label' => esc_html__( 'Desc', 'cherry-comments' ),
						),
					),
				),

// Visual Tuning
				'title_visible' => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Show post titles.', 'cherry-comments' ),
					'value'  => $this->get_option( 'title_visible', $get_default, true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
					),
				),
				'limit_content_word' => array(
					'type'       => 'stepper',
					'parent'     => 'visual_options',
					'title'      => esc_html__( 'Post word count.', 'cherry-comments' ),
					'value'      => $this->get_option( 'limit_content_word', $get_default, apply_filters( 'cherry_comments_limit_content_word', 50 ) ),
					'max_value'  => 150,
					'min_value'  => 0,
					'step_value' => 1,
				),
				'author_visible' => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Show post authors.', 'cherry-comments' ),
					'value'  => $this->get_option( 'author_visible', $get_default, true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
						'true_slave'   => 'author_prefix',
					),
				),
				'author_prefix' => array(
					'type'   => 'text',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Prefix before author`s name.', 'cherry-comments' ),
					'value'  => $this->get_option( 'author_prefix', $get_default, esc_html__( 'Posted by:', 'cherry-comments' ) ),
					'master' => 'author_visible',
				),
				'thumbnail_visible' => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Show post thumbnails.', 'cherry-comments' ),
					'value'  => $this->get_option( 'thumbnail_visible', $get_default, true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
					),
				),
				'enable_scroll'  => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Enable scrolling for dropdown lists.', 'cherry-comments' ),
					'value'  => $this->get_option( 'enable_scroll', $get_default, true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
						'true_slave'   => 'result_area_height',
					),
				),
				'result_area_height' => array(
					'type'       => 'stepper',
					'parent'     => 'visual_options',
					'title'      => esc_html__( 'Dropdown list height.', 'cherry-comments' ),
					'value'      => $this->get_option( 'result_area_height', $get_default, 500 ),
					'max_value'  => 500,
					'min_value'  => 0,
					'step_value' => 1,
					'master'     => 'enable_scroll',
				),
				'more_button'      => array(
					'type'   => 'text',
					'parent' => 'visual_options',
					'title'  => esc_html__( '"View more" button text.', 'cherry-comments' ),
					'value'  => $this->get_option( 'more_button', $get_default, esc_html__( 'View more.', 'cherry-comments' ) ),
					'master' => 'more_button',
				),
				'prev_button'      => array(
					'type'   => 'text',
					'parent' => 'visual_options',
					'title'  => esc_html__( '"Prev" button text.', 'cherry-comments' ),
					'value'  => $this->get_option( 'prev_button', $get_default, esc_html__( '< Prev', 'cherry-comments' ) ),
					'master' => 'navigation_button_element',
				),
				'next_button'      => array(
					'type'   => 'text',
					'parent' => 'visual_options',
					'title'  => esc_html__( '"Next" button text.', 'cherry-comments' ),
					'value'  => $this->get_option( 'next_button', $get_default, esc_html__( 'Next >', 'cherry-comments' ) ),
					'master' => 'navigation_button_element',
				),

// Notice Messages
				'negative_search'      => array(
					'type'          => 'text',
					'parent'        => 'notices',
					'title'         => esc_html__( 'Negative search results.', 'cherry-comments' ),
					'value'         => $this->get_option( 'negative_search', $get_default, esc_html__( 'Sorry, but nothing matched your search terms.', 'cherry-comments' ) ),
				),

				'server_error'      => array(
					'type'          => 'text',
					'parent'        => 'notices',
					'title'         => esc_html__( 'Technical error.', 'cherry-comments' ),
					'value'         => $this->get_option( 'server_error', $get_default, esc_html__( 'Sorry, but we cannot handle your search query now. Please, try again later!', 'cherry-comments' ) ),
				),
			);
		}

		/**
		 * Get icons set
		 *
		 * @since 1.0.0
		 * @access private
		 * @return array
		 */
		private function get_icons_set() {
			return array(
				'icon_set'    => 'cherryWidgetFontAwesome',
				'icon_css'    => esc_url( CHERRY_COMMENTS_URI . 'assets/css/min/font-awesome.min.css' ),
				'icon_base'   => 'fa',
			);
		}

		/**
		 * Get search source.
		 *
		 * @since 1.0.0
		 * @access private
		 * @return array
		 */
		private function get_search_source() {
			$sources = get_post_types( '', 'objects' );
			$exude   = array( 'revision', 'nav_menu_item' );
			$output  = array();
			if ( $sources ) {
				foreach ( $sources as $key => $value ) {
					if ( ! in_array( $key, $exude ) ) {
						$output[ $value->name ] = ucfirst( $value->label );
					}
				}
			}
			return $output;
		}

		/**
		 * .
		 *
		 * @since 1.0.0
		 * @access private
		 * @return array
		 */
		private function get_categories() {
			return apply_filters( 'cherry_comments_support_categories', array( 'category', 'projects_category', 'product_cat' ) );
		}

		/**
		 * .
		 *
		 * @since 1.0.0
		 * @access private
		 * @return array
		 */
		private function get_tags() {
			return apply_filters( 'cherry_comments_support_tags', array( 'post_tag', 'projects_tag', 'product_tag' ) );
		}
	}
}
