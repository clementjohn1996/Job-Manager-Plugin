<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Get application ID from the query string
if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
    $application_id = intval( $_GET['id'] );

    // Fetch the application details from the database
    global $wpdb;
    $application = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}applications WHERE id = %d", $application_id ) );

    if ( $application ) :
        ?>
<div class="wrap" id="application-details-form"
    style="width: 80%; margin: 0 auto; border: 2px solid #e0e0e0; border-radius: 12px; padding: 30px; background-color: #fff; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
    <h2 style="text-align: center; color: #333333; margin-bottom: 25px; font-size: 24px; font-weight: bold;">Application
        Details for <?php echo esc_html( $application->applicant_name ); ?></h2>

    <table style="width: 100%; border-collapse: collapse; font-size: 16px; color: #444;">
        <tr style="background-color: #f8f9fa; border-bottom: 2px solid #ddd;">
            <th style="padding: 12px; text-align: left; font-size: 18px; font-weight: bold;">Field</th>
            <th style="padding: 12px; text-align: left; font-size: 18px; font-weight: bold;">Value</th>
        </tr>
        <tr>
            <td
                style="padding: 12px; font-size: 16px; background-color: #f9f9f9; border-bottom: 1px solid #ddd; font-weight: bold;">
                Your Name</td>
            <td style="padding: 12px; background-color: #f9f9f9; border-bottom: 1px solid #ddd;">
                <?php echo esc_html( $application->applicant_name ); ?></td>
        </tr>
        <tr>
            <td
                style="padding: 12px; font-size: 16px; background-color: #f9f9f9; border-bottom: 1px solid #ddd; font-weight: bold;">
                Your Email</td>
            <td style="padding: 12px; background-color: #f9f9f9; border-bottom: 1px solid #ddd;">
                <?php echo esc_html( $application->applicant_email ); ?></td>
        </tr>
        <tr>
            <td
                style="padding: 12px; font-size: 16px; background-color: #f9f9f9; border-bottom: 1px solid #ddd; font-weight: bold;">
                Message</td>
            <td style="padding: 12px; background-color: #f9f9f9; border-bottom: 1px solid #ddd;">
                <?php echo !empty( $application->applicant_message ) ? esc_html( $application->applicant_message ) : 'No message provided.'; ?>
            </td>
        </tr>
        <tr>
            <td
                style="padding: 12px; font-size: 16px; background-color: #f9f9f9; border-bottom: 1px solid #ddd; font-weight: bold;">
                Application Status</td>
            <td style="padding: 12px; background-color: #f9f9f9; border-bottom: 1px solid #ddd;">
                <?php echo esc_html( $application->status ); ?></td>
        </tr>
        <tr>
            <td
                style="padding: 12px; font-size: 16px; background-color: #f9f9f9; border-bottom: 1px solid #ddd; font-weight: bold;">
                Resume</td>
            <td style="padding: 12px; background-color: #f9f9f9; border-bottom: 1px solid #ddd;">
                <?php if ( ! empty( $application->attached_file ) ) : ?>
                <a href="<?php echo esc_url( $application->attached_file ); ?>" class="button"
                    style="background-color: #007bff; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none;"
                    target="_blank">View Resume</a>
                <?php else : ?>
                <span>No resume available</span>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <!-- Back Button -->
    <button type="button" id="back-to-jobs" class="back-button"
        style="background-color: #6c757d; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease; font-size: 16px;"
        onclick="window.location.href='<?php echo admin_url( 'admin.php?page=applications' ); ?>';">Back to
        Applications</button>

</div>
<?php
    else :
        echo '<div class="wrap"><p>No application found with that ID.</p></div>';
    endif;
} else {
    echo '<div class="wrap"><p>No application ID provided.</p></div>';
}
?>

<style>
/* Custom Styles for Application Details in Table */
#application-details-form {
    width: 80%;
    margin: 0 auto;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 30px;
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

h2 {
    text-align: center;
    color: #333333;
    margin-bottom: 25px;
    font-size: 24px;
    font-weight: bold;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
    color: #444;
}

th,
td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #f8f9fa;
    font-size: 18px;
    font-weight: bold;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

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

.back-button {
    background-color: #6c757d;
    color: #fff;
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