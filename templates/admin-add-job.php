<?php
// Handle the form submission
if (isset($_POST['submit_job'])) {
    // Sanitize form data
    $company_name = sanitize_text_field($_POST['company_name']);
    $position_title = sanitize_text_field($_POST['position_title']);
    $job_description = wp_kses_post($_POST['job_description']);
    $job_type = sanitize_text_field($_POST['job_type']);
    $category = sanitize_text_field($_POST['category']);
    $location = sanitize_text_field($_POST['location']);
    $publish_date = sanitize_text_field($_POST['publish_date']);
    $expire_date = sanitize_text_field($_POST['expire_date']);

    // Handle logo upload
    $company_logo = '';
    if (isset($_FILES['company_logo']) && !empty($_FILES['company_logo']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $uploaded_logo = media_handle_upload('company_logo', 0);
        if (is_wp_error($uploaded_logo)) {
            $company_logo = '';
        } else {
            $company_logo = wp_get_attachment_url($uploaded_logo);
        }
    }

    // Prepare job data for insertion
    $job_data = array(
        'company_id'      => 1, // You can replace this with the actual company ID if available
        'position_title'  => $position_title,
        'job_description' => $job_description,
        'job_type'        => $job_type,
        'category'        => $category,
        'job_location'    => $location,
        'publish_date'    => $publish_date,
        'expire_date'     => $expire_date,
        'company_logo'    => $company_logo,
    );

    // Insert job data into the database
    $inserted = Job_Manager_DB::insert_job($job_data);
    if ($inserted) {
        echo '<div class="updated"><p>Job successfully published.</p></div>';
    } else {
        echo '<div class="error"><p>Failed to publish the job.</p></div>';
    }
}
?>
<div class="wrap">
    <h1>Add New Job</h1>
    <div style="display: flex; justify-content: space-between;">
        <div style="width: 60%;">
            <h2>Job Information</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <table class="form-table">
                    <tr>
                        <th><label for="position_title">Position Title</label></th>
                        <td><input type="text" id="position_title" name="position_title" style="width: 100%;"
                                required /></td>
                    </tr>
                    <tr>
                        <th><label for="job_description">Description</label></th>
                        <td><?php wp_editor('', 'job_description', array('textarea_name' => 'job_description', 'editor_class' => 'widefat')); ?>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="job_type">Job Type</label></th>
                        <td>
                            <select id="job_type" name="job_type" style="width: 50%;" required>
                                <option value="full-time">Full-Time</option>
                                <option value="part-time">Part-Time</option>
                                <option value="remote">Remote</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="category">Category</label></th>
                        <td>
                            <select id="category" name="category" style="width: 50%;" required>
                                <option value="programmer">Programmer</option>
                                <option value="software-developer">Software Developer</option>
                                <option value="wordpress-developer">WordPress Developer</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <h2>Company Information</h2>
                <table class="form-table">
                    <tr>
                        <th><label for="company_name">Company Name</label></th>
                        <td><input type="text" id="company_name" name="company_name" style="width: 100%;" required />
                        </td>
                    </tr>
                    <tr>
                        <th><label for="company_logo">Company Logo</label></th>
                        <td>
                            <div id="logo-upload"
                                style="border: 2px dashed #0073aa; padding: 20px; text-align: center; cursor: pointer; background-color: #f9f9f9; position: relative;">
                                <div id="upload-placeholder"
                                    style="display: flex; flex-direction: column; align-items: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" style="opacity: 0.7;">
                                        <path d="M12 19V6m0 0L5 13m7-7l7 7"></path>
                                    </svg>
                                    <span style="margin-top: 10px; color: #0073aa;">Drop File Here or Click to
                                        Upload</span>
                                </div>
                                <input type="file" id="company_logo" name="company_logo" accept="image/*"
                                    style="display: none;" />
                                <img id="logo-preview"
                                    style="display: none; margin-top: 10px; max-width: 100px; max-height: 100px;" />
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th><label for="location">Location</label></th>
                        <td>
                            <select id="location" name="location" style="width: 50%;" required>
                                <option value="new-york">New York</option>
                                <option value="london">London</option>
                                <option value="remote">Remote</option>
                            </select>
                        </td>
                    </tr>
                    <tr style="display: none;">
                        <th><label for="publish_date" style="font-weight: 500;">Published</label></th>
                        <td>
                            <input type="hidden" id="publish_date" name="publish_date"
                                value="<?php echo current_time('Y-m-d'); ?>" required />
                        </td>
                    </tr>
                    <tr style="display: none;">
                        <th><label for="expire_date" style="font-weight: 500;">Expires</label></th>
                        <td>
                            <input type="hidden" id="expire_date" name="expire_date"
                                value="<?php echo current_time('Y-m-d'); ?>" required />
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="submit_job" id="submit_job" class="button button-primary"
                        value="Publish Job" style="background-color: #0073aa; color: white;" />
                </p>
            </form>
        </div>


        <!-- Listing Box (small box on the top-right) -->
        <div
            style="width: 30%; background-color: #ffffff; padding: 5px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); height: auto; border: 1px solid #e0e0e0;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 10px; color: #333;">Listing</h3>
            <table class="form-table" style="width: 100%; font-size: 13px; border-spacing: 0 8px;">
                <tr>
                    <th style="text-align: left;"><label for="publish_date" style="font-weight: 500;">Published</label>
                    </th>
                    <td>
                        <input type="date" id="publish_date_input" name="publish_date_input"
                            value="<?php echo current_time('Y-m-d'); ?>" required
                            style="width: calc(60% - 1px); padding: 4px 8px; font-size: 13px; border: 1px solid #ccc; border-radius: 4px;" />
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="expire_date" style="font-weight: 500;">Expires</label>
                    </th>
                    <td>
                        <input type="date" id="expire_date_input" name="expire_date_input"
                            value="<?php echo current_time('Y-m-d'); ?>" required
                            style="width: calc(60% - 1px); padding: 4px 8px; font-size: 13px; border: 1px solid #ccc; border-radius: 4px;" />
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="Button" name="status_update" id="status_update" class="button button-primary"
                    value="Update Status" style="background-color: #0073aa; color: white;" />
            </p>
        </div>
    </div>
</div>

<script>
// Handle logo upload (company logo)
document.addEventListener('DOMContentLoaded', function() {
    const logoUploadDiv = document.getElementById('logo-upload');
    const fileInput = document.getElementById('company_logo');
    const logoPreview = document.getElementById('logo-preview');
    const uploadPlaceholder = document.getElementById('upload-placeholder');

    logoUploadDiv.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                logoPreview.src = e.target.result;
                logoPreview.style.display = 'block';
                uploadPlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            logoPreview.src = '';
            logoPreview.style.display = 'none';
            uploadPlaceholder.style.display = 'flex';
        }
    });

    // Update the hidden fields with the new values and trigger submit
    document.getElementById('status_update').addEventListener('click', function() {
        var publishDate = document.getElementById('publish_date_input').value;
        var expireDate = document.getElementById('expire_date_input').value;
        document.getElementById('publish_date').value = publishDate;
        document.getElementById('expire_date').value = expireDate;
        document.getElementById('submit_job').click();
        console.log('Updated Publish Date: ' + publishDate);
        console.log('Updated Expire Date: ' + expireDate);
    });
});
</script>