<?php
    use WpCafe\Utils\Wpc_Utilities;
    $style               = $settings["food_menu_style"];
    $show_item_status   = $settings["show_item_status"];
    $show_thumbnail     = $settings["show_thumbnail"];
    $title_link_show    = $settings["title_link_show"];
    $wpc_cart_button    = $settings["wpc_cart_button_show"];
    $wpc_show_desc      = $settings["wpc_show_desc"];
    $wpc_desc_limit     = $settings["wpc_desc_limit"];
    $wpc_menu_cat       = $settings["wpc_menu_cat"];
    $wpc_menu_count     = $settings["wpc_menu_count"];
    $wpc_menu_order     = $settings["wpc_menu_order"];
    $show_thumbnail     = $settings["show_thumbnail"];
    $wpc_price_show     = $settings["wpc_price_show"];
    $wpc_show_vendor    = !empty($settings["wpc_show_vendor"]) ? $settings["wpc_show_vendor"] : '';
    $no_desc_class      = ($wpc_show_desc != 'yes') ? 'wpc-no-desc' : '';
    $column_desktop     = $settings['wpc_menu_col'];
    $column_tablet      = isset($settings['wpc_menu_col_tablet']) ? $settings['wpc_menu_col_tablet'] : 2;
    $column_mobile      = isset($settings['wpc_menu_col_mobile']) ? $settings['wpc_menu_col_mobile'] : 1;

    apply_filters( 'elementor/control/search_data' , $settings , $unique_id , 'wpc-menus-list' );

    ?>
    <div class="wpc-nav-shortcode main_wrapper_<?php echo esc_attr($unique_id .' '. $no_desc_class)?>" data-id="<?php echo esc_attr($unique_id)?>">
        <div class="list_template_<?php echo esc_attr($unique_id) ?> wpc-nav-shortcode wpc-widget-wrapper">
            <?php
            $food_list_args = array(
                'post_type'     => 'product',
                'no_of_product' => $wpc_menu_count,
                'wpc_cat'       => $wpc_menu_cat,
                'order'         => $wpc_menu_order,
            );
            $products = Wpc_Utilities::product_query( $food_list_args );
            include \Wpcafe::plugin_dir() . "widgets/wpc-menus-list/style/{$style}.php";
            ?>
        </div>
    </div>
    <?php
    return;