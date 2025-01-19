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

// Applications Page (Modified to load either listing or single view)
function job_manager_applications() {
    // Check if the 'view' query parameter is set to 'application-details'
    if ( isset( $_GET['view'] ) && $_GET['view'] == 'application-details' && isset( $_GET['id'] ) ) {
        // Show the single application view template
        job_manager_load_template('single-application-details');
    } else {
        // Default: Show the applications list template
        job_manager_load_template('admin-applications-list'); 
    }
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

    return ob_get_clean(); 
}
// REST API 
function register_job_listing_api() {
    register_rest_route('job-portal/v1', '/jobs', [
        'methods' => 'GET',
        'callback' => 'get_job_listings',
        'permission_callback' => '__return_true',
    ]);

    // Register the Job Application API endpoint
    register_rest_route('job-portal/v1', '/apply', [
        'methods' => 'POST',
        'callback' => 'handle_job_application',
        'permission_callback' => '__return_true',
    ]);

    // Temporarily flush rewrite rules for debugging
    flush_rewrite_rules();
}
add_action('rest_api_init', 'register_job_listing_api');

// Callback function for getting job listings
function get_job_listings(WP_REST_Request $request) {
    global $wpdb;

    // Define your jobs table using $wpdb prefix
    $jobs_table = $wpdb->prefix . 'jobs';

    // Query for approved and open positions
    $jobs_query = "SELECT * FROM $jobs_table WHERE is_approved = 1 AND position_taken = 0";

    // Fetch the results
    $jobs = $wpdb->get_results($jobs_query);

    // Check if jobs are found
    if (empty($jobs)) {
        return new WP_REST_Response('No jobs found.', 404);
    }

    // Return the job listings as JSON response
    return new WP_REST_Response($jobs, 200);
}

// Callback function for handling job application
function handle_job_application(WP_REST_Request $request) {
    $applicant_name = sanitize_text_field($request->get_param('applicant_name'));
    $applicant_email = sanitize_email($request->get_param('applicant_email'));
    $applicant_message = sanitize_textarea_field($request->get_param('applicant_message'));
    $job_id = sanitize_text_field($request->get_param('job_id'));
    $attached_file = $request->get_file_params();

    if (empty($applicant_name) || empty($applicant_email) || empty($job_id)) {
        return new WP_REST_Response('Missing required fields', 400);
    }

    if (!empty($attached_file)) {
        $upload = wp_upload_bits($attached_file['attached_file']['name'], null, file_get_contents($attached_file['attached_file']['tmp_name']));
        if (!$upload['error']) {
            $file_url = $upload['url'];
        } else {
            return new WP_REST_Response('File upload failed', 500);
        }
    }

    global $wpdb;
    $wpdb->insert(
        $wpdb->prefix . 'applications',
        [
            'job_id' => $job_id,
            'applicant_name' => $applicant_name,
            'applicant_email' => $applicant_email,
            'applicant_message' => $applicant_message,
            'attached_file' => $file_url ?? '',
            'status' => 'Pending',
            'submitted_at' => current_time('mysql'),
        ]
    );
    return new WP_REST_Response('Application submitted successfully', 200);
}