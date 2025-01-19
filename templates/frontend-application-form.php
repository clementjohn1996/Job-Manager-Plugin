<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Handle form submission
if ( isset( $_POST['submit_application'] ) ) {
    // Sanitize and validate form data
    $job_id = isset( $_POST['job_id'] ) ? intval( $_POST['job_id'] ) : 0;
    $applicant_name = isset( $_POST['applicant_name'] ) ? sanitize_text_field( $_POST['applicant_name'] ) : '';
    $applicant_email = isset( $_POST['applicant_email'] ) ? sanitize_email( $_POST['applicant_email'] ) : '';
    $applicant_message = isset( $_POST['applicant_message'] ) ? sanitize_textarea_field( $_POST['applicant_message'] ) : '';
    $attached_file = '';

    // Handle file upload
    if ( isset( $_FILES['attached_file'] ) && !empty( $_FILES['attached_file']['name'] ) ) {
        $uploaded_file = $_FILES['attached_file'];
        $upload_dir = wp_upload_dir();
        $target_dir = $upload_dir['path'] . '/';
        $target_file = $target_dir . basename( $uploaded_file['name'] );

        // Check if the file is a PDF
        if ( strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) ) == 'pdf' ) {
            if ( move_uploaded_file( $uploaded_file['tmp_name'], $target_file ) ) {
                $attached_file = $upload_dir['url'] . '/' . basename( $uploaded_file['name'] );
            } else {
                // Handle file upload error
                echo '<p>File upload failed.</p>';
            }
        } else {
            // Handle invalid file type
            echo '<p>Only PDF files are allowed.</p>';
        }
    }

    // Insert data into the applications table
    global $wpdb;
    $applications_table = $wpdb->prefix . 'applications';
    $application_data = array(
        'job_id' => $job_id,
        'applicant_name' => $applicant_name,
        'applicant_email' => $applicant_email,
        'applicant_message' => $applicant_message,
        'attached_file' => $attached_file,
        'status' => 'Pending'
    );

    $inserted = $wpdb->insert( $applications_table, $application_data );

    if ( $inserted ) {
        echo '<p>Your application has been submitted successfully!</p>';
    } else {
        echo '<p>There was an error submitting your application. Please try again.</p>';
    }
}

// Fetch jobs from wp_jobs table
global $wpdb;
$jobs_table = $wpdb->prefix . 'jobs'; 
$jobs_query = "SELECT * FROM $jobs_table WHERE is_approved = 1 AND position_taken = 0"; 
$jobs = $wpdb->get_results($jobs_query);
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

    <div id="application-form" style="display: none; width: 80%; margin: 0 auto;">
        <h2>Job Application Form</h2>


        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="job_id" id="job-id" value="<?php echo $job->id; ?>">


            <div class="form-group" style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                <label for="applicant_name" style="flex: 1; text-align: left;">Your Name<span
                        style="color: red;">*</span>:</label>
                <input type="text" name="applicant_name" id="applicant_name" style="flex: 2; text-align: right;"
                    placeholder="Enter your full name" required>
            </div>


            <div class="form-group" style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                <label for="applicant_email" style="flex: 1; text-align: left;">Your Email<span
                        style="color: red;">*</span>:</label>
                <input type="email" name="applicant_email" id="applicant_email" style="flex: 2; text-align: right;"
                    placeholder="Enter your email address" required>
            </div>

            <div class="form-group" style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                <label for="applicant_message" style="flex: 1; text-align: left;">Message:</label>
                <textarea name="applicant_message" id="applicant_message" rows="4" style="flex: 2; text-align: right;"
                    placeholder="Write your message (optional)"></textarea>
            </div>

            <div class="form-group"
                style="display: flex; flex-direction: column; align-items: center; margin-bottom: 15px;">
                <label for="resume"
                    style="text-align: left; margin-bottom: 10px; font-weight: bold;">Attachments:</label>
                <div id="file-upload" style="border: 2px dashed #0073aa; padding: 20px; text-align: center; cursor: pointer;
                    background-color: #f9f9f9; position: relative; width: 80%; max-width: 400px;">
                    <div id="upload-placeholder" style="display: flex; flex-direction: column; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            style="opacity: 0.7;">
                            <path d="M12 19V6m0 0L5 13m7-7l7 7"></path>
                        </svg>
                        <span style="margin-top: 10px; color: #0073aa;">Drop File Here or Click to Upload</span>
                    </div>
                    <input type="file" id="attached_file" name="attached_file" accept=".pdf" style="display: none;" />
                    <img id="file-preview"
                        style="display: none; margin-top: 10px; max-width: 100px; max-height: 100px;" />
                </div>
            </div>


            <div class="form-actions" style="text-align: center; margin-top: 20px;">
                <button type="submit" name="submit_application" id="submit_application" class=" submit-button"
                    style="padding: 10px 20px; font-size: 16px;">Submit
                    Application</button>
                <button type="button" id="back-to-jobs" class="back-button"
                    style="padding: 10px 20px; font-size: 16px; margin-left: 10px;">Back to Jobs</button>
            </div>
        </form>
    </div>

    <style>
    /* Form Container */
    #application-form {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        background-color: #ffffff;
        width: 90%;
        min-width: 700px;
        max-width: 1200px;
        margin: 20px auto;
        font-family: Arial, sans-serif;
    }

    /* Input Fields and Textarea */
    textarea,
    input[type="text"],
    input[type="email"],
    input[type="file"] {
        width: 100%;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        color: #333;
        margin-top: 5px;
    }

    textarea {
        resize: vertical;
    }

    /* Labels */
    label {
        font-size: 18px;
        font-weight: bold;
        color: #444;
    }

    /* Buttons */
    button {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    .submit-button {
        background-color: #28a745;
    }

    .submit-button:hover {
        background-color: #218838;
    }

    .back-button {
        background-color: #6c757d;
    }

    .back-button:hover {
        background-color: #5a6268;
    }

    /* Header */
    h2 {
        text-align: center;
        color: #333333;
        margin-bottom: 25px;
        font-size: 24px;
        font-weight: bold;
    }

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
        const fileUploadDiv = document.querySelector('[id="file-upload"]');
        const fileInput = document.getElementById('attached_file');
        const filePreview = document.getElementById('file-preview');
        const uploadPlaceholder = document.getElementById('upload-placeholder');

        // Show application form on "Apply" button click
        applyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const jobId = this.getAttribute('data-job-id');
                if (jobIdField) {
                    jobIdField.value = jobId;
                }
                if (jobsList) jobsList.style.display = 'none';
                if (applicationForm) applicationForm.style.display = 'block';
            });
        });

        // Go back to job listings
        if (backToJobsButton) {
            backToJobsButton.addEventListener('click', function() {
                if (applicationForm) applicationForm.style.display = 'none';
                if (jobsList) jobsList.style.display = 'block';
            });
        }

        // File upload interaction
        if (fileUploadDiv && fileInput && uploadPlaceholder) { // Renamed
            fileUploadDiv.addEventListener('click', () => { // Renamed
                fileInput.click();
            });

            fileInput.addEventListener('change', (event) => {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        if (filePreview) {
                            filePreview.src = e.target.result;
                            filePreview.style.display = 'block';
                        }
                        if (uploadPlaceholder) uploadPlaceholder.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                } else {
                    if (logfilePreview) {
                        filePreview.src = '';
                        filePreview.style.display = 'none';
                    }
                    if (uploadPlaceholder) uploadPlaceholder.style.display = 'flex';
                }
            });
        }
    });
    </script>