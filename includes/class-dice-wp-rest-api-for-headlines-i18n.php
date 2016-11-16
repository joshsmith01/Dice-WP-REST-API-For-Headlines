<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://confluence.dice.com/display/WP/WordPress
 * @since      1.0.0
 *
 * @package    Dice_Wp_Rest_Api_For_Headlines
 * @subpackage Dice_Wp_Rest_Api_For_Headlines/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dice_Wp_Rest_Api_For_Headlines
 * @subpackage Dice_Wp_Rest_Api_For_Headlines/includes
 * @author     Josh Smith <josh.smith@dice.com>
 */
class Dice_Wp_Rest_Api_For_Headlines_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dice-wp-rest-api-for-headlines',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
