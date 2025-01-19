<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch applications from the database
global $wpdb;
$applications = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}applications");

?>

<div class="wrap">
    <h1>Job Applications</h1>

    <!-- Table for Applications -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Applicant Email</th>
                <th>Job</th>
                <th>Posted</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $applications ) ) : ?>
            <?php foreach ( $applications as $app ) : ?>
            <tr>
                <td><?php echo esc_html( $app->applicant_name ); ?></td>
                <td><?php echo esc_html( $app->applicant_email ); ?></td>
                <td><?php echo esc_html( get_the_title( $app->job_id ) ); ?></td>
                <td><?php echo esc_html( $app->status ); ?></td> <!-- Display Status -->
                <td>
                    <!-- Ensure the link points to admin.php page -->
                    <a href="admin.php?page=applications&view=application-details&id=<?php echo $app->id; ?>">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else : ?>
            <tr>
                <td colspan="5">No applications found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>