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

class JO__Activate
{
    public static function activate() {
        flush_rewrite_rules();
    }
}