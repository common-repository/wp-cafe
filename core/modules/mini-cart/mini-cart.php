<?php
        $wpc_custom_css = '';

        // cart bg color
        if (isset($settings['wpc_primary_color'])) {
            $bg_color = $settings['wpc_primary_color'] ? $settings['wpc_primary_color'] : "#8d29ff ";
            $wpc_custom_css .= '.wpc_background_color { background-color : ' . esc_attr($bg_color) . '}';
            //button
            $wpc_custom_css .= '
                .wpc_cart_block .wpc_background_color a.button.wc-forward,
                .wpc-food-menu-item .wpc-food-inner-content .wpc-menu-tag li,
                .wpc_cart_block .wpc_cart_icon,
                .picker__holder .picker__box,
                .wpc-food-menu-item .wpc-add-to-cart a,
                .wpc-reservation-field.date.wpc-reservation-calender-field,
                .wpc-category-list-style1 .wpc-category-title a, .wpc-category-list-style3 .wpc-category-title a,
                .wpc-reservation-form .wpc_reservation_user_info,
                body.woocomerce-layout-override-enable.woocommerce-cart .woocommerce .button,
                body.woocomerce-layout-override-enable .woocommerce button.button.alt,
                body.woocomerce-layout-override-enable.archive .products .product .button,
                body.woocomerce-layout-override-enable.archive .products .product .added_to_cart,
                body.woocomerce-layout-override-enable.single-product .products .product .button,
                body.woocomerce-layout-override-enable.single-product .products .product .added_to_cart,
                body.woocomerce-layout-override-enable.woocommerce #respond input#submit,
                .wpc-tab-with-slider .swiper-button-next, .wpc-tab-with-slider .swiper-button-prev, 
                .wpc-food-menu-slider .swiper-button-next, .wpc-food-menu-slider .swiper-button-prev,
                .wpc-food-menu-item .wpc-menu-tag li,
                .reserv-with-food-wrap .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar,
                body.woocomerce-layout-override-enable.woocommerce button.button.alt,
                body.woocomerce-layout-override-enable.single-product div.product .woocommerce-tabs ul li.active,
                body.woocomerce-layout-override-enable.archive .woocommerce-pagination li span.current,
                 body.woocomerce-layout-override-enable.archive .woocommerce-pagination li a.current,
                 .wpc-reservation-pro-wrap .wpc-nav li a.wpc-tab-a.wpc-active,
                 .action-button.wpc-btn,
                 .wpc-content-area .wpc-tab li a.wpc-active,
                 .wpc-minicart-wrapper .wpc_cart_icon,
                 .reserv-with-food-menu-wrap .wpc-food-tab-wrapper .wpc-nav li a.wpc-active,
                body.woocomerce-layout-override-enable.woocommerce-cart .woocommerce .shop_table a.remove,
                .wpc-btn, .attr-btn-primary, 
                .reservation_form_submit.wpc-btn,
                .cancell_form_submit.wpc-btn,
                .wpc-settings-dashboard .button-primary,
                .wpc-btn,
                .wpc-minicart-wrapper .wpc-minicart-header,
                .wpc-minicart-wrapper .wpc-field-wrap .dot-shadow,
                .wpc-minicart-wrapper.style2 .wpc_cart_icon,
                .wpc-minicart-wrapper.style2 .wpc_cart_icon .basket-item-count,
                .wpc_pro_order_tip_wrapper .wpc-btn {
                     background-color : ' . esc_attr($bg_color) . '
                }
                .wpc-minicart-wrapper .wpc-field-wrap input[type=radio] {
                    border-color:' . esc_attr($bg_color) . '
                }
              
                
                .wpc-food-menu-item .wpc-food-inner-content .wpc-menu-currency,
                .wpc-food-menu-item .wpc-food-inner-content .wpc-post-title a:hover,
                .wpc_cart_block .woocommerce-mini-cart li a,
              
                body.woocomerce-layout-override-enable.archive .products .product .price,
                body.woocomerce-layout-override-enable.single-product .products .product .price,
                .wpc-reservation-success .success-title1,
                body.woocomerce-layout-override-enable.single-product .products .product .woocommerce-loop-product__title:hover,
                body.woocomerce-layout-override-enable .products .product .woocommerce-loop-product__title:hover,
                body.woocomerce-layout-override-enable.woocommerce-cart .woocommerce .shop_table tbody td.product-name a:hover,
                .woocomerce-layout-override-enable.woocommerce-checkout .woocommerce table.shop_table.woocommerce-checkout-review-order-table tfoot tr.order-total th,
                 .woocomerce-layout-override-enable.woocommerce-checkout .woocommerce table.shop_table.woocommerce-checkout-review-order-table tfoot tr.order-total td,
                .wpc-food-tab-wrapper .wpc-nav li a.wpc-active,
                body.woocomerce-layout-override-enable.woocommerce-cart .woocommerce .shop_table tbody td.product-name a,
                body.woocomerce-layout-override-enable.woocommerce-checkout .woocommerce .shop_table tbody td.product-name a {
                    color : ' . esc_attr($bg_color) . '
                }
                .wpc-reservation-field.date .flatpickr-day {
                    border-color : ' . esc_attr($bg_color) . '
                }
                .wpc-food-tab-wrapper .wpc-nav li a.wpc-active,
                .wpc-reservation-form #wpc_cancel_request, .wpc-reservation-form #wpc_book_table{
                    border-bottom-color : ' . esc_attr($bg_color) . '
                }
                body.woocomerce-layout-override-enable.single-product div.product .woocommerce-tabs ul li.active{
                    border-right-color : ' . esc_attr($bg_color) . '
                }
                .wpc-food-tab-wrapper .wpc-nav li a:after{
                    border-color:' . esc_attr($bg_color) . ' transparent transparent transparent;
                }
                .wpc-reservation-field.date .flatpickr-day,
                .wpc-food-menu-item.style2:hover {
                    border-color:' . esc_attr($bg_color) . '
                }

                @media (min-width: 768px){
                    .nav-position-right .wpc-nav li a.wpc-active,
                    .nav-position-left .wpc-nav li a.wpc-active,
                    .wpc_pro_order_tip_wrapper .wpc-btn.wpc-btn-border:hover {
                        background-color: ' . esc_attr($bg_color) . ';
                        color: #fff;
                        border-color: ' . esc_attr($bg_color) . ';
                    }
                
                    
                ';
            // icon cross
            $wpc_custom_css .= '.wpc_cart_block .woocommerce-mini-cart li .remove.remove_from_cart_button {color: #fff !important; background-color : ' . esc_attr($bg_color) . '}';
        } else {
            $wpc_custom_css .= '.wpc_background_color { background-color : #A352FF }';
            //button
            $wpc_custom_css .= '.wpc_cart_block .wpc_background_color a.button.wc-forward { background-color : ' . esc_attr('#5D78FF') . '}';
        }

        // cart icon color
        if (isset($settings['wpc_secondary_color'])) {
            $color = $settings['wpc_secondary_color'] ? $settings['wpc_secondary_color'] : "";

            //button hover
            $wpc_custom_css .= '
                .wpc_cart_block .wpc_background_color a.button.wc-forward:hover,
                .wpc-btn:hover, .wpc-btn:focus, .attr-btn-primary:hover, .attr-btn-primary:focus, 
                .reservation_form_submit.wpc-btn:hover, .reservation_form_submit.wpc-btn:focus, 
                .cancell_form_submit.wpc-btn:hover, .cancell_form_submit.wpc-btn:focus, 
                .wpc-settings-dashboard .button-primary:hover,
                 .wpc-settings-dashboard .button-primary:focus,
                 body.woocomerce-layout-override-enable.woocommerce button.button.alt:hover,
                 body.woocomerce-layout-override-enable.woocommerce-cart .woocommerce .shop_table a.remove:hover,
                 body.woocomerce-layout-override-enable.woocommerce-cart .woocommerce .button:hover,
                 .wpc-reservation-form .wpc_reservation_user_info .wpc_log_message,
                 body.woocomerce-layout-override-enable.woocommerce #respond input#submit:hover,
                 body.woocomerce-layout-override-enable .products .product span.onsale,
                 body.woocomerce-layout-override-enable.single-product .products .product span.onsale,
                 body.woocomerce-layout-override-enable .woocommerce button.button.alt:hover,
                 .wpc-reservation-form .confirm_booking_btn,
                 .reserv-with-food-wrap #wpc-multi-step-reservation .wpc-btn.wpc-form-previous,
                 .settings-content-wraps .wpc-btn,
                 .action-button.wpc-btn:hover,
                 .wpc-minicart-wrapper .wpc_cart_icon .basket-item-count,
                #wpc_location_modal .wpc-close,
                .wpc-btn:hover, 
                .wpc_pro_order_tip_wrapper .wpc-btn:hover,
                .wpc_pro_order_tip_wrapper .wpc-btn.wpc-btn-border:hover {
                     background-color : ' . esc_attr($color) . '
                }
                ';
            // icon cross hover
            $wpc_custom_css .= '.wpc_cart_block .woocommerce-mini-cart li .remove.remove_from_cart_button { background-color : ' . esc_attr($color) . '}';
            // count
            $wpc_custom_css .= '
                .woocomerce-layout-override-enable.woocommerce div.product p.price,
                .woocomerce-layout-override-enable.woocommerce div.product span.price,
                body.woocomerce-layout-override-enable.woocommerce-cart .woocommerce .shop_table tbody td.product-name a:hover,
                body.woocomerce-layout-override-enable.woocommerce-checkout .woocommerce .shop_table tbody td.product-name a:hover
                { 
                    color : ' . esc_attr($color) .
                '}';
        } else {
            $wpc_custom_css .= 'a.wpc_cart_icon i { color : #fff}';
            //button hover
            $wpc_custom_css .= '.wpc_cart_block .wpc_background_color a.button.wc-forward:hover { background-color : ' . esc_attr('#5D78FF') . '}';
            // icon cross hover
            $wpc_custom_css .= '.wpc_cart_block .woocommerce-mini-cart li .remove.remove_from_cart_button:hover { background-color : ' . esc_attr('#5D78FF') . '}';
            // count 
            $wpc_custom_css .= '.cart-items-count { color : ' . esc_attr('#fff') . '}';
        }

    
        // add inline css
        wp_register_style('wpc-cart-css', false);
        wp_enqueue_style('wpc-cart-css');
        wp_add_inline_style('wpc-cart-css', $wpc_custom_css);