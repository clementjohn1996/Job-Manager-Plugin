<?php
/**
 * Class Job_Manager_DB
 */

 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Job_Manager_DB {

    // Create tables on plugin activation
    public static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Create Jobs table
        $jobs_table = $wpdb->prefix . 'jobs';
        $jobs_sql = "CREATE TABLE $jobs_table (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            job_title VARCHAR(255) NOT NULL,
            job_description TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        // Create Applications table
        $applications_table = $wpdb->prefix . 'applications';
        $applications_sql = "CREATE TABLE $applications_table (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            job_id BIGINT(20) UNSIGNED NOT NULL,
            applicant_name VARCHAR(255) NOT NULL,
            applicant_email VARCHAR(255) NOT NULL,
            applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (job_id) REFERENCES $jobs_table(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Execute the queries
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $jobs_sql );
        dbDelta( $applications_sql );
    }
    // Delete tables on plugin deactivation
    public static function delete_tables() {
        global $wpdb;

        $jobs_table = $wpdb->prefix . 'jobs';
        $applications_table = $wpdb->prefix . 'applications';

        // Drop tables if they exist
        $wpdb->query( "DROP TABLE IF EXISTS $jobs_table" );
        $wpdb->query( "DROP TABLE IF EXISTS $applications_table" );
    }
}