<?php

/*

Plugin Name: RCA Shop Core

Plugin URI: https://online.chess-teacher.com

Description: Custom plugin for RCA shop online.chess-teacher.com

Version: 1.0.0

Author URI: https://chess-teacher.com

*/





if (!defined('ABSPATH')) exit; // Exit if accessed directly



// hide update notifications

function remove_core_updates()

{

    global $wp_version;

    return (object)array('last_checked' => time(), 'version_checked' => $wp_version,);

}



add_filter('pre_site_transient_update_core', 'remove_core_updates'); //hide updates for WordPress itself

add_filter('pre_site_transient_update_plugins', 'remove_core_updates'); //hide updates for all plugins

add_filter('pre_site_transient_update_themes', 'remove_core_updates'); //hide updates for all themes



add_action('after_setup_theme', 'crb_load');

function crb_load()

{

    require_once(plugin_dir_path(__FILE__) . '/carbon-fields/vendor/autoload.php');

    \Carbon_Fields\Carbon_Fields::boot();

}





//Loading CSS and Js

function rca_shop_enqueue_scripts()

{



    // CSS

    wp_enqueue_style('rca-add-to-cart-style', plugins_url('/assets/css/ajax-add-to-cart.css', __FILE__));

    wp_enqueue_style('rca-custom-style', plugins_url('/assets/css/style.css', __FILE__));





    // JS

    wp_enqueue_script('rca-cbreplay-jquery', 'https://pgn.chessbase.com/jquery-3.0.0.min.js');

    wp_enqueue_script('rca-cbreplay-script', plugins_url('/assets/js/CBReplay.js', __FILE__), array('jquery'), '1.0.0', true);

    wp_enqueue_script('rca-ajax-add-to-cart-script', plugins_url('/assets/js/rca-ajax-add-to-cart.js', __FILE__), array('jquery'), '1.0.0', true);



}



add_action('wp_enqueue_scripts', 'rca_shop_enqueue_scripts');





function rca_shop_admin_enqueue_scripts()

{

    // CSS

    wp_enqueue_style('rca-admin-style', plugins_url('/assets/css/admin-style.css', __FILE__));



    wp_enqueue_script('rca-shop-tinymce', 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/tinymce.min.js');

    wp_enqueue_script('rca-shop-tinymce-script', plugins_url('/assets/js/tiny-mce-button.js', __FILE__));



}



add_action('admin_enqueue_scripts', 'rca_shop_admin_enqueue_scripts');





// Change course base slug "/courses" to "/course"

add_filter('tutor_courses_base_slug', function ($course_post_type) {

    $course_post_type = 'course';

    return $course_post_type;

});





//Add x-hours video text on single course benefits section

add_filter('tutor_course/single/benefits', 'rca_add_new_benefit_course_length', 2, 10);

function rca_add_new_benefit_course_length($array, $course_id) {

    $course_duration = Edumall_Tutor::instance()->get_course_duration_context();

    $disable_course_duration = get_tutor_option('disable_course_duration');

    if (!empty($course_duration) && !$disable_course_duration) {

        array_unshift($array, $course_duration . ' on-demand video');

        return $array;

    }

    else {

        return $array;

    }

}





// RCA helpers functions class

include(plugin_dir_path(__FILE__) . 'inc/rca-shop-helpers.php');



// Tinymce add button

include(plugin_dir_path(__FILE__) . 'inc/tinymce-button.php');


// Tinymce add to cart button
include(plugin_dir_path(__FILE__) . 'inc/tinymce-add-to-cart-button.php');


// Allow iFrame tag on lesson editor

include(plugin_dir_path(__FILE__) . 'inc/allow-iframe-tag.php');



// Woocommerce

include(plugin_dir_path(__FILE__) . 'inc/woo-custom.php');



// Ajax add to cart

include(plugin_dir_path(__FILE__) . 'inc/rca-ajax-add-to-cart.php');



// RCA Shop Core menu custom fields

include(plugin_dir_path(__FILE__) . 'inc/rca-shop-core-menu.php');