<?php
class WPIDPL {

  public function __construct() {
    add_action( 'activate_' . WPIDPL_PLUGIN_BASENAME, array($this, 'install') );
    add_action( 'admin_menu', array($this, 'admin_menu'), 9 );
    add_action( 'plugins_loaded', array($this, 'add_shortcodes'), 1 );
  }

  /* Install and default settings */
  public function install() {
    global $wpdb;
    $table_name = $wpdb->prefix . "idpl";
    $wpdb->query(
      "CREATE TABLE $table_name (
        `idea_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        PRIMARY KEY (`idea_id`)
      )");
  }

  public function admin_menu() {
    add_object_page( 
      __( 'Idea Platform','wpidpl' ), 
      __( 'Ideas', 'wpidpl' ),
      'manage_options', 
      'wpidpl', 
      array($this,'admin_management_page'),
      $this->plugin_url( 'admin/images/menu-icon.png' ) 
    );
  }
  
  public function add_shortcodes() {
    add_shortcode( 'idea-platform', array($this,'idea_platform_tag_func' ));
  }

  // The idea platform in a page
  public function idea_platform_tag_func() {
    echo "This is the idea platform!";
  }

  // Showing the admin page
  public function admin_management_page() {
    echo "This is the admin page!";
  }
  
  private function getString() {
    return "Dit is een string";
  }

  // Building resource URLs
  private function plugin_url( $path = '' ) {
    $url = untrailingslashit( WPCF7_PLUGIN_URL );
    if ( ! empty( $path ) && is_string( $path ) && false === strpos( $path, '..' ) )
      $url .= '/' . ltrim( $path, '/' );
    return $url;
  }
}
?>