<?php

class Jclo_Carousel {
    
    public static function plugin_activation() {
        flush_rewrite_rules();
    }
    
    public static function plugin_deactivation() {
        flush_rewrite_rules();
    }
    
    public function jclo_init() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style') );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script') );
        add_action( 'init', array( $this, 'jclo_custom_post_type' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
    }

    function enqueue_style() {
		wp_enqueue_style( 'bootstrap', JCLO__PLUGIN_DIR . '/assets/css/bootstrap.min.css' );
		wp_enqueue_style( 'custom', JCLO__PLUGIN_DIR . '/assets/css/custom.css' );
	}

	function enqueue_script() {
		wp_enqueue_script( 'bootstrap', JCLO__PLUGIN_DIR . '/assets/js/bootstrap.min.js', array( 'jquery' ), JCLO_VERSION, '' );
		wp_enqueue_script( 'custom', JCLO__PLUGIN_DIR . '/assets/js/custom.css', array( 'jquery' ), JCLO_VERSION, '' );
	}
    
    public function jclo_custom_post_type() {
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
		
		$args = array(
			'label'               => __( 'jclo_carousel'),
			'description'         => __( 'Slick Carousel'),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			'taxonomies'          => array( 'genres' ),
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
		register_post_type( 'jclo_carousel', $args );
    }

    public function add_settings_page() {
		add_submenu_page(
			'edit.php?post_type=jclo_carousel',
			 __( 'Settings', 'jclo-carousel'),
			 __( 'Settings', 'jclo-carousel'),
			 'manage_options',
			 'jclo-settings',
			 array($this, 'settings_page') 
		 );
	}

    function settings_page() {
        require_once( JCLO__PLUGIN_DIR. '_views/settings-page.php' );
    }

}

if ( class_exists( 'Jclo_Carousel' ) ) {
    $jclo__SimpleCarousel = new Jclo_Carousel();
	$jclo__SimpleCarousel->jclo_init();
}

