<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch applications from database
global $wpdb;
$applications = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}applications");

// Get unique dates for filtering
$dates = $wpdb->get_results("SELECT DISTINCT DATE(submitted_at) as date FROM {$wpdb->prefix}applications ORDER BY date DESC");

?>

<div class="wrap">
    <h1>Job Applications</h1>

    <!-- Table with Date Filter Dropdown next to 'Posted' -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Applicant Email</th>
                <th>Job</th>
                <th>
                    <i class="dashicons dashicons-paperclip" style="color: #4a4a4a; margin-right: 5px;"></i> Posted

                    <!-- Date filter as a link -->
                    <form method="get" action="" style="display:inline;">
                        <div id="calendar-container" style="position: relative; display: inline-block;">
                            <button type="button" id="calendar-toggle"
                                style="background: transparent; border: none; font-size: 16px; color: #0073aa; cursor: pointer;">
                                ▼
                            </button>
                            <div id="calendar"
                                style="display: none; position: absolute; top: 30px; z-index: 999; background-color: white; border: 1px solid #ccc; padding: 10px;">
                                <input type="date" name="filter-date" id="filter-date"
                                    style="border: none; font-size: 16px; background: transparent; color: #0073aa;"
                                    value="<?php echo isset( $_GET['filter-date'] ) ? esc_attr( $_GET['filter-date'] ) : ''; ?>" />
                            </div>
                        </div>
                    </form>
                </th>

                <script>
                // Show calendar when the dropdown button is clicked
                document.getElementById('calendar-toggle').addEventListener('click', function() {
                    var calendar = document.getElementById('calendar');
                    calendar.style.display = calendar.style.display === 'none' ? 'block' : 'none';
                });

                // Hide calendar and trigger form submission when a date is selected
                document.getElementById('filter-date').addEventListener('change', function() {
                    this.form.submit(); // Submit the form to filter based on the selected date
                    document.getElementById('calendar').style.display = 'none'; // Hide the calendar
                });
                </script>

                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $applications ) ) : ?>
            <?php 
            // Apply date filter if selected
            $filter_date = isset( $_GET['filter-date'] ) ? $_GET['filter-date'] : '';
            foreach ( $applications as $app ) : 
                if ( $filter_date && strpos( $app->submitted_at, $filter_date ) === false ) {
                    continue;
                }
            ?>
            <tr>
                <td><?php echo esc_html( $app->applicant_name ); ?></td>
                <td><?php echo esc_html( $app->applicant_email ); ?></td>
                <td><?php echo esc_html( get_the_title( $app->job_id ) ); ?></td>
                <td><?php echo esc_html( $app->status ); ?></td> <!-- Display Status -->
                <td>
                    <a href="admin.php?page=application-details&id=<?php echo $app->id; ?>">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else : ?>
            <tr>
                <td colspan="5">No applications found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>