<?php

/**
 * @link              https://confluence.dice.com/display/WP/WordPress
 * @since             1.0.0
 * @package           Dice_Wp_Rest_Api_For_Headlines
 *
 * @wordpress-plugin
 * Plugin Name:       Dice WP REST API For Headlines
 * Plugin URI:        https://confluence.dice.com/display/WP/Dice+WP+REST+API+For+Headlines
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           2016-11-16
 * Author:            Josh Smith
 * Author URI:        https://confluence.dice.com/display/WP/WordPress
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dice-wp-rest-api-for-headlines
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dice-wp-rest-api-for-headlines-activator.php
 */
function activate_dice_wp_rest_api_for_headlines() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dice-wp-rest-api-for-headlines-activator.php';
	Dice_Wp_Rest_Api_For_Headlines_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dice-wp-rest-api-for-headlines-deactivator.php
 */
function deactivate_dice_wp_rest_api_for_headlines() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dice-wp-rest-api-for-headlines-deactivator.php';
	Dice_Wp_Rest_Api_For_Headlines_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dice_wp_rest_api_for_headlines' );
register_deactivation_hook( __FILE__, 'deactivate_dice_wp_rest_api_for_headlines' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dice-wp-rest-api-for-headlines.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dice_wp_rest_api_for_headlines() {

	$plugin = new Dice_Wp_Rest_Api_For_Headlines();
	$plugin->run();

}
run_dice_wp_rest_api_for_headlines();
