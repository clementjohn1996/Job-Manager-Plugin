<?php
/**
 * Plugin Name: Job Manager
 * Description: A simple job manager plugin to handle job listings, applications, and related functionalities.
 * Version: 1.0
 * Author: Clement John
 */

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
    job_manager_load_template('admin-jobs-list'); 
}

// Add New Job Page 
function job_manager_add_new_job() {
    job_manager_load_template('admin-add-job'); 
}

// Applications Page
function job_manager_applications() {
    job_manager_load_template('admin-applications-list'); 
}

// Load the correct template
function job_manager_load_template($template_name) {
    $plugin_dir = plugin_dir_path(__FILE__) . 'templates/';
    $template_path = $plugin_dir . $template_name . '.php';

    if (file_exists($template_path)) {
        include $template_path;
    } else {
        echo 'Template not found.';
    }
}


// Register the shortcode [display_job_application]
function register_jobs_shortcode() {
    add_shortcode( 'display_job_application', 'display_jobs_on_frontend' );
}
add_action( 'init', 'register_jobs_shortcode' );

// Function to display jobs on the front end
function display_jobs_on_frontend() {
    // Get the template file from the plugin's templates folder
    ob_start(); // Start output buffering

    // Include the template file
    include plugin_dir_path(__FILE__) . 'templates/frontend-application-form.php';

    return ob_get_clean(); // Return the buffered content
}