"# Job-Manager-Plugin" 

Description
The Job Manager Plugin is a simple WordPress plugin that enables handling job listings, applications, and related functionalities. It allows users to apply for jobs both through the front-end website form and via an API.

Features
Job Applications via Frontend Form: Users can apply for jobs through a simple form on the website.
Job Applications via API: Allows submitting job applications programmatically using a REST API.
View and Manage Applications in Admin: Admin users can manage and view job applications directly from the WordPress dashboard.
Shortcode Integration (Frontend Application Form)
You can integrate the Job Application Form into any page or post by adding a simple shortcode.

Step 1: Add the Shortcode to Your Page
Edit the page or post where you want to display the job application form.

Insert the following shortcode:

plaintext
Copy
Edit
[display_job_application]
This shortcode will render the job application form, allowing users to apply for jobs.

Customization
The form layout is defined in the plugin's template files. You can customize the form's appearance and functionality by editing the frontend-application-form.php file located in the templates folder of the plugin.

REST API for Job Applications
The plugin provides a REST API endpoint to submit job applications programmatically. This is useful for integrating the job application process into external systems or custom forms.
