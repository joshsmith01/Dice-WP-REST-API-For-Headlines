<?php

add_action( 'admin_menu', 'dwrafh_add_admin_menu' );
add_action( 'admin_init', 'dwrafh_settings_init' );


function dwrafh_add_admin_menu() {

	add_options_page( 'Dice Headlines API Settings', 'Headlines API', 'manage_options', 'dice_headlines_api_settings', 'dwrafh_options_page' );

}


function dwrafh_settings_init() {

	register_setting( 'dwrafh_pluginPage', 'dwrafh_settings' );

	add_settings_section(
		'dwrafh_pluginPage_section',
		__( 'CDN URL', 'Dice-WP-REST-API-For-Headlines' ),
		'dwrafh_settings_section_callback',
		'dwrafh_pluginPage'
	);

	add_settings_field(
		'dwrafh_text_field_0',
		__( 'Add a CDN URL', 'Dice-WP-REST-API-For-Headlines' ),
		'dwrafh_text_field_0_render',
		'dwrafh_pluginPage',
		'dwrafh_pluginPage_section'
	);
}

function dwrafh_text_field_0_render() {

	$options = get_option( 'dwrafh_settings' ); ?>
	<input class="regular-text" type='url' name='dwrafh_settings[dwrafh_text_field_0]'
	       value='<?php echo $options['dwrafh_text_field_0']; ?>'>
	<?php

}


function dwrafh_settings_section_callback() {

	echo __( '<p>Add CDN link and WordPress will offer the images to the mobile clients via the CDN to Headlines API</p>', 'Dice-WP-REST-API-For-Headlines' );

}

function dwrafh_options_page() { ?>
	<form action='options.php' method='post'>
		<h2><?php _e( 'Dice Headlines API Settings', 'Dice-WP-REST-API-For-Headlines' ); ?></h2>

		<?php
		settings_fields( 'dwrafh_pluginPage' );
		do_settings_sections( 'dwrafh_pluginPage' );
		submit_button();
		?>

	</form>
	<?php
}


/**
 * Add a Settings link to the plugins list page.
 *
 * @param $links
 *
 * @return mixed
 */
function plugin_add_dwrafh_settings_link( $links ) {
	$settings_link = '<a href="/wp-admin/options-general.php?page=dice_headlines_api_settings">' . __( 'CDN Settings' ) . '</a>';
	array_push( $links, $settings_link );

	return $links;
}

$plugin = plugin_basename( realpath( __DIR__ . '/../dice-wp-rest-api-for-headlines.php' ) );

add_filter( "plugin_action_links_$plugin", 'plugin_add_dwrafh_settings_link' );
