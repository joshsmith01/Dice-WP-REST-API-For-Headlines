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

			<label for="banner_image_select"><?php echo esc_html( 'Banner Image Choice' ) ?></label>
			<p class="description"><?php echo esc_html( 'Choose the name of the image from the list below. These images chosen here are loaded and served from the Dice.com server. These images are not maintained in WordPress at all.' ) ?></p>
            <?php // These are legacy choices that will eventually be removed from the site. Currently this is how the choice is made on Dice.com homepage, but we are in a transistion period where image values are selected based upon the selected option value. -JMS ?>
			<select id="banner-image-select" name="banner_image_select">
                <option value="select" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'select' ) ?>>
                    <?php echo esc_html('Select'); ?>
                </option>
                <option value="amazon" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'amazon' ) ?>>
	                <?php echo esc_html( 'amazon' ) ?>
                </option><?php echo esc_html( '' ) ?>
                <option value="android" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'android' ) ?>>
	                <?php echo esc_html( 'android' ) ?>
                </option>
                <option value="angularjs-campaign" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'angularjs-campaign' ) ?>>
	                <?php echo esc_html( 'angularjs-campaign' ) ?>
                </option>
                <option value="angularjs-v2-campaign" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'angularjs-v2-campaign' ) ?>>
	                <?php echo esc_html( 'angularjs-v2-campaign' ) ?>
                </option>
                <option value="apple" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'apple' ) ?>>
	                <?php echo esc_html( 'apple' ) ?>
                </option>
                <option value="automotive" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'automotive' ) ?>>
	                <?php echo esc_html( 'automotive' ) ?>
                </option>
                <option value="banner01" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'banner01' ) ?>>
	                <?php echo esc_html( 'banner01' ) ?>
                </option>
                <option value="banner02" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'banner02' ) ?>>
	                <?php echo esc_html( 'banner02' ) ?>
                </option>
                <option value="banner03" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'banner03' ) ?>>
	                <?php echo esc_html( 'banner03' ) ?>
                </option>
                <option value="banner04" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'banner04' ) ?>>
	                <?php echo esc_html( 'banner04' ) ?>
                </option>
                <option value="banner05" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'banner05' ) ?>>
	                <?php echo esc_html( 'banner05' ) ?>
                </option>
                <option value="banner06" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'banner06' ) ?>>
	                <?php echo esc_html( 'banner06' ) ?>
                </option>
                <option value="banner07" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'banner07' ) ?>>
	                <?php echo esc_html( 'banner07' ) ?>
                </option>
                <option value="blockchain" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'blockchain' ) ?>>
	                <?php echo esc_html( 'blockchain' ) ?>
                </option>
                <option value="bob" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'bob' ) ?>>
	                <?php echo esc_html( 'bob' ) ?>
                </option>
                <option value="certification" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'certification' ) ?>>
	                <?php echo esc_html( 'certification' ) ?>
                </option>
                <option value="city" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'city' ) ?>>
	                <?php echo esc_html( 'city' ) ?>
                </option>
                <option value="cloud" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'cloud' ) ?>>
	                <?php echo esc_html( 'cloud' ) ?>
                </option>
                <option value="cloud-v2-campaign" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'cloud-v2-campaign' ) ?>>
	                <?php echo esc_html( 'cloud-v2-campaign' ) ?>
                </option>
                <option value="coding-at-work" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'coding-at-work' ) ?>>
	                <?php echo esc_html( 'coding-at-work' ) ?>
                </option>
                <option value="communication" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'communication' ) ?>>
	                <?php echo esc_html( 'communication' ) ?>
                </option>
                <option value="computer" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'computer' ) ?>>
	                <?php echo esc_html( 'computer' ) ?>
                </option>
                <option value="computer-illustration" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'computer-illustration' ) ?>>
	                <?php echo esc_html( 'computer-illustration' ) ?>
                </option>
                <option value="computer-tip-mac" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'computer-tip-mac' ) ?>>
	                <?php echo esc_html( 'computer-tip-mac' ) ?>
                </option>
                <option value="computer-tip-pc" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'computer-tip-pc' ) ?>>
	                <?php echo esc_html( 'computer-tip-pc' ) ?>
                </option>
                <option value="culture" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'culture' ) ?>>
	                <?php echo esc_html( 'culture' ) ?>
                </option>
                <option value="data" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'data' ) ?>>
	                <?php echo esc_html( 'data' ) ?>
                </option>
                <option value="devops-campaign" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'devops-campaign' ) ?>>
	                <?php echo esc_html( 'devops-campaign' ) ?>
                </option>
                <option value="dice-app" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'dice-app' ) ?>>
	                <?php echo esc_html( 'dice-app' ) ?>
                </option>
                <option value="diceSkills" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'diceSkills' ) ?>>
	                <?php echo esc_html( 'diceSkills' ) ?>
                </option>
                <option value="DR_8" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'DR_8' ) ?>>
	                <?php echo esc_html( 'DR_8' ) ?>
                </option>
                <option value="drone" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'drone' ) ?>>
	                <?php echo esc_html( 'drone' ) ?>
                </option>
                <option value="education" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'education' ) ?>>
	                <?php echo esc_html( 'education' ) ?>
                </option>
                <option value="facebook" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'facebook' ) ?>>
	                <?php echo esc_html( 'facebook' ) ?>
                </option>
                <option value="future" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'future' ) ?>>
	                <?php echo esc_html( 'future' ) ?>
                </option>
                <option value="games" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'games' ) ?>>
	                <?php echo esc_html( 'games' ) ?>
                </option>
                <option value="gaming" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'gaming' ) ?>>
	                <?php echo esc_html( 'gaming' ) ?>
                </option>
                <option value="google" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'google' ) ?>>
	                <?php echo esc_html( 'google' ) ?>
                </option>
                <option value="government" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'government' ) ?>>
	                <?php echo esc_html( 'government' ) ?>
                </option>
                <option value="government-2" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'government-2' ) ?>>
	                <?php echo esc_html( 'government-2' ) ?>
                </option>
                <option value="healthcare" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'healthcare' ) ?>>
	                <?php echo esc_html( 'healthcare' ) ?>
                </option>
                <option value="hiring" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'hiring' ) ?>>
	                <?php echo esc_html( 'hiring' ) ?>
                </option>
                <option value="interview" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'interview' ) ?>>
	                <?php echo esc_html( 'interview' ) ?>
                </option>
                <option value="interview-2" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'interview-2' ) ?>>
	                <?php echo esc_html( 'interview-2' ) ?>
                </option>
                <option value="iphone" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'iphone' ) ?>>
	                <?php echo esc_html( 'iphone' ) ?>
                </option>
                <option value="likely" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'likely' ) ?>>
	                <?php echo esc_html( 'likely' ) ?>
                </option>
                <option value="linux" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'linux' ) ?>>
	                <?php echo esc_html( 'linux' ) ?>
                </option>
                <option value="meet-coffee" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'meet-coffee' ) ?>>
	                <?php echo esc_html( 'meet-coffee' ) ?>
                </option>
                <option value="microsoft" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'microsoft' ) ?>>
	                <?php echo esc_html( 'microsoft' ) ?>
                </option>
                <option value="mobile" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'mobile' ) ?>>
	                <?php echo esc_html( 'mobile' ) ?>
                </option>
                <option value="mobile-cover-letter" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'mobile-cover-letter' ) ?>>
	                <?php echo esc_html( 'mobile-cover-letter' ) ?>
                </option>
                <option value="needs" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'needs' ) ?>>
	                <?php echo esc_html( 'needs' ) ?>
                </option>
                <option value="oculus-rift" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'oculus-rift' ) ?>>
	                <?php echo esc_html( 'oculus-rift' ) ?>
                </option>
                <option value="opensource" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'opensource' ) ?>>
	                <?php echo esc_html( 'opensource' ) ?>
                </option>
                <option value="outsourcing" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'outsourcing' ) ?>>
	                <?php echo esc_html( 'outsourcing' ) ?>
                </option>
                <option value="photoshoot" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'photoshoot' ) ?>>
	                <?php echo esc_html( 'photoshoot' ) ?>
                </option>
                <option value="programming" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'programming' ) ?>>
	                <?php echo esc_html( 'programming' ) ?>
                </option>
                <option value="python" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'python' ) ?>>
	                <?php echo esc_html( 'python' ) ?>
                </option>
                <option value="quiz" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'quiz' ) ?>>
	                <?php echo esc_html( 'quiz' ) ?>
                </option>
                <option value="resume" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'resume' ) ?>>
	                <?php echo esc_html( 'resume' ) ?>
                </option>
                <option value="robotics" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'robotics' ) ?>>
	                <?php echo esc_html( 'robotics' ) ?>
                </option>
                <option value="ruby" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'ruby' ) ?>>
	                <?php echo esc_html( 'ruby' ) ?>
                </option>
                <option value="security" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'security' ) ?>>
	                <?php echo esc_html( 'security' ) ?>
                </option>
                <option value="security-campaign" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'security-campaign' ) ?>>
	                <?php echo esc_html( 'security-campaign' ) ?>
                </option>
                <option value="selfDrive" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'selfDrive' ) ?>>
	                <?php echo esc_html( 'selfDrive' ) ?>
                </option>
                <option value="selfie-photos" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'selfie-photos' ) ?>>
	                <?php echo esc_html( 'selfie-photos' ) ?>
                </option>
                <option value="servers" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'servers' ) ?>>
	                <?php echo esc_html( 'servers' ) ?>
                </option>
                <option value="silicon-valley" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'silicon-valley' ) ?>>
	                <?php echo esc_html( 'silicon-valley' ) ?>
                </option>
                <option value="social-network" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'social-network' ) ?>>
	                <?php echo esc_html( 'social-network' ) ?>
                </option>
                <option value="startup" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'startup' ) ?>>
	                <?php echo esc_html( 'startup' ) ?>
                </option>
                <option value="tablet" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'tablet' ) ?>>
	                <?php echo esc_html( 'tablet' ) ?>
                </option>
                <option value="tablet2" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'tablet2' ) ?>>
	                <?php echo esc_html( 'tablet2' ) ?>
                </option>
                <option value="techie" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'techie' ) ?>>
	                <?php echo esc_html( 'techie' ) ?>
                </option>
                <option value="tesla" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'tesla' ) ?>>
	                <?php echo esc_html( 'tesla' ) ?>
                </option>
                <option value="trendline" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'trendline' ) ?>>
	                <?php echo esc_html( 'trendline' ) ?>
                </option>
                <option value="trends" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'trends' ) ?>>
	                <?php echo esc_html( 'trends' ) ?>
                </option>
                <option value="wearables" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'wearables' ) ?>>
	                <?php echo esc_html( 'wearables' ) ?>
                </option>
                <option value="woman-tech" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'woman-tech' ) ?>>
	                <?php echo esc_html( 'woman-tech' ) ?>
                </option>
                <option value="working" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'working' ) ?>>
	                <?php echo esc_html( 'working' ) ?>
                </option>
                <option value="workplace" <?php selected( $dwrafh_post_meta['banner_image_select'][0], 'workplace' ) ?>>
	                <?php echo esc_html( 'workplace' ) ?>
                </option>
			</select>
			<hr>

			<label for="post_expiration"><?php echo esc_html( 'Post Expiration' ) ?></label>
			<p class="description"><?php echo esc_html( 'All dates and times are in the \'America / New York\' timezone. Keep this in mind when you set a date. The datepicker may display the current local time to you but when the category of \'headlines\' actually gets expired, it will be in the blogs, time zone. In this case, the timezone is \'America / New York\'.' ) ?></p>
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