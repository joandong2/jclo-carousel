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

class JO__SettingsPage {
    public static function settings_page() {
        echo 'hello world po!!!!!!';
    }
}

if ( class_exists( 'JO__SettingsPage' ) ) {
    $jo__SettingsPage = new JO__SettingsPage();
}
