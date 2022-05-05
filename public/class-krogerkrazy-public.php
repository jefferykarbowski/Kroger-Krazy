<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://krogerkrazy.com
 * @since      1.0.0
 *
 * @package    Krogerkrazy
 * @subpackage Krogerkrazy/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Krogerkrazy
 * @subpackage Krogerkrazy/public
 * @author     Kroger Krazy <couponkatarina@gmail.com>
 */
class Krogerkrazy_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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




		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/krogerkrazy-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( 'vue-polyfill', '//polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver', [], '3.96.0' );

		wp_register_script( 'highlight', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.2/highlight.min.js', [ ], '10.1.2' );

		wp_register_script( 'highlight-xml', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.2/languages/xml.min.js', [ 'highlight' ], '10.1.2' );

		wp_register_script( 'quilljs', '//cdn.quilljs.com/1.3.4/quill.js', [], '1.3.4' );

		wp_register_script( 'quill-image-resize', 'https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js', [ 'quilljs' ], '3.0.0' );

		wp_register_script( 'quill-html-edit-button', plugin_dir_url( __DIR__ ) . '/admin/js/quill.htmlEditButton.min.js', [ 'quilljs' ], '2.2.6' );

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/krogerkrazy-public.js', array( 'vue' ), $this->version, false );

	}


	public function kk_printable_list_sidebar_callback() {

		$ajax_obj = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'kk_nonce' ),
		);

//		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'bootstrap-vue' );
		wp_enqueue_style( 'bootstrap-vue-icons' );

		wp_enqueue_script( 'vue-polyfill' );
		wp_enqueue_script( 'vue' );
		wp_enqueue_script( 'vue-quill-editor' );
		wp_enqueue_script( 'bootstrap-vue' );
		wp_enqueue_script( 'bootstrap-vue-icons' );

		wp_enqueue_script( $this->plugin_name . '-printable-list-sidebar', plugin_dir_url( __FILE__ ) . 'js/krogerkrazy-printable-list-sidebar.js', array(
			'vue',
			'vue-html-to-paper'
		), $this->version, true );

		wp_localize_script(
			$this->plugin_name . '-printable-list-sidebar',
			'kk_ajax_obj', $ajax_obj
		);

		ob_start();
		include plugin_dir_path( __FILE__ ) . 'partials/kk-printable-list-sidebar-display.php';
		echo ob_get_clean();
	}


	/**
	 * Render the [printable_list id="000"] shortcode
	 * @return false|string
	 * @since    1.0.0
	 */
	public function kk_printable_list_callback( $atts ) {
		$a = shortcode_atts( array(
			'id' => null,
		), $atts );

		if ( ! $a['id'] ) {
			return 'You must specify an ID for your printable list';
		}

//		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'bootstrap-vue' );
		wp_enqueue_style( 'bootstrap-vue-icons' );

		wp_enqueue_script( 'vue-polyfill' );
		wp_enqueue_script( 'vue' );
		wp_enqueue_script( 'vue-quill-editor' );
		wp_enqueue_script( 'bootstrap-vue' );
		wp_enqueue_script( 'bootstrap-vue-icons' );
		wp_enqueue_script( 'vue-html-to-paper' );

		wp_enqueue_script( $this->plugin_name . '-printable-list', plugin_dir_url( __FILE__ ) . 'js/krogerkrazy-printable-list.js', array(
			'vue',
			'vue-html-to-paper'
		), null, true );
		wp_localize_script(
			$this->plugin_name . '-printable-list',
			'kk_ajax_obj',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wp_rest' )
			)
		);

		ob_start();
		include plugin_dir_path( __FILE__ ) . 'partials/kk-printable-list-display.php';

		return ob_get_clean();
	}


	public function kk_deal_callback( $atts, $content = null ) {
		$id = uniqid();

		$a = shortcode_atts( array(
			'title'       => '',
			'final_price' => '',
			'append_price_text' => '',
			'unique_id' => 'kkdeal_' . $id,
		), $atts );

		// wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'bootstrap-vue' );
		wp_enqueue_style( 'bootstrap-vue-icons' );

		wp_enqueue_script( 'vue-polyfill' );
		wp_enqueue_script( 'vue' );
		wp_enqueue_script( 'bootstrap-vue' );
		wp_enqueue_script( 'bootstrap-vue-icons' );

		wp_enqueue_script( $this->plugin_name . '-krogerkrazy-deal', plugin_dir_url( __FILE__ ) . 'js/krogerkrazy-deal.js', array( 'vue' ), null, true );
 
		
		ob_start();
		include plugin_dir_path( __FILE__ ) . 'partials/krogerkrazy-deal.php';

		return ob_get_clean();
	}


	public function email_list_callback() {

		$email         = $_POST['email-address'];
		$printableList = $_POST['printableList'];
		$printableList = stripslashes( $printableList );
		$printableList = json_decode( $printableList, true );

		$customPrintableList = $_POST['customPrintableList'];
		$customPrintableList = stripslashes( $customPrintableList );
		$customPrintableList = json_decode( $customPrintableList, true );

		$subject = 'Kroger Krazy Deal List';

		$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
          <style type="text/css">
          .ExternalClass{width:100%}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:150%}a{text-decoration:none}body,td,input,textarea,select{margin:unset;font-family:unset}input,textarea,select{font-size:unset}@media screen and (max-width: 600px){table.row th.col-lg-1,table.row th.col-lg-2,table.row th.col-lg-3,table.row th.col-lg-4,table.row th.col-lg-5,table.row th.col-lg-6,table.row th.col-lg-7,table.row th.col-lg-8,table.row th.col-lg-9,table.row th.col-lg-10,table.row th.col-lg-11,table.row th.col-lg-12{display:block;width:100% !important}.d-mobile{display:block !important}.d-desktop{display:none !important}.w-lg-25{width:auto !important}.w-lg-25>tbody>tr>td{width:auto !important}.w-lg-50{width:auto !important}.w-lg-50>tbody>tr>td{width:auto !important}.w-lg-75{width:auto !important}.w-lg-75>tbody>tr>td{width:auto !important}.w-lg-100{width:auto !important}.w-lg-100>tbody>tr>td{width:auto !important}.w-lg-auto{width:auto !important}.w-lg-auto>tbody>tr>td{width:auto !important}.w-25{width:25% !important}.w-25>tbody>tr>td{width:25% !important}.w-50{width:50% !important}.w-50>tbody>tr>td{width:50% !important}.w-75{width:75% !important}.w-75>tbody>tr>td{width:75% !important}.w-100{width:100% !important}.w-100>tbody>tr>td{width:100% !important}.w-auto{width:auto !important}.w-auto>tbody>tr>td{width:auto !important}.p-lg-0>tbody>tr>td{padding:0 !important}.pt-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-top:0 !important}.pr-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-right:0 !important}.pb-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-bottom:0 !important}.pl-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-left:0 !important}.p-lg-1>tbody>tr>td{padding:0 !important}.pt-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-top:0 !important}.pr-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-right:0 !important}.pb-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-bottom:0 !important}.pl-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-left:0 !important}.p-lg-2>tbody>tr>td{padding:0 !important}.pt-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-top:0 !important}.pr-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-right:0 !important}.pb-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-bottom:0 !important}.pl-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-left:0 !important}.p-lg-3>tbody>tr>td{padding:0 !important}.pt-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-top:0 !important}.pr-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-right:0 !important}.pb-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-bottom:0 !important}.pl-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-left:0 !important}.p-lg-4>tbody>tr>td{padding:0 !important}.pt-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-top:0 !important}.pr-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-right:0 !important}.pb-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-bottom:0 !important}.pl-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-left:0 !important}.p-lg-5>tbody>tr>td{padding:0 !important}.pt-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-top:0 !important}.pr-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-right:0 !important}.pb-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-bottom:0 !important}.pl-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-left:0 !important}.p-0>tbody>tr>td{padding:0 !important}.pt-0>tbody>tr>td,.py-0>tbody>tr>td{padding-top:0 !important}.pr-0>tbody>tr>td,.px-0>tbody>tr>td{padding-right:0 !important}.pb-0>tbody>tr>td,.py-0>tbody>tr>td{padding-bottom:0 !important}.pl-0>tbody>tr>td,.px-0>tbody>tr>td{padding-left:0 !important}.p-1>tbody>tr>td{padding:4px !important}.pt-1>tbody>tr>td,.py-1>tbody>tr>td{padding-top:4px !important}.pr-1>tbody>tr>td,.px-1>tbody>tr>td{padding-right:4px !important}.pb-1>tbody>tr>td,.py-1>tbody>tr>td{padding-bottom:4px !important}.pl-1>tbody>tr>td,.px-1>tbody>tr>td{padding-left:4px !important}.p-2>tbody>tr>td{padding:8px !important}.pt-2>tbody>tr>td,.py-2>tbody>tr>td{padding-top:8px !important}.pr-2>tbody>tr>td,.px-2>tbody>tr>td{padding-right:8px !important}.pb-2>tbody>tr>td,.py-2>tbody>tr>td{padding-bottom:8px !important}.pl-2>tbody>tr>td,.px-2>tbody>tr>td{padding-left:8px !important}.p-3>tbody>tr>td{padding:16px !important}.pt-3>tbody>tr>td,.py-3>tbody>tr>td{padding-top:16px !important}.pr-3>tbody>tr>td,.px-3>tbody>tr>td{padding-right:16px !important}.pb-3>tbody>tr>td,.py-3>tbody>tr>td{padding-bottom:16px !important}.pl-3>tbody>tr>td,.px-3>tbody>tr>td{padding-left:16px !important}.p-4>tbody>tr>td{padding:24px !important}.pt-4>tbody>tr>td,.py-4>tbody>tr>td{padding-top:24px !important}.pr-4>tbody>tr>td,.px-4>tbody>tr>td{padding-right:24px !important}.pb-4>tbody>tr>td,.py-4>tbody>tr>td{padding-bottom:24px !important}.pl-4>tbody>tr>td,.px-4>tbody>tr>td{padding-left:24px !important}.p-5>tbody>tr>td{padding:48px !important}.pt-5>tbody>tr>td,.py-5>tbody>tr>td{padding-top:48px !important}.pr-5>tbody>tr>td,.px-5>tbody>tr>td{padding-right:48px !important}.pb-5>tbody>tr>td,.py-5>tbody>tr>td{padding-bottom:48px !important}.pl-5>tbody>tr>td,.px-5>tbody>tr>td{padding-left:48px !important}.s-lg-1>tbody>tr>td,.s-lg-2>tbody>tr>td,.s-lg-3>tbody>tr>td,.s-lg-4>tbody>tr>td,.s-lg-5>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-0>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-1>tbody>tr>td{font-size:4px !important;line-height:4px !important;height:4px !important}.s-2>tbody>tr>td{font-size:8px !important;line-height:8px !important;height:8px !important}.s-3>tbody>tr>td{font-size:16px !important;line-height:16px !important;height:16px !important}.s-4>tbody>tr>td{font-size:24px !important;line-height:24px !important;height:24px !important}.s-5>tbody>tr>td{font-size:48px !important;line-height:48px !important;height:48px !important}}@media yahoo{.d-mobile{display:none !important}.d-desktop{display:block !important}.w-lg-25{width:25% !important}.w-lg-25>tbody>tr>td{width:25% !important}.w-lg-50{width:50% !important}.w-lg-50>tbody>tr>td{width:50% !important}.w-lg-75{width:75% !important}.w-lg-75>tbody>tr>td{width:75% !important}.w-lg-100{width:100% !important}.w-lg-100>tbody>tr>td{width:100% !important}.w-lg-auto{width:auto !important}.w-lg-auto>tbody>tr>td{width:auto !important}.p-lg-0>tbody>tr>td{padding:0 !important}.pt-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-top:0 !important}.pr-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-right:0 !important}.pb-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-bottom:0 !important}.pl-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-left:0 !important}.p-lg-1>tbody>tr>td{padding:4px !important}.pt-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-top:4px !important}.pr-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-right:4px !important}.pb-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-bottom:4px !important}.pl-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-left:4px !important}.p-lg-2>tbody>tr>td{padding:8px !important}.pt-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-top:8px !important}.pr-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-right:8px !important}.pb-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-bottom:8px !important}.pl-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-left:8px !important}.p-lg-3>tbody>tr>td{padding:16px !important}.pt-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-top:16px !important}.pr-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-right:16px !important}.pb-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-bottom:16px !important}.pl-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-left:16px !important}.p-lg-4>tbody>tr>td{padding:24px !important}.pt-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-top:24px !important}.pr-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-right:24px !important}.pb-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-bottom:24px !important}.pl-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-left:24px !important}.p-lg-5>tbody>tr>td{padding:48px !important}.pt-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-top:48px !important}.pr-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-right:48px !important}.pb-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-bottom:48px !important}.pl-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-left:48px !important}.s-lg-0>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-lg-1>tbody>tr>td{font-size:4px !important;line-height:4px !important;height:4px !important}.s-lg-2>tbody>tr>td{font-size:8px !important;line-height:8px !important;height:8px !important}.s-lg-3>tbody>tr>td{font-size:16px !important;line-height:16px !important;height:16px !important}.s-lg-4>tbody>tr>td{font-size:24px !important;line-height:24px !important;height:24px !important}.s-lg-5>tbody>tr>td{font-size:48px !important;line-height:48px !important;height:48px !important}}

        </style>
</head>
  <body style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border: 0;" bgcolor="#ffffff">
<table valign="top" class="bg-light body" style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin: 0; padding: 0; border: 0;" bgcolor="#f8f9fa">
  <tbody>
    <tr>
      <td valign="top" style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; margin: 0;" align="left" bgcolor="#f8f9fa">
        
    <table class="container" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
  <tbody>
    <tr>
      <td align="center" style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; margin: 0; padding: 0 16px;">
        <!--[if (gte mso 9)|(IE)]>
          <table align="center">
            <tbody>
              <tr>
                <td width="600">
        <![endif]-->
        <table align="center" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%; max-width: 600px; margin: 0 auto;">
          <tbody>
            <tr>
              <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; margin: 0;" align="left">
               
';


		if ($customPrintableList) {
			$body .= '<h3 style="margin-top: 0; margin-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 28px; line-height: 33.6px;" align="left">My Custom Items</h3>';

			foreach ( $customPrintableList as $item ) {
				$body .= $this->renderListItem( $item['title'], $item['is_heading'], $item['heading'] );
			}
		}

		if($printableList) {
			foreach ( $printableList as $item ) {
				$body .= $this->renderListItem( $item['title']['rendered'], $item['is_heading'], $item['heading'] );
			}
		}





		$body .= '</td>
            </tr>
          </tbody>
        </table>
        <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
            </tbody>
          </table>
        <![endif]-->
      </td>
    </tr>
  </tbody>
</table>

  
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>';

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		$sendmail = wp_mail( $email, $subject, $body, $headers );

		$return = array(
			'mail_sent'  => $sendmail,
		);

		wp_send_json($return);



	}



	private function renderListItem( $title, $is_heading, $heading ) {

		if ( $is_heading === 'true' ) {

			return '<h3 style="margin-top: 0; margin-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 28px; line-height: 33.6px;" align="left">' . $heading . '</h3>';

		} else {

			return '
      <table class="s-1 w-100" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
  <tbody>
    <tr>
      <td height="4" style="border-spacing: 0px; border-collapse: collapse; line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left">
        
      </td>
    </tr>
  </tbody>
</table>

<table class="card " border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: separate !important; border-radius: 4px; width: 100%; overflow: hidden; border: 1px solid #dee2e6;" bgcolor="#ffffff">
  <tbody>
    <tr>
      <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">
        <div>
        <table class="card-body" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
  <tbody>
    <tr>
      <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; width: 100%; margin: 0; padding: 20px;" align="left">
        <div>
          <h5 class="text-muted " style="margin-top: 0; margin-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 20px; line-height: 24px; color: #636c72;" align="left">
<span style="display: inline-block; width: 20px; height: 20px; border-radius: 4px; margin-right: 10px; border: 4px solid;"></span>' . $title . '</h5>
<table class="s-2 w-100" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
  <tbody>
    <tr>
      <td height="8" style="border-spacing: 0px; border-collapse: collapse; line-height: 8px; font-size: 8px; width: 100%; height: 8px; margin: 0;" align="left">
         
      </td>
    </tr>
  </tbody>
</table>


        </div>
      </td>
    </tr>
  </tbody>
</table>

      </div>
      </td>
    </tr>
  </tbody>
</table>
<table class="s-1 w-100" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
  <tbody>
    <tr>
      <td height="4" style="border-spacing: 0px; border-collapse: collapse; line-height: 4px; font-size: 4px; width: 100%; height: 4px; margin: 0;" align="left">
         
      </td>
    </tr>
  </tbody>
</table>';
		}
	}


}
