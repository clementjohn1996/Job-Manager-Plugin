<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb;


// Fetch jobs
$jobs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jobs ORDER BY publish_date DESC");

?>

<div class="wrap">
    <h1>Jobs</h1>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Publish Date</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $jobs ) ) : ?>
            <?php foreach ( $jobs as $job ) : ?>
            <tr>
                <td><?php echo esc_html( $job->title ); ?></td>
                <td><?php echo esc_html( $job->publish_date ); ?></td>
                <td><?php echo esc_html( $job->expiry_date ); ?></td>
                <td>
                    <a href="#">Edit</a> | <a href="#">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else : ?>
            <tr>
                <td colspan="4">No jobs found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>