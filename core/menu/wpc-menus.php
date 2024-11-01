<?php
namespace WpCafe\Core\Menu;

defined( 'ABSPATH' ) || exit;

/**
 * Menu handle class
 */
class Wpc_Menus {

    use \WpCafe\Traits\Wpc_Singleton;

    public $settings;
    private $pages;
    private $sub_pages;

    /**
     * Call all action
     */
    public function init() {

        // create cafe  menu
        $this->pages = [
            [
                "page_title"  => esc_html( 'Dashboard', 'wpcafe' ),
                "menu_title"  => esc_html( 'WPCafe', 'wpcafe' ),
                "capability"  => 'manage_options',
                "menu_slug"   => 'cafe_menu',
                "cb_function" => [$this, 'dashboard_view'],
                "icon"        => \Wpcafe::assets_url() . 'images/brand_icon.svg',
                'position'    => 9,
            ],
        ];

        // create cafe sub menu
        $this->sub_pages = [
            [
                "parent_slug" => 'cafe_menu',
                "page_title"  => esc_html__( 'Settings', 'wpcafe' ),
                "menu_title"  => esc_html__( 'Settings', 'wpcafe' ),
                "capability"  => 'manage_options',
                "menu_slug"   => 'cafe_settings',
                "cb_function" => [$this, 'admin_settings_view'],
                'position'    => 1,
            ],
            [
                "parent_slug" => 'cafe_menu',
                "page_title"  => esc_html__( 'Add new bookings', 'wpcafe' ),
                "menu_title"  => esc_html__( 'Reservation List', 'wpcafe' ),
                "capability"  => 'manage_options',
                "menu_slug"   => 'edit.php?post_type=wpc_reservation',
                "cb_function" => false,
                'position'    => 2,
            ],
            [
                "parent_slug" => 'cafe_menu',
                "page_title"  => esc_html__( 'Shortcodes', 'wpcafe' ),
                "menu_title"  => esc_html__( 'Available Shortcodes', 'wpcafe' ),
                "capability"  => 'manage_options',
                "menu_slug"   => 'wpc_shortcode',
                "cb_function" => [$this, 'available_shortcode_view'],
                'position'    => 3,
            ],
            [
                "parent_slug" => 'cafe_menu',
                "page_title"  => esc_html__( 'Tools', 'wpcafe' ),
                "menu_title"  => esc_html__( 'Tools', 'wpcafe' ),
                "capability"  => 'manage_options',
                "menu_slug"   => 'wpc_tools',
                "cb_function" => [$this,'wpc_tools_view'],
                'position'    => 5,
            ],
       
        ];



        // add sub menus from pro
        if ( class_exists('Wpcafe_Pro') ) {

            $sub_menus = array(
                "parent_slug" => 'cafe_menu',
                "page_title"  => esc_html__( 'Product Addons', 'wpcafe' ),
                "menu_title"  => esc_html__( 'Product Addons', 'wpcafe' ),
                "capability"  => 'manage_options',
                "menu_slug"   => 'wpc_product_addons',
                "cb_function" => [$this,'addons_menu_pages'],
                'position'    => 2,
            );

            array_push( $this->sub_pages , $sub_menus ); 

        }

        $tools_settings      = get_option( 'wpcafe_tools_settings' );
        $enable_table_layout = ( isset( $tools_settings['enable_table_layout'] ) && $tools_settings['enable_table_layout'] == 'on' )  ? 'checked' : '';

        // add sub menus from pro
        if ( class_exists( 'Wpcafe_Pro' ) && $enable_table_layout ) {

            $sub_menus = array(
                "parent_slug" => 'cafe_menu',
                "page_title"  => esc_html__( 'Table Layout', 'wpcafe' ),
                "menu_title"  => esc_html__( 'Table Layout', 'wpcafe' ),
                "capability"  => 'manage_options',
                "menu_slug"   => 'wpc_table_layout',
                "cb_function" => [$this,'wpc_table_layout'],
                'position'    => 2,
            );

            array_push( $this->sub_pages , $sub_menus ); 

        }


        // register menu and sub menu pages
        new \WpCafe\Core\Base\Wpc_Menu_Build( 
            $this->pages, 
            esc_html__( 'Dashboard', 'wpcafe' ), 
            $this->sub_pages 
        );

    }

    /**
     * Product addons callback
     */
    public function addons_menu_pages(){
        return apply_filters("wpcafe_pro/menus/admin_submenu_pages", null );
    }

    /**
     * Table selection callback
     */
    public function wpc_table_layout(){
        return apply_filters("wpc_table_layout/menus/admin_submenu_pages", null );
    }

    /**
     * Show Dashboard Page
     */
    public function dashboard_view() {
        include_once \Wpcafe::core_dir() . "settings/part/dashboard.php";
    }

    /**
     * Show Settings Page
     */
    public function admin_settings_view() {
        \WpCafe\Core\Settings\Wpc_Key_Options::instance()->wpc_key_options();
    }

    /**
     * Show Shortcode Page
     */
    public function available_shortcode_view() {
        \WpCafe\Core\Settings\Wpc_Key_Options::instance()->shortcode_menu_view();
    }

    /**
     * Show Tools Page
     */
    public function wpc_tools_view() {
        \WpCafe\Core\Settings\Wpc_Key_Options::instance()->tools_menu_view();
    }

    /**
     * WpCafe app settings page
     */
    public function wpc_app_banner() {
        include_once \Wpcafe::core_dir() . "settings/part/app_banner.php";
    }

}
