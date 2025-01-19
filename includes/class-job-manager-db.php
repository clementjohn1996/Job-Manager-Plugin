<?php
/**
 * Class Job_Manager_DB
 *
 * Handles database operations for the Job Manager plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Job_Manager_DB {

    /**
     * Create or update tables on plugin activation.
     */
    public static function create_tables() {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}jobs");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}applications");
        $charset_collate = $wpdb->get_charset_collate();

        // Create or update Jobs table
        $jobs_table = $wpdb->prefix . 'jobs';
        $jobs_sql = "CREATE TABLE $jobs_table (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            company_id BIGINT(20) UNSIGNED NOT NULL,  
            position_title VARCHAR(255) NOT NULL, 
            job_description TEXT NOT NULL,
            job_type VARCHAR(50) NOT NULL,
            category VARCHAR(100) NOT NULL,
            job_location VARCHAR(100),     
            publish_date DATE,     
            expire_date DATE,          
            company_logo VARCHAR(255), 
            is_approved TINYINT(1) DEFAULT 0, 
            is_featured TINYINT(1) DEFAULT 0,
            position_taken TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (company_id) REFERENCES {$wpdb->prefix}companies(id) ON DELETE SET NULL
        ) $charset_collate;";

        // Create or update Applications table
        $applications_table = $wpdb->prefix . 'applications';
        $applications_sql = "CREATE TABLE $applications_table (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            job_id BIGINT(20) UNSIGNED,
            applicant_name VARCHAR(255) NOT NULL,
            applicant_email VARCHAR(255) NOT NULL,
            applicant_message TEXT,
            attached_file VARCHAR(255),
            status VARCHAR(50) DEFAULT 'Pending',
            FOREIGN KEY (job_id) REFERENCES {$wpdb->prefix}jobs(id) ON DELETE CASCADE
        ) $charset_collate;";

        // Execute the queries
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $jobs_sql );
        dbDelta( $applications_sql ); 
        echo $wpdb->last_error;  
        $wpdb->print_error();     
    }

    /**
     * Delete tables on plugin deactivation.
     */
    public static function delete_tables() {
        global $wpdb;

        $jobs_table = $wpdb->prefix . 'jobs';
        $applications_table = $wpdb->prefix . 'applications';

        $wpdb->query( "DROP TABLE IF EXISTS $applications_table" );
        $wpdb->query( "DROP TABLE IF EXISTS $jobs_table" );
    }

    /**
     * Insert a job into the jobs table.
     *
     * @param array $data Job data to insert.
     * @return bool True on success, false on failure.
     */
    public static function insert_job( $data ) {
        global $wpdb;

        $jobs_table = $wpdb->prefix . 'jobs';

        // Sanitize and validate input data
        $insert_data = array(
            'company_id'      => isset( $data['company_id'] ) ? intval( $data['company_id'] ) : 0,
            'position_title'  => isset( $data['position_title'] ) ? sanitize_text_field( $data['position_title'] ) : '',
            'job_description' => isset( $data['job_description'] ) ? wp_kses_post( $data['job_description'] ) : '',
            'job_type'        => isset( $data['job_type'] ) ? sanitize_text_field( $data['job_type'] ) : '',
            'category'        => isset( $data['category'] ) ? sanitize_text_field( $data['category'] ) : '',
            'job_location'    => isset( $data['job_location'] ) ? sanitize_text_field( $data['job_location'] ) : '',
            'publish_date'    => isset( $data['publish_date'] ) ? sanitize_text_field( $data['publish_date'] ) : '',
            'expire_date'     => isset( $data['expire_date'] ) ? sanitize_text_field( $data['expire_date'] ) : '',
            'company_logo'    => isset( $data['company_logo'] ) ? esc_url_raw( $data['company_logo'] ) : '',
            'is_approved'     => isset( $data['is_approved'] ) ? intval( $data['is_approved'] ) : 0, // New field
            'is_featured'     => isset( $data['is_featured'] ) ? intval( $data['is_featured'] ) : 0, // New field
            'position_taken'  => isset( $data['position_taken'] ) ? intval( $data['position_taken'] ) : 0, // New field
        );

        // Attempt insertion
        $result = $wpdb->insert( $jobs_table, $insert_data );

        if ( false === $result ) {
            error_log( 'Job insert failed: ' . $wpdb->last_error );
            return false;
        }

        return true;
    }
 /**
 * Insert an application into the applications table.
 *
 * @param array $data Application data to insert.
 * @return bool True on success, false on failure.
 */
public static function insert_application($data) {
    global $wpdb;

    $applications_table = $wpdb->prefix . 'applications';

    // Sanitize and validate input data
    $insert_data = array(
        'job_id' => isset($data['job_id']) ? intval($data['job_id']) : 0,
        'applicant_name' => isset($data['applicant_name']) ? sanitize_text_field($data['applicant_name']) : '',
        'applicant_email' => isset($data['applicant_email']) ? sanitize_email($data['applicant_email']) : '',
        'applicant_message'=> isset($data['applicant_message']) ? sanitize_email($data['applicant_message']) : '',
        'file_url' => isset($data['file_url']) ? esc_url_raw($data['file_url']) : '',
        'status' => isset($data['status']) ? sanitize_text_field($data['status']) : 'Pending',
    );

    // Attempt insertion
    $result = $wpdb->insert($applications_table, $insert_data);

    if (false === $result) {
        error_log('Application insert failed: ' . $wpdb->last_error);
        return false;
    }

    return true;
}}
?>