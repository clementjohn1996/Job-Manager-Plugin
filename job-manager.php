<?php
/**
 * Plugin Name: Job Manager
 * Description: A simple plugin to create Jobs and Applications tables.
 * Version: 1.0
 * * Author: Clement John
 *  */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-job-manager-db.php';

register_activation_hook( __FILE__, array( 'Job_Manager_DB', 'create_tables' ) );

register_deactivation_hook( __FILE__, array( 'Job_Manager_DB', 'delete_tables' ) );