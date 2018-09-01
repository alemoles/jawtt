<?php
add_action('init', 'travel_propietario_page');

function travel_propietario_page() {
    /**
     * Adicionar post propietario
     */
    $labelspropietario = array(
        'name' => _x('Propietarios', 'post type general name'),
        'singular_name' => _x('propietario', 'post type singular name'),
        'add_new' => _x('Añadir  propietario', 'Add owner'),
        'add_new_item' => _x('Añadir nuevo propietario', 'Add new owner'),
        'edit_item' => __('Editar propietario'),
        'new_item' => __('Nuevo propietario'),
        'view_item' => __('Ver propietario'),
        'search_items' => __('Buscar propietario'),
        'not_found' => __('No se han encontrado ningun propietario'),
        'not_found_in_trash' => __('No se han encontrado propietario en la papelera'),
        'menu_icon' => 'dashicons-admin-users',
        'parent_item_colon' => '',
    );

    $argspropietario = array('labels' => $labelspropietario,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('author'),
    );

    register_post_type('propietario', $argspropietario);


}