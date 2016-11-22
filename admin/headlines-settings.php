<?php
/**
 * Created by PhpStorm.
 * User: joshsmith01
 * Date: 11/16/16
 * Time: 11:35
 */

function dwrafh_remove_category() {

	// Finds all posts with the category headline. -JMS
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

		$expiry_value = get_field( 'expiry_datetime', $post->ID );
		if ( isset( $expiry_value ) ) {

			// Get the time from the jQuery dropdown and convert it to Unix time format. -JMS
			$expiry_datetime = strtotime( get_field( 'expiry_datetime', $post->ID ) );
			// If the post has an a category of 'headline' and the expiration date has passed, then expire the category 'headline'. -JMS
			if ( ($expiry_datetime <= strtotime( 'now' ) ) && ( has_category( 'headline', $post->ID ) ) ) {

				wp_remove_object_terms($post->ID, 'headline', 'category');

			}
		}
	}

	wp_reset_postdata();
}
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
//		'headlines_dashboard_widget_function' // Display function.
		'dwrafh_display_admin_page' // Display function.
	);
}
add_action( 'wp_dashboard_setup', 'headlines_add_dashboard_widgets' );

// Add the menu pages to the sidebar in the Admin area. -JMS
function dwrafh_add_menu_page() {

	add_menu_page( 'Headlines', 'Headlines', 'manage_options', 'edit-headlines', 'dwrafh_display_admin_page', 'dashicons-id-alt' );
}
add_action( 'admin_menu', 'dwrafh_add_menu_page' );

function dwrafh_display_admin_page() {

	// Set up some query parameters. -JMS
	$args = array(
		'post_type'              => 'post',
		'orderby'                => 'menu_order',
		'order'                  => 'ASC',
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
		'posts_per_page'         => 15,
		'post_status'            => array('publish', 'future'),
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
		<p>Sort the posts listed below however you'd like them to be posted on the Dice.com Homepage slider.</p>
		<?php
		$i_horizontal = 1;
		$release_date = 'Today';
		if ( $headlines->have_posts() ) { ?>
			<p><?php _e( '<strong>NOTE: </strong>This only effects headlines for the Dice WP REST API', 'dice-wp-rest-api-for-headlines' ) ?></p>
			<ul id="custom-type-list">
				<?php echo $release_date; ?>
				<?php while ( $headlines->have_posts() ) {

					$headlines->the_post(); ?>
					<li id="<?php esc_attr( the_ID() ); ?>"><?php the_title(); ?> Post ID: <?php esc_attr( the_ID() );?></li>
					<?php
					if ($i_horizontal % 3 ==  0 ) { ?>
						<hr>
						<?php echo $release_date = date( 'l', strtotime( "+1 day" )); ?>
					<?php }
					$i_horizontal++;
				}

				?>
			</ul>
		<?php } else {
			?><p><?php _e( 'You have no headlines to sort', 'dice-wp-rest-api-for-headlines' ); ?></p><?php
		}
		?>
	</div>
	<?php
} // END dwrafh_add_menu_page()

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
	wp_send_json_success('Post order saved');
}
add_action('wp_ajax_save_sort', 'dwrafh_save_reorder');
