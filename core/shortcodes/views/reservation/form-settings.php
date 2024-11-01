<?php
use WpCafe\Utils\Wpc_Utilities;

$wpc_min_guest_no    = isset($settings['wpc_min_guest_no']) ? $settings['wpc_min_guest_no'] : 1;

if ( !empty( $settings['reser_multi_schedule'] )) {
    $wpc_max_guest_no = $seat_capacity;
}else {
    $wpc_max_guest_no = !empty($settings['rest_max_reservation']) ? $settings['rest_max_reservation'] : 20;
}

$wpc_default_gest_no = !empty($settings['wpc_default_gest_no'])  ? $settings['wpc_default_gest_no'] : $wpc_min_guest_no;
$wpc_late_bookings   = isset($settings['wpc_late_bookings']) && $settings['wpc_late_bookings'] !== "1"  ? $settings['wpc_late_bookings'] : "";
$phone_required      = isset($settings['wpc_require_phone']) ? "required" : "";
$show_branches       = isset($settings['show_branches'] )   ? "yes" : "no";
$require_branch      = isset($settings['require_branch'] )   ? "required" : "no";

$cancellation_option = '';

if ( isset($settings['wpc_allow_cancellation']) && $settings['wpc_allow_cancellation'] == 'off' ) {
    $cancellation_option .= "hide-cancel-text";
}

$wpc_image_url = \Wpcafe::assets_url() . 'images/reservation_image.png';
if (is_array($atts) && isset($atts['wpc_image_url']) && $atts['wpc_image_url'] !== '') {
    $wpc_image_url = $atts['wpc_image_url'];
}

$reservation_arr = array(
    'wpc_check_name'        => esc_html__('Name :', 'wpcafe'),
    'wpc_check_email'       => esc_html__('Email :', 'wpcafe'),
    'wpc_check_phone'       => esc_html__('Phone :', 'wpcafe'),
    'wpc_check_guest'       => esc_html__('Guests :', 'wpcafe'),
    'wpc_check_start_time'  => esc_html__('Time :', 'wpcafe'),
    'wpc_check_booking_date'=> esc_html__('Date :', 'wpcafe'),
    'wpc_reserv_message'    => esc_html__('Additional Information :', 'wpcafe'),
);

if( $show_branches=="yes" ){
    // Add branch field in backend reservation form
    $reservation_arr['wpc_check_branch'] = esc_html__('Branch  :', 'wpcafe');
}

$style="";

if ( isset($atts['form_style']) ) {
    switch ( $atts['form_style'] ) {
        case "1":
            $column_lg = "wpc-col-lg-6";
            $column_md = "wpc-col-md-12";
            break;
        case "2":
            $column_lg = "wpc-col-lg-12";
            $column_md = "wpc-col-md-6";
            break;
    }
    $style=$atts['form_style'];
}

$late_one   = esc_html__("Our last booking time is","wpcafe" );
$late_two   = " {last_time}.";
$late_three =  esc_html__(" You can book up until","wpcafe");
$late_four  = " {last_min}";
$late_five  =  esc_html__(" minutes before closing time.","wpcafe" );
// get location
$wpc_location_arr = Wpc_Utilities::get_location_data( "Select a branch","No branch is set", "key" );

$dash="";

if ( $show_form_field =="on" && $show_to_field =="on" ){
    $dash="-";
}

return;
