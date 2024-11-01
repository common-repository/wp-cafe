<?php

use WpCafe\Utils\Wpc_Utilities;

$interval = array( 5,10,15,20,25,30,35,40,45,50,55,60 );
$interval_time = [];
foreach ($interval as $value) {
    $interval_time[$value] = $value;
}
$reserv_time_interval = !empty( $settings['reserv_time_interval'] ) ? $settings['reserv_time_interval'] : 30;
?>
<div class="wpc-tab-wrapper wpc-tab-style2">

    <ul class="wpc-nav mb-30">
        <li>
            <a class="wpc-tab-a wpc-active"  data-id="general-reservation-settings">
                <?php echo esc_html__('General Settings', 'wpcafe'); ?>
                <svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/></svg>
            </a>
        </li>
        <li>
            <a class="wpc-tab-a" data-id="form-cus-reservation-settings">
                <?php echo esc_html__('Form Customization', 'wpcafe'); ?>
            </a>
        </li>
        <li>
            <a class="wpc-tab-a" data-id="schedule-reservation-settings">
                <?php echo esc_html__('Schedule', 'wpcafe'); ?>
            </a>
        </li>
        <li>
            <a class="wpc-tab-a" data-id="email-reservation-settings">
                <?php echo esc_html__('Email Settings', 'wpcafe'); ?>
            </a>
        </li>
        <li>
            <a class="wpc-tab-a" data-id="notification-reservation-settings">
                <?php echo esc_html__('Notification Email', 'wpcafe'); ?>
            </a>
        </li>        
    </ul>
    <div class="wpc-tab-content">
        <div class="wpc-tab wpc-active" data-id="general-reservation-settings">
            <?php
            $pages_list = [];
            $pages_list[''] = esc_html__('Select a Page', 'wpcafe');
            foreach (get_pages() as $key => $value) {
                $pages_list[$value->ID] = $value->post_title;
            }

            $markup_fields_one = [
                'wpc_reservation_form_display_page' => [
                    'item' => [
                        'label'    => esc_html__( 'Display Pages', 'wpcafe' ),
                        'desc'     => esc_html__( 'Display reservation form only in the selected page', 'wpcafe' ),
                        'type'     => 'select_single',
                        'disable_field'      => true,
                        'options'  => $pages_list,
                        'attr'     => [
                            'class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'
                        ],
                        'span'  => ['class'=> 'wpc-pro-text', 'html' => esc_html__('Pro version only', 'wpcafe')],
                        'pro'   => 'yes'
                    ],
                    'data' => [ 'wpc_reservation_form_display_page' => $wpc_reservation_form_display_page ],
                ],
            ];

            foreach ( $markup_fields_one as $key => $info ) {
                $this->get_field_markup( $info['item'], $key, $info['data'] );
            }

            // render key settings
            if( !empty( $get_data['key_options']) && file_exists( $get_data['key_options'] )){
                require_once $get_data['key_options'] ;
                foreach ( $markup_fields_rest as $key => $info ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                }
            }

            $markup_fields_two = [
                'wpc_pending_message' => [
                    'item' => [
                        'label'    => esc_html__( 'Pending Message', 'wpcafe' ),
                        'desc'     => esc_html__( 'Message that will show up when a user successfully place a reservation', 'wpcafe' ),
                        'type'     => 'textarea',
                        'attr'     => ['class' => 'wpc-label-item', 'row' => '7', 'col' => '30'],
                    ],
                    'data' => [ 'wpc_pending_message' => $wpc_pending_message ],
                ],
                'reserve_dynamic_message' => [
                    'item' => [
                        'label'    => esc_html__( 'Empty Schedule Message', 'wpcafe' ),
                        'desc'     => esc_html__( 'This message will be shown on reservation form when there is no reservation schedule set from admin settings.', 'wpcafe' ),
                        'type'     => 'textarea',
                        'attr'     => ['class' => 'wpc-label-item', 'row' => '7', 'col' => '30'],
                    ],
                    'data' => [ 'reserve_dynamic_message' => $reserve_dynamic_message ],
                ],
                'reserv_time_interval' => [
                    'item' => [
                        'label'    => esc_html__( 'Reservation Schedule Interval', 'wpcafe' ),
                        'desc'     => esc_html__( 'Reservation schedule time difference', 'wpcafe' ),
                        'type'     => 'select_single',
                        'options'  => $interval_time,
                        'attr'     => [
                            'class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'
                        ],
                    ],
                    'data' => [ 'reserv_time_interval' => $reserv_time_interval ],
                ],
                'wpc_booking_confirmed_message' => [
                    'item' => [
                        'label'    => esc_html__( 'Reservation Confirmed Message', 'wpcafe' ),
                        'desc'     => esc_html__( 'Message that will show up when a user\'s reservation is confirmed', 'wpcafe' ),
                        'type'     => 'textarea',
                        'attr'     => ['class' => 'wpc-label-item', 'row' => '7', 'col' => '30'],
                    ],
                    'data' => [ 'wpc_booking_confirmed_message' => $wpc_booking_confirmed_message ],
                ],
            ];

            foreach ( $markup_fields_two as $key => $info ) {
                $this->get_field_markup( $info['item'], $key, $info['data'] );
            }

            //render menu settings
            if ( class_exists('Wpcafe_Pro') ) {
                if( !empty( $get_data['reservation_general_settings'] ) && file_exists( $get_data['reservation_general_settings'] )){
                    include_once $get_data['reservation_general_settings'];
                    foreach ( $markup_fields_general as $key => $info ) {
                        $this->get_field_markup( $info['item'], $key, $info['data'] );
                    }
                }
            }

            ?>

        </div>

        <div class="wpc-tab" data-id="form-cus-reservation-settings">    
            <?php

            $markup_fields_seven = [
                'wpc_allow_cancellation' => [
                    'item' => [
                        'options'  =>['off'=>'off', 'on'=>'on'],
                        'label'    => esc_html__( 'Allow Cancellations?', 'wpcafe' ),
                        'desc'     => esc_html__( 'Allow user to cancelled reservation through cancellation form', 'wpcafe' ),
                        'type'     => 'checkbox',
                        'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                    ],
                    'data' => [ 'wpc_allow_cancellation' => $wpc_checked_allow_cancellation ],
                ],
                'wpc_require_phone' => [
                    'item' => [
                        'options'  =>['off'=>'off', 'on'=>'on'],
                        'label'    => esc_html__( 'Require Phone?', 'wpcafe' ),
                        'desc'     => esc_html__( 'Make phone/contact no. required while placing reservation', 'wpcafe' ),
                        'type'     => 'checkbox',
                        'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                    ],
                    'data' => [ 'wpc_require_phone' => $checked_require_phone ],
                ],
            ];

            foreach ( $markup_fields_seven as $key => $info ) {
                $this->get_field_markup( $info['item'], $key, $info['data'] );
            }

            $interval = array( 5,10,15,20,25,30,35,40,45,50,55,60 );
            $interval_time = [];
            foreach ($interval as $value) {
                $interval_time[$value] = $value;
            }
            $reserv_time_interval = !empty( $settings['reserv_time_interval'] ) ? $settings['reserv_time_interval'] : 30;

            $markup_fields_eight = [
                'show_branches' => [
                    'item' => [
                        'options'  =>['on'=>'on'],
                        'label'    => esc_html__( 'Show Branch Name?', 'wpcafe' ),
                        'desc'     => esc_html__( 'Show branches in reservation form', 'wpcafe' ),
                        'type'     => 'checkbox',
                        'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                    ],
                    'data' => [ 'show_branches' => $show_branches ],
                ],
                'require_branch' => [
                    'item' => [
                        'options'  =>['on'=>'on'],
                        'label'    => esc_html__( 'Is Branch Name Required?', 'wpcafe' ),
                        'desc'     => esc_html__( 'Keep branch name as required during order placement.', 'wpcafe' ),
                        'type'     => 'checkbox',
                        'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                    ],
                    'data' => [ 'require_branch' => $checked_require_branch ],
                ],
            ];
        
            foreach ( $markup_fields_eight as $key => $info ) {
                $this->get_field_markup( $info['item'], $key, $info['data'] );
            }

            // render reservation form settings settings
            if( !empty( $get_data['reservation_form_settings'] ) && file_exists(  $get_data['reservation_form_settings'] )){
                include_once $get_data['reservation_form_settings'];
            }

            if( !empty( $get_data["license_settings"] ) && file_exists($get_data["license_settings"])){
                include_once $get_data["license_settings"];
            }
            ?>
        </div>
        
        <div class="wpc-tab" data-id="schedule-reservation-settings">
            <?php
            // render reservation schedule settings
            if( !empty( $get_data['reservation_schedule'] ) && file_exists( $get_data['reservation_schedule'] )){
                include_once  $get_data['reservation_schedule'] ;
            }

            if( !empty( $get_data['capacity']) ){
                $wpc_no_range = $get_data['capacity'];
            }else{
                $wpc_no_range = 20;
            }

            $min_geust_no = isset( $settings['wpc_min_guest_no'] ) && $settings['wpc_min_guest_no'] !== '' ? $settings['wpc_min_guest_no'] : 1;

            $geust_no_min = [];
            $geust_no_min[''] = esc_html__('Select Number of Guests', 'wpcafe');
            for($i = 1 ; $i <= $wpc_no_range ; $i++) {
                $geust_no_min[$i] = $i;
            }

            $max_geust_no = isset( $settings['wpc_max_guest_no'] ) && $settings['wpc_max_guest_no'] !== '' ? $settings['wpc_max_guest_no'] : $wpc_no_range;

            $geust_no_max = [];
            $geust_no_max[''] = esc_html__('Select Number of Guests', 'wpcafe');
            for($i = 1 ; $i <= $wpc_no_range ; $i++) {
                $geust_no_max[$i] = $i;
            }

            if( isset( $settings['reser_multi_schedule'] ) && $settings['reser_multi_schedule'] =="on" ){
                if( !empty( $settings['seat_capacity'] ) ){
                    $wpc_no_range = max( $settings['seat_capacity'] );
                }elseif( !empty( $settings['diff_seat_capacity'] ) ){
                    array_walk_recursive($settings['diff_seat_capacity'], function($v)use(&$wpc_no_range){if($wpc_no_range === null || $v > $wpc_no_range) $wpc_no_range = $v;});
                }
            }elseif( !empty( $get_data['capacity']) ){
                $wpc_no_range = $get_data['capacity'];
            }else{
                $wpc_no_range = 20;
            }

            $geust_no = [];
            $geust_no[''] = esc_html__('Select Number of Guests', 'wpcafe');
            for($i = 1 ; $i <= $wpc_no_range ; $i++) {
                $geust_no[$i] = $i;
            }
            $geust_no[0] = esc_html__('No. Auto Confirmation', 'wpcafe');

            $default_geust_no       = isset( $settings['wpc_default_guest_no'] ) && $settings['wpc_default_guest_no'] !== '' ? $settings['wpc_default_guest_no'] : 1;
            $rest_max_reservation   = isset( $settings['rest_max_reservation'] ) && $settings['rest_max_reservation'] !== '' ? $settings['rest_max_reservation'] : 20;            

            $markup_fields_two = [
                'rest_max_reservation' => [
                    'item' => [
                        'label'    => esc_html__( 'Seat Capacity for Single Slot', 'wpcafe' ),
                        'desc'     => esc_html__( 'If you use single slot schedule option, this will be counted as the total seat capacity of your restaurant.', 'wpcafe' ),
                        'type'     => 'number',
                        'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                    ],
                    'data' => [ 'rest_max_reservation' => $rest_max_reservation ],
                ],
                'wpc_min_guest_no' => [
                    'item' => [
                        'label'    => esc_html__( 'Minimum Guest Number', 'wpcafe' ),
                        'desc'     => esc_html__( 'Number of minimum allowed guest for a single reservation', 'wpcafe' ),
                        'type'     => 'select_single',
                        'options'  => $geust_no_min,
                        'attr'     => [
                            'class' => 'wpc-label-item wpc-guest-count', 'input_class'=> 'wpc-settings-input mb-2'
                        ],
                        'span'  => ['class'=> 'desc max_error hide_field wpc-default-guest-message', 'html' => esc_html__('Minimum guest number must be less than maximum guest number.', 'wpcafe')]
                    ],
                    'data' => [ 'wpc_min_guest_no' => $min_geust_no ],
                ],
                'wpc_max_guest_no' => [
                    'item' => [
                        'label'    => esc_html__( 'Maximum Guest Number', 'wpcafe' ),
                        'desc'     => esc_html__( 'Number of maximum allowed guest for a single reservation', 'wpcafe' ),
                        'type'     => 'select_single',
                        'options'  => $geust_no_max,
                        'attr'     => [
                            'class' => 'wpc-label-item wpc-guest-count', 'input_class'=> 'wpc-settings-input mb-2'
                        ],
                        'span'  => ['class'=> 'desc max_error hide_field wpc-default-guest-message', 'html' => esc_html__('Maximum guest number must be grater than minimum guest number.', 'wpcafe')]
                    ],
                    'data' => [ 'wpc_max_guest_no' => $max_geust_no ],
                ],
                'wpc_default_guest_no' => [
                    'item' => [
                        'label'    => esc_html__( 'Automatically Confirmed Guest No.', 'wpcafe' ),
                        'desc'     => esc_html__( 'Confirmed a reservation if number. of guests is the selected number. This number. must be between minimum and maximum guest number.', 'wpcafe' ),
                        'type'     => 'select_single',
                        'options'  => $geust_no,
                        'attr'     => [
                            'class' => 'wpc-label-item wpc-guest-count', 'input_class'=> 'wpc-settings-input mb-2'
                        ],
                        'span'  => ['class'=> 'desc default_error hide_field wpc-default-guest-message', 'html' => esc_html__('This value must be in between minimum &amp; maximum guest no.', 'wpcafe')]
                    ],
                    'data' => [ 'wpc_default_guest_no' => $default_geust_no ],
                ],
            ];

            foreach ( $markup_fields_two as $key => $info ) {
                if ( !class_exists('Wpcafe_Pro') ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                } else {
                    if ( $key !== "rest_max_reservation" ) {
                        $this->get_field_markup( $info['item'], $key, $info['data'] );
                    }
                }
                
            }

            ?>
            <div class="single_schedule">
            <div class="wpc-label-item wpc-schedule-tab">
                <?php

                    $weekly_active = '';
                    $daily_active = '';
                    if(!empty($settings['wpc_all_day_start_time'])){
                        $daily_active = 'wpc-schedule-active';    
                    } else {
                        $weekly_active = 'wpc-schedule-active';
                    }
                ?>

                <div class="wpc-label wpc-schedule-label">
                    <strong class="wpc-settings-label"><?php esc_html_e('Schedule', 'wpcafe'); ?></strong>
                    <p class="wpc-desc"> <?php esc_html_e('Set weekly or opening and closing schedule of your restaurant', 'wpcafe'); ?> </p>
                </div>
                <div class="wpc-meta">
                    <!--schedule tab start -->
                    <div class="wpc-schedule-tab-wrapper single-slot-schedule">
                        <ul class="wpc-nav-schedule mb-30">
                            <li>
                                <a class="wpc-tab-a wpc-active <?php echo esc_attr($weekly_active); ?> " data-title="<?php echo esc_attr__('Set weekly opening and closing schedule','wpcafe'); ?>" data-id="single-weekly-schedule"
                                id="weekly_valid" data-weekly_valid="<?php echo esc_attr__('You have already set weekly schedule. Please unset weekly schedule.','wpcafe') ?>"
                                data-exist_warning="<?php echo esc_attr__('day exist. Please check another day','wpcafe') ?>"
                                >
                                    <?php echo esc_html__('Weekly Schedule', 'wpcafe'); ?>
                                </a>
                            </li>
                            <li>
                                <a class="wpc-tab-a <?php echo esc_attr($daily_active); ?>" data-title="<?php echo esc_attr__('Set schedule for all days', 'wpcafe'); ?>"
                                data-id="single-daily-schedule" id="every_day_valid" data-every_day_valid="<?php echo esc_attr__('You have already set everyday schedule. Please unset everyday schedule.','wpcafe') ?>">
                                    <?php echo esc_html__('Everyday Schedule', 'wpcafe'); ?>
                                </a>
                            </li>
                        </ul>
                        <div class="wpc-tab-content">
                            <div class="wpc-tab <?php echo esc_attr($weekly_active); ?>" data-id="single-weekly-schedule">
                                <div class="schedule_main_block">
                                    <h5 class="wpc_pb_two"><?php esc_html_e('Weekly (Set opening and closing schedule for each day of a week separately)', 'wpcafe'); ?></h5>
                                    <?php
                                    $wpc_schedule['wpc_weekly_schedule'] = isset( $settings['wpc_weekly_schedule'] ) ? $settings['wpc_weekly_schedule'] : [];
                                    $wpc_schedule['wpc_weekly_schedule_start_time'] = isset( $settings['wpc_weekly_schedule_start_time'] ) ? $settings['wpc_weekly_schedule_start_time'] : [];
                                    $wpc_schedule['wpc_weekly_schedule_end_time']   = isset( $settings['wpc_weekly_schedule_end_time'] ) ? $settings['wpc_weekly_schedule_end_time'] : [];
                                    //  For weekly schedule 
                                    if( is_array( $wpc_schedule['wpc_weekly_schedule']  ) && count( $wpc_schedule['wpc_weekly_schedule']  ) >0 ){
                                        for ( $index=0; $index < count( $wpc_schedule['wpc_weekly_schedule']   ) ; $index ++) { ?>
                                            <div class="schedule_block week_schedule_wrap week_schedule_wrap_<?php echo esc_attr( $index ); ?>" data-id="<?php echo esc_attr( $index ); ?>">
                                                <div class="wpc-weekly-schedule-list">
                                                    <?php foreach ($week_days as $key => $value) { ?>
                                                        <input type="hidden" name="slug_single_weekly" value="<?php echo esc_attr( !empty($settings['slug_single_weekly']) ? $settings['slug_single_weekly'] : "single_weekly_".strtolower($value)."_"."$key" ); ?>"/>
                                                        <input type="checkbox" name="wpc_weekly_schedule[<?php echo intval($index)?>][<?php echo esc_attr($value);?>]" 
                                                        class="<?php echo esc_attr(strtolower($value));?>" id="weekly_<?php echo esc_attr(strtolower($value).'_'.intval($index));?>"
                                                        <?php echo isset( $wpc_schedule['wpc_weekly_schedule'][$index][$value] ) ? 'checked' : ''?>
                                                        /><label for="weekly_<?php echo esc_attr(strtolower($value).'_'.intval($index));?>"><?php echo esc_html($value); ?></label>
                                                    <?php } ?>
                                                </div>

                                                <div class="wpc-schedule-field multi_schedule_wrap mb-2">
                                                    <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Start Time', 'wpcafe'); ?></p>
                                                    <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('End Time', 'wpcafe'); ?></p>
                                                </div>
                                                
                                                <div class="schedule_block wpc-schedule-field">
                                                    <?php
                                                        $weekly_start_time = $wpc_schedule['wpc_weekly_schedule_start_time'][ $index ];
                                                        $weekly_end_time   = $wpc_schedule['wpc_weekly_schedule_end_time'][ $index ];
                                                    ?>
                                                    <div class="wpc_weekly_start_wrap">
                                                        <input type="text"  name="wpc_weekly_schedule_start_time[]" id="wpc_weekly_schedule_start_time_<?php echo intval($index) ?>" data-id="<?php echo intval($index) ?>" value="<?php echo esc_attr( Wpc_Utilities::wpc_render( $weekly_start_time ) ); ?>" class="wpc_weekly_schedule_start_time wpc_weekly_schedule_start_time_<?php echo intval( Wpc_Utilities::wpc_numeric($index) ); ?> ml-2 mr-1 wpc-settings-input attr-form-control <?php echo empty( $weekly_start_time ) ? 'wpc_field_error' : '' ?>" placeholder="<?php echo esc_attr__('Start Time' , 'wpcafe'); ?>"/>
                                                        <?php if( empty( $weekly_start_time ) ) { ?>
                                                            <span class="wpc_field_error_msg"><?php echo esc_html__('This field should be filled up', 'wpcafe'); ?></span>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="wpc_weekly_end_wrap">
                                                        <input type="text" name="wpc_weekly_schedule_end_time[]"   id="wpc_weekly_schedule_end_time_<?php echo intval($index) ?>" data-id="<?php echo intval($index) ?>" value="<?php echo esc_attr( Wpc_Utilities::wpc_render( $weekly_end_time ) ); ?>" class="wpc_weekly_schedule_end_time wpc_weekly_schedule_end_time_<?php echo intval( Wpc_Utilities::wpc_numeric($index) ); ?> ml-2 wpc-settings-input attr-form-control <?php echo empty( $weekly_end_time ) ? 'wpc_field_error' : '' ?>" placeholder="<?php echo esc_attr__('End Time', 'wpcafe'); ?>"/>
                                                        <?php if( empty( $weekly_end_time ) ) { ?>
                                                            <span class="wpc_field_error_msg"><?php echo esc_html__('This field should be filled up', 'wpcafe'); ?></span>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="wpc_weekly_clear" id="<?php echo intval($index) ?>" data-id="<?php echo intval($index) ?>">
                                                        <span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe'); ?>"> <small class="wpc-tooltip-angle"></small>
                                                        </span>
                                                    </div> 
                                                </div>
                                                <div class="weekly_message_<?php echo intval($index) ?> wpc-default-guest-message"></div>
                                                <?php if( $index != 0 ) { ?>
                                                <span class="wpc-btn-close dashicons dashicons-no-alt remove_schedule_block pl-1"></span>
                                                <?php } ?>
                                            </div>
                                            <?php
                                        }
                                    }
                                    else {
                                    ?>
                                        <div class="schedule_block week_schedule_wrap week_schedule_wrap_0" data-id="<?php echo esc_attr( 0 )?>">
                                            <div class="wpc-weekly-schedule-list">
                                            <?php foreach ($week_days as $key => $value) { ?>
                                                    <input type="hidden" name="slug_single_weekly" value="<?php echo esc_attr( !empty($settings['slug_single_weekly']) ? $settings['slug_single_weekly'] : "slug_single_weekly_".strtolower($value)."_"."$key" ); ?>"/>
                                                    <input type="checkbox" name="wpc_weekly_schedule[0][<?php echo esc_attr($value);?>]" 
                                                    class="<?php echo esc_attr(strtolower($value));?>" id="schedule_<?php echo esc_attr(strtolower($value));?>"
                                                    /><label for="schedule_<?php echo esc_attr(strtolower($value));?>"><?php echo esc_html($value); ?></label>
                                            <?php } ?>
                                            </div>
                                            <div class="wpc-schedule-field">
                                                <div class="wpc_weekly_start_wrap">
                                                    <input type="text" name="wpc_weekly_schedule_start_time[]" id="weekly_start_0" data-id="0" class="wpc_weekly_schedule_start_time wpc_weekly_schedule_start_time_0 mr-1 wpc-settings-input attr-form-control" disabled placeholder="<?php echo esc_attr__('Start Time', 'wpcafe'); ?>"/>
                                                </div>
                                                <div class="wpc_weekly_end_wrap">
                                                    <input type="text" name="wpc_weekly_schedule_end_time[]" id="weekly_end_0" data-id="0" class="wpc_weekly_schedule_end_time wpc_weekly_schedule_end_time_0 wpc-settings-input attr-form-control" disabled placeholder="<?php echo esc_attr__('End Time', 'wpcafe'); ?>"/>
                                                </div>
                                                <div class="wpc_weekly_clear" id="0" data-id="0" style="display: none;">
                                                    <span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset field', 'wpcafe'); ?>"> <small class="wpc-tooltip-angle"></small>
                                                    </span> 
                                                </div>  
                                            </div>
                                            <div class="weekly_message_0 wpc-default-guest-message"></div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="wpc_flex_reverse wpc-weekly-schedule-btn">
                                    <span class="add_schedule_block wpc-btn-text" data-clear_text="<?php echo esc_attr__( 'Reset Fields', 'wpcafe' ); ?>" data-remove_text="<?php echo esc_attr__( 'Remove' , 'wpcafe' )  ?>" data-title="<?php echo esc_attr__('Add Weekly Schedule', 'wpcafe'); ?>" data-start_time="<?php echo esc_attr__('Start Time', 'wpcafe'); ?>" data-end_time="<?php echo esc_attr__('End Time', 'wpcafe'); ?>">
                                        <?php echo esc_html__('Add','wpcafe'); ?>                                            
                                    </span>
                                </div>
                            </div>
                            <div class="wpc-tab <?php echo esc_attr($daily_active); ?>" data-id="single-daily-schedule">
                                <div class="wpc-all-day-schedule">
                                    <h5 class="wpc_pb_two"><?php esc_html_e('All day (Set opening and closing schedule for all days of a week)', 'wpcafe'); ?></h5>  
                                    <?php
                                        //  For everyday schedule 
                                        $all_day_start_time = isset($settings['wpc_all_day_start_time'] ) ? $settings['wpc_all_day_start_time'] : '';
                                        $all_day_end_time   = isset($settings['wpc_all_day_end_time'] ) ? $settings['wpc_all_day_end_time'] : '';

                                        $all_day_start_error = ( empty( $all_day_start_time ) && ! empty( $all_day_end_time ) ) ? true : false;
                                        $all_day_end_error   = ( empty( $all_day_end_time ) && ! empty( $all_day_start_time ) ) ? true : false;
                                    ?>
                                    <div class="wpc-schedule-field mb-2">
                                        <div class="wpc_all_day_start_wrap">
                                            <input type="hidden" name="slug_single_all" value="<?php echo esc_attr( !empty($settings['slug_single_all']) ? $settings['slug_single_all'] : "single_all_0" ); ?>"/>
                                            <input type="text" name="wpc_all_day_start_time" value="<?php echo esc_attr( $all_day_start_time ); ?>"
                                            class="wpc_all_day_start mb-1 wpc-settings-input attr-form-control <?php echo ( $all_day_start_error ) ? 'wpc_field_error' : '' ?>" placeholder="<?php echo esc_attr__('Start time', 'wpcafe'); ?>" />
                                            <?php if( $all_day_start_error ) { ?>
                                                <span class="wpc_field_error_msg"><?php echo esc_html__('This field should be filled up', 'wpcafe'); ?></span>
                                            <?php } ?>
                                        </div>

                                        <div class="wpc_all_day_end_wrap">
                                            <input type="hidden" name="slug_single_all" value="<?php echo esc_attr( !empty($settings['slug_single_all']) ? $settings['slug_single_all'] : "single_all_0" ); ?>"/>
                                            <input type="text" name="wpc_all_day_end_time" value="<?php echo esc_attr( $all_day_end_time ); ?>" 
                                            class="wpc_all_day_end wpc-settings-input attr-form-control <?php echo ( $all_day_end_error ) ? 'wpc_field_error' : '' ?>" <?php echo ( empty ( $all_day_start_time ) && empty ( $all_day_end_time ) ) ? 'disabled' : '' ?> placeholder="<?php echo esc_attr__('End Time', 'wpcafe' ); ?>"/> 
                                            <?php if( $all_day_end_error ) { ?>
                                                <span class="wpc_field_error_msg"><?php echo esc_html__('This field should be filled up', 'wpcafe'); ?></span>
                                            <?php } ?>
                                        </div>

                                        <div class="wpc_all_day_clear" style="<?php echo ( empty ( $all_day_start_time ) && empty ( $all_day_end_time ) ) ? 'display: none;' : '' ?>"><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe'); ?>"> <small class="wpc-tooltip-angle"></small></span> </div> 
                                    </div>
                                    <div class="all_day_message wpc-default-guest-message"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>                              
                </div>
            </div>

            <?php            
            if ( class_exists( 'Wpcafe_Pro' ) && file_exists($get_data['reservation_holiday_settings']) ) {
                include_once $get_data['reservation_holiday_settings']; 
            }
            ?>

            <div class="wpc-label-item wpc-shcedule-event-item">
                <div class="wpc-label">
                    <strong><?php esc_html_e('Exception Schedule', 'wpcafe'); ?></strong>
                    <div class="wpc-desc mb-2"> <?php esc_html_e('Set opening and closing schedule for any special day', 'wpcafe'); ?> </div>
                </div>
                <div class="wpc-meta exception_section">
                    <div class="exception_main_block">
                        <?php
                        $wpc_exception['wpc_exception_date']       = isset( $settings['wpc_exception_date'] ) ? $settings['wpc_exception_date'] : [];
                        $wpc_exception['wpc_exception_start_time'] = isset( $settings['wpc_exception_start_time'] ) ? $settings['wpc_exception_start_time'] : [];
                        $wpc_exception['wpc_exception_end_time']   = isset( $settings['wpc_exception_end_time'] ) ? $settings['wpc_exception_end_time'] : [];
                        if( is_array( $wpc_exception['wpc_exception_date'] ) && count($wpc_exception['wpc_exception_date']) > 0 && $wpc_exception['wpc_exception_date']['0'] !== ''){
                            ?>
                            <div class="wpc-schedule-field multi_schedule_wrap mb-2">
                                <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Date', 'wpcafe'); ?></p>
                                <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Start Time', 'wpcafe'); ?></p>
                                <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('End Time', 'wpcafe'); ?></p>
                            </div>
                            <?php
                            for ( $index=0; $index < count( $wpc_exception['wpc_exception_date'] ) ; $index ++) {
                                $exception_start_time = $wpc_exception['wpc_exception_start_time'][ $index ];
                                $exception_end_time   = $wpc_exception['wpc_exception_end_time'][ $index ];
                                ?>

                                <div class="exception_block d-flex mb-2">
                                    <input type="text" name="wpc_exception_date[]" value="<?php echo esc_attr( Wpc_Utilities::wpc_render( $wpc_exception['wpc_exception_date'][ $index ] ) ); ?>" class="wpc_exception_date wpc_exception_date_<?php echo intval( $index )?> mr-1 wpc-settings-input attr-form-control" id="exception_date_<?php echo intval( Wpc_Utilities::wpc_render( $index ) )?>" data-id="<?php echo intval( Wpc_Utilities::wpc_render( $index ) )?>" data-current_id="<?php echo intval( $index ) ?>" placeholder="<?php esc_attr_e('Date', 'wpcafe' ); ?>" />
                                    <div class="wpc_exception_start_time_wrap">
                                        <input type="text" name="wpc_exception_start_time[]" value="<?php echo esc_attr( Wpc_Utilities::wpc_render( $exception_start_time ) ); ?>" class="wpc_exception_start_time wpc_exception_start_time_<?php echo intval( $index )?> mr-1 wpc-settings-input attr-form-control <?php echo empty( $exception_start_time ) ? 'wpc_field_error' : '' ?>" id="exception_start_time<?php echo intval( $index ) ?>"  placeholder="<?php esc_attr_e('Start Time', 'wpcafe'); ?>" />
                                        <?php if( empty( $exception_start_time ) ) { ?>
                                            <span class="wpc_field_error_msg"><?php echo esc_html__('This field should be filled up', 'wpcafe'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="wpc_exception_end_time_wrap">
                                        <input type="text"  name="wpc_exception_end_time[]" value="<?php echo esc_attr( Wpc_Utilities::wpc_render( $exception_end_time ) ); ?>" class="wpc_exception_end_time wpc_exception_end_time_<?php echo intval( $index )?> wpc-settings-input attr-form-control <?php echo empty( $exception_end_time ) ? 'wpc_field_error' : '' ?>" id="exception_end_time<?php echo intval( $index ) ?>" data-id="0" placeholder="<?php esc_attr_e('End Time', 'wpcafe' ); ?>"/>
                                        <?php if( empty( $exception_end_time ) ) { ?>
                                            <span class="wpc_field_error_msg"><?php echo esc_html__('This field should be filled up', 'wpcafe'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="exception_time_clear" id="<?php echo intval( $index )?>" data-id="<?php echo intval( $index )?>"><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe'); ?>"> <small class="wpc-tooltip-angle"></small></span> </div>
                                    <?php if( $index != 0 ) { ?>
                                        <span class="wpc-btn-close dashicons dashicons-no-alt remove_exception_block wpc_icon_middle_position"></span>
                                    <?php } ?>
                                </div>
                                <div class=" wpc-default-guest-message schedule_exception_message_<?php echo intval( $index );?>"></div>
                                <?php
                            }
                        }
                        else {
                        ?>
                            <div class="exception_block d-flex mb-2">
                                <input type="text" name="wpc_exception_date[]" value="" class="wpc_exception_date wpc_exception_date_0 mr-1 wpc-settings-input attr-form-control" data-current_id="0" placeholder="<?php esc_attr_e('Date', 'wpcafe'  )?>" />
                                <div class="wpc_exception_start_time_wrap">
                                    <input type="text" name="wpc_exception_start_time[]" value="" id="wpc_exception_start_time_0" data-id="0" class="wpc_exception_start_time wpc_exception_start_time_0 mr-1 wpc-settings-input attr-form-control" disabled placeholder="<?php esc_attr_e('Start Time', 'wpcafe'); ?>" />
                                </div>
                                <div class="wpc_exception_end_time_wrap">
                                    <input type="text" name="wpc_exception_end_time[]" value="" id="wpc_exception_end_time_0" data-id="0" class="wpc_exception_end_time wpc_exception_end_time_0 wpc-settings-input attr-form-control" disabled placeholder="<?php esc_attr_e('End Time', 'wpcafe'); ?>"/>
                                </div>
                                <div class="exception_time_clear" id="0" data-id="0" style="display: none;"><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe'); ?>"> <small class="wpc-tooltip-angle"></small></span></div>
                            </div>
                            <div class=" wpc-default-guest-message schedule_exception_message_0"></div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="wpc_flex_reverse">
                        <span class="add_exception_block wpc-btn-text wpc-tooltip" data-tooltip-remove ="<?php echo esc_attr__('Remove Schedule', 'wpcafe') ?>" data-tooltip-reset="<?php echo esc_attr__('Reset fields' , 'wpcafe' ); ?>" data-title="<?php echo esc_attr__('Add more', 'wpcafe'); ?>" data-start_time="<?php echo esc_attr__('Start Time', 'wpcafe'); ?>" data-end_time="<?php echo esc_attr__('End Time', 'wpcafe'); ?>">
                            <?php echo esc_html__('Add','wpcafe'); ?>
                            <small class="wpc-tooltip-angle"></small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="wpc-label-item">
                <div class="wpc-label">
                    <label for="wpc_early_bookings"><?php esc_html_e('Earliest Time Limit for Reservation', 'wpcafe'  ); ?></label>
                    <div class="wpc-desc"> <?php esc_html_e('Set initial time for early reservation. User can not place reservation before the defined time', 'wpcafe'); ?> </div>
                </div>
                <div class="wpc-meta">
                    <select id="wpc_early_bookings" class="wpc-settings-input" name="wpc_early_bookings">
                        <?php
                        $selected_early_booking = !empty( $settings['wpc_early_bookings'] ) ? $settings['wpc_early_bookings'] : "";
                        
                        $wpc_early_bookings= array( 
                            'any_time'     => esc_html__( 'Any time', 'wpcafe' ),
                            'day'          => esc_html__( 'Day', 'wpcafe' ),
                            'week'         => esc_html__( 'Week', 'wpcafe' ),
                            'month'        => esc_html__( 'Month', 'wpcafe' ),
                            );
                            foreach( $wpc_early_bookings as $key => $value ) { ?>
                                <option <?php selected( $selected_early_booking , $key , true ); ?> value='<?php echo esc_attr( $key ); ?>'><?php echo esc_html( $value ); ?></option>
                            <?php }
                        ?>
                    </select>
                    <input type="number" name="wpc_early_bookings_value" value="<?php echo ( !empty( $settings['wpc_early_bookings_value'] ) ) ? esc_attr( Wpc_Utilities::wpc_render( $settings['wpc_early_bookings_value'] ) ) : ''; ?>"  min="0" class="wpc-settings-input <?php echo ( empty( $settings['wpc_early_bookings'] ) || $settings['wpc_early_bookings'] == 'any_time') ? 'wpc-display-none' : ''; ?>" required <?php echo ( empty($settings['wpc_early_bookings'] ) || $settings['wpc_early_bookings'] == 'any_time') ? 'disabled="disabled"' : ''; ?>>
                </div>
            </div>
            <div class="wpc-label-item">
                <div class="wpc-label">
                    <label for="wpc_late_bookings"><?php esc_html_e('Last Time for Reservation', 'wpcafe'); ?></label>
                    <div class="wpc-desc"> <?php esc_html_e('Set final time for late reservation. User can not place reservation after the defined time', 'wpcafe'); ?> </div>
                </div>
                <div class="wpc-meta">
                    <select id="wpc_late_bookings" class="wpc-settings-input" name="wpc_late_bookings">
                        <?php
                        $selected_late_booking = !empty( $settings['wpc_late_bookings'] ) ? $settings['wpc_late_bookings'] : "";
                        $wpc_late_bookings= array( 
                            '1'       => esc_html__( 'Up to the last minute', 'wpcafe' ),
                            '15'      => esc_html__( 'At least 15 minutes in advance', 'wpcafe' ),
                            '30'      => esc_html__( 'At least 30 minutes in advance', 'wpcafe' ),
                            '45'      => esc_html__( 'At least 45 minutes in advance', 'wpcafe' ),
                            );
                            foreach( $wpc_late_bookings as $key => $value ) { ?>
                                <option <?php selected( $selected_late_booking , $key , true ); ?> value='<?php echo esc_attr( $key ); ?>'><?php echo esc_html( $value ); ?></option>
                            <?php }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="wpc-tab" data-id="email-reservation-settings">
            <?php
            $sender_email = isset($settings['sender_email_address'] ) && $settings['sender_email_address'] !== '' ? $settings['sender_email_address'] : wp_get_current_user()->data->user_email;
            $admin_email = isset($settings['wpc_admin_email_address'] ) && $settings['wpc_admin_email_address'] !== '' ? $settings['wpc_admin_email_address'] : '';

            $markup_fields_three = [
                'sender_email_address' => [
                    'item' => [
                        'label'    => esc_html__( 'Sender Email Address', 'wpcafe' ),
                        'desc'     => esc_html__( 'Admin and User will receive email from this email address.', 'wpcafe' ),
                        'type'     => 'email',
                        'place_holder' => esc_attr(wp_get_current_user()->data->user_email),
                        'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                    ],
                    'data' => [ 'sender_email_address' => $sender_email ],
                ],
                'wpc_admin_email_address' => [
                    'item' => [
                        'label'    => esc_html__( 'Receiver Email Address (Admin)', 'wpcafe' ),
                        'desc'     => esc_html__( 'Admin will receive emails at this email address. If \'Sender Email Address\' is not set, then this email will also be used to send both admin and user email updates.', 'wpcafe' ),
                        'type'     => 'email',
                        'place_holder' => '',
                        'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                    ],
                    'data' => [ 'wpc_admin_email_address' => $admin_email ],
                ],
            ];

            foreach ( $markup_fields_three as $key => $info ) {
                $this->get_field_markup( $info['item'], $key, $info['data'] );
            }
                      
            if ( class_exists( 'Wpcafe_Pro' ) && file_exists($get_data['branch_email_settings']) ) {
                include $get_data['branch_email_settings'];
                foreach ( $markup_fields_branch as $key => $info ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                }
            }
        
            $reply_to_name = isset($settings['wpc_reply_to_name'] ) && $settings['wpc_reply_to_name'] !== '' ? $settings['wpc_reply_to_name'] : wp_get_current_user()->data->display_name;
            $booking_req = $allow_admin_notif_book_req == 'off' ? 'checked' : '';

            $markup_fields_four = [
                'wpc_reply_to_name' => [
                    'item' => [
                        'label'    => esc_html__( 'Reply-To Name', 'wpcafe' ),
                        'desc'     => esc_html__( 'For table reservation, set the \'reply-to\' name that will be shown on the email sent to user', 'wpcafe' ),
                        'type'     => 'text',
                        'place_holder' => esc_attr(wp_get_current_user()->data->display_name),
                        'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                    ],
                    'data' => [ 'wpc_reply_to_name' => $reply_to_name ],
                ],
                
            ];

            foreach ( $markup_fields_four as $key => $info ) {
                $this->get_field_markup( $info['item'], $key, $info['data'] );
            }
            ?>
            <div class="group-switcher-fields">
                <?php
                $markup_fields_four_1 = [
                    'wpc_admin_notification_for_booking_req' => [
                        'item' => [
                            'options'  =>['off' => 'off','on'=>'on'],
                            'label'    => esc_html__( 'New Reservation Notification (Admin)?', 'wpcafe' ),
                            'desc'     => esc_html__( 'Send email to admin when new reservation is placed.', 'wpcafe' ),
                            'type'     => 'checkbox',
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                        ],
                        'data' => [ 'wpc_admin_notification_for_booking_req' => $allow_admin_notif_book_req ],
                    ],
                    'wpc_user_notification_for_booking_req' => [
                        'item' => [
                            'options'  =>['off' => 'off','on'=>'on'],
                            'label'    => esc_html__( 'New Reservation Notification (User)?', 'wpcafe' ),
                            'desc'     => esc_html__( 'Send email to user when new reservation is placed.', 'wpcafe' ),
                            'type'     => 'checkbox',
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                        ],
                        'data' => [ 'wpc_user_notification_for_booking_req' => $allow_user_notif_book_req ],
                    ],
                ];

                foreach ( $markup_fields_four_1 as $key => $info ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                }

                ?>
            </div>
            <div class="group-switcher-fields">
                <?php

                $markup_fields_four_2 = [
                    'wpc_user_notification_for_confirm_req' => [
                        'item' => [
                            'options'  =>['off' => 'off','on'=>'on'],
                            'label'    => esc_html__( 'Reservation Confirmation Notification (User)?', 'wpcafe' ),
                            'desc'     => esc_html__( 'Send email to user when admin confirms reservation.', 'wpcafe' ),
                            'disable_field'      => true,
                            'type'     => 'checkbox',
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                            'pro'      => 'yes'
                        ],
                        'data' => [ 'wpc_user_notification_for_confirm_req' => $user_notif_confirm_book ],
                    ],
                    'wpc_admin_notification_for_confirm_req' => [
                        'item' => [
                            'options'  =>['off' => 'off','on'=>'on'],
                            'label'    => esc_html__( 'Reservation Confirmation Notification (Admin)?', 'wpcafe' ),
                            'desc'     => esc_html__( 'Send email to admin when reservation is confirmed.', 'wpcafe' ),
                            'disable_field'      => true,
                            'type'     => 'checkbox',
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                            'pro'      => 'yes'
                        ],
                        'data' => [ 'wpc_admin_notification_for_confirm_req' => $admin_notif_confirm_book ],
                    ],
                ];

                foreach ( $markup_fields_four_2 as $key => $info ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                }
                ?>
            </div>
            <div class="group-switcher-fields">
                <?php 
                $markup_fields_four_3 = [
                    'wpc_user_notification_for_cancel_req' => [
                        'item' => [
                            'options'  =>['off' => 'off','on'=>'on'],
                            'label'    => esc_html__( 'Reservation Cancellation Notification (User)?', 'wpcafe' ),
                            'desc'     => esc_html__( 'Send email to user when a reservation is cancelled by the admin.', 'wpcafe' ),
                            'disable_field'      => true,
                            'type'     => 'checkbox',
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                            'pro'      => 'yes'
                        ],
                        'data' => [ 'wpc_user_notification_for_cancel_req' => $user_notif_cancel_req ],
                    ],
                    'wpc_admin_cancel_notification' => [
                        'item' => [
                            'options'  =>['off' => 'off','on'=>'on'],
                            'label'    => esc_html__( 'Reservation Cancellation Notification (Admin)?', 'wpcafe' ),
                            'desc'     => esc_html__( 'Send email to admin when a reservation is cancelled.', 'wpcafe' ),
                            'disable_field'      => true,
                            'type'     => 'checkbox',
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                            'pro'      => 'yes'
                        ],
                        'data' => [ 'wpc_admin_cancel_notification' => $admin_notif_cancel_req ],
                    ],
                ];

                foreach ( $markup_fields_four_3 as $key => $info ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                }

                ?>
            </div>
        </div>
        <div class="wpc-tab" data-id="notification-reservation-settings">
            <div class="wpc-label-item wpc-email-tag">
                <div class="wpc-label">
                    <label for="wpc_admin_email_address"><?php esc_html_e('Template Tags', 'wpcafe'); ?></label>

                    <p class="wpc-desc"> <?php echo esc_html__('Use the following tags to automatically add reservation information to the emails', 'wpcafe'); ?></p>
                        <?php
                            $tag_box = array (
                                array(
                                    "tag_name" => '{user_email}',
                                    "description" => esc_html__('Email of the user who made the booking' , 'wpcafe'),
                                ),
                                array(
                                    "tag_name" => '{user_name}',
                                    "description" => esc_html__('* Name of the user who made the booking' , 'wpcafe'),
                                ),
                                array(
                                    "tag_name" => '{party}',
                                    "description" => esc_html__('* Number of people booked', 'wpcafe'),
                                ),
                                array(
                                    "tag_name" => '{date}',
                                    "description" => esc_html__('* Date and time of the booking', 'wpcafe'),
                                ),
                                array(
                                    "tag_name" => '{phone}',
                                    "description" => esc_html__('Phone number if supplied with the request', 'wpcafe'),
                                ),
                                array(
                                    "tag_name" => '{message}',
                                    "description" => esc_html__('Message added to the request', 'wpcafe'),
                                ),
                                array(
                                    "tag_name" => '{site_name}',
                                    "description" => esc_html__('The name of this website', 'wpcafe'),
                                ),
                                array(
                                    "tag_name" => '{site_link}',
                                    "description" => esc_html__('A link to this website', 'wpcafe'),
                                ),
                                array(
                                    "tag_name" => '{current_time}',
                                    "description" => esc_html__('Current date and time', 'wpcafe'),
                                ),
                                array(
                                    "tag_name" => '{invoice_no}',
                                    "description" => esc_html__('Invoice no. of reservation', 'wpcafe'),
                                ),
                                array(
                                    "tag_name" => '{branch_name}',
                                    "description" => esc_html__('Branch Name', 'wpcafe'),
                                ),
                        );
                        foreach ($tag_box as $key => $value) { ?>
                            <div class="wpc-template-tags-box">
                            <strong><?php echo esc_html( $value['tag_name'] ); ?></strong> <?php echo esc_html( $value['description'] ); ?>
                            </div>
                        <?php } ?> 
                </div>
            </div>
            <div class="email-template-settings">
                <?php
                $subject_admin          = isset($settings['wpc_admin_notification_subject'] ) ? $settings['wpc_admin_notification_subject'] : '';
                $body_admin             = html_entity_decode( isset( $settings['wpc_admin_notification_email'] ) ? $settings['wpc_admin_notification_email'] : "" );
                $subject_new_req        = isset($settings['wpc_new_req_email_subject'] ) ? $settings['wpc_new_req_email_subject'] : '';
                $body_user_req          = html_entity_decode( isset( $settings['wpc_new_req_email'] ) ? $settings['wpc_new_req_email'] : "" );
                $subject_booking_confirm = isset($settings['wpc_admin_booking_confirm_subject'] ) ? $settings['wpc_admin_booking_confirm_subject'] : '';
                $body_booking_confirm   = html_entity_decode( isset( $settings['wpc_admin_booking_confirm_email'] ) ? $settings['wpc_admin_booking_confirm_email'] : "" );
                $subject_cancel_admin   = isset($settings['wpc_admin_booking_cancel_subject'] ) ? $settings['wpc_admin_booking_cancel_subject'] : '';
                $body_booking_cancel    = html_entity_decode( isset( $settings['wpc_admin_booking_cancel_email'] ) ? $settings['wpc_admin_booking_cancel_email'] : "" );
                $subject_confirm_user   = isset($settings['wpc_confirm_email_subject'] ) ? $settings['wpc_confirm_email_subject'] : '';
                $body_confirm_user      = html_entity_decode( isset( $settings['wpc_confirm_email'] ) ? $settings['wpc_confirm_email'] : "" ); 
                $subject_rejected_email = isset($settings['wpc_rejected_email_subject'] ) ? $settings['wpc_rejected_email_subject'] : '';
                $body_rejected_email    = html_entity_decode( isset( $settings['wpc_rejected_email'] ) ? $settings['wpc_rejected_email'] : "" );

                $markup_fields_five = [
                    'wpc_admin_notification_subject' => [
                        'item' => [
                            'label'    => esc_html__( 'New Reservation Email Subject (Admin)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Subject of email that will be sent to admin when a new reservation occurs', 'wpcafe' ),
                            'type'     => 'text',
                            'place_holder' => esc_html__('New Reservation Request', 'wpcafe'),
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                        ],
                        'data' => [ 'wpc_admin_notification_subject' => $subject_admin ],
                    ],
                    'wpc_admin_notification_email' => [
                        'item' => [
                            'label'    => esc_html__( 'New Reservation Email Body (Admin)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Body of email that will be sent to admin when a new reservation occurs', 'wpcafe' ),
                            'type'     => 'wp_editor',
                            'attr'     => ['class' => 'wpc-label-item rich-texteditor'],
                            'settings' => ['textarea_name'=> 'wpc_admin_notification_email', 'media_buttons' => false, 'wpautop' => true]
                        ],
                        'data' => [ 'wpc_admin_notification_email' => $body_admin ],
                    ],
                    'wpc_new_req_email_subject' => [
                        'item' => [
                            'label'    => esc_html__( 'New Reservation Email Subject (User)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Subject of email that will be sent to user when a new reservation occurs', 'wpcafe' ),
                            'type'     => 'text',
                            'place_holder' => esc_html__('New Reservation Request Subject', 'wpcafe'),
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                        ],
                        'data' => [ 'wpc_new_req_email_subject' => $subject_new_req ],
                    ],
                    'wpc_new_req_email' => [
                        'item' => [
                            'label'    => esc_html__( 'New Reservation Email Body (User)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Body of email that will be sent to user when a new reservation occurs', 'wpcafe' ),
                            'type'     => 'wp_editor',
                            'attr'     => ['class' => 'wpc-label-item rich-texteditor'],
                            'settings' => ['textarea_name'=> 'wpc_new_req_email', 'media_buttons' => false, 'wpautop' => true]
                        ],
                        'data' => [ 'wpc_new_req_email' => $body_user_req ],
                    ],
                    'wpc_admin_booking_confirm_subject' => [
                        'item' => [
                            'label'    => esc_html__( 'Reservation Confirm Email Subject (Admin)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Subject of email that will be sent to admin when a reservation is confirmed', 'wpcafe' ),
                            'type'     => 'text',
                            'place_holder' => esc_html__('New Reservation Confirmed', 'wpcafe'),
                            'disable_field'      => true,
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                            'span'  => ['class'=> 'wpc-pro-text', 'html' => esc_html__('Pro version only', 'wpcafe')],
                            'pro'   => 'yes'
                        ],
                        'data' => [ 'wpc_admin_booking_confirm_subject' => $subject_booking_confirm ],
                    ],
                    'wpc_admin_booking_confirm_email' => [
                        'item' => [
                            'label'    => esc_html__( 'Reservation Confirmation Email Body (Admin)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Body of email that will be sent to admin when a reservation is confirmed', 'wpcafe' ),
                            'type'     => 'wp_editor',
                            'attr'     => ['class' => 'wpc-label-item rich-texteditor'],
                            'settings' => ['textarea_name'=> 'wpc_admin_booking_confirm_email', 
                            'media_buttons' => false, 'wpautop' => true],
                            'pro'   => 'yes'
                        ],
                        'data' => [ 'wpc_admin_booking_confirm_email' => $body_booking_confirm ],
                    ],
                    'wpc_admin_booking_cancel_subject' => [
                        'item' => [
                            'label'    => esc_html__( 'Reservation Cancellation Email Subject (Admin)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Subject of email that will be sent to admin when a reservation is cancelled', 'wpcafe' ),
                            'type'     => 'text',
                            'place_holder' => esc_html__('Reservation Request Cancelled', 'wpcafe'),
                            'disable_field'      => true,
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                            'span'  => ['class'=> 'wpc-pro-text', 'html' => esc_html__('Pro version only', 'wpcafe')],
                            'pro'   => 'yes'
                        ],
                        'data' => [ 'wpc_admin_booking_cancel_subject' => $subject_cancel_admin ],
                    ],
                    'wpc_admin_booking_cancel_email' => [
                        'item' => [
                            'label'    => esc_html__( 'Reservation Cancellation Email Body (Admin)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Body of email that will be sent to admin when a reservation is cancelled', 'wpcafe' ),
                            'type'     => 'wp_editor',
                            'attr'     => ['class' => 'wpc-label-item rich-texteditor'],
                            'settings' => ['textarea_name'=> 'wpc_admin_booking_cancel_email', 'media_buttons' => false, 'wpautop' => true],
                            'pro'      => 'yes'
                        ],
                        'data' => [ 'wpc_admin_booking_cancel_email' => $body_booking_cancel ],
                    ],
                    'wpc_confirm_email_subject' => [
                        'item' => [
                            'label'    => esc_html__( 'Reservation Confirmation Email Subject (User)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Subject of email that will be sent to user when reservation is confirmed', 'wpcafe' ),
                            'type'     => 'text',
                            'place_holder' => esc_html__('Confirm Email Subject', 'wpcafe'),
                            'disable_field'      => true,
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                            'span'  => ['class'=> 'wpc-pro-text', 'html' => esc_html__('Pro version only', 'wpcafe')],
                            'pro'   => 'yes'
                        ],
                        'data' => [ 'wpc_confirm_email_subject' => $subject_confirm_user ],
                    ],
                    'wpc_confirm_email' => [
                        'item' => [
                            'label'    => esc_html__( 'Reservation Confirmation Email Body (User)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Body of email that will be sent to user when reservation is confirmed', 'wpcafe' ),
                            'type'     => 'wp_editor',
                            'attr'     => ['class' => 'wpc-label-item rich-texteditor'],
                            'settings' => ['textarea_name'=> 'wpc_confirm_email',
                            'media_buttons' => false, 'wpautop' => true],
                            'pro'   => 'yes'
                        ],
                        'data' => [ 'wpc_confirm_email' => $body_confirm_user ],
                    ],
                    'wpc_rejected_email_subject' => [
                        'item' => [
                            'label'    => esc_html__( 'Reservation Cancellation Email Subject (User)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Subject of email that will be sent to user when a reservation is cancelled', 'wpcafe' ),
                            'type'     => 'text',
                            'place_holder' => esc_html__('Rejected Email Subject', 'wpcafe'),
                            'disable_field'      => true,
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                            'span'  => ['class'=> 'wpc-pro-text', 'html' => esc_html__('Pro version only', 'wpcafe')],
                            'pro'   => 'yes'
                        ],
                        'data' => [ 'wpc_rejected_email_subject' => $subject_rejected_email ],
                    ],
                    'wpc_rejected_email' => [
                        'item' => [
                            'label'    => esc_html__( 'Reservation Cancellation Email Body (User)', 'wpcafe' ),
                            'desc'     => esc_html__( 'Body of email that will be sent to user when a reservation is cancelled', 'wpcafe' ),
                            'type'     => 'wp_editor',
                            'attr'     => ['class' => 'wpc-label-item rich-texteditor'],
                            'settings' => ['textarea_name'=> 'wpc_rejected_email', 'media_buttons' => false, 'wpautop' => true],
                            'pro'   => 'yes'
                        ],
                        'data' => [ 'wpc_rejected_email' => $body_rejected_email ],
                    ],
                ];

                foreach ( $markup_fields_five as $key => $info ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                }
                ?>
                <h3 class="wpc-tab-title"><?php esc_html_e('Reservation With Food Menu', 'wpcafe'); ?></h3>
                <?php
                $body_menu_email = html_entity_decode( isset( $settings['wpc_reservation_with_menu_email'] ) ? $settings['wpc_reservation_with_menu_email'] : "" );
                $markup_fields_six = [
                    'wpc_reservation_with_menu_email' => [
                        'item' => [
                            'label'    => esc_html__( 'Additional Email Text', 'wpcafe' ),
                            'desc'     => esc_html__( 'Additional content of email which will be included in all email related to \'Reservation With Food Menu Order\' feature. This content will only be included if you use \'Reservation With Food Menu\'', 'wpcafe' ),
                            'type'     => 'wp_editor',
                            'attr'     => ['class' => 'wpc-label-item'],
                            'settings' => ['textarea_name'=> 'wpc_reservation_with_menu_email', 'media_buttons' => false, 'wpautop' => true],
                            'pro'   => 'yes'
                        ],
                        'data' => [ 'wpc_reservation_with_menu_email' => $body_menu_email ],
                    ],
                ];

                foreach ( $markup_fields_six as $key => $info ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                }

                ?>
            </div>
        </div>
    </div>
</div>
<?php
return;