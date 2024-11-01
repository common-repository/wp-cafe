<?php
use WpCafe\Utils\Wpc_Utilities;
?>

<ul class="wpc-settings-tab nav nav-tabs wpc-tab">
		<li>
				<a href="#" class="nav-tab nav-tab-active" data-id="food-ordering-tab">
						<i class="wpcafe-settings-1"></i>
						<span><?php echo esc_html__('Food Ordering', 'wpcafe'); ?></span>
				</a>
		</li>
		<li>
				<a href="#" class="nav-tab" data-id="reservation-tab">
						<i class="wpcafe-settings-1"></i>
						<span><?php echo esc_html__('Reservation', 'wpcafe'); ?></span>
				</a>
		</li>
</ul>

<div class="tab-content settings-content-wraps">
	<div class="wpc-tab-style2">
		<div class="wpc-tab-content wpc_tab_content">
			<div id='food-ordering-tab' data-id='tab_food-ordering-tab' class='tab-pane active'>
				<!-- Food Menu -->
				<div class="shortcode-generator-wrap food_menu_1">
						<div class="shortcode-generator-main-wrap">
								<div class="shortcode-generator-inner">
										<div class="shortcode-popup-close">x</div>

										<div class="wpc-row">
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Select Template', 'wpcafe'); ?></h3>
																<?php
																		$wpc_food_menu = [
																				"wpc_food_menu_list" => esc_html__('Food Menu List', 'wpcafe'),
																				"wpc_food_menu_tab" => esc_html__('Food Menu Tab', 'wpcafe'),
																		];
																?>
																<?php 
																echo wp_kses( Wpc_Utilities::get_option_range( $wpc_food_menu, 'free-options' ), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>
														</div>
												</div>
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Select Style', 'wpcafe'); ?></h3>
														<div class="list-style">
																		<?php  
																		echo wp_kses( Wpc_Utilities::get_option_style( 3 ,'style','style-', 'Style ' ), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>
																</div>
																<div class="tab-style wpc-d-none">
																		<?php
																		echo wp_kses( Wpc_Utilities::get_option_style( 2 ,'style','style-', 'Style ' ), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>
														</div>
														</div>
												</div>
										</div>

										<div class="wpc-row">
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Order', 'wpcafe'); ?></h3>
																		<?php Wpc_Utilities::get_order('wpc_menu_order'); ?>
														</div>
												</div>
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Product Count', 'wpcafe'); ?></h3>
																<input type="number" data-count ="<?php echo esc_attr('no_of_product') ?>" class="post_count wpc-setting-input" value="20">
														</div>
												</div>
										</div>
										<div class="wpc-row">
												<div class="wpc-col-lg-12">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Select Category', 'wpcafe'); ?></h3>
																<?php echo wp_kses( Wpc_Utilities::get_wpc_taxonomy_ids('product_cat','wpc_food_categories'), Wpc_Utilities::wpc_kses_allowed_tags() );
																?>
														</div>
												</div>
										</div>

										<div class="wpc-row">

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Description', 'wpcafe'),'wpc_show_desc'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Description Limit', 'wpcafe'); ?></h3>
																<input type="number" data-count ="<?php echo esc_attr('wpc_desc_limit') ?>" class="post_count wpc-setting-input" value="20">
														</div>
												</div>
										</div>

										<div class="wpc-row">
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Enable title link?', 'wpcafe'); ?></h3>
																		<select class="wpc-setting-input">
																				<option value="title_link_show='yes'"><?php echo esc_html__('Yes', 'wpcafe') ?></option>
																				<option value="title_link_show='no'"><?php echo esc_html__('no', 'wpcafe') ?></option>
																		</select>
														</div>
												</div>

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show item status?', 'wpcafe'), 'title_link_show'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

										</div>
										<div class="wpc-row">

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Product Thumbnail', 'wpcafe'), 'product_thumbnail'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show cart button', 'wpcafe'), 'wpc_cart_button'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

										</div>
										<div class="wpc-row">

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show price', 'wpcafe'), 'wpc_price_show'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

										</div>

										<?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_food_menu_tab]', 'wpc_food_menu_tab-shortcode'); ?>            

								</div>
						</div>

						<?php Wpc_Utilities::generate_shortcode_button(esc_html__('Food Menu', 'wpcafe')); ?>

				</div>

				<!-- Food Menu Filter by location-->
				<div class="shortcode-generator-wrap food_menu_2">
						<div class="shortcode-generator-main-wrap">
								<div class="shortcode-generator-inner">
										<div class="shortcode-popup-close">x</div>

										<div class="wpc-row">
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Select Template', 'wpcafe'); ?></h3>
																<select  class="get_template wpc-setting-input">
																		<option value="wpc_food_location_menu"> <?php echo esc_html__(' Food Menu List', 'wpcafe'); ?> </option>
																</select>
														</div>
												</div>
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Select Style', 'wpcafe'); ?></h3>
																<?php
																		$style_count = 1;
																		echo wp_kses( Wpc_Utilities::get_option_style( $style_count ,'style','style-', 'Style ' ), Wpc_Utilities::wpc_kses_allowed_tags() ); 
																?>
														</div>
												</div>
										</div>

										<div class="wpc-row">
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Order', 'wpcafe'); ?></h3>
																<?php Wpc_Utilities::get_order('wpc_menu_order');?>
														</div>
												</div>
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Product Count', 'wpcafe'); ?></h3>
																<input type="number" data-count ="<?php echo esc_attr('no_of_product') ?>" class="post_count wpc-setting-input" value="20">
														</div>
												</div>
										</div>

										<div class="wpc-row">

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Description', 'wpcafe'), 'wpc_show_desc'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Description Limit', 'wpcafe'); ?></h3>
																<input type="number" data-count ="<?php echo esc_attr('wpc_desc_limit') ?>" class="post_count wpc-setting-input" value="20">
														</div>
												</div>
										</div>

										<div class="wpc-row">

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Enable title link?', 'wpcafe'), 'title_link_show'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show item status?', 'wpcafe'), 'show_item_status'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

										</div>
										<div class="wpc-row">

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Product Thumbnail', 'wpcafe'), 'show_thumbnail'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show cart button', 'wpcafe'), 'wpc_cart_button'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

										</div>

										<div class="wpc-row">

												<?php 
												echo wp_kses( Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show delivery time', 'wpcafe'), 'wpc_delivery_time_show'), Wpc_Utilities::wpc_kses_allowed_tags() ); ?>

												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Show price', 'wpcafe'); ?></h3>
																<?php Wpc_Utilities::get_price_option('wpc_price_show');?>
														</div>
												</div>
										</div>

										<?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_food_location_menu]', 'wpc_food_location_menu-shortcode'); ?>            

								</div>
						</div>

						<?php Wpc_Utilities::generate_shortcode_button(esc_html__('Filter Food Menu By Location', 'wpcafe')); ?>

				</div>

				<!-- Food Menu Filter by location-->
				<div class="shortcode-generator-wrap food_menu_2">
						<div class="shortcode-generator-main-wrap">
								<div class="shortcode-generator-inner">
										<div class="shortcode-popup-close">x</div>

										<div class="wpc-row">
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap">
																<h3><?php echo esc_html__('Select Template', 'wpcafe'); ?></h3>
																<select class="get_template wpc-setting-input">
																		<option value="food_location_filter"> <?php echo esc_html__('Location Filter', 'wpcafe'); ?> </option>
																</select>
														</div>
												</div>
												<div class="wpc-col-lg-6">
														<div class="wpc-field-wrap location_alignment">
																<h3><?php echo esc_html__('Alignment', 'wpcafe'); ?></h3>
																<select class="wpc-setting-input" data-cat="<?php echo esc_html__('location_alignment','wpcafe');?>">
																		<option value="left"> <?php echo esc_html__('left', 'wpcafe'); ?> </option>
																		<option value="right"> <?php echo esc_html__('right', 'wpcafe'); ?> </option>
																		<option value="center" selected> <?php echo esc_html__('center', 'wpcafe'); ?> </option>
																</select>
														</div>
												</div>
										</div>

										<?php Wpc_Utilities::generate_shortcode_button_popup('[food_location_filter]', 'food_location_filter-shortcode'); ?>            
								</div>
						</div>
						<?php Wpc_Utilities::generate_shortcode_button(esc_html__('Location Filter', 'wpcafe')); ?>
				</div>

				<?php
						apply_filters( 'wpcafe/key_options/hook_settings', false );
				?>

			</div>

			<div id='reservation-tab' data-id='tab_reservation-tab' class='tab-pane'>
					<!-- reservation form start -->
					<div class="shortcode-generator-wrap reserv-form-style">
							<div class="shortcode-generator-main-wrap">
									<div class="shortcode-generator-inner">
											<div class="shortcode-popup-close">x</div>

											<div class="wpc-row">
													<div class="wpc-col-lg-6">
															<div class="wpc-field-wrap">
																	<h3><?php echo esc_html__('Select Template', 'wpcafe'); ?></h3>
																	<select class="get_template wpc-setting-input">
																			<option value="wpc_reservation_form"> <?php echo esc_html__(' Reservation Form', 'wpcafe'); ?> </option>
																	</select>
															</div>
													</div>
													<div class="wpc-col-lg-6">
															<div class="wpc-field-wrap">
																	<h3><?php echo esc_html__('Select Style', 'wpcafe'); ?></h3>
																	<?php
																	echo wp_kses( Wpc_Utilities::get_option_style( 2, 'form_style','', 'Style ' ), Wpc_Utilities::wpc_kses_allowed_tags() );
																	?>
															</div>
													</div>
											</div>

											<div class="wpc-row">
													<div class="wpc-col-lg-12">
															<div class="wpc-field-wrap image-url-field">
																	<h3 class="wpc-sc-builder-label"><?php echo esc_html__('Image url for reservation form style one', 'wpcafe'); ?></h3>
																	<span class="wpc-sc-builder-label-desc"><?php echo esc_html('This image will be used in reservation form on second step beside the form input fields', 'wpcafe'); ?></span>
																	<input type="url" data-image_url="<?php echo esc_attr('wpc_image_url'); ?>" placeholder="<?php echo esc_attr('http://domain.com/img.jpg'); ?>" class="image_url">
															</div>
													</div>
											</div>

											<?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_reservation_form]', 'wpc_reservation_form-shortcode'); ?>            

									</div>
							</div>

							<?php Wpc_Utilities::generate_shortcode_button(esc_html__('Reservation Form', 'wpcafe')); ?>

					</div>

					<?php
							apply_filters('wpcafe/key_options/hook_settings_reservation',false);
					?>
					<!-- reservation form end -->
			</div>
		</div>
	</div>
</div>

<?php
