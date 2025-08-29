<?php

/**
 * Plugin Name: Woles Shortcodes
 * Description: Custom shortcodes to enhance Wordpress-based website development experience
 * Version: 1.0
 * Author: Adnan Zaki (Wolestech DevTeam)
 * Author URI: https://github.com/adnzaki
 */


function woles_base_path() {
    return str_replace('http://', '', site_url()) . '/';
}  


function woles_base_url() {
    return site_url() . '/';
}

function woles_link($atts) {
    $arr = shortcode_atts([
        'path' => '',
        'use_protocol' => 'yes'
    ], $atts);
    
    $baseUrl = $arr['use_protocol'] === 'no' ? woles_base_path() : woles_base_url();
    
    return $baseUrl . $arr['path'];
}

add_shortcode('woles_base_path', 'woles_base_path');
add_shortcode('woles_base_url', 'woles_base_url');
add_shortcode('woles_link', 'woles_link');