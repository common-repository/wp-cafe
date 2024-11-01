<?php

namespace WpCafe\Core\Modules\Food_Menu;
use WpCafe_Pro\Utils\Utilities as Pro_Utilities;
use Astra_Woocommerce;
use WpCafe\Utils\Wpc_Utilities;

defined( 'ABSPATH' ) || exit;

class Hooks {

		use \WpCafe\Traits\Wpc_Singleton;

		/*** Food Order report start ***/

	/**
	 * Dashboard overview
	 */
	public function monthly_report() {
			// report for this month.
			$response =
			array( 'booking' => '','sales'=>'','refunds'=>'','clients'=>'');

			// reservation.
			$all_reservations   = \WpCafe\Core\Modules\Reservation\Hooks::instance()->get_monthly_reservation();
			// clients.
			$customer_list      = $this->get_customer_list(array('wc-refunded'));
			// get sales.
			$total_sales        = $this->get_sales_details(array('wc-processing', 'wc-completed'));
			// get refund.
			$total_refund       = $this->get_sales_details(array('wc-refunded'));

			$response =
			array( 'booking' => $all_reservations ,'sales'=> $total_sales ,'refunds'=>$total_refund,'clients'=>$customer_list);

			return $response;
	}

	/**
	 * Get monthly customer list
	 */
	public function get_customer_list() {

			$date_from  = date('Y-m-d', strtotime(date('Y-m-d') . "-1 Month"));
			$date_to    = date('Y-m-d', strtotime(date('Y-m-d') ));
			$clients = 0;

			global $wpdb;

			$post_meta      = $wpdb->postmeta;
			$posts          = $wpdb->posts;
			$users          = $wpdb->users;

			$query = $wpdb->prepare(
				"SELECT users.ID FROM $users AS users
				INNER JOIN $post_meta AS customer_ids ON users.ID = customer_ids.meta_value
					AND customer_ids.meta_key = '_customer_user'
				INNER JOIN $posts AS orders ON customer_ids.post_id = orders.ID
					AND orders.post_type='shop_order'
					AND post_date BETWEEN %s AND %s",
				"{$date_from} 00:00:00",
				"{$date_to} 23:59:59"
			);

			$cache_key = Wpc_Utilities::get_query_cache( $query );
			$clients = wp_cache_get( $cache_key, 'wpcafe_query_cache');

			if( !$clients ) {

				$clients = $wpdb->get_results($query); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery

				wp_cache_set($cache_key, $clients, 'wpcafe_query_cache', 60);
			}
			
						
			return count($clients); 

	}

	/**
	 * get sales details
	 */
	public function get_sales_details($status){
			$total = 0;
			if (class_exists('Woocommerce') ) {

					global $wpdb;
					$date_from  = date('Y-m-d', strtotime(date('Y-m-d') . "-1 Month"));
					$date_to    = date('Y-m-d', strtotime(date('Y-m-d') ));
					$post_status= implode("','", $status );

					$query = $wpdb->prepare(
						"SELECT * FROM $wpdb->posts 
							WHERE post_type = 'shop_order'
							AND post_status IN ('{$post_status}')
							AND post_date BETWEEN %s AND %s
							",
							"{$date_from} 00:00:00",
							"{$date_to} 23:59:59"
					);

					$cache_key = Wpc_Utilities::get_query_cache( $query );
					$orders = wp_cache_get( $cache_key, 'wpcafe_order_cache');
		
					if( !$orders ) {

						$orders = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
						wp_cache_set($cache_key, $orders, 'wpcafe_order_cache', 60);

					}

					foreach ( $orders as $customer_order ) {
							$order = wc_get_order( $customer_order );
							$total += $order->get_total();
					}

			}
			return $total;
	}

		/*** Food Order report end ***/


	/**
	 * Fire all hooks
	 */
	public function init() {
		add_action( 'woocommerce_thankyou', [$this,'wpc_checkout_callback'], 10, 1 );
		add_action( 'woocommerce_admin_order_data_after_billing_address', [$this,'show_order_details_meta'], 10, 1 );
		//Filter food by location validation
		add_filter( 'woocommerce_cart_redirect_after_error', '__return_false'); 
		add_filter( 'woocommerce_add_to_cart_validation', [$this,'food_location_add_to_cart_validation'], 10, 3 );

		// remove astra theme conflict
		add_action('init', [$this,'remove_astra_mini_cart']);

	}
	
	/**
	 * Remove astra theme conflict issue
	 *
	 * @return void
	 */
	public function remove_astra_mini_cart () {
		if ( class_exists('Astra_Woocommerce')) {
			$obj = Astra_Woocommerce::get_instance();
			remove_filter('woocommerce_add_to_cart_fragments',[$obj,'cart_link_fragment'],11);
			remove_filter('add_to_cart_fragments',[$obj,'cart_link_fragment'],11);
		}
	}

	/**
	 * Discard items from cart , if
	 * Items is adding from different location
	 * to cart
	 */
	public function food_location_add_to_cart_validation( $passed, $product_id, $quantity ) {
			global $woocommerce;
			$items = $woocommerce->cart->get_cart();
			// before add to cart product check if location exist.
			$wpc_location_id = ! empty( $_POST['wpc_location_id'] ) ? sanitize_text_field( $_POST['wpc_location_id'] ) : "";

			if ( "" !== $wpc_location_id ) {
					if ( ! empty( $items ) ) {
							foreach($items as $item => $values) {
									$cart_product_id = $values['data']->get_id();
									$location = wp_get_post_terms($cart_product_id,'wpcafe_location',array('fields'=>'ids'));
									if ( ! empty($location) && ( !in_array( $wpc_location_id , $location ) ) ) {
											return $passed = false;
									}
							}
					}
			}
 
			return $passed ;
	}

	/**
	 * Show Food location , order meta in order details
	 */
	public function show_order_details_meta($order) {
			$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
			// Food location
			if(get_post_meta( $order_id, 'wpc_location_name', true ) != ''):
			?>
					<p><strong><?php echo esc_html__('Food Delivery Location:', 'wpcafe');
					?></strong> <?php echo esc_html(get_post_meta( $order_id, 'wpc_location_name', true )); ?></p>
			<?php
			endif;

			if (class_exists("Wpcafe_Pro")) {
					// Order type and schedule
					$order_data = Pro_Utilities::get_order_type();
					if (Pro_Utilities::data_validation_check_arr($order_data)) {
							foreach ($order_data as $key => $value) {
									if (get_post_meta($order_id, $value, true) != '') {
											?>
											<p>
													<strong><?php echo esc_html($key); ?>: </strong>
													<?php echo esc_html(get_post_meta($order_id, $value, true)); ?>
											</p>
											<?php
									}
							}
					}

			}
	}

	/**
	 * after successful checkout, some data are returned from woocommerce
	 * we can use these data to update our own data storage / tables
	 */
	public function wpc_checkout_callback( $order_id ) {
			if ( !$order_id ) {
					return;
			}
			global $wpdb;
			$order = wc_get_order( $order_id );

			do_action("wpcafe/after_thankyou");

	}

	/**
     * Change currency symbol based on WooCommerce settings
     */
    public static function get_price_with_currency_symbol( $price ){
        $price =  floatval($price);
        $currency_symbol = get_woocommerce_currency_symbol();
        $currency_pos    = get_option( 'woocommerce_currency_pos' );

        switch( $currency_pos ){
            case "left":
                $price_with_symbol = $currency_symbol . $price;
                break;
            case "right":
                $price_with_symbol = $price . $currency_symbol;
                break;
            case "left_space":
                $price_with_symbol = $currency_symbol . ' '. $price;
                break;
            case "right_space":
                $price_with_symbol = $price.' ' . $currency_symbol;
                break;

            default:
                $price_with_symbol = $currency_symbol . $price;
        }
        
        return $price_with_symbol;
    }

}
