<?php

if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch jobs from wp_jobs table
global $wpdb;
$jobs_table = $wpdb->prefix . 'jobs'; 
$jobs_query = "SELECT * FROM $jobs_table WHERE is_approved = 1 AND position_taken = 0"; 
$jobs = $wpdb->get_results($jobs_query);
?>

<!-- HTML Form -->
<div class="job-application-form">
    <h2>Job Application</h2>

    <!-- Display Success or Error Messages -->
    <?php if (isset($success_message)) : ?>
    <div class="success-message"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)) : ?>
    <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="applicant_name">Your Name:</label>
            <input type="text" name="applicant_name" id="applicant_name" placeholder="Enter your full name" required>
        </div>

        <div class="form-group">
            <label for="applicant_email">Your Email:</label>
            <input type="email" name="applicant_email" id="applicant_email" placeholder="Enter your email address"
                required>
        </div>

        <div class="form-group">
            <label for="applicant_message">Message:</label>
            <textarea name="applicant_message" id="applicant_message"
                placeholder="Write your message (optional)"></textarea>
        </div>

        <div class="form-group">
            <label for="resume">Resume (PDF only):</label>
            <input type="file" name="resume" id="resume" accept=".pdf" required>
        </div>

        <button type="submit" name="submit_application">Submit Application</button>
    </form>
</div>

<style>
.error-message {
    color: red;
    margin-bottom: 10px;
}

.success-message {
    color: green;
    margin-bottom: 10px;
}

.form-group {
    margin-bottom: 15px;
}
</style>