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
	 * @var      Krogerkrazy_Loader $loader Maintains and registers all hooks for the plugin.
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


		function cptui_register_my_cpts() {

			/**
			 * Post Type: List Items.
			 */

			$labels = [
				"name"                     => __( "List Items", "krogerkrazy" ),
				"singular_name"            => __( "List Item", "krogerkrazy" ),
				"menu_name"                => __( "My List Items", "krogerkrazy" ),
				"all_items"                => __( "All List Items", "krogerkrazy" ),
				"add_new"                  => __( "Add new", "krogerkrazy" ),
				"add_new_item"             => __( "Add new item", "krogerkrazy" ),
				"edit_item"                => __( "Edit item", "krogerkrazy" ),
				"new_item"                 => __( "New item", "krogerkrazy" ),
				"view_item"                => __( "View item", "krogerkrazy" ),
				"view_items"               => __( "View item", "krogerkrazy" ),
				"search_items"             => __( "Search items", "krogerkrazy" ),
				"not_found"                => __( "No items found", "krogerkrazy" ),
				"not_found_in_trash"       => __( "No items found in trash", "krogerkrazy" ),
				"parent"                   => __( "Parent item:", "krogerkrazy" ),
				"featured_image"           => __( "Featured image for this item", "krogerkrazy" ),
				"set_featured_image"       => __( "Set featured image for this item", "krogerkrazy" ),
				"remove_featured_image"    => __( "Remove featured image for this item", "krogerkrazy" ),
				"use_featured_image"       => __( "Use as featured image for this item", "krogerkrazy" ),
				"archives"                 => __( "Item archives", "krogerkrazy" ),
				"insert_into_item"         => __( "Insert into Item", "krogerkrazy" ),
				"uploaded_to_this_item"    => __( "Upload to this Item", "krogerkrazy" ),
				"filter_items_list"        => __( "Filter Items list", "krogerkrazy" ),
				"items_list_navigation"    => __( "Lists Item navigation", "krogerkrazy" ),
				"items_list"               => __( "Lists Item", "krogerkrazy" ),
				"attributes"               => __( "Items attributes", "krogerkrazy" ),
				"name_admin_bar"           => __( "Item", "krogerkrazy" ),
				"item_published"           => __( "Item published", "krogerkrazy" ),
				"item_published_privately" => __( "Item published privately.", "krogerkrazy" ),
				"item_reverted_to_draft"   => __( "Item reverted to draft.", "krogerkrazy" ),
				"item_scheduled"           => __( "Item scheduled", "krogerkrazy" ),
				"item_updated"             => __( "Item updated.", "krogerkrazy" ),
				"parent_item_colon"        => __( "Parent item:", "krogerkrazy" ),
			];

			$args = [
				"label"                 => __( "List Items", "krogerkrazy" ),
				"labels"                => $labels,
				"description"           => "",
				"public"                => true,
				"publicly_queryable"    => true,
				"show_ui"               => true,
				"show_in_rest"          => true,
				"rest_base"             => "list_items",
				"rest_controller_class" => "WP_REST_Posts_Controller",
				"has_archive"           => true,
				"show_in_menu"          => true,
				"show_in_nav_menus"     => false,
				"delete_with_user"      => false,
				"exclude_from_search"   => true,
				"capability_type"       => "post",
				"map_meta_cap"          => true,
				"hierarchical"          => false,
				"rewrite"               => false,
				"query_var"             => true,
				"supports"              => [ "title", "editor" ],
				"show_in_graphql"       => false,
			];

			register_post_type( "list_item", $args );
		}

		add_action( 'init', 'cptui_register_my_cpts' );


		function cptui_register_my_taxes() {

			/**
			 * Taxonomy: List.
			 */

			$labels = [
				"name"                       => __( "List", "krogerkrazy" ),
				"singular_name"              => __( "list", "krogerkrazy" ),
				"menu_name"                  => __( "List", "krogerkrazy" ),
				"all_items"                  => __( "All List", "krogerkrazy" ),
				"edit_item"                  => __( "Edit list", "krogerkrazy" ),
				"view_item"                  => __( "View list", "krogerkrazy" ),
				"update_item"                => __( "Update list name", "krogerkrazy" ),
				"add_new_item"               => __( "Add new list", "krogerkrazy" ),
				"new_item_name"              => __( "New list name", "krogerkrazy" ),
				"parent_item"                => __( "Parent list", "krogerkrazy" ),
				"parent_item_colon"          => __( "Parent list:", "krogerkrazy" ),
				"search_items"               => __( "Search List", "krogerkrazy" ),
				"popular_items"              => __( "Popular List", "krogerkrazy" ),
				"separate_items_with_commas" => __( "Separate List with commas", "krogerkrazy" ),
				"add_or_remove_items"        => __( "Add or remove List", "krogerkrazy" ),
				"choose_from_most_used"      => __( "Choose from the most used List", "krogerkrazy" ),
				"not_found"                  => __( "No List found", "krogerkrazy" ),
				"no_terms"                   => __( "No List", "krogerkrazy" ),
				"items_list_navigation"      => __( "List list navigation", "krogerkrazy" ),
				"items_list"                 => __( "List list", "krogerkrazy" ),
				"back_to_items"              => __( "Back to List", "krogerkrazy" ),
			];


			$args = [
				"label"                 => __( "List", "krogerkrazy" ),
				"labels"                => $labels,
				"public"                => false,
				"publicly_queryable"    => false,
				"hierarchical"          => false,
				"show_ui"               => true,
				"show_in_menu"          => true,
				"show_in_nav_menus"     => false,
				"query_var"             => true,
				"rewrite"               => false,
				"show_admin_column"     => false,
				"show_in_rest"          => true,
				"rest_base"             => "lists",
				"rest_controller_class" => "WP_REST_Terms_Controller",
				"show_in_quick_edit"    => true,
				"show_in_graphql"       => false,
			];
			register_taxonomy( "list", [ "list_item" ], $args );


			/**
			 * Taxonomy: List Headings.
			 */

			$labels = [
				"name"                       => __( "List Headings", "twentytwentyone" ),
				"singular_name"              => __( "List Heading", "twentytwentyone" ),
				"menu_name"                  => __( "List Headings", "twentytwentyone" ),
				"all_items"                  => __( "All List Headings", "twentytwentyone" ),
				"edit_item"                  => __( "Edit List Heading", "twentytwentyone" ),
				"view_item"                  => __( "View List Heading", "twentytwentyone" ),
				"update_item"                => __( "Update List Heading name", "twentytwentyone" ),
				"add_new_item"               => __( "Add new List Heading", "twentytwentyone" ),
				"new_item_name"              => __( "New List Heading name", "twentytwentyone" ),
				"parent_item"                => __( "Parent List Heading", "twentytwentyone" ),
				"parent_item_colon"          => __( "Parent List Heading:", "twentytwentyone" ),
				"search_items"               => __( "Search List Headings", "twentytwentyone" ),
				"popular_items"              => __( "Popular List Headings", "twentytwentyone" ),
				"separate_items_with_commas" => __( "Separate List Headings with commas", "twentytwentyone" ),
				"add_or_remove_items"        => __( "Add or remove List Headings", "twentytwentyone" ),
				"choose_from_most_used"      => __( "Choose from the most used List Headings", "twentytwentyone" ),
				"not_found"                  => __( "No List Headings found", "twentytwentyone" ),
				"no_terms"                   => __( "No List Headings", "twentytwentyone" ),
				"items_list_navigation"      => __( "List Headings list navigation", "twentytwentyone" ),
				"items_list"                 => __( "List Headings list", "twentytwentyone" ),
				"back_to_items"              => __( "Back to List Headings", "twentytwentyone" ),
			];


			$args = [
				"label"                 => __( "List Headings", "twentytwentyone" ),
				"labels"                => $labels,
				"public"                => false,
				"publicly_queryable"    => false,
				"hierarchical"          => false,
				"show_ui"               => true,
				"show_in_menu"          => true,
				"show_in_nav_menus"     => false,
				"query_var"             => false,
				"rewrite"               => false,
				"show_admin_column"     => false,
				"show_in_rest"          => true,
				"rest_base"             => "list_headings",
				"rest_controller_class" => "WP_REST_Terms_Controller",
				"show_in_quick_edit"    => false,
				"show_in_graphql"       => false,
				"meta_box_cb"           => false,
			];
			register_taxonomy( "list_headings", [ "post" ], $args );


		}

		add_action( 'init', 'cptui_register_my_taxes' );


		wp_register_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css', array(), $this->version, 'all' );

		wp_register_style( 'quilljs', 'https://cdn.quilljs.com/1.3.4/quill.core.css', array(), '1.3.4', 'all' );

		wp_register_style( 'quill-snow', 'https://cdn.quilljs.com/1.3.4/quill.snow.css', array(), '1.3.4', 'all' );

		wp_register_style( 'quill-bubble', 'https://cdn.quilljs.com/1.3.4/quill.bubble.css', array(), '1.3.4', 'all' );

		wp_register_style( 'bootstrap-vue', '//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css', array(), $this->version, 'all' );

		wp_register_script( 'vue-polyfill', '//polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver', [], '3.96.0' );

		wp_register_script( 'quilljs', '//cdn.quilljs.com/1.3.4/quill.js', [], '1.3.4' );

		wp_register_script( 'quill-image-resize', 'https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js', [ 'quilljs' ], '3.0.0' );


		wp_register_script( 'vue', '//unpkg.com/vue@latest/dist/vue.min.js', [], '2.6.12' );

		wp_register_script( 'bootstrap-vue', '//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js', [], '2.20.0' );

		wp_register_script( 'vue-quill-editor', 'https://cdn.jsdelivr.net/npm/vue-quill-editor@3.0.6/dist/vue-quill-editor.min.js', [], '3.0.6' );


		wp_register_script( 'bootstrap-vue-icons', '//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue-icons.min.js', [], '1.2.0' );

		wp_register_script( 'v-mask', '//cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js', [ 'vue' ], '2.2.4' );

		wp_register_script( 'v-mask-plugins', '//cdn.jsdelivr.net/npm/text-mask-addons@3.8.0/dist/textMaskAddons.min.js', [ 'v-mask' ], '3.8.0' );

		wp_register_script( 'vue-html-to-paper', '//unpkg.com/vue-html-to-paper/build/vue-html-to-paper.js', [ 'vue' ] );

		// Bootstrap's Datepicker is terrible.
		wp_register_script( 'v-calendar', '//unpkg.com/v-calendar', [], '1.6.2' );

		wp_register_script( 'sortable', '//cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js', array( 'jquery' ), '1.8.4', true );

		wp_register_script( 'vue-draggable', '//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js', array(
			'jquery',
			'sortable'
		), '2.20.0', true );

		wp_register_script( 'he', '//cdn.jsdelivr.net/npm/he@1.2.0/he.min.js', [], '1.2.0' );

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
		$this->loader->add_filter( 'rest_list_item_query', $krogerkrazy_rest_api, 'filter_rest_list_item_query', 10, 2 );

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
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'krogerkrazy_meta_box_callback' );

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
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
		$this->loader->add_shortcode( 'printable_list', $plugin_public, 'kk_printable_list_callback' );
		$this->loader->add_shortcode( 'kk-deal', $plugin_public, 'kk_deal_callback' );
		$this->loader->add_filter( 'wp_footer', $plugin_public, 'kk_printable_list_sidebar_callback' );
		$this->loader->add_filter( 'wp_ajax_email_list', $plugin_public, 'email_list_callback' );
		$this->loader->add_filter( 'wp_ajax_nopriv_email_list', $plugin_public, 'email_list_callback' );

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
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Krogerkrazy_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

}
