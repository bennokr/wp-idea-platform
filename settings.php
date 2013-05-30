<?php

// require_once WPIDPL_PLUGIN_DIR . '/includes/functions.php';
// require_once WPIDPL_PLUGIN_DIR . '/includes/deprecated.php';
// require_once WPIDPL_PLUGIN_DIR . '/includes/formatting.php';
// require_once WPIDPL_PLUGIN_DIR . '/includes/pipe.php';
// require_once WPIDPL_PLUGIN_DIR . '/includes/shortcodes.php';
// require_once WPIDPL_PLUGIN_DIR . '/includes/capabilities.php';
// require_once WPIDPL_PLUGIN_DIR . '/includes/classes.php';

if ( is_admin() )
	require_once WPIDPL_PLUGIN_DIR . '/admin/admin.php';
else
	require_once WPIDPL_PLUGIN_DIR . '/includes/controller.php';

/* Loading modules */

add_action( 'plugins_loaded', 'wpidpl_load_modules', 1 );

function wpidpl_load_modules() {
	$dir = WPIDPL_PLUGIN_MODULES_DIR;

	if ( ! ( is_dir( $dir ) && $dh = opendir( $dir ) ) )
		return false;

	while ( ( $module = readdir( $dh ) ) !== false ) {
		if ( substr( $module, -4 ) == '.php' && substr( $module, 0, 1 ) != '.' )
			include_once $dir . '/' . $module;
	}
}

add_action( 'plugins_loaded', 'wpidpl_set_request_uri', 9 );

function wpidpl_set_request_uri() {
	global $wpidpl_request_uri;

	$wpidpl_request_uri = add_query_arg( array() );
}

function wpidpl_get_request_uri() {
	global $wpidpl_request_uri;

	return (string) $wpidpl_request_uri;
}

add_action( 'init', 'wpidpl_init' );

function wpidpl_init() {
	wpidpl();

	// L10N
	wpidpl_load_plugin_textdomain();

	// Custom Post Type
	wpidpl_register_post_types();

	do_action( 'wpidpl_init' );
}

function wpidpl() {
	global $wpidpl;

	if ( is_object( $wpidpl ) )
		return;

	$wpidpl = (object) array(
		'processing_within' => '',
		'widget_count' => 0,
		'unit_count' => 0,
		'global_unit_count' => 0,
		'result' => array() );
}

function wpidpl_load_plugin_textdomain() {
	load_plugin_textdomain( 'wpidpl', false, 'idea-platform/languages' );
}

function wpidpl_register_post_types() {
	WPIDPL_IdeaPlatform::register_post_type();
}

/* Upgrading */
// nothing here, sorry

/* Install and default settings */

add_action( 'activate_' . WPIDPL_PLUGIN_BASENAME, 'wpidpl_install' );

function wpidpl_install() {
	if ( $opt = get_option( 'wpidpl' ) )
		return;

	wpidpl_load_plugin_textdomain();
	wpidpl_register_post_types();

	if ( get_posts( array( 'post_type' => 'wpidpl_idea_platform' ) ) )
		return;

	$idea_platform = wpidpl_get_idea_platform_default_pack(
		array( 'title' => sprintf( __( 'Idea platform %d', 'wpidpl' ), 1 ) ) );

	$idea_platform->save();
}

?>