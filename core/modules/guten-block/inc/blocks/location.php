<?php
namespace WpCafe\Core\Modules\Guten_Block\Inc\Blocks;

defined( "ABSPATH" ) || exit;

class Location {
    
    use \WpCafe\Traits\Wpc_Singleton;

    function init() {
        add_action('init', [$this, 'register_location_block']);
    }

    function register_location_block(){
        register_block_type(
            'wpc/location', array(
                'editor_script' => 'wpc-block-js',
                'render_callback'   => [$this, 'wpc_location_callback'],
                'api_version'       => 1,
                'attributes'        => array()
            )
        );

        $this->register_location_block_scripts();

    }

    function register_location_block_scripts(){
        wp_register_script('frontend-js-block-location', \Wpcafe::assets_url() . 'js/wpc-block-location.js', [ 'jquery', 'wpc-pro-widgets-modal-script', 'jquery-timepicker', 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n' ], \Wpcafe::version());
    }


    function wpc_location_callback() {
        //check if the location module is active
        $settings  = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();

        if( ! isset( $settings['wpcafe_food_location']) ){
            return;
        }

        if(is_checkout() ){ 
            wp_enqueue_script( 'frontend-js-block-location' );
        }

        return do_shortcode( '[wpc_location_checkout]' );
    }

}
