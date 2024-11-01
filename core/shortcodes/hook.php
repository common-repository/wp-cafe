<?php

namespace WpCafe\Core\Shortcodes;

defined("ABSPATH") || exit;

use Astra_Woocommerce;
use WpCafe\Traits\Wpc_Singleton;
use WpCafe\Utils\Wpc_Utilities;

/**
 * create post type class
 */
class Hook{

    use Wpc_Singleton;

    private $settings_obj = null;
    public $wpc_message   = '';
    public $wpc_cart_css  = '';

    /**
     * call hooks
     */
    public function init(){

        $settings = $this->settings_obj =  \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();


        $shortcode_arr  = array(
            'wpc_food_menu_tab'     => 'wpc_food_menu_tab',
            'wpc_food_menu_list'    => 'wpc_food_menu_list',
            'wpc_reservation_form'  => 'reservation_shortcode',
            'wpc_food_location_menu'=> 'food_location_menu',
            'food_location_filter'  => 'food_location_filter',
	    'wpc_location_checkout' => 'wpc_location_checkout_block',
			
        );

        // add shortcode
        if( ! empty( $shortcode_arr ) ){
            foreach( $shortcode_arr as $key => $value ){
                add_shortcode($key, [$this, $value]);
            }
        }

        // add mini-cart to header
        add_action('wp_head', [$this, 'wpc_custom_inline_css']);
        add_action('wp_footer', [$this, 'wpc_custom_mini_cart']);

        // add new field in checkout page
        if ( isset($settings['wpcafe_food_location']) && $settings['wpcafe_food_location'] == 'on' && class_exists('WooCommerce')) {
            add_action('woocommerce_checkout_before_customer_details', [$this, 'wpc_location_checkout_form'], 10);
            add_action('woocommerce_checkout_create_order', [$this, 'wpc_location_update_meta'], 10, 1);
        }

        // menu order action
        add_action('product_cat_add_form_fields', [$this, 'wpc_product_cat_taxonomy_add_new_meta_field'], 10, 1);
        add_action('product_cat_edit_form_fields', [$this, 'wpc_product_cat_taxonomy_edit_meta_field'], 10, 1);
        add_action('edited_product_cat', [$this, 'wpc_product_cat_taxonomy_save_meta_field'], 10, 1);
        add_action('create_product_cat', [$this, 'wpc_product_cat_taxonomy_save_meta_field'], 10, 1);

        //Displaying Additional Columns
        add_filter('manage_edit-product_cat_columns', [$this, 'wpc_custom_fields_list_title']);
        add_action('manage_product_cat_custom_column', [$this, 'wpc_custom_fields_list_diplay'], 10, 3);

        if ( ( !empty( $settings['wpcafe_allow_cart'] ) && $settings['wpcafe_allow_cart'] == "on" ) &&  class_exists('woocommerce') ) {
			// update cart counter.
			add_filter('woocommerce_add_to_cart_fragments', [$this, 'wpc_add_to_cart_count_fragment_refresh'], 30, 1);
			add_filter('woocommerce_add_to_cart_fragments', [$this, 'wpc_add_to_cart_content_fragment_refresh']);
		}
		

		// in mini-cart before 'cart', 'checkout' buttons add extra content.
		add_action( 'woocommerce_widget_shopping_cart_before_buttons', [ $this, 'before_minicart_buttons_add_extra_content' ], 9, 1 );
		// ajax add to cart
		add_action('woocommerce_before_single_product', [ $this,'add_woocommerce_template_loop_add_to_cart']);
    }
	
	/**
	* Add hook for ajax add to cart
	*/
	public function add_woocommerce_template_loop_add_to_cart() {
		global $product;

		if (isset($product) && !$product->is_type('external')) {
			?>
			<script type="text/javascript">
				(function ($) {
					"use strict";
					$(document).ready(function () {
						wpc_add_to_cart($);
					});
				})(jQuery);
			</script>
			<?php
		}
	}


    /**
     * Create a shortcode to render the reservation form.
     * Print the reservation form's HTML code.
     */
    public function reservation_shortcode($atts){
        ob_start();

        $settings = $this->settings_obj;
        // get pro feature values
        $result_data = [];

        $sanitized_atts = [];
        foreach ($atts as $key => $value) {
            switch ($key) {
                case 'booking_status':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'seat_capacity':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'calender_view':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'column_lg':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'column_md':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'from_field_label':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'to_field_label':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'show_form_field':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'show_to_field':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'required_from_field':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'required_to_field':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'first_booking_button':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'form_booking_button':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'form_cancell_button':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'form_style':
                    $sanitized_atts[$key] = esc_html($value);
                    break;
                case 'wpc_image_url':
                    $sanitized_atts[$key] = esc_url($value);
                    break;
                case 'reservation_extra_field':
                    $sanitized_atts[$key] = wp_kses( $value, Wpc_Utilities::wpc_kses_allowed_tags() );
                    break;
                default:
                    $sanitized_atts[$key] = esc_html($value);
                    break;
            }
        }

        $result_data = apply_filters('wpcafe/action/reservation_template', $sanitized_atts );

        if(class_exists('Wpcafe_Pro')){
            extract( shortcode_atts( [
                'fluent_crm_webhook'  => '',
            ], $atts ));
        }

        $from_field_label     = esc_html__('From', 'wpcafe');
        $to_field_label       = esc_html__('To', 'wpcafe');
        $show_form_field      = "on";
        $show_to_field        = "on";
        $from_to_column       = "wpc-col-md-6";
        $required_from_field  = 'on';
        $required_to_field    = 'on';
        $view                 = 'yes';
        $column_lg            = 'wpc-col-lg-6';
        $column_md            = 'wpc-col-md-12';
        $first_booking_button   = esc_html__("Book a table","wpcafe");
        $booking_button_text    = esc_html__("Confirm Booking","wpcafe");
        $cancel_button_text     = esc_html__("Request Cancellation" ,"wpcafe");

        if ( is_array($result_data) && ! empty($result_data) ) {
            if ( isset( $result_data['calender_view']) ) {
                $view      = $result_data['calender_view'];
                $column_lg = isset($result_data['column_lg']) ? $result_data['column_lg'] : 'wpc-col-lg-6';
                $column_md = isset($result_data['column_md']) ? $result_data['column_md'] : 'wpc-col-md-12';
            }
            if(isset( $result_data['from_field_label'] ) && isset( $result_data['to_field_label'] )  ) {
                $from_field_label   =  $result_data['from_field_label'];
                $to_field_label     =  $result_data['to_field_label'];
                $show_form_field    =  $result_data['show_form_field'];
                $show_to_field      =  $result_data['show_to_field'];
                $required_from_field=  $result_data['required_from_field'];
                $required_to_field  =  $result_data['required_to_field'];

                if(!( $show_form_field =='on' && $show_to_field =='on' ) ){
                    $from_to_column = "wpc-col-md-12";
                }

                $first_booking_button   = $result_data['first_booking_button'];
                $booking_button_text    = $result_data['form_booking_button'];
                $cancel_button_text     = $result_data['form_cancell_button'];
            }
        }

        $seat_capacity  = isset( $result_data['seat_capacity'] ) ? $result_data['seat_capacity'] : 20;
        
        $booking_status = isset( $result_data['booking_status'] ) ? $result_data['booking_status']: '';
        
        $reservation_form_template = \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/reservation-form-template.php";
        $cancellation_form_template = \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/cancellation-form-template.php";
        
        // All form settings for reservation
        if ( file_exists( \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/form-settings.php" ) ) {
            include_once \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/form-settings.php";
        }

        ?>
        <div class="reservation_section">
            <?php

            if( file_exists( $reservation_form_template ) ){
            	// get all form settings
                include_once $reservation_form_template;
            }

            if ( !empty( $settings['wpc_allow_cancellation'] ) && $settings['wpc_allow_cancellation'] !=="off" && file_exists( $cancellation_form_template )) {
                include_once $cancellation_form_template;
            }

            ?>
        </div>
        <?php


        return ob_get_clean();
    }

    /**
     * Food menu shortcode
     */
    public function wpc_food_menu_tab($atts){
        if (!class_exists('Woocommerce')) { return; }
        $settings = array();
        $atts     = Wpc_Utilities::replace_qoute( $atts );

        $atts = extract(shortcode_atts([
            'style'                 => 'style-1',
            'wpc_food_categories'   => '',
            'no_of_product'         => 5,
            'wpc_desc_limit'        => 20,
            'wpc_menu_order'        => 'DESC',
            'wpc_show_desc'         => 'yes',
            'title_link_show'       => 'yes',
            'show_item_status'      => 'yes',
            'product_thumbnail'     => 'yes',
            'wpc_cart_button'       => 'yes',
            'wpc_price_show'        => 'yes',
        ], $atts));

        ob_start();
        $wpc_cat_arr  = explode(',', $wpc_food_categories);
        if (!empty($wpc_cat_arr)) {
            $food_menu_tabs = Wpc_Utilities::get_tab_array_from_category($wpc_cat_arr);
            
            // sort category list
            if ( !empty($food_menu_tabs) ) {
                ksort($food_menu_tabs);
            }
            
            $unique_id = md5(md5(microtime()));
            $settings["food_menu_tabs"]         = $food_menu_tabs;
            $settings["food_tab_menu_style"]    = $style;
            $settings["show_thumbnail"]         = $product_thumbnail;
            $settings["wpc_menu_order"]         = $wpc_menu_order;
            $settings["show_item_status"]       = $show_item_status;
            $settings["wpc_menu_count"]         = $no_of_product;
            $settings["wpc_show_desc"]          = $wpc_show_desc;
            $settings["wpc_desc_limit"]         = $wpc_desc_limit;
            $settings["title_link_show"]        = $title_link_show;
            $settings["wpc_cart_button"]        = $wpc_cart_button;
            $settings["wpc_price_show"]        = $wpc_price_show;
            // render template
            $template = \Wpcafe::core_dir() ."shortcodes/views/food-menu/food-tab.php";
            if( file_exists( $template ) ){
                include $template;
            }
        }
        
        return ob_get_clean();
    }

    /**
     * Food menu list block
     */
    public function wpc_food_menu_list($atts){

        if (!class_exists('Woocommerce')) { return; }

        $atts    = Wpc_Utilities::replace_qoute( $atts );
        $atts    = extract(shortcode_atts(
            [
                'style'                 => 'style-1',
                'wpc_food_categories'   => '',
                'no_of_product'         => 5,
                'wpc_cart_button'       => 'yes',
                'product_thumbnail'     => 'yes',
                'wpc_price_show'        => 'yes',
                'show_item_status'      => 'yes',
                'wpc_show_desc'         => 'yes',
                'title_link_show'       => 'yes',
                'wpc_desc_limit'        => 20,
                'wpc_menu_order'        => 'DESC',
                'wpc_menu_col'          => '4',
                'wpc_menu_col_tablet'   => '3',
                'wpc_menu_col_mobile'   => '2',
                'wpc_show_vendor'       => 'no'
            ],
            $atts
        ));

        $allowed_file_names = [
            'style-1',
            'style-2',
            'style-3'
        ];
    
        if( in_array($style, $allowed_file_names)){
            $template_file = esc_html($style);
        }else{
            $template_file = $allowed_file_names[0];
        }

        ob_start();
        // category sorting from backend
        $wpc_cat_arr      = explode(',', $wpc_food_categories);

        $wpc_menu_col           = 4;
        $wpc_menu_col_tablet    = 3;
        $wpc_menu_col_mobile    = 2;

        if (is_array($wpc_cat_arr) && count($wpc_cat_arr) > 0) {
            $unique_id = md5(md5(microtime()));
            $settings = array();
            $settings["food_menu_style"]        = $template_file;
            $settings["show_thumbnail"]         = $product_thumbnail;
            $settings["wpc_price_show"]         = $wpc_price_show;
            $settings["wpc_cart_button_show"]   = $wpc_cart_button;
            $settings["show_item_status"]       = $show_item_status;
            $settings["title_link_show"]        = $title_link_show;
            $settings["wpc_show_desc"]          = $wpc_show_desc;
            $settings["wpc_desc_limit"]         = $wpc_desc_limit;
            $settings["wpc_menu_cat"]           = $wpc_cat_arr;
            $settings["wpc_menu_count"]         = $no_of_product;
            $settings["wpc_menu_order"]         = $wpc_menu_order;
            
            $settings['wpc_menu_col']           = $wpc_menu_col;
            $settings['wpc_menu_col_tablet']    = $wpc_menu_col_tablet;
            $settings['wpc_menu_col_mobile']    = $wpc_menu_col_mobile;
            $settings["wpc_show_vendor"]        = $wpc_show_vendor;

            // render template
            $template = \Wpcafe::core_dir() ."shortcodes/views/food-menu/food-list.php";
            if( file_exists( $template ) ){
                include $template;
            }
        }
        return ob_get_clean();
    }

    /**
     * menu of the popup
     *
     */

     public function wpc_menu_off_the_popup($settings){
        if (!class_exists('Wpcafe_Pro')) {  return; }
        $menu_popup_duration            =  !empty( $settings['menu_popup_duration'] ) ? $settings['menu_popup_duration'] : '' ;
        $date_now = date( WPCAFE_DEFAULT_DATE_FORMAT );
        
        if ( $menu_popup_duration !=="" && $date_now > $menu_popup_duration ) {
            return;
        }

        $special_menu_title1            =  isset( $settings['special_menu_title1'] ) ? $settings['special_menu_title1'] : '' ;
        $special_menu_title2            =  isset( $settings['special_menu_title2'] ) ? $settings['special_menu_title2'] : '' ;
        $special_menu_title3            =  isset( $settings['special_menu_title3'] ) ? $settings['special_menu_title3'] : '' ;
        $special_menus                  =  isset( $settings['special_menus'] ) ? $settings['special_menus'] : [] ;
        $special_menu_button            =  isset( $settings['special_menu_button'] ) ? $settings['special_menu_button'] : '' ;
        $special_menu_button_link       =  isset( $settings['special_menu_button_link'] ) ? $settings['special_menu_button_link'] : '' ;
        $wpc_image_url = \Wpcafe::assets_url() . 'images/motd-btn-shade.svg';

    ?>
    <div class="wpc-menu-of-the-day">
         <div id="wpc-menu-of-the-day-modal">
            <div class="modal-content">
            <div class="motd-bg-shape">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 500.9 683" style="enable-background:new 0 0 500.9 683;" xml:space="preserve">
            <style type="text/css">
                .st0{fill:#FFEA28;}
            </style>
            <g>
                <path class="st0" d="M497.6,404.3c-1.3-0.7-2.5-4-4.3-1.5c-1.8,2.5-2.8,5.5-2.8,8.6c-0.5,12.5,0.5,25.4-6,36.9
                    c-1,1.9-1.3,4.4-4.4,3.7c-2.5-0.5-4.1-1.9-4-4.7c0.2-5-0.2-10,0.2-14.9c1.5-18-2-36.6,6.5-54.7c-4.6,11.8,6,13.1,12.5,17.1
                    c4,2.5,6,0.9,5.5-3c-2.6-19.3,0.6-38.6-3.1-58c-0.5-2.5-2.4-4.3-2.2-7.5c1.1-24.7-2.6-49.2-10-73.3c-0.4-1.4-0.6-8-5.1-1.4
                    c-0.8,1.2-10-2.5-10.8-9.1c-0.5-3.9-2.2-8.5,2.8-9.5c6.5-1.3,3.9,6,7.3,8.2c0.5,0.2,1,0.4,1.5,0.5c2.2-3.6-0.3-6.9-1.3-10.2
                    c-10.7-36.2-26-70.9-42.8-105.3c-9.2-18.7-20.6-37.1-20.4-58.3c-0.2-1.9-1-3.7-2.2-5.1c-9.7-11.4-12.2-25.7-20.9-37.6
                    c-5.7-7.8-9.2-16.6-12.8-25.3H0v683h397.2c13.5-27.7,27.6-55.2,39.1-83.8c8.8-22,19.4-43.4,23.9-66.8c0.8-4,3.7-3,5.7-3.6
                    c1.2-0.5,2.5,0.1,2.9,1.3c0.3,0.7,0.2,1.4-0.2,2c-5.8,9.9,0.6,21.9-6.7,31.8c6,0.4,11.5-0.1,9.7-5.2c-2.5-6.7,1.4-10.5,3.7-15.4
                    c13.4-27.4,14.1-57.5,17.3-87c1.6-14.9,1.1-30.1,5.2-44.5C498.6,408.9,502.8,406.9,497.6,404.3z"/>
                <path class="st0" d="M440.4,624.1c2-0.9,4.8-1.5,5.4-3c7.2-16.4,13.9-33,20.8-49.6l-4.2-1.7C457.1,588.8,441.9,603.6,440.4,624.1z"
                    />
            </g>
            </svg>
            </div>
                <div class="wpc-row motd-wrap">
                    <div class="wpc-col-12 wpc-col-sm-5 wpc-align-self-center">
                        <div class="wpc-motd-wrap">
                            <h3 class="wpc-motd-title">
                            <?php echo esc_html( $special_menu_title1);?></h3>
                          
                            <h4 class="wpc-motd-subtitle">
                                <span class="wpc-motd-left">
                                <svg width="22" height="24" viewBox="0 0 22 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.6088 22.2945C5.54579 21.7156 3.38587 21.4235 1.29062 21.156C0.839336 21.0999 0.420348 21.4129 0.388114 21.8538C0.323644 22.2951 0.64618 22.6987 1.06523 22.7548C3.09601 23.0124 5.19108 23.288 7.15739 23.8453C7.57644 23.9662 8.02792 23.7173 8.15686 23.2893C8.28579 22.8615 8.02785 22.4157 7.6088 22.2945Z" fill="white"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8621 14.0866C10.5419 10.6984 6.80273 7.74635 3.54703 4.26727C3.25691 3.94234 2.74116 3.92493 2.41881 4.22826C2.09647 4.53191 2.06436 5.04251 2.3867 5.36743C5.64241 8.85651 9.38163 11.8182 12.7018 15.2164C13.0241 15.5336 13.5398 15.5381 13.8621 15.2261C14.1522 14.9144 14.1845 14.4038 13.8621 14.0866Z" fill="white"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M20.0838 1.05443C20.1805 2.99367 20.277 4.9329 20.3737 6.87246C20.3737 7.31666 20.7608 7.65964 21.212 7.63804C21.6633 7.61612 21.9855 7.23801 21.9855 6.79349C21.8888 4.85103 21.7923 2.90889 21.6956 0.966751C21.6633 0.522557 21.2764 0.181837 20.8252 0.206013C20.4061 0.230189 20.0516 0.610559 20.0838 1.05443Z" fill="white"/>
                                </svg>
                                </span>
                                <?php echo esc_html( $special_menu_title2);?>
                                <span class="wpc-motd-right">
                                <svg width="256" height="66" viewBox="0 0 256 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <svg width="22" height="24" viewBox="0 0 22 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.6088 22.2945C5.54579 21.7156 3.38587 21.4235 1.29062 21.156C0.839336 21.0999 0.420348 21.4129 0.388114 21.8538C0.323644 22.2951 0.64618 22.6987 1.06523 22.7548C3.09601 23.0124 5.19108 23.288 7.15739 23.8453C7.57644 23.9662 8.02792 23.7173 8.15686 23.2893C8.28579 22.8615 8.02785 22.4157 7.6088 22.2945Z" fill="white"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8621 14.0866C10.5419 10.6984 6.80273 7.74635 3.54703 4.26727C3.25691 3.94234 2.74116 3.92493 2.41881 4.22826C2.09647 4.53191 2.06436 5.04251 2.3867 5.36743C5.64241 8.85651 9.38163 11.8182 12.7018 15.2164C13.0241 15.5336 13.5398 15.5381 13.8621 15.2261C14.1522 14.9144 14.1845 14.4038 13.8621 14.0866Z" fill="white"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M20.0838 1.05443C20.1805 2.99367 20.277 4.9329 20.3737 6.87246C20.3737 7.31666 20.7608 7.65964 21.212 7.63804C21.6633 7.61612 21.9855 7.23801 21.9855 6.79349C21.8888 4.85103 21.7923 2.90889 21.6956 0.966751C21.6633 0.522557 21.2764 0.181837 20.8252 0.206013C20.4061 0.230189 20.0516 0.610559 20.0838 1.05443Z" fill="white"/>
                                </svg>
                                </span>
                            </h4> 

                            <?php if($special_menu_title3) : ?>    
                            <p class="wpc-motd-discount">
                                <?php   echo esc_html( $special_menu_title3);  ?> 
                            </p>
                            <?php endif; ?>

                            <?php if($special_menu_button_link != ''): ?>
                            <a href="<?php echo esc_url($special_menu_button_link);?>" class="wpc-motd-order-btn wpc-btn"><?php echo esc_html( $special_menu_button);?> <i class="wpcafe-icon-arrow-right"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="wpc-col-12 wpc-col-sm-6 wpc-align-self-center wpc-offset-1">
                        <div class="wpc-motd-wrap">
                            <div class="wpc-motd-products">
                            <?php 
                                foreach( $special_menus as $special_menu){
                            ?>
                                <div class="wpc-motd-product">
                                    <a href="<?php echo esc_url( get_permalink( $special_menu ) ); ?>" class="motd-product-link">
                                        <div class="wpc-row">
                                            <div class="wpc-col-4 wpc-motd-product-img">
                                                <img src="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id( $special_menu ) ) );?>" alt="">
                                            </div>
                                            <div class="wpc-col-8">
                                                <h3 class="wpc-motd-product-title"><?php echo esc_html( get_the_title( $special_menu) )?></h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php
                                }
                            ?>    
                            </div> 
                        </div>                     
                    </div>
                </div>
                <button class="wpc-btn special-menu-close"> X </button>
            </div>
        </div>
    </div>
    <?php
     }
    /**
     * Mini cart for frontend
     *
     */
    public function wpc_custom_mini_cart(){
        if (!class_exists('WooCommerce')) {  return; }
        $settings       = $this->settings_obj;

        $location = isset( $_GET['location'] ) ? absint( $_GET['location'] ): 0;
        
        // show location
        if (!empty($settings['wpcafe_food_location']) && $settings['wpcafe_food_location'] == 'on' && empty( $location ) ) {
            ?>
            <div class="popup_location"></div>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var location_data = localStorage.getItem('wpc_location');
                    location_data   = location_data !==null ? JSON.parse(location_data) : null;

                    if (jQuery('.wpc_map_and_result_wrapper').length == 0 ){
                    if ( location_data == null || ( typeof location_data.value !=="undefined"
                    && location_data.value =="") ){
                        jQuery(".popup_location").html(
                        `<div id="wpc_location_modal" class="wpc_modal"><div class="modal-content">
                        <select name="wpc-location" class="wpc-location">
                            <?php
                            $wpc_locations = Wpc_Utilities::get_location_data("","","id");
                            // get wpcafe locations
                            foreach ( $wpc_locations as $key => $value) {
                                ?>
                                <option value="<?php echo esc_html( $key ) ?>" <?php echo count($wpc_locations) <= 2? "selected='selected'" : "" ?> ><?php echo esc_html( $value ) ?></option>  
                                <?php
                            }
                            ?>
                        </select>
                        <div class="saving_warning hide_field"><?php echo esc_html__( "Press OK to save your desired location" ,"wpcafe") ?></div>
                        <button class="wpc-select-location wpc-btn wpc-btn-primary"><?php echo esc_html__( "Ok", "wpcafe" );?></button>
                        <button class="wpc-close wpc-btn"> X </button>
                        </div></div>`
                        );
                    }
                }
                });
            </script>
            <?php
        }
        if ( ( isset($settings['wpcafe_allow_cart'])
            && $settings['wpcafe_allow_cart'] == 'on' ) ) {
            $wpc_cart_icon  = !empty( $settings['wpc_mini_cart_icon'] ) ? $settings['wpc_mini_cart_icon'] : 'wpcafe-cart_icon';
            
            $custom_mini_cart = \Wpcafe::core_dir() ."/modules/mini-cart/views/custom-mini-cart.php";
            
            if( file_exists($custom_mini_cart) ){
                include_once $custom_mini_cart;
            }
        }

        // menu of the day
        if (!empty($settings['enable_special_menu']) && $settings['enable_special_menu'] == 'on') {
            $this->wpc_menu_off_the_popup($settings);
        }
        ?>
        <!-- After add to cart  message  -->
        <script type="text/javascript">
            jQuery( function($){
                var get_reserv_detials = localStorage.getItem('wpc_reservation_details');
                $('body').on('added_to_cart', function(event, fragments, cart_hash, button){
                    $('.wpc-cart-message').fadeIn().delay(3000).fadeOut();
                    // pass data to show in reservation details
                    if ( typeof food_details_reservation !=="undefined" &&
                    typeof get_reserv_detials !=="undefined" && get_reserv_detials !== null && typeof button !=="undefined"
                    ) {

                        var product_id    = button.data('product_id'),   // Get the product id
                            product_name  = button.data('product_name'), // Get the product name
                            product_price = button.data('product_price'); // Get the product price

                        food_details_reservation({product_id:product_id,product_name:product_name,product_price:product_price ,
                         } , $  )
                    }
                }); 
            });
        </script>

        <?php
    }

    /**
     * Cart count  function
     */
    public function wpc_add_to_cart_count_fragment_refresh($fragments){
        ob_start();
        ?>
					<span class="wpc-mini-cart-count">
							<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
					</span>
        <?php
        $fragments['.wpc-mini-cart-count'] = ob_get_clean();

        return $fragments;
    }

    /**
     * Cart count  function
     */
    public function wpc_add_to_cart_content_fragment_refresh($fragments){
        ob_start();
        ?>
        <div class="widget_shopping_cart_content">
            <?php
							if(file_exists(\Wpcafe::core_dir().'/modules/mini-cart/views/mini-cart-template.php')){
								include_once \Wpcafe::core_dir().'/modules/mini-cart/views/mini-cart-template.php';
							}
            ?>
        </div>
        <?php
        $fragments['div.widget_shopping_cart_content'] = ob_get_clean();

        return $fragments;
    }

    /**
     * Location field in checkout form
     */
    public function wpc_location_checkout_form(){
        $checkout = WC()->checkout;
        ?>
        <div id="wpc_location_field">
            <div class="location_heading"><?php echo esc_html__('Food Order Location', 'wpcafe');?></div>
            <div class="wpc_location_name"></div>
            <input type="hidden" name="wpc_location_name" class="wpc_location_name" />
        </div> 
        <?php
    }

    /**
     * Update location select option
     *
     * @param [type] $order
     */
    public function wpc_location_update_meta($order){

        if (sanitize_text_field(isset($_POST['wpc_location_name'])) && !empty(sanitize_text_field($_POST['wpc_location_name']))) {
            $order->update_meta_data('wpc_location_name', sanitize_text_field($_POST['wpc_location_name']));
        }
    }

    /**
     * Category new field for set priority
     */
    public function wpc_product_cat_taxonomy_add_new_meta_field(){
    ?>
        <div class="form-field">
            <label for="wpc_menu_order_priority"><?php esc_html_e('Order menu', 'wpcafe'); ?></label>
            <input type="text" name="wpc_menu_order_priority" id="wpc_menu_order_priority">
        </div>
    <?php
    }

    /**
     * Category edit field for set priority
     */
    public function wpc_product_cat_taxonomy_edit_meta_field($term){
        //getting term ID
        $term_id                 = $term->term_id;
        $wpc_menu_order_priority = get_term_meta($term_id, 'wpc_menu_order_priority', true);
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="wpc_menu_order_priority"><?php esc_html_e('Order menu', 'wpcafe'); ?></label></th>
            <td>
                <input type="text" name="wpc_menu_order_priority" id="wpc_menu_order_priority" value="<?php echo esc_attr($wpc_menu_order_priority) ? esc_attr($wpc_menu_order_priority) : ''; ?>">
            </td>
        </tr>
        <?php
    }

    /**
     * Category save field for set priority
     */
    public function wpc_product_cat_taxonomy_save_meta_field($term_id){
        $wpc_menu_order_priority = filter_input(INPUT_POST, 'wpc_menu_order_priority');
        update_term_meta($term_id, 'wpc_menu_order_priority', $wpc_menu_order_priority);
    }

    /**
     * Order menu column added to category admin screen.
     */
    public function wpc_custom_fields_list_title($columns){
        $columns['wpc_menu_order_priority'] = esc_html__('Order menu', 'wpcafe');
        $columns['cat_id']                  = esc_html__('ID', 'wpcafe');
        return $columns;
    }

    /**
     * Order menu column value added to product category admin screen.
     */
    public function wpc_custom_fields_list_diplay($columns, $column, $id){
        if ('wpc_menu_order_priority' == $column) {
            $columns = esc_html(get_term_meta($id, 'wpc_menu_order_priority', true));
        } elseif ('cat_id' == $column) {
            $columns = esc_html($id);
        }

        return $columns;
    }

    /**
     * Custom inline css
     */
    public function wpc_custom_inline_css(){
        if (!class_exists('WooCommerce')) {
            return;
        }
        $settings       = $this->settings_obj;
        $template       = \Wpcafe::core_dir() . "modules/mini-cart/mini-cart.php";

        if( file_exists( $template ) ){
            include_once $template;
        }
    }

    /**
     * Food by location
     */
    public function food_location_menu( $atts ){
        if (!class_exists('Woocommerce')) {
            return;
        }

        ob_start();
        $unique_id = md5(md5(microtime()));
        $product_data               = $atts;
        $product_data['unique_id']  = $unique_id ;
        $product_data['wpc_menu_col']  = 'wpc-col-md-8' ;
        
        // shortcode option
        $atts = extract(shortcode_atts(
            [
                'wpc_food_categories'   => '',
                'style'                 => 'style-1',
                'no_of_product'         => 5,
                'show_thumbnail'        => "yes",
                'wpc_cart_button'       => 'yes',
                'wpc_price_show'       => 'yes',
                'title_link_show'       => 'yes',
                'wpc_menu_col'          => '6',
                'wpc_show_desc'         => 'yes',
                'wpc_desc_limit'        => '15',
                'live_search'           => 'yes',
                'wpc_delivery_time_show'=> 'yes',
                'show_item_status'      => 'yes',
                'wpc_menu_order'        => 'DESC',
                'wpc_nav_position'      => 'top',
                'location_alignment '   => 'center'
            ], $atts ));

         
        $location_alignment = "center";

        $products = wc_get_products([]);

        if ( file_exists( \WpCafe::plugin_dir() . "core/shortcodes/views/food-menu/location-select.php" ) ) {
            ?>
            <div class="location_menu" data-product_data ="<?php echo esc_attr( json_encode( $product_data  ));?>">
                <?php include_once \WpCafe::plugin_dir() . "core/shortcodes/views/food-menu/location-select.php"; ?>
            </div>
            <?php
        }
        
        return ob_get_clean();
    }

    /**
     * Create a shortcode to render the food location filter.
     * Print the food location filter HTML code.
     */
    public function food_location_filter($atts){
        ob_start();

        Wpc_Utilities::select_food_locations_filter($atts);

        return ob_get_clean();
    }
    
    /**
	 * add extra content like tax, shipping, total only in minicart
	 *
	 * @return void
	 */
	public function before_minicart_buttons_add_extra_content() {  
        $cart_obj = WC()->cart; 
 		if ( ! empty( $cart_obj ) ) {
		?>
		<div class="wpc-minicart-extra">
			<div class="wpc-minicart-extra-total">
				<span><?php echo esc_html__( 'Total', 'wpcafe' ); ?> <span class="wpc-extra-text"><?php echo esc_html__('(including all charges)', 'wpcafe'); ?></span></span>
                <b class="wpc-minicart-total"><?php echo wp_kses(wc_price( $cart_obj->total ), array('span'=>array(), 'small'=> array(), 'a'=>array(), 'bdi'=>array(), 'del'=>array())); ?></b>
			</div>
		</div>
		<?php
		}
	}

	/**
     * Location field in checkout form for gutenblock shortcode
     */
    public function wpc_location_checkout_block(){
        $checkout = WC()->checkout;
	ob_start();
        ?>
        <div id="wpc_location_field">
            <div class="location_heading"><?php echo esc_html__('Food Order Location', 'wpcafe');?></div>
            <div class="wpc_location_name"></div>
            <input type="hidden" name="wpc_location_name" class="wpc_location_name" />
        </div> 
        <?php

	return ob_get_clean();

    }

}
