<?php 
/**
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://joblenda.me/
 * @since             1.0.0
 * @package           Slick Carousel
 *
*/

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

// delete custom post type data
// $carousel = get_posts( array( 'post_type' => 'jo_carousel', 'numberposts' => -1 ) );

// foreach( $carousel as $car ) {
//     wp_delete_post( $car->ID, true );
// }

global $wpdb;
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'jclo_carousel'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );
$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)" );