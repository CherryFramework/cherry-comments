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
	class Cherry_Comments_Options{

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
		 * Settings section on options page.
		 *
		 * @since 1.0.0
		 * @var array
		 * @access public
		 */
		public $options = null;

		/**
		 * Default options.
		 *
		 * @since 1.0.0
		 * @var array
		 * @access private
		 */
		private $options_default = array();

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

			$this->set_options();
		}


		/**
		 * Function set phugin options.
		 *
		 * @since 1.0.0
		 * @access private
		 * @return void
		 */
		private function set_options() {
			$this->form = array(
				'chery-search-options-form' => array(),
			);

			$this->section = array(
				'search_options_section' => array(
					'type'          => 'section',
					'parent'        => 'chery-search-options-form',
					'title'         => '<span class="dashicons dashicons-search"></span>' . esc_html__( 'Cherry Search Settings', 'cherry-search' ),
				),
			);

			$this->component_tab = array(
				'search_options_tab'   => array(
					'type'           => 'component-tab-vertical',
					'parent'         => 'search_options_section',
				),
			);

			$this->tabs = array(
				'main'            => array(
					'type'   => 'options',
					'parent' => 'search_options_tab',
					'scroll' => true,
					'title'  => esc_html__( 'Main options', 'cherry-search' ),
				),
				'query_options'  => array(
					'type'   => 'options',
					'parent' => 'search_options_tab',
					'scroll' => true,
					'title'  => esc_html__( 'Search results options', 'cherry-search' ),
				),
				'visual_options' => array(
					'type'   => 'options',
					'parent' => 'search_options_tab',
					'scroll' => true,
					'title'  => esc_html__( 'Visual options', 'cherry-search' ),
				),
				'notices' => array(
					'type'   => 'options',
					'parent' => 'search_options_tab',
					'scroll' => true,
					'title'  => esc_html__( 'Notifications', 'cherry-search' ),
				),
				'submite_buttons' => array(
					'type'   => 'options',
					'parent' => 'search_options_section',
				),
			);

			$this->info = array(
				'form_html'  => array(
					'type'       => 'html',
					'parent'     => 'main',
					'class'      => 'cherry-control info-block',
					'html'       => sprintf(
						'<p>%1$s</p><ol><li>%2$s</li><li>%3$s</li><li>%4$s</li></ol>',
						esc_html__( 'In case you need to add Cherry Search on your website, you can do it in several ways:', 'cherry-search' ),
						esc_html__( 'Enable a "Replace the standard search" option', 'cherry-search' ),
						esc_html__( 'Add Cherry Search using this shortcode', 'cherry-search' ) . ' <code class ="cherry-code-example">' . htmlspecialchars( '[cherry_comments_form]' ) . '</code>',
						esc_html__( 'Add PHP code to the necessaryfiles of your theme:', 'cherry-search' ) . '<code class ="cherry-code-example">' . htmlspecialchars( '<?php if ( function_exists( \'cherry_get_search_form\' ) ) { cherry_get_search_form(); } ?>' ) . '</code>'
					),
				),
			);

			$this->options = array(

// Main Settings
				'change_standard_search'  => array(
					'type'         => 'switcher',
					'parent'       => 'main',
					'title'        => esc_html__( 'Replace the standard search form.', 'cherry-search' ),
					'description'  => esc_html__( 'This option allows to replace all the standard search forms on your website.', 'cherry-search' ),
					'value'        => $this->get_setting( 'change_standard_search', true ),
					'toggle'       => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-search' ),
						'false_toggle' => esc_html__( 'No', 'cherry-search' ),
					),
				),
				'search_button_icon' => array(
					'type'        => 'iconpicker',
					'parent'      => 'main',
					'title'       => esc_html__( 'Search Button Icon.', 'cherry-search' ),
					'description' => esc_html__( 'This option sets search button text.', 'cherry-search' ),
					'value'       => $this->get_setting( 'search_button_icon', '' ),
					'auto_parse'  => true,
					'icon_data'   => apply_filters( 'cherry_comments_button_icon', $this->get_icons_set() ),
				),
				'search_button_text'      => array(
					'type'        => 'text',
					'parent'      => 'main',
					'title'       => esc_html__( 'Search Button Text.', 'cherry-search' ),
					'description' => esc_html__( 'This option sets search button text.', 'cherry-search' ),
					'value'       => $this->get_setting( 'search_button_text', '' ),
				),
				'search_placeholder_text' => array(
					'type'        => 'text',
					'parent'      => 'main',
					'title'       => esc_html__( 'Caption / Placeholder text.', 'cherry-search' ),
					'description' => esc_html__( 'This option sets placeholder text in input field.', 'cherry-search' ),
					'value'       => $this->get_setting( 'search_placeholder_text', esc_html__( 'Search', 'cherry-search' ) ),
				),

// Search Query Settings
				'search_source' => array(
					'type'        => 'select',
					'parent'      => 'query_options',
					'title'       => esc_html__( 'Search in.', 'cherry-search' ),
					'description' => esc_html__( 'You can select particular search areas. If nothing is selected in the option, search will be made over the entire site.', 'cherry-search' ),
					'multiple'    => true,
					'filter'      => true,
					'value'       => $this->get_setting( 'search_source', array( 'any' ) ),
					'options'     => $this->get_search_source(),
					'placeholder' => esc_html__( 'Selected search source.', 'cherry-search' ),
				),
				'exclude_source_category' => array(
					'type'        => 'select',
					'parent'      => 'query_options',
					'title'       => esc_html__( 'Exclude categories from search results.', 'cherry-search' ),
					'description' => esc_html__( 'This option allows to set categories in which search will not be made.', 'cherry-search' ),
					'multiple'    => true,
					'filter'      => true,
					'value'       => $this->get_setting( 'exclude_source_category', 'projects' ),
					'options'     => $this->utility->satellite->get_terms_array( $this->get_categories() ),
					'placeholder' => esc_html__( 'Not selected categories.', 'cherry-search' ),
				),
				'exclude_source_tags' => array(
					'type'        => 'select',
					'parent'      => 'query_options',
					'title'       => esc_html__( 'Exclude tags from search results.', 'cherry-search' ),
					'description' => esc_html__( 'This option allows to set tags in which search will not be made.', 'cherry-search' ),
					'multiple'    => true,
					'filter'      => true,
					'value'       => $this->get_setting( 'exclude_source_tags', '' ),
					'options'     => $this->utility->satellite->get_terms_array( $this->get_tags() ),
					'placeholder' => esc_html__( 'Not selected tags.', 'cherry-search' ),
				),
				'exclude_source_post_format' => array(
					'type'        => 'select',
					'parent'      => 'query_options',
					'title'       => esc_html__( 'Exclude post types from search results.', 'cherry-search' ),
					'description' => esc_html__( 'This option allows to post types in which search will not be made.', 'cherry-search' ),
					'multiple'    => true,
					'filter'      => true,
					'value'       => $this->get_setting( 'exclude_source_post_format', '' ),
					'options'     => $this->utility->satellite->get_terms_array( 'post_format' ),
					'placeholder' => esc_html__( 'Not selected post formats.', 'cherry-search' ),
				),
				'limit_query'             => array(
					'type'        => 'stepper',
					'parent'      => 'query_options',
					'title'       => esc_html__( 'Number of results displayed in one search query.', 'cherry-search' ),
					'description' => esc_html__( 'This option will allow you to limit the number of displayed search results. If the overall number of results will exceeed the previously set limit, the "load more" button will come up..', 'cherry-search' ),
					'value'       => $this->get_setting( 'limit_query', 5 ),
					'max_value'   => 50,
					'min_value'   => 0,
					'step_value'  => 1,
				),
				'results_order_by'        => array(
					'type'    => 'radio',
					'parent'  => 'query_options',
					'title'   => esc_html__( 'Sort search results by:', 'cherry-search' ),
					'value'   => $this->get_setting( 'results_order_by', 'date' ),
					'options' => array(
						'date'          => array(
							'label' => esc_html__( 'Date', 'cherry-search' ),
						),
						'title'         => array(
							'label' => esc_html__( 'Title', 'cherry-search' ),
						),
						'autohr'        => array(
							'label' => esc_html__( 'Author', 'cherry-search' ),
						),
						'modified'      => array(
							'label' => esc_html__( 'Last modified', 'cherry-search' ),
						),
						'comment_count' => array(
							'label' => esc_html__( 'Number of Comments (descending)', 'cherry-search' ),
						),
					),
				),
				'results_order'           => array(
					'type'    => 'radio',
					'parent'  => 'query_options',
					'title'   => esc_html__( 'Filter results by: ', 'cherry-search' ),
					'value'   => $this->get_setting( 'results_order', 'asc' ),
					'options' => array(
						'asc'  => array(
							'label' => esc_html__( 'Asc', 'cherry-search' ),
						),
						'desc' => array(
							'label' => esc_html__( 'Desc', 'cherry-search' ),
						),
					),
				),

// Visual Tuning
				'title_visible' => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Show post titles.', 'cherry-search' ),
					'value'  => $this->get_setting( 'title_visible', true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-search' ),
						'false_toggle' => esc_html__( 'No', 'cherry-search' ),
					),
				),
				'limit_content_word' => array(
					'type'       => 'stepper',
					'parent'     => 'visual_options',
					'title'      => esc_html__( 'Post word count.', 'cherry-search' ),
					'value'      => $this->get_setting( 'limit_content_word', apply_filters( 'cherry_comments_limit_content_word', 50 ) ),
					'max_value'  => 150,
					'min_value'  => 0,
					'step_value' => 1,
				),
				'author_visible' => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Show post authors.', 'cherry-search' ),
					'value'  => $this->get_setting( 'author_visible', true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-search' ),
						'false_toggle' => esc_html__( 'No', 'cherry-search' ),
						'true_slave'   => 'author_prefix',
					),
				),
				'author_prefix' => array(
					'type'   => 'text',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Prefix before author`s name.', 'cherry-search' ),
					'value'  => $this->get_setting( 'author_prefix', esc_html__( 'Posted by:', 'cherry-search' ) ),
					'master' => 'author_visible',
				),
				'post_type_visible' => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Show post type.', 'cherry-search' ),
					'value'  => $this->get_setting( 'post_type_visible', true ),
					'lock'  => $this->lock_option(),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-search' ),
						'false_toggle' => esc_html__( 'No', 'cherry-search' ),
					),
				),
				'category_visible' => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Show post categoryes.', 'cherry-search' ),
					'value'  => $this->get_setting( 'category_visible', true ),
					'lock'  => $this->lock_option(),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-search' ),
						'false_toggle' => esc_html__( 'No', 'cherry-search' ),
					),
				),
				'post_tag_visible' => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Show post tags.', 'cherry-search' ),
					'value'  => $this->get_setting( 'post_tag_visible', true ),
					'lock'  => $this->lock_option(),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-search' ),
						'false_toggle' => esc_html__( 'No', 'cherry-search' ),
					),
				),
				'thumbnail_visible' => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Show post thumbnails.', 'cherry-search' ),
					'value'  => $this->get_setting( 'thumbnail_visible', true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-search' ),
						'false_toggle' => esc_html__( 'No', 'cherry-search' ),
					),
				),
				'enable_scroll'  => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Enable scrolling for dropdown lists.', 'cherry-search' ),
					'value'  => $this->get_setting( 'enable_scroll', true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-search' ),
						'false_toggle' => esc_html__( 'No', 'cherry-search' ),
						'true_slave'   => 'result_area_height',
					),
				),
				'result_area_height' => array(
					'type'       => 'stepper',
					'parent'     => 'visual_options',
					'title'      => esc_html__( 'Dropdown list height.', 'cherry-search' ),
					'value'      => $this->get_setting( 'result_area_height', 500 ),
					'max_value'  => 500,
					'min_value'  => 0,
					'step_value' => 1,
					'master'     => 'enable_scroll',
				),
				'post_count_visible'  => array(
					'type'   => 'switcher',
					'parent' => 'visual_options',
					'title'  => esc_html__( 'Enable results counter.', 'cherry-search' ),
					'value'  => $this->get_setting( 'post_count_visible', false ),
					'lock'   => $this->lock_option(),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-search' ),
						'false_toggle' => esc_html__( 'No', 'cherry-search' ),
					),
				),
				'result_area_navigation' => array(
					'type'    => 'radio',
					'parent'  => 'visual_options',
					'title'   => esc_html__( 'Result area navigation:', 'cherry-search' ),
					'value'   => $this->get_setting( 'result_area_navigation', 'more_button' ),
					'options' => array(
						'hide_navigation' => array(
							'label' => esc_html__( 'Hide navigation', 'cherry-search' ),
						),
						'more_button' => array(
							'label' => esc_html__( '"View more" button', 'cherry-search' ),
							'slave' => 'more_button',
						),
						'bullet_pagination' => array(
							'label' => esc_html__( 'Bullet pagination', 'cherry-search' ),
							'lock'  => $this->lock_option(),
						),
						'number_pagination' => array(
							'label' => esc_html__( 'Number pagination', 'cherry-search' ),
							'lock'  => $this->lock_option(),
						),
						'navigation_button' => array(
							'label' => esc_html__( 'Navigation button', 'cherry-search' ),
							'lock'  => $this->lock_option(),
							'slave' => 'navigation_button_element',
						),
					),
				),
				'more_button'      => array(
					'type'   => 'text',
					'parent' => 'visual_options',
					'title'  => esc_html__( '"View more" button text.', 'cherry-search' ),
					'value'  => $this->get_setting( 'more_button', esc_html__( 'View more.', 'cherry-search' ) ),
					'master' => 'more_button',
				),
				'prev_button'      => array(
					'type'   => 'text',
					'parent' => 'visual_options',
					'title'  => esc_html__( '"Prev" button text.', 'cherry-search' ),
					'value'  => $this->get_setting( 'prev_button', esc_html__( '< Prev', 'cherry-search' ) ),
					'master' => 'navigation_button_element',
				),
				'next_button'      => array(
					'type'   => 'text',
					'parent' => 'visual_options',
					'title'  => esc_html__( '"Next" button text.', 'cherry-search' ),
					'value'  => $this->get_setting( 'next_button', esc_html__( 'Next >', 'cherry-search' ) ),
					'master' => 'navigation_button_element',
				),

// Notice Messages
				'negative_search'      => array(
					'type'          => 'text',
					'parent'        => 'notices',
					'title'         => esc_html__( 'Negative search results.', 'cherry-search' ),
					'value'         => $this->get_setting( 'negative_search', esc_html__( 'Sorry, but nothing matched your search terms.', 'cherry-search' ) ),
				),

				'server_error'      => array(
					'type'          => 'text',
					'parent'        => 'notices',
					'title'         => esc_html__( 'Technical error.', 'cherry-search' ),
					'value'         => $this->get_setting( 'server_error', esc_html__( 'Sorry, but we cannot handle your search query now. Please, try again later!', 'cherry-search' ) ),
				),
			);

			$this->buttons = array(
// Submite buttons
				'cherry-reset-buttons'  => array(
					'type'          => 'button',
					'parent'        => 'submite_buttons',
					'content'       => '<span class="text">' . esc_html__( 'Reset', 'cherry-search' ) . '</span>' . $this->spinner . $this->button_icon,
					'view_wrapping' => false,
					'form'          => 'chery-search-options-form',
				),
				'cherry-save-buttons'  => array(
					'type'          => 'button',
					'parent'        => 'submite_buttons',
					'style'         => 'success',
					'content'       => '<span class="text">' . esc_html__( 'Save', 'cherry-search' ) . '</span>' . $this->spinner . $this->button_icon,
					'view_wrapping' => false,
					'form'          => 'chery-search-options-form',
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
