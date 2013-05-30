<?php
// require_once WPIDPL_PLUGIN_DIR . '/includes/classes.php';

if ( is_admin() )
	require_once WPIDPL_PLUGIN_DIR . '/admin/admin.php';
else
	require_once WPIDPL_PLUGIN_DIR . '/includes/controller.php';

?>