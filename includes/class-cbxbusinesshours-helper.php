<?php
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	/**
	 * CBX Businesshours Helper class with lots of static method for quick use
	 *
	 * Class CBXBusinessHoursHelper
	 */
	class CBXBusinessHoursHelper {
		/**
		 * Init session
		 */
		public static function init_session() {

			//if session is not started, let's start it
			/*if ( ! session_id() ) {
				session_start();
			}*/

			/**
			 * Start sessions if not exists
			 *
			 * @author     Ivijan-Stefan Stipic <creativform@gmail.com>
			 */
			if ( version_compare( PHP_VERSION, '7.0.0', '>=' ) ) {
				if ( function_exists( 'session_status' ) && session_status() == PHP_SESSION_NONE ) {
					session_start( array(
						'cache_limiter'  => 'private_no_expire',
						'read_and_close' => false,
					) );
				}
			} elseif ( version_compare( PHP_VERSION, '5.4.0', '>=' ) && version_compare( PHP_VERSION, '7.0.0', '<' ) ) {
				if ( function_exists( 'session_status' ) && session_status() == PHP_SESSION_NONE ) {
					session_cache_limiter( 'private_no_expire' );
					session_start();
				}
			} else {
				if ( session_id() == '' ) {
					if ( version_compare( PHP_VERSION, '4.0.0', '>=' ) ) {
						session_cache_limiter( 'private_no_expire' );
					}
					session_start();
				}
			}

		}//end method init_session

		/**
		 * Returns post types as array
		 *
		 * @return array
		 */
		public static function post_types() {
			$post_type_args = array(
				'builtin' => array(
					'options' => array(
						'public'   => true,
						'_builtin' => true,
						'show_ui'  => true,
					),
					'label'   => esc_html__( 'Built in post types', 'cbxbusinesshours' ),
				)

			);

			$post_type_args = apply_filters( 'cbxbusinesshours_post_types', $post_type_args );

			$output    = 'objects'; // names or objects, note names is the default
			$operator  = 'and'; // 'and' or 'or'
			$postTypes = array();

			foreach ( $post_type_args as $postArgType => $postArgTypeArr ) {
				$types = get_post_types( $postArgTypeArr['options'], $output, $operator );

				if ( ! empty( $types ) ) {
					foreach ( $types as $type ) {
						$postTypes[ $postArgType ]['label']                = $postArgTypeArr['label'];
						$postTypes[ $postArgType ]['types'][ $type->name ] = $type->labels->name;
					}
				}
			}

			return $postTypes;
		}//end post_types

		/**
		 * Plain post types list
		 *
		 * @return array
		 */
		public static function post_types_plain() {
			$post_types = self::post_types();
			$post_arr   = array();

			foreach ( $post_types as $optgroup => $types ) {
				foreach ( $types['types'] as $type_slug => $type_name ) {
					$post_arr[ esc_attr( $type_slug ) ] = wp_unslash( $type_name );
				}
			}

			return $post_arr;
		}//end post_types_plain

		/**
		 * Plain post types list in reverse
		 *
		 * @return array
		 */
		public static function post_types_plain_r() {
			$post_types = self::post_types_plain();

			$post_arr = array();

			foreach ( $post_types as $key => $value ) {
				$post_arr[ esc_attr( wp_unslash( $value ) ) ] = esc_attr( $key );
			}

			return $post_arr;
		}//end post_types_plain_r

		/**
		 * Return the key value pair of posttypes
		 *
		 * @param $all_post_types
		 *
		 * @return array
		 */
		public static function get_formatted_posttype_multicheckbox( $all_post_types ) {
			$posts_defination = array();

			foreach ( $all_post_types as $key => $post_type_defination ) {
				foreach ( $post_type_defination as $post_type_type => $data ) {
					if ( $post_type_type == 'label' ) {
						$opt_grouplabel = $data;
					}

					if ( $post_type_type == 'types' ) {
						foreach ( $data as $opt_key => $opt_val ) {
							$posts_defination[ $opt_grouplabel ][ $opt_key ] = $opt_val;
						}
					}
				}
			}

			return $posts_defination;
		}//end enqueue_styles

		/**
		 * Today's index in a week
		 *
		 * @return false|int|mixed|string|void
		 */
		public static function today_week_index() {
			$current_offset = get_option( 'gmt_offset' );
			$tzstring       = get_option( 'timezone_string' );

			$check_zone_info = true;

			// Remove old Etc mappings. Fallback to gmt_offset.
			if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
				$tzstring = '';
			}

			if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists
				$check_zone_info = false;
				if ( 0 == $current_offset ) {
					$tzstring = '+0';
				} elseif ( $current_offset < 0 ) {
					$tzstring = '' . $current_offset;
				} else {
					$tzstring = '+' . $current_offset;
				}
			}


			//three timezone types:  https://stackoverflow.com/questions/17694894/different-timezone-types-on-datetime-object/17711005#17711005
			$date_time_zone = new DateTimeZone( $tzstring );

			//$date_format   = 'Y-m-d';
			$date_time_now = new DateTime( 'now', $date_time_zone );
			date_time_set( $date_time_now, 0, 0, 0 );
			$today_date     = $date_time_now;
			$today_day      = strtolower( $today_date->format( 'l' ) ); //get the day from date


			//$start_of_week_global = get_option( 'start_of_week' );
			//$today      = strtolower( date( "D" ) );

			//$week_days  = array_keys( CBXBusinessHoursHelper::getWeekShortDays() );
			$week_days  = array_keys( CBXBusinessHoursHelper::getWeekLongDays() );
			$week_index = array_search( $today_day, $week_days );



			return $week_index;
		}//end today_week_index


		/**
		 * Get current user role
		 *
		 * @return array
		 * @since 1.0.5
		 *
		 */
		public static function get_current_user_roles() {
			if ( is_user_logged_in() ) {
				$user  = wp_get_current_user();
				$roles = ( array ) $user->roles;

				return $roles; // This returns an array
				// Use this to return a single value
				// return $roles[0];
			} else {
				return array();
			}
		}//end get_current_user_roles

		/**
		 * Get the user roles
		 *
		 * @param string $useCase
		 *
		 * @return array
		 * @since 1.0.5
		 */
		public static function user_roles( $plain = true, $include_guest = false ) {
			global $wp_roles;

			if ( ! function_exists( 'get_editable_roles' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/user.php' );

			}

			$userRoles = array();
			if ( $plain ) {
				foreach ( get_editable_roles() as $role => $roleInfo ) {
					$userRoles[ $role ] = $roleInfo['name'];
				}
				if ( $include_guest ) {
					$userRoles['guest'] = esc_html__( "Guest", 'cbxbusinesshours' );
				}
			} else {
				//optgroup
				$userRoles_r = array();
				foreach ( get_editable_roles() as $role => $roleInfo ) {
					$userRoles_r[ $role ] = $roleInfo['name'];
				}

				$userRoles = array(
					'Registered' => $userRoles_r,
				);

				if ( $include_guest ) {
					$userRoles['Anonymous'] = array(
						'guest' => esc_html__( "Guest", 'cbxbusinesshours' ),
					);
				}
			}

			return apply_filters( 'cbxbusinesshours_user_roles', $userRoles, $plain, $include_guest );
		}//end user_roles

		/**
		 * @return array
		 */
		public static function weekdaysDefault() {
			$weekdays_default = array(
				'sunday' => array(
					'start'   => '',
					'end'     => '',
					'message' => '',
				),

				'monday' => array
				(
					'start'   => '',
					'end'     => '',
					'message' => '',
				),

				'tuesday' => array
				(
					'start'   => '',
					'end'     => '',
					'message' => '',
				),

				'wednesday' => array
				(
					'start'   => '',
					'end'     => '',
					'message' => '',
				),

				'thursday' => array
				(
					'start'   => '',
					'end'     => '',
					'message' => '',
				),

				'friday' => array
				(
					'start'   => '',
					'end'     => '',
					'message' => '',
				),

				'saturday' => array
				(
					'start'   => '',
					'end'     => '',
					'message' => '',
				),
			);

			return $weekdays_default;
		}//end weekdaysDefault

		public static function daysOfWeek() {
			$dow = array(
				'sunday'    => array(
					'long'  => esc_html__( 'Sunday', 'cbxbusinesshours' ),
					'short' => esc_html__( 'Sun', 'cbxbusinesshours' ),
				),
				'monday'    => array(
					'long'  => esc_html__( 'Monday', 'cbxbusinesshours' ),
					'short' => esc_html__( 'Mon', 'cbxbusinesshours' ),
				),
				'tuesday'   => array(
					'long'  => esc_html__( 'Tuesday', 'cbxbusinesshours' ),
					'short' => esc_html__( 'Tue', 'cbxbusinesshours' ),
				),
				'wednesday' => array(
					'long'  => esc_html__( 'Wednesday', 'cbxbusinesshours' ),
					'short' => esc_html__( 'Wed', 'cbxbusinesshours' ),
				),
				'thursday'  => array(
					'long'  => esc_html__( 'Thursday', 'cbxbusinesshours' ),
					'short' => esc_html__( 'Thu', 'cbxbusinesshours' ),
				),
				'friday'    => array(
					'long'  => esc_html__( 'Friday', 'cbxbusinesshours' ),
					'short' => esc_html__( 'Fri', 'cbxbusinesshours' ),
				),
				'saturday'  => array(
					'long'  => esc_html__( 'Saturday', 'cbxbusinesshours' ),
					'short' => esc_html__( 'Sat', 'cbxbusinesshours' ),
				),
			);

			return $dow;
		}//end daysOfWeek

		/**
		 * @return array
		 *
		 *  Get week long days with translation
		 */
		public static function getWeekLongDays() {
			$weekdays              = array();
			$weekdays['sunday']    = __( 'Sunday' );
			$weekdays['monday']    = __( 'Monday' );
			$weekdays['tuesday']   = __( 'Tuesday' );
			$weekdays['wednesday'] = __( 'Wednesday' );
			$weekdays['thursday']  = __( 'Thursday' );
			$weekdays['friday']    = __( 'Friday' );
			$weekdays['saturday']  = __( 'Saturday' );

			return $weekdays;
		}//end getWeekLongDays


		/**
		 * @return array
		 *
		 *  Get week long days keys
		 */
		public static function getWeekLongDayKeys() {
			$weekdays = CBXBusinessHoursHelper::getWeekLongDays();

			return array_keys( $weekdays );
		}//end getWeekShortDayKeys


		/**
		 * @return array
		 *
		 *  Get week short days
		 */
		public static function getWeekShortDays() {
			$weekdays        = array();
			$weekdays['sun'] = __( 'Sun' );//0
			$weekdays['mon'] = __( 'Mon' );//1
			$weekdays['tue'] = __( 'Tue' );//2
			$weekdays['wed'] = __( 'Wed' );//3
			$weekdays['thu'] = __( 'Thu' );//4
			$weekdays['fri'] = __( 'Fri' );//5
			$weekdays['sat'] = __( 'Sat' );//6

			return $weekdays;
		}//end getWeekShortDays


		/**
		 * @return array
		 *
		 * Get week short days keys
		 */
		public static function getWeekShortDayKeys() {
			$weekdays = CBXBusinessHoursHelper::getWeekShortDays();

			return array_keys( $weekdays );
		}//end getWeekShortDayKeys

		/**
		 * Find start days by general settings
		 *
		 * @param array $arr
		 * @param null  $start_of_week
		 *
		 * @return array
		 */
		public static function sortDaysWithFirstDayofWeek( $arr = array(), $start_of_week = null ) {


			if ( $start_of_week === null ) {
				$setting              = new CBXBusinessHoursSettings();
				$start_of_week_global = get_option( 'start_of_week' );
				$start_of_week        = intval( $setting->get_option( 'start_of_week', 'cbxbusinesshours_settings', $start_of_week_global ) );
			}

			$sliced_array    = array_slice( $arr, $start_of_week );
			$intersect_array = array_diff( $arr, $sliced_array );
			$arr             = array_merge( $sliced_array, $intersect_array );

			return $arr;
		}//end sortDaysWithFirstDayofWeek

		/**
		 * Convert the store weekdays sorted with the first day of week
		 *
		 * @param $office_weekdays
		 * @param $weekdays
		 *
		 * @return array
		 */
		public static function followWithFirstDayofWeekSorted( $office_weekdays, $weekdays ) {
			$weekdays_sorted = array();
			foreach ( $weekdays as $value ) {
				$weekdays_sorted[] = isset( $office_weekdays[ $value ] ) ? $office_weekdays[ $value ] : array();
			}
			$office_weekdays = $weekdays_sorted;

			return $office_weekdays;
		}//end followWithFirstDayofWeekSorted


		/**
		 * Todays day and time return by date to todays parameter by shortcode
		 *
		 * @param $today
		 * @param $office_weekdays
		 *
		 * @return string
		 *
		 */
		public static function todaysDateCheck( $today, $office_weekdays ) {

			$timestamp = strtotime( $today );
			$today     = strtolower( date( 'l', $timestamp ) );

			$today_start = isset( $office_weekdays[ $today ]['start'] ) ? $office_weekdays[ $today ]['start'] : "";
			$today_end   = isset( $office_weekdays[ $today ]['end'] ) ? $office_weekdays[ $today ]['end'] : "";

			return ucwords( $today ) . " : " . $today_start . " - " . $today_end;
		}

		/**
		 * Check date format for given date [source https://stackoverflow.com/a/19271434]
		 *
		 * @param string $date
		 * @param string $format
		 *
		 * @return bool
		 *
		 */
		public static function validateDate( $date, $format = 'Y-m-d' ) {
			$d = DateTime::createFromFormat( $format, $date );

			return $d && $d->format( $format ) === $date;
		}//end validateDate

		/**
		 * Returns business hours display as html
		 *
		 * @param array $atts
		 * @param int   $post_id
		 *
		 * @return string
		 * @throws Exception
		 */
		public static function business_hours_display( $atts, $post_id = 0 ) {
			$post_id = intval($post_id);

			/*if ( is_admin() ) {
				wp_enqueue_style( 'cbxbusinesshours-admin' );
			} else {
				wp_enqueue_style( 'cbxbusinesshours-public' );
			}*/


			wp_enqueue_style( 'cbxbusinesshours-public' );

			$current_offset = get_option( 'gmt_offset' );
			$tzstring       = get_option( 'timezone_string' );

			$check_zone_info = true;

			// Remove old Etc mappings. Fallback to gmt_offset.
			if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
				$tzstring = '';
			}

			if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists
				$check_zone_info = false;
				if ( 0 == $current_offset ) {
					$tzstring = '+0';
				} elseif ( $current_offset < 0 ) {
					$tzstring = '' . $current_offset;
				} else {
					$tzstring = '+' . $current_offset;
				}
			}


			//three timezone types:  https://stackoverflow.com/questions/17694894/different-timezone-types-on-datetime-object/17711005#17711005
			$date_time_zone = new DateTimeZone( $tzstring );

			global $wp_locale;
			$dow = CBXBusinessHoursHelper::daysOfWeek();

			$date_format   = 'Y-m-d';
			$date_time_now = new DateTime( 'now', $date_time_zone );
			date_time_set( $date_time_now, 0, 0, 0 );

			$start_of_week_global = intval( get_option( 'start_of_week' ) ); //index following sunday as first day of week

			$setting = new CBXBusinessHoursSettings();

			$settings        = get_option( 'cbxbusinesshours_settings', array() );

			//find default values
			$compact_default       = isset( $settings['compact'] ) ? intval( $settings['compact'] ) : 0;
			$time_format_default   = isset( $settings['time_format'] ) ? intval( $settings['time_format'] ) : 24;
			$day_format_default    = isset( $settings['day_format'] ) ? esc_attr( $settings['day_format'] ) : 'long';
			$start_of_week_default = isset( $settings['start_of_week'] ) ? intval( $settings['start_of_week'] ) : $start_of_week_global;
			$today_default         = isset( $settings['today'] ) ? esc_attr( $settings['today'] ) : '';
			$custom_date_default   = isset( $settings['custom_date'] ) ? esc_attr( $settings['custom_date'] ) : '';

			if ( $custom_date_default != '' && ! CBXBusinessHoursHelper::validateDate( $custom_date_default ) ) {
				$custom_date_default = '';
			}

			if ( $custom_date_default != '' && $today_default == 'today' ) {
				$today_default = $custom_date_default;
			}
			//end find default values

			$honor_post_meta = isset($atts['honor_post_meta'])? intval($atts['honor_post_meta']) : 1;

			if ( $post_id > 0 ) {
				$data            = get_post_meta( $post_id, '_cbxbussnesshours_meta', true );
				$office_weekdays = isset( $data['weekdays'] ) ? maybe_unserialize( $data['weekdays'] ) : array();
				$dayexception    = isset( $data['dayexception'] ) ? maybe_unserialize( $data['dayexception'] ) : array();
				$exceptions      = isset( $dayexception['dayexceptions'] ) ? $dayexception['dayexceptions'] : array();
				if($honor_post_meta){
					$atts            = isset( $data['setting'] ) ? maybe_unserialize( $data['setting'] ) : array();
				}
			} else {
				$office_weekdays = $setting->get_option( 'weekdays', 'cbxbusinesshours_hours', array() );
				$dayexception    = $setting->get_option( 'dayexception', 'cbxbusinesshours_hours', array() );
				$exceptions      = isset( $dayexception['dayexceptions'] ) ? $dayexception['dayexceptions'] : array();
			}


			$title       = isset( $atts['title'] ) ? sanitize_text_field( $atts['title'] ) : '';
			$before_text = isset( $atts['before_text'] ) ? sanitize_text_field( $atts['before_text'] ) : '';
			$after_text  = isset( $atts['after_text'] ) ? sanitize_text_field( $atts['after_text'] ) : '';


			$compact       = isset( $atts['compact'] ) ? intval( $atts['compact'] ) : $compact_default;
			$time_format   = isset( $atts['time_format'] ) ? intval( $atts['time_format'] ) : $time_format_default;
			$day_format    = isset( $atts['day_format'] ) ? esc_attr( $atts['day_format'] ) : $day_format_default;
			$start_of_week = isset( $atts['start_of_week'] ) ? intval( $atts['start_of_week'] ) : $start_of_week_default;
			$today         = isset( $atts['today'] ) ? esc_attr( $atts['today'] ) : $today_default;

			//Get the week first day
			$start_of_weekDay = $wp_locale->get_weekday( $start_of_week ); //Day name in format: Sunday


			$start_of_the_week = strtotime( "Last $start_of_weekDay" );

			if ( strtolower( date( 'l' ) ) === strtolower( $start_of_weekDay ) ) {
				$start_of_the_week = strtotime( 'today' );
			}

			$end_of_the_week = $start_of_the_week + ( 60 * 60 * 24 * 7 ) - 1;

			$current_week_start_date = new DateTime( '@' . $start_of_the_week, $date_time_zone );
			$current_week_end_date   = new DateTime( '@' . $end_of_the_week, $date_time_zone );


			$process_today  = false;
			$today_date     = '';
			$today_date_str = '';
			$today_day      = '';


			//if empty then for full week
			if ( $today != '' ) {
				//then for today or for a custom date
				if ( $today == 'today' ) {

					//$today_date     = new DateTime('now', $date_time_zone);
					$today_date = $date_time_now;

					$today_date_str = $today_date->format( $date_format );
					$today_day      = strtolower( $today_date->format( 'l' ) ); //get the day from date
					$process_today  = true;

				} elseif ( CBXBusinessHoursHelper::validateDate( $today ) ) {
					$today_date = DateTime::createFromFormat( $date_format, $today, $date_time_zone );
					date_time_set( $today_date, 0, 0, 0 );

					//if ( $today_date < new DateTime('now', $date_time_zone) ) {
					if ( $today_date < $date_time_now ) {
						return esc_html__( 'The date has already passed', 'cbxbusinesshours' );
					}

					$today_date_str = $today_date->format( $date_format );
					$today_day      = strtolower( $today_date->format( 'l' ) ); //get the day from date
					$process_today  = true;
				} else {
					return esc_html__( 'Invalid Date', 'cbxbusinesshours' );
				}
			}


			if ( is_array( $exceptions ) && sizeof( $exceptions ) > 0 ) {
				foreach ( $exceptions as $exception ) {
					$ex_date = isset( $exception['ex_date'] ) ? esc_attr( $exception['ex_date'] ) : '';
					if ( $ex_date == '' ) {
						continue;
					}

					if ( ! CBXBusinessHoursHelper::validateDate( $ex_date ) ) {
						continue;
					}

					//$ex_day  = date( 'l', strtotime( $ex_date ) ); //get the day from date
					$ex_date = DateTime::createFromFormat( $date_format, $ex_date, $date_time_zone );
					date_time_set( $ex_date, 0, 0, 0 );

					$found_day       = strtolower( $ex_date->format( 'l' ) ); //get the day from date
					$found_day_start = isset( $exception['ex_start'] ) ? $exception['ex_start'] : '';
					$found_day_end   = isset( $exception['ex_end'] ) ? $exception['ex_end'] : '';
					$found_day_msg   = isset( $exception['ex_subject'] ) ? esc_attr( wp_unslash( $exception['ex_subject'] ) ) : '';

					if ( $process_today ) {
						if ( $today_date == $ex_date ) {
							$office_weekdays[ $today_day ]['start']   = $found_day_start;
							$office_weekdays[ $today_day ]['end']     = $found_day_end;
							$office_weekdays[ $today_day ]['message'] = $found_day_msg;

							break;
						}
					} else {
						if ( $ex_date >= $current_week_start_date && $ex_date <= $current_week_end_date ) {
							$office_weekdays[ $found_day ]['start']   = $found_day_start;
							$office_weekdays[ $found_day ]['end']     = $found_day_end;
							$office_weekdays[ $found_day ]['message'] = $found_day_msg;
						}
					}
				}
			}


			if ( $process_today ) {

				$today_start = isset( $office_weekdays[ $today_day ]['start'] ) ? $office_weekdays[ $today_day ]['start'] : '';
				$today_end   = isset( $office_weekdays[ $today_day ]['end'] ) ? $office_weekdays[ $today_day ]['end'] : '';
				$today_msg   = isset( $office_weekdays[ $today_day ]['message'] ) ? esc_attr( wp_unslash( $office_weekdays[ $today_day ]['message'] ) ) : '';

				$today_day_display = isset( $dow[ $today_day ][ $day_format ] ) ? $dow[ $today_day ][ $day_format ] : ucfirst( $today_day );


				$cbxbusinesshours_display_today_closed = '';

				if ( $today_start == '' || $today_end == '' ) {
					$today_start_end                       = esc_html__( 'Closed', 'cbxbusinesshours' );
					$cbxbusinesshours_display_today_closed = 'cbxbusinesshours_display_today_closed';
				} elseif ( $time_format == '12' ) {
					$today_start = date( "g:i a", strtotime( $today_start ) );
					$today_end   = date( "g:i a", strtotime( $today_end ) );

					$today_start_end = $today_start . " - " . $today_end;
				} else {
					$today_start_end = $today_start . " - " . $today_end;
				}

				if ( $today_msg != '' ) {
					$today_start_end .= ' (<span class="cbxbusinesshours_display_msg">' . $today_msg . '</span>)';
				}


				$today_html = '';

				if ( $title != '' ) {
					$today_html .= '<h3 class="cbxbusinesshours-heading">' . $title . '</h3>';
				}

				if ( $before_text != '' ) {
					$today_html .= '<div class="cbxbusinesshours_display_before_text">' . wpautop( $before_text ) . '</div>';
				}

				$today_html .= '<div class="cbxbusinesshours_display cbxbusinesshours_display_today ' . $cbxbusinesshours_display_today_closed . '">';
				$today_html .= $today_day_display . ': ' . $today_start_end;
				$today_html .= '</div>';

				if ( $after_text != '' ) {
					$today_html .= '<div class="cbxbusinesshours_display_after_text">' . wpautop( $after_text ) . '</div>';
				}

				return $today_html;
			}


			//sorting array by start of weekdays

			$weekdays        = CBXBusinessHoursHelper::getWeekLongDayKeys();

			$weekdays_fdw    = CBXBusinessHoursHelper::sortDaysWithFirstDayofWeek( $weekdays, $start_of_week );

			$office_weekdays = CBXBusinessHoursHelper::followWithFirstDayofWeekSorted( $office_weekdays, $weekdays_fdw );


			//starting and ending time from database
			$starting_time = array_column( $office_weekdays, 'start' );
			$ending_time   = array_column( $office_weekdays, 'end' );
			$msg_time      = array_column( $office_weekdays, 'message' );

			$html = '';



			$dow = CBXBusinessHoursHelper::followWithFirstDayofWeekSorted( $dow, $weekdays_fdw );



			$key = $day_format;


			if ( $starting_time && $ending_time ) {

				$opening_short = array();
				for ( $i = 0; $i < 7; $i ++ ) {
					$temp = array( $i );
					for ( $j = $i + 1; $j < 7; $j ++ ) {

						if ( $compact == 0 ) {
							$i = $j - 1;
							$j = 7;
						} elseif ( $starting_time[ $i ] == $starting_time[ $j ] && $ending_time[ $i ] == $ending_time[ $j ] ) {
							$temp[] = $j;
							if ( $j == 6 ) {
								$i = 6;
							}
						} else {
							$i = $j - 1;
							$j = 7;
						}
					}
					$opening_short[] = $temp;
				}
			}

			$today_week_index = CBXBusinessHoursHelper::today_week_index();


			$today_week_index_day       = $weekdays[$today_week_index];
			$today_week_index_day_fdw   = array_search($today_week_index_day, $weekdays_fdw );


			if ( ! empty( $opening_short ) ) {
				$html .= '<table class="cbxbusinesshours_display cbxbusinesshours_display_week">';

				foreach ( $opening_short as $os ) {
					$today_highlight       = ( $os[0] == $today_week_index_day_fdw ) ? true : false;
					$today_highlight_class = ( $today_highlight ) ? 'cbxbusinesshours_is_today' : '';

					$today_msg = '';
					$today_msg .= isset( $msg_time[ $os[0] ] ) ? $msg_time[ $os[0] ] : '';

					$day_text = $dow[ $os[0] ][ $key ];
					if ( count( $os ) > 1 ) {
						$end = array_pop( $os );
						$end = $dow[ $end ][ $key ];

						$day_text = $day_text . ' - ' . $end;
					}

					if ( ! empty( $starting_time[ $os[0] ] ) && ! ( $starting_time[ $os[0] ] == '0:00' && $ending_time[ $os[0] ] == '0:00' ) ) {

						if ( $time_format == 12 ) {
							$hours_text = date( "g:i a", strtotime( $starting_time[ $os[0] ] ) ) . ' - ' .
							              date( "g:i a", strtotime( $ending_time[ $os[0] ] ) );
						} else {
							$hours_text = $starting_time[ $os[0] ] . ' - ' . $ending_time[ $os[0] ];
						}


						if ( $today_msg != '' ) {
							$hours_text .= ' (<span class="cbxbusinesshours_display_msg">' . $today_msg . '</span>)';
						}

					} else {
						$hours_text = '<span class="cbxbusinesshours_display_week_closed">' . esc_html__( 'Closed', 'cbxbusinesshours' ) . '</span>';
						if ( $today_msg != '' ) {
							$hours_text .= '(<span class="cbxbusinesshours_display_msg">' . $today_msg . '</span>)';
						}
					}


					$html .= '<tr class="' . esc_attr( $today_highlight_class ) . '">
                		<td>' . $day_text . ':</td>
                		<td>' . $hours_text . '</td>                
            		</tr>';


				}//end each day

				$html .= '</table>';
			}

			$html_output = '';


			if ( $title != '' ) {
				$html_output .= '<h3 class="cbxbusinesshours-heading">' . $title . '</h3>';
			}

			if ( $before_text != '' ) {
				$html_output .= '<div class="cbxbusinesshours_display_before_text">' . wpautop( $before_text ) . '</div>';
			}

			$html_output .= $html;

			if ( $after_text != '' ) {
				$html_output .= '<div class="cbxbusinesshours_display_after_text">' . wpautop( $after_text ) . '</div>';
			}

			return $html_output;
		}//end business_hours_display

		/**
		 * @param array $value
		 *
		 * @return mixed
		 */
		public static function sanitize_callback_dayexception( $dayexception ) {
			if ( is_array( $dayexception ) && sizeof( $dayexception ) > 0 ) {
				$exceptions = isset( $dayexception['dayexceptions'] ) ? $dayexception['dayexceptions'] : array();


				if ( is_array( $exceptions ) && sizeof( $exceptions ) > 0 ) {
					foreach ( $exceptions as $key => $exception ) {
						$date = $exception['ex_date'];
						if ( $date == '' || ! CBXBusinessHoursHelper::validateDate( $date ) ) {
							unset( $exceptions[ $key ] );
						}
					}

					$dayexception['dayexceptions'] = $exceptions;
				}
			}

			return $dayexception;
		}//end sanitize_callback_dayexception


		/**
		 * Shortcode builder for display and copy paste purpose
		 *
		 * @param array  $general_settings
		 * @param array  $light_settings
		 * @param array  $circular_settings
		 * @param array  $kk_settings
		 * @param string $type
		 *
		 * @return string
		 * @since 1.0.1
		 *
		 */
		public static function shortcode_builder() {
			$settings             = get_option( 'cbxbusinesshours_settings', array() );
			$start_of_week_global = get_option( 'start_of_week' );

			$title         = isset( $settings['title'] ) ? sanitize_text_field( $settings['title'] ) : '';
			$before_text   = isset( $settings['before_text'] ) ? sanitize_text_field( $settings['before_text'] ) : '';
			$after_text    = isset( $settings['after_text'] ) ? sanitize_text_field( $settings['after_text'] ) : '';
			$compact       = isset( $settings['compact'] ) ? intval( $settings['compact'] ) : 0;
			$time_format   = isset( $settings['time_format'] ) ? intval( $settings['time_format'] ) : 24;
			$day_format    = isset( $settings['day_format'] ) ? esc_attr( $settings['day_format'] ) : 'long';
			$start_of_week = isset( $settings['start_of_week'] ) ? intval( $settings['start_of_week'] ) : $start_of_week_global;
			$today         = isset( $settings['today'] ) ? esc_attr( $settings['today'] ) : '';
			$custom_date   = isset( $settings['custom_date'] ) ? esc_attr( $settings['custom_date'] ) : '';

			if ( $custom_date != '' && ! CBXBusinessHoursHelper::validateDate( $custom_date ) ) {
				$custom_date = '';
			}

			if ( $custom_date != '' && $today == 'today' ) {
				$today = $custom_date;
			}

			$attr = array(
				'title'         => $title,
				'before_text'   => $before_text,
				'after_text'    => $after_text,
				'time_format'   => $time_format,
				'day_format'    => $day_format,
				'start_of_week' => $start_of_week,
				'today'         => $today
			);


			$attr = apply_filters( 'cbxbusinesshours_shortcode_builder_attr', $attr );

			$attr_html = '';

			foreach ( $attr as $key => $value ) {
				$attr_html .= ' ' . $key . '="' . $value . '" ';
			}

			return '[cbxbusinesshours ' . $attr_html . ']';
		}//end shortcode_builder

		/**
		 * Add utm params to any url
		 *
		 * @param string $url
		 *
		 * @return string
		 */
		public static function url_utmy($url = ''){
			if($url== '') return $url;

			$url = add_query_arg(array(
				'utm_source'    => 'plgsidebarinfo',
				'utm_medium'    => 'plgsidebar',
				'utm_campaign'  => 'wpfreemium',
			), $url );

			return $url;
		}//end url_utmy

	}//end class CBXBusinessHoursHelper