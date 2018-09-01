<?php

include 'functions-cpt-hostal.php';
include 'hostal-option.php';



	// start your child theme code here
	
	// custom function to disable the trim excerpt
	// add_filter('wp_trim_excerpt', 'custom_disable_trim_excerpt', 11, 2);
	// function custom_disable_trim_excerpt( $excerpt, $original ){
	// 	if( !empty($original) ){
	// 		return $original;
	// 	}
	// 	return $excerpt;
	// }
include_once 'function-cpt-propietario.php';
include_once 'function-post-taxonomy-propietario.php';

//require get_stylesheet_directory() . '/assets/css';
function modify_jquery_version() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', get_stylesheet_directory_uri() . '/assets/ui/js/jquery-2.1.3.min.js', array(), '2.1.3', true);
    }
}
add_action('init', 'modify_jquery_version');

function my_theme_enqueue_styles() {

    $parent_style = 'TravelTour'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );

    wp_enqueue_style('ui-style', get_stylesheet_directory_uri() . '/assets/ui/css/ui-style.css', array(), '1.0.0', 'all');


    wp_enqueue_script('ui-scripts', get_stylesheet_directory_uri() . '/assets/ui/js/ui-scripts.js', array(), '1.0.0', 'all');

    wp_localize_script('ui-scripts', 'ui', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ui_security_key'),
    ));
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');


add_action('wp_ajax_logo_load', 'logo_load');
add_action('wp_ajax_nopriv_logo_load', 'logo_load');

function logo_load(){
    $data = esc_sql($_POST);
    if (!wp_verify_nonce($data['nonce'], 'ui_security_key')) {
        wp_die('Security check');
    }

    echo '<a href="#">';
    echo '<img src="'.get_stylesheet_directory_uri().'/images/tp-logo.png" alt="Logo">';
    echo '</a>';
    die();
}

add_action( 'after_switch_theme', 'create_page_on_theme_activation' );

function create_page_on_theme_activation(){

    // Set the title, template, etc
    $new_page_title     = __('Alojamientos','text-domain'); // Page's title
    $new_page_content   = '';                           // Content goes here
    $new_page_template  = 'page-hostal.php';       // The template to use for the page
    $page_check = get_page_by_title($new_page_title);   // Check if the page already exists
    // Store the above data in an array
    $new_page = array(
        'post_type'     => 'page',
        'post_title'    => $new_page_title,
        'post_content'  => $new_page_content,
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_slug'     => 'hostal'
    );
    // If the page doesn't already exist, create it
    if(!isset($page_check->ID)){
        $new_page_id = wp_insert_post($new_page);
        if(!empty($new_page_template)){
            update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
        }
    }
}