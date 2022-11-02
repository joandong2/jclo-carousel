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
 * Text Domain:       jslo-carousel
 * Domain Path:       /languages
 */

 
if ( !defined( 'ABSPATH' ) ) {
    die;
}

define( 'JCLO_VERSION', '1.0.0' );
define( 'JCLO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( JCLO__PLUGIN_DIR . 'class.jclo-carousel.php' );
require_once( JCLO__PLUGIN_DIR . '_views/shortcodes.php' );

register_activation_hook( __FILE__, array( 'Jclo_Carousel', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Jclo_Carousel', 'plugin_deactivation' ) );