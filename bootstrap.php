<?php

namespace WpCafe;

defined( 'ABSPATH' ) || exit;

use WpCafe\Autoloader;
use WpCafe\Utils\Wpc_Utilities;

/**
 * Autoload all classes
 */
require_once plugin_dir_path( __FILE__ ) . '/autoloader.php';

final class Bootstrap{
    
    private static $instance;
    private $has_pro;

    /**
     * Register action
     */
    private function __construct() {
        // load autoload method
        Autoloader::run();
    }

    public function init(){

        $this->has_pro = class_exists('Wpcafe_Pro');
        
        // activation and deactivation hook
        register_activation_hook( __FILE__, [$this, 'wpc_active'] );
        register_deactivation_hook( __FILE__, [$this, 'wpc_deactive'] );

        //handle buy-pro notice
        $this->handle_buy_pro_menu();

        //enqueue file
        \WpCafe\Core\Enqueue\Wpc_Enqueue::instance()->init();

        // fire in every plugin load action
        $this->wpc_init_plugin();  
        
    }

    /**
     * do stuff on active
     *
     * @return void
     */
    public function wpc_active() {
        $installed = get_option( 'wpc_cafe_installed' );

        if ( !$installed ) {
            update_option( 'wpc_cafe_installed', time() );
        }

        update_option( 'wpc_cafe_version', \Wpcafe::version() );
    }

    /**
     * do stuff on deactive
     *
     * @return void
     */
    public function wpc_deactive() {
        flush_rewrite_rules();
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Load all class
     *
     * @return void
     */
    public function wpc_init_plugin() {
        
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            add_action( 'admin_notices', [$this, 'wpc_admin_notice_woocommerce_not_active'] );
        }

        // call ajax submit
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            \WpCafe\Core\Action\Wpc_Ajax_Action::instance()->init();
        }
        

        //make admin menu open if any custom post type is selected
        add_action( 'parent_file', [$this, 'wpc_keep_cpt_menu_open'] );

        //register all custom post type
        \WpCafe\Core\Post_type\Cpt::instance()->init();

        // register elementor
        \WpCafe\Widgets\Manifest::instance()->init();

        // register widgets and shortcode
        $this->register_shortcodes();

        // register gutenberg blocks
        \WpCafe\Core\Modules\Guten_Block\Inc\Register_Blocks::instance()->init();
        
        if ( is_admin() ){
            \WpCafe\Core\Core::instance()->init();
        }
        
        \WpCafe\Core\Core::instance()->register_modules();
       
        // Divi Builder class added filter
        if(class_exists( 'ET_Builder_Plugin')){
            add_filter('et_builder_inner_content_class', [$this, 'wpc_divi_classes']);
        }
    }
   
    /**
     * Divi Builder class filter
     *
     * @return void
     */
    function wpc_divi_classes($classes){
        $classes[] = 'wpc-divi-parent-wrap';
        return $classes;
    }


    /**
     * Load on plugin
     *
     * @return void
     */
    public function wpc_admin_notice_woocommerce_not_active() {

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        if ( file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
            $btn['label'] = esc_html__( 'Activate WooCommerce', 'wpcafe' );
            $btn['url']   = wp_nonce_url( 'plugins.php?action=activate&plugin=woocommerce/woocommerce.php&plugin_status=all&paged=1', 'activate-plugin_woocommerce/woocommerce.php' );
        } else {
            $btn['label'] = esc_html__( 'Install WooCommerce', 'wpcafe' );
            $btn['url']   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );
        }

        Wpc_Utilities::push(
            [
                'id'          => 'unsupported-woocommerce-version',
                'type'        => 'error',
                'dismissible' => true,
                'btn'         => $btn,
                'message'     => sprintf( esc_html__( 'WpCafe requires WooCommerce , which is currently NOT RUNNING.', 'wpcafe' ) ),
            ]
        );
    }

    

    public function admin_notice_wpcafe_pro_not_active() {
        $btn['label'] = esc_html__( 'Buy Pro', 'wpcafe' );
        $btn['url']   = 'https://themewinter.com/wp-cafe/';

        Wpc_Utilities::push(
            [
                'id'          => 'wpcafe-pro-notice',
                'type'        => 'error',
                'dismissible' => true,
                'btn'         => $btn,
                'message'     => sprintf( esc_html__( 'Unlock more features with the pro version', 'wpcafe' ) ),
            ]
        );
    }

    /**
     * Register shortcode function
     *
     * @return void
     */
    public function register_shortcodes() {
        \WpCafe\Core\Shortcodes\Hook::instance()->init();
    }

    /**
     * Keep open menu function
     *
     */
    public function wpc_keep_cpt_menu_open( $parent_file ) {
        global $current_screen;
        $post_type = $current_screen->post_type;

        if ( $post_type == 'wpc_reservation' ) {
            wp_enqueue_script( 'wpc-active-custom-post-type', \Wpcafe::assets_url() . 'js/wpc-admin-menu.js', ['jquery'], \Wpcafe::version(), false );
            $parent_file = 'cafe_menu';
        }

        return $parent_file;
    }

    

    /**
     * Show buy-pro menu if pro plugin not active
     *
     * @return void
     */
    public function handle_buy_pro_menu(){

        /**
        * Show banner (codename: jhanda)
        */

        $filter_string = 'wp-cafe,wpcafe-free-only';
        
        if( $this->has_pro ) {
            
            $filter_string .= ',wpcafe-pro';
            $filter_string = str_replace(',wpcafe-free-only', '', $filter_string);

        }
        
        \Wpmet\Libs\Banner::instance('wp-cafe')
        // ->is_test(true)
        ->set_filter( ltrim($filter_string, ',') )
        ->set_api_url('https://demo.themewinter.com/public/jhanda')
        ->set_plugin_screens('toplevel_page_cafe_menu')
        ->set_plugin_screens('wpcafe_page_wpcafe_get_help')
        ->set_plugin_screens('edit-wpc_reservation')
        ->call();

        //show get-help and upgrade-to-premium menu
        $this->handle_get_help_and_upgrade_menu();
    }
    
    /**
     * Show menu for get-help
     * Show menu for upgrade-te-premium if pro version not active
     *
     * @return void
     */
    public function handle_get_help_and_upgrade_menu(){

        /**
         * Show go Premium menu
         */
        \Wpmet\Libs\Pro_Awareness::instance('wpcafe')
        ->set_parent_menu_slug('cafe_menu')
        ->set_plugin_file('wp-cafe/wpcafe.php')
        ->set_pro_link( $this->has_pro ? "" : 'https://themewinter.com/wp-cafe/' )
        ->set_default_grid_thumbnail( \Wpcafe::plugin_url() . '/utils/pro-awareness/assets/support.png' )
        ->set_default_grid_link('https://themewinter.com/support/')
        ->set_default_grid_desc(esc_html__('Our experienced support team is ready to resolve your issues any time.', 'wpcafe'))
        ->set_page_grid([
            'url' => 'https://www.facebook.com/groups/themewinter',
            'title' => esc_html__('Join the Community', 'wpcafe'),
            'thumbnail' => \Wpcafe::plugin_url() . '/utils/pro-awareness/assets/community.png',
            'description' => esc_html__('Join our Facebook group to get 20% discount coupon on premium products. Follow us to get more exciting offers.', 'wpcafe'),
        ])
        ->set_page_grid([
            'url' => 'https://www.youtube.com/watch?v=onxXm98D-Uk&list=PLW54c-mt4ObB2k9t8A5ALlwKGjeWxB8zQ',
            'title' => esc_html__('Video Tutorials', 'wpcafe'),
            'thumbnail' => \Wpcafe::plugin_url() . '/utils/pro-awareness/assets/video_tutorial.png',
            'description' => esc_html__('Learn the step by step process for developing your site easily from video tutorials.', 'wpcafe'),
        ])
        ->set_page_grid([
            'url' => 'https://themewinter.com/wpcafe-roadmaps/#ideas',
            'title' => esc_html__('Feature Request', 'wpcafe'),
            'thumbnail' => \Wpcafe::plugin_url() . '/utils/pro-awareness/assets/feature_request.png',
            'description' => esc_html__('Have any special feature in mind? Let us know through the feature request.', 'wpcafe'),
        ])
        ->set_page_grid([
            'url' => 'https://support.themewinter.com/docs/plugins/wp-cafe/',
            'title' => esc_html__('Documentation', 'wpcafe'),
            'thumbnail' => \Wpcafe::plugin_url() . '/utils/pro-awareness/assets/documentation.png',
            'description' => esc_html__('Detailed documentation to help you understand the functionality of each feature.', 'wpcafe'),
        ])
        ->set_plugin_row_meta('Documentation','https://support.themewinter.com/docs/plugins/docs-category/wp-cafe/', ['target'=>'_blank'])
        ->set_plugin_row_meta('Facebook Community','https://www.facebook.com/groups/themewinter', ['target'=>'_blank'])
        ->set_plugin_action_link('Settings', admin_url() . 'admin.php?page=cafe_menu')
        ->set_plugin_action_link( ( $this->has_pro ? '' : 'Go Premium'),'https://themewinter.com/wp-cafe/', ['target'=>'_blank', 'style' => 'color: #FCB214; font-weight: bold;'])
        ->set_plugin_row_meta('Rate the plugin ★★★★★', 'https://wordpress.org/support/plugin/wp-cafe/reviews/#new-post', ['target' => '_blank'])
        ->call();
    }

}