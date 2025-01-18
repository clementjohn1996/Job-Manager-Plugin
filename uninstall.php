<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-job-manager-db.php';

Job_Manager_DB::delete_tables();