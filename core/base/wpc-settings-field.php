<?php
namespace WpCafe\Core\Base;

defined( "ABSPATH" ) || exit;

use WpCafe\Traits\Wpc_Singleton;

class Wpc_Settings_Field {

    use Wpc_Singleton;

    private $key_settings_option;

    /**
     * set key option function
     */
    public function __construct() {
        $this->key_settings_option = 'wpcafe_reservation_settings_options';
    }
    
    /**
     * Get all settings
     */
    public function get_settings_option( $key = null, $default = null ) {
        if ( $key != null ) {
            $this->key_settings_option = $key;
        }
        
        return get_option( $this->key_settings_option );
    }

    public function set_option( $key, $default = null ) {
    }

    /**
     * Convert time in 24
     *
     */
    public function convert_time_24( $time ){
        $time_24 = date('H:i', strtotime($time)  );

        return $time_24;
    }

    /**
     * Compare start time and end time 
     */
    public function compare_time( $start_arr , $end_arr ){
        $time_checking_error = false;
        if ( ( is_array( $start_arr ) && $start_arr[0] !== "" ) && ( is_array($end_arr) && $end_arr[0] !== ""  ) ) {
            for ($i=0; $i < count( $start_arr ); $i++) { 
                $start_time = $this->convert_time_24( $start_arr[$i] );
                $end_time   = $this->convert_time_24( $end_arr[$i] );
                if ( $start_time >= $end_time ) {
                    $time_checking_error = true;
                }
            }
        }

        return $time_checking_error;
    }

    /**
     * Submit action of settings
     */
    public function form_handler() {
        // WPCafe admin settings 
        if ( isset( $_POST['wpcafe_settings_key_options_action'] ) ) {
            $request = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
            if ( !check_admin_referer( 'wpcafe-settings-page', 'wpcafe-settings-page' ) ) {
                return;
            }
            // multi slot schedule
            $request = apply_filters( 'wpcafe_pro/multiple_slot/settings', $request );
            return \WpCafe\Core\Action\Wpc_Action::instance()->wpc_store( -1, $request ); 
        }

        if ( isset( $_POST['wpcafe_tools_action'] ) ) {
            $request = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
            if ( !check_admin_referer( 'wpcafe-tools-page', 'wpcafe-tools-page' ) ) {
                return;
            }

            return \WpCafe\Core\Action\Wpc_Action::instance('wpcafe_tools_action')->wpc_store( -1, $request ); 
        }
        
        // product addons
        if ( isset( $_POST['wpcafe_product_addons'] ) ) {
            
            $request = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
            
            if ( !check_admin_referer( 'wpcafe-product-addons', 'wpcafe-product-addons' ) ) {
                return;
            }
            return \WpCafe\Core\Action\Wpc_Action::instance("wpcafe_product_addons")->wpc_store( -1, $request );
        }

        // WPCafe app settings 
        if ( isset( $_POST['wpcafe_app_settings_options'] ) ) {
            
            $request = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
            
            if ( !check_admin_referer( 'wpcafe-app-settings-page', 'wpcafe-app-settings-page' ) ) {
                return;
            }
            return \WpCafe\Core\Action\Wpc_Action::instance()->wpc_store( -1, $request );
        }

    }

}
