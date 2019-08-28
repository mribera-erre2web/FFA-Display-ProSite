<?php

/**
 * Trigger this file on Plugin unistall
 *
 * @package EntourancePlugin
 *
 */

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ){
    die;
}


// Clear Database stored data
global $wpdb;

$table_name = $wpdb->prefix . 'ffa_stats_match_week';

$sql = "DROP TABLE IF EXISTS ".$table_name.";";

$table_name = $wpdb->prefix . 'ffa_stats_player';

$sql = "DROP TABLE IF EXISTS ".$table_name.";";

$table_name = $wpdb->prefix . 'ffa_stats_match_player';

$sql = "DROP TABLE IF EXISTS ".$table_name.";";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
