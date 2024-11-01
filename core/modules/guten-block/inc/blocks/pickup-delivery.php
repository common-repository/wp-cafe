<?php
namespace WpCafe\Core\Modules\Guten_Block\Inc\Blocks;

defined( "ABSPATH" ) || exit;

class Pickup_Delivery {
    
    use \WpCafe\Traits\Wpc_Singleton;

    function init() {
        add_action('init', [$this, 'register_pickup_delivery_block']);
    }

    function register_pickup_delivery_block(){
        register_block_type(
            'wpc/pickup-delivery', array(
                'editor_script' => 'wpc-block-js',
                'render_callback'   => [$this, 'wpc_pickup_delivery_callback'],
                'api_version'       => 1,
                'attributes'        => array()
            )
        );

        $this->register_pickup_delivery_block_scripts();
    }

    function register_pickup_delivery_block_scripts(){
        wp_register_script('frontend-js-block-pickup', \Wpcafe::assets_url() . 'js/wpc-block-pickup.js', [ 'jquery', 'wpc-pro-widgets-modal-script', 'jquery-timepicker', 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n' ], \Wpcafe::version());
    }

    // pickup delivery checkout page block callback
    function wpc_pickup_delivery_callback() {
        //check if the pickup/delivery module is active
        $allowed_options = [
            'Both',
            'Delivery',
            'Pickup'
        ];

        $settings  = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();

        if( ! in_array($settings['wpc_pro_allow_order_for'], $allowed_options)){
            return;
        }

        if(is_checkout() ){ 
            wp_enqueue_script( 'frontend-js-block-pickup' );
        } 

        return do_shortcode( '[wpc_pickup_delivery_checkout]' );
    }

}
