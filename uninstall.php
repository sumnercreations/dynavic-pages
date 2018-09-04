<?php

/**
 * Trigger this file on Plugin uninstall
 *
 * @package  DynavicPages
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Clear Database stored data
$dynavicPosts = get_posts( array( 'post_type' => 'dynavic', 'numberposts' => -1 ) );

foreach( $dynavicPosts as $post ) {
	wp_delete_post( $post->ID, true );
}

// Access the database via SQL
global $wpdb;
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'dynavic'" );