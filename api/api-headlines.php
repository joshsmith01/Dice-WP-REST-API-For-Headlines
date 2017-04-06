<?php
/**
 * Created by PhpStorm.
 * User: joshsmith01
 * Date: 11/17/16
 * Time: 14:33
 */

/**
 * Grab latest post title by an author!
 *
 *
 *
 *
 *
 */

// Normally this function would take at parameter of $data, derived from the request URL, but we don't need anything here. -JMS
function dwrafh_prepare_response() {
	// Set up some query parameters. -JMS
	$args = array(
		'posts_per_page'    => 5,
		'category_name'     => 'headline',
		'orderby'           => 'menu_order',
		'order'             => 'ASC',
        'post_status'       => array('publish', 'future'),
	);

	$posts = get_posts( $args );


	$post_headlines_array = array();

	if ( empty( $posts ) ) {
//		return null;
		return new WP_Error( 'dwrafh_prepare_response', 'No Posts found. Bummer :(', array( 'status' => 404 ) );
	}

	// Loop over each post, which will be 3, and unset fields. -JMS
	$i = 0;
	foreach ( $posts as $post  ) {
		$post_meta = get_post_meta($post->ID);
		$permalink = get_permalink($post->ID);
		$description = $post_meta['headline_description'][0];
		$banner_image_large_data = maybe_unserialize( $post_meta['banner_large_data'][0] );
		$banner_image_small_data = maybe_unserialize( $post_meta['banner_small_data'][0] );

		$large_alt = get_post($post->ID);

		unset( $post->post_date );
		unset( $post->post_date_gmt );
		unset( $post->post_content );
		unset( $post->post_excerpt );
		unset( $post->post_status );
		unset( $post->comment_status );
		unset( $post->comment_count );
		unset( $post->ping_status );
		unset( $post->post_password );
		unset( $post->to_ping );
		unset( $post->pinged );
		unset( $post->post_modified );
		unset( $post->post_modified_gmt );
		unset( $post->post_content_filtered );
		unset( $post->post_parent );
		unset( $post->guid );
		unset( $post->menu_order );
		unset( $post->post_type );
		unset( $post->post_mime_type );
		unset( $post->comment_count );
		unset( $post->filter );

		$post_headlines_array[ $i ]['id'] = (int)$post->ID;
		$post_headlines_array[ $i ]['title'] = esc_html($post->post_title);
		if ( $description ) {
			$post_headlines_array[ $i ]['description'] = esc_html( $description );
		} else {
			$post_headlines_array[ $i ]['description'] = null;
		}

		$post_headlines_array[ $i ]['link'] = esc_url( $permalink );

		if ( $banner_image_large_data ) {
			$post_headlines_array[ $i ]['banners']['large']['url'] = esc_url($banner_image_large_data['src']);
			$post_headlines_array[ $i ]['banners']['large']['alt'] = esc_html($banner_image_large_data['alt']);
		} else {
			$post_headlines_array[ $i ]['banners']['large'] = null;
		}

		if ( $banner_image_small_data ) {
			$post_headlines_array[ $i ]['banners']['small']['url'] = esc_url($banner_image_small_data['src']);
			$post_headlines_array[ $i ]['banners']['small']['alt'] = esc_html($banner_image_small_data['alt']);
		} else {
			$post_headlines_array[ $i ]['banners']['small'] = null;
		}

		$i++;
	}

	return $post_headlines_array;
}

// Register the route
add_action( 'rest_api_init', function () {
	register_rest_route( 'dice-headlines/v1', '/headlines', array(
		'methods'  => 'GET',
		'callback' => 'dwrafh_prepare_response',
	) );
} );
