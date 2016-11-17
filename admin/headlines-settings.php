<?php
/**
 * Created by PhpStorm.
 * User: joshsmith01
 * Date: 11/16/16
 * Time: 11:35
 */

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
		'post_status'            => 'publish',
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
		<?php
		if ( $headlines->have_posts() ) {
			?>
			<p><?php _e( '<strong>NOTE:</strong>This only effects headlines for the Dice WP REST API', 'dice-wp-rest-api-for-headlines' ) ?></p>
			<ul id="custom-type-list">
				<?php while ( $headlines->have_posts() ) {
					$headlines->the_post(); ?>
					<li id="<?php esc_attr( the_ID() ); ?>"><?php the_title(); ?></li>
					<?php
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
