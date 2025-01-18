<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch jobs from database or custom post type
$jobs = get_posts([
    'post_type' => 'job',
    'post_status' => 'publish',
    'numberposts' => -1,
]);

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Jobs</h1>
    <a href="admin.php?page=add-new-job" class="page-title-action">Add New</a>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Position Title</th>
                <th>Company Name</th>
                <th>Is Featured</th>
                <th>Job Type</th>
                <th>Category</th>
                <th>Expires</th>
                <th>Applications</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $jobs ) ) : ?>
            <?php foreach ( $jobs as $job ) : ?>
            <tr>
                <td><?php echo esc_html( $job->post_title ); ?></td>
                <td><?php echo esc_html( get_post_meta( $job->ID, 'expiry_date', true ) ); ?></td>
                <td>
                    <a href="post.php?post=<?php echo $job->ID; ?>&action=edit">Edit</a> |
                    <a href="#" onclick="deleteJob(<?php echo $job->ID; ?>)">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else : ?>
            <tr>
                <td colspan="3">No jobs found.</td>
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