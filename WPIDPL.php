<?php
class WPIDPL {
  /*
   * The Idea Platform
   *
   * Will allow sharing of ideas, replies, contacting authors, rating ideas
   * 
   */

  private $add_idea_func;
  private $idea_table;
  private $db;

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
    $this->add_idea_func = "wpidpl_add_idea";
    $this->idea_table = $wpdb->prefix . "idea_platform_ideas";

    add_action( 'activate_' . WPIDPL_PLUGIN_BASENAME, array($this, 'install') );
    add_action( 'admin_menu', array($this, 'admin_menu'), 9 );
    add_action( 'plugins_loaded', array($this, 'add_shortcodes'), 1 );
    add_action( 'wp_ajax_' . $add_idea_func, array($this, 'add_idea') );
    add_action( 'wp_ajax_nopriv_' . $this->add_idea_func, array($this, 'add_idea') );
  }

  /* Install and default settings */
  public function install() {
    $this->db->query(
      "CREATE TABLE `$this->idea_table` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        `votes` int(11) DEFAULT 0,
        `status` int(11) DEFAULT 0,
        `author_name` varchar(128) DEFAULT NULL,
        `author_group` int(11) DEFAULT NULL,
        `author_mail` varchar(128) DEFAULT NULL,
        `title` varchar(256) DEFAULT NULL,
        `description` text,
        `data_source` varchar(128) DEFAULT NULL,
        `data_location` varchar(128) DEFAULT NULL,
        `data_open` tinyint(1) NOT NULL DEFAULT '1',
        `files` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
      )");
  }

  // Add the options to the wordpress admin menu
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
  
  // Add wordpress shortcodes
  public function add_shortcodes() {
    add_shortcode( 'idea-platform', array($this,'idea_platform_tag_func' ));
  }

  // The idea platform in a page
  public function idea_platform_tag_func() {
    if (isset($_GET['idea_id']) && is_numeric($_GET['idea_id'])) {
      // Show single idea page
      $id = $_GET['idea_id'];
      $ideas = $this->db->get_row("SELECT * FROM $this->idea_table WHERE id = $id");
    } else {
      // Show idea listing
      // TODO: GET offset / sorting
      $ideas = $this->db->get_results("SELECT * FROM $this->idea_table");
      $submit_url = admin_url( 'admin-ajax.php' );
      include WPIDPL_PLUGIN_DIR . "/list.php";
    }
  }

  // Showing the admin page
  public function admin_management_page() {
    include WPIDPL_PLUGIN_DIR . "/admin/admin.php";
  }

  // Asynchronous request
  public function add_idea() {
    // $response = json_encode( $_POST );
    // TODO: use php array functions (diff, keys etc) to prevent all this repetition
    if (isset($_POST['author_name']) && isset($_POST['author_name']) && isset($_POST['author_mail']) && isset($_POST['data_location']) && isset($_POST['data_source']) && isset($_POST['description']) && isset($_POST['title'])) {
    // `insert` sanitizes the input (afask)
    $q = $this->db->insert( 
      $this->idea_table, 
      array( 
        'author_name' => $_POST['author_name'],
        'author_mail' => $_POST['author_mail'],
        'data_location' => $_POST['data_location'],
        'data_source' => $_POST['data_source'],
        'description' => $_POST['description'],
        'title' => $_POST['title']
      )
    );

    //header( "Content-Type: application/json" );
    var_dump($q);
    exit;
    }
  }

  // Building resource URLs
  private function plugin_url( $path = '' ) {
    $url = untrailingslashit( WPIDPL_PLUGIN_URL );
    if ( ! empty( $path ) && is_string( $path ) && false === strpos( $path, '..' ) )
      $url .= '/' . ltrim( $path, '/' );
    return $url;
  }
}
?>