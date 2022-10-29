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
 * @wordpress-plugin
 * Plugin Name:       Slick Carousel
 * Plugin URI:        https://joblenda.me/
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            John Oblenda
 * Author URI:        https://joblenda.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

if ( !defined( 'ABSPATH' ) ) {
    die;
}

class JO__SimpleCarousel
{
    function __construct() {
        add_action( 'init', array( $this, 'custom_post_type_12221986' ) );
    }

    function activate() {
        flush_rewrite_rules();
        $this->custom_post_type_12221986();
    }

    function deactivate() {
        flush_rewrite_rules();
    }

    public function custom_post_type_12221986() {
        /*
		* Creating a function to create our CPT
		*/
		$labels = array(
			'name'                => _x( 'Carousel', 'Post Type General Name'),
			'singular_name'       => _x( 'Carousel', 'Post Type Singular Name'),
			'menu_name'           => __( 'Carousel'),
			'parent_item_colon'   => __( 'Parent Carousel'),
			'all_items'           => __( 'All Carousel'),
			'view_item'           => __( 'View Carousel'),
			'add_new_item'        => __( 'Add New Carousel'),
			'add_new'             => __( 'Add New'),
			'edit_item'           => __( 'Edit'),
			'update_item'         => __( 'Update'),
			'search_items'        => __( 'Search'),
			'not_found'           => __( 'Not Found'),
			'not_found_in_trash'  => __( 'Not found in Trash'),
		);
		
		// Set other options for Custom Post Type
		
		$args = array(
			'label'               => __( 'jo_carousel'),
			'description'         => __( 'Slick Carousel'),
			'labels'              => $labels,
			// Features this CPT supports in Post Editor
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			// You can associate this CPT with a taxonomy or custom taxonomy. 
			'taxonomies'          => array( 'genres' ),
			/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/ 
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 25,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest' => true,

		);
 
		// Registering your Custom Post Type
		register_post_type( 'jo_carousel', $args );
    }

}

if ( class_exists( 'JO__SimpleCarousel' ) ) {
    $jo__SimpleCarousel = new JO__SimpleCarousel();
}

// activation
register_activation_hook( __FILE__, array( $jo__SimpleCarousel, 'activate' ) );
// deactivation
register_activation_hook( __FILE__, array( $jo__SimpleCarousel, 'deactivate' ) );