<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

add_action('init', 'add_moptin_post_type');
function add_moptin_post_type() {
    register_post_type('moptin', array(
        'label' => 'Moptin',
        'public' => FALSE,
        'show_ui' => true,
        'show_in_menu' => true,
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'hierarchical' => true,
        'rewrite' => fasle,
        'query_var' => false,
        'has_archive' => false,
        'supports' => array('title','thumbnail'),
        'taxonomies' => array(),
        'menu_icon'=>'dashicons-email-alt'
    ) );
}