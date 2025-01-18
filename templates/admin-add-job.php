<?php
if (isset($_POST['submit_job'])) {
    // Handle form submission and insert data into the database
    if (isset($_POST['position_title'], $_POST['job_description'], $_POST['job_type'], $_POST['category'], $_POST['company_name'], $_POST['location'])) {
        // Collect and sanitize the data
        $position_title = sanitize_text_field($_POST['position_title']);
        $job_description = wp_kses_post($_POST['job_description']);
        $job_type = sanitize_text_field($_POST['job_type']);
        $category = sanitize_text_field($_POST['category']);
        $company_name = sanitize_text_field($_POST['company_name']);
        $location = sanitize_text_field($_POST['location']);
        $publish_date = isset( $data['publish_date'] ) ? sanitize_text_field( $data['publish_date'] ) : '';
        $expire_date = isset( $data['expire_date'] ) ? sanitize_text_field( $data['expire_date'] ) : '';



        // Handle company logo upload
        if (isset($_FILES['company_logo']) && !empty($_FILES['company_logo']['name'])) {
            // Use WordPress media upload function for better handling
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $file = $_FILES['company_logo'];
            $upload = media_handle_upload('company_logo', 0);

            if (is_wp_error($upload)) {
                $company_logo = ''; 
            } else {
                $company_logo = wp_get_attachment_url($upload);
            }
        } else {
            $company_logo = ''; 
        }
        
        $company_id = sanitize_title($company_name) . '-' . time();

        $job_data = array(
            'company_id' => $company_id,
            'job_title' => $position_title,
            'job_description' => $job_description,
            'job_type' => $job_type,
            'category' => $category,
            'job_location' => $location, 
            'publish_date' => $publish_date,
            'expire_date' => $expire_date,
            'company_logo' => $company_logo,
        );

        // Insert the job into the database
        $inserted = Job_Manager_DB::insert_job($job_data);

        if ($inserted) {
            echo '<div class="updated"><p>Job added successfully!</p></div>';echo $publish_date;
        } else {
            echo '<div class="error"><p>There was an error adding the job. Please try again.</p></div>';
        }
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
            <form method="POST" action="" enctype="multipart/form-data">
                <table class="form-table" style="width: 100%; font-size: 13px; border-spacing: 0 8px;">
                    <tr>
                        <th style="text-align: left;"><label for="publish_date"
                                style="font-weight: 500;">Published</label></th>
                        <td>
                            <input type="date" id="publish_date" name="publish_date"
                                value="<?php echo current_time('Y-m-d'); ?>" required
                                style="width: calc(60% - 1px); padding: 4px 8px; font-size: 13px; border: 1px solid #ccc; border-radius: 4px;" />
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: left;"><label for="expire_date" style="font-weight: 500;">Expires</label>
                        </th>
                        <td>
                            <input type="date" id="expire_date" name="expire_date"
                                value="<?php echo current_time('Y-m-d'); ?>" required
                                style="width: calc(60% - 1px); padding: 4px 8px; font-size: 13px; border: 1px solid #ccc; border-radius: 4px;" />
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="submit_job" id="submit_job" class="button button-primary" value="Publish"
                        style="background-color: #0073aa; color: white;" />
                </p>
            </form>
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
});
</script>