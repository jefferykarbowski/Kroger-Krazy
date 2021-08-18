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
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('vue-polyfill');
		wp_enqueue_script('quilljs');
		wp_enqueue_script('quill-image-resize');
		wp_enqueue_script('vue');
		wp_enqueue_script('vue-quill-editor');
		wp_enqueue_script('bootstrap-vue');
		wp_enqueue_script('bootstrap-vue-icons');
		wp_enqueue_script('v-calendar');
		wp_enqueue_script('sortable');
		wp_enqueue_script('vue-draggable');
		wp_enqueue_script('vue');
		wp_enqueue_script('v-mask');
		wp_enqueue_script('v-mask-plugins');
		wp_enqueue_script('he');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/krogerkrazy-admin.js', array( 'vue' ), null, true );
		wp_localize_script(
			$this->plugin_name,
			'krogerkrazy_ajax_obj',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('wp_rest')
			)
		);

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'quilljs' );
		wp_enqueue_style( 'quill-snow' );
		wp_enqueue_style( 'quill-bubble' );
		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'bootstrap-vue' );
		wp_enqueue_style( 'bootstrap-vue-icons' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/krogerkrazy-admin.css', array(), null, 'all' );

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


	public function krogerkrazy_meta_box_callback() {
		$screen = get_current_screen();
		add_meta_box(
			'krogerkrazy_meta_box',
			__( 'Embed Printable Lists', 'textdomain' ),
			array( $this, 'add_meta_box_callback' ),
			$screen,
			'side',
			'high'
		);
	}


	public function add_meta_box_callback() {
		{
			?>
			<fieldset id="pembed_postbox">
				<div>
					<label>Title</label>
					<input type="text" id="pembed_title" name="pembed_title" value="" style="width: 100%;" />
				</div>
				<div>
					<label>Description</label>
					<textarea id="pembed_desc" name="pembed_desc" value="" style="width: 100%; height: 80px;" /></textarea>
				</div>
				<div>
					<label>Final Price</label>
					<input type="text" id="pembed_final_price" name="pembed_final_price" value="" style="width: 100%;" />
				</div>
				<div style="margin-top: 4px;">
					<input id="pembed_create" type="button" value="&laquo; Insert Embed Code" class='button' onClick="pembed_insertCode();"/>
				</div>
			</fieldset>
			<script>
                function pembed_insertCode()
                {
                    var code = "";

                    code += "[kk-deal";
                    if( jQuery("#pembed_title").val() == "" )
                    {
                        alert("Please add a title.");
                        return;
                    }
                    else if( jQuery("#pembed_desc").val() == "" )
                    {
                        alert("Please add a description.");
                        return;
                    }
                    code += " title=\"" + jQuery("#pembed_title").val() + "\" ";
                    code += "description=\"" + jQuery("#pembed_desc").val() + "\" ";
                    code += "final_price=\"" + jQuery("#pembed_final_price").val() + "\" ";

                    code += "]";
                    parent.tinyMCE.activeEditor.execCommand("mceInsertContent",false, code);
                }
			</script>
			<?php

		}

	}

}
