<?php
namespace WpCafe\Core\Settings;
defined( "ABSPATH" ) || exit;

use WpCafe\Utils\Wpc_Utilities;

class Wpc_Key_Options extends \WpCafe\Core\Base\Config {

    use \WpCafe\Traits\Wpc_Singleton;

    public $wpc_settings_field;

   
    /**
     * Settings field
     *
     * @return void
     */
    public function wpc_key_options() {
        if (isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'reservation_details') {
            apply_filters('wpcafe/key_options/reservation_details','wpc_pro_reservation_details');
        } else {
            ?>
            <div class="wpc-settings">
                    <div id="setting_message" class="hide_field"></div>
                    <?php
                        $visit          = esc_html__('Visit the', 'wpcafe');
                        $documentation  = esc_html__('documentation', 'wpcafe');
                        $schedule_text  = esc_html__('reservation settings section for reservation schedule of your restaurant.', 'wpcafe');
                        $menu_text      = esc_html__(' for food menu options of your restaurant.', 'wpcafe');
                        $sched_doc_link = Wpc_Utilities::wpc_kses( '<a href="https://support.themewinter.com/docs/plugins/wp-cafe/reservation-form/" target="_blank" class="doc-link">'. $documentation .'</a> ' );
                        $fmenu_doc_link = Wpc_Utilities::wpc_kses( '<a href="https://support.themewinter.com/docs/plugins/wp-cafe/food-menu/" target="_blank" class="doc-link"> '. $documentation .'</a> ' );
                        // show tab
                        $menu_icon = '<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.9913 16.4992V17.4994C19.9913 19.1498 19.9913 20.5 16.9926 20.5H2.99869C-2.38315e-07 20.5 0 19.1498 0 17.4994V16.4992C0 15.9491 0.449804 15.499 0.999564 15.499H18.9917C19.5415 15.499 19.9913 15.9491 19.9913 16.4992Z" fill="#77797E"/>
                            <path d="M12.434 3.67652C12.484 3.47648 12.514 3.28644 12.5239 3.0764C12.5539 1.91616 11.8442 0.895947 10.7247 0.595885C9.04546 0.13579 7.52612 1.39605 7.52612 2.99638C7.52612 3.23643 7.55611 3.45647 7.61608 3.67652C4.00765 4.44668 1.29883 7.65734 1.29883 11.4981V12.9984C1.29883 13.5485 1.74863 13.9986 2.29839 13.9986H17.7417C18.2914 13.9986 18.7412 13.5485 18.7412 12.9984V11.4981C18.7412 7.65734 16.0424 4.45668 12.434 3.67652ZM13.0237 10.2479H7.02634C6.61652 10.2479 6.27666 9.9078 6.27666 9.49772C6.27666 9.08763 6.61652 8.74756 7.02634 8.74756H13.0237C13.4336 8.74756 13.7734 9.08763 13.7734 9.49772C13.7734 9.9078 13.4336 10.2479 13.0237 10.2479Z" fill="#77797E"/>
                            </svg>
                            ';
                        $reservation_icon = '<svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.2442 2.22651V0.722892C13.2442 0.327711 12.9167 0 12.5218 0C12.1269 0 11.7994 0.327711 11.7994 0.722892V2.16868H5.53832V0.722892C5.53832 0.327711 5.21081 0 4.81589 0C4.42096 0 4.09346 0.327711 4.09346 0.722892V2.22651C1.49272 2.46747 0.230881 4.01928 0.0382332 6.3229C0.0189684 6.60241 0.250145 6.83374 0.519852 6.83374H16.8178C17.0972 6.83374 17.3283 6.59278 17.2994 6.3229C17.1068 4.01928 15.8449 2.46747 13.2442 2.22651Z" fill="#77797E"/>
                            <path d="M16.3751 8.2793H0.96324C0.433458 8.2793 0 8.71303 0 9.24315V15.1805C0 18.0721 1.44486 19.9998 4.8162 19.9998H12.5221C15.8935 19.9998 17.3383 18.0721 17.3383 15.1805V9.24315C17.3383 8.71303 16.9049 8.2793 16.3751 8.2793ZM5.98172 16.3468C5.8854 16.4335 5.77944 16.501 5.66385 16.5492C5.54826 16.5974 5.42304 16.6263 5.29782 16.6263C5.1726 16.6263 5.04738 16.5974 4.93179 16.5492C4.8162 16.501 4.71024 16.4335 4.61392 16.3468C4.44054 16.1636 4.33458 15.913 4.33458 15.6624C4.33458 15.4118 4.44054 15.1612 4.61392 14.9781C4.71024 14.8914 4.8162 14.8239 4.93179 14.7757C5.16297 14.6793 5.43267 14.6793 5.66385 14.7757C5.77944 14.8239 5.8854 14.8914 5.98172 14.9781C6.1551 15.1612 6.26106 15.4118 6.26106 15.6624C6.26106 15.913 6.1551 16.1636 5.98172 16.3468ZM6.184 12.6552C6.13584 12.7709 6.06841 12.8769 5.98172 12.9733C5.8854 13.06 5.77944 13.1275 5.66385 13.1757C5.54826 13.2239 5.42304 13.2528 5.29782 13.2528C5.1726 13.2528 5.04738 13.2239 4.93179 13.1757C4.8162 13.1275 4.71024 13.06 4.61392 12.9733C4.52723 12.8769 4.4598 12.7709 4.41164 12.6552C4.36348 12.5395 4.33458 12.4142 4.33458 12.2889C4.33458 12.1636 4.36348 12.0383 4.41164 11.9227C4.4598 11.807 4.52723 11.701 4.61392 11.6046C4.71024 11.5179 4.8162 11.4504 4.93179 11.4022C5.16297 11.3058 5.43267 11.3058 5.66385 11.4022C5.77944 11.4504 5.8854 11.5179 5.98172 11.6046C6.06841 11.701 6.13584 11.807 6.184 11.9227C6.23216 12.0383 6.26106 12.1636 6.26106 12.2889C6.26106 12.4142 6.23216 12.5395 6.184 12.6552ZM9.35306 12.9733C9.25674 13.06 9.15078 13.1275 9.03519 13.1757C8.9196 13.2239 8.79438 13.2528 8.66916 13.2528C8.54394 13.2528 8.41872 13.2239 8.30313 13.1757C8.18754 13.1275 8.08158 13.06 7.98526 12.9733C7.81188 12.7901 7.70592 12.5395 7.70592 12.2889C7.70592 12.0383 7.81188 11.7877 7.98526 11.6046C8.08158 11.5179 8.18754 11.4504 8.30313 11.4022C8.53431 11.2962 8.80401 11.2962 9.03519 11.4022C9.15078 11.4504 9.25674 11.5179 9.35306 11.6046C9.52644 11.7877 9.6324 12.0383 9.6324 12.2889C9.6324 12.5395 9.52644 12.7901 9.35306 12.9733Z" fill="#77797E"/>
                            </svg>';
                        $style_icon = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.9923 13.9991C11.9923 15.7691 11.2228 17.3691 9.99359 18.4591C8.93427 19.4191 7.53517 19.9991 5.99615 19.9991C2.68828 19.9991 0 17.3091 0 13.9991C0 11.9745 1.01312 10.1808 2.55331 9.09672C2.80151 8.92202 3.13737 9.04145 3.2713 9.31383C4.21463 11.2324 5.95049 12.669 8.01486 13.2291C8.64446 13.4091 9.30403 13.4991 9.99359 13.4991C10.4803 13.4991 10.9466 13.4529 11.3997 13.3679C11.6881 13.3138 11.9751 13.4986 11.9878 13.7918C11.9908 13.8612 11.9923 13.9305 11.9923 13.9991Z" fill="#77797E"/>
                                <path d="M15.9904 6C15.9904 6.78 15.8405 7.53 15.5706 8.21C14.8811 9.95 13.402 11.29 11.5732 11.79C11.0735 11.93 10.5438 12 9.9942 12C9.44455 12 8.91489 11.93 8.41521 11.79C6.58639 11.29 5.10734 9.95 4.41778 8.21C4.14795 7.53 3.99805 6.78 3.99805 6C3.99805 2.69 6.68632 0 9.9942 0C13.3021 0 15.9904 2.69 15.9904 6Z" fill="#77797E"/>
                                <path d="M19.9866 13.9991C19.9866 17.3091 17.2983 19.9991 13.9904 19.9991C13.2464 19.9991 12.5306 19.8633 11.8728 19.6131C11.5543 19.492 11.4949 19.0843 11.7166 18.8254C12.8579 17.493 13.4907 15.7851 13.4907 13.9991C13.4907 13.6591 13.4608 13.3191 13.4108 12.9991C13.3811 12.8146 13.4752 12.6333 13.6386 12.5427C14.9617 11.8097 16.0421 10.6867 16.7155 9.31468C16.8494 9.04198 17.1855 8.92226 17.4338 9.09713C18.9737 10.1813 19.9866 11.9748 19.9866 13.9991Z" fill="#77797E"/>
                                </svg>';
                        $general_icon = '<svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.1637 7.55474C17.2473 7.55474 16.4638 6.19865 17.4167 4.53532C17.9673 3.57123 17.639 2.34227 16.6756 1.79136L14.8439 0.742508C14.0075 0.244569 12.9275 0.541214 12.4299 1.37818L12.3135 1.57947C11.3606 3.2428 9.79359 3.2428 8.83011 1.57947L8.71365 1.37818C8.2372 0.541214 7.15726 0.244569 6.32083 0.742508L4.48917 1.79136C3.52569 2.34227 3.19747 3.58182 3.74803 4.54592C4.71151 6.19865 3.92802 7.55474 2.01166 7.55474C0.910539 7.55474 0 8.45527 0 9.56769V11.4323C0 12.5341 0.899951 13.4453 2.01166 13.4453C3.92802 13.4453 4.71151 14.8013 3.74803 16.4647C3.19747 17.4288 3.52569 18.6577 4.48917 19.2086L6.32083 20.2575C7.15726 20.7554 8.2372 20.4588 8.73482 19.6218L8.85128 19.4205C9.80418 17.7572 11.3711 17.7572 12.3346 19.4205L12.4511 19.6218C12.9487 20.4588 14.0287 20.7554 14.8651 20.2575L16.6967 19.2086C17.6602 18.6577 17.9884 17.4182 17.4379 16.4647C16.4744 14.8013 17.2579 13.4453 19.1743 13.4453C20.2754 13.4453 21.1859 12.5447 21.1859 11.4323V9.56769C21.1753 8.46586 20.2754 7.55474 19.1637 7.55474ZM10.5877 13.9432C8.69247 13.9432 7.14667 12.3964 7.14667 10.5C7.14667 8.60359 8.69247 7.0568 10.5877 7.0568C12.4829 7.0568 14.0287 8.60359 14.0287 10.5C14.0287 12.3964 12.4829 13.9432 10.5877 13.9432Z" fill="#77797E"/>
                                </svg>';

                        $settings_tabs = array(
                            'menu_settings' => [ 'name' => esc_html__('Food Ordering', 'wpcafe') , 'icon' => $menu_icon],
                            'schedule'      => [ 'name' => esc_html__('Reservation', 'wpcafe') , 'icon' =>  $reservation_icon],
                            'notification'  => [ 'name' => esc_html__('Style', 'wpcafe'), 'icon' => $style_icon],
                            'key_options'   => [ 'name' => esc_html__('General Settings', 'wpcafe'), 'icon' => $general_icon ],
                        );
                        $wpc_doc_link = array(
                            'schedule'      => $visit .' '.$sched_doc_link .' '. $schedule_text .'',
                            'menu_settings' => $visit .' '.$fmenu_doc_link .' '. $menu_text .'',
                        );
                        $tab_arr    = [ $settings_tabs , $wpc_doc_link ];

                        $settings   =  \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();

                        if( isset( $_GET['settings-tab'] ) ){
                            $recent_tab = $_GET['settings-tab'];
                        }else{
                            $recent_tab = "menu_settings";
                        }

                    ?>
                    <form method='post' class='wpc_pb_two wpc_tab_content' id='wpc_settings_form' >
                        <!-- header start -->
                        <?php include_once \Wpcafe::core_dir() . "settings/layout/header.php"; ?>
                        <!-- header end -->

                        <?php  if ( isset($_GET['saved']) && sanitize_text_field($_GET['saved']) == 1 ) { ?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php echo esc_html__("Your settings have been saved","wpcafe")?></p>
                            </div>
                        <?php } ?>
                        
                        <div class="wrap">
                            <ul class="wpc-settings-tab nav nav-tabs wpc-tab">
                                <?php
                                $filtered_tab = apply_filters('wpcafe/key_options/settings_tab_item', $tab_arr );

                                if( isset( $filtered_tab['settings_tab']) ){
                                    $tabs = $filtered_tab['settings_tab'][0];
                                    $wpc_doc_link = $filtered_tab['settings_tab'][1];
                                }else{
                                    $tabs = $tab_arr[0];
                                }

                                $i=0;
                                foreach ($tabs as $key => $value){
                                    $i++;
                                    ?>
                                    <li>
                                        <a href="#" class="nav-tab <?php echo esc_html($recent_tab) == $key ? 'nav-tab-active': '';?>" data-id="<?php echo esc_html($key) ?>">
                                            <?php echo wp_kses_post( $value['icon'] ); ?>
                                            <span>
                                                <?php 
                                                    echo esc_html( $value['name']); 
                                                ?>
                                            </span>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <div class="tab-content settings-content-wraps <?php echo esc_attr($recent_tab); ?>">
                                <?php
                                $week_days = ['Sat','Sun','Mon','Tue','Wed','Thu','Fri'];

                                $get_data                                     = apply_filters('wpcafe/key_options/menu_settings', $settings);
                                $wpc_reservation_form_display_page            =  (isset($settings['wpc_reservation_form_display_page'] ) ?  $settings['wpc_reservation_form_display_page'] : '');
                                $wpcafe_food_location                         =  isset($settings['wpcafe_food_location']) ? "checked" : "";
                                $wpcafe_allow_cart                            =  (! isset($settings['wpcafe_allow_cart'] ) || isset($settings['wpcafe_allow_cart'] ) && $settings['wpcafe_allow_cart'] == 'on' ) ? 'on' : 'off';
                                $minicart_style                               = isset($settings['minicart_style']) ? $settings['minicart_style'] : 'style-1';

                                $show_branches                                =  isset($settings['show_branches'] )   ? "checked" : "";
                                $wpc_checked_allow_cancellation               =  (! isset($settings['wpc_allow_cancellation'] ) || isset($settings['wpc_allow_cancellation'] ) && $settings['wpc_allow_cancellation'] == 'on' ) ? 'on' : 'off';
                                $checked_require_phone                        =  (isset($settings['wpc_require_phone']) ? "checked" : "");
                                $checked_require_branch                       =  (isset($settings['require_branch']) ? "checked" : "");
                                $allow_admin_notif_book_req                   =  (! isset($settings['wpc_admin_notification_for_booking_req'] ) || isset($settings['wpc_admin_notification_for_booking_req'] ) && $settings['wpc_admin_notification_for_booking_req'] == 'on' )  ? 'on' : 'off';
                                $allow_user_notif_book_req                    =  (! isset($settings['wpc_user_notification_for_booking_req'] ) || isset($settings['wpc_user_notification_for_booking_req'] ) && $settings['wpc_user_notification_for_booking_req'] == 'on' )  ? 'on' : 'off';

                                $admin_notif_confirm_book                     =  (isset($get_data['notification_settings']['admin_notif_confirm_book']) ? $get_data['notification_settings']['admin_notif_confirm_book'] : 'off');
                                $user_notif_confirm_book                      =  (isset($get_data['notification_settings']['user_notif_confirm_book']) ? $get_data['notification_settings']['user_notif_confirm_book'] : 'off');

                                $admin_notif_cancel_req                       =  (isset($get_data['notification_settings']['admin_notif_cancel_req']) ? $get_data['notification_settings']['admin_notif_cancel_req'] : 'off');
                                $user_notif_cancel_req                        =  (isset($get_data['notification_settings']['user_notif_cancel_req']) ? $get_data['notification_settings']['user_notif_cancel_req'] : 'off');
                                $reserve_dynamic_message                      =  (isset($settings['reserve_dynamic_message']) ? $settings['reserve_dynamic_message'] : '');
                                $wpc_mini_empty_cart_link                      = ((isset($settings['wpc_mini_empty_cart_link']) && !empty($settings['wpc_mini_empty_cart_link'])) ? $settings['wpc_mini_empty_cart_link'] : get_site_url());

                                
                                $wpc_booking_confirmed =  esc_html__('Thank you for booking. Your booking is confirmed. Please check your email.', 'wpcafe');
                                $wpc_booking_confirmed_message = $wpc_booking_confirmed;
                                $wpc_pending = esc_html__('Thank you for booking. Your booking is pending. Please check your email.', 'wpcafe');
                                $wpc_pending_message = $wpc_pending;
                                if ( isset($settings['wpc_pending_message'])) {
                                    if ($settings['wpc_pending_message'] == '') {
                                        $wpc_pending_message = $wpc_pending;
                                    }else {
                                        $wpc_pending_message = $settings['wpc_pending_message'];
                                    }
                                }

                                if ( isset($settings['wpc_booking_confirmed_message'])) {
                                    if ($settings['wpc_booking_confirmed_message'] == '') {
                                        $wpc_booking_confirmed_message = $wpc_booking_confirmed;
                                    }else {
                                        $wpc_booking_confirmed_message = $settings['wpc_booking_confirmed_message'];
                                    }
                                }
                                ?>
                                <div class="wpc_pb_two wpc_tab_content">                    
                                    <input type="hidden" name="settings_tab" class="settings_tab" value="<?php echo esc_attr( $recent_tab ); ?>"/>
                                    <?php
                                    foreach ($tabs as $item => $content) {
                                        $active_class = (  ( $item == $recent_tab ) ? 'active' : '' );
                                        if ( in_array( $item, array_keys( $settings_tabs ) ) ) {
                                            ?>
                                            <div id='<?php echo esc_attr( $item );?>' data-id='tab_<?php echo esc_attr( $item ); ?>' class='tab-pane <?php echo esc_attr( $active_class );?>'>
                                                <?php
                                            
                                                //Schedule
                                                if ( $item == 'schedule' && file_exists(  \Wpcafe::core_dir() ."settings/part/schedule.php" ) ) {
                                                    include_once \Wpcafe::core_dir() ."settings/part/schedule.php";
                                                } elseif ( $item == 'key_options' && file_exists(  \Wpcafe::core_dir() ."settings/part/key-options.php" ) ) {
                                                    include_once \Wpcafe::core_dir() ."settings/part/key-options.php";                                       
                                                } elseif ( $item == 'notification'  && file_exists(  \Wpcafe::core_dir() ."settings/part/notifications.php" ) ) {
                                                    include_once \Wpcafe::core_dir() ."settings/part/notifications.php";
                                                } elseif ( $item == 'menu_settings' ) {
                                                    ?>
                                                    <div class="wpc-tab-wrapper wpc-tab-style2">
                                                        <ul class="wpc-nav mb-30">
                                                            <li>
                                                                <a class="wpc-tab-a wpc-active" data-id="food-menu-option">
                                                                    <?php echo esc_html__('General Settings', 'wpcafe'); ?>
                                                                    <svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/></svg>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="wpc-tab-a" data-id="minicart-option">
                                                                    <?php echo esc_html__('Minicart', 'wpcafe'); ?>
                                                                </a>
                                                            </li>

                                                            <?php if ( class_exists('Wpcafe_Pro') ) { ?>
                                                                <?php if( !empty( $get_data['live_order_notification'] ) && file_exists( $get_data['live_order_notification'] )){ ?>
                                                                    <li>
                                                                        <a class="wpc-tab-a" data-id="live-order-notification">
                                                                            <?php echo esc_html__('Live Order', 'wpcafe'); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>

                                                                <?php  if( !empty( $get_data['tip_settings'] ) && file_exists( $get_data['tip_settings'] )){ ?>
                                                                    <li>
                                                                        <a class="wpc-tab-a" data-id="tip-option">
                                                                            <?php echo esc_html__('Tipping', 'wpcafe'); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>

                                                                <?php  if( !empty( $get_data['discount_settings'] ) && file_exists( $get_data['discount_settings'] )){ ?>
                                                                    <li>
                                                                        <a class="wpc-tab-a" data-id="discount-option">
                                                                            <?php echo esc_html__('Discount', 'wpcafe'); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                                <?php  if( !empty( $get_data['special_menus'] ) && file_exists( $get_data['special_menus'] )){ ?>
                                                                    <li>
                                                                        <a class="wpc-tab-a" data-id="special_menus">
                                                                            <?php echo esc_html__('Special Menus', 'wpcafe'); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                                <?php  if( !empty( $get_data['pickup_settings'] ) && file_exists( $get_data['pickup_settings'] )){ ?>
                                                                    <li>
                                                                        <a class="wpc-tab-a" data-id="pickup_option">
                                                                            <?php echo esc_html__('Pickup', 'wpcafe'); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                                <?php  if( !empty( $get_data['delivery_settings'] ) && file_exists( $get_data['delivery_settings'] )){ ?>
                                                                    <li>
                                                                        <a class="wpc-tab-a" data-id="delivery_option">
                                                                            <?php echo esc_html__('Delivery', 'wpcafe'); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                                <!-- qr code menu -->
                                                                <?php  if( !empty( $get_data['qrcode_settings'] ) && file_exists( $get_data['qrcode_settings'] )){ ?>
                                                                    <li>
                                                                        <a class="wpc-tab-a" data-id="qrcode-option">
                                                                            <?php echo esc_html__('QR Code', 'wpcafe'); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>

                                                            <?php } ?>
                                                        </ul>
                                                        <div class="wpc-tab-content">
                                                            <!-- food menu Settings options -->
                                                            <div class="wpc-tab wpc-active" data-id="food-menu-option">
                                                                <?php do_action('wpc_before_admin_location_settings');?>
                                                                <?php
                                                                $markup_fields_one = [
                                                                    'wpcafe_food_location' => [
                                                                        'item' => [
                                                                            'options'  =>['on'=>'on'],
                                                                            'label'    => esc_html__( 'Allow Location', 'wpcafe' ),
                                                                            'desc'     => esc_html__( 'Show location pop-up on front-end to get user location for food delivery', 'wpcafe' ),
                                                                            'type'     => 'checkbox',
                                                                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                                                                        ],
                                                                        'data' => [ 'wpcafe_food_location' => $wpcafe_food_location ],
                                                                    ],

                                                                ];

                                                                foreach ( $markup_fields_one as $key => $info ) {
                                                                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                                                                }

                                                                do_action('wpc_after_admin_location_settings');
                                                                
                                                                //render menu settings
                                                                if ( class_exists('Wpcafe_Pro') ) {
                                                                    if( !empty( $get_data['menu_settings'] ) && file_exists( $get_data['menu_settings'] )){
                                                                        include_once $get_data['menu_settings'];
                                                                        foreach ( $markup_fields_menu as $key => $info ) {
                                                                            $this->get_field_markup( $info['item'], $key, $info['data'] );
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </div>

                                                            <div class="wpc-tab" data-id="minicart-option">
                                                                <?php

                                                                $mini_cart_icon = isset($settings['wpc_mini_cart_icon'] ) ? $settings['wpc_mini_cart_icon'] : '';

                                                                $markup_fields_two = [
                                                                    'wpcafe_allow_cart' => [
                                                                        'item' => [
                                                                            'options'  =>['off'=>'off','on'=>'on'],
                                                                            'label'    => esc_html__( 'Allow Cart', 'wpcafe' ),
                                                                            'desc'     => esc_html__( 'Show cart on the frontend', 'wpcafe' ),
                                                                            'type'     => 'checkbox',
                                                                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                                                                        ],
                                                                        'data' => [ 'wpcafe_allow_cart' => $wpcafe_allow_cart ],
                                                                    ],

                                                                    'minicart_style' => [
                                                                        'item' => [
                                                                            'label'    => esc_html__( 'Mini Cart Style', 'wpcafe' ),
                                                                            'desc'     => esc_html__( 'You can choose mini cart style', 'wpcafe' ),
                                                                            'type'     => 'select_single',
                                                                            'options'  => [ 
                                                                                'style-1'   => esc_html__( 'Style 1', 'wpcafe' ),
                                                                                'style-2'   => esc_html__( 'Style 2', 'wpcafe' ),
                                                                            ],
                                                                            'attr'     => [
                                                                                'class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'
                                                                            ],
                                                                        ],
                                                                        'data' => [ 'minicart_style' => $minicart_style ],
                                                                    ],
                                                                    
                                                                    'wpc_mini_cart_icon' => [
                                                                        'item' => [
                                                                            'label'    => __( 'Mini-cart Icon', 'wpcafe' ),
                                                                            'desc'     => wp_kses_post( 'Icon class for mini cart. Any icon library which is available in your site will work. Example:  font-awesome, dash-icon etc. <a href="//support.themewinter.com/docs/plugins/wp-cafe/general-settings-2/#15-toc-title" target="_blank" >'. esc_html__('Documentation', 'wpcafe').'</a>' ),
                                                                            'type'     => 'text',
                                                                            'place_holder' => __('icon here', 'wpcafe'),
                                                                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                                                                            'div'  => ['class'=>'wpc-admin-settings-message', 'html'=> __( 'For instance : fa fa-shopping-cart', 'wpcafe'), 'id'=> '' ]
                                                                        ],
                                                                        'data' => [ 'wpc_mini_cart_icon' => $mini_cart_icon ],
                                                                    ],

                                                                    'wpc_mini_empty_cart_link' => [
                                                                        'item' => [
                                                                            'label'    => esc_html__( 'Mini Cart Empty Button Link', 'wpcafe' ),
                                                                            'desc'     => esc_html__( 'Mini Cart Empty Button Link', 'wpcafe' ),
                                                                            'type'     => 'text',
                                                                            'place_holder' => esc_html__('https://domain.com/menu/', 'wpcafe'),
                                                                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                                                                        ],
                                                                        'data' => [ 'wpc_mini_empty_cart_link' => $wpc_mini_empty_cart_link ],
                                                                    ],
                                                                ];

                                                                foreach ( $markup_fields_two as $key => $info ) {
                                                                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                                                                }
                                                                
                                                                ?>
                                                            
                                                            </div>

                                                            <?php if ( class_exists('Wpcafe_Pro') ) { ?>
                                                                <?php if( !empty( $get_data['live_order_notification'] ) && file_exists( $get_data['live_order_notification'] )){ ?>
                                                                    <div class="wpc-tab" data-id="live-order-notification">
                                                                    <div class="live-order-container">
                                                                        <?php
                                                                            include_once $get_data['live_order_notification'];
                                                                            foreach ( $markup_fields_order as $key => $info ) {
                                                                                $this->get_field_markup( $info['item'], $key, $info['data'] );
                                                                            }            
                                                                        ?>
                                                                    </div>
                                                                    </div>
                                                                <?php } 
                                                                
                                                                if( !empty( $get_data['tip_settings'] ) && file_exists( $get_data['tip_settings'] )){ ?>
                                                                    <div class="wpc-tab" data-id="tip-option">
                                                                        <?php
                                                                            include_once $get_data['tip_settings'];
                                                                        ?>
                                                                    </div>
                                                                <?php } 

                                                                    if( !empty( $get_data['discount_settings'] ) && file_exists( $get_data['discount_settings'] )){ ?>
                                                                    <div class="wpc-tab" data-id="discount-option">
                                                                        <?php  include_once $get_data['discount_settings']; ?>
                                                                    </div>
                                                                <?php }

                                                                if( !empty( $get_data['special_menus'] ) && file_exists( $get_data['special_menus'] )){ ?>
                                                                    <div class="wpc-tab" data-id="special_menus">
                                                                        <?php  include_once $get_data['special_menus']; ?>
                                                                    </div>
                                                                <?php }

                                                                if( !empty( $get_data['pickup_settings'] ) && file_exists( $get_data['pickup_settings'] )){ ?>
                                                                    <div class="wpc-tab" data-id="pickup_option">
                                                                        <?php  include_once $get_data['pickup_settings']; ?>
                                                                    </div>
                                                                <?php }

                                                                if( !empty( $get_data['delivery_settings'] ) && file_exists( $get_data['delivery_settings'] )){ ?>
                                                                    <div class="wpc-tab" data-id="delivery_option">
                                                                        <?php  include_once $get_data['delivery_settings']; ?>
                                                                    </div>
                                                                <?php }

                                                                if( !empty( $get_data['qrcode_settings'] ) && file_exists( $get_data['qrcode_settings'] )){ ?>
                                                                    <div class="wpc-tab" data-id="qrcode-option">
                                                                        <?php
                                                                            include_once $get_data['qrcode_settings'];
                                                                        ?>
                                                                    </div>
                                                                <?php } 
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        <?php }
                                    }

                                    apply_filters('wpcafe/key_options/tab_content', $settings,$wpc_doc_link,$recent_tab);
                                    
                                    ?>
                                    <div class="wpc_submit_wrap">
                                        <input type="hidden" name="wpcafe_settings_key_options_action" value="save">
                                        <input type="submit" name="submit" class="wpc_mt_two wpc-btn" value="<?php esc_attr_e('Save Changes', 'wpcafe'); ?>">
                                        <?php wp_nonce_field('wpcafe-settings-page', 'wpcafe-settings-page'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
            <?php
        }
    }


    /**
     * Short code View
     *
     * @return void
     */
    public function shortcode_menu_view() {
        ?>
        <div class="wpc-settings wpc-shortcode-setttings">
            <div class="tab-content">
            <?php include_once \Wpcafe::core_dir() . "settings/layout/header.php"; ?>
                <div class="wrap">
                    <div class='wpc-shortcode-inner-wrap'>
                        <?php
                        //hooks
                        if (  file_exists( \Wpcafe::core_dir() ."settings/part/hooks.php") ) {
                            include_once \Wpcafe::core_dir() ."settings/part/hooks.php";
                        }
                        ?>
                    </div>  
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * tools view
     *
     * @return void
     */
    public function tools_menu_view() {
        ?>
        <div class="wpc-settings wpc-tools-setttings">
            <?php include_once \Wpcafe::core_dir() . "settings/layout/header.php"; ?>
            <div class="wrap">
                <div class='wpc-tools-inner-wrap'>
                    <?php
                    $enable_table_layout = '';
                    $enable_delivery_module =  class_exists( 'Wpcafe_Pro' ) ? 'checked' : '';
                    if ( class_exists( 'Wpcafe_Pro' ) ) {
                        $enable_table_layout = \WpCafe_Pro\Utils\Utilities::is_table_layout_enabled();
                    }

                    //hooks
                    if ( file_exists( \Wpcafe::core_dir() ."settings/part/tools.php") ) {
                        include_once \Wpcafe::core_dir() ."settings/part/tools.php";
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}

