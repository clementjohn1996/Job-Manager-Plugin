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
                                style="border: 2px dashed #0073aa; padding: 20px; text-align: center; cursor: pointer; background-color: #f9f9f9;">
                                <span>Click to upload your logo</span>
                                <input type="file" id="company_logo" name="company_logo" accept="image/*"
                                    style="display: none;" />
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
            <table class="form-table" style="width: 100%; font-size: 13px; border-spacing: 0 8px;">
                <tr>
                    <th style="text-align: left;"><label for="publish_date" style="font-weight: 500;">Published</label>
                    </th>
                    <td>
                        <input type="datetime-local" id="publish_date" name="publish_date"
                            value="<?php echo current_time('mysql'); ?>" readonly
                            style="width: calc(60% - 1px); padding: 4px 8px; font-size: 13px; border: 1px solid #ccc; border-radius: 4px;" />
                        <a href="javascript:void(0);" onclick="editPublishDate()"
                            style="color: #007bff; text-decoration: none; font-size: 13px; margin-left: 8px;">Edit</a>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left;"><label for="expire_date" style="font-weight: 500;">Expires</label>
                    </th>
                    <td>
                        <input type="date" id="expire_date" name="expire_date"
                            style="width: calc(60% - 1px); padding: 4px 8px; font-size: 13px; border: 1px solid #ccc; border-radius: 4px;"
                            readonly />
                        <a href="javascript:void(0);" onclick="editExpireDate()"
                            style="color: #007bff; text-decoration: none; font-size: 13px; margin-left: 8px;">Edit</a>
                        <a href="javascript:void(0);" onclick="neverExpire()"
                            style="color: #007bff; text-decoration: none; font-size: 13px; margin-left: 2px;">Never
                            Expires</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
// JavaScript to handle file input trigger
document.getElementById('logo-upload').addEventListener('click', function() {
    document.getElementById('company_logo').click();
});
</script>