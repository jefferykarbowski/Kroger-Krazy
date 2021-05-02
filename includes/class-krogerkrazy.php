<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://krogerkrazy.com
 * @since      1.0.0
 *
 * @package    Krogerkrazy
 * @subpackage Krogerkrazy/includes
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
 * @package    Krogerkrazy
 * @subpackage Krogerkrazy/includes
 * @author     Kroger Krazy <couponkatarina@gmail.com>
 */
class Krogerkrazy {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Krogerkrazy_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
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
	public function __construct() {
		if ( defined( 'KROGERKRAZY_VERSION' ) ) {
			$this->version = KROGERKRAZY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'krogerkrazy';

		$this->load_dependencies();
		$this->set_locale();
		$this->setup_rest_api();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Krogerkrazy_Loader. Orchestrates the hooks of the plugin.
	 * - Krogerkrazy_i18n. Defines internationalization functionality.
	 * - Krogerkrazy_Admin. Defines all hooks for the admin area.
	 * - Krogerkrazy_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		
		wp_register_style('material-design-icons', '//cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css');

		wp_register_style('vuetify', '//cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css');

		wp_register_script('vue-polyfill', '//polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver', array( 'jquery'), '3.96.0', true);

		wp_register_script('vue', '//unpkg.com/vue@latest/dist/vue.min.js', array( 'jquery'), '2.6.12', true);

		wp_register_script('vuetify', '//cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js', array( 'vue'), '2.4.11', true);

		wp_register_script('sortable', '//cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js', array( 'vue', 'vuetify'), '1.8.4', true);

		wp_register_script('vue-draggable', '//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js', array('vue', 'vuetify', 'sortable'), '2.20.0', true);


		/**
		 * The class responsible for extending Wordpress's Rest API.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-krogerkrazy-rest-api.php';


		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-krogerkrazy-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-krogerkrazy-i18n.php';



		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-krogerkrazy-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-krogerkrazy-public.php';



		$this->loader = new Krogerkrazy_Loader();


	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Krogerkrazy_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Krogerkrazy_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}



	/**
	 * Extend WP's Rest API
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function setup_rest_api() {

		$krogerkrazy_rest_api = new KrogerKrazy_Rest_API();
		$this->loader->add_action( 'rest_url_prefix', $krogerkrazy_rest_api, 'kk_api_slug' );
		$this->loader->add_action( 'rest_api_init', $krogerkrazy_rest_api, 'register_routes' );


	}




	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Krogerkrazy_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'setup_posts_menu' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Krogerkrazy_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Krogerkrazy_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
