<div class="wpc-tab-wrapper wpc-tab-style2">
    <div class="wpc-tab-content">
        <!-- notifications Settings options -->
        <div class="wpc-tab wpc-active" data-id="notification-options">
            <?php
            $primary_color = isset($settings['wpc_primary_color'] ) ? $settings['wpc_primary_color'] : '';

            $secondary_color = isset($settings['wpc_secondary_color'] ) ? $settings['wpc_secondary_color'] : '';

            $cart_icon = isset($settings['wpc_cart_icon'] ) ? $settings['wpc_cart_icon'] : '';

            $markup_fields = [
                'wpc_primary_color' => [
                    'item' => [
                        'label'    => esc_html__( 'Primary Color', 'wpcafe' ),
                        'desc'     => esc_html__( 'Choose a primary color for menu', 'wpcafe' ),
                        'type'     => 'color',
                        'attr'     => [
                            'class' => 'wpc-label-item',
                        ],
                    ],
                    'data' => [ 'wpc_primary_color' => $primary_color ],
                ],
                'wpc_secondary_color' => [
                    'item' => [
                        'label'    => esc_html__( 'Secondary Color', 'wpcafe' ),
                        'desc'     => esc_html__( 'Choose a secondary color for menu', 'wpcafe' ),
                        'type'     => 'color',
                        'attr'     => [
                            'class' => 'wpc-label-item',
                        ],
                    ],
                    'data' => [ 'wpc_secondary_color' => $secondary_color ],
                ],

                'wpc_cart_icon' => [
                    'item' => [
                        'label'    => esc_html__( 'Cart Icon', 'wpcafe' ),
                        'desc'     => esc_html__( 'Icon class for simple,group menu icon. Any icon library which is available in your site will work. Example:  font-awesome, dash-icon etc.', 'wpcafe' ),
                        'type'     => 'text',
                        'place_holder' => esc_html__('icon here', 'wpcafe'),
                        'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                        'div'  => ['class'=>'wpc-admin-settings-message', 'html'=> esc_html__( 'For instance : fa fa-shopping-basket', 'wpcafe'), 'id'=> '' ]
                    ],
                    'data' => [ 'wpc_cart_icon' => $cart_icon ],
                ],

            ];
            foreach ( $markup_fields as $key => $info ) {
                $this->get_field_markup( $info['item'], $key, $info['data'] );
            }

            //render menu settings
            if ( class_exists('Wpcafe_Pro') ) {
                if( !empty( $get_data['style_settings'] ) && file_exists( $get_data['style_settings'] )){
                    include_once $get_data['style_settings'];
                    foreach ( $markup_fields_style as $key => $info ) {
                        $this->get_field_markup( $info['item'], $key, $info['data'] );
                    }
                }
            }
            ?>      
        </div>
    </div>
</div>


