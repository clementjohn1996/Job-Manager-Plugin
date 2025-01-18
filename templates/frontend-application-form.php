<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch jobs from wp_jobs table
global $wpdb;
$jobs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jobs");

?>

<div class="job-manager-wrapper">
    <h1 class="heading">Available Jobs</h1>

    <div id="jobs-list">
        <table class="jobs-table">
            <thead>
                <tr>
                    <th>Position Title</th>
                    <th>Job Type</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Published</th>
                    <th>Expires</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( ! empty( $jobs ) ) : ?>
                <?php foreach ( $jobs as $job ) : ?>
                <tr>
                    <td><?php echo esc_html( $job->position_title ); ?></td>
                    <td><?php echo esc_html( $job->job_type ); ?></td>
                    <td><?php echo esc_html( $job->category ); ?></td>
                    <td><?php echo esc_html( $job->job_location ); ?></td>
                    <td><?php echo esc_html( $job->publish_date ); ?></td>
                    <td><?php echo esc_html( $job->expire_date ); ?></td>
                    <td>
                        <button class="apply-button" data-job-id="<?php echo esc_attr( $job->id ); ?>">
                            Apply Now
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else : ?>
                <tr>
                    <td colspan="7">No jobs found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="application-form" style="display: none;">
        <h2>Job Application Form</h2>
        <form method="post" action="">
            <input type="hidden" name="job_id" id="job-id" value="">
            <div class="form-group">
                <label for="applicant_name">Your Name:</label>
                <input type="text" name="applicant_name" id="applicant_name" placeholder="Enter your full name"
                    required>
            </div>
            <div class="form-group">
                <label for="applicant_email">Your Email:</label>
                <input type="email" name="applicant_email" id="applicant_email" placeholder="Enter your email address"
                    required>
            </div>
            <div class="form-group">
                <label for="applicant_resume">Upload Resume:</label>
                <input type="file" name="applicant_resume" id="applicant_resume" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="submit-button">Submit Application</button>
                <button type="button" id="back-to-jobs" class="back-button">Back to Jobs</button>
            </div>
        </form>
    </div>
</div>

<style>
/* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

/* Wrapper */
.job-manager-wrapper {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background: #ffffff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

/* Heading */
.heading {
    text-align: center;
    color: #333333;
    margin-bottom: 20px;
}

/* Job Table */
.jobs-table {
    width: 100%;
    border-collapse: collapse;
}

.jobs-table th,
.jobs-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #dddddd;
}

.jobs-table th {
    background-color: #f8f9fa;
    color: #333333;
}

.jobs-table tr:hover {
    background-color: #f1f1f1;
}

.apply-button {
    background-color: #007bff;
    color: #ffffff;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.apply-button:hover {
    background-color: #0056b3;
}

/* Application Form */
#application-form {
    margin-top: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555555;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #dddddd;
    border-radius: 5px;
    font-size: 14px;
    color: #333333;
}

.form-actions {
    display: flex;
    justify-content: space-between;
}

.submit-button {
    background-color: #28a745;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-button:hover {
    background-color: #218838;
}

.back-button {
    background-color: #6c757d;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: #5a6268;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyButtons = document.querySelectorAll('.apply-button');
    const jobsList = document.getElementById('jobs-list');
    const applicationForm = document.getElementById('application-form');
    const jobIdField = document.getElementById('job-id');
    const backToJobsButton = document.getElementById('back-to-jobs');

    // Show application form on "Apply" button click
    applyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const jobId = this.getAttribute('data-job-id');
            jobIdField.value = jobId;
            jobsList.style.display = 'none';
            applicationForm.style.display = 'block';
        });
    });

    // Go back to job listings
    backToJobsButton.addEventListener('click', function() {
        applicationForm.style.display = 'none';
        jobsList.style.display = 'block';
    });
});
</script>