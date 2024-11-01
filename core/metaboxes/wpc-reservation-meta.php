<?php
namespace WpCafe\Core\Metaboxes;

use WpCafe\Core\Base\Wpc_Metabox;
use WpCafe\Utils\Wpc_Utilities;

defined( 'ABSPATH' ) || exit;

class Wpc_Reservation_Meta extends Wpc_Metabox {

    public $metabox_id         = 'wpc_reservation_meta';
    public $reservation_fields = [];
    public $cpt_id             = 'wpc_reservation';
            
    /**
     * Register meta box
     *
     * @return void
     */
    public function register_meta_boxes() {
        add_meta_box(
            $this->metabox_id,
            esc_html__( 'Reservation Information', 'wpcafe' ),
            [$this, 'display_callback'],
            $this->cpt_id
        );
    }

    /**
     * Pass meta box array
     */
    public function wpc_default_metabox_fields() {
        $settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();

        $wpc_late_bookings   = isset($settings['wpc_late_bookings']) && $settings['wpc_late_bookings'] !== "1"  ? $settings['wpc_late_bookings'] : "";
        $multi_schedule      = !empty($settings['reser_multi_schedule']) ? $settings['reser_multi_schedule'] : "off";
        $multi_sch_class     = $multi_schedule == "on" ? "wpc-multi-reservation-msg" : "";

        $late_one   = esc_html__("Our last booking time is","wpcafe" );
        $late_two   = " {last_time}.";
        $late_three =  esc_html__(" You can book before","wpcafe");
        $late_four  = " {last_min}";
        $late_five  =  esc_html__(" minutes of closing time.","wpcafe" );

        ?>
            <div class='late_booking' data-late_booking="<?php echo esc_html($late_one.$late_two.$late_three.$late_four.$late_five);?>"></div>
            <div class='wpc_cancell_log_message'></div>
            <div class='wpc_error_message' data-time_compare="<?php echo esc_html__('Booking end time must be after start time','wpcafe')?>"></div>
            <div class='wpc_success_message  <?php echo esc_attr($multi_sch_class)?>' data-start="<?php echo esc_html__("Start time","wpcafe");?>" data-end="<?php echo esc_html__("End time","wpcafe");?>" data-schedule="<?php echo esc_html__("Schedule","wpcafe");?>" data-late_booking = "<?php echo ( $wpc_late_bookings !=="" ) ? esc_html__("You can booked before ".$wpc_late_bookings."min of closing time.","wpcafe") : "" ?>"></div>
            <div class='date_missing' data-date_missing="<?php echo esc_html__("Please select a date first","wpcafe");?>"></div>
        <?php

        do_action( 'wpcafe/metabox/before_reservation_meta', get_the_ID() );

        $wpc_visual_selection = absint( get_post_meta(get_the_ID(), 'wpc_visual_selection', true) );

        $this->reservation_fields = [
            'wpc_name'              => [
                'label'    => esc_html__( 'Name', 'wpcafe' ),
                'type'     => 'text',
                'default'  => '',
                'value'    => '',
                'desc'     => esc_html__( 'Name of customer', 'wpcafe' ),
                'priority' => 1,
                'attr'     => ['class' => 'wpc-label-item'],
                'required' => true,
            ],
            'wpc_email'             => [
                'label'    => esc_html__( 'Email', 'wpcafe' ),
                'type'     => 'email',
                'default'  => '',
                'value'    => '',
                'desc'     => esc_html__( 'Email of customer', 'wpcafe' ),
                'priority' => 1,
                'attr'     => ['class' => 'wpc-label-item'],
                'required' => true,
            ],
            'wpc_phone'             => [
                'label'    => esc_html__( 'Phone', 'wpcafe' ),
                'type'     => 'tel',
                'default'  => '',
                'value'    => '',
                'desc'     => esc_html__( 'Phone of customer', 'wpcafe' ),
                'priority' => 1,
                'attr'     => ['class' => 'wpc-label-item'],
                'required' => true,
            ],
            'wpc_message'           => [
                'label'    => esc_html__( 'Message', 'wpcafe' ),
                'type'     => 'textarea',
                'default'  => '',
                'value'    => '',
                'desc'     => esc_html__( 'Add a note', 'wpcafe' ),
                'priority' => 1,
                'attr'     => ['class' => 'wpc-label-item'],
                'required' => true,
            ],
            'wpc_booking_date'      => [
                'label'     => esc_html__( 'Date', 'wpcafe' ),
                'type'      => 'text',
                'inline'    => false,
                'timestamp' => false,
                'priority'  => 1,
                'desc'      => esc_html__( 'Date of reservation', 'wpcafe' ),
                'attr'      => ['class' => 'wpc-label-item wpc-booking-date', 'wpc_visual_selection' => $wpc_visual_selection],
                'required'  => true,
            ],
            'wpc_from_time'         => [
                'label'    => esc_html__( 'From', 'wpcafe' ),
                'type'     => 'text',
                'default'  => '',
                'value'    => '',
                'desc'     => esc_html__( 'Reservation start time', 'wpcafe' ),
                'priority' => 1,
                'attr'     => ['class' => 'wpc-label-item wpc_from_time', 'wpc_visual_selection' => $wpc_visual_selection],
                'required' => true,
            ],
            'wpc_to_time'           => [
                'label'    => esc_html__( 'To', 'wpcafe' ),
                'type'     => 'text',
                'default'  => '',
                'value'    => '',
                'desc'     => esc_html__( 'Reservation end time', 'wpcafe' ),
                'priority' => 1,
                'attr'     => ['class' => 'wpc-label-item wpc_to_time', 'wpc_visual_selection' => $wpc_visual_selection],
                'required' => true,
            ],
            'wpc_total_guest'       => [
                'label'    => esc_html__( 'No of Guests', 'wpcafe' ),
                'type'     => 'select_single',
                'options'  => Wpc_Utilities::get_seat_count_limit(),
                'priority' => 1,
                'required' => true,
                'desc'     => esc_html__( 'No of total guests', 'wpcafe' ),
                'attr'     => ['class' => 'wpc-label-item', 'wpc_visual_selection' => $wpc_visual_selection ],
            ],
            
        ];

        // get branch
        if( isset( $settings['show_branches'] ) && $settings['show_branches'] !=="" ){
            $this->reservation_fields['wpc_branch'] = [
                'label'    => esc_html__('Which branch of our restaurant', 'wpcafe'),
                'type'     => 'select_single',
                'options'  => Wpc_Utilities::get_location_data( 'Select a branch', 'No branch is set', 'value' ),
                'priority' => 1,
                'required' => true,
                'desc'     => esc_html__( "Show food location / branch in reservation form", "wpcafe" ),
                'attr'     => ['class' => 'wpc-label-item'],
            ] ;
        }

        $show_dynamic_reservation_status = apply_filters( 'wpcafe/meta/show_reservation_status_dynamic', get_the_ID(), true );
        if ( $show_dynamic_reservation_status ) {
            $this->reservation_fields['wpc_reservation_state'] = [
                'label'    => esc_html__( 'Status', 'wpcafe' ),
                'type'     => 'select_single',
                'options'  => Wpc_Utilities::get_reservation_states(),
                'priority' => 1,
                'required' => true,
                'desc'     => esc_html__( 'Reservation status', 'wpcafe' ),
                'attr'     => ['class' => 'wpc-label-item'],
            ];
        }

        do_action( 'wpcafe/metabox/after_reservation_meta', get_the_ID() );


        // get extra field
        $all_reserve_fields = apply_filters('wpcafe/meta/extra_field_label', $this->reservation_fields , get_the_ID() );

        return $all_reserve_fields;
    }

    /**
     * Save metabox title
     *
     */
    public function wpc_set_reservation_title( $data, $postarr ) {
		
        if ( is_admin() && 'wpc_reservation' == $data['post_type'] && isset($postarr['wpc_email']) && $postarr['wpc_email'] !=='' ) {
            
            /**
             * update reservation title from reservation meta
             */
            if ( isset( $postarr['wpc_name'] ) ) {
                $reservation_title = sanitize_text_field( $postarr['wpc_name'] );
            } else {
                $reservation_title = get_post_meta( $postarr['ID'], 'wpc_name', true );
            }

            if ( isset( $postarr['wpc_email'] ) ) {
                $wpc_email = sanitize_email( $postarr['wpc_email'] );
            }

            $reservation_state = isset( $postarr['wpc_reservation_state'] ) ? sanitize_text_field( $postarr['wpc_reservation_state'] ) : 'Pending';

            $post_slug          = sanitize_title_with_dashes( $reservation_title, '', 'save' );
            $reservation_slug   = sanitize_title( $post_slug );
            $data['post_title'] = $reservation_title;
            $data['post_name']  = $reservation_slug;

            /**
             * insert invoice but don't update
             */
            $saved_reservation_invoice = get_post_meta( $postarr['ID'], 'wpc_reservation_invoice', true );
            $invoice_no                = '';

			$this->update_reservation_meta( $postarr );

            if ( $saved_reservation_invoice !=="" ) {
                $postarr['wpc_reservation_invoice'] = $saved_reservation_invoice;
                $invoice_no                         = $saved_reservation_invoice;
            } else {
                $postarr['wpc_reservation_invoice'] = Wpc_Utilities::generate_invoice_number( $postarr['ID'] );
                update_post_meta( $postarr['ID'], 'wpc_reservation_invoice', $postarr['wpc_reservation_invoice'] );
                $saved_reservation_invoice = $invoice_no = $postarr['wpc_reservation_invoice'];
            }

			// send notification to user and admin
			$this->send_notification($postarr,$wpc_email,$saved_reservation_invoice,$invoice_no,$reservation_state  );

			// update extra field 
            apply_filters('wpcafe/reservation_with_food/extra_field',  $postarr );

        }
        
        return $data;
    }
	
	/**
	 * Send email to user and admin
	 *
	 * @param [type] $postarr
	 * @param [type] $wpc_email
	 * @param [type] $saved_reservation_invoice
	 * @param [type] $invoice_no
	 * @return void
	 */
	private function send_notification($postarr,$wpc_email,$saved_reservation_invoice,$invoice_no,$reservation_state ) {

		/**
		 * send required notification to both user and admin
		 * as per the reservation notification settings
		 */
		$settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();

		if ( isset( $reservation_state )  ) {
			/**
			 * email to admin & user for new booking request
			 */
			switch ( $reservation_state ) {
				case ( ($reservation_state == 'cancelled' || $reservation_state == 'confirmed') && $saved_reservation_invoice !="" ):
					$wpc_template = [
						'invoice'       => $saved_reservation_invoice, 
						'reservation_id'=>$postarr['ID'],
						'wpc_email'     => $wpc_email, 
					];
					apply_filters( 'wpcafe/metabox/notification', $settings, $reservation_state, $wpc_template );
					break;
				case (  $reservation_state == 'pending'  && $saved_reservation_invoice !=="" ):
					$message = $settings['wpc_pending_message'];

					$args = array(
						'wpc_email'     => $wpc_email,
						'invoice'       => $invoice_no,
						'message'       => $message,
						'reservation_id'=> $postarr['ID']
					);

					$send_notification = apply_filters('wpcafe/notification/send_email_notification', true, $invoice_no);

					if( $send_notification ){
						Wpc_Utilities::send_notification_admin_user( $settings , $args );
					}
					break;
			}
		}
	}
	
	/**
	 * Add reservation meta
	 *
	 * @param [type] $postarr
	 * @return void
	 */
	public function update_reservation_meta( $postarr ){
		$meta = array(
			'wpc_name','wpc_email','wpc_message','wpc_total_guest','wpc_reservation_state'
		);
		foreach ($meta as $key => $value) {
			update_post_meta( $postarr['ID'], $value , $postarr[$value] );
		}
	}

}
