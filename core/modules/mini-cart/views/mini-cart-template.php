<?php

use WpCafe\Utils\Wpc_Utilities;

defined( 'ABSPATH' ) || exit;


do_action( 'woocommerce_before_mini_cart' ); 
$settings       = $this->settings_obj;
$min_order_amount = !empty($settings['min_order_amount']) ? floatval( $settings['min_order_amount'] ) : 0 ;
$cart_link 		= !empty($settings['wpc_mini_empty_cart_link']) ? $settings['wpc_mini_empty_cart_link'] : get_permalink( wc_get_page_id( 'shop' ) );
?>

<?php if ( ! WC()->cart->is_empty() ) : ?>
	<div class="cart-wrapper">
		<ul class="woocommerce-mini-cart cart_list product_list_widget">
			<?php

			do_action( 'woocommerce_before_mini_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
						<?php
						echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"> 
									<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" xmlns:v="https://vecta.io/nano"><path fill-rule="evenodd" d="M13.19 1.137a.75.75 0 0 0-1.062 1.059l1.924 1.929H5.95l1.923-1.929a.75.75 0 1 0-1.062-1.059l-2.98 2.988h-.315c-.489 0-1.178.015-1.727.382-.629.422-.873 1.141-.873 2.035 0 .964.217 1.752.916 2.155a1.76 1.76 0 0 0 .411.17l1.108 6.788v.001c.141.853.4 1.744 1.058 2.411.68.688 1.66 1.017 2.973 1.017h5.025c1.39 0 2.379-.303 3.039-.995.628-.659.825-1.547.972-2.314l1.324-6.905c.14-.038.284-.093.423-.173.699-.403.916-1.191.916-2.155 0-.894-.244-1.613-.873-2.035-.549-.367-1.237-.382-1.727-.382h-.314l-2.98-2.988zm3.01 7.821H3.779l1.053 6.453v.001c.126.764.321 1.272.646 1.601.304.308.836.571 1.906.571h5.025c1.202 0 1.7-.264 1.954-.53.286-.3.431-.761.584-1.561h0L16.2 8.958zM2.625 5.753c-.039.026-.208.141-.208.788 0 .4.048.625.094.745.036.094.065.108.07.11h.001c.025.014.094.042.268.055.134.01.27.009.446.008h0 0 0 0l.221-.001h12.967l.221.001.446-.008c.175-.013.243-.04.268-.055h.001c.006-.003.034-.017.07-.11.046-.12.094-.344.094-.745 0-.648-.169-.762-.208-.788-.12-.08-.356-.128-.892-.128H3.517c-.536 0-.772.048-.892.128zm6.259 5.913a.75.75 0 0 0-1.5 0v2.958a.75.75 0 0 0 1.5 0v-2.958zm3.083-.75a.75.75 0 0 1 .75.75v2.958a.75.75 0 1 1-1.5 0v-2.958a.75.75 0 0 1 .75-.75z" fill="#e7272d"/></svg>
								</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_attr__( 'Remove this item', 'wpcafe' ),
								esc_attr( $product_id ),
								esc_attr( $cart_item_key ),
								esc_attr( $_product->get_sku() )
							),
							$cart_item_key
						);
						?>

						<?php if ( empty( $product_permalink ) ) : ?>
							<?php echo Wpc_Utilities::wpc_render($thumbnail) . Wpc_Utilities::wpc_kses( $product_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<a href="<?php echo esc_url( $product_permalink ); ?>">
								<?php echo Wpc_Utilities::wpc_render($thumbnail) . Wpc_Utilities::wpc_kses( $product_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						<?php endif; ?>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<div class="mini-cart-quantity-wrapper">
							<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $product_price, $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<strong class="single-subtotal-item">
								<?php echo esc_html( get_woocommerce_currency_symbol() ); ?><span class="wpc-minicart-subtotal"><?php echo esc_html($cart_item['quantity'] * $_product->get_price()); ?></span>
							</strong>
						</div>
					</li>
					<?php
				}
			}

			do_action( 'woocommerce_mini_cart_contents' );
			?>
		</ul>

		<?php
		if(class_exists('Wpcafe_Pro')){
			if(file_exists(\Wpcafe_Pro::core_dir().'/shortcodes/views/mini-cart/cross-sell.php')){
				include_once \Wpcafe_Pro::core_dir().'/shortcodes/views/mini-cart/cross-sell.php';
			}
		}
		
		?>
		<div class="wpc-subtotal-wrap">
			<?php
			if(class_exists('Wpcafe_Pro')){
				?>
					<div class="wpc-coupon-wrapper">
						<?php 
							wc_print_notices();
							if (empty (WC()->cart->get_coupons())){

							?>
								<?php if ( wc_coupons_enabled() ) { ?>
									<label class="showcoupon wpc-minicart-copoun-label" for="minicart-coupon">
										<?php echo esc_html__('Have a coupon code?', 'wpcafe'); ?>
									</label>
								<div class="coupon_from_wrap">
									<form class="coupon_from widget_shopping_cart_content" method="post">
								<?php } else { ?>
									<div class="coupon_from_wrap">
									<form id="apply-promo-code" class="coupon_from wpc_coupon_form widget_shopping_cart__coupon">
									<?php } ?>
										<input id="minicart-coupon" class="input-text wpc-minicart-coupon-field" type="text" name="coupon_code"/>
										<button type="submit" id="minicart-apply-button" class="wpc-cupon-btn button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'wpcafe' ); ?>"><?php echo esc_html__( 'Apply', 'wpcafe' ); ?></button>
										<?php do_action( 'woocommerce_cart_coupon' ); ?>
										<?php do_action( 'woocommerce_cart_actions' ); ?>
									</form>
								</div>
						<?php } ?>
					</div>
				<?php
			}
	?>
			<p class="woocommerce-mini-cart__total total">
				<?php
				/**
				 * Hook: woocommerce_widget_shopping_cart_total.
				 *
				 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
				 */
				do_action( 'woocommerce_widget_shopping_cart_total' );

				?>
				<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
					<div id="widget-shopping-cart-remove-coupon" class="mini_cart_coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<?php esc_html_e('Coupon: ', 'wpcafe'); ?> <?php echo esc_attr( sanitize_title( $code ) ); ?> 
							<?php wc_cart_totals_coupon_html( $coupon ); ?>
					</div>
				<?php endforeach; ?>
			</p>

			<?php  do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); 


			if( floatval(WC()->cart->subtotal) > floatval($min_order_amount) || $min_order_amount == 0 ) {
			?>
				<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>
			<?php 
			}else{
				$message = sprintf(__("Your current amount is %s,
				You need to add at least %s to place order", "wpcafe"), 
					\WpCafe\Core\Modules\Food_Menu\Hooks::get_price_with_currency_symbol( WC()->cart->subtotal ), 
					\WpCafe\Core\Modules\Food_Menu\Hooks::get_price_with_currency_symbol( $min_order_amount ) );
				wc_print_notice( $message , 'error' );
			}
			

			do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); 
			?>
		</div>
	</div>
<?php else : ?>

	<div class="wpc-empty-cart">
		<div class="cart-wrapper">
			<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'wpcafe' ); ?></p>
			<a href="<?php echo esc_url( $cart_link ); ?>" class="wpc-btn wpc-empty-btn"><?php esc_html_e( 'Explore Food Items', 'wpcafe' ); ?></a>
		</div>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>