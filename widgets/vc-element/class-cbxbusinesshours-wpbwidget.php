<?php
// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class CBXBusinessHours_WPBWidget extends WPBakeryShortCode {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'bakery_shortcode_mapping' ),12 );
	}// /end of constructor

	/**
	 * Element Mapping
	 */
	public function bakery_shortcode_mapping() {
		$start_of_week_global = get_option( 'start_of_week' );
		$week_days            = array_values( \CBXBusinessHoursHelper::getWeekLongDays() );

		// Map the block with vc_map()
		vc_map( array(
			"name"        => esc_html__( "CBX Business Opening Hours", 'cbxbusinesshours' ),
			"description" => esc_html__( "CBX business opening hours display widget", 'cbxbusinesshours' ),
			"base"        => "cbxbusinesshours",
			"icon"        => CBXBUSINESSHOURS_ROOT_URL . 'assets/images/vc_icon.png',
			"category"    => esc_html__( 'CBX Widgets', 'cbxbusinesshours' ),
			"params"      => apply_filters( 'cbxbusinesshours_wpbakery_params', array(
					array(
						'type'        => 'textfield',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Title', 'cbxbusinesshours' ),
						'param_name'  => 'title',
						'std'         => esc_html__('Business Opening Hours', 'cbxbusinesshours'),
					),
					array(
						'type'        => 'textfield',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Post ID', 'cbxbusinesshours' ),
						'description' => esc_html__( 'To display business hours from post meta put post id, if post id  is set below params will be ignored. Post ID 0 means it will display from global setting and below params.', 'cbxbusinesshours' ),
						'param_name'  => 'post_id',
						'std'         => 0,
					),
					array(
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Honor Post Meta', 'cbxbusinesshours' ),
						'description' => esc_html__( 'If post id greater than 0 or valid, then other widget params ignored and post meta values are used. So, choose no will help to display custom as widget settings.', 'cbxbusinesshours' ),
						'param_name'  => 'honor_post_meta',
						'value'       => array(
							esc_html__( 'Yes', 'cbxbusinesshours' )  => '1',
							esc_html__( 'No', 'cbxbusinesshours' ) => '0',
						),
						'std'         => 1,
					),
					array(
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Display Mode', 'cbxbusinesshours' ),
						'param_name'  => 'compact',
						'value'       => array(
							esc_html__( 'Plain Table', 'cbxbusinesshours' )  => '1',
							esc_html__( 'Compact Table', 'cbxbusinesshours' ) => '0',
						),
						'std'         => 0,
					),
					array(
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Time Format', 'cbxbusinesshours' ),
						'param_name'  => 'time_format',
						'value'       => array(
							esc_html__( '24 hours', 'cbxbusinesshours' )  => 24,
							esc_html__( '12 hours', 'cbxbusinesshours' ) => 12,
						),
						'std'         => 24,
					),
					array(
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Day Name Format', 'cbxbusinesshours' ),
						'param_name'  => 'day_format',
						'value'       => array(
							esc_html__( 'Long', 'cbxbusinesshours' )  => 'long',
							esc_html__( 'Short', 'cbxbusinesshours' ) => 'short',
						),
						'std'         => 'long',
					),
					array(
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Opening Days Display', 'cbxbusinesshours' ),
						'param_name'  => 'today',
						'value'       => array(
							esc_html__( 'Current Week(7 days)', 'cbxbusinesshours' )  => '',
							esc_html__( 'Today/For Current Date', 'cbxbusinesshours' ) => 'today',
						),
						'std'         => 'long',
					),
					array(
						"type"        => "cbxbusinesshoursdate",
						"class"       => "",
						'admin_label' => true,
						"heading"     => esc_html__( 'Custom Date(Format: yyyy-mm-dd)', 'cbxbusinesshours' ),
						"param_name"  => "custom_date",
						"value"       => esc_html__( 'yyyy-mm-dd', 'cbxbusinesshours' ),
						'description' => esc_html__( 'Date format: yyyy-mm-dd', 'cbxbusinesshours' ),
						'std'         => ''
					),
					array(
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Start of the Week', 'cbxbusinesshours' ),
						'param_name'  => 'start_of_week',
						'value'       => $week_days,
						'std'         => $start_of_week_global,
					),
					array(
						'type'        => 'textfield',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Before Text', 'cbxbusinesshours' ),
						'param_name'  => 'before_text',
						'std'         => '',
					),
					array(
						'type'        => 'textfield',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'After Text', 'cbxbusinesshours' ),
						'param_name'  => 'after_text',
						'std'         => '',
					),

				)
			)
		) );
	}//end bakery_shortcode_mapping
}// end class CBXBusinessHours_WPBWidget