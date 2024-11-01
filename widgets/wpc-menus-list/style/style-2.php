<?php

use \WpCafe\Utils\Wpc_Utilities;
use \WpCafe\Core\Shortcodes\Template_Functions as Wpc_Widget_Template;

$cafe_settings      =  \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
$cart_icon          = !empty($cafe_settings['wpc_cart_icon']) ? $cafe_settings['wpc_cart_icon'] : 'wpcafe-cart_icon';
$customization_icon = !empty($cafe_settings['wpc_customization_icon']) ? $cafe_settings['wpc_customization_icon'] : 'wpcafe-customize';
$col = ($show_thumbnail == 'yes') ? 'wpc-col-md-8' : 'wpc-col-md-12';
$class = ($title_link_show=='yes')? '' : 'wpc-no-link';

foreach ($products as $product) { 
    $permalink = ( $title_link_show == 'yes' ) ?  get_permalink($product->get_id()) : '#';
    $food_menu_list_args = array(
        'show_thumbnail'    => $show_thumbnail,
        'wpc_price_show'    => $wpc_price_show,
        'permalink'         => $permalink,
        'wpc_cart_button'   => $wpc_cart_button,
        'unique_id'         => $unique_id,
        'product'           => $product,
        'class'             => $class,
        'show_item_status'  => $show_item_status,
        'wpc_show_desc'     => $wpc_show_desc,
        'wpc_desc_limit'    => $wpc_desc_limit,
        'cart_icon'         => $cart_icon,
        'customization_icon'=> $customization_icon,
        'wpc_show_vendor'   => $wpc_show_vendor
    );
    Wpc_Widget_Template::wpc_food_menu_list_template_two( $food_menu_list_args );

}