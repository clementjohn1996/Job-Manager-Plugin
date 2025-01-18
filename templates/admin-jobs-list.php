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
                <td><input type="checkbox" id="select-all" name="select-all"
                        style="width: 16px; height: 16px; margin-right: 10px;" /></td>
                <th>Position Title</th>
                <th>Is Featured</th>
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
                <td><input type="checkbox" class="job-checkbox"
                        style="width: 16px; height: 16px; margin-right: 10px;" /></td>
                <td><?php echo esc_html( $job->position_title ); ?></td>
                <td>
                    <?php echo ( $job->is_featured ) ? '✓' : '✗'; ?>
                </td>
                <td><?php echo esc_html( $job->job_type ); ?></td>
                <td><?php echo esc_html( $job->category ); ?></td>
                <td><?php echo esc_html( $job->job_location ); ?></td>
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
document.getElementById('select-all').addEventListener('click', function() {
    var checkboxes = document.querySelectorAll('.job-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = document.getElementById('select-all').checked;
    });
});

function deleteJob(jobId) {
    if (confirm('Are you sure you want to delete this job?')) {
        // Add AJAX call for deletion
    }
}
</script>