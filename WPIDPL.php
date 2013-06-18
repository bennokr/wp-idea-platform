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
      'comment' => "wpidpl_comment",
      'contact' => "wpidpl_contact"
    );
    $this->idea_table     = $wpdb->prefix . "idea_platform_ideas";
    $this->comments_table = $wpdb->prefix . "idea_platform_comments";
    $this->submit_url = admin_url( 'admin-ajax.php' );

    // Set regional settings for date
    setlocale(LC_TIME, "NL_nl"); 

    // Hook up public functions
    add_action( 'activate_' . WPIDPL_PLUGIN_BASENAME, array($this, 'install') );
    add_action( 'admin_menu', array($this, 'admin_menu'), 9 );
    add_action( 'plugins_loaded', array($this, 'add_shortcodes'), 1 );
    add_action( 'admin_init', array($this, 'register_settings'));
    add_action( 'wp_enqueue_scripts', array($this, 'add_stylesheet' ));

    foreach ($this->ajax_names as $k => $v) {
      add_action( 'wp_ajax_' .        $v, array($this, $k) );
      add_action( 'wp_ajax_nopriv_' . $v, array($this, $k) );
    }

    $this->groups = false;
    if ("" != get_option('idpl_groups')) {
      // The groups are a comma-seperated list
      $this->groups = array_map('trim',explode(",", get_option('idpl_groups')));
    }

  }

  /* Install and default settings */
  public function install() {
    $this->db->query(
      "CREATE TABLE `$this->idea_table` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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

    $this->db->query(
      "CREATE TABLE `$this->comments_table` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `author_name` varchar(128) DEFAULT NULL,
        `author_group` int(11) DEFAULT NULL,
        `author_mail` varchar(128) DEFAULT NULL,
        `description` text,
        `idea_id` int(11) DEFAULT NULL,
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

  // Register settings
  public function register_settings() {
    $vs = array('idpl_votes-name','idpl_votes-namepl','idpl_groups');
    foreach ($vs as $v) {
      register_setting('idpl_settings', $v);
    }
  }
   /**
   * Enqueue plugin style-file
   */
  public function add_stylesheet() {
      // Respects SSL, Style.css is relative to the current file
      wp_register_style( 'idpl-style', plugins_url('style.css', __FILE__) );
      wp_enqueue_style( 'idpl-style' );
  }

  // The idea platform in a page
  public function idea_platform_tag_func() {
    if (isset($_GET['idea_id']) && is_numeric($_GET['idea_id'])) {
      // Show single idea page
      $id = $_GET['idea_id']; // unsanitized
      $idea = $this->db->get_row(
        $this->db->prepare("SELECT * FROM $this->idea_table WHERE id = %d", array($id)));
      $comments = $this->db->get_results(
        $this->db->prepare("SELECT * FROM $this->comments_table WHERE idea_id = %d", array($id)));


      include WPIDPL_PLUGIN_DIR . "/page.php";
    } else {
      // Show idea listing
      // TODO: pagination
      $where = (isset($_GET['filter']) && is_numeric($_GET['filter']))? " WHERE status = " . $_GET['filter'] : "";
      $sort = " ORDER BY " . ((isset($_GET['sort']) && $_GET['sort'] == "votes")? "votes DESC" : "date DESC");
      $ideas = $this->db->get_results("SELECT * FROM $this->idea_table".$where.$sort);
      include WPIDPL_PLUGIN_DIR . "/list.php";
    }
  }

  // Showing the admin page
  public function admin_management_page() {
    // TODO: check admin credentials, etc!
    if (isset($_POST['idea_id']) && is_array($_POST['idea_id'])) {
      // Changing status
      if (isset($_POST['status']) && is_array($_POST['status'])) {
        foreach($_POST['status'] as $id=>$stat) {
          $this->db->query(
            $this->db->prepare("UPDATE `$this->idea_table` SET status = %d WHERE id = %d", array($stat, $id)));
        }
      }
      // Deleting ideas
      if (isset($_POST['delete']) && is_array($_POST['delete'])) {
        foreach($_POST['delete'] as $id=>$del) {
          if ($del) {
            // echo "delete $id<br />";
            $this->db->query(
              $this->db->prepare("DELETE FROM `$this->idea_table` WHERE id = %d", $id));
          }
        }
      }
    }

    $ideas = $this->db->get_results("SELECT * FROM $this->idea_table");
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
      $id = $_POST['id']; // unsanitized, do not use bare!
      $this->db->query(
        $this->db->prepare("UPDATE `$this->idea_table` SET votes = votes + 1 WHERE id = %d", array($id)));
      $idea = $this->db->get_row(
        $this->db->prepare("SELECT votes FROM $this->idea_table WHERE id =  %d", array($id)));
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
      echo json_encode( false );
    }
    exit;
  }
  // Asynchronous contact mail form
  public function contact() {
    // TODO: SPAM CHECKING
    header( "Content-Type: application/json" );
    $keys = array_flip(array('idea_id', 'mail', 'telephone', 'message'));
    $mail = array_intersect_key($_POST, $keys);
    if (!array_diff_key($mail,$keys)) { // check if all keys are in the POST
      // Send mail
      $id = $mail['idea_id']; // unsanitized, do not use bare!
      $idea = $this->db->get_row($this->db->prepare("SELECT author_name, author_mail, title FROM $this->idea_table WHERE id =  %d", array($id)));

      $tel = sanitize_text_field($mail['telephone']);
      $msg = nl2br(htmlspecialchars($mail['message']));
      $address = sanitize_email($mail['mail']);
      $title = $idea->title;
      //TODO: i18n
      $rep = "Reactie op";
      $content = <<<EOT
<b>$rep $title</b><br />
<p>$msg</p>
<b>Telefoon:</b> $tel<br />
<b>E-mail:</b> $address<br />
EOT;

      echo json_encode( $this->send_mail($address, $idea->author_name.' <'.$idea->author_mail.'>', $rep . ' ' . $title, $content));

    } else {
      echo json_encode( false );
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

  private function getStatusString($num) {
    switch ($num) {
      case 0: { return 'Openstaand'; break; };
      case 1: { return 'Gesloten'; break;};
      case 2: { return 'Gerealiseerd'; break;};
    };
    return "";
  }

  // Wordpress mail sending
  private function send_mail($to, $reply, $title, $msg) {
    add_filter( 'wp_mail_content_type', 'set_html_content_type' );
    $sent = wp_mail(
      $to, 
      $title,
      $msg,
      array('Reply-To: '. $reply));
    $sent = $sent && wp_mail(
      get_option('admin_email'),
      $title,
      $msg,
      array('Reply-To: '. sanitize_email($mail['mail'])));
    remove_filter( 'wp_mail_content_type', 'set_html_content_type' ); // reset content-type to to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
    return $sent;
  }
}

// Wordpress HTML mail incompetence
function set_html_content_type()
{
  return 'text/html';
}
?>