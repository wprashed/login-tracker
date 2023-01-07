<?php
/*
Plugin Name: Login Tracker
Description: Tracks user login activity and displays it on the dashboard.
Version: 1.0
Author: Your Name
Author URI: http://yourwebsite.com
*/

function track_login( $user_login, $user ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'login_log';

    $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user->ID,
            'user_login' => $user_login,
            'time' => current_time( 'mysql' )
        )
    );
}
add_action( 'wp_login', 'track_login', 10, 2 );

function login_tracker_dashboard_widget() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'login_log';

    $login_log = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY time DESC" );

    echo '<table>';
    echo '<tr><th>Username</th><th>Time</th></tr>';
    foreach ( $login_log as $log ) {
        $user = get_user_by( 'id', $log->user_id );
        echo '<tr><td>' . $user->user_login . '</td><td>' . $log->time . '</td></tr>';
    }
    echo '</table>';
}

function add_login_tracker_dashboard_widget() {
    wp_add_dashboard_widget( 'login_tracker_dashboard_widget', 'Login Tracker', 'login_tracker_dashboard_widget' );
}
add_action( 'wp_dashboard_setup', 'add_login_tracker_dashboard_widget' );

?>

<?php
/*
* Plugin Name: User Login Data
* Plugin URI: https://www.example.com
* Description: This plugin stores and displays user login data on the WordPress dashboard as a dashboard widget.
* Version: 1.0
* Author: John Doe
* Author URI: https://www.example.com
* License: GPL2
*/

// Register the dashboard widget
function user_login_data_dashboard_widget() {
  wp_add_dashboard_widget(
    'user_login_data_dashboard_widget', // Widget slug
    'User Login Data', // Title
    'display_user_login_data' // Display function
  );
}
add_action( 'wp_dashboard_setup', 'user_login_data_dashboard_widget' );

// Display the dashboard widget content
function display_user_login_data() {
  $user_login_data = get_option( 'user_login_data' );
  if ( ! $user_login_data ) {
    $user_login_data = array();
  }
  echo '<table>';
  echo '<tr><th>Username</th><th>Last Login</th></tr>';
  foreach ( $user_login_data as $username => $last_login ) {
    echo '<tr><td>' . $username . '</td><td>' . $last_login . '</td></tr>';
  }
  echo '</table>';
}

// Store user login data
function store_user_login_data( $user_login, $user ) {
  $user_login_data = get_option( 'user_login_data' );
  if ( ! $user_login_data ) {
    $user_login_data = array();
  }
  $user_login_data[$user_login] = date( 'Y-m-d H:i:s' );
  update_option( 'user_login_data', $user_login_data );
}
add_action( 'wp_login', 'store_user_login_data', 10, 2 );
?>


Regenerate response

<?php
/*
Plugin Name: User Login Data
Description: Stores and displays user login data on the WordPress dashboard
Author: [Your Name]
Version: 1.0
*/

// Create custom dashboard widget
function login_data_dashboard_widget() {
	wp_add_dashboard_widget(
		'login_data_dashboard_widget', // Widget slug
		'User Login Data', // Title
		'login_data_display' // Display function
	);
}
add_action( 'wp_dashboard_setup', 'login_data_dashboard_widget' );

// Display widget content
function login_data_display() {
	global $wpdb;

	// Get login data from database
	$login_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}user_login_data" );

	// Display data in table
	echo '<table>';
	echo '<tr>';
	echo '<th>Username</th>';
	echo '<th>Date</th>';
	echo '</tr>';
	foreach ( $login_data as $data ) {
		echo '<tr>';
		echo '<td>' . $data->username . '</td>';
		echo '<td>' . $data->login_date . '</td>';
		echo '</tr>';
	}
	echo '</table>';
}

// Hook into login action to store data
function store_login_data( $user_login, $user ) {
	global $wpdb;

	// Insert login data into database
	$wpdb->insert(
		$wpdb->prefix . 'user_login_data',
		array(
			'username' => $user_login,
			'login_date' => current_time( 'mysql' ),
		),
		array(
			'%s',
			'%s',
		)
	);
}
add_action( 'wp_login', 'store_login_data', 10, 2 );

// Create database table on plugin activation
function create_login_data_table() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'user_login_data';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		username varchar(60) NOT NULL,
		login_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
register_activation_hook( __FILE__, 'create_login_data_table' );

<?php
/*
Plugin Name: User Login Data
Plugin URI: https://www.yourwebsite.com
Description: This plugin stores users' login data with IP address and country in the database and shows it on the WordPress dashboard as a dashboard widget.
Author: Your Name
Author URI: https://www.yourwebsite.com
Version: 1.0
*/

// Create a custom table in the database to store login data
function create_login_data_table() {
   global $wpdb;
   $table_name = $wpdb->prefix . 'login_data';
   $charset_collate = $wpdb->get_charset_collate();

   $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      user_id bigint(20) NOT NULL,
      ip_address varchar(50) NOT NULL,
      country varchar(50) NOT NULL,
      login_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      PRIMARY KEY  (id)
   ) $charset_collate;";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
}
register_activation_hook( __FILE__, 'create_login_data_table' );

// Store login data in the custom table when a user logs in
function store_login_data( $user_login, $user ) {
   global $wpdb;
   $table_name = $wpdb->prefix . 'login_data';

   $ip_address = $_SERVER['REMOTE_ADDR'];
   // Use a geolocation API to get the user's country (e.g. https://ipapi.co/)
   $response = wp_remote_get( "https://ipapi.co/$ip_address/json/" );
   $country = json_decode( $response['body'] )->country_name;

   $wpdb->insert(
      $table_name,
      array(
         'user_id' => $user->ID,
         'ip_address' => $ip_address,
         'country' => $country,
         'login_time' => current_time( 'mysql' ),
      )
   );
}
add_action( 'wp_login', 'store_login_data', 10, 2 );

// Create the dashboard widget to display login data
function create_login_data_dashboard_widget() {
   wp_add_dashboard_widget(
      'login_data_dashboard_widget', // Widget slug.
      'Login Data', // Title.
      'display_login_data_dashboard_widget' // Display function.
   );
}
add_action( 'wp_dashboard_setup', 'create_login_data_dashboard_widget' );

function display_login_data_dashboard_widget() {
   global $wpdb;
   $table_name = $wpdb->prefix . 'login_data';
   $login_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY login_time DESC LIMIT 5" );

   echo '<table>
