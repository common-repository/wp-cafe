<?php

namespace WpCafe\Core\Shortcodes;
use \WpCafe\Utils\Wpc_Utilities;

defined( 'ABSPATH' ) || exit;

class Template_Functions {

	/**
	 * Food Menu List Template One
	 */
	public static function wpc_food_menu_list_template( $args ){
			extract( $args );
			?>
				<div class="wpc-food-menu-item wpc-row">
					<?php
					if ($show_thumbnail == 'yes' || $show_thumbnail == 'on') { ?>
							<div class="wpc-col-md-4">
								<!-- thumbnail -->
								<?php if ($product->get_image()) { ?>
										<div class="wpc-food-menu-thumb">
												<a href="<?php echo esc_url( $permalink ); ?>" class="<?php echo esc_attr($class); ?>">
														<?php echo wp_kses( $product->get_image('woocommerce_thumbnail'), Wpc_Utilities::wpc_kses_allowed_tags()) ; ?>
												</a>
										</div>
								<?php  } ?>
							</div>
					<?php }  ?>
					<div class="<?php echo esc_attr($col); ?>">
							<div class="wpc-food-inner-content">
									<!-- product tag and tax -->
									<div class="wpc-menu-tag-wrap">
											<?php
											$show_item_status == 'yes' ? Wpc_Utilities::wpc_tag( $product->get_id() , $product->is_in_stock() ) : "";
											if ($product->get_price_suffix() != '') { ?>
													<ul class="wpc-menu-tag">
															<li>
																	<?php if (wc_get_price_including_tax($product)) {
																			// get percentage tax
																			echo wp_kses($product->get_price_suffix(), Wpc_Utilities::wpc_kses_allowed_tags() );
																	} ?>
															</li>
													</ul>
													<?php
											} ?>
									</div>

									<h3 class="wpc-post-title wpc-title-with-border">
											<a href="<?php echo esc_url($permalink); ?>" class="<?php echo esc_attr($class); ?>"> <?php echo esc_attr( $product->get_name() );  ?> </a>
											<span class="wpc-title-border"></span>
											<?php
											if( $product->get_type() !== 'variable' && $wpc_price_show !== 'no') {
													?>
													<span class="wpc-menu-price"><?php
													echo wp_kses($product->get_price_html(), Wpc_Utilities::wpc_kses_allowed_tags() );
													 ?></span></span>
													<?php
											} else {
													if( $wpc_price_show !== 'no'){
															// variation price.
															$variation_price = $product->get_variation_prices( true ); // true for getting tax price
															$var_price = '';
															if( is_array( $variation_price ) && isset( $variation_price['price'] ) ){
																	if( $wpc_price_show == 'yes' || $wpc_price_show == 'min'){
																			$var_price .= "<span class='min_price'>". get_woocommerce_currency_symbol() . array_shift($variation_price['price']) . "</span>";
																	}
																	if( $wpc_price_show == 'yes' ){
																			$var_price .= " - ";
																	}
																	if( $wpc_price_show == 'yes' || $wpc_price_show == 'max'){
																			$var_price .= "<span class='max_price'>". get_woocommerce_currency_symbol() . array_pop($variation_price['price']) . "</span>";
																	}
															}

													?>
													<span class="wpc-menu-currency"><span class="wpc-menu-price">
														<?php echo wp_kses($var_price, Wpc_Utilities::wpc_kses_allowed_tags() );
														?></span></span>
													<?php
													}
											}
											?>
									</h3>
									<?php
											if(class_exists('Wpcafe_Multivendor') && !empty( $wpc_show_vendor ) && $wpc_show_vendor == 'yes') {
													apply_filters( 'wpcafe_multivendor_seller', $product->get_id());
											}
									?>
									<p>
											<?php
											if ($wpc_show_desc == 'yes') {
													echo  esc_html( Wpc_Utilities::wpcafe_trim_words( get_the_excerpt( $product->get_id() ) , $wpc_desc_limit) );
											}
											?>
									</p>
									<?php
											// cart button
											$add_cart_args = array(
													'product'       => $product,
													'cart_button'   => $wpc_cart_button,
													'wpc_btn_text'  => "",
													'customize_btn' => "",
													'widget_id'     => $unique_id,
													'cart_icon'         => !empty($cart_icon),
													'customization_icon'=> !empty($customization_icon)
											);

											echo wp_kses(Wpc_Utilities::product_add_to_cart( $add_cart_args ), Wpc_Utilities::wpc_kses_allowed_tags() );
									?>
							</div>
					</div>
					<div class="wpc_loader_wrapper">
							<div class="loder-dot dot-a"></div>
							<div class="loder-dot dot-b"></div>
							<div class="loder-dot dot-c"></div>
							<div class="loder-dot dot-d"></div>
							<div class="loder-dot dot-e"></div>
							<div class="loder-dot dot-f"></div>
							<div class="loder-dot dot-g"></div>
							<div class="loder-dot dot-h"></div>
					</div>
			</div>

			<?php
	}

	/**
	 * Food Menu List Template Two
	 */
	public static function wpc_food_menu_list_template_two( $args ){
			extract( $args );
			?>

			<div class="wpc-food-menu-item style2">
							<div class="wpc-row">
									<div class="wpc-col-md-8 wpc-align-self-center">
											<div class="wpc-food-inner-content">
													<!-- display tag -->
											<div class="wpc-menu-tag-wrap">
											<?php
											$show_item_status == 'yes' ? Wpc_Utilities::wpc_tag( $product->get_id() , $product->is_in_stock() ) : "";
											$price = Wpc_Utilities::menu_price_by_tax( $product );
											?>
													<?php
														if ($show_item_status == 'yes' && $product->get_price_suffix() != '') {
													?>
															<ul class="wpc-menu-tag">
																	<li>
																			<?php
																	if (wc_get_price_including_tax($product)) {
																			// get percentage tax
																			echo wp_kses($product->get_price_suffix(), Wpc_Utilities::wpc_kses_allowed_tags() );
																	}
																	?>
																	</li>
															</ul>
															<?php
															}
													?>
													</div>
													<h3 class="wpc-post-title">
															<a href="<?php echo esc_url( $permalink ); ?>"
																	class="<?php echo esc_attr( $class); ?>"><?php echo esc_html($product->get_name());  ?>
															</a>

													</h3>
													<?php
															if(class_exists('Wpcafe_Multivendor') && !empty( $wpc_show_vendor ) && $wpc_show_vendor == 'yes') {
																	apply_filters( 'wpcafe_multivendor_seller', $product->get_id());
															}
													?>
													<?php  if( $wpc_show_desc == 'yes' ){ ?>
													<p>
															<?php echo  esc_html( Wpc_Utilities::wpcafe_trim_words( get_the_excerpt($product->get_id() ), $wpc_desc_limit) ); ?>
													</p>
													<?php } ?>

											</div>
									</div>
									<!-- thumbnail -->
									<?php 
									if ( $show_thumbnail == 'yes' ) {

											if ($product->get_image()) { 
													?>
															<div class="wpc-col-md-4">
																	<div class="wpc-food-menu-thumb">
																			<?php 
																	if( $product->get_type() !== 'variable' && $wpc_price_show !== 'no') {
																			?>
																			<span class="wpc-menu-currency">
																					<?php 
																					echo wp_kses($product->get_price_html(), Wpc_Utilities::wpc_kses_allowed_tags() );
																					?></span>
																			</span>
																			<?php
																	} else {
																			if( $wpc_price_show !== 'no'){
																					// variation price.
																					$variation_price = $product->get_variation_prices( true ); // true for getting tax price 

																					$var_price = '';
																					if( is_array( $variation_price ) && isset( $variation_price['price'] ) ){
																							if( $wpc_price_show == 'yes' || $wpc_price_show == 'min'){
																									$var_price .= "<span class='min_price'>". get_woocommerce_currency_symbol() . array_shift($variation_price['price']) . "</span>";
																							}
																							if( $wpc_price_show == 'yes' ){
																									$var_price .= " - ";
																							}
																							if( $wpc_price_show == 'yes' || $wpc_price_show == 'max'){
																									$var_price .= "<span class='max_price'>". get_woocommerce_currency_symbol() . array_pop($variation_price['price']) . "</span>";
																							}
																					}

																			?>
																					<span class="wpc-menu-currency"><span class="wpc-menu-price"><?php
																					        echo wp_kses($var_price, Wpc_Utilities::wpc_kses_allowed_tags() );
																					    ?></span></span>
																			<?php
																			}
																	}
																	?>
																			<a href="<?php echo esc_url(get_permalink($product->get_id())); ?>"
																					class="<?php echo esc_attr( $class ); ?>">
																					<?php echo wp_kses( $product->get_image('woocommerce_thumbnail'), Wpc_Utilities::wpc_kses_allowed_tags()) ; ?>
																			</a>
																			<?php
																			// cart button
																			$add_cart_args = array(
																					'product'       => $product,
																					'cart_button'   => $wpc_cart_button,
																					'wpc_btn_text'  => "",
																					'customize_btn' => "",
																					'widget_id'     => $unique_id,
																					'cart_icon'         => $cart_icon,
																					'customization_icon'=> $customization_icon
																			);
																			echo wp_kses(Wpc_Utilities::product_add_to_cart( $add_cart_args ), Wpc_Utilities::wpc_kses_allowed_tags() );
																	?>
																	</div>
															</div>
															<?php
											}
									}
							?>
							</div>
					</div>
			<?php
	}

	/**
	 * Food Menu List Template Three
	 */
	public static function wpc_food_menu_list_template_three( $args ){
			extract( $args );
			?>
					<div class="wpc-col-lg-<?php echo esc_attr($column_desktop); ?> wpc-col-md-<?php echo esc_attr($column_tablet); ?> wpc-col-sm-<?php echo esc_attr($column_mobile); ?>">
							<div class="wpc-food-single-item">
									<div class="wpc-food-inner-content">
											<!-- display tag -->
											<?php
											$show_item_status == 'yes' ? Wpc_Utilities::wpc_tag( $product->get_id() , $product->is_in_stock() ) : "";
											$price = Wpc_Utilities::menu_price_by_tax( $product );
											?>
											<?php
											if ($show_item_status == 'yes' && $product->get_price_suffix() != '') {
													?>
													<div class="wpc-menu-tag-wrap">
															<ul class="wpc-menu-tag">
															<li>
																	<?php
																	if (wc_get_price_including_tax($product)) {
																			// get percentage tax
																			echo wp_kses($product->get_price_suffix(), Wpc_Utilities::wpc_kses_allowed_tags() );
																	}
																	?>
															</li>
													</ul>
													</div>
													<?php
											}
											?>
											<h3 class="wpc-post-title">
													<a href="<?php echo esc_url( $permalink ); ?>"
														class="<?php echo esc_attr( $class); ?>"><?php echo esc_html($product->get_name());  ?>
													</a>

											</h3>
											<?php
													if(class_exists('Wpcafe_Multivendor') && !empty( $wpc_show_vendor ) && $wpc_show_vendor == 'yes') {
															apply_filters( 'wpcafe_multivendor_seller', $product->get_id());
													}
											?>
											<?php  if( $wpc_show_desc == 'yes' ){ ?>
													<p>
															<?php echo  esc_html( Wpc_Utilities::wpcafe_trim_words( get_the_excerpt($product->get_id() ), $wpc_desc_limit) ); ?>
													</p>
											<?php } ?>
									</div>
									<!-- thumbnail -->
									<?php
									if ( $show_thumbnail == 'yes' ) {

											if ($product->get_image()) {
													?>
													<div class="wpc-food-menu-thumb">
															<?php
															if( $product->get_type() !== 'variable' && $wpc_price_show !== 'no') {
																	?>
																	<span class="wpc-menu-currency">
																					<?php 
																					echo wp_kses($product->get_price_html(), Wpc_Utilities::wpc_kses_allowed_tags() );
																					?></span>
																	</span>
																	<?php
															} else {
																	if( $wpc_price_show !== 'no'){
																			// variation price 
																			$variation_price = $product->get_variation_prices( true ); // true for getting tax price 
																			$var_price = '';
																			if( is_array( $variation_price ) && isset( $variation_price['price'] ) ){
																					if( $wpc_price_show == 'yes' || $wpc_price_show == 'min'){
																							$var_price .= "<span class='min_price'>". get_woocommerce_currency_symbol() . array_shift($variation_price['price']) . "</span>";
																					}
																					if( $wpc_price_show == 'yes' ){
																							$var_price .= " - ";
																					}
																					if( $wpc_price_show == 'yes' || $wpc_price_show == 'max'){
																							$var_price .= "<span class='max_price'>". get_woocommerce_currency_symbol() . array_pop($variation_price['price']) . "</span>";
																					}
																			}
																	?>
																			<span class="wpc-menu-currency"><span class="wpc-menu-price">
																				<?php
																					        echo wp_kses($var_price, Wpc_Utilities::wpc_kses_allowed_tags() );
																					    ?>
																			</span></span>
																	<?php
																	}
															}
															?>

															<?php
															// cart button
															$add_cart_args = array(
																	'product'       => $product,
																	'cart_button'   => $wpc_cart_button,
																	'wpc_btn_text'  => "",
																	'customize_btn' => "",
																	'widget_id'     => $unique_id,
																	'cart_icon'         => $cart_icon,
																	'customization_icon'=> $customization_icon
															);

															echo wp_kses( Wpc_Utilities::product_add_to_cart( $add_cart_args ), Wpc_Utilities::wpc_kses_allowed_tags() );
															?>
															<a href="<?php echo esc_url(get_permalink($product->get_id())); ?>"
																class="<?php echo esc_attr( $class ); ?>">
																	<?php echo wp_kses( $product->get_image('woocommerce_thumbnail'), Wpc_Utilities::wpc_kses_allowed_tags()) ; ?>
															</a>
													</div>
													<?php
											}
									}
									?>
							</div>
					</div>
			<?php
	}

	/**
	 * Food tab list
	 *
	 * @param [type] $food_menu_tabs
	 * @return void
	 */
	public static function render_food_menu_tab_nav( $food_menu_tabs ){
			?>
			<ul class="wpc-nav">
			<?php
			if( is_array( $food_menu_tabs ) && count( $food_menu_tabs )>0 ){
					foreach ($food_menu_tabs as $tab_key => $value) {
							$active_class = (($tab_key == array_keys($food_menu_tabs)[0]) ? 'wpc-active' : ' ');
							$cat_id       = isset($value['post_cats'][0] ) ? intval( $value['post_cats'][0] ) : 0 ;
							?>
							<li>
									<a href='#' class='wpc-tab-a <?php echo esc_attr($active_class); ?>' data-id='tab_<?php echo intval($tab_key); ?>'
									data-cat_id='<?php echo esc_attr( $cat_id ); ?>'>
											<span><?php echo esc_html($value['tab_title']); ?></span>
									</a>
							</li>
							<?php
					}
			}
			?>
			</ul>
			<?php
	}

	/**
	 * Render Food Menu Tab Holder Markup
	 *
	 * @param [type] $active_class
	 * @param [type] $content_key
	 * @param [type] $cat_id
	 * @param [type] $unique_id
	 * @param [type] $products
	 * @param [type] $style
	 * @param [type] $wpc_cart_button
	 * @param [type] $wpc_show_desc
	 * @param [type] $show_thumbnail
	 * @param [type] $title_link_show
	 * @param [type] $show_item_status
	 * @param [type] $wpc_desc_limit
	 * 
	 * @since 1.3.3
	 * 
	 * @return html markup
	 */
	public static function render_food_menu_tab_product_block( $args ){
			extract( $args );
			?>
			<div class='wpc-tab <?php echo esc_attr($active_class); ?>' data-id='tab_<?php echo intval($content_key); ?>' data-cat_id='<?php echo  esc_attr($cat_id);?>'>
					<div class="tab_template_<?php echo esc_attr( $cat_id.'_'.$unique_id );?>"></div>
					<div class="template_data_<?php echo esc_attr( $cat_id.'_'.$unique_id );?>">
							<?php include \Wpcafe::plugin_dir() . "widgets/wpc-food-menu-tab/style/{$style}.php"; ?>
					</div>
			</div><!-- Tab pane 1 end -->
			<?php 
	}

	public static function modal_markup( $wpc_locations, $store_id = null ){
			?>
			<div id="wpc_location_modal" class="wpc_modal">
					<!-- Modal content -->
					<div class="modal-content">
							<?php 
							if(!is_null($store_id)){
									?>
									<input type='hidden' class="wpc-location-store" name='wpc-store-id' value="<?php echo esc_attr( $store_id );?>"/>
									<?php
							}
							?>
							<select name="wpc-location" class="wpc-location">
									<?php
									// get wpcafe locations
									foreach ( $wpc_locations as $key => $value) {
											?> 
											<option value="<?php echo esc_html( $key ) ?>" <?php echo count($wpc_locations) <= 2? "selected='selected'" : "" ?> ><?php echo esc_html( $value ) ?></option>  
											<?php 
									}
									?>
							</select>
							<button class="wpc-select-location wpc-btn wpc-btn-primary"><?php echo esc_html__( "Ok", "wpcafe" );?></button>
							<button class="wpc-close wpc-btn"> X </button>
					</div>
			</div>
			<?php
	}

}
