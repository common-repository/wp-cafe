
<div class="wpc-settings">
        <div id="setting_message" class="hide_field"></div>

        <form method='post' class='wpc_pb_two' id='wpc_settings_form' >
            <?php  if ( isset($_GET['saved']) && sanitize_text_field($_GET['saved']) == 1 ) { ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo esc_html__("Your settings have been saved","wpcafe")?></p>
                </div>
            <?php } ?>
            
            <div class="wpc-admin-sec">
                <div class="wpc-row">
                    <div class="wpc-col-lg-5 wpc-col-md-8">
                        <div class="wpc-tools-content">
                            <h1 class="wpc-main-title">
                                <?php echo esc_html__('Extensions to Power Up Your Plugins','wpcafe'); ?>
                            </h1>
                            <p class="wpc-desc">
                                <?php echo esc_html__('Extensions are quick solutions our team came up with to solve specific issues you may need. (Note - extensions are not covered by our support team.)','wpcafe'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                            <div class="wpc-label">
								<div class="wpc-row">
										<div class="wpc-col-md-6">
												<div class="wpc-label-item wpc-tools-item">
														<div class="wpc-label">
																<div class="wpc-label-icon">
																		<svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
																				<ellipse cx="32.0009" cy="31.9995" rx="19.1615" ry="19.1306" fill="#16B6E9"/>
																				<path d="M50.6423 2.75579C51.8355 1.79548 53.5616 1.88657 54.6468 2.9671L61.021 9.31417C62.1062 10.3947 62.202 12.1178 61.2433 13.3116L56.2532 19.5257C55.1357 20.9174 53.0555 21.0327 51.7905 19.7731L44.1955 12.2105C42.9305 10.9509 43.0405 8.87379 44.4315 7.75433L50.6423 2.75579Z" fill="#16B6E9"/>
																				<path opacity="0.2" d="M13.3587 2.75579C12.1655 1.79548 10.4393 1.88657 9.35418 2.9671L2.97994 9.31417C1.89478 10.3947 1.79896 12.1178 2.75765 13.3116L7.74776 19.5257C8.86532 20.9174 10.9455 21.0327 12.2105 19.7731L19.8055 12.2105C21.0705 10.9509 20.9605 8.87379 19.5695 7.75433L13.3587 2.75579Z" fill="#16B6E9"/>
																				<path opacity="0.2" d="M2.75849 50.6884C1.7998 51.8822 1.89562 53.6053 2.98078 54.6859L9.35502 61.0329C10.4402 62.1135 12.1663 62.2045 13.3595 61.2442L19.5704 56.2457C20.9613 55.1262 21.0713 53.0491 19.8063 51.7895L12.2113 44.2269C10.9463 42.9673 8.86617 43.0826 7.7486 44.4743L2.75849 50.6884Z" fill="#16B6E9"/>
																				<path opacity="0.2" d="M61.2435 50.6884C62.2022 51.8822 62.1063 53.6053 61.0212 54.6859L54.6469 61.0329C53.5618 62.1135 51.8356 62.2045 50.6424 61.2442L44.4316 56.2457C43.0406 55.1262 42.9307 53.0491 44.1957 51.7895L51.7906 44.2269C53.0556 42.9673 55.1358 43.0826 56.2534 44.4743L61.2435 50.6884Z" fill="#16B6E9"/>
																		</svg>
																</div>
																<div class="wpc-label-content">
																		<label for="enable_table_layout"><?php esc_html_e('Table Layout', 'wpcafe'); ?></label>
																		<div class="wpc-desc"> <?php esc_html_e('To help you get started, we\'ve put together a super tutorial', 'wpcafe'); ?> </div>
																		<span class="wpc-badge"><?php echo esc_html__('Pro','wpcafe'); ?></span>
																</div>
														</div>
														<div class="wpc-meta">
																<input id='enable_table_layout' type="checkbox" <?php echo esc_attr( $enable_table_layout ); ?> class="wpcafe-admin-control-input"
																		name="enable_table_layout" <?php echo ( !class_exists( 'Wpcafe_Pro' ) ) ? 'readonly disabled' : ''; ?> />
																<label for="enable_table_layout" class="wpcafe_switch_button_label"></label>
														</div>
												</div>

										</div>

										<div class="wpc-col-md-6">
												<div class="wpc-label-item wpc-tools-item">
														<div class="wpc-label">
																<div class="wpc-label-icon">
																		<svg width="65" height="64" viewBox="0 0 65 64" fill="none" xmlns="http://www.w3.org/2000/svg">
																				<path opacity="0.3" d="M3.19734 14.9255H18.1227C19.8872 14.9255 21.32 16.3584 21.32 18.1229V29.8509C21.32 31.6154 19.8872 33.0482 18.1227 33.0482H3.19734C1.43283 33.035 0 31.6021 0 29.8376V18.1096C0 16.3584 1.43283 14.9255 3.19734 14.9255Z" fill="#F5841C"/>
																				<path d="M34.1114 10.6667C37.0569 10.6667 39.4447 8.27884 39.4447 5.33333C39.4447 2.38781 37.0569 0 34.1114 0C31.1659 0 28.7781 2.38781 28.7781 5.33333C28.7781 8.27884 31.1659 10.6667 34.1114 10.6667Z" fill="#F5841C"/>
																				<path d="M11.7273 36.0729C11.7273 37.7313 12.3774 39.3101 13.5449 40.4908H30.8715V36.0597C30.8715 35.4759 30.3939 34.9983 29.8102 34.9983H12.7887C12.2049 35.0116 11.7273 35.4892 11.7273 36.0729Z" fill="#F5841C"/>
																				<path d="M64.73 52.6036C63.6554 48.6368 59.9539 45.9834 55.8677 46.2222C56.0402 45.5987 56.1198 44.9619 56.1198 44.3118C56.08 42.2421 55.0186 40.3184 53.2939 39.1642C50.9987 37.6518 49.3005 33.7911 49.0352 29.7181H51.0783C52.8295 29.7181 54.2624 28.2985 54.2624 26.534V24.4113C54.2624 22.6601 52.8295 21.2272 51.0783 21.2272H43.7815C37.6256 19.4495 35.7682 17.4064 34.6538 16.1858C34.6273 16.1593 34.614 16.1327 34.5875 16.1062C34.813 14.4346 33.9506 12.8027 32.4382 12.0465C30.5012 11.1178 28.1928 11.9404 27.2641 13.8773C27.1314 14.1427 27.0385 14.4213 26.9722 14.7132L23.9208 29.3201C23.5626 31.0448 24.6638 32.7164 26.3885 33.0747C26.4946 33.1012 26.5875 33.1145 26.6936 33.1277L31.9341 33.7115C32.4913 33.7645 32.9158 34.2554 32.876 34.8259L32.0535 48.796C30.3022 47.257 29.6787 44.8027 30.4747 42.6136H14.1828C12.4581 45.466 9.71187 50.7993 10.8263 54.262C11.2508 55.5887 12.2857 56.6235 13.6124 57.0613C14.09 57.2073 14.5941 57.2604 15.0983 57.2206C15.3636 60.9884 18.6273 63.8142 22.3818 63.5489C25.7782 63.3101 28.4714 60.6036 28.7102 57.2206H46.9788C47.337 57.2073 47.6687 57.0348 47.9075 56.7695C48.0402 57.393 48.2524 58.0033 48.5311 58.5871C48.7831 59.1177 49.4199 59.3433 49.9506 59.0912C50.1231 59.0116 50.2558 58.8922 50.3619 58.733C50.4813 58.5605 50.614 58.3748 50.7599 58.2156C51.3304 61.9436 54.8196 64.4909 58.5344 63.9204C62.2624 63.3499 64.8096 59.8607 64.2392 56.1459C64.1993 55.8673 64.133 55.602 64.0667 55.3234C64.6372 55.257 65.0484 54.7529 64.9954 54.1824C64.9688 53.6517 64.876 53.1211 64.73 52.6036ZM21.9042 61.466C19.4896 61.466 17.4731 59.6351 17.2342 57.2338H26.5742C26.3354 59.6351 24.3188 61.466 21.9042 61.466ZM42.5609 49.8308H38.4216L40.2525 32.5307C40.3984 31.0846 39.5626 29.7314 38.1961 29.2272L32.9689 27.277C32.478 27.0912 32.1861 26.5738 32.3055 26.0564L33.1016 22.6335C35.8213 24.7961 39.0982 26.1493 42.5476 26.5075L42.5609 49.8308ZM58.1098 61.7844C55.536 62.1293 53.1745 60.3118 52.8295 57.738C52.7765 57.3665 52.7765 56.9817 52.8163 56.6103L53.1347 56.2123C55.5758 54.6998 58.4813 54.1293 61.3204 54.607L61.5725 54.806C62.8727 57.0481 62.1032 59.9137 59.861 61.2139C59.3304 61.5058 58.7334 61.7048 58.1098 61.7844Z" fill="#F5841C"/>
																		</svg>
																</div>
																<div class="wpc-label-content">
																		<label for="enable_delivery_module"><?php esc_html_e('Delivery Module', 'wpcafe'); ?></label>
																		<div class="wpc-desc"> <?php esc_html_e('To help you get started, we\'ve put together a super tutorial', 'wpcafe'); ?> </div>
																		<span class="wpc-badge"><?php echo esc_html__('Pro','wpcafe'); ?></span>
																</div>
														</div>
														<div class="wpc-meta">
																<input id='enable_delivery_module' type="checkbox" <?php echo esc_attr( $enable_delivery_module ); ?> class="wpcafe-admin-control-input"
																		name="enable_delivery_module" readonly disabled />
																<label for="enable_delivery_module" class="wpcafe_switch_button_label"></label>
														</div>
												</div>

										</div>

								</div>
								<div class="mt-4 wpc_submit_wrap">
										<input type="hidden" name="wpcafe_tools_action" value="tools_save">
										<input type="submit" name="submit" id="cafe_settings_submit" class="wpc_mt_two wpc-btn" value="<?php esc_attr_e('Save Changes', 'wpcafe'); ?>">
										<?php wp_nonce_field('wpcafe-tools-page', 'wpcafe-tools-page'); ?>
								</div>
            </div>
        </form>
</div>