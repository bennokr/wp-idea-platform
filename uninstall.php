<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function wpidpl_delete_plugin() {
	global $wpdb;
	$table_name = $wpdb->prefix . "idea_platform";
	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}

wpidpl_delete_plugin();

?>