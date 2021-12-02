<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://krogerkrazy.com
 * @since             1.0.0
 * @package           Krogerkrazy
 *
 * @wordpress-plugin
 * Plugin Name:       Kroger Krazy
 * Plugin URI:        https://krogerkrazy.com
 * Description:       Create coupon lists to allow users to easily select from a list of coupons that can be printed.
 * Version:           1.1.0
 * Author:            Kroger Krazy
 * Author URI:        https://krogerkrazy.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       krogerkrazy
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'KROGERKRAZY_VERSION', '1.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-krogerkrazy-activator.php
 */
function activate_krogerkrazy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-krogerkrazy-activator.php';
	Krogerkrazy_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-krogerkrazy-deactivator.php
 */
function deactivate_krogerkrazy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-krogerkrazy-deactivator.php';
	Krogerkrazy_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_krogerkrazy' );
register_deactivation_hook( __FILE__, 'deactivate_krogerkrazy' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-krogerkrazy.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_krogerkrazy() {

	require 'plugin-update-checker/plugin-update-checker.php';
	$pluginUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/jefferykarbowski/Kroger-Krazy',
		__FILE__,
		'kroger-krazy'
	);

	$pluginUpdateChecker->setBranch('main');
	$pluginUpdateChecker->setAuthentication('55fc9310055924d57bd72f9d496d820f3036829e');

	$plugin = new Krogerkrazy();
	$plugin->run();

}
run_krogerkrazy();
