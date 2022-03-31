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


	public function admin_rest_list_headings_query($query_vars, $request) {
		$query_vars['orderby']  = 'meta_value_num';
		$query_vars['meta_key'] = 'tax_position';
		$query_vars['order']    = 'DESC';
		return $query_vars;
	}



	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {




		wp_register_script( 'vue-polyfill', '//polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver', [], '3.96.0' );

		wp_register_script( 'highlight', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.2/highlight.min.js', [ ], '10.1.2' );

		wp_register_script( 'highlight-xml', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.2/languages/xml.min.js', [ 'highlight' ], '10.1.2' );

		wp_register_script( 'quilljs', '//cdn.quilljs.com/1.3.4/quill.js', [], '1.3.4' );

		wp_register_script( 'quill-image-resize', 'https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js', [ 'quilljs' ], '3.0.0' );

		wp_register_script( 'quill-html-edit-button', plugin_dir_url( __DIR__ ) . '/admin/js/quill.htmlEditButton.min.js', [ 'quilljs', 'highlight' ], '2.2.6' );

		wp_register_script( 'vue', '//cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js', [], '2.6.14' );

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


		wp_enqueue_script('vue-polyfill');
		wp_enqueue_script('highlight');
		wp_enqueue_script('highlight-xml');
		wp_enqueue_script('quilljs');
		wp_enqueue_script('quill-image-resize');
		wp_enqueue_script('quill-html-edit-button');
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

		wp_register_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css', array(), $this->version, 'all' );

		wp_register_style( 'quilljs', 'https://cdn.quilljs.com/1.3.4/quill.core.css', array(), '1.3.4', 'all' );

		wp_register_style( 'quill-snow', 'https://cdn.quilljs.com/1.3.4/quill.snow.css', array(), '1.3.4', 'all' );

		wp_register_style( 'quill-bubble', 'https://cdn.quilljs.com/1.3.4/quill.bubble.css', array(), '1.3.4', 'all' );

		wp_register_style( 'quill-bubble', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.2/styles/github.min.css', array(), '10.1.2', 'all' );

		wp_register_style( 'bootstrap-vue', '//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'quilljs' );
		wp_enqueue_style( 'quill-snow' );
		wp_enqueue_style( 'quill-bubble' );
		wp_enqueue_style( 'highlight' );
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
