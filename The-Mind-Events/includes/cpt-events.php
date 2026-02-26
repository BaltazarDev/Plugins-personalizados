<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function tm_register_events_cpt() {
	$labels = [
		'name'               => _x( 'Events', 'post type general name', 'the-mind-events' ),
		'singular_name'      => _x( 'Event', 'post type singular name', 'the-mind-events' ),
		'menu_name'          => _x( 'The Mind Events', 'admin menu', 'the-mind-events' ),
		'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'the-mind-events' ),
		'add_new'            => _x( 'Add New', 'event', 'the-mind-events' ),
		'add_new_item'       => __( 'Add New Event', 'the-mind-events' ),
		'new_item'           => __( 'New Event', 'the-mind-events' ),
		'edit_item'          => __( 'Edit Event', 'the-mind-events' ),
		'view_item'          => __( 'View Event', 'the-mind-events' ),
		'all_items'          => __( 'All Events', 'the-mind-events' ),
		'search_items'       => __( 'Search Events', 'the-mind-events' ),
		'not_found'          => __( 'No events found.', 'the-mind-events' ),
		'not_found_in_trash' => __( 'No events found in Trash.', 'the-mind-events' ),
	];

	$args = [
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'tm-event' ],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-calendar-alt',
		'supports'           => [ 'title', 'editor', 'thumbnail' ], // Title, Text (Editor), Photography (Thumbnail)
	];

	register_post_type( 'tm_event', $args );
}
add_action( 'init', 'tm_register_events_cpt' );

/**
 * Add Meta Box for Event Date
 */
function tm_add_events_metaboxes() {
    add_meta_box(
        'tm_event_details',
        __( 'Event Details', 'the-mind-events' ),
        'tm_render_event_date_metabox',
        'tm_event',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'tm_add_events_metaboxes' );

function tm_render_event_date_metabox( $post ) {
    wp_nonce_field( 'tm_events_metabox_nonce', 'tm_events_nonce' );
    $value = get_post_meta( $post->ID, '_tm_event_date', true );
    ?>
    <label for="tm_event_date"><?php _e( 'Event Date:', 'the-mind-events' ); ?></label>
    <input type="date" id="tm_event_date" name="tm_event_date" value="<?php echo esc_attr( $value ); ?>" style="width:100%; margin-top:5px;">
    <?php
}

function tm_save_event_date( $post_id ) {
    if ( ! isset( $_POST['tm_events_nonce'] ) || ! wp_verify_nonce( $_POST['tm_events_nonce'], 'tm_events_metabox_nonce' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['tm_event_date'] ) ) {
        update_post_meta( $post_id, '_tm_event_date', sanitize_text_field( $_POST['tm_event_date'] ) );
    }
}
add_action( 'save_post_tm_event', 'tm_save_event_date' );
