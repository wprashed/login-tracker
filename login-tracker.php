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

