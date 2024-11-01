<?php
namespace WpCafe\Core\Post_type;

defined( 'ABSPATH' ) || exit;

/**
 * create post type class
 */
class Cpt {
    use \WpCafe\Traits\Wpc_Singleton;

    public function init() {
        /**
         * Register custom posts
         */
        $service = new \WpCafe\Core\Base\Wpc_Custom_Post();
        $this->register_reservation_cpt( $service );

        /**
         * Register metaboxes for custom posts
         */
        $wpc_reservation_metabox = new \WpCafe\Core\Metaboxes\Wpc_Reservation_Meta();
        add_action( 'add_meta_boxes', [$wpc_reservation_metabox, 'register_meta_boxes'] );
        add_action( 'save_post', [$wpc_reservation_metabox, 'save_meta_box_data'] );
        add_filter( 'wp_insert_post_data', [ $wpc_reservation_metabox, 'wpc_set_reservation_title' ], 500, 2 );
        add_filter( 'register_post_type_args', [$this, 'reservation_slug_register_post_type_args'], 10, 2 );

        // Food locations
        $wpc_food_location_slug          = sanitize_title( 'Food locations' );
        $wpc_food_location_singular_name = esc_html__( 'Food Locations', 'wpcafe' );
        $wpc_food_location_plural_name   = esc_html__( 'Food Locations', 'wpcafe' );

        if ( $wpc_food_location_slug == '' ) {
            $wpc_food_location_slug = esc_html__( 'Food Locations', 'wpcafe' );
        }

        if ( $wpc_food_location_singular_name == '' ) {
            $wpc_food_location_singular_name = esc_html__( 'Food Locations', 'wpcafe' );
        }

        $wpc_wpcafe_location = new \WpCafe\Core\Base\Wpc_Taxonomies();
        $wpc_wpcafe_location->xs_init( 'wpcafe_location', $wpc_food_location_singular_name, $wpc_food_location_plural_name, $wpc_food_location_slug, 'product' );
        
        // add header in reservation list page
        add_action('admin_notices',[$this,'reservation_post_add_header']);
    }

    /**
     * add header in reservation list page
     */
    public function reservation_post_add_header(){
        if ( ! empty($_GET['post_type']) && 'wpc_reservation' == $_GET['post_type'] ) {
            $reservation_header = true;
            // header start
            include_once \Wpcafe::core_dir() . "settings/layout/header.php";
            //  header end
        }

    }

    /**
     * Add custom post reservation
     */
    public function register_reservation_cpt( $service ) {
        //Reservation form
        $slug          = sanitize_title( "reservation_forms" );
        $plural_name   = esc_html__('Reservations', 'wpcafe');
        $singular_name = esc_html__('Reservation', 'wpcafe');

        if ( $slug == '' ) {
            $slug = esc_html__( 'reservation_forms', 'wpcafe' );
        }

        $menus_rewrite = [ 'slug' => $slug ];
        $service->xs_init( 'wpc_reservation', $singular_name, $plural_name,
            array(
                'menu_icon'           => 'dashicons-grid-view',
                'supports'            => false,
                'rewrite'             => $menus_rewrite,
                'exclude_from_search' => true,
                'show_in_menu'        => 'edit.php?post_type=wpc_reservation',
            ) );
    }

    
    //translatable reservation texts
    public function reservation_slug_register_post_type_args( $args, $post_type ) {
    
        if ( 'wpc_reservation' === $post_type ) {
            $args['labels']['name'] = esc_html__('Reservations', 'wpcafe');
            $args['labels']['singular_name'] = esc_html__('Reservation', 'wpcafe'); 
            $args['labels']['add_new_item'] = esc_html__('Add New Reservation', 'wpcafe'); 
            $args['labels']['edit_item'] = esc_html__('Edit Reservation', 'wpcafe'); 
            $args['labels']['new_item'] = esc_html__('New Reservation', 'wpcafe'); 
            $args['labels']['view_item'] = esc_html__('View Reservation', 'wpcafe'); 
            $args['labels']['search_items'] = esc_html__('Search Reservations', 'wpcafe'); 
            $args['labels']['not_found'] = esc_html__('No Reservations found', 'wpcafe'); 
            $args['labels']['not_found_in_trash'] = esc_html__('No Reservations found in trash', 'wpcafe'); 
            $args['labels']['parent_item_colon'] = esc_html__('Parent Reservation', 'wpcafe'); 
        }
    
        return $args;
    }

}
