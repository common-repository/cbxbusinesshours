<?php
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	//extra shortcode for auto start day with today's day
	add_shortcode( 'cbxbusinesshours_starttoday', 'cbxbusinesshours_starttoday_callback' );

	if ( ! function_exists( 'cbxbusinesshours_starttoday_callback' ) ) {
		function cbxbusinesshours_starttoday_callback( $atts ) {

			$start_of_week_global = get_option( 'start_of_week' );
			$today                = strtolower( date( "D" ) );
			$week_days            = array_keys( CBXBusinessHoursHelper::getWeekShortDays() );
			$start_of_week        = array_search( $today, $week_days );
			$start_of_week        = ( $start_of_week === false ) ? $start_of_week_global : $start_of_week;


			// normalize attribute keys, lowercase
			$atts = array_change_key_case( (array) $atts, CASE_LOWER );


			$atts = shortcode_atts( array(
				'title'         => esc_html__('Business Opening Hours', 'cbxbusinesshours'),//leave empty to ignore
				'before_text'   => '',//text to display before opening hours
				'after_text'    => '',//text to display after opening hours
				'compact'       => 0,
				'time_format'   => 24,
				'day_format'    => 'long',
				'start_of_week' => $start_of_week, //0 = sunday, 1 = monday
				'today'         => '' //empty means week, another value 'today'  or any custom date in format 'yyyy-mm-dd'
			), $atts, 'cbxbusinesshours_starttoday' );

			//return CBXBusinessHoursHelper::business_hours_display( $atts );
			return '<div class="cbxbusinesshours_display_wrap">'.CBXBusinessHoursHelper::business_hours_display( $atts ).'</div>';

		}//end cbxbusinesshours_starttoday_callback
	}
