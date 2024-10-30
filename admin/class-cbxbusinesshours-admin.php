<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXBusinessHours
 * @subpackage CBXBusinessHours/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CBXBusinessHours
 * @subpackage CBXBusinessHours/admin
 * @author     Codeboxr <info@codeboxr.com>
 */
class CbxBusinessHours_Admin
{

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
     * @param  string  $plugin_name  The name of this plugin.
     * @param  string  $version  The version of this plugin.
     *
     * @since    1.0.0
     *
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        if ( defined( 'WP_DEBUG' ) ) {
            $this->version = current_time( 'timestamp' ); //for development time only
        }

        //get plugin base file name
        $this->plugin_basename = plugin_basename(plugin_dir_path(__DIR__).$plugin_name.'.php');

        $this->setting = new CBXBusinessHoursSettings();
    }

    public function settings_init()
    {
        $this->setting->set_sections($this->get_settings_sections());
        $this->setting->set_fields($this->get_settings_field());
        $this->setting->admin_init();

    }// end of settings_init method

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook = '')
    {
        global $post_type, $post;
        $settings = $this->setting;
        $page     = isset($_GET['page']) ? esc_attr(wp_unslash($_GET['page'])) : '';
        $suffix   = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

        $post_types = $settings->get_option('post_types', 'cbxbusinesshours_integration', array());

        //wp_register_style( 'cbxbusinesshours-admin', plugin_dir_url( __FILE__ ) . '../assets/css/cbxbusinesshours-admin.css', array(), $this->version, 'all' );


        if ($page == 'cbxbusinesshours') {
            wp_register_style('select2', plugin_dir_url(__FILE__).'../assets/select2/css/select2.min.css', array(), $this->version);
            wp_register_style('jquery-timepicker', plugin_dir_url(__FILE__).'../assets/css/jquery.timepicker.min.css', array(), $this->version, 'all');
            wp_register_style('jquery-ui', plugin_dir_url(__FILE__).'../assets/css/jquery-ui.css', array(), $this->version, 'all');
            wp_register_style('cbxbusinesshours-settings',
                plugin_dir_url(__FILE__).'../assets/css/cbxbusinesshours-settings.css',
                array(
                    'select2',
                    'jquery-timepicker',
                    'jquery-ui',
                    'wp-color-picker',
                ),
                $this->version,
                'all');

            wp_enqueue_style('select2');
            wp_enqueue_style('jquery-timepicker');
            wp_enqueue_style('jquery-ui');
            wp_enqueue_style('wp-color-picker');

            wp_enqueue_style('cbxbusinesshours-settings');
        }

        //add new or edit mode
        if (($hook == 'post.php' || $hook == 'post-new.php') && in_array($post_type, $post_types)) {
            wp_register_style('select2', plugin_dir_url(__FILE__).'../assets/select2/css/select2.min.css', array(), $this->version);
            wp_register_style('jquery-timepicker', plugin_dir_url(__FILE__).'../assets/css/jquery.timepicker.min.css', array(), $this->version, 'all');
            wp_register_style('jquery-ui', plugin_dir_url(__FILE__).'../assets/css/jquery-ui.css', array(), $this->version, 'all');

            wp_register_style('cbxbusinesshours-meta',
                plugin_dir_url(__FILE__).'../assets/css/cbxbusinesshours-meta.css',
                array(
                    'select2',
                    'jquery-timepicker',
                    'jquery-ui',
                ),
                CBXBUSINESSHOURS_PLUGIN_VERSION,
                'all');


            wp_enqueue_style('select2');
            wp_enqueue_style('jquery-timepicker');
            wp_enqueue_style('jquery-ui');
            wp_enqueue_style('cbxbusinesshours-meta');

            wp_register_style('cbxbusinesshours-public', plugin_dir_url(__FILE__).'../assets/css/cbxbusinesshours-public.css', array(), $this->version, 'all');
            wp_enqueue_style('cbxbusinesshours-public');


        }

        //adding branding css
        if ($page == 'cbxbusinesshours' || $page == 'cbxbusinesshourspro') {
            wp_register_style('cbxbusinesshours-branding',
                plugin_dir_url(__FILE__).'../assets/css/cbxbusinesshours-branding.css',
                array(),
                $this->version);
            wp_enqueue_style('cbxbusinesshours-branding');
        }
    }//end enqueue_styles

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook = '')
    {
        global $post_type, $post;
        $settings       = $this->setting;
        $page           = isset($_GET['page']) ? esc_attr(wp_unslash($_GET['page'])) : '';
        $current_screen = get_current_screen();

        $post_types = $settings->get_option('post_types', 'cbxbusinesshours_integration', array());


        $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';


        if ($page == 'cbxbusinesshours') {
            wp_register_script('select2', plugin_dir_url(__FILE__).'../assets/select2/js/select2.min.js', array('jquery'), $this->version, true);
            wp_register_script('jquery-timepicker', plugin_dir_url(__FILE__).'../assets/js/jquery.timepicker.min.js', array('jquery'), $this->version, true);
            wp_register_script('cbxbusinesshours-settings',
                plugin_dir_url(__FILE__).'../assets/js/cbxbusinesshours-settings.js',
                array(
                    'jquery',
                    'select2',
                    'jquery-timepicker',
                    'jquery-ui-datepicker',
                    'wp-color-picker',
                ),
                $this->version,
                true);


            // Localize the script with translation
            $translation_placeholder = apply_filters('cbxbusinesshours_setting_js_vars',
                array(
                    'remove'       => esc_html__('Remove', 'cbxbusinesshours'),
                    'date'         => esc_html__('Date', 'cbxbusinesshours'),
                    'start'        => esc_html__('Start', 'cbxbusinesshours'),
                    'end'          => esc_html__('End', 'cbxbusinesshours'),
                    'subject'      => esc_html__('Subject', 'cbxbusinesshours'),
                    //'test' => '<h2>##store_id##I have a pen</h2>'
                    //'hoursformat' => $hoursformat
                    'copy_success' => esc_html__('Shortcode copied to clipboard', 'cbxbusinesshours'),
                    'copy_fail'    => esc_html__('Oops, unable to copy', 'cbxbusinesshours'),
                    'please_select'    => esc_html__( 'Please Select', 'cbxbusinesshours' ),
                    'upload_title'     => esc_html__( 'Window Title', 'cbxbusinesshours' ),
                ));

            wp_localize_script('cbxbusinesshours-settings', 'cbxbusinesshours_setting', $translation_placeholder);


            wp_enqueue_script('jquery');
            wp_enqueue_media();
            wp_enqueue_script('select2');
            wp_enqueue_script('jquery-timepicker');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('cbxbusinesshours-settings');
        }

        if ((isset($current_screen->id) && $current_screen->id == 'widgets') || (isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'cbxbusinesshours_dashboard_widget')) {
            wp_register_script('cbxbusinesshours-widgets',
                plugin_dir_url(__FILE__).'../assets/js/cbxbusinesshours-widgets.js',
                array(
                    'jquery',
                    'jquery-ui-datepicker',
                ),
                $this->version,
                true);

            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('cbxbusinesshours-widgets');

            wp_register_style('jquery-ui', plugin_dir_url(__FILE__).'../assets/css/jquery-ui.css', array(), $this->version, 'all');
            wp_enqueue_style('jquery-ui');
        }

        //add new or edit mode
        if (($hook == 'post.php' || $hook == 'post-new.php') && in_array($post_type, $post_types)) {
            wp_register_script('select2', plugin_dir_url(__FILE__).'../assets/select2/js/select2.min.js', array('jquery'), $this->version, true);
            wp_register_script('jquery-timepicker', plugin_dir_url(__FILE__).'../assets/js/jquery.timepicker.min.js', array('jquery'), $this->version, true);

            wp_register_script('cbxbusinesshours-meta',
                plugin_dir_url(__FILE__).'../assets/js/cbxbusinesshours-meta..js',
                array(
                    'jquery',
                    'select2',
                    'jquery-timepicker',
                    'jquery-ui-datepicker',
                    //'wp-color-picker'
                ),
                $this->version, true);

            // Localize the script with translation
            $translation_placeholder = apply_filters('cbxbusinesshours_meta_js_vars',
                array(
                    'remove'       => esc_html__('Remove', 'cbxbusinesshours'),
                    'date'         => esc_html__('Date', 'cbxbusinesshours'),
                    'start'        => esc_html__('Start', 'cbxbusinesshours'),
                    'end'          => esc_html__('End', 'cbxbusinesshours'),
                    'subject'      => esc_html__('Subject', 'cbxbusinesshours'),
                    'copy_success' => esc_html__('Shortcode copied to clipboard', 'cbxbusinesshours'),
                    'copy_fail'    => esc_html__('Oops, unable to copy', 'cbxbusinesshours'),
                    //'hoursformat' => $hoursformat
                ));

            wp_localize_script('cbxbusinesshours-meta', 'cbxbusinesshours_meta', $translation_placeholder);

            wp_enqueue_script('jquery');
            //wp_enqueue_media();
            wp_enqueue_script('select2');
            wp_enqueue_script('jquery-timepicker');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('cbxbusinesshours-meta');
        }

	    //header scroll
	    wp_register_script( 'cbxbusinesshours-scroll', plugins_url( '../assets/js/cbxbusinesshours-scroll.js', __FILE__ ), array( 'jquery' ),
		    $this->version,true );
	    if ( $page == 'cbxbusinesshours') {
		    wp_enqueue_script( 'jquery' );
		    wp_enqueue_script( 'cbxbusinesshours-scroll' );
	    }
    }//end enqueue_scripts

    /**
     * This admin_menu method will create options page
     */
    public function admin_menu()
    {
        add_options_page(esc_html__('Office Opening & Business Hours', 'cbxbusinesshours'),
            esc_html__('Office Business Hours', 'cbxbusinesshours'),
            'manage_options',
            'cbxbusinesshours',
            array(
                $this,
                'display_plugin_admin_settings',
            ));
    }// end of admin_menu method

    /**
     * This callback method
     */
    public function display_plugin_admin_settings()
    {
        $setting = $this->setting;

	    $doc = isset($_REQUEST['cbxbusinesshours-help-support'])? absint($_REQUEST['cbxbusinesshours-help-support']) : 0;

	    if($doc){
		    echo cbxbusinesshours_get_template_html('admin/dashboard.php');
	    }
	    else{
		    echo cbxbusinesshours_get_template_html('admin/settings-display.php', array('ref' => $this, 'setting' => $setting));
	    }

    }// end of CBXBusinessHours_options_page_data method

    public function get_settings_sections()
    {
        $sections = array(
            array(
                'id'    => 'cbxbusinesshours_settings',
                'title' => esc_html__('Settings', 'cbxbusinesshours'),
            ),
            array(
                'id'    => 'cbxbusinesshours_hours',
                'title' => esc_html__('Manage Hours', 'cbxbusinesshours'),
            ),
            array(
                'id'    => 'cbxbusinesshours_sc_demo',
                'title' => esc_html__('Shortcode & Demo', 'cbxbusinesshours'),
            ),
            array(
                'id'    => 'cbxbusinesshours_integration',
                'title' => esc_html__('Post Type Integration', 'cbxbusinesshours'),
            ),
        );

        return apply_filters('cbxbusinesshours_setting_sections', $sections);
    }// end of get_settings_sections method

    public function get_settings_field()
    {

        $weekdays_default     = CBXBusinessHoursHelper::weekdaysDefault();
        $start_of_week_global = get_option('start_of_week');

        $user_roles_no_guest = CBXBusinessHoursHelper::user_roles(true, false);

        $posts_definition = CBXBusinessHoursHelper::get_formatted_posttype_multicheckbox(CBXBusinessHoursHelper::post_types());

        $settings_builtin_fields = array(
            'cbxbusinesshours_hours'       => array(
                array(
                    'name'    => 'weekdays',
                    'label'   => esc_html__('Week Days', 'cbxbusinesshours'),
                    'type'    => 'weekdays',
                    'default' => $weekdays_default,
                ),
                array(
                    'name'              => 'dayexception',
                    'label'             => esc_html__('Exception Days / Holiday', 'cbxbusinesshours'),
                    'type'              => 'dayexception',
                    'sanitize_callback' => array('CBXBusinessHoursHelper', 'sanitize_callback_dayexception'),
                ),

            ),
            'cbxbusinesshours_settings'    => array(
                array(
                    'name'    => 'compact',
                    'label'   => esc_html__('Default Display Mode', 'cbxbusinesshours'),
                    'type'    => 'select',
                    'options' => array(
                        0 => esc_html__('Plain Table', 'cbxbusinesshours'),
                        1 => esc_html__('Compact Table', 'cbxbusinesshours'),
                    ),
                    'default' => 0,
                ),
                array(
                    'name'    => 'time_format',
                    'label'   => esc_html__('Time Format', 'cbxbusinesshours'),
                    'type'    => 'select',
                    'options' => array(
                        '24' => esc_html__('24 Hour', 'cbxbusinesshours'),
                        '12' => esc_html__('12 Hour', 'cbxbusinesshours'),
                    ),
                    'default' => 24,
                ),
                array(
                    'name'    => 'day_format',
                    'label'   => esc_html__('Day Name Format', 'cbxbusinesshours'),
                    'type'    => 'select',
                    'options' => array(
                        'long'  => esc_html__('Long Name(Example: Sunday)', 'cbxbusinesshours'),
                        'short' => esc_html__('Short Name(Example: Sun)', 'cbxbusinesshours'),
                    ),
                    'default' => 'long',
                ),
                array(
                    'name'    => 'today',
                    'label'   => esc_html__('Opening Days Display', 'cbxbusinesshours'),
                    'type'    => 'select',
                    'options' => array(
                        ''      => esc_html__('Current Week(7 days)', 'cbxbusinesshours'),
                        'today' => esc_html__('Today/For Current Date', 'cbxbusinesshours'),
                    ),
                    'default' => '',
                ),

                array(
                    'name'    => 'custom_date',
                    'label'   => esc_html__('Custom Date', 'cbxbusinesshours'),
                    'desc'    => esc_html__('(Format: yyyy-mm-dd). If today select, custom date is not empty , custom date value will be used', 'cbxbusinesshours'),
                    'type'    => 'date',
                    'default' => '',
                ),
                array(
                    'name'    => 'start_of_week',
                    'label'   => esc_html__('Start of the Week', 'cbxbusinesshours'),
                    'type'    => 'select',
                    'options' => array(
                        0 => esc_html__('Sunday', 'cbxbusinesshours'),
                        1 => esc_html__('Monday', 'cbxbusinesshours'),
                        2 => esc_html__('Tuesday', 'cbxbusinesshours'),
                        3 => esc_html__('Wednesday', 'cbxbusinesshours'),
                        4 => esc_html__('Thursday', 'cbxbusinesshours'),
                        5 => esc_html__('Friday', 'cbxbusinesshours'),
                        6 => esc_html__('Saturday', 'cbxbusinesshours'),
                    ),
                    'default' => intval($start_of_week_global),
                ),
                array(
                    'name'    => 'dashboard_widget',
                    'label'   => esc_html__('Enable Dashboard Widget', 'cbxbusinesshours'),
                    'type'    => 'select',
                    'options' => array(
                        1 => esc_html__('Enable', 'cbxbusinesshours'),
                        0 => esc_html__('Disable', 'cbxbusinesshours'),
                    ),
                    'default' => 1,
                ),

                array(
                    'name'    => 'dashboard_widget_roles',
                    'label'   => esc_html__('Show Dashboard Widget for user role', 'cbxbusinesshours'),
                    'type'    => 'multiselect',
                    'options' => $user_roles_no_guest,
                    'default' => array('administrator'),
                ),
            ),
            'cbxbusinesshours_sc_demo'     => array(
                array(
                    'name'    => 'shortcode_demo',
                    'label'   => esc_html__('Shortcode & Demo', 'cbxbusinesshours'),
                    'desc'    => esc_html__('Shortcode and demo based on default setting, please save once to check change.', 'cbxbusinesshours'),
                    'type'    => 'shortcode',
                    'class'   => 'cbxbusinesshours_demo_copy',
                    'default' => CBXBusinessHoursHelper::shortcode_builder(),
                ),

            ),
            'cbxbusinesshours_integration' => array(
                array(
                    'name'     => 'post_types',
                    'label'    => esc_html__('Post Type(s) Selection', 'cbxbusinesshours'),
                    'desc'     => esc_html__('Select post type integration for business hours as meta box', 'cbxbusinesshours'),
                    'type'     => 'multiselect',
                    'optgroup' => 1,
                    'default'  => array(),
                    'options'  => $posts_definition,
                ),
                array(
                    'name'    => 'auto_integration',
                    'label'   => esc_html__('Auto Integration', 'cbxbusinesshours'),
                    'desc'    => esc_html__('Displays the business hours before or after a post content. Works for any post types detailed/single view.', 'cbxbusinesshours'),
                    'type'    => 'select',
                    'default' => 'disable',
                    'options' => array(
                        'before_content' => esc_html__('Before Content', 'cbxbusinesshours'),
                        'after_content'  => esc_html__('After Content', 'cbxbusinesshours'),
                        'disable'        => esc_html__('Disable Auto Integration', 'cbxbusinesshours'),
                    ),
                ),
                array(
                    'name'     => 'auto_post_types',
                    'label'    => esc_html__('Auto Integration Post Type(s)', 'cbxbusinesshours'),
                    'desc'     => esc_html__('Select post type integration.', 'cbxbusinesshours'),
                    'type'     => 'multiselect',
                    'optgroup' => 1,
                    'default'  => array(),
                    'options'  => $posts_definition,
                ),
            )
        );

        $settings_fields = array(); //final setting array that will be passed to different filters

        $sections = $this->get_settings_sections();


        foreach ($sections as $section) {
            if ( ! isset($settings_builtin_fields[$section['id']])) {
                $settings_builtin_fields[$section['id']] = array();
            }
        }

        foreach ($sections as $section) {
            $settings_fields[$section['id']] = apply_filters('cbxbusinesshours_global_'.$section['id'].'_fields',
                $settings_builtin_fields[$section['id']]);
        }

        $settings_fields = apply_filters('cbxbusinesshours_global_fields', $settings_fields); //final filter if need

        return $settings_fields;
    }//end get_settings_field

    /**
     *  Gutenberg block init
     */
    public function gutenberg_blocks_init()
    {
        if ( ! function_exists('register_block_type')) {
            return;
        }

        $start_of_week_global = get_option('start_of_week');

        wp_register_script('cbxbusinesshours-block',
            plugin_dir_url(__FILE__).'../assets/js/cbxbusinesshours-block.js',
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
            ),
            filemtime(plugin_dir_path(__FILE__).'../assets/js/cbxbusinesshours-block.js'));

        $js_vars = apply_filters('cbxbusinesshours_block_js_vars',
            array(
                'block_title'      => esc_html__('CBX Business Hours', 'cbxbusinesshours'),
                'block_category'   => 'codeboxr',
                'block_icon'       => 'dashicons-clock',
                'general_settings' => array(
                    'heading'                 => esc_html__('General Setting', 'cbxbusinesshours'),
                    'title'                   => esc_html__('Title', 'cbxbusinesshours'),
                    'post_id'                 => esc_html__('Post ID', 'cbxbusinesshours'),
                    'post_id_note'            => esc_html__('To display business hours from post meta put post id, if post id  is set below params will be ignored. Post ID 0 means it will display from global setting and below params.',
                        'cbxbusinesshours'),
                    'honor_post_meta'         => esc_html__('Honor Post Meta', 'cbxbusinesshours'),
                    'honor_post_meta_note'    => esc_html__('If post id greater than 0 or valid, then other widget params ignored and post meta values are used. So, choose no will help to display custom as widget settings.', 'cbxbusinesshours'),
                    'honor_post_meta_options' => array(
                        array('label' => esc_html__('Yes', 'cbxbusinesshours'), 'value' => 1),
                        array('label' => esc_html__('No', 'cbxbusinesshours'), 'value' => 0),
                    ),
                    'compact_options'         => array(
                        array('label' => esc_html__('Plain Table', 'cbxbusinesshours'), 'value' => 0),
                        array('label' => esc_html__('Compact Table', 'cbxbusinesshours'), 'value' => 1),
                    ),
                    'time_format'             => esc_html__('Time Format', 'cbxbusinesshours'),
                    'time_format_options'     => array(
                        array('label' => esc_html__('24 hours', 'cbxbusinesshours'), 'value' => 24),
                        array('label' => esc_html__('12 hours', 'cbxbusinesshours'), 'value' => 12),
                    ),
                    'day_format'              => esc_html__('Day Name Format', 'cbxbusinesshours'),
                    'day_format_options'      => array(
                        array('label' => esc_html__('Long', 'cbxbusinesshours'), 'value' => 'long'),
                        array('label' => esc_html__('Short', 'cbxbusinesshours'), 'value' => 'short'),
                    ),
                    'today'                   => esc_html__('Opening Days Display', 'cbxbusinesshours'),
                    'today_options'           => array(
                        array('label' => esc_html__('Current Week(7 days)', 'cbxbusinesshours'), 'value' => 'week'),
                        array('label' => esc_html__('Today/For Current Date', 'cbxbusinesshours'), 'value' => 'today'),
                    ),
                    'custom_date'             => esc_html__('Custom Date(Format: yyyy-mm-dd)', 'cbxbusinesshours'),
                    'start_of_week'           => esc_html__('Start of the Week', 'cbxbusinesshours'),
                    'start_of_week_options'   => array(
                        array('label' => __('Sunday'), 'value' => 0),
                        array('label' => __('Monday'), 'value' => 1),
                        array('label' => __('Tuesday'), 'value' => 2),
                        array('label' => __('Wednesday'), 'value' => 3),
                        array('label' => __('Thursday'), 'value' => 4),
                        array('label' => __('Friday'), 'value' => 5),
                        array('label' => __('Saturday'), 'value' => 6),
                    ),
                    'before_text'             => esc_html__('Before Text', 'cbxbusinesshours'),
                    'after_text'              => esc_html__('After Text', 'cbxbusinesshours'),
                ),
            ));

        wp_localize_script('cbxbusinesshours-block', 'cbxbusinesshours_block', $js_vars);

        register_block_type('codeboxr/cbxbusinesshours',
            array(
                'editor_script'   => 'cbxbusinesshours-block',
                'attributes'      => array(
                    'title'           => array(
                        'type'    => 'string',
                        'default' => esc_html__('Business Opening Hours', 'cbxbusinesshours'),
                    ),
                    'post_id'         => array(
                        'type'    => 'integer',
                        'default' => 0,
                    ),
                    'honor_post_meta' => array(
                        'type'    => 'integer',
                        'default' => 1,
                    ),
                    'compact'         => array(
                        'type'    => 'integer',
                        'default' => 0,
                    ),
                    'time_format'     => array(
                        'type'    => 'integer',
                        'default' => 24,
                    ),
                    'day_format'      => array(
                        'type'    => 'string',
                        'default' => 'long',
                    ),
                    'today'           => array(
                        'type'    => 'string',
                        'default' => 'week',
                    ),
                    'custom_date'     => array(
                        'type'    => 'string',
                        'default' => '',
                    ),
                    'start_of_week'   => array(
                        'type'    => 'integer',
                        'default' => $start_of_week_global,
                    ),
                    'before_text'     => array(
                        'type'    => 'string',
                        'default' => '',
                    ),
                    'after_text'      => array(
                        'type'    => 'string',
                        'default' => '',
                    ),

                ),
                'render_callback' => array($this, 'cbxbusinesshours_block_render'),
            ));
    }//end gutenberg_blocks_init

    /**
     * Getenberg server side render
     *
     * @param  array  $attributes
     *
     * @return string
     * @throws Exception
     */
    public function cbxbusinesshours_block_render($attributes = array())
    {
        $start_of_week_global = get_option('start_of_week');

        $atts = array();

        $atts['post_id']         = isset($attributes['post_id']) ? intval($attributes['post_id']) : 0;
        $atts['honor_post_meta'] = isset($attributes['honor_post_meta']) ? intval($attributes['honor_post_meta']) : 1;
        $atts['title']           = isset($attributes['title']) ? sanitize_text_field($attributes['title']) : '';
        $atts['before_text']     = isset($attributes['before_text']) ? sanitize_text_field($attributes['before_text']) : '';
        $atts['after_text']      = isset($attributes['after_text']) ? sanitize_text_field($attributes['after_text']) : '';

        $atts['start_of_week'] = isset($attributes['start_of_week']) ? intval($attributes['start_of_week']) : $start_of_week_global;
        $atts['compact']       = isset($attributes['compact']) ? intval($attributes['compact']) : 0;
        $atts['time_format']   = isset($attributes['time_format']) ? intval($attributes['time_format']) : 24;
        $atts['day_format']    = isset($attributes['day_format']) ? esc_attr($attributes['day_format']) : 'long';
        $atts['today']         = isset($attributes['today']) ? esc_attr($attributes['today']) : '';

        $custom_date = isset($attributes['custom_date']) ? esc_attr($attributes['custom_date']) : '';

        if ($atts['today'] == 'week') {
            $atts['today'] = '';
        }

        if ($atts['today'] == 'today' && $custom_date != '' && CBXBusinessHoursHelper::validateDate($custom_date)) {
            $atts['today'] = esc_attr($custom_date);
        }

        return '<div class="cbxbusinesshours_display_wrap">'.CBXBusinessHoursHelper::business_hours_display($atts, $atts['post_id']).'</div>';
    }//end cbxbusinesshours_block_render


    /**
     * Register New Gutenberg block Category if need
     *
     * @param $categories
     * @param $post
     *
     * @return mixed
     */
    public function gutenberg_block_categories($categories, $post)
    {
        $found = false;
        foreach ($categories as $category) {
            if ($category['slug'] == 'codeboxr') {
                $found = true;
                break;
            }
        }

        if ( ! $found) {
            return array_merge(
                $categories,
                array(
                    array(
                        'slug'  => 'codeboxr',
                        'title' => esc_html__('CBX Blocks', 'cbxbusinesshours'),
                    ),
                )
            );
        }

        return $categories;
    }//end gutenberg_block_categories


    /**
     * Enqueue style for block editor
     */
    public function enqueue_block_editor_assets()
    {
        wp_register_style('cbxbusinesshours-public', plugin_dir_url(__FILE__).'../assets/css/cbxbusinesshours-public.css', array(), $this->version, 'all');
        wp_enqueue_style('cbxbusinesshours-public');

        /*wp_register_script( 'cbxbusinesshours-block-extra',
            plugin_dir_url( __FILE__ ) . '../assets/js/cbxbusinesshours-block-extra.js',
            array(
                'jquery',
                'jquery-ui-datepicker',
            ),
            $this->version,
            true );

        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'cbxbusinesshours-block-extra' );

        wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . '../assets/css/jquery-ui.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'jquery-ui' );*/
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function plugin_listing_setting_link($links)
    {
        return array_merge(array(
            'settings' => '<a style="font-weight: bold; color: #2196f3;" target="_blank" href="'.admin_url('options-general.php?page=cbxbusinesshours').'">'.esc_html__('Settings', 'cbxbusinesshours').'</a>',
        ), $links);

    }//end plugin_listing_setting_link

    /**
     * Filters the array of row meta for each/specific plugin in the Plugins list table.
     * Appends additional links below each/specific plugin on the plugins page.
     *
     * @access  public
     *
     * @param  array  $links_array  An array of the plugin's metadata
     * @param  string  $plugin_file_name  Path to the plugin file
     * @param  array  $plugin_data  An array of plugin data
     * @param  string  $status  Status of the plugin
     *
     * @return  array       $links_array
     */
    public function plugin_row_meta($links_array, $plugin_file_name, $plugin_data, $status)
    {
        if (strpos($plugin_file_name, CBXBUSINESSHOURS_BASE_NAME) !== false) {
            if ( ! function_exists('is_plugin_active')) {
                include_once(ABSPATH.'wp-admin/includes/plugin.php');
            }

            $links_array[] = '<a target="_blank" style="color:#2196f3 !important; font-weight: bold;" href="https://wordpress.org/support/plugin/cbxbusinesshours/" aria-label="'.esc_attr__('Free Support',
                    'cbxbusinesshours').'">'.esc_html__('Free Support', 'cbxbusinesshours').'</a>';
            $links_array[] = '<a target="_blank" style="font-weight: bold; color: #2196f3;" href="https://wordpress.org/plugins/cbxbusinesshours/#reviews" >'.esc_html__('Reviews', 'cbxbusinesshours').'</a>';
            $links_array[] = '<a target="_blank" style="font-weight: bold; color: #2196f3;" href="https://codeboxr.com/product/cbx-office-opening-business-hours-for-wordpress/" >'.esc_html__('Documentation', 'cbxbusinesshours').'</a>';


            if (in_array('cbxbusinesshourspro/cbxbusinesshourspro.php.php', apply_filters('active_plugins', get_option('active_plugins'))) || defined('CBXBUSINESSHOURSPRO_PLUGIN_NAME')) {
                $links_array[] = '<a target="_blank" style="font-weight: bold; color: #2196f3;" href="https://codeboxr.com/contact-us/" >'.esc_html__('Pro Support', 'cbxbusinesshours').'</a>';
            } else {
                $links_array[] = '<a target="_blank" style="font-weight: bold; color: #2196f3;" href="https://codeboxr.com/product/cbx-office-opening-business-hours-for-wordpress/" >'.esc_html__('Try Pro Addon', 'cbxbusinesshours').'</a>';

            }


        }

        return $links_array;
    }//end plugin_row_meta


    /**
     * If we need to do something in upgrader process is completed for poll plugin
     *
     * @param $upgrader_object
     * @param $options
     */
    public function plugin_upgrader_process_complete($upgrader_object, $options)
    {
        if ($options['action'] == 'update' && $options['type'] == 'plugin') {
            if (isset($options['plugins']) && is_array($options['plugins']) && sizeof($options['plugins']) > 0) {
                foreach ($options['plugins'] as $each_plugin) {
                    if ($each_plugin == CBXBUSINESSHOURS_BASE_NAME) {

                        set_transient('cbxbusinesshours_upgraded_notice', 1);
                        break;
                    }
                }
            }
        }

    }//end plugin_upgrader_process_complete

    /**
     * Show a notice to anyone who has just installed the plugin for the first time
     * This notice shouldn't display to anyone who has just updated this plugin
     */
    public function plugin_activate_upgrade_notices()
    {
        // Check the transient to see if we've just activated the plugin
        if (get_transient('cbxbusinesshours_activated_notice')) {
            echo '<div style="border-left:1px solid #2196f3;" class="notice notice-success is-dismissible">';
            echo '<p>'.sprintf(__('Thanks for installing/deactivating <strong>CBX Office Opening & Business Hours</strong> V%s - <a href="%s" target="_blank">Codeboxr Team</a>', 'cbxbusinesshours'), CBXBUSINESSHOURS_PLUGIN_VERSION,
                    'https://codeboxr.com/').'</p>';
            echo '<p>'.sprintf(__('Explore <a href="%s" target="_blank">Plugin Setting</a> | <a href="%s" target="_blank">Documentation</a>', 'cbxbusinesshours'), admin_url('options-general.php?page=cbxbusinesshours'),
                    'https://codeboxr.com/product/cbx-office-opening-business-hours-for-wordpress/').'</p>';
            echo '</div>';
            // Delete the transient so we don't keep displaying the activation message
            delete_transient('cbxbusinesshours_activated_notice');

            $this->pro_addon_compatibility_campaign();

        }

        // Check the transient to see if we've just activated the plugin
        if (get_transient('cbxbusinesshours_upgraded_notice')) {
            echo '<div style="border-left:1px solid #2196f3;" class="notice notice-success is-dismissible">';
            echo '<p>'.sprintf(__('Thanks for upgrading <strong>CBX Office Opening & Business Hours</strong> V%s , enjoy the new features and bug fixes - <a href="%s" target="_blank">Codeboxr Team</a>', 'cbxbusinesshours'),
                    CBXBUSINESSHOURS_PLUGIN_VERSION, 'https://codeboxr.com/').'</p>';
            echo '<p>'.sprintf(__('Explore <a href="%s" target="_blank">Plugin Setting</a> | <a href="%s" target="_blank">Documentation</a>', 'cbxbusinesshours'), admin_url('options-general.php?page=cbxbusinesshours'),
                    'https://codeboxr.com/product/cbx-office-opening-business-hours-for-wordpress/').'</p>';
            echo '</div>';
            // Delete the transient so we don't keep displaying the activation message
            delete_transient('cbxbusinesshours_upgraded_notice');

            $this->pro_addon_compatibility_campaign();

        }
    }//end plugin_activate_upgrade_notices

    /**
     * Check plugin compatibility and pro addon install campaign
     */
    public function pro_addon_compatibility_campaign()
    {

        if ( ! function_exists('is_plugin_active')) {
            include_once(ABSPATH.'wp-admin/includes/plugin.php');
        }

        //if the pro addon is active or installed
        if (in_array('cbxbusinesshourspro/cbxbusinesshourspro.php', apply_filters('active_plugins', get_option('active_plugins'))) || defined('CBXBUSINESSHOURSPRO_PLUGIN_NAME')) {
            //plugin is activated

            $plugin_version = CBXBUSINESSHOURSPRO_PLUGIN_NAME;


            /*if(version_compare($plugin_version,'1.0.11', '<=') ){
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'CBX Petition Pro Addon Vx.x.x or any previous version is not compatible with CBX Petition Vx.x.x or later. Please update CBX Petition Pro Addon to version x.x.0 or later  - Codeboxr Team', 'cbxpetition' ) . '</p></div>';
            }*/
        } else {
            echo '<div style="border-left:1px solid #2196f3;" class="notice notice-success is-dismissible"><p>'.sprintf(__('<a target="_blank" href="%s">CBX Office Opening & Business Hours Pro Addon</a> has some extra features, custom store with per store business hours setting - Codeboxr Team',
                    'cbxbusinesshours'), 'https://codeboxr.com/product/cbx-office-opening-business-hours-for-wordpress/').'</p></div>';
        }

    }//end pro_addon_compatibility_campaign

    /**
     * Add our self-hosted autoupdate plugin to the filter transient
     *
     * @param $transient
     *
     * @return object $ transient
     */
    public function pre_set_site_transient_update_plugins_pro_addon($transient)
    {
        // Extra check for 3rd plugins
        if (isset($transient->response['cbxbusinesshourspro/cbxbusinesshourspro.php'])) {
            return $transient;
        }

        if ( ! function_exists('get_plugins')) {
            require_once ABSPATH.'wp-admin/includes/plugin.php';
        }

        $plugin_info = array();
        $all_plugins = get_plugins();
        if ( ! isset($all_plugins['cbxbusinesshourspro/cbxbusinesshourspro.php'])) {
            return $transient;
        } else {
            $plugin_info = $all_plugins['cbxbusinesshourspro/cbxbusinesshourspro.php'];
        }


        $remote_version = '1.0.7';

        if (version_compare($plugin_info['Version'], $remote_version, '<')) {
            $obj                                                                = new stdClass();
            $obj->slug                                                          = 'cbxbusinesshourspro';
            $obj->new_version                                                   = $remote_version;
            $obj->plugin                                                        = 'cbxbusinesshourspro/cbxbusinesshourspro.php';
            $obj->url                                                           = '';
            $obj->package                                                       = false;
            $obj->name                                                          = 'CBX Office Opening & Business Hours Pro Addon';
            $transient->response['cbxbusinesshourspro/cbxbusinesshourspro.php'] = $obj;
        }

        return $transient;
    }//end pre_set_site_transient_update_plugins_pro_addon

    /**
     * Pro Addon update message
     */
    public function plugin_update_message_pro_addon()
    {
        echo ' '.sprintf(__('Check how to <a style="color:#6648fe !important; font-weight: bold;" href="%s"><strong>Update manually</strong></a> , download latest version from <a style="color:#6648fe !important; font-weight: bold;" href="%s"><strong>My Account</strong></a> section of Codeboxr.com',
                'cbxbusinesshours'), 'https://codeboxr.com/manual-update-pro-addon/', 'https://codeboxr.com/my-account/');
    }//end plugin_update_message_pro_addon

    /**
     * Add business hours meta boxes for the supported post types
     *
     * @since    1.0.0
     */
    public function add_meta_boxes()
    {
        $settings   = $this->setting;
        $post_types = $settings->get_option('post_types', 'cbxbusinesshours_integration', array());

        if (is_array($post_types) && sizeof($post_types) > 0) {
            foreach ($post_types as $post_type) {
                //add meta box for creating form and form elements
                add_meta_box(
                    'cbxbusinesshoursg_metabox_'.esc_attr($post_type), esc_html__('Business Hours Meta box', 'cbxbusinesshours'), array($this, 'business_hours_metabox_display'), $post_type, 'normal', 'high'
                );
            }

            /*add_meta_box(
                'cbxchangelog_shortcode', esc_html__('Shortcode', 'cbxchangelog'), array($this, 'cbxchangelog_shortcode_display'), 'cbxchangelog', 'side', 'low'
            );*/
        }
    }//end add_meta_boxes

    /**
     * Render the metabox
     *
     * @param $post
     *
     * since v1.0.0
     */
    public function business_hours_metabox_display($post)
    {
        $settings = $this->setting;

        if (isset($post->ID) && $post->ID > 0) {
            $post_id   = $post->ID;
            $post_type = $post->post_type;

            $post_types = $settings->get_option('post_types', 'cbxbusinesshours_integration', array());

            if ( ! in_array($post_type, $post_types)) {
                return;
            }


            wp_nonce_field('cbxbusinesshours_'.$post_type.'_meta_box', 'cbxbusinesshours_'.$post_type.'_meta_box_nonce');

            //include('partials/metabox_changelogs.php');
            echo cbxbusinesshours_get_template_html('metabox_business_hours.php', array(
                    'post_id'   => $post_id,
                    'post_type' => $post_type,
                    'settings'  => $settings
                )
            );
        }
    }//end business_hours_metabox_display

    /**
     * cbxbusinesshours meta box save
     *
     * @param $post_id
     */
    public function metabox_save($post_id, $post, $update)
    {
        $settings   = $this->setting;
        $post_types = $settings->get_option('post_types', 'cbxbusinesshours_integration', array());

        $post_type = $post->post_type;
        //if this post not set for this meta then return early
        if ( ! in_array($post_type, $post_types)) {
            return;
        }

        // Check if our nonce is set.
        if ( ! isset($_POST['cbxbusinesshours_'.$post_type.'_meta_box_nonce'])) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce($_POST['cbxbusinesshours_'.$post_type.'_meta_box_nonce'], 'cbxbusinesshours_'.$post_type.'_meta_box')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && $post_type == $_POST['post_type']) {

            if ( ! current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        //now we are free to save the meta
        $start_of_week_global = get_option('start_of_week');
        $post_data            = $_POST;

        $week_days          = isset($post_data['cbxbusinesshours_meta_weekdays']) ? $post_data['cbxbusinesshours_meta_weekdays'] : array();
        $days_of_exceptions = isset($post_data['cbxbusinesshours_meta_dayexception']) ? $post_data['cbxbusinesshours_meta_dayexception'] : array();
        $setting            = isset($post_data['cbxbusinesshours_meta_setting']) ? $post_data['cbxbusinesshours_meta_setting'] : array();

        $setting['start_of_week'] = isset($setting['start_of_week']) ? intval($setting['start_of_week']) : $start_of_week_global;
        $setting['compact']       = isset($setting['compact']) ? intval($setting['compact']) : 0;
        $setting['time_format']   = isset($setting['time_format']) ? intval($setting['time_format']) : 24;
        $setting['day_format']    = isset($setting['day_format']) ? sanitize_text_field($setting['day_format']) : 'long';
        $setting['today']         = isset($setting['today']) ? sanitize_text_field($setting['today']) : '';
        $setting['custom_date']   = isset($setting['custom_date']) ? sanitize_text_field($setting['custom_date']) : '';
        $setting['title']         = isset($setting['title']) ? sanitize_text_field($setting['title']) : '';
        $setting['before_text']   = isset($setting['before_text']) ? sanitize_text_field($setting['before_text']) : '';
        $setting['after_text']    = isset($setting['after_text']) ? sanitize_text_field($setting['after_text']) : '';


        $data_safe = array();


        $days_of_exceptions = CBXBusinessHoursHelper::sanitize_callback_dayexception($days_of_exceptions);

        $data_safe['weekdays']     = maybe_serialize($week_days);
        $data_safe['dayexception'] = maybe_serialize($days_of_exceptions);
        $data_safe['setting']      = maybe_serialize($setting);

        $data_safe = apply_filters('cbxbusinesshours_meta_data_before_update', $data_safe, $post_id);

        update_post_meta($post_id, '_cbxbussnesshours_meta', $data_safe);
    }//end metabox_save
}//end class CbxBusinessHours_Admin
