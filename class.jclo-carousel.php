<?php

class Jclo_Carousel {
    
    public static function plugin_activation() {
        flush_rewrite_rules();
    }
    
    public static function plugin_deactivation() {
        global $wpdb;
        $wpdb->query( "DELETE FROM wp_posts WHERE post_type = ''" );
        $wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );
        $wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)" );

        flush_rewrite_rules();
    }
    
    public function jclo_init() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style') );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script') );
        add_action( 'init', array( $this, 'jclo_custom_post_type' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'add_meta_boxes', array( $this, 'jclo_uploader_meta_box' ) );
        add_action( 'save_post', array( $this, 'jclo_meta_box_save' ) );

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

    function jclo_uploader_meta_box() {
        add_meta_box( 
            'jclo-images', 
            'Images for Carousel', 
            array ( $this, 'jclo_uploader_meta_box_function' ), 
            'jclo_carousel', 
            'normal', 
            'high' 
        );
    }

    function jclo_uploader_meta_box_function($post) {
        $banner_img = get_post_meta($post->ID, 'jclo-images', true);
        ?>
            <style type="text/css">
                .multi-upload-medias ul li .delete-img { position: absolute; right: 3px; top: 2px; background: aliceblue; border-radius: 50%; cursor: pointer; font-size: 14px; line-height: 20px; color: red; }
                .multi-upload-medias ul li { width: 120px; display: inline-block; vertical-align: middle; margin: 5px; position: relative; }
                .multi-upload-medias ul li img { width: 100%; }
            </style>

            <table cellspacing="10" cellpadding="10">
                <tr>
                    <td>Banner Image</td>
                    <td>
                        <?php echo self::multi_media_uploader_field( 'jclo-images', $banner_img ); ?>
                    </td>
                </tr>
            </table>

            <script type="text/javascript">
                jQuery(function($) {

                    $('body').on('click', '.wc_multi_upload_image_button', function(e) {
                        e.preventDefault();

                        var button = $(this),
                        custom_uploader = wp.media({
                            title: 'Insert image',
                            button: { text: 'Use this image' },
                            multiple: true 
                        }).on('select', function() {
                            var attech_ids = '';
                            attachments
                            var attachments = custom_uploader.state().get('selection'),
                            attachment_ids = new Array(),
                            i = 0;
                            attachments.each(function(attachment) {
                                attachment_ids[i] = attachment['id'];
                                attech_ids += ',' + attachment['id'];
                                if (attachment.attributes.type == 'image') {
                                    $(button).siblings('ul').append('<li data-attechment-id="' + attachment['id'] + '"><a href="' + attachment.attributes.url + '" target="_blank"><img class="true_pre_image" src="' + attachment.attributes.url + '" /></a><i class=" dashicons dashicons-no delete-img"></i></li>');
                                } else {
                                    $(button).siblings('ul').append('<li data-attechment-id="' + attachment['id'] + '"><a href="' + attachment.attributes.url + '" target="_blank"><img class="true_pre_image" src="' + attachment.attributes.icon + '" /></a><i class=" dashicons dashicons-no delete-img"></i></li>');
                                }
                                i++;
                            });

                            var ids = $(button).siblings('.attechments-ids').attr('value');
                            if (ids) {
                                var ids = ids + attech_ids;
                                $(button).siblings('.attechments-ids').attr('value', ids);
                            } else {
                                $(button).siblings('.attechments-ids').attr('value', attachment_ids);
                            }
                            $(button).siblings('.wc_multi_remove_image_button').show();
                        })
                        .open();
                    });

                    $('body').on('click', '.wc_multi_remove_image_button', function() {
                        $(this).hide().prev().val('').prev().addClass('button').html('Add Media');
                        $(this).parent().find('ul').empty();
                        return false;
                    });

                });

                jQuery(document).ready(function() {
                    jQuery(document).on('click', '.multi-upload-medias ul li i.delete-img', function() {
                        var ids = [];
                        var this_c = jQuery(this);
                        jQuery(this).parent().remove();
                        jQuery('.multi-upload-medias ul li').each(function() {
                            ids.push(jQuery(this).attr('data-attechment-id'));
                        });
                        jQuery('.multi-upload-medias').find('input[type="hidden"]').attr('value', ids);
                    });
                })
            </script>
        <?php
    }

    public static function multi_media_uploader_field($name, $value = '') {
        $image = '">Add Media';
        $image_str = '';
        $image_size = 'full';
        $display = 'none';
        $value = explode(',', $value);
    
        if (!empty($value)) {
            foreach ($value as $values) {
                if ($image_attributes = wp_get_attachment_image_src($values, $image_size)) {
                    $image_str .= '<li data-attechment-id=' . $values . '><a href="' . $image_attributes[0] . '" target="_blank"><img src="' . $image_attributes[0] . '" /></a><i class="dashicons dashicons-no delete-img"></i></li>';
                }
            }

        }
    
        if($image_str){
            $display = 'inline-block';
        }
    
        return '<div class="multi-upload-medias"><ul>' . $image_str . '</ul><a href="#" class="wc_multi_upload_image_button button' . $image . '</a><input type="hidden" class="attechments-ids ' . $name . '" name="' . $name . '" id="' . $name . '" value="' . esc_attr(implode(',', $value)) . '" /><a href="#" class="wc_multi_remove_image_button button" style="display:inline-block;display:' . $display . '">Remove media</a></div>';
    }
    
    // Save Meta Box values.
    function jclo_meta_box_save( $post_id ) {
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return; 
        }

        if( !current_user_can( 'edit_post' ) ){
            return; 
        }
        
        if( isset( $_POST['jclo-images'] ) ){
            update_post_meta( $post_id, 'jclo-images', $_POST['jclo-images'] );
        }
    }
}

if ( class_exists( 'Jclo_Carousel' ) ) {
    $jclo__SimpleCarousel = new Jclo_Carousel();
	$jclo__SimpleCarousel->jclo_init();
}

