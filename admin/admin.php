<?php

// Add the admin page to the menu
add_action( 'admin_menu', 'wpidpl_admin_menu', 9 );
function wpidpl_admin_menu() {
	add_object_page( 
		__( 'Idea Platform','wpidpl' ), 
		__( 'Ideas', 'wpidpl' ),
		'manage_options', 
		'wpidpl', 
		'wpidpl_admin_management_page',
		wpidpl_plugin_url( 'admin/images/menu-icon.png' ) 
	);
}

// Building resource URLs
function wpidpl_plugin_url( $path = '' ) {
	$url = untrailingslashit( WPCF7_PLUGIN_URL );
	if ( ! empty( $path ) && is_string( $path ) && false === strpos( $path, '..' ) )
		$url .= '/' . ltrim( $path, '/' );
	return $url;
}

// Showing the admin page
function wpidpl_admin_management_page() {
	echo "This is the admin page!";
}

?>