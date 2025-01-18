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
 
 add_action( 'admin_menu', 'job_manager_admin_menu' );
 
// Add custom menu and submenus to the WordPress admin dashboard
 
 function job_manager_admin_menu() {
     add_menu_page('Job Manager', 'Job Manager', 'manage_options', 'job-manager', 'job_manager_dashboard', 'dashicons-businessman', 6);
     add_submenu_page('job-manager', 'Manage Jobs', 'Job Manager', 'manage_options', 'job-manager', 'job_manager_dashboard');
     add_submenu_page('job-manager', 'Add New Job', 'Add New Job', 'manage_options', 'add-new-job', 'job_manager_add_new_job');
     add_submenu_page('job-manager', 'Applications', 'Applications', 'manage_options', 'applications', 'job_manager_applications');
 }
 
 // Dashboard Page
 function job_manager_dashboard() {
     echo '<div class="wrap"><h1>Job Manager Dashboard</h1>';
     echo '<p>Welcome to the Job Manager plugin. Use the options in the menu to manage jobs and applications.</p></div>';
 }
 
 // Add New Job Page
 function job_manager_add_new_job() {
     echo '<div class="wrap"><h1>Add New Job</h1>';
     echo '<p>Here, you can add jobs.</p></div>';
 }
 
 // Applications Page
 function job_manager_applications() {
     echo '<div class="wrap"><h1>Applications</h1>';
     echo '<p>Here, you can view all job applications.</p></div>';
 }
 