<?php
class WPIDPL {
  /*
   * The Idea Platform
   *
   * Will allow sharing of ideas, replies, contacting authors, rating ideas
   * 
   */

  private $ajax_names;
  private $idea_table;
  private $db;
  private $submit_url;

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
    $this->ajax_names = array(
      'add_idea' => "wpidpl_add_idea",
      'vote' => "wpidpl_vote",
      'comment' => "wpidpl_comment"
    );
    $this->idea_table = $wpdb->prefix . "idea_platform_ideas";
    $this->comments_table = $wpdb->prefix . "idea_platform_comments";
    $this->submit_url = admin_url( 'admin-ajax.php' );

    add_action( 'activate_' . WPIDPL_PLUGIN_BASENAME, array($this, 'install') );
    add_action( 'admin_menu', array($this, 'admin_menu'), 9 );
    add_action( 'plugins_loaded', array($this, 'add_shortcodes'), 1 );
    foreach ($this->ajax_names as $k => $v) {
      add_action( 'wp_ajax_' .        $v, array($this, $k) );
      add_action( 'wp_ajax_nopriv_' . $v, array($this, $k) );
    }
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
      $idea = $this->db->get_row("SELECT * FROM $this->idea_table WHERE id = $id");
      $comments = $this->db->get_results("SELECT * FROM $this->comments_table WHERE idea_id = $id");
      include WPIDPL_PLUGIN_DIR . "/page.php";
    } else {
      // Show idea listing
      // TODO: GET offset / sorting
      $ideas = $this->db->get_results("SELECT * FROM $this->idea_table");
      include WPIDPL_PLUGIN_DIR . "/list.php";
    }
  }

  // Showing the admin page
  public function admin_management_page() {
    include WPIDPL_PLUGIN_DIR . "/admin/admin.php";
  }

  // Asynchronous adding
  public function add_idea() {
    header( "Content-Type: application/json" );
    $keys = array_flip(array('author_name', 'author_group', 'author_mail', 'data_location', 'data_source', 'description', 'title'));
    $insert = array_intersect_key($_POST, $keys);
    if (!array_diff_key($insert,$keys)) { // check if all keys are in the POST
      // `insert` sanitizes the input (afask)
      $q = $this->db->insert($this->idea_table, $insert);
      echo json_encode( array('idea_id' => $this->db->insert_id) );
    } else {
      echo json_encode( false );
    }
    exit;
  }
  // Asynchronous voting
  public function vote() {
    // TODO: LIMIT VOTES (ip?)
    header( "Content-Type: application/json" );
    if (isset($_POST['id']) &&  is_numeric($_POST['id'])) {
      $id = $_POST['id'];
      $this->db->query("UPDATE `$this->idea_table` SET votes = votes + 1 WHERE id = $id ");
      $idea = $this->db->get_row("SELECT votes FROM $this->idea_table WHERE id = $id");
      echo json_encode( $idea );
    }
    exit;
  }
  // Asynchronous commenting
  public function comment() {
    // TODO: SPAM CHECKING
    header( "Content-Type: application/json" );
    $keys = array_flip(array('author_name', 'author_group', 'author_mail', 'description', 'idea_id'));
    $insert = array_intersect_key($_POST, $keys);
    if (!array_diff_key($insert,$keys)) { // check if all keys are in the POST
      // `insert` sanitizes the input (afask)
      $q = $this->db->insert($this->comments_table, $insert);
      echo json_encode( $insert );
    } else {
      echo json_encode( array($insert, $keys) );
    }
    exit;
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