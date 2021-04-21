<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://krogerkrazy.com
 * @since      1.0.0
 *
 * @package    Krogerkrazy
 * @subpackage Krogerkrazy/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Krogerkrazy
 * @subpackage Krogerkrazy/admin
 * @author     Kroger Krazy <couponkatarina@gmail.com>
 */
class Krogerkrazy_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/krogerkrazy-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_style( 'material-design-icons' );
		wp_enqueue_style( 'vuetify' );
		wp_enqueue_script('vue-polyfill');
		wp_enqueue_script('vue');
		wp_enqueue_script('vuetify');
		wp_enqueue_script('sortable');
		wp_enqueue_script('vue-draggable');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/krogerkrazy-admin.js', array( 'vue' ), $this->version, true );


	}

	public function setup_posts_menu() {

		add_submenu_page(
			'edit.php',
			__( 'Printable Lists', 'krogerkrazy' ),
			__( 'Printable Lists', 'krogerkrazy' ),
			'edit_posts',
			'printable_lists',
			array( $this, 'printable_lists_callback' )
		);

	}

	public function printable_lists_callback() {

		include plugin_dir_path(__FILE__) . 'partials/krogerkrazy-admin-display.php';

	}

}
