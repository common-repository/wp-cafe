<?php
use WpCafe\Utils\Wpc_Utilities;
use \WpCafe\Core\Shortcodes\Template_Functions;

//check if woocommerce exists
if (!class_exists('Woocommerce')) { return; }

if( is_array( $food_menu_tabs ) && count( $food_menu_tabs )>0 ){
    
apply_filters( 'elementor/control/search_data' , $settings , $unique_id , 'wpc-food-menu-tab' );

$wpc_menu_count = is_array($settings) && isset($settings['wpc_menu_count']) ? $settings['wpc_menu_count'] : 5;
$wpc_show_desc  = is_array($settings) && isset($settings['wpc_show_desc']) ? $settings['wpc_show_desc'] : 'yes';
$wpc_show_vendor  = is_array($settings) && isset($settings['wpc_show_vendor']) ? $settings['wpc_show_vendor'] : 'no';
$show_thumbnail = is_array($settings) && isset($settings['show_thumbnail']) ? $settings['show_thumbnail'] : 'yes';
$title_link_show= is_array($settings) && isset($settings['title_link_show']) ? $settings['title_link_show'] : 'yes';
$class = ($title_link_show=='yes')? '' : 'wpc-no-link';
?>
<div class="wpc-food-tab-wrapper wpc-nav-shortcode main_wrapper_<?php echo esc_attr($unique_id)?>" data-id="<?php echo esc_attr($unique_id);?>">
    
    <?php Template_Functions::render_food_menu_tab_nav( $food_menu_tabs ); ?>
    
    <div class="wpc-tab-content wpc-widget-wrapper">
        <?php
            foreach ($food_menu_tabs as $content_key => $value) {
                if(isset( $value['post_cats'][0] )){
                    $active_class = (($content_key == array_keys($food_menu_tabs)[0]) ? 'tab-active' : ' ');
                    $cat_id = isset($value['post_cats'][0] ) ? intval( $value['post_cats'][0] ) : 0 ;

                    $food_tab_args = array(
                        'post_type'     => 'product',
                        'no_of_product' => $wpc_menu_count,
                        'wpc_cat'       => $value['post_cats'],
                        'order'         => $wpc_menu_order,
                    );
                    $products = Wpc_Utilities::product_query( $food_tab_args );

                    $menu_tab_args = array(
                        'active_class'      => $active_class,
                        'content_key'       => $content_key,
                        'cat_id'            => $cat_id,
                        'unique_id'         => $unique_id,
                        'products'          => $products,
                        'style'             => $style,
                        'wpc_cart_button'   => $wpc_cart_button,
                        'wpc_price_show'    => $wpc_price_show,
                        'wpc_show_desc'     => $wpc_show_desc,
                        'show_thumbnail'    => $show_thumbnail,
                        'title_link_show'   => $title_link_show,
                        'show_item_status'  => $show_item_status,
                        'wpc_desc_limit'    => $wpc_desc_limit,
                        'wpc_show_vendor'   => $wpc_show_vendor
                    );
                    Template_Functions::render_food_menu_tab_product_block( $menu_tab_args );
                }
            } 
        ?>
    </div><!-- Tab content-->
</div>
<?php
}
return;

