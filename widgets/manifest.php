<?php

namespace WpCafe\Widgets;

defined( 'ABSPATH' ) || exit;

use WpCafe\Utils\Wpc_Utilities;

Class Manifest {
    use \WpCafe\Traits\Wpc_Singleton;

    private $categories = ['menu' => 'Wpcafe menu'];

    public function init() {
        add_action( 'elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories'] );
        add_action( 'elementor/widgets/register', [$this, 'register_widgets'] );
    }

    public function get_input_widgets() {
        return [
            'Wpc_Menus_List',
            'Wpc_Food_Menu_Tab',
            'Wpc_Resevation_Form',
            'Wpc_Food_Locaion',
            'Wpc_Location_Menu',
            'Wpc_Food_Location_Filter'
        ];
    }

    public function includes() {

    }

    /**
     * Register all elementor widgets dynamically
     */
    public function register_widgets() {

        foreach ( $this->get_input_widgets() as $v ):
            $f     = str_replace( '_', '-', $v );
            $files = plugin_dir_path( __FILE__ ) . strtolower( $f ) . '/' . strtolower( $f ) . '.php';

            if ( file_exists( $files ) ) {
                require_once $files;
                $class_name = 'WpCafe\\Widgets\\' . $v . '\\' . Wpc_Utilities::make_classname( $v );
                \Elementor\Plugin::instance()->widgets_manager->register( new $class_name() );
            }
        endforeach;
    }

    public function add_elementor_widget_categories( $elements_manager ) {
        foreach ( $this->categories as $k => $v ) {
            $elements_manager->add_category(
                'wpcafe-' . $k,
                [
                    'title' => esc_html( $v ),
                    'icon'  => 'fa fa-plug',
                ]
            );
        }
    }
}
