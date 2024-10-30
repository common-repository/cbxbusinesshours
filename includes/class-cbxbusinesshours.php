<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXBusinessHours
 * @subpackage CBXBusinessHours/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    CBXBusinessHours
 * @subpackage CBXBusinessHours/includes
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXBusinessHours
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      CBXBusinessHours_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {

        $this->plugin_name = CBXBUSINESSHOURS_PLUGIN_NAME;
        $this->version = CBXBUSINESSHOURS_PLUGIN_VERSION;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - CBXBusinessHours_Loader. Orchestrates the hooks of the plugin.
     * - CBXBusinessHours_i18n. Defines internationalization functionality.
     * - CBXBusinessHours_Admin. Defines all hooks for the admin area.
     * - CBXBusinessHours_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cbxbusinesshours-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cbxbusinesshours-i18n.php';

	    /**
	     * Setting Class Api will be helpful for creating options page setting section
	     */
	    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cbxbusinesshours-settings.php';

	    /**
	     * Helper class
	     */
	    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/cbxbusinesshours-tpl-loader.php';
	    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cbxbusinesshours-helper.php';

	    /**
	     * Dashboard widget class
	     */
	    require_once plugin_dir_path(dirname(__FILE__)) . 'widgets/dashboard_widgets/class-cbxbusinesshour-dashboard-widget.php';


        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-cbxbusinesshours-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cbxbusinesshours-public.php';

	    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/cbxbusinesshours-functions.php';

	    $this->loader = new CBXBusinessHours_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the CBXBusinessHours_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new CBXBusinessHours_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new CBXBusinessHours_Admin($this->get_plugin_name(), $this->get_version());

	    if(is_admin()){
		    $this->loader->add_action( 'init', 'CBXBusinessHoursHelper', 'init_session' );
	    }

	    $this->loader->add_action('admin_menu', $plugin_admin, 'admin_menu');
	    $this->loader->add_action('admin_init', $plugin_admin, 'settings_init');

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');



	    //gutenberg blocks
	    $this->loader->add_action('init', $plugin_admin, 'gutenberg_blocks_init');
	    $this->loader->add_filter( 'block_categories', $plugin_admin, 'gutenberg_block_categories', 10, 2 );
	    $this->loader->add_action( 'enqueue_block_editor_assets', $plugin_admin,'enqueue_block_editor_assets' );


	    //add plugin row meta and actions links
	    $this->loader->add_filter( 'plugin_action_links_' . CBXBUSINESSHOURS_BASE_NAME, $plugin_admin, 'plugin_listing_setting_link' );
	    $this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'plugin_row_meta', 10, 4 );

	    $this->loader->add_action( 'upgrader_process_complete', $plugin_admin, 'plugin_upgrader_process_complete', 10, 2 );
	    $this->loader->add_action( 'admin_notices', $plugin_admin, 'plugin_activate_upgrade_notices' );
	    $this->loader->add_filter('pre_set_site_transient_update_plugins', $plugin_admin, 'pre_set_site_transient_update_plugins_pro_addon');
	    $this->loader->add_action('in_plugin_update_message-' . 'cbxbusinesshourspro/cbxbusinesshourspro.php', $plugin_admin, 'plugin_update_message_pro_addon' );

	    $dashboard_widget = new CBXBusinessHoursDashboard();
	    $this->loader->add_action('wp_dashboard_setup', $dashboard_widget, 'CBXBusinessHours_dashboard_widget');

	    //add metabox for custom post type cbxfeedbackform && cbxfeedbackbtn
	    $this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_meta_boxes');

	    //meta save
	   $this->loader->add_action('save_post', $plugin_admin, 'metabox_save', 10, 3); //save meta
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new CBXBusinessHours_Public($this->get_plugin_name(), $this->get_version());

	    $this->loader->add_action( 'template_redirect', 'CBXBusinessHoursHelper', 'init_session' );

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
	    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

	    $this->loader->add_action('init', $plugin_public, 'init_shortcodes');
	    $this->loader->add_action('widgets_init', $plugin_public, 'init_register_widgets');

	    //elementor
	    //$this->loader->add_action( 'elementor/init',$plugin_public, 'init_elementor_widgets' );
	    $this->loader->add_action( 'elementor/widgets/widgets_registered',$plugin_public, 'init_elementor_widgets' );
	    $this->loader->add_action('elementor/editor/before_enqueue_scripts', $plugin_public, 'elementor_icon_loader', 99999);
	    $this->loader->add_action( 'elementor/elements/categories_registered', $plugin_public,'add_elementor_widget_categories' );

	    $this->loader->add_action( 'vc_before_init', $plugin_public, 'vc_before_init_actions', 12 ); //priority set to 12 from default 10

	    $this->loader->add_filter( 'the_content', $plugin_public, 'the_content_auto_integration' );

    }// end of define_public_hooks methods

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    CBXBusinessHours_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

}// end of CBXBusinessHours class
