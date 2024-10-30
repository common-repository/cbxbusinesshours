<?php
	// Prevent direct file access
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class CBXBusinessHours_VCParam_DatePicker {
		/**
		 * Initiator.
		 */
		public function __construct() {
			if ( defined( 'WPB_VC_VERSION' ) && version_compare( WPB_VC_VERSION, 4.8 ) >= 0 ) {
				if ( function_exists( 'vc_add_shortcode_param' ) ) {

					wp_register_style( 'jquery-ui',plugin_dir_url( __FILE__ ) . '../../../assets/css/ui-lightness/jquery-ui.min.css',array(),CBXBUSINESSHOURS_PLUGIN_VERSION );

					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'jquery-ui-core' );
					wp_enqueue_script( 'jquery-ui-datepicker' );
					vc_add_shortcode_param( 'cbxbusinesshoursdate', array( $this, 'cbxbusinesshoursdate_render' ));
				}
			} else {
				if ( function_exists( 'add_shortcode_param' ) ) {

					wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . '../../../assets/css/ui-lightness/jquery-ui.min.css', array(),CBXBUSINESSHOURS_PLUGIN_VERSION );

					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'jquery-ui-core' );
					wp_enqueue_script( 'jquery-ui-datepicker' );
					add_shortcode_param( 'cbxbusinesshoursdate', array( $this, 'cbxbusinesshoursdate_render' ) );
				}
			}
		}

		/**
		 * Date Picker
		 *
		 * @param $settings
		 * @param $value
		 *
		 * @return string
		 */
		public function cbxbusinesshoursdate_render( $settings, $value ) {
			$dependency = '';
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type'] ) ? $settings['type'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';

			$uni    = uniqid( 'datetimepicker-' . wp_rand() );
			$output = '<div id="cbxbusinesshoursdate-' . esc_attr( $uni ) . '" class="cbxbusinesshoursdate"><input placeholder="yyyy-mm-dd" class=" datepicker cbxbusinesshoursdatepicker wpb_vc_param_value ' . esc_attr( $param_name ) . ' ' . esc_attr( $type ) . ' ' . esc_attr( $class ) . '" name="' . esc_attr( $param_name ) . '" style="width:100%;" value="' . esc_attr( $value ) . '" ' . $dependency . '/></div>';

			$output .= '<script type="text/javascript">
 					jQuery( \'.cbxbusinesshoursdatepicker\' ).datepicker({
 						 dateFormat: \'mm/dd/yy\' 
					});
				</script>';

			return $output;
		}

	}// End Class CBXBusinessHours_VCParam_DatePicker

	new CBXBusinessHours_VCParam_DatePicker();