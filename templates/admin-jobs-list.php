<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch jobs from wp_jobs table
global $wpdb;
$jobs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jobs");

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Jobs</h1>
    <a href="admin.php?page=add-new-job" class="page-title-action">Add New</a>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Position Title</th>
                <th>Company Name</th>
                <th>Job Type</th>
                <th>Category</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Published</th>
                <th>Expires</th>
                <th>Company Logo</th>
                <th>Applications</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $jobs ) ) : ?>
            <?php foreach ( $jobs as $job ) : ?>
            <tr>
                <td><?php echo esc_html( $job->position_title ); ?></td>
                <td><?php echo esc_html( $job->company_id ); // You can fetch company name if it's a related table ?>
                </td>
                <td><?php echo esc_html( $job->job_type ); ?></td>
                <td><?php echo esc_html( $job->category ); ?></td>
                <td><?php echo esc_html( $job->job_location ); ?></td>
                <td><?php echo esc_html( $job->salary ? '$' . number_format( $job->salary, 2 ) : 'N/A' ); ?></td>
                <td><?php echo esc_html( $job->publish_date ); ?></td>
                <td><?php echo esc_html( $job->expire_date ); ?></td>
                <td>
                    <?php if ( $job->company_logo ) : ?>
                    <img src="<?php echo esc_url( $job->company_logo ); ?>" alt="Company Logo" width="50" height="50" />
                    <?php else : ?>
                    No Logo
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                        // Assuming applications are stored as comments on each job post (replace with your logic if needed)
                        $applications = get_comments( [
                            'post_id' => $job->id,
                            'status' => 'approve',
                        ] );
                        echo count( $applications );
                        ?>
                </td>
                <td>
                    <a href="post.php?post=<?php echo $job->id; ?>&action=edit">Edit</a> |
                    <a href="#" onclick="deleteJob(<?php echo $job->id; ?>)">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else : ?>
            <tr>
                <td colspan="10">No jobs found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function deleteJob(jobId) {
    if (confirm('Are you sure you want to delete this job?')) {
        // Add AJAX call for deletion
    }
}
</script>