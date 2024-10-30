<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class CBXBusinessHoursFrontWidget
 */
class CBXBusinessHoursFrontWidget extends WP_Widget {
	/**
	 * CBXBusinessHoursFrontWidget constructor.
	 */
	public function __construct() {
		parent::__construct( 'cbxbusinesshours',
			esc_html__( 'CBX Business Opening Hours', 'cbxbusinesshours' ),
			array(
				'classname'   => 'cbxbusinesshours_display_wrap  cbxbusinesshours_display_wrap_widget',
				'description' => esc_html__( 'CBX business opening hours display widget', 'cbxbusinesshours' ),
			) );
	}//end constructor


	/**
	 * Update Widget
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$start_of_week_global  = get_option( 'start_of_week' );

		$instance                  = $old_instance;
		$instance['title']         = isset( $new_instance['title'] ) ? $new_instance['title'] : '';

		$instance['post_id']       = isset( $new_instance['post_id'] ) ? intval( $new_instance['post_id'] ) : 0;
		$instance['honor_post_meta']       = isset( $new_instance['honor_post_meta'] ) ? intval( $new_instance['honor_post_meta'] ) : 1;
		$instance['compact']       = isset( $new_instance['compact'] ) ? intval( $new_instance['compact'] ) : 0;
		$instance['time_format']   = isset( $new_instance['time_format'] ) ? intval( $new_instance['time_format'] ) : 24;
		$instance['day_format']    = isset( $new_instance['day_format'] ) ? sanitize_text_field( $new_instance['day_format'] ) : 'long';
		$instance['start_of_week'] = isset( $new_instance['start_of_week'] ) ? intval( $new_instance['start_of_week'] ) : $start_of_week_global;


		$instance['before_text'] = isset( $new_instance['before_text'] ) ? sanitize_text_field( $new_instance['before_text'] ) : '';
		$instance['after_text']  = isset( $new_instance['after_text'] ) ? sanitize_text_field( $new_instance['after_text'] ) : '';

		$instance['today']       = isset( $new_instance['today'] ) ? sanitize_text_field( $new_instance['today'] ) : 'week';
		$instance['custom_date'] = isset( $new_instance['custom_date'] ) ? sanitize_text_field($new_instance['custom_date']) : ''; //y-m-d

		if ( $instance['custom_date'] != '' && ! CBXBusinessHoursHelper::validateDate( $instance['custom_date'] ) ) {
			$instance['custom_date'] = '';
		}


		return $instance;
	}// end of update method

	/**
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$start_of_week_global  = get_option( 'start_of_week' );

		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Business Opening Hours', 'cbxbusinesshours' );

		$post_id         = isset( $instance['post_id'] ) ? intval( $instance['post_id'] ) : 0;
		$honor_post_meta = isset( $instance['honor_post_meta'] ) ? intval( $instance['honor_post_meta'] ) : 1;
		$compact         = isset( $instance['compact'] ) ? intval( $instance['compact'] ) : 0;
		$time_format     = isset( $instance['time_format'] ) ? intval( $instance['time_format'] ) : 24;
		$day_format      = isset( $instance['day_format'] ) ? $instance['day_format'] : 'long';
		$today           = isset( $instance['today'] ) ? $instance['today'] : 'week';
		$custom_date     = isset( $instance['custom_date'] ) ? $instance['custom_date'] : '';
		$start_of_week   = isset( $instance['start_of_week'] ) ? intval( $instance['start_of_week'] ) : $start_of_week_global;

		if ( $custom_date != '' && ! CBXBusinessHoursHelper::validateDate( $custom_date ) ) {
			$custom_date = '';
		}

		$before_text = isset( $instance['before_text'] ) ? sanitize_text_field( $instance['before_text'] ) : '';
		$after_text  = isset( $instance['after_text'] ) ? sanitize_text_field( $instance['after_text'] ) : '';

		?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ?>"><?php echo esc_html__( 'Title:', 'cbxbusinesshours' ) ?></label>
            <input type="text" class="" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ) ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ?>"
                   value="<?php echo $title; ?>">
        </p>
        <div style="background-color: #f1f1f1; padding: 10px 5px;">
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ) ?>"><?php echo esc_html__( 'Post ID:', 'cbxbusinesshours' ) ?></label>
			<input type="text" class="" name="<?php echo esc_attr( $this->get_field_name( 'post_id' ) ) ?>"
			       id="<?php echo esc_attr( $this->get_field_id( 'post_id' ) ) ?>"
			       value="<?php echo $post_id; ?>">
		</p>
		<p>
			<?php esc_html_e( 'To display business hours from post meta put post id, if post id  is set below params will be ignored. Post ID 0 means it will display from global setting and below params.', 'cbxbusinesshours' ); ?>
		</p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'honor_post_meta' ) ) ?>"><?php echo esc_html__( 'Honor Post Meta:', 'cbxbusinesshours' ) ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'honor_post_meta' ) ); ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'honor_post_meta' ) ); ?>">
				<?php

				$honor_post_meta_options = array(
					1 => esc_html__( 'Yes', 'cbxbusinesshours' ),
					0 => esc_html__( 'No', 'cbxbusinesshours' ),
				);

				foreach ( $honor_post_meta_options as $key => $value ) {
					?>
                    <option value="<?php echo $key; ?>" <?php selected( $honor_post_meta, $key ) ?> > <?php echo esc_attr($value); ?> </option>
				<?php } ?>
            </select>
        </p>
        <p>
			<?php esc_html_e( 'If post id greater than 0 or valid, then other widget params ignored and post meta values are used. So, choose no will help to display custom as widget settings.', 'cbxbusinesshours' ); ?>
        </p>
        </div>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'compact' ) ) ?>"><?php echo esc_html__( 'Display Mode:', 'cbxbusinesshours' ) ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'compact' ) ); ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'compact' ) ); ?>">
				<?php

				$compact_options = array(
					0 => esc_html__( 'Plain Table', 'cbxbusinesshours' ),
					1 => esc_html__( 'Compact Table', 'cbxbusinesshours' ),
				);

				foreach ( $compact_options as $key => $value ) {
					?>
                    <option value="<?php echo $key; ?>" <?php selected( $compact, $key ) ?> > <?php echo $value; ?> </option>
				<?php } ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'time_format' ) ) ?>"><?php echo esc_html__( 'Time Format:', 'cbxbusinesshours' ) ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'time_format' ) ); ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'time_format' ) ); ?>">
				<?php
				$time_format_options = array(
					24 => esc_html__( '24 hours', 'cbxbusinesshours' ),
					12 => esc_html__( '12 hours', 'cbxbusinesshours' ),
				);


				foreach ( $time_format_options as $key => $value ) {
					?>
                    <option value="<?php echo $key; ?>" <?php selected( $time_format, $key ) ?> > <?php echo $value; ?> </option>
				<?php } ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'day_format' ) ) ?>"><?php echo esc_html__( 'Day Name Format:', 'cbxbusinesshours' ) ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'day_format' ) ); ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'day_format' ) ); ?>">
				<?php
				$day_format_options = array(
					'long'  => esc_html__( 'Long', 'cbxbusinesshours' ),
					'short' => esc_html__( 'Short', 'cbxbusinesshours' ),
				);


				foreach ( $day_format_options as $key => $value ) {
					?>
                    <option value="<?php echo $key; ?>" <?php selected( $day_format, $key ) ?> > <?php echo $value; ?> </option>
				<?php } ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'today' ) ) ?>"><?php echo esc_html__( 'Opening Days Display:', 'cbxbusinesshours' ) ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'today' ) ); ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'today' ) ); ?>">
				<?php
				$today_options = array(
					'week'  => esc_html__( 'Current Week(7 days)', 'cbxbusinesshours' ),
					'today' => esc_html__( 'Today/For Current Date', 'cbxbusinesshours' ),
				);


				foreach ( $today_options as $key => $value ) {
					?>
                    <option value="<?php echo $key; ?>" <?php selected( $today, $key ) ?> > <?php echo $value; ?> </option>
				<?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'custom_date' ) ) ?>"><?php echo esc_html__( 'Custom Date:', 'cbxbusinesshours' ) ?></label>
            <input type="text" autocomplete="new-password" class="cbxbusinesshours_custom_date" name="<?php echo esc_attr( $this->get_field_name( 'custom_date' ) ) ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'custom_date' ) ) ?>"
                   value="<?php echo $custom_date; ?>">

        </p>
        <p><?php echo sprintf( esc_html__( 'Date format: %s', 'cbxbusinesshours' ), 'Y-m-d' ); ?></p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'start_of_week' ) ) ?>"><?php echo esc_html__( 'Start of the Week:', 'cbxbusinesshours' ) ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'start_of_week' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'start_of_week' ) ) ?>">
				<?php
				$week_days = array_values( CBXBusinessHoursHelper::getWeekLongDays() );


				foreach ( $week_days as $key => $value ) {
					?>
                    <option value="<?php echo $key; ?>" <?php selected( $start_of_week, $key ) ?> > <?php echo $value; ?> </option>
				<?php } ?>
            </select>
        </p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'before_text' ) ) ?>"><?php echo esc_html__( 'Before Text:', 'cbxbusinesshours' ) ?></label>
			<input type="text" class="" name="<?php echo esc_attr( $this->get_field_name( 'before_text' ) ) ?>"
			       id="<?php echo esc_attr( $this->get_field_id( 'before_text' ) ) ?>"
			       value="<?php echo $before_text; ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'after_text' ) ) ?>"><?php echo esc_html__( 'After Text:', 'cbxbusinesshours' ) ?></label>
			<input type="text" class="" name="<?php echo esc_attr( $this->get_field_name( 'after_text' ) ) ?>"
			       id="<?php echo esc_attr( $this->get_field_id( 'after_text' ) ) ?>"
			       value="<?php echo $after_text; ?>">
		</p>


		<?php
	}//end of form method

	/**
	 * Widget Display
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @throws Exception
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Business Opening Hours', 'cbxbusinesshours' );
		if ( isset( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}


		if ( $instance['today'] == 'week' ) {
			$instance['today'] = '';
		}

		if ( $instance['today'] == 'today' && $instance['custom_date'] != '' && CBXBusinessHoursHelper::validateDate( $instance['custom_date'] ) ) {
			$instance['today'] = esc_attr( $instance['custom_date'] );
		}

		$post_id     = isset( $instance['post_id'] ) ? intval( $instance['post_id'] ) : 0;


		$instance['title'] = '';
		echo CBXBusinessHoursHelper::business_hours_display( $instance, $post_id );

		echo $args['after_widget'];
	}//end widget
}//end class CBXBusinessHoursFrontWidget