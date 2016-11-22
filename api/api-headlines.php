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
		'posts_per_page'    => 3,
		'category_name'     => 'headline',
		'orderby'           => 'menu_order',
		'order'             => 'ASC',
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
		$permalink = get_permalink($post->ID);
		$description = get_field('description', $post->ID);
		$bannerimage = get_field( 'bannerimage', $post->ID );
		$banner_large = get_field( 'banner_large', $post->ID );
		$banner_small = get_field( 'banner_small', $post->ID );


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

		$post_headlines_array[ $i ]['id'] = $post->ID;
		$post_headlines_array[ $i ]['title'] = esc_html($post->post_title);
		if ( $description ) {
			$post_headlines_array[ $i ]['description'] = esc_html( $description );
		} else {
			$post_headlines_array[ $i ]['description'] = null;
		}

		$post_headlines_array[ $i ]['link'] = $permalink;

		if ( $bannerimage ) {
			$post_headlines_array[ $i ]['bannerImage'] = $bannerimage;
		} else {
			$post_headlines_array[ $i ]['bannerImage'] = null;
		}

		if ( $banner_large ) {
			$post_headlines_array[ $i ]['banners']['large']['url'] = $banner_large['url'];
			$post_headlines_array[ $i ]['banners']['large']['alt'] = $banner_large['alt'];
		} else {
			$post_headlines_array[ $i ]['banners']['large'] = null;
		}

		if ( $banner_small ) {
			$post_headlines_array[ $i ]['banners']['small']['url'] = $banner_small['url'];
			$post_headlines_array[ $i ]['banners']['small']['alt'] = $banner_small['alt'];
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
