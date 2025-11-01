<?php
/*
Plugin Name: React Menu Shortcode
Description: نمایش منو با React از طریق شورت‌کد [react_menu]
Version: 1.0
Author: mahyar
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class RMS_Plugin {
    public function __construct() {
        add_shortcode( 'react_menu', [ $this, 'render_shortcode' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );
    }

    public function register_rest_routes() {
        register_rest_route('rms/v1', '/menu', [
            'methods'  => 'GET',
            'callback' => [ $this, 'get_menu_items' ],
            'args'     => [
                'slug' => ['required' => false, 'type' => 'string'],
            ],
            'permission_callback' => '__return_true',
        ]);
    }

    public function get_menu_items( WP_REST_Request $request ) {
        $slug = $request->get_param('slug') ? sanitize_text_field($request->get_param('slug')) : '';
        if (!$slug) return [];

        $menu_obj = null;
        $menus = wp_get_nav_menus();

        foreach ($menus as $menu) {
            if ( strtolower($menu->name) === strtolower($slug) ) {
                $menu_obj = $menu;
                break;
            }
        }

        if (!$menu_obj) return [];

        $menu_items = wp_get_nav_menu_items($menu_obj->term_id);

        return array_map(function($item){
            return [
                'ID'         => $item->ID,
                'title'      => $item->title,
                'url'        => $item->url,
                'menu_order' => $item->menu_order,
                'parent'     => $item->menu_item_parent,
            ];
        }, $menu_items);
    }

    public function render_shortcode( $atts ) {
        $atts = shortcode_atts([
            'slug' => '',
        ], $atts);

        wp_enqueue_script('rms-app');
        wp_enqueue_style('rms-app');

        $uid = uniqid('rms-menu-');
        return "<div id='$uid' class='rms-menu' data-menu='" . esc_attr(json_encode($atts)) . "'></div>";
    }

    public function enqueue_scripts() {
        $js_url = plugins_url('build/app.js', __FILE__);
        $css_url = plugins_url('build/style.css', __FILE__);

        wp_register_script(
            'rms-app',
            $js_url,
            ['wp-element', 'wp-api-fetch'],
            filemtime(plugin_dir_path(__FILE__) . 'build/app.js'),
            true
        );

        if (file_exists(plugin_dir_path(__FILE__) . 'build/style.css')) {
            wp_register_style(
                'rms-app',
                $css_url,
                [],
                filemtime(plugin_dir_path(__FILE__) . 'build/style.css')
            );
        }

        wp_localize_script('rms-app', 'RMS_DATA', [
            'rest_url' => esc_url_raw(rest_url('rms/v1/menu')),
        ]);
    }
}

new RMS_Plugin();
