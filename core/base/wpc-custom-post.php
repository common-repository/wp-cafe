<?php

namespace WpCafe\Core\Base;

defined( 'ABSPATH' ) || exit;

/**
 * custom Post type class
 */
class Wpc_Custom_Post {
    private $xs_posts;
    /**
     * Call action
     *
     * @param [type] $textdomain
     */
    public function __construct() {
        $this->xs_posts   = [];
        add_action( 'init', [ $this, 'register_custom_post' ] );
    }

    /**
     * Create custom post
     */
    public function xs_init( $type, $singular_label, $plural_label, $settings = [] ) {
        $default_settings = [
            'labels'              => [
                'name'               => $plural_label,
                'singular_name'      => $singular_label,
                'add_new_item'       => sprintf(esc_html__( 'Add New %s', 'wpcafe' ), $singular_label),
                'edit_item'          => sprintf(esc_html__( 'Edit %s', 'wpcafe' ), $singular_label),
                'new_item'           => sprintf(esc_html__( 'New %s', 'wpcafe' ), $singular_label),
                'view_item'          => sprintf(esc_html__( 'View %s', 'wpcafe' ), $singular_label),
                'search_items'       => sprintf(esc_html__( 'Search %s', 'wpcafe' ), $plural_label),
                'not_found'          => sprintf(esc_html__( 'No %s found', 'wpcafe' ), $plural_label),
                'not_found_in_trash' => sprintf(esc_html__( 'No %s found in trash', 'wpcafe' ), $plural_label),
                'parent_item_colon'  => sprintf(esc_html__( 'Parent %s', 'wpcafe' ), $singular_label),
                'menu_name'          => $plural_label,
            ],
            'supports'            => false,
            'hierarchical'        => true,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => 'cafe_menu',
            'menu_icon'           => 'dashicons-text-page',
            'menu_position'       => 1,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'publicly_queryable'  => true,
            'query_var'           => true,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'show_in_rest'        => false,
            'rewrite'             => [ 'slug' => $plural_label, 'with_front' => false ],
        ];
        $this->xs_posts[$type] = array_merge( $default_settings, $settings );
    }

    /**
     * Register custom post
     *
     * @return void
     */
    public function register_custom_post() {

        foreach ( $this->xs_posts as $key => $value ) {
            register_post_type( $key, $value );
            flush_rewrite_rules();
        }

    }

}

class Wpc_Taxonomies {
    protected $textdomain;
    protected $taxonomies;
    /**
     * Call action
     *
     * @param [type] $textdomain
     */
    public function __construct() {
        $this->taxonomies = [];
        add_action( 'init', [ $this, 'register_taxonomy' ] );
    }

    /**
     * Create taxonomies
     *
     * @param [type] $type
     * @param [type] $singular_label
     * @param [type] $plural_label
     * @param [type] $post_types
     * @param array $settings
     * @return void
     */
    public function xs_init( $type, $singular_label, $plural_label, $slug, $post_types, $settings = [] ) {
        $default_settings = [
            'labels'            => [
                'name'                  => esc_html( $plural_label ),
                'singular_name'         => esc_html( $singular_label ),
                'add_new_item'          => sprintf(esc_html__( 'New %s name', 'wpcafe' ), $singular_label),
                'new_item_name'         => sprintf(esc_html__( 'Add New %s', 'wpcafe' ), $singular_label),
                'edit_item'             => sprintf(esc_html__( 'Edit %s', 'wpcafe' ), $singular_label),
                'update_item'           => sprintf(esc_html__( 'Update %s', 'wpcafe' ), $singular_label),
                'add_or_remove_items'   => sprintf(esc_html__( 'Add or remove %s', 'wpcafe' ), strtolower( $plural_label )),
                'search_items'          => sprintf(esc_html__( 'Search %s', 'wpcafe' ), $plural_label),
                'popular_items'         => sprintf(esc_html__( 'Popular %s', 'wpcafe' ), $plural_label),
                'all_items'             => sprintf(esc_html__( 'All %s', 'wpcafe' ), $plural_label),
                'parent_item'           => sprintf(esc_html__( 'Parent %s', 'wpcafe' ), $singular_label),
                'choose_from_most_used' => sprintf(esc_html__( 'Choose from the most used %s', 'wpcafe' ), strtolower( $plural_label )),
                'parent_item_colon'     => sprintf(esc_html__( 'Parent %s', 'wpcafe' ), $singular_label),
                'menu_name'             => esc_html( $singular_label),
            ],

            'public'            => true,
            'show_in_nav_menus' => true,
            'can_export'        => true,
            'show_admin_column' => true,
            'hierarchical'      => true,
            'query_var'         => true,
            'show_tagcloud'     => true,
            'show_ui'           => true,
            'rewrite'           => [
                'slug' => sanitize_title_with_dashes( $slug ),
            ],
        ];
        $this->taxonomies[$type]['post_types'] = $post_types;
        $this->taxonomies[$type]['args']       = array_merge( $default_settings, $settings );
    }

    /**
     * register taxonomies
     *
     * @return void
     */
    public function register_taxonomy() {
        foreach ( $this->taxonomies as $key => $value ) {
            register_taxonomy( $key, $value['post_types'], $value['args'] );
        }
    }
}
