<?php
/*
Plugin Name: ChatGPT Interface
Plugin URI: https://your-website.com
Description: A ChatGPT-like interface for WordPress
Version: 1.0
Author: Your Name
Author URI: https://your-website.com
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Add menu item to WordPress admin
function chatgpt_interface_menu() {
    add_menu_page(
        'ChatGPT Interface',
        'ChatGPT Interface',
        'manage_options',
        'chatgpt-interface',
        'chatgpt_interface_page',
        'dashicons-format-chat'
    );
}
add_action('admin_menu', 'chatgpt_interface_menu');

// Create the plugin page
function chatgpt_interface_page() {
    ?>
    <div class="wrap">
        <h1>ChatGPT Interface</h1>
        <div id="chatgpt-interface-root"></div>
    </div>
    <?php
}

// Enqueue scripts and styles with proper dependencies
function chatgpt_interface_scripts($hook) {
    // Only load on plugin page or when shortcode is present
    global $post;
    if ($hook !== 'toplevel_page_chatgpt-interface' && 
        (!is_a($post, 'WP_Post') || !has_shortcode($post->post_content, 'chatgpt_interface'))) {
        return;
    }

    // Deregister conflicting scripts
    wp_deregister_script('wp-interface');
    
    wp_enqueue_script(
        'chatgpt-interface-js',
        plugin_dir_url(__FILE__) . 'dist/assets/index.js',
        array('wp-element'), // Add wp-element as dependency
        '1.0.0',
        true
    );
    
    wp_enqueue_style(
        'chatgpt-interface-css',
        plugin_dir_url(__FILE__) . 'dist/assets/index.css',
        array(),
        '1.0.0'
    );
}
add_action('admin_enqueue_scripts', 'chatgpt_interface_scripts');
add_action('wp_enqueue_scripts', 'chatgpt_interface_scripts');

// Add shortcode to use in posts/pages
function chatgpt_interface_shortcode() {
    // Ensure scripts and styles are loaded
    chatgpt_interface_scripts('shortcode');
    
    // Add GPT Engineer script
    wp_enqueue_script(
        'gpt-engineer',
        'https://cdn.gpteng.co/gptengineer.js',
        array(),
        '1.0.0',
        true
    );
    
    ob_start();
    ?>
    <div id="chatgpt-interface-root"></div>
    <?php
    return ob_get_clean();
}
add_shortcode('chatgpt_interface', 'chatgpt_interface_shortcode');