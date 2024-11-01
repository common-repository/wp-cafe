<?php 
     $settings       = $this->settings_obj;
     do_action('wpc_before_minicart');

if ( !empty($settings['minicart_style']) && $settings['minicart_style'] === 'style-1' ) { 
    // style 1
    ?>
    <div class="wpc_cart_block wpc-minicart-wrapper style1 wpc-cart_main_block">

    <a href="#" class="wpc_cart_icon">
        <div class="wpc-cart-message"><?php echo esc_html__('Product has been added', 'wpcafe'); ?></div>

        <i class="<?php echo esc_attr($wpc_cart_icon); ?>"></i>
        <sup class="basket-item-count" style="display: inline-block;">
            <span class="cart-items-count count wpc-mini-cart-count"></span>
        </sup>
    </a>
        <div class="wpc-menu-mini-cart wpc_background_color">
                <div class="widget_shopping_cart_content"> 
                     <?php
                        if(file_exists(\Wpcafe::core_dir().'modules/mini-cart/views/mini-cart-template.php')){
                            include_once \Wpcafe::core_dir().'modules/mini-cart/views/mini-cart-template.php';
                        }
                    ?>
                </div>
                
            </div>
        </div>
    <?php  
}else{ 
    // style 2
    ?>
    <div class="wpc-minicart-wrapper style2 wpc-cart_main_block">
        <a href="#" class="wpc_cart_icon">
            <div class="wpc-cart-message"><?php echo esc_html__('Product has been added', 'wpcafe'); ?></div>

            <i class="<?php echo esc_attr($wpc_cart_icon); ?>"></i>
            <sup class="basket-item-count" style="display: inline-block;">
                <span class="cart-items-count count wpc-mini-cart-count"></span>
            </sup>
        </a>
        <div class="wpc_cart_block">
            <div class="wpc-minicart-header">
                <div class="cart-counts">
                    <span class="cart-count">
                        <span class="cart-items-count count wpc-mini-cart-count"></span><?php echo esc_html__(' items', 'wpcafe'); ?>
                    </span>
                    <?php echo esc_html__('in cart', 'wpcafe'); ?>
                </div>
                <button type="button" class="minicart-close wpc-btn-border wpc-btn">
                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.93359 16.0653L16.0653 9.93359" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16.0653 16.0653L9.93359 9.93359" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.7513 23.8346H16.2513C21.668 23.8346 23.8346 21.668 23.8346 16.2513V9.7513C23.8346 4.33464 21.668 2.16797 16.2513 2.16797H9.7513C4.33464 2.16797 2.16797 4.33464 2.16797 9.7513V16.2513C2.16797 21.668 4.33464 23.8346 9.7513 23.8346Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            <div class="wpc-menu-mini-cart wpc_background_color">
                <div class="widget_shopping_cart_content">
                     <?php
                        
                        if(file_exists(\Wpcafe::core_dir().'modules/mini-cart/views/mini-cart-template.php')){
                            include_once \Wpcafe::core_dir().'modules/mini-cart/views/mini-cart-template.php';
                        }
                        
                    ?>
                </div>
                
            </div>
        </div>
    </div>
    <?php
}
?>

<?php do_action('wpc_after_minicart');?>