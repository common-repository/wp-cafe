<?php

namespace WpCafe\Traits;

defined( 'ABSPATH' ) || exit;

/**
 * Instance of class
 */
trait Wpc_Singleton {
    
    private static $instance;

    /**
     * Wpc_Singleton trait
     */
    public static function instance() {
        if ( !self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}
