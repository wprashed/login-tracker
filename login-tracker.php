<?php
/**
 *
 * Plugin Name:       Login Tracker
 * Plugin URI:        https://wordpress.org/plugins/login-tracker
 * Description:       Tracks user login activity and displays it on the dashboard.
 * Version:           1.0.0
 * Author:            Md Rashed Hossain
 * Author URI:        https://wprashed.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       login-tracker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'LOGIN_TRACKER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-login-tracker-activator.php
 */
function activate_login_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-login-tracker-activator.php';
	Login_Tracker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-login-tracker-deactivator.php
 */
function deactivate_login_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-login-tracker-deactivator.php';
	Login_Tracker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_login_tracker' );
register_deactivation_hook( __FILE__, 'deactivate_login_tracker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-login-tracker.php';

/**
 * Begins execution of the plugin.
 * @since    1.0.0
 */
function run_login_tracker() {

	$plugin = new Login_Tracker();
	$plugin->run();

}
run_login_tracker();
