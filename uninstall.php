<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function wpidpl_delete_plugin() {
	global $wpdb;

	delete_option( 'wpidpl' );

	$posts = get_posts( array(
		'numberposts' => -1,
		'post_type' => 'wpidpl_idea_platform',
		'post_status' => 'any' ) );

	foreach ( $posts as $post )
		wp_delete_post( $post->ID, true );

	$table_name = $wpdb->prefix . "idea_platform";

	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}

wpidpl_delete_plugin();

?>