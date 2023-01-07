<?php
/*
Plugin Name: Login Tracker
Description: Tracks user login activity and displays it on the dashboard.
Version: 1.0
Author: Your Name
Author URI: http://yourwebsite.com
*/

/* Code */

function create_login_data_table() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'login_data';

	$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		username varchar(60) NOT NULL,
		login_time datetime NOT NULL,
		ip_address varchar(45) NOT NULL,
		country varchar(2) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
register_activation_hook( __FILE__, 'create_login_data_table' );

// Create a custom dashboard widget
function login_data_dashboard_widget() {
	wp_add_dashboard_widget(
		'login_data_dashboard_widget', // Widget slug.
		'Login Data', // Title.
		'login_data_dashboard_widget_display' // Display function.
	);
}
add_action( 'wp_dashboard_setup', 'login_data_dashboard_widget' );

// Display the dashboard widget
function login_data_dashboard_widget_display() {
	global $wpdb;

	// Get the login data from the database
	$login_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}login_data ORDER BY login_time DESC" );

	// Display the login data in a table
	echo '<table class="widefat">';
	echo '<thead><tr><th>Username</th><th>Login Time</th><th>IP Address</th><th>Country</th></tr></thead>';
	echo '<tbody>';
	foreach ( $login_data as $data ) {
		echo '<tr><td>' . $data->username . '</td><td>' . $data->login_time . '</td><td>' . $data->ip_address . '</td><td>' . $data->country . '</td></tr>';
	}
	echo '</tbody>';
	echo '</table>';
}

// Store the login data in the database
function store_login_data( $username ) {
	global $wpdb;

	// Get the user's IP address
	$ip_address = $_SERVER['REMOTE_ADDR'];

	// Get the user's country
	$country = get_country_from_ip( $ip_address );

	// Store the login data in the database
	$wpdb->insert(
		$wpdb->prefix . 'login_data',
		array(
			'username'    => $username,
			'login_time'  => current_time( 'mysql' ),
			'ip_address'  => $ip_address,
			'country'     => $country,
		),
		array(
			'%s',
			'%s',
			'%s',
			'%s',
		)
	);
}
add_action( 'wp_login', 'store_login_data' );
