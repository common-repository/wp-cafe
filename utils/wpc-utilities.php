<?php

namespace WpCafe\Utils;

use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * All  helper functions to use 
 */
class Wpc_Utilities {

	private static $settings_key = 'wpcafe_reservation_settings_options';

	/**
	 * Html markup validation
	 */
	 public static function wpc_kses( $raw ) {
		$allowed_tags = [
			'a'                             => [
				'class'  => [],
				'href'   => [],
				'rel'    => [],
				'title'  => [],
				'target' => [],
			],
			'input'                         => [
				'value'       => [],
				'type'        => [],
				'size'        => [],
				'name'        => [],
				'checked'     => [],
				'placeholder' => [],
				'id'          => [],
				'class'       => [],
			],

			'select'                        => [
				'value'       => [],
				'type'        => [],
				'size'        => [],
				'name'        => [],
				'placeholder' => [],
				'id'          => [],
				'class'       => [],
				'option'      => [
					'value'   => [],
					'checked' => [],
				],
			],

			'textarea'                      => [
				'value'       => [],
				'type'        => [],
				'size'        => [],
				'name'        => [],
				'rows'        => [],
				'cols'        => [],
				'placeholder' => [],
				'id'          => [],
				'class'       => [],
			],
			'abbr'                          => [
				'title' => [],
			],
			'b'                             => [],
			'blockquote'                    => [
				'cite' => [],
			],
			'cite'                          => [
				'title' => [],
			],
			'code'                          => [],
			'del'                           => [
				'datetime' => [],
				'title'    => [],
			],
			'dd'                            => [],
			'div'                           => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'dl'                            => [],
			'dt'                            => [],
			'em'                            => [],
			'h1'                            => [
				'class' => [],
			],
			'h2'                            => [
				'class' => [],
			],
			'h3'                            => [
				'class' => [],
			],
			'h4'                            => [
				'class' => [],
			],
			'h5'                            => [
				'class' => [],
			],
			'h6'                            => [
				'class' => [],
			],
			'i'                             => [
				'class' => [],
			],
			'img'                           => [
				'alt'    => [],
				'class'  => [],
				'height' => [],
				'src'    => [],
				'width'  => [],
			],
			'li'                            => [
				'class' => [],
			],
			'ol'                            => [
				'class' => [],
			],
			'p'                             => [
				'class' => [],
			],
			'q'                             => [
				'cite'  => [],
				'title' => [],
			],
			'span'                          => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'small'                          => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'iframe'                        => [
				'width'       => [],
				'height'      => [],
				'scrolling'   => [],
				'frameborder' => [],
				'allow'       => [],
				'src'         => [],
			],
			'strike'                        => [],
			'br'                            => [],
			'strong'                        => [],
			'data-wow-duration'             => [],
			'data-wow-delay'                => [],
			'data-wallpaper-options'        => [],
			'data-stellar-background-ratio' => [],
			'ul'                            => [
				'class' => [],
			],
			'label'                         => [
				'class' => [],
				'for' => [],
			],
		];

		if ( function_exists( 'wp_kses' ) ) { // WP is here
			return wp_kses( $raw, $allowed_tags );
		} else {
			return $raw;
		}

	}

	/**
	 * Html markup validation
	 */
	public static function wpc_kses_allowed_tags() {
		$allowed_tags = [
			'a'                             => [
				'class'  => [],
				'href'   => [],
				'rel'    => [],
				'title'  => [],
				'target' => [],				
				'id'	 => [],
				'data-product_id' => [],
				'data-tableid'	=> [],
				'data-product_name' => [],
				'data-product_price' => []
			],
			'input'                         => [
				'value'       => [],
				'type'        => [],
				'size'        => [],
				'name'        => [],
				'checked'     => [],
				'placeholder' => [],
				'id'          => [],
				'class'       => [],
				'autocomplete'=> [],
				'step'        => [],
				'min'         => [],
				'max'         => [],
			],

			'select'                        => [
				'value'       => [],
				'type'        => [],
				'size'        => [],
				'name'        => [],
				'placeholder' => [],
				'id'          => [],
				'class'       => [],
				'multiple'    => array(),
				'data-cat'	  => []
				
			],
			'option'      => [
				'value'   => [],
				'checked' => [],
				'selected'=> [],
				'data-formstyle' => []
			],

			'textarea'                      => [
				'value'       => [],
				'type'        => [],
				'size'        => [],
				'name'        => [],
				'rows'        => [],
				'cols'        => [],
				'placeholder' => [],
				'id'          => [],
				'class'       => []
			],
			'abbr'                          => [
				'title' => [],
			],
			'b'                             => [],
			'blockquote'                    => [
				'cite' => [],
			],
			'cite'                          => [
				'title' => [],
			],
			'code'                          => [],
			'del'                           => [
				'datetime' => [],
				'title'    => [],
			],
			'dd'                            => [],
			'div'                           => [
				'class' => [],
				'title' => [],
				'style' => [],
				'id'    => [],
			],
			'dl'                            => [],
			'dt'                            => [],
			'em'                            => [],
			'h1'                            => [
				'class' => [],
			],
			'h2'                            => [
				'class' => [],
			],
			'h3'                            => [
				'class' => [],
			],
			'h4'                            => [
				'class' => [],
			],
			'h5'                            => [
				'class' => [],
			],
			'h6'                            => [
				'class' => [],
			],
			'i'                             => [
				'class' => [],
			],
			'img'                           => [
				'alt'    => [],
				'class'  => [],
				'height' => [],
				'src'    => [],
				'width'  => [],
			],
			'li'                            => [
				'class' => [],
			],
			'ol'                            => [
				'class' => [],
			],
			'p'                             => [
				'class' => [],
			],
			'q'                             => [
				'cite'  => [],
				'title' => [],
			],
			'span'                          => [
				'class' => [],
				'id' => [],
				'title' => [],
				'style' => [],
				'span'	=> []
			],
			'small'                          => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'iframe'                        => [
				'width'       => [],
				'height'      => [],
				'scrolling'   => [],
				'frameborder' => [],
				'allow'       => [],
				'src'         => [],
			],
			'strike'                        => [],
			'br'                            => [],
			'data-wow-duration'             => [],
			'data-wow-delay'                => [],
			'data-wallpaper-options'        => [],
			'data-stellar-background-ratio' => [],
			'ul'                            => [
				'class' => [],
			],
			'label'                         => [
				'class' => [],
				'for' => [],
			],
			'audio' => array(
				'class' => array(),
				'id' => array(),
				'name' => array(),
				'controls' => array(),
			),
			'source' => array(
				'src' => array(),
				'type' => array(),
			),
			'strong' => array(
				'for' => array(),
			),			
			'attr' => array(
				'style' => array(),
				'class' => array(),
				'input_class' => array(),
			),
		];

		return $allowed_tags;

	}

	/**
	 * Auto generate class name from path.
	 */
	public static function make_classname( $dirname ) {
		$dirname    = pathinfo( $dirname, PATHINFO_FILENAME );
		$class_name = explode( '-', $dirname );
		$class_name = array_map( 'ucfirst', $class_name );
		$class_name = implode( '_', $class_name );

		return $class_name;
	}

	public static function kspan( $text ) {
		return str_replace( ['{', '}'], ['<span>', '</span>'], self::wpc_kses( $text ) );
	}

	/**
	 * Seat count min , max limit
	 */
	public  static function get_seat_count_limit() {
		$seat_count_limit = array();
		try {
			$settings_obj     = new \WpCafe\Core\Base\Wpc_Settings_Field;
			$settings         = $settings_obj->get_settings_option();
			
			$get_seat_capacity= apply_filters('wpcafe/reservation/seat_capacity', $settings );
			$seat_capacity    = class_exists('Wpcafe_Pro') && isset( $get_seat_capacity ) ? $get_seat_capacity : 20;
			$wpc_min_guest_no = isset( $settings['wpc_min_guest_no'] ) && $settings['wpc_min_guest_no']!=="" ? $settings['wpc_min_guest_no'] : 1;
			$wpc_max_guest_no = !empty( $settings['wpc_max_guest_no'] )  ? $settings['wpc_max_guest_no'] : $seat_capacity;
			for ( $i = $wpc_min_guest_no; $i <= $wpc_max_guest_no; $i++ ) {
				$seat_count_limit[$i] = $i;
			}

			return $seat_count_limit;
		} catch ( \Exception $es ) {
			return [];
		}

	}

	/**
	 * Reservation status array
	 */
	public static function get_reservation_states() {
		$reservation_states              = [];
		$reservation_states['pending']   = esc_html__( 'Pending', 'wpcafe' );
		$reservation_states['confirmed'] = esc_html__( 'Confirmed', 'wpcafe' );
		$reservation_states['cancelled'] = esc_html__( 'Cancelled', 'wpcafe' );
		$reservation_states['completed'] = esc_html__( 'Completed', 'wpcafe' );
		
		return $reservation_states;
	}

	/**
	 * Generate invoice no.
	 */
	public static function generate_invoice_number( $post_id ) {
		$fourdigitrandom = rand( 1000, 9999 );
		$invoice_no      = 'WPC' . $fourdigitrandom . $post_id;

		return $invoice_no;
	}

	/**
	 * Email sending function
		*
	 * @param array $args = [$to, $subject, $mail_body, $from, $from_name].
	 */
	public static function wpc_send_email( $args ) {
		extract( $args );

		$subject = $subject; // wp_specialchars_decode() does not decode all entities
		$body    = wpautop( html_entity_decode( $mail_body ) );
		$from_name = html_entity_decode( $from_name );

		$headers = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . $from_name . ' <' . $from . '>' );

		$result  = wp_mail( $to, $subject, $body, $headers );
		
		return $result;
	}

	/**
	 * Show Notices
	 */
	public static function push( $notice ) {

		$defaults = array(
			'id'               => '',
			'type'             => 'info',
			'show_if'          => true,
			'message'          => '',
			'class'            => 'wpc-active-notice',
			'dismissible'      => false,
			'btn'              => array(),
			'dismissible-meta' => 'user',
			'dismissible-time' => WEEK_IN_SECONDS,
			'data'             => '',
		);

		$notice = wp_parse_args( $notice, $defaults );

		$classes = array( 'wpc-notice', 'notice' );

		$classes[] = $notice['class'];

		if ( isset( $notice['type'] ) ) {
			$classes[] = 'notice-' . $notice['type'];
		}

		// Is notice dismissible?
		if ( true === $notice['dismissible'] ) {
			$classes[] = 'is-dismissible';

			// Dismissable time.
			$notice['data'] = ' dismissible-time=' . esc_attr( $notice['dismissible-time'] ) . ' ';
		}

		// Notice ID.
		$notice_id    = 'wpc-sites-notice-id-' . $notice['id'];
		$notice['id'] = $notice_id;

		if ( ! isset( $notice['id'] ) ) {
			$notice_id    = 'wpc-sites-notice-id-' . $notice['id'];
			$notice['id'] = $notice_id;
		} else {
			$notice_id = $notice['id'];
		}

		$notice['classes'] = implode( ' ', $classes );

		// User meta.
		$notice['data'] .= ' dismissible-meta=' . esc_attr( $notice['dismissible-meta'] ) . ' ';

		if ( 'user' === $notice['dismissible-meta'] ) {
			$expired = get_user_meta( get_current_user_id(), $notice_id, true );
		} elseif ( 'transient' === $notice['dismissible-meta'] ) {
			$expired = get_transient( $notice_id );
		}
		
		if( 'wpc-sites-notice-id-wpcafe-pro-notice' == $notice['id'] ) {
			self::pro_banner_markup( $notice );
		}
		else {
			// Notice visible after transient expire.
			if ( isset( $notice['show_if'] ) ) {
				if ( true === $notice['show_if'] ) {
					// Is transient expired?
					if ( false === $expired || empty( $expired ) ) {
						self::markup( $notice );
					}
				}
			} else {
				self::markup( $notice );
			}
		}

	}

	/**
	 * Markup Notice.
	 */
	public static function pro_banner_markup( $notice = [] ) {
		?>
		<div id="<?php echo esc_attr( $notice['id'] ); ?>" class="wpc notice wpc-notice wpc-notice-buy-pro-banner is-dismissible" <?php echo esc_attr( $notice['data'] ); ?>>
			 <?php if ( !empty( $notice['btn'] ) ) { ?>
					<a target="_blank" href="<?php echo esc_url( $notice['btn']['url'] ); ?>" class="notice-banner-link"></a>
			<?php } ?>
		</div>
		<?php
	}


	/**
	 * Markup Notice.
	 */
	public static function markup( $notice = [] ) {
		?>
		<div id="<?php echo esc_attr( $notice['id'] ); ?>" class="<?php echo esc_attr( $notice['classes'] ); ?>" <?php echo esc_attr( $notice['data'] ); ?>>
			<p>
				<?php echo esc_attr( $notice['message'] ); ?>
			</p>

			<?php if ( !empty( $notice['btn'] ) ): ?>
				<p>
					<a href="<?php echo esc_url( $notice['btn']['url'] ); ?>" class="button-primary"><?php echo esc_html( $notice['btn']['label'] ); ?></a>
				</p>
			<?php endif;?>
		</div>
		<?php
	}

	/**
	 * Render html.
	 */
	public static function wpc_render( $content ) {
		if ( $content == "" ) {
			return "";
		}

		return $content;
	}

	/**
	 * Render numeric value.
	 */
	public static function wpc_numeric( $content ) {
		if ( is_numeric( $content ) ) {
			return $content;
		}

		return $content;
	}

	/**
	 *  Check nonce validation of submit data
	 */
	public static function is_secured( $nonce_field, $action, $post ) {
		$nonce = isset( $post[$nonce_field] ) ? sanitize_text_field( $post[$nonce_field] ) : '';
		if ( $nonce == '' ) {
			return false;
		}

		if ( !wp_verify_nonce( $nonce, $action ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Menu category
	 */
	public static function get_menu_category( $id = null ) {
		$menu_category = [];
		try {

			if ( is_null( $id ) ) {
				$terms = get_terms( [
					'taxonomy'   => 'product_cat',
					'hide_empty' => false,
				] );

				foreach ( $terms as $cat ) {
					if(is_object( $cat ) ){
						$menu_category[$cat->term_id] = $cat->name;
					}
				}

				return $menu_category;
			} else {
				// return single menu.
				return get_post( $id );
			}

		} catch ( \Exception $es ) {
			return [];
		}

	}

	/**
	 * content crop function
	 */
	public static function wpcafe_trim_words( $content, $count = 150, $readmore = null ) {
		return wp_trim_words( $content, $count, $readmore );
	}



	public static function get_location_data( $default_options="", $no_options="" , $value_type = "key", $number = false ) {
		$default_options = esc_html__('Select Delivery Location', 'wpcafe');
		$no_options = esc_html__('No Delivery Location is Set', 'wpcafe');
		// get location
		$wpc_location     = get_terms('wpcafe_location', ['taxonomy' => 'wpcafe_location', 'hide_empty' => 0, 'orderby' => 'DESC', 'parent' => 0, 'number'=> $number]);

		$wpc_location_arr = ['' => $default_options];

		if ( !empty($wpc_location) ) {
			foreach ($wpc_location as $value) {
				if ( $value_type =="key" ) {
					$wpc_location_arr["$value->slug"] = $value->name;
				}
				else if ( $value_type =="id" ) {
					$wpc_location_arr["$value->term_id"] = $value->name;
				}
				else{
					$wpc_location_arr["$value->name"] = $value->name;
				}
			}
		}

		return $wpc_location_arr;
	}

	/**
	 * Location feature function
	 */
	public static function get_location_details(){
		$settings =  \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
		// show location
		if (isset($settings['wpcafe_food_location']) && $settings['wpcafe_food_location'] == 'on') {
			$wpc_locations = Wpc_Utilities::get_location_data("","","id");
			\WpCafe\Core\Shortcodes\Template_Functions::modal_markup( $wpc_locations );
		}
	}

/**
	* Show tag function
 */
	public static function wpc_tag( $id , $stock_status ){
		$current_tags = get_the_terms($id, 'product_tag');
		//create a list to hold our tags.
		?>
		<ul class="wpc-menu-tag">
			<?php
			if ( $stock_status == true || ( $current_tags && ! is_wp_error( $current_tags ) ) ) {
				//for each tag we create a list item.
				if ( is_array( $current_tags ) && count( $current_tags )>0 ) {
					foreach ( $current_tags as $tag ) {
						$tag_title = $tag->name;
						?>
						<li>	<?php echo esc_html( $tag_title ); ?></li>
						<?php
					}
				}
			}
			else{
				?><li><?php echo esc_html__('Out of stock','wpcafe'); ?></li><?php 
			}
			?>
		</ul>
		<?php
	}

	/**
	 * Product query
	 * @param array $args = [ $post_type, $no_of_product, $wpc_cat, $order, $page, $total_count, $search_value, $taxonomy ]
	 */
	public static function product_query( $params ){
		$defaults = array(
			'post_type'     => 'product',
			'no_of_product' => 10,
			'wpc_cat'       => array(),
			'order'         => 'DESC',
			'page'          => null,
			'total_count'   => false,
			'search_value'  => false,
			'taxonomy'      => 'product_cat'
		);

		extract( wp_parse_args( $params , $defaults ) );

		$args = [];
		$args['post_type']      = $post_type;
		if ( $total_count ) {
			$args    = [ 'posts_per_page' =>  -1 ];
		}
		elseif( $search_value ){
			$args['posts_per_page']  = $no_of_product;
			$args['post_title_like'] = $search_value;
		}
		elseif( $page ){
			$args    = [
				'posts_per_page' =>  $no_of_product,
				'paged'          =>  $page,
			];
		}
		else{
			$args    = [ 'posts_per_page' =>  $no_of_product ];
		}

		$args['tax_query'] = array(
			'relation' => 'AND',
		);

		if( is_array( $wpc_cat ) && count( $wpc_cat )>0 ){
			$tax_query = array(
			array(
				'taxonomy'          => $taxonomy,
				'terms'             =>  $wpc_cat,
				'field'             => 'id',
				'include_children'  => true,
				'operator'          => 'IN'
			),
		);
			$args['tax_query'] = $tax_query;
		}

		$args['orderby']        = 'date';
		$args['order']          = $order;
		$args['post_status']    = 'publish';

		return wc_get_products($args);
	}

	/**
	 * Add to cart button based on product type
	 *
	 * @param [type] $args [ $product, $cart_button, $wpc_btn_text='', $customize_btn= '', $widget_id=''].
	 */
	public static function product_add_to_cart( $args ) {

		extract( $args );
		$settings           = get_option('wpcafe_reservation_settings_options');
		$cart_icon          = !empty($settings['wpc_mini_cart_icon']) ? $settings['wpc_mini_cart_icon'] : 'wpcafe-cart_icon';
		$customization_icon = !empty($settings['wpc_customization_icon']) ? $settings['wpc_customization_icon'] : 'wpcafe-customize';
		// qr code parameter.
		$html = self::qr_code_input();
		$price_html = "";

		switch ( $product->get_type() ) {

			case ( $product->get_type() == 'variable' || $product->get_type() == 'grouped' )
			&& $product->is_in_stock() == true :
				if( $cart_button=='on' || $cart_button =='yes' ) {
					if ( class_exists('\Wpcafe_Pro') ) {
						return apply_filters("wpcafe/shortcode/variation", $product, $customize_btn, $widget_id, $customization_icon );
					} else {
						$price_html = '
							<div class="wpc-add-to-cart">
								<a href="'.$product->get_permalink().'" class="wpc-btn">
									<i class="'.$cart_icon.'"></i>
								</a>
							</div>
						';
						}
				}
				break;

			case ($product->get_type() == 'simple' ) &&
				($cart_button == 'on' || $cart_button == 'yes' ) &&
				$product->is_in_stock() == true :

				$class = !empty($wpc_btn_text) ? 'cart-text-added' : 'cart-text-no-added';
				
				if ( class_exists('\Wpcafe_Pro') ) {

					// check if product addons exists or not.
					$wpc_addons = \WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc\Addon_Control::instance()->all_addons_list($product->get_id(),false);

					if ( ( !empty($wpc_addons) && count($wpc_addons) > 0) ) {
						return apply_filters("wpcafe/shortcode/simple", $product, $customize_btn, $widget_id , $customization_icon );
					}else {
		
						$price_html='
							<div class="wpc-add-to-cart">
								<a
									data-product_name='.esc_html($product->get_name()).'
									data-product_price="'. wc_get_price_to_display( $product ) . get_woocommerce_currency_symbol().'"
									data-product_id="'. $product->get_id().'"
									rel="nofollow" class="button add_to_cart_button ajax_add_to_cart '.$class.'"
									'.$html.'
								>
									<i class="'.$cart_icon.'"></i>
								</a>
							</div>';
					}
				}else {
				
					$price_html ='<div class="wpc-add-to-cart">
						<a href="?add-to-cart='.esc_html($product->get_id()).'"
						data-product_name="'.esc_html($product->get_name()).'"
						data-product_price="'.esc_html( wc_get_price_to_display( $product ) . get_woocommerce_currency_symbol() ).'"
						data-product_id="'.esc_html($product->get_id()).'"
						'.esc_html($html).'
						rel="nofollow" class="button  add_to_cart_button ajax_add_to_cart '.esc_attr($class).'">
							<span class="adding"> '.esc_html__('Adding...', 'wpcafe').'</span>
							<span class="added"> '.esc_html__('Added', 'wpcafe').'</span>';
							if (isset($wpc_btn_text) && $wpc_btn_text  != '') {
								
								$price_html .='<span class="add-cart-text"> '.esc_html($wpc_btn_text).' </span>';
							}
							$price_html .='<i class="'.esc_attr($cart_icon).'"></i>
						</a>
					</div>';
				}

				break;

			case $product->get_type() == 'external'  &&
				($cart_button == 'on' || $cart_button == 'yes' ) &&
				$product->is_in_stock() == true :
				$price_html = '
					<div class="wpc-external-product-link">
						<a href="'.$product->get_product_url().'" class="wpc-btn">
								'.$product->get_button_text().'
						</a>
					</div>
					';
				break;
			
			default:
				break;
		}

		return wp_kses( $price_html, Wpc_Utilities::wpc_kses_allowed_tags() );

	}

	public static function qr_code_input() {
		
		$html = '';
		if ( !empty( $_GET['tableId'] ) ) {
			$html = ' data-tableid="'. esc_html__( $_GET['tableId'] ).'"';
		}

		return $html;
	}

	/**
	 * Get variation price
	 */
	public static function get_variation_price($product){
		if($product->get_type() == 'grouped'){
			$children = $product->get_children();
			$price = 0;
			$var_price = get_woocommerce_currency_symbol( '' ) . ' ' . $price;
		}else{
			$variation_price = $product->get_variation_prices(true); // true for getting tax price
			$var_price = '';
			if (is_array($variation_price) && isset($variation_price['price'])) {
				$first = array_shift($variation_price['price']);
				$array_pop = array_pop($variation_price['price']);
				$last = ( !empty( $array_pop ) ) ?  "-" . get_woocommerce_currency_symbol() . $array_pop : '';
				$var_price = get_woocommerce_currency_symbol() . $first . $last ;
			}
		}

		return $var_price;
	}

	/**
	 * email to admin & user for new booking request
	 */
	public static function send_notification_admin_user( $settings , $args ){
		$result = false;
		// send notification email to admin
		if ( !isset( $settings['wpc_admin_notification_for_booking_req'] ) || ( isset( $settings['wpc_admin_notification_for_booking_req'] ) && $settings['wpc_admin_notification_for_booking_req'] == "on" ) && isset( $settings['wpc_admin_email_address'] )) {
			$sender_email    = !empty($settings['sender_email_address']) ? $settings['sender_email_address'] : "";
			$mail_to         = !empty($settings['wpc_admin_email_address'])  ? $settings['wpc_admin_email_address'] : $sender_email;
			$mail_subject    = isset($settings['wpc_admin_notification_subject']) ? $settings['wpc_admin_notification_subject'] : "";
			$default_message = $args['message'] . esc_html__( "Invoice No: " , "wpcafe") . $args['invoice'] . "";
			$mail_body       = isset($settings['wpc_admin_notification_email']) && $settings['wpc_admin_notification_email'] !=="" ? $settings['wpc_admin_notification_email'] : $default_message ;

			$wpc_main_email  = \WpCafe\Core\Modules\Reservation\Hooks::instance()->filter_template_tags( $args['reservation_id'],$mail_body, $args['invoice'] );
			$mail_from       = isset($settings['sender_email_address']) && $settings['sender_email_address'] !==''  ? $settings['sender_email_address'] : $settings['wpc_admin_email_address'];
			$mail_from_name  = isset($settings['wpc_reply_to_name'])  ? $settings['wpc_reply_to_name']  : esc_html__("Admin","wpcafe");

			$email_args = array(
				'to'        => $mail_to,
				'subject'   => $mail_subject,
				'mail_body' => $wpc_main_email,
				'from'      => $mail_from,
				'from_name' => $mail_from_name
			);
			$result = Wpc_Utilities::wpc_send_email( $email_args );

		}

		// send notification email to user
		if ( !isset( $settings['wpc_user_notification_for_booking_req'] ) || ( isset( $settings['wpc_user_notification_for_booking_req'] ) && $settings['wpc_user_notification_for_booking_req'] == "on" ) ) {
			$mail_to         = $args['wpc_email'];
			$mail_subject    = isset( $settings['wpc_new_req_email_subject'] ) ? $settings['wpc_new_req_email_subject'] : "";
			$default_message = $args['message'] . esc_html__( "Invoice No: " , "wpcafe") . $args['invoice'] . "";
			$mail_body       = isset( $settings['wpc_new_req_email'] ) && $settings['wpc_new_req_email'] !=="" ? $settings['wpc_new_req_email'] : $default_message;
			$mail_from       = isset($settings['wpc_admin_email_address']) && $settings['wpc_admin_email_address'] !==''  ? $settings['wpc_admin_email_address'] : $settings['sender_email_address'];
			$mail_from_name  = isset($settings['wpc_reply_to_name'])  ? $settings['wpc_reply_to_name']  : esc_html__("Admin","wpcafe");
			$wpc_main_email  = \WpCafe\Core\Modules\Reservation\Hooks::instance()->filter_template_tags( $args['reservation_id'],$mail_body, $args['invoice'] );

			$email_args = array(
				'to'         => $mail_to,
				'subject'    => $mail_subject,
				'mail_body'  => $wpc_main_email,
				'from'       => $mail_from,
				'from_name'  => $mail_from_name
			);
			$result = Wpc_Utilities::wpc_send_email( $email_args );
		}

	  return $result;
	}

	/**
	 * send email to branch for new booking request
	 */
	public static function send_notification_branch_user( $settings , $args ){
		$result = false;

		// send notification email to user
		if ( ( isset( $settings['wpc_allow_location_email'] ) && $settings['wpc_allow_location_email'] == "on" ) ) {
			$mail_to  = $args['wpc_email'];
			$mail_subject    = isset( $settings['wpc_new_req_email_subject'] ) ? $settings['wpc_new_req_email_subject'] : "";
			$default_message = $args['message'] . esc_html__(' Invoice No: ' , 'wpcafe') . $args['invoice'] . "";
			$mail_body       = isset( $settings['wpc_new_req_email'] ) && $settings['wpc_new_req_email'] !=="" ? $settings['wpc_new_req_email'] : $default_message;
			$mail_from       = isset($settings['wpc_admin_email_address']) && $settings['wpc_admin_email_address'] !==''  ? $settings['wpc_admin_email_address'] : $settings['sender_email_address'];
			$mail_from_name  = isset($settings['wpc_reply_to_name'])  ? $settings['wpc_reply_to_name']  : esc_html__('Admin', 'wpcafe');
			$wpc_main_email  = str_replace( $args['wpc_tag_arr'], $args['wpc_value_arr'] , $mail_body );
			$wpc_main_email     = \WpCafe\Core\Modules\Reservation\Hooks::instance()->filter_template_tags( $args['reservation_id'],$mail_body, $args['invoice'] );

			$email_args = array(
				'to'         => $mail_to,
				'subject'    => $mail_subject,
				'mail_body'  => $wpc_main_email,
				'from'       => $mail_from,
				'from_name'  => $mail_from_name
			);
			$result = Wpc_Utilities::wpc_send_email( $email_args );
		}

	return $result;
	}

	/**
	 * Menu price by tax for short code and widget
	 */
	public static function menu_price_by_tax( $product ){
		$price = '';
		if (wc_get_price_excluding_tax($product)) {
			$price      = wc_get_price_excluding_tax($product);
		} else {
			$price      = wc_get_price_including_tax($product);
		}

		return $price;
	}

	/**
	 * Admin page array
	 */
	public static function admin_page_array(){

		$admin_page_arr = [
			'wpcafe_page_cafe_settings',
			'edit-wpc_reservation',
			'wpc_reservation',
			'wpcafe_page_wpcafe_get_help',
			'product',
			'edit-post',
			'post','page',
			'edit-page',
			'edit-wpcafe_location',
			'wpcafe_page_wpc-license',
			'shop_order',
			'toplevel_page_app_settings',
			'wpcafe_page_wpc_shortcode',
			'wpcafe_page_wpc_product_addons',
			'toplevel_page_cafe_menu'
		];

		return $admin_page_arr;
	}

	/**
	 * Get Tab Array From Category List
	 *
	 * @param [type] $wpc_cat_arr
	 *
	 * @since 1.3.3
	 *
	 * @return array tab array
	 */
	public static function get_tab_array_from_category( $wpc_cat_arr ){

		foreach ($wpc_cat_arr as $key => $value) {
			if ($wpc_cat = get_term_by('id', $value, 'product_cat')) {
				$wpc_get_menu_order = get_term_meta($wpc_cat->term_id, 'wpc_menu_order_priority', true);
				$wpc_cat    = get_term_by('id', $value, 'product_cat');
				$cat_name   = ($wpc_cat && $wpc_cat->name ) ? $wpc_cat->name : "";
				$tab_data   = array('post_cats'=>[$value, $wpc_cat->slug],'tab_title' => $cat_name);
				if ($wpc_get_menu_order == '') {
					$food_menu_tabs[$key] = $tab_data;
				} else {
					$food_menu_tabs[$wpc_get_menu_order] = $tab_data;
				}
			}
		}

		return $food_menu_tabs;
	}

	/**
	 * get_category id
	 *
	 * @param [type] $order_id
	 * @param [type] $order
	 *
	 * @since 2.4.1
	 *
	 * @return void
	 */
	public static function get_wpc_taxonomy_ids( $taxonomy='product_cat', $shortcode_cat = "cat_id"){
		$html = '';
		$taxonomy       = $taxonomy;
		$args_cat = array(
			'taxonomy'     => $taxonomy,
			'number'       => 50,
			'hide_empty'   => 0,
		);
		$cats = get_categories( $args_cat );
	
		$html .= '<select  data-cat = "'.esc_attr($shortcode_cat).'" class="wpc-shortcode-select wpc-setting-input" multiple="multiple">';

		foreach($cats as $item):
			$html .='<option value="'. esc_attr($item->term_id) . '">' . ( esc_html($item->name) ) . '</option>';
		endforeach;
		$html .='</select>';

		return $html;

	}

	/**
	 * Replace qoute of sting
	 */
	public static function replace_qoute( $data ){
		if( count( $data )>0 ){
			array_walk( $data , function( &$value , $index )
			{ $value = str_replace(['&#039;','&lsquo;','&quot;'],'', $value); });
		}

		return $data;
	}

	/**
	 * shortcode builder option range
	 */
	public static function get_option_range( $arr=[],  $class="" ) {
		$html = '';
		$html .='<select  class="wpc-setting-input '.esc_attr($class).'">';
		foreach($arr as $key=> $value){
			$html .='<option value="'.esc_html( $key ).'"> '.esc_html($value).' </option>';
		}
		$html .='</select>';

	  return $html;
  }
	/**
	 * shortcode builder hide empty
	 */
	public static function get_show_hide($key){
		$hide_empty = [
			"$key='yes'" => esc_html__('Yes', 'wpcafe'),
			"$key='no'" => esc_html__('No', 'wpcafe'),
		];
		return wp_kses( Wpc_Utilities::get_option_range( $hide_empty, '' ), Wpc_Utilities::wpc_kses_allowed_tags() );
	}
	/**
	 * shortcode builder hide empty
	 */
	public static function get_order($key){
	$order = [
		"$key='ASC'" => esc_html__('ASC', 'wpcafe'),
		"$key='DESC'" => esc_html__('DESC', 'wpcafe'),
	];

		echo wp_kses( Wpc_Utilities::get_option_range( $order, '' ), Wpc_Utilities::wpc_kses_allowed_tags() );
	}

   /**
	 * shortcode builder style
	 */
   public static function get_option_style( $limit , $value_name, $option_name="", $display_name=""  ) {
		$html = '';
		$html .='<select class="wpc-setting-input">';
			for ($i=1; $i <= $limit; $i++) {
				$value = $value_name."="."'".$option_name.$i."'";
				$html .='<option value="'.esc_html( $value ).'" data-formstyle="formstyle'.esc_html($i).'" class="form-style"> '.esc_html($display_name .$i).' </option>';
			}
			$html .= '</select>';

		return $html;
   }

	/**
	* shortcode builder price options
	*/
   public static function get_price_option( $key ) {
		$hide_empty = [
			"$key='yes'" => esc_html__('Yes', 'wpcafe'),
			"$key='no'" => esc_html__('No', 'wpcafe'),
			"$key='min'" => esc_html__('Min Price (For Variation)', 'wpcafe'),
			"$key='max'" => esc_html__('Max Price (For Variation)', 'wpcafe'),
		];
		 echo wp_kses( Wpc_Utilities::get_option_range( $hide_empty, '' ), Wpc_Utilities::wpc_kses_allowed_tags() );
   }

	/**
	 * Get Cafe options key
	 *
	 * @param [type] $key
	 * @param string $default
	 * @return void
	 */
	public static function get_option( $key, $default = '' ) {
		$all_settings = get_option( self::$settings_key );

		return ( isset( $all_settings[$key] ) && $all_settings[$key] != '' ) ? $all_settings[$key] : $default;
	}

	/**
	 * discard empty field from settings
	 *
	 * @param array $form_data
	 * @param string $setting_keys
	 * @return array empty field discarded settings
	 */
	public static function discard_individual_empty_fields_from_settings( $form_data = [], $setting_keys = [] ) {

		if ( ! empty( $setting_keys ) ) {
			foreach ( $setting_keys as $setting_key ) {

				if ( isset( $form_data[ $setting_key ] ) ) {
					$submitted_arr = $form_data[ $setting_key ];
		
					$new_index_arr = []; // for 0 based index after deleting element
					foreach ( $submitted_arr as $index => $value ) {
						if ( ! empty( $value ) ) {
							array_push( $new_index_arr, $value );
						}
					}
		
					$form_data[ $setting_key ] = $new_index_arr;
				}

			}
		}

		return $form_data;
	}

	/**
	 * discard empty field from two dependent/related settings
	 *
	 * @param array $form_data
	 * @param string $related_two_setting_keys
	 * @return array two dependent/related empty field discarded settings content
	 */
	public static function discard_two_related_empty_fields_from_settings( $form_data = [], $related_two_setting_keys = [] ) {


		if ( ! empty( $related_two_setting_keys ) ) {
			foreach ( $related_two_setting_keys as $related_parent_key => $related_child_key ) {

				if ( isset( $form_data[ $related_parent_key ] ) ) {
					$parent_arr = $form_data[ $related_parent_key ];
					$child_arr  = ( isset( $form_data[ $related_child_key ] ) ) ? $form_data[ $related_child_key ] : [];

					// for 0 based index after deleting element
					$new_parent_arr = [];
					$new_child_arr  = [];

					foreach ( $parent_arr as $index => $parent_val ) {
						$parent_val = ltrim( $parent_val );
						// if all empty then discard i.e. if at least one exist then push
						if ( ! ( empty( $parent_val ) && ( isset( $child_arr[ $index ] ) && empty( ltrim ( $child_arr[ $index ] ) )) ) ) {
							array_push( $new_parent_arr, $parent_val );
							array_push( $new_child_arr, $child_arr[ $index ] );
						}
					}

					$form_data[ $related_parent_key ] = $new_parent_arr;
					$form_data[ $related_child_key ]  = $new_child_arr;
				}

			}
		}

		return $form_data;
	}

	/**
	 * discard empty field from three dependent/related settings
	 *
	 * @param array $form_data
	 * @param string $related_three_setting_keys
	 * @return array three dependent/related empty field discarded settings content
	 */
	public static function discard_three_related_empty_fields_from_settings( $form_data = [], $related_three_setting_keys = [] ) {

		if ( ! empty( $related_three_setting_keys ) ) {
			foreach ( $related_three_setting_keys as $index => $three_setting_keys ) {

				$first_key  = $three_setting_keys[0];
				$second_key = $three_setting_keys[1];
				$third_key  = $three_setting_keys[2];

				$first_key_arr  = isset( $form_data[ $first_key ] ) ? $form_data[ $first_key ] : [];
				$second_key_arr = isset( $form_data[ $second_key ] ) ? $form_data[ $second_key ] : [];
				$third_key_arr  = isset( $form_data[ $third_key ] ) ? $form_data[ $third_key ] : [];

				// for 0 based index after deleting element
				$new_first_key_arr  = [];
				$new_second_key_arr = [];
				$new_third_key_arr  = [];

				// loop by second array as checkbox array may not be present
				foreach ( $second_key_arr as $second_index => $second_val ) {
					$first_val = isset( $first_key_arr[ $second_index ] ) ? $first_key_arr[ $second_index ] : [];
					$third_val = isset( $third_key_arr[ $second_index ] ) ? $third_key_arr[ $second_index ] : '';

					// if all empty then discard i.e. if at least one exist then push
					if ( ! ( empty( $second_val ) && empty( $third_val ) ) ) {
						array_push( $new_first_key_arr, $first_val );
						array_push( $new_second_key_arr, $second_val );
						array_push( $new_third_key_arr, $third_val );
					}
				}

				$form_data[ $first_key ]  = $new_first_key_arr;
				$form_data[ $second_key ] = $new_second_key_arr;
				$form_data[ $third_key ]  = $new_third_key_arr;
			}
		}

		return $form_data;
	}

	/**
	 * discard empty field from four dependent/related settings
	 *
	 * @param array $form_data
	 * @param string $related_four_setting_keys
	 * @return array four dependent/related empty field discarded settings content
	 */
	public static function discard_four_related_empty_fields_from_settings( $form_data = [], $related_four_setting_keys = [] ) {

		if ( ! empty( $related_four_setting_keys ) ) {
			foreach ( $related_four_setting_keys as $index => $four_setting_keys ) {

				$first_key  = $four_setting_keys[0];
				$second_key = $four_setting_keys[1];
				$third_key  = $four_setting_keys[2];
				$fourth_key = $four_setting_keys[3];

				$first_key_arr  = isset( $form_data[ $first_key ] ) ? $form_data[ $first_key ] : [];
				$second_key_arr = isset( $form_data[ $second_key ] ) ? $form_data[ $second_key ] : [];
				$third_key_arr  = isset( $form_data[ $third_key ] ) ? $form_data[ $third_key ] : [];
				$fourth_key_arr = isset( $form_data[ $fourth_key ] ) ? $form_data[ $fourth_key ] : [];

				// for 0 based index after deleting element
				$new_first_key_arr  = [];
				$new_second_key_arr = [];
				$new_third_key_arr  = [];
				$new_fourth_key_arr = [];

				// loop by second array as checkbox array may not be present
				foreach ( $second_key_arr as $second_index => $second_val ) {
					$first_val  = isset( $first_key_arr[ $second_index ] ) ? $first_key_arr[ $second_index ] : [];
					$third_val  = isset( $third_key_arr[ $second_index ] ) ? ltrim( $third_key_arr[ $second_index ] ) : ''; // schedule name
					$fourth_val = isset( $fourth_key_arr[ $second_index ] ) ? $fourth_key_arr[ $second_index ] : ''; // seat capacity

					// if all empty then discard i.e. if at least one exist then push
					if ( ! ( empty( $first_val ) && empty( $second_val ) && empty( $third_val ) && empty( $fourth_val ) ) ) {
						array_push( $new_first_key_arr, $first_val );
						array_push( $new_second_key_arr, $second_val );
						array_push( $new_third_key_arr, $third_val );
						array_push( $new_fourth_key_arr, $fourth_val );
					}
				}

				$form_data[ $first_key ]  = $new_first_key_arr;
				$form_data[ $second_key ] = $new_second_key_arr;
				$form_data[ $third_key ]  = $new_third_key_arr;
				$form_data[ $fourth_key ] = $new_fourth_key_arr;
			}
		}

		return $form_data;
	}

	/**
	 * discard empty field from five dependent/related settings
	 *
	 * @param array $form_data
	 * @param string $related_five_setting_keys
	 * @return array five dependent/related empty field discarded settings content
	 */
	public static function discard_five_related_empty_fields_from_settings( $form_data = [], $related_five_setting_keys = [] ) {

		if ( ! empty( $related_five_setting_keys ) ) {
			foreach ( $related_five_setting_keys as $index => $five_setting_keys ) {

				$first_key  = $five_setting_keys[0];
				$second_key = $five_setting_keys[1];
				$third_key  = $five_setting_keys[2];
				$fourth_key = $five_setting_keys[3];
				$fifth_key  = $five_setting_keys[4];
				
				$first_key_arr  = isset( $form_data[ $first_key ] ) ? $form_data[ $first_key ] : [];
				$second_key_arr = isset( $form_data[ $second_key ] ) ? $form_data[ $second_key ] : [];
				$third_key_arr  = isset( $form_data[ $third_key ] ) ? $form_data[ $third_key ] : [];
				$fourth_key_arr = isset( $form_data[ $fourth_key ] ) ? $form_data[ $fourth_key ] : [];
				$fifth_key_arr  = isset( $form_data[ $fifth_key ] ) ? $form_data[ $fifth_key ] : [];

				// for 0 based index after deleting element
				$new_first_key_arr  = [];
				$new_second_key_arr = [];
				$new_third_key_arr  = [];
				$new_fourth_key_arr = [];
				$new_fifth_key_arr  = [];

				// loop by second array as checkbox array may not be present
				$auto_index = 0;
				foreach ( $second_key_arr as $second_index => $second_val_arr ) {

					// here all values array
					$first_val_arr  = isset( $first_key_arr[ $second_index ] ) ? $first_key_arr[ $second_index ] : [];
					$third_val_arr  = isset( $third_key_arr[ $second_index ] ) ? $third_key_arr[ $second_index ] : '';
					$fourth_val_arr = isset( $fourth_key_arr[ $second_index ] ) ? $fourth_key_arr[ $second_index ] : '';
					$fifth_val_arr  = isset( $fifth_key_arr[ $second_index ] ) ? $fifth_key_arr[ $second_index ] : '';

					// retrieve value from array
					// $first_val = is_array( $first_val_arr ) ? reset( $first_val_arr ) : $first_val_arr;

					$days_to_row_index = 0;
					$days_need_to_push = false;
					foreach ( $second_val_arr as $row_index => $second_val ) {
						$third_val  = isset( $third_val_arr[ $row_index ] ) ? $third_val_arr[ $row_index ] : '';
						$fourth_val = isset( $fourth_val_arr[ $row_index ] ) ? ltrim( $fourth_val_arr[ $row_index ] ) : ''; // schedule name
						$fifth_val  = isset( $fifth_val_arr[ $row_index ] ) ? $fifth_val_arr[ $row_index ] : ''; // seat capacity

						// if all empty then discard i.e. if at least one exist then push
						if ( ! ( empty( $second_val ) && empty( $third_val ) && empty( $fourth_val ) && empty( $fifth_val ) ) ) {
							$days_need_to_push = true;
							$new_second_key_arr[ $auto_index ][ $days_to_row_index ] = $second_val;
							$new_third_key_arr[ $auto_index ][ $days_to_row_index ]  = $third_val;
							$new_fourth_key_arr[ $auto_index ][ $days_to_row_index ] = $fourth_val;
							$new_fifth_key_arr[ $auto_index ][ $days_to_row_index ]  = $fifth_val;
							$days_to_row_index++;
						}
					}

					if ( $days_need_to_push ) {
						$new_first_key_arr[ $auto_index ] = $first_val_arr;
						$auto_index += 1;
					}
				}

				$form_data[ $first_key ]  = $new_first_key_arr;
				$form_data[ $second_key ] = $new_second_key_arr;
				$form_data[ $third_key ]  = $new_third_key_arr;
				$form_data[ $fourth_key ] = $new_fourth_key_arr;
				$form_data[ $fifth_key ]  = $new_fifth_key_arr;
			}
		}

		return $form_data;
	}

	/**
	 * generate shortcode button
	 */
	public static function generate_shortcode_button($label_text, $desc="") {
		$description = $desc == "" ? esc_html__('You can generate shortcode', 'wpcafe') : $desc;
		?>
		<div class="wpc-label-item">
			<div class="wpc-label">
				<label for="wpc_reservation_form"><?php echo esc_html($label_text); ?></label>
				<div class="wpc-desc"> <?php echo esc_html($description); ?></div>
			</div>
			<div class="wpc-meta">
				<button type="button" class="wpc-btn wpc-btn-border wpc-btn-secondary s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'wpcafe'); ?></button>

			</div>
		</div>

		<?php
	}

	/**
	 * generate shortcode button popup
	 */
	public static function generate_shortcode_button_popup($value, $input_id) {
		?>
		<button type="button"  class="wpc-btn shortcode-generate-btn"><?php echo esc_html__('Generate', 'wpcafe'); ?></button>

		<div class="attr-form-group copy_shortcodes">
			<div class="wpc-meta">
				<input type="text" readonly name="<?php echo esc_attr($input_id); ?>" id="<?php echo esc_attr($input_id); ?>" value="<?php echo esc_attr($value); ?>" class="wpc-setting-input wpc_include_shortcode" placeholder="<?php esc_html_e('Label Text', 'wpcafe'); ?>">
				<button type="button" title="copy" onclick="copyTextData('<?php echo esc_attr($input_id); ?>');" class="etn_copy_button wpc-btn"><span class="dashicons dashicons-category"></span></button>
			</div>
		</div>
		<?php
	}

	/**
	 * generate shortcode button popup
	 */
	public static function generate_shortcode_show_hide($label_text, $show_hide_key) {
		$html = '';
		$html .='<div class="wpc-col-lg-6">
			<div class="wpc-field-wrap">
				<h3>'.esc_html($label_text).'</h3>
				'.Wpc_Utilities::get_show_hide($show_hide_key).'
			</div>
		</div>';

		return $html;
	}

	/**
	 * check selected date format is valid or not: if not update with cafe default format
	 *
	 * @return void
	 */
	public static function check_date_format_is_valid() {
		$date_format = get_option( 'date_format' );

		$date_format = preg_replace( '/(?<!\\\\)[Yyo]/', '%1$s', $date_format );
		$date_format = preg_replace( '/(?<!\\\\)[FmMn]/', '%2$s', $date_format );
		$date_format = preg_replace( '/(?<!\\\\)[jd]/', '%3$s', $date_format );

		// Fallback to ISO date format if year, month, or day are missing from the date format.
		if ( 1 !== substr_count( $date_format, '%1$s' ) || 1 !== substr_count( $date_format, '%2$s' ) || 1 !== substr_count( $date_format, '%3$s' ) ) {
			$date_format = '%1$s-%2$s-%3$s';

			update_option( 'date_format', WPCAFE_DEFAULT_DATE_FORMAT );
		}
	}

	/**
	 * select food locations
	 *
	 * @return void
	 */
	public static function select_food_locations_filter($atts) {

		// shortcode option
        $atts = extract(shortcode_atts(
            [
                'location_alignment'   => 'center'
            ], $atts ));
        
		$food_location  = Wpc_Utilities::get_location_data ( esc_html__("Select food location", "wpcafe") , esc_html__("No location is set", "wpcafe"),"id" );
		?>
		<!-- select location -->
		<form class="location_menu">
			<select id="filter_location" name="filter_location" class="filter-location <?php echo esc_attr($location_alignment); ?>">
				<?php foreach ( $food_location as $key => $value ) { ?>
					<option value="<?php echo esc_attr($key); ?>"><?php echo esc_html__( $value, 'wpcafe' ) ?></option>
				<?php } ?>
			</select>
		</form>

		<div id="location_change" class="location_modal hide_field location_change">
				<div class="modal-content">
						<div>
								<?php echo esc_html__("By changing your current location, You will
												lose your selected item from the cart.","wpcafe");?>
						</div>
						<button class="change_yes wpc-btn wpc-btn-primary"><?php echo esc_html__( "Yes", "wpcafe" );?></button>
						<button class="change_no wpc-btn wpc-btn-primary"><?php echo esc_html__( "No", "wpcafe" );?></button>
				</div>
		</div>
		<?php
	}

	/**
	 * select food locations
	 *
	 * @return void
	 */
	public static function get_pro(){
		$html = '';
		$html .='<div class="wpc-pro">
			<a href="https://themewinter.com/wp-cafe/#ts-pricing-list" target="_blank">';
			$html .= esc_html__('Upgrade to  Pro','wpcafe');
			$html .='<svg width="8" height="9" viewBox="0 0 8 9" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1 7.5L7 1.5M7 1.5H1M7 1.5V7.5" stroke="#0A1018" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
		</div>';

		return $html;
	}

	public static function get_query_cache( $query ){

		$cache_key = 'wpcafe_query_' . md5($query);
		return $cache_key;
	}

	public static function get_formatted_time($timeString){
		$time_format = get_option('time_format');
		$dateTime = \DateTime::createFromFormat($time_format, $timeString);
		if ($dateTime) {
		    $timestamp = $dateTime->getTimestamp();
		    return date_i18n($time_format, $timestamp );
		} else {
		    return $timeString;
		}
	}

	public static function get_formatted_date($dateString){
		$date_format = get_option('date_format');
		$dateTime = \DateTime::createFromFormat($date_format, $dateString);
		if ($dateTime) {
		    $timestamp = $dateTime->getTimestamp();
		    return date_i18n($date_format, $timestamp );
		} else {
		    return $dateString;
		}
	}
}
