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

class Jclo_Carousel_Shortcode {
	
    public function __construct() {
        add_shortcode( 'jclo-carousel', array( $this, 'jclo_carousel_shortcode_func' ) );
	}
    
    function jclo_carousel_shortcode_func( $atts ) {
        $a = shortcode_atts( array(
            'id' => '#',
        ), $atts );

        $image_str = '';
        $post = get_post( $a['id'] );
        $jclo_images = get_post_meta($post->ID, 'jclo-images', true);
        $jclo_slides_to_show = get_post_meta($post->ID, 'jclo-slides-to-show', true);
        $jclo_slides_dots = get_post_meta($post->ID, 'jclo-slides-dots', true);
        $jclo_slides_autoplay = get_post_meta($post->ID, 'jclo-slides-autoplay', true);

        $images = explode(',', $jclo_images);
    
        if (!empty($images)) {
            $image_str .= '<div class="jclo-carousel-'. $post->ID .'">';
            foreach ($images as $image) {
                if ($image_attributes = wp_get_attachment_image_src($image, 'full')) {
                    $image_str .= '<div><img src="' . $image_attributes[0] . '" /></div>';
                }
            }
            $image_str .= '</div>';
        }

        ?>

        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#jclo-carousel-'+<?php echo $post->ID ?>).slick({
                    autoplay: <?php echo $jclo_slides_autoplay; ?>,
                    dots: <?php echo $jclo_slides_dots; ?>,
                    infinite: true,
                    speed: 300,
                    slidesToShow: <?php echo $jclo_slides_to_show; ?>,
                });
            });
        </script>

        <?php
        return $image_str;
    }
}

if ( class_exists( 'Jclo_Carousel_Shortcode' ) ) {
    $jclo__SimpleCarousel_Shortcode = new Jclo_Carousel_Shortcode();
}