<?php
/**
 * Plugin Name: FFA Display Plugin
 * Plugin URI: https://www.erre2web.com/ffa-display
 * Description: Display FFA Scores content using a shortcode to insert in a page or post (Still in development)
 * Version: 1.0
 * Text Domain: ffa-display
 * Author:  Marco 'xerud' Ribera
 * Author URI: https://www.erre2web.com
 */

include('score.php');

function ffa_display_plugin($atts) {
    $Content = render($atts['all']);

    return $Content;
}

function add_my_stylesheet(){
    wp_enqueue_style( 'isolated-bootstrap', plugins_url( '/css/bootstrap-iso.min.css', __FILE__ ) );
    wp_enqueue_style( 'isolated-bootstrap-override', plugins_url( '/css/style.css', __FILE__ ) );
}

function add_admin_pages(){
    add_menu_page( 'FFA Plugin', 'FFA Display', 'manage_options', 'ffa_display_plugin', 'admin_index','dashicons-awards', 110);
}

function admin_index(){
    require_once plugin_dir_path(__FILE__).'templates/admin.php';
}

function custom_table_type(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'ffa_stats_player';

    $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
		    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `playerId` int(11),
            `nickname` varchar(255),
            `profileImg` varchar(255),
            `region` varchar(10)
	    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );


    $table_name = $wpdb->prefix . 'ffa_stats_match_player';

    $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
		    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `playerId` int(11),
            `matchId` int(11),
            `weekId` int(11),
            `points` int(10),
            `position` int(10)
	    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );


    $table_name = $wpdb->prefix . 'ffa_stats_match_week';

    $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
		    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `beginAt` varchar(255),
            `position` int(10),
            `state` varchar(255),
            `week` varchar(10)
	    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

add_shortcode('ffa-display', 'ffa_display_plugin');
add_action('wp_enqueue_scripts', 'add_my_stylesheet');
add_action( 'admin_menu', 'add_admin_pages');

custom_table_type();
