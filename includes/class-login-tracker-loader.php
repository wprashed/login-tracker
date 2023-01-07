<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://wprashed.com
 * @since      1.0.0
 *
 * @package    Login_Tracker
 * @subpackage Login_Tracker/includes
 */

/**
 * Register all actions and filters for the plugin.
 * @package    Login_Tracker
 * @subpackage Login_Tracker/includes
 * @author     Md Rashed Hossain <rashedcse18@gmail.com>
 */
class Login_Tracker_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		// Create Database for the plugin

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
			echo '<thead><tr><th>Username</th><th>Login Time</th><th>IP Address</th></tr></thead>';
			echo '<tbody>';
			foreach ( $login_data as $data ) {
				echo '<tr><td>' . $data->username . '</td><td>' . $data->login_time . '</td><td>' . $data->ip_address . '</td></tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}

		// Store the login data in the database
		function store_login_data( $username ) {
			global $wpdb;

			// Get the user's IP address
			$ip_address = $_SERVER['REMOTE_ADDR'];

			// Store the login data in the database
			$wpdb->insert(
				$wpdb->prefix . 'login_data',
				array(
					'username'    => $username,
					'login_time'  => current_time( 'mysql' ),
					'ip_address'  => $ip_address,
				),
				array(
					'%s',
					'%s',
					'%s',
				)
			);
		}
		add_action( 'wp_login', 'store_login_data' );

	}

}
