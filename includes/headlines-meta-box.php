<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function dwrafh_headlines_metabox( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'banner_nonce' );

	$dwrafh_post_meta = get_post_meta( $post->ID);
	?>
	<p class="timezone-info">
		<?php $timezone_format = _x( 'Y-m-d H:i', 'timezone date format' ); ?>
		<?php if ( get_option( 'timezone_string' ) || ! empty( $current_offset ) ) : ?>
			<span id="local-time"><?php
				/* translators: %s: local time */
				printf( __( 'Local Blog time is %s' ),
					'<code>' . date_i18n( $timezone_format ) . '</code>'
				);
				?></span>
		<?php endif; ?>
	</p>


	<div id="mytabs">
		<ul class="category-tabs">
			<li class="tabs"><a href="#frag1"><?php echo esc_html( 'Headline Meta' ) ?></a></li>
			<li class="tabs"><a href="#frag2"><?php echo esc_html( 'Banner Small' ) ?></a></li>
			<li class="tabs"><a href="#frag3"><?php echo esc_html( 'Banner Large' ) ?></a></li>
		</ul>
<!--		<br class="clear"/>-->
		<div id="frag1" class="tabs-panel">
			<label for="headline-description"><?php echo esc_html( 'Headline Description' ) ?></label>
			<p class="description"><?php echo esc_html( 'Use 150 characters of text to get people to click the article and read more from the Dice.com homepage' ) ?></p>
			<input type="text" id="headline-description-id" name="headline_description" value="<?php  if (isset($dwrafh_post_meta['headline_description'])) {echo $dwrafh_post_meta['headline_description'][0]; }?>">
			<hr>
			<label for="post_expiration"><?php echo esc_html( 'Post Expiration' ) ?></label>
			<p class="description"><?php echo esc_html( 'All dates and times are in the ') ?><?php echo get_option('timezone_string') . esc_html(' timezone. Keep this in mind when you set a date. The datepicker may display the current local time to you but when the category of \'headlines\' actually gets expired, it will be in the blogs, time zone. In this case, the timezone is ')?><?php echo get_option('timezone_string') ?>.</p>
			<input type="text" id="post-expiration" name="post_expiration" placeholder="Pick Expiration" value="<?php if ( isset( $dwrafh_post_meta['post_expiration'] ) ) {
				echo $dwrafh_post_meta['post_expiration'][0];
			} ?>">
            <a id="clear-post-expiration" class="input-button button"><?php echo esc_html( 'Clear Expiration' ) ?></a>
		</div>

		<div class="hidden tabs-panel" id="frag2">
			<img src="" alt="" id="banner-small">
			<input type="hidden" id="banner-small-hidden-field" name="banner_small_data">
			<input type="button" id="banner-small-upload-button" class="button" value="Add Small Banner">
			<input type="button" id="banner-small-delete-button" class="button" value="Remove Banner">
		</div>

		<div class="hidden tabs-panel" id="frag3">
            <img src="" alt="" id="banner-large">
            <input type="hidden" id="banner-large-hidden-field" name="banner_large_data">
            <input type="button" id="banner-large-upload-button" class="button" value="Add Large Banner">
            <input type="button" id="banner-large-delete-button" class="button" value="Remove Banner">
		</div>
	</div>



	<?php
}

function save_banner_data( $post_id ) {
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'banner_nonce' ] ) && wp_verify_nonce( $_POST['banner_nonce' ], basename( __FILE__ ) ) );

	// Exits script depending on save status. -JMS
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}

	// Save the small banner. -JMS
	if (isset($_POST['banner_small_data'])) {
		$small_banner_data = json_decode(stripslashes( $_POST['banner_small_data'] ));
		$test = 0;
		if (is_object( $small_banner_data[0] ) ) {
		    $small_banner_data = array( 'id' => intval($small_banner_data[0]->id),
                                        'src' => esc_url_raw( $small_banner_data[0]->src),
                                        'alt' => $small_banner_data[0]->alt
            );
		} else {
		    $small_banner_data = [];
        }
        update_post_meta($post_id, 'banner_small_data', $small_banner_data);
	}

	// Save the large banner. -JMS
	if ( isset( $_POST['banner_large_data'] ) ) {
		$large_banner_data = json_decode( stripslashes( $_POST['banner_large_data'] ) );
		$test = 0;
		if ( is_object( $large_banner_data[0] ) ) {
			$large_banner_data = array( 'id'  => intval( $large_banner_data[0]->id ),
			                            'src' => esc_url_raw( $large_banner_data[0]->src ),
			                            'alt' => $large_banner_data[0]->alt
			);
		} else {
			$large_banner_data = [];
		}
		update_post_meta( $post_id, 'banner_large_data', $large_banner_data );
	}

	// Save the headline description
    if (isset( $_POST['headline_description'])) {
	    update_post_meta( $post_id, 'headline_description', $_POST['headline_description']);
    }

    // Save the legacy image selection. -JMS
    if (isset( $_POST['banner_image_select'])) {
	    update_post_meta( $post_id, 'banner_image_select', $_POST['banner_image_select'] );
    }

    // Save the date time expiration. -JMS
	if ( isset( $_POST['post_expiration'] ) ) {
		update_post_meta( $post_id, 'post_expiration', $_POST['post_expiration'] );
	}



}
add_action('save_post', 'save_banner_data');