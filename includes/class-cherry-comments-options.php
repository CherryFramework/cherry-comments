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
			parent::__construct( CHERRY_COMMENTS_SLUG );
			$this->set_component();
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
				'chery-comments-options-form' => array(),
			);

			$this->section = array(
				'cherry-comments-section' => array(
					'type'          => 'section',
					'parent'        => 'chery-comments-options-form',
					'title'         => '<span class="dashicons dashicons-admin-comments"></span>' . esc_html__( 'Cherry Comments Settings', 'cherry-comments' ),
				),
			);

			$this->component_tab = array(
				'cherry-comments-tab'   => array(
					'type'           => 'component-tab-vertical',
					'parent'         => 'cherry-comments-section',
				),
			);

			$this->tabs = array(
				'comment_list'            => array(
					'type'   => 'options',
					'parent' => 'cherry-comments-tab',
					'scroll' => true,
					'title'  => esc_html__( 'Comment List', 'cherry-comments' ),
				),
				'singl_comment'  => array(
					'type'   => 'options',
					'parent' => 'cherry-comments-tab',
					'scroll' => true,
					'title'  => esc_html__( 'Single Comment', 'cherry-comments' ),
				),
				'comment_form' => array(
					'type'   => 'options',
					'parent' => 'cherry-comments-tab',
					'scroll' => true,
					'title'  => esc_html__( 'Comment Form', 'cherry-comments' ),
				),
				'notices' => array(
					'type'   => 'settings',
					'parent' => 'cherry-comments-tab',
					'scroll' => true,
					'title'  => esc_html__( 'Notifications', 'cherry-comments' ),
				),
				'submite_buttons' => array(
					'type'   => 'options',
					'parent' => 'cherry-comments-section',
				),
			);

			/*$this->info = array(
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
			);*/

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

// Comment List
				'comment_count' => array(
					'type'       => 'stepper',
					'parent'     => 'comment_list',
					'title'      => esc_html__( 'Comment count on page.', 'cherry-comments' ),
					'value'      => $this->get_option( 'comment_count', $get_default, 50 ),
					'max_value'  => 500,
					'min_value'  => 10,
					'step_value' => 1,
				),
				'deep_comment_count' => array(
					'type'       => 'stepper',
					'parent'     => 'comment_list',
					'title'      => esc_html__( 'The number of comment levels deep.', 'cherry-comments' ),
					'value'      => $this->get_option( 'deep_comment_count', $get_default, 5 ),
					'max_value'  => 10,
					'min_value'  => 2,
					'step_value' => 1,
				),
				'sort_comments_by' => array(
					'type'         => 'switcher',
					'parent'       => 'comment_list',
					'title'        => esc_html__( 'Enable Sort filter.', 'cherry-comments' ),
					'value'        => $this->get_option( 'sort_comments_by', $get_default, true ),
					'toggle'       => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
					),
					'lock'         => true,
				),
				'enable_comment_count' => array(
					'type'         => 'switcher',
					'parent'       => 'comment_list',
					'title'        => esc_html__( 'Enable comment counter.', 'cherry-comments' ),
					'value'        => $this->get_option( 'enable_comment_count', $get_default, true ),
					'toggle'       => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
					),
				),

// Singl Comment
				'author_gavatar' => array(
					'type'   => 'switcher',
					'parent' => 'singl_comment',
					'title'  => esc_html__( 'Enable author gavatar.', 'cherry-comments' ),
					'value'  => $this->get_option( 'author_gavatar', $get_default, true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
					),
				),
				'author_prefix' => array(
					'type'   => 'textarea',
					'parent' => 'singl_comment',
					'title'  => esc_html__( 'Prefix before author`s name.', 'cherry-search' ),
					'value'  => $this->get_option( 'author_prefix', $get_default, esc_html__( 'Posted by', 'cherry-search' ) ),
				),
				'comment_date' => array(
					'type'   => 'switcher',
					'parent' => 'singl_comment',
					'title'  => esc_html__( 'Enable comment public date.', 'cherry-comments' ),
					'value'  => $this->get_option( 'comment_date', $get_default, true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
						'true_slave'   => 'comment_data_prefix',
					),
				),
				'comment_data_prefix'      => array(
					'type'   => 'textarea',
					'parent' => 'singl_comment',
					'title'  => esc_html__( 'Prefix before publish date.', 'cherry-search' ),
					'value'  => $this->get_option( 'comment_data_prefix', $get_default, esc_html__( 'Publict date', 'cherry-search' ) ),
					'master' => 'comment_date',
				),
				'comment_link' => array(
					'type'   => 'switcher',
					'parent' => 'singl_comment',
					'title'  => esc_html__( 'Enable comment link.', 'cherry-comments' ),
					'value'  => $this->get_option( 'comment_link', $get_default, true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
						'true_slave'   => 'comment_link_prefix',
					),
				),
				'comment_link_prefix'      => array(
					'type'   => 'textarea',
					'parent' => 'singl_comment',
					'title'  => esc_html__( 'Prefix before comment link.', 'cherry-search' ),
					'value'  => $this->get_option( 'negative_search', $get_default, esc_html__( 'Sorry, but nothing matched your search terms.', 'cherry-search' ) ),
					'master' => 'comment_link',
				),
				'hide_long_comment' => array(
					'type'   => 'switcher',
					'parent' => 'singl_comment',
					'title'  => esc_html__( 'Hide Long Comment.', 'cherry-comments' ),
					'value'  => $this->get_option( 'hide_long_comment', $get_default, true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
						'true_slave'   => 'comment_area',
					),
				),
				'comment_area' => array(
					'type'       => 'stepper',
					'parent'     => 'singl_comment',
					'title'      => esc_html__( 'comment_area.', 'cherry-comments' ),
					'value'      => $this->get_option( 'comment_area', $get_default, 100 ),
					'max_value'  => 500,
					'min_value'  => 100,
					'step_value' => 1,
					'master' => 'hide_long_comment',
				),

// Comment Form
				'smileys_in_comment' => array(
					'type'   => 'switcher',
					'parent' => 'comment_form',
					'title'  => esc_html__( 'Enable smileys for comment.', 'cherry-comments' ),
					'value'  => $this->get_option( 'smileys_in_comment', $get_default, true ),
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
						'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
					),
				),
				'comment_words_count' => array(
					'type'       => 'stepper',
					'parent'     => 'comment_form',
					'title'      => esc_html__( 'comment_words_count.', 'cherry-comments' ),
					'value'      => $this->get_option( 'comment_words_count', $get_default, 150 ),
					'max_value'  => 500,
					'min_value'  => 1,
					'step_value' => 1,
				),
				'comment_form_builder'  => array(
					'type'         => 'repeater',
					'parent'       => 'comment_form',
					'title'        => esc_html__( 'Build comment form', 'cherry-comments' ),
					'add_label'    => esc_html__( 'Add field', 'cherry-comments' ),
					'title_field'  => 'field_type',
					'hidden_input' => true,
					'fields'       => array(
						'field_type' => array(
							'type'        => 'select',
							'name'        => 'field_type',
							'id'          => 'field_type',
							'label'       => esc_html__( 'Field type', 'cherry-search' ),
							//'description' => esc_html__( 'You can select particular search areas. If nothing is selected in the option, search will be made over the entire site.', 'cherry-search' ),
							'multiple'    => false,
							'filter'      => false,
							'options'     => $this->get_field_type(),
							'placeholder' => esc_html__( 'Selected field type.', 'cherry-search' ),
						),
						'required' => array(
							'type'   => 'switcher',
							'name'   => 'required',
							'id'     => 'required',
							'label'  => esc_html__( 'Required', 'cherry-comments' ),
							'value'  => 'always',
							'toggle' => array(
								'true_toggle'  => esc_html__( 'Yes', 'cherry-comments' ),
								'false_toggle' => esc_html__( 'No', 'cherry-comments' ),
							),
						),
						'lable' => array(
							'type'        => 'text',
							'id'          => 'lable',
							'name'        => 'lable',
							'placeholder' => esc_html__( 'Lable', 'cherry-comments' ),
							'label'       => esc_html__( 'Field Lable', 'cherry-comments' ),
						),
						'placeholder' => array(
							'type'        => 'text',
							'id'          => 'placeholder',
							'name'        => 'placeholder',
							'placeholder' => esc_html__( 'Placeholder', 'cherry-comments' ),
							'label'       => esc_html__( 'Placeholder', 'cherry-comments' ),
						),
						'visibility' => array(
							'type'        => 'select',
							'name'        => 'visibility',
							'id'          => 'visibility',
							'label'       => esc_html__( 'Visibility', 'cherry-search' ),
							//'description' => esc_html__( 'You can select particular search areas. If nothing is selected in the option, search will be made over the entire site.', 'cherry-search' ),
							'multiple'    => false,
							'filter'      => false,
							'options'     => $this->get_visibility_state(),
							'placeholder' => esc_html__( 'Selected visibility state.', 'cherry-search' ),
						),
					),
					'value'       => $this->get_option( 'comment_form_builder', $get_default,
						array(
							'item-0' => array (
								'lable'      => esc_html__( 'Name', 'cherry-search' ),
								'field_type' => 'name',
								'required'    => true,
								'placeholder' => esc_html__( 'Enter your name', 'cherry-search' ),
								'visibility'  => 'not_registered_user',
							),
							'item-1' => array (
								'lable'      => esc_html__( 'Mail', 'cherry-search' ),
								'field_type' => 'mail',
								'required'    => true,
								'placeholder' => esc_html__( 'Enter your e-mail', 'cherry-search' ),
								'visibility'  => 'not_registered_user',
							),
							'item-2' => array (
								'lable'       => esc_html__( 'Website url', 'cherry-search' ),
								'field_type'  => 'website',
								'required'    => true,
								'placeholder' => esc_html__( 'Enter your website url', 'cherry-search' ),
								'visibility'  => 'not_registered_user',
							),
							'item-3' => array (
								'lable'      => esc_html__( 'Comment', 'cherry-search' ),
								'field_type' => 'comment',
								'required'    => true,
								'placeholder' => esc_html__( 'Enter your website url', 'cherry-search' ),
								'visibility'  => 'always',
							),
						)
					),
				),

// Notice Messages
				'invalid_field_notice'      => array(
					'type'          => 'textarea',
					'parent'        => 'notices',
					'title'         => esc_html__( 'invalid_field_notice.', 'cherry-search' ),
					'value'         => $this->get_option( 'negative_search', $get_default, esc_html__( 'Sorry, but nothing matched your search terms.', 'cherry-search' ) ),
				),

				'accaunt_is_bann'      => array(
					'type'          => 'textarea',
					'parent'        => 'notices',
					'title'         => esc_html__( 'accaunt_is_bann.', 'cherry-search' ),
					'value'         => $this->get_option( 'server_error', $get_default, esc_html__( 'Sorry, but we cannot handle your search query now. Please, try again later!', 'cherry-search' ) ),
				),
				'successful'      => array(
					'type'          => 'textarea',
					'parent'        => 'notices',
					'title'         => esc_html__( 'Technical error.', 'cherry-search' ),
					'value'         => $this->get_option( 'server_error', $get_default, esc_html__( 'Sorry, but we cannot handle your search query now. Please, try again later!', 'cherry-search' ) ),
				),
			);
		}

		/**
		 * Return field visibility state.
		 *
		 * @since 1.0.0
		 * @access private
		 * @return array
		 */
		private function get_visibility_state() {
			return apply_filters(
				'cherry_comments__field_visibility_states',
				array(
					'always'    => esc_html__( 'always', 'cherry-comments' ),
					'registered_user' => esc_html__( 'registered_user', 'cherry-comments' ),
					'not_registered_user' => esc_html__( 'not_registered_user', 'cherry-comments' ),
					'hide'    => esc_html__( 'hide', 'cherry-comments' ),
				)
			);
		}
		/**
		 * Return field type.
		 *
		 * @since 1.0.0
		 * @access private
		 * @return array
		 */
		private function get_field_type() {
			return apply_filters(
				'cherry_comments__field_types',
				array(
					'name'    => esc_html__( 'Name', 'cherry-comments' ),
					'mail'    => esc_html__( 'Mail', 'cherry-comments' ),
					'website' => esc_html__( 'User Website', 'cherry-comments' ),
					'comment' => esc_html__( 'Comment', 'cherry-comments' ),
					'captcha' => esc_html__( 'Captcha', 'cherry-comments' ),
				)
			);
		}
	}
}
