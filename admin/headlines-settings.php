<?php
/**
 * Created by PhpStorm.
 * User: joshsmith01
 * Date: 11/16/16
 * Time: 11:35
 */

function dwrafh_remove_category() {
	// Finds all posts with the category headline. It shouldn't be too many as there are typically only 3 ever which are live and up to 15 which can be set.  -JMS

	$args = array(
		'post_type'              => 'post',
		'posts_per_page'         => -1,
		'post_status'            => array( 'publish', 'future' ),
		'category_name'          => 'headline'
	);

	// Gets the query response. -JMS
	$posts = get_posts( $args );

	// Finds each post and its category expiration date if it has one and expires the categories that need to be expired. -JMS
	foreach ( $posts as $post ) {
	    // Get the post meta for each post only once, then search through that array. -JMS
	    $post_meta = get_post_meta($post->ID);
	    // Get the value from the field in the post's page, return a string value. -JMS
        $expiry_value_string = $post_meta['post_expiration'][0];
	    // Get a value from the blog's current time according to time zone from Settings, return a string. -JMS
		$wp_blog_current_timex = current_time( 'Y-m-d H:i:s', 0 );

		// Remove the headlines category if it not empty and if it is expired. (!empty and isset return two different things and are not synonymous.  -JMS
		if ( !empty( $expiry_value_string ) ) {

			// Get the time from the jQuery dropdown and convert it to Unix time format. -JMS
            $expiry_datetime = new DateTime( $expiry_value_string, new DateTimeZone( get_option( 'timezone_string' ) ) );
            $expiry_timestamp = (int)$expiry_datetime->format('U');

            // Get the local time according to the Blog Time Zone in Settings. -JMS
			$current_blog_datetime  = new DateTime( $wp_blog_current_timex, new DateTimeZone( get_option( 'timezone_string' ) ) );
			$current_blog_timestamp = (int)$current_blog_datetime->format( 'U' );

			// If the post has an a category of 'headline' and the expiration date has passed, then expire the category 'headline'. -JMS
			if ( ( $expiry_timestamp <= $current_blog_timestamp ) && ( has_category( 'headline', $post->ID ) ) ) {
                // Actually remove the category. -JMS
				wp_remove_object_terms($post->ID, 'headline', 'category');

			}
		}
	}
    // Reset that beautiful post meta and get WP_Query ready for another batch.
	wp_reset_postdata();
}
// Fire this function each time someone hits a URL since it's not be cron. -JMS
add_action( 'init', 'dwrafh_remove_category' );


/**
 * Adds a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function headlines_add_dashboard_widgets() {

	wp_add_dashboard_widget(
		'headlines_dashboard_widget',       // Widget slug.
		'Dice.com Headlines Order',         // Title.
		'dwrafh_display_dashboard_widget' // Display function.
	);
}
add_action( 'wp_dashboard_setup', 'headlines_add_dashboard_widgets' );


function dwrafh_display_dashboard_widget() {

	// Set up some query parameters. -JMS
	$args = array(
		'post_type'              => 'post',
		'orderby'                => 'menu_order',
		'order'                  => 'ASC',
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
		'posts_per_page'         => 15,
		'post_status'            => array( 'publish', 'future' ),
		'category_name'          => 'headline'
	);

	// Get the query response. -JMS
	$headlines = new WP_Query( $args );
	?>
	<div id="headline-sort" class="wrap">

		<div id="icon-headline-admin" class="icon32"><br/></div>
		<h2 id="headline-page-title"><?php _e( 'Sort Headlines for Dice.com Homepage', 'dice-wp-rest-api-for-headlines' ); ?>
			<img src="<?php echo esc_url( admin_url() . '/images/loading.gif' ) ?>" alt="loading indicator"
			     id="loading-animation">
		</h2>
		<p><?php _e('Sort the posts listed below into their publication order for Dice.com Homepage.', 'dice-wp-rest-api-for-headlines') ?></p>
		<?php
		$i_horizontal = 0;
        $days_ahead = 0;
		if ( $headlines->have_posts() ) { ?>
			<p><?php _e( '<strong>NOTE: </strong>Only effects Dice WP REST API for Headlines', 'dice-wp-rest-api-for-headlines' ) ?></p>

        <?php // Add a notification of the Local Blog Time ?>
        <?php $timezone_format = _x( 'Y-m-d H:i', 'timezone date format' );
                if( get_option('headlines_per_day') ) {
                	$headlines_per_day = get_option( 'headlines_per_day' );
                } else {
                	$headlines_per_day = 5;
                };
        ?>
        <p class="timezone-info">
            <?php if ( get_option( 'timezone_string' ) || ! empty( $current_offset ) ) : ?>
                <span id="local-time"><?php
                    printf( __( 'Local blog time is %s' ),
                    '<code>' . date_i18n( $timezone_format ) . '</code>' );
                ?></span>
	            <a href="<?php echo site_url() . '/wp-json/dice-headlines/v1/headlines' ?>" target="_blank">View JSON Response</a>
            <?php endif; ?>
        </p>
            <?php if ( $headlines->have_posts() ) { ?>
				<span><?php _e( 'Headlines per day?' ) ?></span>
				<input id="headlines-per-day" type="number" name="headlines-per-day" min="1" max="10" value="<?php echo $headlines_per_day; ?>">

            <ul id="custom-type-list">
            <?php while ( $headlines->have_posts() ) {
                if ( $i_horizontal % $headlines_per_day == 0 ) { ?>
                    <?php echo $release_date = date( 'l', strtotime( sprintf( "+%d day", $days_ahead  ) ) );
                    $days_ahead++;
                    ?>
                    <hr />

					<?php }
					$i_horizontal ++;
					$headlines->the_post();
	                $checked = has_category( 'top-headline' );
	                $locked = get_post_meta( $headlines->post->ID, 'locked', true );
	                $headline_id = $headlines->post->ID;
					?>
                    <li id="<?php echo $headline_id; ?>" class="headline-item <?php if ( $locked ) { echo 'static'; } else { echo 'ui-sortable-handle'; }  ?> ">
	                    <div class="main-headline-info">
		                    <a href="<?php echo get_edit_post_link(); ?>"><?php the_title(); ?></a><?php ?>
		                    <div class="button-holder">
	                            <button class="open-extra-info"><?php _e('Extra Info')?></button>
		                    </div>
	                    </div>
	                    <div class="extra-headline-info">
	                        <div class="inner-extra-headline-info">
		                        <div class="input-holder">
			                        <label for="top-headline-<?php echo $headline_id; ?>"><?php _e('Top Headline', 'text-domain') ?></label>
			                        <input id="top-headline-<?php echo $headline_id; ?>" class="top-headline-choice"
		                               type="checkbox" <?php checked( $checked ); ?>>
		                        </div>

		                        <div class="input-holder">
			                        <label for="lock-order-<?php echo $headline_id; ?>"><?php _e('Lock Order', 'text-domain') ?></label>
			                        <input id="lock-order-<?php echo $headline_id; ?>" class="lock-order" type="checkbox" <?php checked( $locked ); ?> name="lock-order-<?php echo $headline_id; ?>" >
		                        </div>


			                        <button aria-label="Remove This Headline"class="remove-headline button warning-button" id="remove-headline-<?php echo $headline_id; ?>">
				                        <?php _e('Remove Headline', 'text-domain') ?>
			                        </button>
		                        <div class="input-holder">
									<label for="tracking-code-<?php echo $headline_id; ?>"><?php _e( 'Tracking Code', 'text-domain' ) ?></label>
									<input id="tracking-code-<?php echo $headline_id; ?>" class="headline-tracking-code" type="text" value="<?php echo get_post_meta( $headline_id, 'headline_tracking_code', true)  ?>">
								</div>

	                        </div>
                        </div>
                    </li>
					<?php
				} ?>
                <button class="button-primary" id="update-headlines"><?php _e('Update Headlines', 'text-domain') ?></button>
            </ul>

            <?php }
				}

		else {
			?><p><?php _e( 'You have no headlines to sort', 'dice-wp-rest-api-for-headlines' ); ?></p><?php
		}
		?>
		<div>
			<div class="button-holder-extra-information">
				<button class="open-extra-info"><?php _e('How to use the widget') ?></button>
			</div>
			<div class="extra-headline-info">
				<h3><?php _e( 'Get started with Publishing to Dice.com Homepage', 'dice-wp-rest-api-for-headlines' ); ?></h3>
				<ol>
					<li><?php _e( 'Write a post and assign it the category of <em>headline</em>.', 'dice-wp-rest-api-for-headlines' ); ?></li>
					<li><?php _e( 'Repeat 3x', 'dice-wp-rest-api-for-headlines' ); ?></li>
				</ol>
				<p><?php _e( 'Congratulations! News is published. See you tomorrow.', 'dice-wp-rest-api-for-headlines' ); ?></p>
				<p><?php _e( 'Articles naturally publish in a descending order, latest post on top. If you want to change the order in
					which the post appear, simply drag the titles on the Dashboard widget to the order you desire.', 'dice-wp-rest-api-for-headlines' ); ?></p>
				<p><?php _e( '<strong>NOTE: </strong>It is recommended that you have 3 new posts ready before you expire the old posts.', 'dice-wp-rest-api-for-headlines' ); ?></p>
				<h3><?php _e( 'Automate, if desired.', 'dice-wp-rest-api-for-headlines' ); ?></h3>
				<ol>
					<li><?php _e( 'Follow the previous steps above until you have more than 3 posts with the category of <em>headline</em>.', 'dice-wp-rest-api-for-headlines' ); ?>
					</li>
					<li><?php _e( 'WordPress will publish posts by publication date unless the titles have been rearranged here.', 'dice-wp-rest-api-for-headlines' ); ?></li>
					<li><?php _e( 'Add expiration dates and times to the previous or current posts.', 'dice-wp-rest-api-for-headlines' ); ?></li>
				</ol>
				<p><?php _e( 'Older posts will expire and new posts will automatically move up and will be published on the Dice.com
					homepage.', 'dice-wp-rest-api-for-headlines' ); ?></p>
			</div>
		</div>

	</div>

	<?php
} // END dwrafh_add_menu_page()

function dwrafh_display_admin_page() { ?>
	<div id="headline-sort" class="wrap">
		<div id="icon-headline-admin" class="icon32"><br/></div>
		<h2 id="headline-page-title"><?php _e( 'How to Post Articles to Dice.com Homepage', 'dice-wp-rest-api-for-headlines')?></h2>
		<p></p>
	</div>
	<?php
} // END dwrafh_display_admin_page()

/**
 * @return null|void
 */
function dwrafh_save_reorder() {
	if ( ! check_ajax_referer( 'wp-headline-order', 'security' ) ) {
		return wp_send_json_error( 'Invalid Nonce' );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json_error( 'Insufficient User Permissions' );
	}

	$order   = $_POST['order'];
	$counter = 0;
	foreach ( $order as $item_id ) {
		$post = array(
			'ID'         => (int)$item_id,
			'menu_order' => $counter,
		);
		wp_update_post( $post );
		$counter ++;
	}

	return null;
}
add_action('wp_ajax_save_sort', 'dwrafh_save_reorder');


/**
 * Save the posts' menu order if it is 'locked'. -JMS
 * @return null|void
 */
function dwrafh_save_lock_order() {
	if ( ! check_ajax_referer( 'wp-headline-order', 'security' ) ) {
		return wp_send_json_error( 'Invalid Nonce' );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json_error( 'Insufficient User Permissions' );
	}

	$lock_order_arr   = $_POST['lockOrder'];
	$post_order_arr = $_POST['order'];

		foreach ( $post_order_arr as $post_id ) {
			if (in_array($post_id, $lock_order_arr)) {
				update_post_meta( $post_id , 'locked', true );
			} else {
				update_post_meta($post_id, 'locked', false);
			}
		}


	return null;
}
add_action('wp_ajax_save_sort', 'dwrafh_save_lock_order');

// Remove category, headline, from posts. -JMS
function dwrafh_remove_headline_cat() {
	if ( ! check_ajax_referer( 'wp-headline-order', 'security' ) ) {
		return wp_send_json_error( 'Invalid Nonce' );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json_error( 'Insufficient User Permissions' );
	}

	$headlinePostId = $_POST['parentId'];
	$terms = 'headline';
	$taxonomy = 'category';

	wp_remove_object_terms( $headlinePostId, $terms, $taxonomy );
	wp_send_json_success( 'Post with ID of '. $headlinePostId .' has been removed' );

	return null;
}
add_action('wp_ajax_remove_headline', 'dwrafh_remove_headline_cat' );

// Add a category of top-headline to the post -JMS
function dwrafh_update_top_headline_cat() {
	if ( ! check_ajax_referer( 'wp-headline-order', 'security' ) ) {
		return wp_send_json_error( 'Invalid Nonce' );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json_error( 'Insufficient User Permissions' );
	}

	$headlinePostId = $_POST['topHeadlineId'];
	$terms          = 'top-headline';
	$taxonomy       = 'category';


	$headlineArgs = [
		'numberposts' => - 1,
		'category_name'    => 'headline',
		'exclude'     => [ $headlinePostId ]
	];

	$headline_posts = get_posts( $headlineArgs );

	foreach ( $headline_posts as $headline_post ) {
		wp_remove_object_terms( $headline_post->ID, $terms, $taxonomy );
	}

	wp_set_object_terms( $headlinePostId, $terms, $taxonomy, true );

	return null;
}

add_action( 'wp_ajax_update_top_headline', 'dwrafh_update_top_headline_cat' );

// Remove the category of top-headline from the post -JMS
function dwrafh_remove_top_headline_cat() {
	if ( ! check_ajax_referer( 'wp-headline-order', 'security' ) ) {
		return wp_send_json_error( 'Invalid Nonce' );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json_error( 'Insufficient User Permissions' );
	}

	$terms          = 'top-headline';
	$taxonomy       = 'category';

	$headlineArgs = [
        'numberposts' => -1,
        'category_name'    => 'headline',
    ];
	$headline_posts = get_posts( $headlineArgs );

	foreach ( $headline_posts as $headline_post ) {
		wp_remove_object_terms( $headline_post->ID, $terms, $taxonomy );
    }

	return null;
}
add_action( 'wp_ajax_remove_top_headline', 'dwrafh_remove_top_headline_cat' );

/**
 * Update posts' post_meta with tracking codes if that post has a tracking code. -JMS
 * @return null
 *
 */
function updateTrackingCode() {
	$tracking_code_arr = $_POST['trackingCode'];

	foreach ($tracking_code_arr as $ID=>$code ) {
		update_post_meta($ID, 'headline_tracking_code', $code );
	}
	return null;
}


/**
 * Get the number of posts the user wants to issue per day from the Dashboard Widget. -JMS
 * @return null
 */
function updateHeadlinesPerDay() {

	update_option('headlines_per_day', absint( $_POST['headlinesPerDay'] ) );
	return null;
}




function dwrafh_update_headlines () {
	// Make sure the nonce matches. -JMS
    if ( ! check_ajax_referer( 'wp-headline-order', 'security' ) ) {
		return wp_send_json_error( 'Invalid Nonce' );
	}
    // Make sure that the logged in user has the permissions to even do these operations. -JMS
	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json_error( 'Insufficient User Permissions' );
	}

	// Check to see if the topHeadlineId var is even set. -JMS
    // Either remove the top headline or set one and only one post as the Top Headline. -JMS
	if (!$_POST['topHeadlineId']) {
	    dwrafh_remove_top_headline_cat();
    } else {
    	dwrafh_update_top_headline_cat();
	}

	// Save the post order of the headlines. -JMS
	dwrafh_save_reorder();

    // Save the lock order of headlines. -JMS
	dwrafh_save_lock_order();

	// Update the posts' specific tracking code if users entered one. -JMS
	updateTrackingCode();

	// Update the options table with the user-desired number of headlines to issue per day. -JMS
	updateHeadlinesPerDay();

	// Send a useful success response back to the server so the front end can display a useful message. -JMS
	wp_send_json_success( 'Headline Posts have been updated' );

	return null;
};
add_action('wp_ajax_update_headlines', 'dwrafh_update_headlines');