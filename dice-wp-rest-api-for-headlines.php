<?php

/**
 * @link              https://confluence.dice.com/display/WP/WordPress
 * @since             1.0.0
 * @package           Dice_Wp_Rest_Api_For_Headlines
 * @prefix            dwrafh = Dice WP REST API For Headlines
 * @wordpress-plugin
 * Plugin Name:       Dice WP REST API For Headlines
 * Plugin URI:        https://confluence.dice.com/display/WP/Dice+WP+REST+API+For+Headlines
 * Description:       Use the WP REST API and custom Dice endpoints to automate posting of new articles to Dice.com.
 * Version:           1.0.1
 * Author:            Josh Smith
 * Author URI:        https://confluence.dice.com/display/WP/WordPress
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dice-wp-rest-api-for-headlines
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/DiceHoldingsInc/Dice-WP-REST-API-For-Headlines
 * GitHub Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Make sure the plugin has access to its own files. -JMS
require_once plugin_dir_path( __FILE__ ) . 'includes/headlines-meta-box.php';
require_once plugin_dir_path(__FILE__) . 'admin/headlines-settings.php';
require_once plugin_dir_path( __FILE__ ) . 'api/api-headlines.php';

// Enqueue some scripts and styles here. -JMS
function dwrafh_enqueue_scripts($hook) {
    // Use this to determine your $hook:
	//wp_die($hook);
	    wp_enqueue_style( 'dwrafh-admin-css', plugins_url( 'admin/css/admin-headlines.css', __FILE__ ) );

	    // Load up the tabs and media upload where it's needed, on the post page. -JMS
	    if ($hook === 'post.php') {
		    wp_enqueue_script( 'dwrafh-admin-js', plugins_url( 'admin/js/admin-headlines.js', __FILE__ ), array(
			    'jquery',
			    'media-upload',
			    'jquery-ui-tabs'
		    ), '20161115', true );
	    }

	    // Load up the reorder script where it is needed, on the Dashboard page. -JMS
		if ( $hook === 'index.php' ) {
			wp_enqueue_script( 'reorder-js', plugins_url( 'admin/js/reorder.js', __FILE__ ), array(
				'jquery',
				'jquery-ui-sortable'
			), '20161115', true );
		}

		// Move the field values from JavaScript to PHP. -JMS
	    wp_localize_script( 'reorder-js', 'WP_HEADLINE_LISTING', array(
		    'security' => wp_create_nonce( 'wp-headline-order' ),
		    'success'  => 'Headlines sort order has been saved',
		    'failure'  => 'There was an error saving the sort order, or you do not have the proper permissions.'
	    ) );

	wp_enqueue_script( 'flatpickr', plugins_url( '/admin/js/flatpickr.min.js', __FILE__ ), array( 'jquery' ), true );
	wp_localize_script( 'dwrafh-admin-js',
		'customUploads',
		array(
			'smallBannerData' => get_post_meta( get_the_ID(), 'banner_small_data', true ),
			'largeBannerData' => get_post_meta( get_the_ID(), 'banner_large_data', true )
		) );

	add_meta_box( 'headlines-metabox', __( 'Headlines Meta' ), 'dwrafh_headlines_metabox', 'post', 'normal', 'high'/*,array()*/ );
}
add_action('admin_enqueue_scripts', 'dwrafh_enqueue_scripts');

