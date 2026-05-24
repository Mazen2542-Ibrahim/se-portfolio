<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove plugin options.
delete_option( 'sep_about' );
delete_option( 'sep_style' );
delete_option( 'sep_version' );

// Delete all posts for each CPT (true = force delete, bypasses trash).
$cpt_slugs = [ 'sep_project', 'sep_skill', 'sep_experience', 'sep_education', 'sep_certificate' ];
foreach ( $cpt_slugs as $post_type ) {
	$posts = get_posts( [
		'post_type'      => $post_type,
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	] );
	foreach ( $posts as $post_id ) {
		wp_delete_post( $post_id, true );
	}
}

// Remove all transients matching sep_*.
global $wpdb;
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_sep_%'" );      // phpcs:ignore WordPress.DB.DirectDatabaseQuery
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_sep_%'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
