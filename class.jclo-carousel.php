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
        add_action( 'add_meta_boxes', array( $this, 'jclo_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'jclo_meta_boxes_save' ) );

    }

    function enqueue_style() {
		wp_enqueue_style( 'bootstrap', plugins_url( '/_assets/css/bootstrap.min.css', __FILE__ ) );
		wp_enqueue_style( 'custom', plugins_url( '/_assets/css/custom.css', __FILE__ ) );
	}

	function enqueue_script() {
		wp_enqueue_script( 'bootstrap', plugins_url( '/_assets/js/bootstrap.min.js', __FILE__ ) , array( 'jquery' ), JCLO_VERSION, '' );
		wp_enqueue_script( 'custom', plugins_url( '/_assets/js/custom.js', __FILE__ ), array( 'jquery' ), JCLO_VERSION, '' );
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

    function jclo_meta_boxes() {
        add_meta_box( 
            'jclo-images', 
            'Carousel', 
            array ( $this, 'jclo_meta_box_function' ), 
            'jclo_carousel', 
            'normal', 
            'high' 
        );
    }

    function jclo_meta_box_function($post) {
        $jclo_images = get_post_meta($post->ID, 'jclo-images', true);
        $jclo_slides_to_show = get_post_meta($post->ID, 'jclo-slides-to-show', true);
        $jclo_slides_dots = get_post_meta($post->ID, 'jclo-slides-dots', true);
        $jclo_slides_autoplay = get_post_meta($post->ID, 'jclo-slides-autoplay', true);
        echo self::jclo_fields( 'jclo-images', $jclo_images ); 

        // other fields
        ?>
        <div class="form-group">
            <label for="jclo-slides-to-show">No of slides</label>
            <input class="form-control" type="number" value="<?php echo $jclo_slides_to_show ? $jclo_slides_to_show : '' ?>" name="jclo-slides-to-show" id="jclo-slides-to-show"/>
        </div>
        
        <?php
    }

    // image fields
    public static function jclo_fields($name, $value = '') {
        $image_str = '';
        $image_size = 'full';
        $value = explode(',', $value);
    
        if (!empty($value)) {
            foreach ($value as $values) {
                if ($image_attributes = wp_get_attachment_image_src($values, $image_size)) {
                    $image_str .= '<li data-attechment-id=' . $values . '><a href="' . $image_attributes[0] . '" target="_blank"><img src="' . $image_attributes[0] . '" /></a><i class="dashicons dashicons-no delete-img"></i></li>';
                }
            }
        }
    
        return '<div class="jclo-images"><ul>' . $image_str . '</ul><a href="#" class="wc_multi_upload_image_button button">Add Media</a><input type="hidden" class="attechments-ids ' . $name . '" name="' . $name . '" id="' . $name . '" value="' . esc_attr(implode(',', $value)) . '" /><a href="#" class="wc_multi_remove_image_button button" style="display:inline-block;">Remove media</a></div>';
    }
    
    // Save Meta Box values.
    function jclo_meta_boxes_save( $post_id ) {
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return; 
        }

        if( !current_user_can( 'edit_post' ) ){
            return; 
        }
        
        if( isset( $_POST['jclo-images'] ) || isset( $_POST['jclo-slides-to-show'] )){
            update_post_meta( $post_id, 'jclo-images', $_POST['jclo-images'] );
            update_post_meta( $post_id, 'jclo-slides-to-show', $_POST['jclo-slides-to-show'] );
        }
    }
}

if ( class_exists( 'Jclo_Carousel' ) ) {
    $jclo__SimpleCarousel = new Jclo_Carousel();
	$jclo__SimpleCarousel->jclo_init();
}

