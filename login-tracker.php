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
