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
 */

// Normally this function would take at parameter of $data, derived from the request URL, but we don't need anything here. -JMS
function dwrafh_prepare_response() {
	// Set up some query parameters. -JMS
	$args = array(
		'posts_per_page'    => get_option( 'headlines_per_day' ),
		'orderby'           => 'menu_order',
		'order'             => 'ASC',
        'post_status'       => array('publish', 'future'),
		'post_type'         => array('post', 'dice_ideal_employer', 'erc-post'),
		'tax_query'         => array(
			'relation'      => 'OR',
			array(
				'taxonomy' => 'ideal-employer-category',
				'field'     => 'slug',
				'terms'     => 'headline'
			),
			array(
				'taxonomy' => 'erc_category',
				'field'    => 'slug',
				'terms'    => 'headline'
			),
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => 'headline'
			)
		)
	);

	$posts = get_posts( $args );
	$post_headlines_array = array();
	$options = get_option( 'dwrafh_settings' );
	$dwrafh_cdn_url = $options['dwrafh_text_field_0'];
	$dwrafh_override_url = $options['dwrafh_text_field_1'];
	if ( empty( $posts ) ) {
		return new WP_Error( 'dwrafh_prepare_response', 'No Posts found. Bummer :(', array( 'status' => 404 ) );
	}

	// Loop over each post, which will be 3, and unset fields. -JMS
	$i = 0;
	foreach ( $posts as $post  ) {
		$post_meta = get_post_meta($post->ID);
		$permalink = get_permalink($post->ID);
		$author = get_the_author_meta('display_name', $post->post_author);
		$description = $post_meta['headline_description'][0];
		$banner_image_large_data = maybe_unserialize( $post_meta['banner_large_data'][0] );
		$banner_image_small_data = maybe_unserialize( $post_meta['banner_small_data'][0] );
		$headline_tracking_code = $post_meta['headline_tracking_code'][0];

		$banner_image_large_cdn_data = wp_make_link_relative( $banner_image_large_data['src'] );
		$banner_image_small_cdn_data = wp_make_link_relative( $banner_image_small_data['src'] );
		$permalink_relative = wp_make_link_relative($permalink);


		unset(  $post->post_date,
//			$post->post_date_gmt,
			$post->post_content,
			$post->post_excerpt,
			$post->post_status,
			$post->comment_status,
			$post->comment_count,
			$post->ping_status,
			$post->post_password,
			$post->to_ping,
			$post->pinged,
			$post->post_modified,
			$post->post_modified_gmt,
			$post->post_content_filtered,
			$post->post_parent,
			$post->guid,
			$post->menu_order,
			$post->post_type,
			$post->post_mime_type,
			$post->comment_count,
			$post->filter
	);

		$post_headlines_array[ $i ]['id'] = (int)$post->ID;
		$post_headlines_array[ $i ]['title'] = esc_html($post->post_title);
		$post_headlines_array[ $i ]['published_date'] = strtotime($post->post_date_gmt);
		$post_headlines_array[ $i ]['author_display_name'] = $author;
		if ( $description ) {
			$post_headlines_array[ $i ]['description'] = esc_html( $description );
		} else {
			$post_headlines_array[ $i ]['description'] = null;
		}

		if ( $dwrafh_override_url ) {
			$post_headlines_array[ $i ]['link'] = $dwrafh_override_url . $permalink_relative;
		} else {
			$post_headlines_array[ $i ]['link'] = esc_url( $permalink );
		}

		/**
		 * Check to see if there is a CDN first and use that if there is. If not, check to see if there is an override URL, if there is use that. Lastly, use the application URL since nothing else will work.
		 */
		if ( $banner_image_large_data ) {
			if ($dwrafh_cdn_url) {
				$post_headlines_array[ $i ]['banners']['large']['url'] = esc_url( $dwrafh_cdn_url . $banner_image_large_cdn_data );
				$post_headlines_array[ $i ]['banners']['large']['alt'] = esc_html( $banner_image_large_data['alt'] );
			} elseif ( $dwrafh_override_url ) {
				$post_headlines_array[ $i ]['banners']['large']['url'] = esc_url( $dwrafh_override_url . $banner_image_large_cdn_data );
				$post_headlines_array[ $i ]['banners']['large']['alt'] = esc_html( $banner_image_large_data['alt'] );
			} else {
				$post_headlines_array[ $i ]['banners']['large']['url'] = esc_url( $banner_image_large_data['src'] );
				$post_headlines_array[ $i ]['banners']['large']['alt'] = esc_html( $banner_image_large_data['alt'] );
			}
		} else {
			$post_headlines_array[ $i ]['banners']['large'] = null;
		}

		if ( $banner_image_small_data ) {
			if( $dwrafh_cdn_url ) {
				$post_headlines_array[ $i ]['banners']['small']['url'] = esc_url( $dwrafh_cdn_url . $banner_image_small_cdn_data );
				$post_headlines_array[ $i ]['banners']['small']['alt'] = esc_html( $banner_image_small_data['alt'] );
			} elseif ( $dwrafh_override_url ) {
				$post_headlines_array[ $i ]['banners']['small']['url'] = esc_url( $dwrafh_override_url . $banner_image_small_cdn_data );
				$post_headlines_array[ $i ]['banners']['small']['alt'] = esc_html( $banner_image_small_data['alt'] );
			}
			else {
				$post_headlines_array[ $i ]['banners']['small']['url'] = esc_url( $banner_image_small_data['src'] );
				$post_headlines_array[ $i ]['banners']['small']['alt'] = esc_html( $banner_image_small_data['alt'] );
			}
		} else {
			$post_headlines_array[ $i ]['banners']['small'] = null;
		}

		if ( $headline_tracking_code ) {
			$post_headlines_array[$i]['headline_tracking_code'] = $headline_tracking_code;
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
