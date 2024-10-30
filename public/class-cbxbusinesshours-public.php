<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXBusinessHours
 * @subpackage CBXBusinessHours/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CBXBusinessHours
 * @subpackage CBXBusinessHours/public
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXBusinessHours_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;


	private $setting;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

        if ( defined( 'WP_DEBUG' ) ) {
            $this->version = current_time( 'timestamp' ); //for development time only
        }

		$this->setting = new CBXBusinessHoursSettings();
	}//end

	/**
	 *
	 */
	public function init_register_widgets() {
		/**
		 * Front end display widgets file
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/classic_widgets/class-cbxbusinesshour-front-widget.php';

		register_widget( 'CBXBusinessHoursFrontWidget' );
	}// end of init_register_widgets method

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_register_style( 'cbxbusinesshours-public', plugin_dir_url( __FILE__ ) . '../assets/css/cbxbusinesshours-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

	}//end enqueue_scripts

	/**
	 * Init all shortcodes
	 */
	public function init_shortcodes() {
		add_shortcode( 'cbxbusinesshours', array( $this, 'cbxbusinesshours_shortcode' ) );
	}//end init_shortcodes

	/**
	 * Shortcode [cbxbusinesshours] callback
	 *
	 * @param $atts
	 *
	 * @return string
	 * @throws Exception
	 */
	public function cbxbusinesshours_shortcode( $atts ) {
		// normalize attribute keys, lowercase
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		$start_of_week_global = get_option( 'start_of_week' );

		$atts = shortcode_atts( array(
			'title'           => esc_html__( 'Business Opening Hours', 'cbxbusinesshours' ),
			//leave empty to ignore
			'before_text'     => '',
			//text to display before opening hours
			'after_text'      => '',
			//text to display after opening hours
			'compact'         => 0,
			'time_format'     => 24,
			'day_format'      => 'long',
			'start_of_week'   => $start_of_week_global,
			//0 = sunday, 1 = monday
			'today'           => '',
			//empty means week, another value 'today'  or any custom date in format 'yyyy-mm-dd',
			'post_id'         => 0,
			'honor_post_meta' => 1, //if  post_id > 0 then post meta used than shortcode other param(where applicable)
		), $atts, 'cbxbusinesshours' );


		$atts['title'] = sanitize_text_field($atts['title']);
		$atts['post_id'] = $post_id = intval($atts['post_id']);

		return '<div class="cbxbusinesshours_display_wrap">'.CBXBusinessHoursHelper::business_hours_display( $atts, $post_id ).'</div>';
	}//end cbxbusinesshours_shortcode

	/**
	 * init elementor widgets
	 *
	 * @throws Exception
	 */
	public function init_elementor_widgets() {

		//include the widget class file
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor-elements/class-cbxbusinesshours-elementor.php';

		//register the widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CBXBusinessHoursElemWidget\Widgets\CBXBusinessHours_ElemWidget() );


	}//end widgets_registered

	/**
	 * Load Elementor Custom Icon
	 */
	function elementor_icon_loader() {
		wp_register_style( 'cbxbusinesshours-elementor-icon', CBXBUSINESSHOURS_ROOT_URL . 'widgets/elementor-elements/elementor-icon/icon.css', false, CBXBUSINESSHOURS_PLUGIN_VERSION );
		wp_enqueue_style( 'cbxbusinesshours-elementor-icon' );
	}//end elementor_icon_loader

	/**
	 * Add new category to elementor
	 *
	 * @param $elements_manager
	 */
	public function add_elementor_widget_categories( $elements_manager ) {

		$categories = $elements_manager->get_categories();

		if ( ! isset( $categories['codeboxr'] ) ) {
			$elements_manager->add_category(
				'codeboxr',
				array(
					'title' => esc_html__( 'Codeboxr Widgets', 'cbxbusinesshours' ),
					'icon'  => 'fa fa-plug',
				)
			);
		}
	}//end add_elementor_widget_categories

	/**
	 * // Before VC Init
	 */
	public function vc_before_init_actions() {

		if ( ! class_exists( 'CBXBusinessHours_VCParam_DatePicker' ) ) {
			require_once CBXBUSINESSHOURS_ROOT_PATH . 'widgets/vc-element/params/class-cbxbusinesshours-vc-param-datepicker.php';
		}

		if ( ! class_exists( 'CBXBusinessHours_WPBWidget' ) ) {
			require_once CBXBUSINESSHOURS_ROOT_PATH . 'widgets/vc-element/class-cbxbusinesshours-wpbwidget.php';
		}


		new CBXBusinessHours_WPBWidget();
	}// end method vc_before_init_actions

	/**
	 * Auto integration for 'the_content'
	 *
	 * @param $content
	 *
	 * @return string
	 * @throws Exception
	 */
	public function the_content_auto_integration( $content ) {
		if(is_admin()) return $content;

		if(!is_singular()) return $content;

		if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ) {
			return $content;
		}



		global  $post;
		$post_id   = intval($post->ID);
		$post_type = $post->post_type;

		$settings = $this->setting;
		$auto_post_types = $settings->get_option( 'auto_post_types', 'cbxbusinesshours_integration', array() );
		if ( ! in_array( $post_type, $auto_post_types ) ) {
			return $content;
		}

		$auto_integration = $settings->get_option( 'auto_integration', 'cbxbusinesshours_integration', 'disable' );
		if($auto_integration == 'disable') return $content;



		$business_hours = '<div class="cbxbusinesshours_display_wrap">'.CBXBusinessHoursHelper::business_hours_display( array(), $post_id ).'</div>';



		if($auto_integration == 'before_content'){
			return $business_hours.$content;
		}
		else{
			return $content.$business_hours;
		}
	}//end  the_content_auto_integration
}//end class CBXBusinessHours_Public
