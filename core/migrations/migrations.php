<?php

namespace WpCafe\Core\Migrations;

defined('ABSPATH') || exit;

class Migrations {
    
    use \WpCafe\Traits\Wpc_Singleton;
    /**
     * Main Function 
     *
     * @return void
     */
    public function init(){
        $this->migrate_booking_date();    // migrations booking date to Y-m-d
    }

    /**
     * migrations booking date to Y-m-d
     *
     * @return void
     */
    public function migrate_booking_date() {
        $migration_done = !empty( get_option( "wpc_format_booking_date" ) ) ? true : false;
        
        if( !$migration_done ){

            $args          = [
                'post_type' => 'wpc_reservation',
            ];
            $bookings = get_posts($args);
            foreach( $bookings as $booking ){
                $get_booking_date = get_post_meta( $booking->ID , 'wpc_booking_date', true );
                if ( $get_booking_date !=="" ) {
                    $booking_date = date("Y-m-d",strtotime($get_booking_date) );
                    update_post_meta( $booking->ID , 'wpc_booking_date', $booking_date );
                }
            }

            update_option( "wpc_format_booking_date", true );
        }
    }

}
