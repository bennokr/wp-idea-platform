<?php

// Add the shortcodes
add_action( 'plugins_loaded', 'wpidpl_add_shortcodes', 1 );
function wpidpl_add_shortcodes() {
	add_shortcode( 'idea-platform', 'wpidpl_idea_platform_tag_func' );
}

// The idea platform in a page
function wpidpl_idea_platform_tag_func() {
	echo "This is the idea platform!";
}

?>