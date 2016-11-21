<?php
/**
 * Created by PhpStorm.
 * User: joshsmith01
 * Date: 11/16/16
 * Time: 11:35
 */

// TODO: Don't use this exact code, but turn this into a dashboard widget. -JMS
// TODO: Add horizontal lines to separate the day's worth of headlines. -JMS
// TODO: Add future posts to the query array. -JMS
/**
 * Add a widget to the dashboard.
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

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function example_dashboard_widget_function() {

	// Display whatever it is you want to show.
	echo "Hello World, I'm a great Dashboard Widget";
}









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
