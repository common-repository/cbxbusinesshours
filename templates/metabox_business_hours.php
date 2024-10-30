<?php

	/**
	 * Provide a admin area view for the plugin
	 *
	 * This file is used to markup the admin-facing aspects of the plugin.
	 *
	 * @link       http://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    cbxbusinesshours
	 * @subpackage cbxbusinesshours/templates
	 */


	global $wpdb;

	//$validation_errors_status = false;
	//$validation_errors        = array();
	//$invalid_fields           = array();

	/*if ( is_array( $_SESSION ) && array_key_exists( 'cbxbussnesshours_meta_validation_errors', $_SESSION ) ) {
		$validation_errors_status = true;
		$validation_errors        = $_SESSION['cbxbussnesshours_meta_validation_errors'];
		unset( $_SESSION['cbxbussnesshours_meta_validation_errors'] );

		if ( isset( $validation_errors['invalid_fields'] ) ) {
			$invalid_fields = $validation_errors['invalid_fields'];
		}
	}*/

	$data     = array();


	$name     = '';
	$weekdays = array();

	$data = get_post_meta( $post_id, '_cbxbussnesshours_meta', true );

	/*if ( sizeof( $invalid_fields ) > 0 ) {
		$data = array_merge( $data, $invalid_fields );
	}*/

	$start_of_week_global = get_option( 'start_of_week' );


	$weekdays_data = isset( $data['weekdays'] ) ? maybe_unserialize( $data['weekdays'] ) : array();
	$dayexception  = isset( $data['dayexception'] ) ? maybe_unserialize( $data['dayexception'] ) : array();
	$settings_data = isset( $data['setting'] ) ? maybe_unserialize( $data['setting'] ) : array();

	$start_of_week = isset( $settings_data['start_of_week'] ) ? intval( $settings_data['start_of_week'] ) : $start_of_week_global;
	$compact       = isset( $settings_data['compact'] ) ? intval( $settings_data['compact'] ) : 0;
	$time_format   = isset( $settings_data['time_format'] ) ? intval( $settings_data['time_format'] ) : 24;
	$day_format    = isset( $settings_data['day_format'] ) ? esc_attr( $settings_data['day_format'] ) : 'long';
	$today         = isset( $settings_data['today'] ) ? esc_attr( $settings_data['today'] ) : '';
	$custom_date   = isset( $settings_data['custom_date'] ) ? esc_attr( $settings_data['custom_date'] ) : '';
	$title         = isset( $settings_data['title'] ) ? sanitize_text_field( $settings_data['title'] ) : '';
	$before_text   = isset( $settings_data['before_text'] ) ? sanitize_text_field( $settings_data['before_text'] ) : '';
	$after_text    = isset( $settings_data['after_text'] ) ? sanitize_text_field( $settings_data['after_text'] ) : '';
?>
<div id="cbxbusinesshours_meta_wrapper">
	<h2 class="nav-tab-wrapper">
		<a href="#cbxbusinesshours_settings" class="nav-tab" id="cbxbusinesshours_settings-tab"><?php esc_html_e( 'Setting', 'cbxbusinesshours' ); ?></a>
		<a href="#cbxbusinesshours_hours" class="nav-tab" id="cbxbusinesshours_hours-tab"><?php esc_html_e( 'Week Days & Exceptions', 'cbxbusinesshours' ); ?></a>
		<a href="#cbxbusinesshours_sc_demo" class="nav-tab" id="cbxbusinesshours_sc_demo-tab"><?php esc_html_e( 'Shortcode & Demo', 'cbxbusinesshours' ); ?></a>
	</h2>
	<div class="metabox-holder">
		<div id="cbxbusinesshours_settings" class="cbxbusinesshours_group">
			<div class="cbxbusinesshours_fields">
				<div class="settings_wrapper">
					<h3 class="one_col_field_title"><?php esc_html_e( 'Settings',
							'cbxbusinesshours' ); ?></h3>
					<table class="form-table">
						<tbody>
						<tr class="title">
							<th scope="row">
								<label for="cbxbusinesshours_meta_setting['title]"><?php esc_html_e( "Title","cbxbusinesshourspro" ); ?></label>
							</th>
							<td>
								<input autocomplete="new-password" type="text" class="regular-text regular-date title" id="cbxbusinesshours_meta_setting[title]" name="cbxbusinesshours_meta_setting[title]" value="<?php echo $title; ?>" />
								<p><?php esc_html_e( 'If not empty this title will be used for shortcode/widget title param', 'cbxbusinesshours' ); ?></p>

							</td>
						</tr>
						<tr class="compact">
							<th scope="row">
								<label for="cbxbusinesshours_meta_setting[compact]"><?php esc_html_e( "Default Display Mode",
										"cbxbusinesshourspro" ) ?></label>
							</th>
							<td>
								<select class="regular selecttwo-select"
										name="cbxbusinesshours_meta_setting[compact]"
										data-select2-id="cbxbusinesshours_meta_setting[compact]">
									<?php
										$options = array(
											0 => esc_html__( 'Plain Table', 'cbxbusinesshours' ),
											1 => esc_html__( 'Compact Table', 'cbxbusinesshours' ),
										);

										foreach ( $options as $key => $value ) { ?>
											<option value="<?php echo $key; ?>" <?php selected( $compact,
												$key ); ?>><?php echo $value ?></option>
										<?php } ?>
								</select>
							</td>
						</tr>
						<tr class="time-format">
							<th scope="row">
								<label for="cbxbusinesshours_meta_setting['time_format]"><?php esc_html_e( "Time Format",
										"cbxbusinesshourspro" ); ?></label>
							</th>
							<td>
								<select class="regular selecttwo-select"
										name="cbxbusinesshours_meta_setting[time_format]" id="cbxbusinesshours_meta_setting[time_format]">
									<?php
										$options = array(
											'24' => esc_html__( '24 Hour', 'cbxbusinesshours' ),
											'12' => esc_html__( '12 Hour', 'cbxbusinesshours' ),
										);
										foreach ( $options as $key => $value ) {
											?>
											<option value="<?php echo $key ?>" <?php selected( $time_format,
												$key ); ?>><?php echo $value ?></option>
										<?php } ?>
								</select>
							</td>
						</tr>
						<tr class="day_format">
							<th scope="row">
								<label for="cbxbusinesshours_meta_setting['day_format]"><?php esc_html_e( "Day Name Format",
										"cbxbusinesshourspro" ); ?></label>
							</th>
							<td>
								<select class="regular selecttwo-select"
										name="cbxbusinesshours_meta_setting[day_format]" id="cbxbusinesshours_meta_setting[day_format]">
									<?php
										$options = array(
											'long'  => esc_html__( 'Long Name(Example: Sunday)',
												'cbxbusinesshours' ),
											'short' => esc_html__( 'Short Name(Example: Sun)',
												'cbxbusinesshours' ),
										);

										foreach ( $options as $key => $value ) {
											?>
											<option value="<?php echo $key ?>" <?php selected( $day_format,
												$key ); ?>><?php echo $value ?></option>
										<?php } ?>
								</select>
							</td>
						</tr>
						<tr class="today">
							<th scope="row">
								<label for="cbxbusinesshours_meta_setting['today]"><?php esc_html_e( "Opening Days Display",
										"cbxbusinesshourspro" ); ?></label>
							</th>
							<td>
								<select class="regular selecttwo-select" name="cbxbusinesshours_meta_setting[today]"
										id="cbxbusinesshours_meta_setting[today]">
									<?php
										$options = array(
											''      => esc_html__( 'Current Week(7 days)',
												'cbxbusinesshours' ),
											'today' => esc_html__( 'Today/For Current Date',
												'cbxbusinesshours' ),
										);
										foreach ( $options as $key => $value ) {
											?>
											<option value="<?php echo $key ?>" <?php selected( $today,
												$key ); ?>><?php echo $value ?></option>
										<?php } ?>
								</select>
							</td>
						</tr>
						<tr class="today">
							<th scope="row">
								<label for="cbxbusinesshours_meta_setting['custom_date]"><?php esc_html_e( "Custom Date",
										"cbxbusinesshourspro" ); ?></label>
							</th>
							<td>
								<input autocomplete="new-password" type="text" class="regular-text regular-date datepicker" id="cbxbusinesshours_meta_setting[custom_date]" name="cbxbusinesshours_meta_setting[custom_date]" value="<?php echo $custom_date; ?>" />
								<p><?php esc_html_e( '(Format: yy-mm-dd). If today select, custom date is not empty , custom date value will be used', 'cbxbusinesshours' ); ?></p>

							</td>
						</tr>
						<tr class="start_of_week">
							<th scope="row">
								<label for="cbxbusinesshours_meta_setting['start_of_week]"><?php esc_html_e( "Start of the Week", "cbxbusinesshourspro" ); ?></label>
							</th>
							<td>
								<select class="regular selecttwo-select"
										name="cbxbusinesshours_meta_setting[start_of_week]" id="cbxbusinesshours_meta_setting[start_of_week]">
									<?php
										$week_days = array_values( CBXBusinessHoursHelper::getWeekLongDays() );


										foreach ( $week_days as $key => $value ) {
											?>
											<option value="<?php echo $key ?>" <?php selected( $start_of_week,
												$key ); ?>><?php echo $value ?></option>
										<?php } ?>
								</select>
							</td>
						</tr>
						<tr class="before_text">
							<th scope="row">
								<label for="cbxbusinesshours_meta_setting['before_text]"><?php esc_html_e( "Before Text","cbxbusinesshourspro" ); ?></label>
							</th>
							<td>
								<input autocomplete="new-password" type="text" class="regular-text regular-date before_text" id="cbxbusinesshours_meta_setting[before_text]" name="cbxbusinesshours_meta_setting[before_text]" value="<?php echo $before_text; ?>" />
								<p><?php esc_html_e( 'If not empty this text will be shown before business hours information.', 'cbxbusinesshours' ); ?></p>

							</td>
						</tr>
						<tr class="after_text">
							<th scope="row">
								<label for="cbxbusinesshours_meta_setting['after_text]"><?php esc_html_e( "After Text","cbxbusinesshourspro" ); ?></label>
							</th>
							<td>
								<input autocomplete="new-password" type="text" class="regular-text regular-date after_text" id="cbxbusinesshours_meta_setting[after_text]" name="cbxbusinesshours_meta_setting[after_text]" value="<?php echo $after_text; ?>" />
								<p><?php esc_html_e( 'If not empty this text will be shown after business hours information.', 'cbxbusinesshours' ); ?></p>

							</td>
						</tr>
						</tbody>
					</table>
				</div>

			</div>
		</div>
		<div id="cbxbusinesshours_hours" class="cbxbusinesshours_group">
			<div class="cbxbusinesshours_fields">
				<?php
					$weekdays = CBXBusinessHoursHelper::getWeekLongDays();

					$html = '<div class="weekdays_day_wrapper">';
					$html .= '<h3 class="one_col_field_title">' . esc_html__( 'Week Days(Time in 24 hour format)',
							'cbxbusinesshours' ) . '</h3>';

					foreach ( $weekdays as $key => $days ) {

						if ( ! isset( $weekdays_data[ $key ]['start'] ) ) {
							$weekdays_data[ $key ]['start'] = '';
						}
						if ( ! isset( $weekdays_data[ $key ]['end'] ) ) {
							$weekdays_data[ $key ]['end'] = '';
						}

						if ( ! isset( $weekdays_data[ $key ]['message'] ) ) {
							$weekdays_data[ $key ]['message'] = '';
						}

						$html .= '<div class="weekdays_day">';
						$html .= '<div class="weekdays_day_label">' . $days . ' : </div>';
						$html .= '<div class="weekdays_day_fields">';
						$html .= '<input autocomplete="new-password" type="text"  class="regular-text regular-text-start timepicker timepicker-start input-field" name="cbxbusinesshours_meta_weekdays[' . $key . '][start]" value="' . $weekdays_data[ $key ]['start'] . '" placeholder="' . esc_html__( 'Opening Time',
								'cbxbusinesshours' ) . '"/>';

						$html .= '<input autocomplete="new-password" type="text"  class="regular-text regular-text-end timepicker timepicker-end input-field" name="cbxbusinesshours_meta_weekdays[' . $key . '][end]" value="' . $weekdays_data[ $key ]['end'] . '"  placeholder="' . esc_html__( 'Ending Time',
								'cbxbusinesshours' ) . '"/>';

						$html .= '&nbsp;&nbsp;<a class="button weekdays_day_resetday "><span class="dashicons dashicons-image-rotate" style="margin-right:5px; margin-top: 5px;color: white;"></span>' . esc_html__( 'Reset Day',
								'cbxbusinesshours' ) . '</a>';
						$html .= ' <a class="button weekdays_day_copytoall "><span class="dashicons dashicons-admin-page" style="margin-top: 5px;color: white;"></span>' . esc_html__( 'Copy to All',
								'cbxbusinesshours' ) . '</a>';

						$html .= '<p class="weekdays_day_message"><input type="text" name="cbxbusinesshours_meta_weekdays[' . $key . '][message]" class="regular-text regular-text-message" value="' . $weekdays_data[ $key ]['message'] . '" placeholder="' . esc_html__( 'Custom information', 'cbxbusinesshours' ) . '" /></p>';

						$html .= '</div>';
						$html .= '<div class="clear clearfix"></div></div>';

					}

					$html .= '<p style="text-align: left; padding-left: 120px;"><a class="button weekdays_day_resetall "><span class="dashicons dashicons-image-rotate" style="margin-right:5px; margin-top: 5px;color: white;"></span>' . esc_html__( 'Reset(Empty) All Days',
							'cbxbusinesshours' ) . '</a></p>';

					$html .= '</div>';


					echo $html;
				?>
			</div>
			<?php
				if ( ! is_array( $dayexception ) ) {
					$dayexception = array();
				}
				$ex_last_count = isset( $dayexception['ex_last_count'] ) ? intval( $dayexception['ex_last_count'] ) : 0;
				$exceptions    = isset( $dayexception['dayexceptions'] ) ? $dayexception['dayexceptions'] : array();

			?>
			<div class="cbxbusinesshours_fields">
				<div class="dayexception_wrapper">
					<h3 class="one_col_field_title"><?php esc_html_e( 'Exception Days / Holiday(Time in 24 hour format)',
							'cbxbusinesshours' ); ?></h3>
					<div class="dayexception_items">
						<?php
							if ( is_array( $exceptions ) && sizeof( $exceptions ) > 0 ) {
								foreach ( $exceptions as $key => $exception ) {
									?>
									<p class="dayexception_item">
										<input type="text" class="datepicker"
											   placeholder="<?php esc_html_e( 'Date',
											       'cbxbusinesshours' ); ?>"
											   name="cbxbusinesshours_meta_dayexception[dayexceptions][<?php echo esc_attr( $key ); ?>][ex_date]"
											   value="<?php echo esc_attr( $exception['ex_date'] ) ?>" /> <input type="text" class="timepicker timepicker-start"
																												 placeholder="<?php esc_html_e( 'Start',
											                                                                         'cbxbusinesshours' ) ?>"
																												 name="cbxbusinesshours_meta_dayexception[dayexceptions][<?php echo esc_attr( $key ); ?>][ex_start]"
																												 value="<?php echo esc_attr( $exception['ex_start'] ) ?>" />

										<input type="text" class="timepicker timepicker-end"
											   placeholder="<?php esc_html_e( 'End',
											       'cbxbusinesshours' ); ?>"
											   name="cbxbusinesshours_meta_dayexception[dayexceptions][<?php echo esc_attr( $key ); ?>][ex_end]"
											   value="<?php echo esc_attr( $exception['ex_end'] ) ?>" />

										<input type="text" placeholder="<?php esc_html_e( 'Subject',
											'cbxbusinesshours' ); ?>"
											   name="cbxbusinesshours_meta_dayexception[dayexceptions][<?php echo esc_attr( $key ); ?>][ex_subject]"
											   value="<?php echo esc_attr( $exception['ex_subject'] ) ?>" />

										<a class="remove_exception button"><?php echo '<span class="dashicons dashicons-trash" style="margin-top: 3px;color: red;"></span>' . esc_html__( 'Remove',
													'cbxbusinesshours' ); ?></a>

									</p>

								<?php } // end foreach
							} // end if condition
						?>
					</div>
					<br /> <a class="add_exception button">
                                                <span class="dashicons dashicons-plus-alt"
													  style="margin-top: 5px;color: white;"></span>
						<?php echo esc_html__( 'Add New', 'cbxbusinesshours' ); ?>
					</a> <a class="removeall_exception button"><?php echo '<span class="dashicons dashicons-trash" style="margin-top: 3px;color: red;"></span>' . esc_html__( 'Remove All Exception',
								'cbxbusinesshours' ); ?></a> <input type="hidden" class="dayexception_last_count"
																	   name="cbxbusinesshours_meta_dayexception[ex_last_count]"
																	   value="<?php echo intval( $ex_last_count ); ?>" />
				</div>
			</div>
		</div>
		<div id="cbxbusinesshours_sc_demo" class="cbxbusinesshours_group">
			<?php if ( $post_id > 0 ): ?>
				<h3><?php esc_html_e( 'Shortcode', 'cbxbusinesshours' ); ?></h3>
				<?php
				$sc = '';
				?>
				<textarea readonly="" rows="5" cols="55" class="regular-text cbxbusinesshours_demo_copy" id="cbxbusinesshours_sc_demo[shortcode_demo]" name="shortcode_demo">[cbxbusinesshours  post_id="<?php echo $post_id; ?>" ]</textarea>
				<a data-target-cp="#cbxbusinesshours_sc_demo\[shortcode_demo\]" class="shortcode_demo_btn" href="#"><?php esc_html_e('Click to copy shortcode', 'cbxbusinesshours'); ?></a>
				<p class="description"><?php esc_html_e( 'Shortcode and demo based on post setting', 'cbxbusinesshours' ); ?></p>
				<h3><?php esc_html_e( 'Demo', 'cbxbusinesshours' ); ?></h3>
				<div class="shortcode_demo_wrap"><?php echo do_shortcode( '[cbxbusinesshours  post_id="' . $post_id . '" ]' ); ?></div>
			<?php else: ?>
				<p><?php esc_html_e( 'Please save or create the store first to check demo and shortcode', 'cbxbusinesshours' ); ?></p>
			<?php endif; ?>


		</div>
	</div>
	<br />
</div>
