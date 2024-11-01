<?php

global $woocommerce;

if ( is_object( WC()->cart ) && WC()->cart->cart_contents_count == 0 ) {
    $cart_empty = 1;
}else{
    $cart_empty = 0;
}

?>

<!-- render html -->
<div class="food_location" data-cart_empty="<?php echo esc_attr( $cart_empty );?>">
		<?php
		if ( !empty( $products ) ) {
			include \Wpcafe::plugin_dir() . "widgets/wpc-menus-list/style/${style}.php";
		}
		else {
			?>
				<div><?php esc_html_e( 'No menu found' , 'wpcafe')?></div>
			<?php
		}
		?>
</div>
