<div class="wpc-tab-wrapper wpc-tab-style2">
    <div class="wpc-tab-content">
        <!-- General Settings options -->
        <div class="wpc-tab wpc-active" data-id="general-key-option">
            <div class="wpc-label-item">
                <div class="wpc-label">
                    <strong><?php esc_html_e('Date & Time Format', 'wpcafe'); ?></strong>
                    <div class="wpc-desc"> <?php esc_html_e('Reservation and order type (Delivery/Pickup) date format', 'wpcafe' ); ?> </div>
                </div>
                <div class="wpc-meta">
                    <?php 
                        $current_offset  = get_option( 'gmt_offset' );
                        $timezone_str        = get_option( 'timezone_string' );
                        $timezone_format = _x( WPCAFE_DEFAULT_DATE_FORMAT . " " . WPCAFE_DEFAULT_TIME_FORMAT, 'timezone date format' );  
                        $check_zone_info = true;
                        if ( false !== strpos( $timezone_str, 'Etc/GMT' ) ) {
                            $timezone_str = '';
                        }
                        if ( empty( $timezone_str ) ) {
                            $check_zone_info = false;
                            if ( 0 == $current_offset ) {
                                $timezone_str = 'UTC+0';
                            } elseif ( $current_offset < 0 ) {
                                $timezone_str = 'UTC' . $current_offset;
                            } else {
                                $timezone_str = 'UTC+' . $current_offset;
                            }
                        }
                    ?>
                    <p>
                        <?php esc_html_e( 'Selected current timezone is ', 'wpcafe' ); ?>
                        <code><?php echo esc_html($timezone_str); ?></code><?php echo '.'; ?>
                        <?php echo esc_html__('Universal time is ', 'wpcafe'); ?>
                        <code><?php echo esc_html( date_i18n( $timezone_format, false, true ) ); ?></code>
                     </p>
                     <a href="<?php echo esc_url( admin_url( 'options-general.php#timezone_string' ) ); ?>" target="_blank" class="wpc-btn-text">
                        <?php esc_html_e( 'Update Date & Time Format', 'wpcafe' ); ?>
                    </a>
                </div>
            </div>
            <?php
            $reserv_form_local = isset( $settings["reserv_form_local"] )?  $settings["reserv_form_local"] : "en";
            $lang_arr = [];
                        
            if(file_exists( WP_CONTENT_DIR . '/languages/languages.php' )){
                include_once WP_CONTENT_DIR . '/languages/languages.php';
            } else {
                if( file_exists(  \Wpcafe::core_dir() ."settings/part/languages.php" )){
                    include_once \Wpcafe::core_dir() ."settings/part/languages.php";
                }
            }

            $markup_fields_three = [
                'reserv_form_local' => [
                    'item' => [
                        'label'    => esc_html__( 'Calendar Language', 'wpcafe' ),
                        'desc'     => esc_html__( 'Translate reservation form, order type (Delivery/Pickup) day and month name. Visit the documentation for details.', 'wpcafe' ),
                        'type'     => 'select_single',
                        'options'  => $lang_arr,
                        'attr'     => [
                            'class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'
                        ],
                    ],
                    'data' => [ 'reserv_form_local' => $reserv_form_local ],
                ],
            ];
        
            foreach ( $markup_fields_three as $key => $info ) {
                $this->get_field_markup( $info['item'], $key, $info['data'] );
            }

            if ( class_exists( 'Wpcafe_Pro' ) && file_exists($get_data['integration_settings']) ) { 
                include $get_data['integration_settings']; 
            }
            
            ?>
        </div>
    </div>                    
</div>