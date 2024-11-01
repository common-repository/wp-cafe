<?php

namespace WpCafe\Core\Base;

defined( 'ABSPATH' ) || exit;

/**
 * Menu handle class
 */

if ( !class_exists( 'Wpc_Menu_Build' ) ) {
    class Wpc_Menu_Build {

        private $admin_pages     = [];
        private $admin_sub_pages = [];

        /**
         * Load all menu and sub menus
         *
         * @return void
         */
        public function __construct( $pages , $name , $sub_pages ){
            // register menu
            $this->add_pages( $pages )
            ->sub_menu_pages( $name )
            ->add_sub_pages( $sub_pages )
            ->menu_register();
        }

        public function menu_register() {
            if ( !empty( $this->admin_pages ) ) {
                add_action( 'admin_menu', [$this, 'wpc_add_admin_menu'] );
            }
        }

        /**
         * Create main page array function
         */
        public function add_pages( $pages ) {
            $this->admin_pages = $pages;

            return $this;
        }

        /**
         * merge all page function
         */
        public function add_sub_pages( $pages ) {
            $this->admin_sub_pages = array_merge( $this->admin_sub_pages, $pages );

            return $this;
        }

        /**
         * Create menu page
         * @param [type] $cb_function
         */
        public function sub_menu_pages( $title = null ) {
            if ( empty( $this->admin_pages ) ) {
                return;
            }

            $admin_page = $this->admin_pages[0];
            $sub_pages  = [
                [
                    "parent_slug" => $admin_page['menu_slug'],
                    "page_title"  => $admin_page['page_title'],
                    "menu_title"  => ( $title ) ? $title : $admin_page['menu_title'],
                    "capability"  => $admin_page['capability'],
                    "menu_slug"   => $admin_page['menu_slug'],
                    "cb_function" => $admin_page['cb_function'],
                    "position"    => $admin_page['position'],
                ],
            ];
            $this->admin_sub_pages = $sub_pages;

            return $this;
        }

        /**
         * Create admin and sub menu
         */
        public function wpc_add_admin_menu() {
            foreach ( $this->admin_pages as $key => $value ) {
                add_menu_page( $value['page_title'], $value['menu_title'],
                    $value['capability'], $value['menu_slug'], $value['cb_function'], $value['icon'], $value['position'] );
            }

            foreach ( $this->admin_sub_pages as $key => $value ) {
                add_submenu_page( $value['parent_slug'], $value['page_title'], __( $value['menu_title'], 'wpcafe' ),
                    $value['capability'], $value['menu_slug'], $value['cb_function'] , $value['position'] );
            }
        }
    }
}
