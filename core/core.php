<?php
namespace WpCafe\Core;

use WpCafe\Utils\Wpc_Utilities;

defined( "ABSPATH" ) || exit;

/**
 * Load all admin class
 */
class Core {
    
    use \WpCafe\Traits\Wpc_Singleton;

    /**
     *  Call admin function
     */
    function init() {

        //register all menu
        \WpCafe\Core\Menu\Wpc_Menus::instance()->init();
        
        // Settings field for bookings
        $setting_field = \WpCafe\Core\Base\Wpc_Settings_Field::instance();
        
        $this->dispatch_actions( $setting_field );
        // All modules register
        $this->register_backend_actions();

        // add our custom date formats
        add_filter( 'date_formats', [ $this, 'merge_date_formats' ], 10, 1 );

	add_filter( 'safe_style_css', [ $this, 'add_filtered_style_attr'] );
        
        // All modules register
        $this->update_date_time_format_from_wp();

        // check valid format
        Wpc_Utilities::check_date_format_is_valid(); 
    }

	/**
     * Filtered style for div attr
     */
    public function add_filtered_style_attr( $styles ) {
        $styles[] = 'display';

	return $styles;
    }

    /**
     * Register  for backend
     */
    public function register_backend_actions() {
        //register reservation report dashboard
        \WpCafe\Core\Modules\Reservation\Wpc_Reservation_Report::instance()->init();
        // migrate data
        \WpCafe\Core\Migrations\Migrations::instance()->init();
    }

    /**
     * Register modules
     */
    public function register_modules() {
        //register food menu module
        \WpCafe\Core\Modules\Food_Menu\Hooks::instance()->init();
    }

    /**
     * Save settings
     */
    public function dispatch_actions( $setting_field ) {
        add_action( 'admin_init', [$setting_field, 'form_handler'] );
    }

    /**
     * update date/time format from saved settings
     *
     * @return void
     */
    public function update_date_time_format_from_wp() {
        $convert_date_time_format_from_wp_done = !empty( get_option( "wpcafe_convert_date_time_format_from_wp" ) ) ? true : false;
        if( !$convert_date_time_format_from_wp_done ) {
            $settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
    
            if ( isset( $settings['wpc_date_format'] ) && !empty( $settings['wpc_date_format'] ) ) {
                update_option( 'date_format', $settings['wpc_date_format'] );
            }
    
            if ( isset( $settings['wpc_time_format'] ) && !empty( $settings['wpc_time_format'] ) ) {
                if ( $settings['wpc_time_format'] == '24' ) {
                    update_option( 'time_format', 'G:i a' );
                }
            }

            update_option( "wpcafe_convert_date_time_format_from_wp", true );
        }
    }

    /**
     * merge wp default date formats with wpcafe custom formats
     *
     * @param array $all_date_formats
     * @return array
     */
    public function merge_date_formats( $all_date_formats = [] ) {
        $cafe_custom_date_formats = [
            'y/m/d',
            'd-m-Y',
            'm-d-Y',
            'Y.m.d',
            'm.d.Y',
            'd.m.Y',
        ];

        $all_date_formats = array_merge( $all_date_formats, $cafe_custom_date_formats );

        return $all_date_formats;
    }

}
