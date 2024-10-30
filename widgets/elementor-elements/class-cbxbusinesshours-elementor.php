<?php

namespace CBXBusinessHoursElemWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CBX Office Opening & Business Hours Elementor Widget
 */
class CBXBusinessHours_ElemWidget extends \Elementor\Widget_Base {

	/**
	 * Retrieve widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cbxbusinesshours';
	}

	/**
	 * Retrieve  widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'CBX Business Hours', 'cbxbusinesshours' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the widget categories.
	 *
	 * @since  1.0.10
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'codeboxr' );
	}

	/**
	 * Retrieve g widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cbxbusinesshours-icon';
	}

	/**
	 * Register google maps widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		global $post;
		$post_id = intval($post->ID);


		$this->start_controls_section(
			'section_cbxbusinesshours',
			array(
				'label' => esc_html__( 'CBX Business Opening Hours', 'cbxbusinesshours' ),
			)
		);

		$this->add_control(
			'cbxbusinesshours_title',
			array(
				'label'   => esc_html__( 'Title', 'cbxbusinesshours' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__('Business Opening Hours', 'cbxbusinesshours'),
				'label_block' => true
			)
		);

		$this->add_control(
			'cbxbusinesshours_post_id',
			array(
				'label'   => esc_html__( 'Post ID', 'cbxbusinesshours' ),
				'description'   => esc_html__( 'To display business hours from post meta put post id, if post id  is set below params will be ignored. Post ID 0 means it will display from global setting and below params.', 'cbxbusinesshours' ).sprintf(esc_html__('This post ID: %d', 'cbxbusinesshours'), $post_id),
				'type'    => Controls_Manager::TEXT,
				'default' => 0,
				'label_block' => true
			)
		);

		$this->add_control(
			'cbxbusinesshours_honor_post_meta',
			array(
				'label'   => esc_html__( 'Honor Post Meta', 'cbxbusinesshours' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => 1,
				'options' => array(
					1 => esc_html__( 'Yes', 'cbxbusinesshours' ),
					0 => esc_html__( 'No', 'cbxbusinesshours' ),
				),
				'label_block' => true,
				'description'   => esc_html__( 'If post id greater than 0 or valid, then other widget params ignored and post meta values are used. So, choose no will help to display custom as widget settings.', 'cbxbusinesshours' ),
			)
		);

		$this->add_control(
			'cbxbusinesshours_compact',
			array(
				'label'   => esc_html__( 'Display Mode', 'cbxbusinesshours' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => 0,
				'options' => array(
					0 => esc_html__( 'Plain Table', 'cbxbusinesshours' ),
					1 => esc_html__( 'Compact Table', 'cbxbusinesshours' ),
				),
				'label_block' => true
			)
		);

		$this->add_control(
			'cbxbusinesshours_time_format',
			array(
				'label'   => esc_html__( 'Time Format', 'cbxbusinesshours' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => 24,
				'options' => array(
					24 => esc_html__( '24 hours', 'cbxbusinesshours' ),
					12 => esc_html__( '12 hours', 'cbxbusinesshours' ),
				),
				'label_block' => true
			)
		);

		$this->add_control(
			'cbxbusinesshours_day_format',
			array(
				'label'   => esc_html__( 'Day Name Format', 'cbxbusinesshours' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => 'long',
				'options' => array(
					'long'  => esc_html__( 'Long', 'cbxbusinesshours' ),
					'short' => esc_html__( 'Short', 'cbxbusinesshours' ),
				),
				'label_block' => true
			)
		);

		$this->add_control(
			'cbxbusinesshours_today',
			array(
				'label'   => esc_html__( 'Opening Days Display', 'cbxbusinesshours' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => 'week',
				'options' => array(
					'week'  => esc_html__( 'Current Week(7 days)', 'cbxbusinesshours' ),
					'today' => esc_html__( 'Today/For Current Date', 'cbxbusinesshours' ),
				),
				'label_block' => true
			)
		);

		$this->add_control(
			'cbxbusinesshours_custom_date',
			array(
				'label'          => esc_html__( 'Custom Date(Format: yyyy-mm-dd)', 'cbxbusinesshours' ),
				'type'           => Controls_Manager::DATE_TIME,
				'default'        => '',
				'picker_options' => array(
					'enableTime' => false,
				),
				'label_block' => true
			)
		);

		$start_of_week_global = get_option( 'start_of_week' );
		$week_days            = array_values( \CBXBusinessHoursHelper::getWeekLongDays() );

		$this->add_control(
			'cbxbusinesshours_start_of_week',
			array(
				'label'       => esc_html__( 'Start of the Week', 'cbxbusinesshours' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => $start_of_week_global,
				'options'     => $week_days,
				'label_block' => true
			)

		);

		$this->add_control(
			'cbxbusinesshours_before_text',
			array(
				'label'   => esc_html__( 'Before Text', 'cbxbusinesshours' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true
			)
		);

		$this->add_control(
			'cbxbusinesshours_after_text',
			array(
				'label'   => esc_html__( 'After Text', 'cbxbusinesshours' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		/*if ( !class_exists( 'CBXBusinessHoursSettings' ) ) {
			require_once CBXBUSINESSHOURS_ROOT_PATH . 'includes/class-cbxbusinesshours-settings.php';
		}

		$settings_api = new \CBXBusinessHoursSettings();*/

		$start_of_week_global = get_option( 'start_of_week' );
		$settings             = $this->get_settings();


		$atts = array();

		$atts['post_id']         = isset( $settings['cbxbusinesshours_post_id'] ) ? intval( $settings['cbxbusinesshours_post_id'] ) : 0;
		$atts['honor_post_meta'] = isset( $settings['cbxbusinesshours_honor_post_meta'] ) ? intval( $settings['cbxbusinesshours_honor_post_meta'] ) : 1;
		$atts['title']           = isset( $settings['cbxbusinesshours_title'] ) ? sanitize_text_field( $settings['cbxbusinesshours_title'] ) : '';
		$atts['before_text']     = isset( $settings['cbxbusinesshours_before_text'] ) ? sanitize_text_field( $settings['cbxbusinesshours_before_text'] ) : '';
		$atts['after_text']      = isset( $settings['cbxbusinesshours_after_text'] ) ? sanitize_text_field( $settings['cbxbusinesshours_after_text'] ) : '';
		$atts['start_of_week']   = isset( $settings['cbxbusinesshours_start_of_week'] ) ? intval( $settings['cbxbusinesshours_start_of_week'] ) : $start_of_week_global;
		$atts['compact']         = isset( $settings['cbxbusinesshours_compact'] ) ? intval( $settings['cbxbusinesshours_compact'] ) : 0;
		$atts['time_format']     = isset( $settings['cbxbusinesshours_time_format'] ) ? intval( $settings['cbxbusinesshours_time_format'] ) : 24;
		$atts['day_format']      = isset( $settings['cbxbusinesshours_day_format'] ) ? esc_attr( $settings['cbxbusinesshours_day_format'] ) : 'long';
		$atts['today']           = isset( $settings['cbxbusinesshours_today'] ) ? esc_attr( $settings['cbxbusinesshours_today'] ) : '';
		$custom_date             = isset( $settings['cbxbusinesshours_custom_date'] ) ? esc_attr( $settings['cbxbusinesshours_custom_date'] ) : '';

		if ( $atts['today'] == 'week' ) {
			$atts['today'] = '';
		}

		if ( $atts['today'] == 'today' && $custom_date != '' && \CBXBusinessHoursHelper::validateDate( $custom_date ) ) {
			$atts['today'] = esc_attr( $custom_date );
		}

		echo '<div class="cbxbusinesshours_display_wrap">';
		echo \CBXBusinessHoursHelper::business_hours_display( $atts, intval($atts['post_id']) );
		echo '</div>';
	}//end render

	/**
	 * Render widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function _content_template() {

	}//end _content_template
}//end CBXBusinessHours_ElemWidget
