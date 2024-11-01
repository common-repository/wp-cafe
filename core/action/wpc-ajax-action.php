<?php

namespace WpCafe\Core\Action;

defined( 'ABSPATH' ) || exit;

use WpCafe\Utils\Wpc_Utilities as Utils;
use Wpcafe_Pro\Utils\Table_Utils as Table_Layout_Helper;
/**
 * All ajax action
 */
class Wpc_Ajax_Action {

	use \WpCafe\Traits\Wpc_Singleton;

	/**
	 * Ajax function call
	 */
	public function init() {

			$callback = ['wpc_check_for_submission','filter_food_location', 
			'wpc_seat_capacity','filter_chart_data'];

			if ( !empty( $callback ) ) {
					foreach ($callback as $key => $value) {
							add_action( 'wp_ajax_'. $value , [$this , $value ] );
							add_action( 'wp_ajax_nopriv_'. $value , [$this , $value ] );
					}
			}
	}

	/**
	 * Reservation form submit check
	 */
	public function wpc_check_for_submission() {
		// Process a booking request

		if ( ! wp_verify_nonce( $_REQUEST['_wpc_nonce'], 'wpc_check_for_submission_nonce' ) ) {
            wp_send_json_error( [
                'message' => esc_html__( 'Nonce verification failed!', 'wpcafe' )
            ] );
        }


		$settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
		
		if ( "wpc_reservation" == sanitize_text_field( $_POST['wpc_action'] ) ) {

				//check for valid nonce
				$post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );

				//store our post vars into variables for later use
				//now would be a good time to run some basic error checking/validation
				//to ensure that data for these values have been set

				$meta_array                          = [];
				$meta_array['wpc_name']              = $title                 = isset( $post_arr['wpc_name'] ) ? sanitize_text_field( $post_arr['wpc_name'] ) : "";
				$meta_array['wpc_message']           = $content               = isset( $post_arr['wpc_message'] ) ? sanitize_text_field( $post_arr['wpc_message'] ) : "";
				$meta_array['wpc_email']             = $wpc_email             = (isset( $post_arr['wpc_email'] ) && is_email( $post_arr['wpc_email'] ) ) ? sanitize_email( $post_arr['wpc_email'] ) : "";
				$meta_array['wpc_phone']             = $wpc_phone             = isset( $post_arr['wpc_phone'] ) ? preg_replace( '/[^0-9+-]/', '', sanitize_text_field( $post_arr['wpc_phone'] ) ) : "";
				$meta_array['wpc_total_guest']       = $wpc_total_guest       = isset( $post_arr['wpc_guest_count'] ) ? intval( sanitize_text_field( $post_arr['wpc_guest_count'] ) ) : "";
				$meta_array['wpc_from_time']         = $wpc_from_time         = isset( $post_arr['wpc_from_time'] ) ? sanitize_text_field( $post_arr['wpc_from_time'] ) : "";
				$meta_array['wpc_to_time']           = $wpc_to_time           = isset( $post_arr['wpc_to_time'] ) ? sanitize_text_field( $post_arr['wpc_to_time'] ) : "";
				$meta_array['wpc_booking_date']      = $wpc_date              = isset( $post_arr['wpc_booking_date'] ) ? $post_arr['wpc_booking_date'] : "";
				$meta_array['wpc_branch']            = $wpc_branch            = isset( $post_arr['wpc_branch'] ) ? $post_arr['wpc_branch'] : "";

				// Get first and last name from the title (wpc_name)
				$full_name   = explode(' ', $title);
				$last_name   = end($full_name);
				array_pop($full_name);
				$first_name  =  implode(' ', $full_name);

				// Fluent CRM data array
				$crm_contact = [
						'first_name' => $first_name,
						'last_name' => $last_name,
						'email'      => $wpc_email,
						'phone' => $wpc_phone
				]; 

				// Calling fluent CRM webhook
				$url = isset( $post_arr['wpc_webhook'] ) ? htmlspecialchars_decode($post_arr['wpc_webhook'] , ENT_QUOTES): "";
				
				if ( wp_http_validate_url( $url ) ) {
					wp_remote_post( $url, ['body' => $crm_contact]  );
				}


				// table based booking
				$table_based_error      = false;
				$table_based_error_msg  = '';
				$wpc_visual_selection   = isset( $post_arr['wpc_visual_selection'] ) ? absint( $post_arr['wpc_visual_selection'] ) : 0;

				if ( $wpc_visual_selection ) {
						$selected_ids   = isset( $post_arr['wpc_booked_ids'] ) ? $post_arr['wpc_booked_ids'] : '';
						$selected_ids   = !empty( $selected_ids ) ? explode( ',', $selected_ids ) : [];
						$total_selected = count( $selected_ids );

						if ( $total_selected > 0 ) {
								if ( $wpc_total_guest != $total_selected ) {
										$table_based_error     = true;
										$table_based_error_msg = esc_html__('Sorry! There exists some mismatch between total guest and total selected seat. Please check and try again.', 'wpcafe' );
								} else {
										$booking_data       = Table_Layout_Helper::get_booking_data( $wpc_date, $wpc_from_time, $wpc_to_time );
										$booking_open       = $booking_data['booking_open'];

										if ( $booking_open ) {
												$capacity         = $booking_data['capacity'];
												$booked_total     = $booking_data['booked_total'];
												
												if ( ( $booked_total + $total_selected ) <= $capacity ) {
														$booked_ids       = $booking_data['booked_ids'];
										
														$intersected_ids    = array_intersect( $selected_ids, $booked_ids );
														$table_based_error  = !empty( $intersected_ids ) ? true : false;
												} else {
														$remaining_seats = $capacity - $booked_total;
														$table_based_error = true;
														$table_based_error_msg = esc_html__('Sorry! The number of seats you selected exceeds the total seat capacity. Only ', 'wpcafe' ) . $remaining_seats . esc_html__( ' seat(s) are remaining.', 'wpcafe' );
												}
										} else {
												$table_based_error = true;
												$table_based_error_msg = esc_html__('Sorry! All seats are already booked. Please change schedule and try again.', 'wpcafe' );
										}

										if ( !$table_based_error ) {
												$wpc_schedule_slug                   = isset( $post_arr['wpc_schedule_slug'] ) ? $post_arr['wpc_schedule_slug'] : Table_Layout_Helper::retrieve_slug_name();
												$wpc_obj_names                       = isset( $_POST['wpc_obj_names'] ) ? sanitize_text_field( $_POST['wpc_obj_names'] ) : [];
												$wpc_intersected_data                = isset( $_POST['wpc_intersected_data'] ) ? sanitize_text_field( $_POST['wpc_intersected_data'] ) : [];
												$wpc_mapping_data                    = isset( $_POST['wpc_mapping_data'] ) ? sanitize_text_field( $_POST['wpc_mapping_data'] ) : [];
												$selected_table_ids                  = isset( $_POST['wpc_booked_table_ids'] ) ? sanitize_text_field( $_POST['wpc_booked_table_ids'] ) : [];
												$selected_table_ids                  = !empty( $selected_table_ids ) ? explode( ',', $selected_table_ids ) : [];
												
												$meta_array['wpc_visual_selection']  = $wpc_visual_selection;
												$meta_array['wpc_schedule_slug']     = $wpc_schedule_slug;
												$meta_array['wpc_booked_ids']        = maybe_serialize( $selected_ids );
												$meta_array['wpc_booked_table_ids']  = maybe_serialize( $selected_table_ids );
												$meta_array['wpc_obj_names']         = $wpc_obj_names;
												$meta_array['wpc_intersected_data']  = $wpc_intersected_data;
												$meta_array['wpc_mapping_data']      = $wpc_mapping_data;
										} else {
												$table_based_error_msg = esc_html__('Sorry! There are some seats which are already booked, Please reload and select again.' ,'wpcafe' );
										}
								}
						}
				}

				$post_type = 'wpc_reservation';

				$post_slug = sanitize_title_with_dashes( $title, '', 'save' );
				$postslug  = sanitize_title( $post_slug );

				if ( !$table_based_error ) {
						if ( isset( $title ) && isset( $wpc_email ) && isset( $wpc_total_guest ) &&
								isset( $wpc_from_time ) && isset( $wpc_to_time ) && isset( $wpc_date ) ||
								( isset( $settings['wpc_require_phone'] ) && isset( $wpc_phone ) ) ||
								( isset( $settings['require_branch'] ) && isset( $wpc_branch ) )
								) {

								//the array of arguments to be inserted with wp_insert_post
								$new_post = [
										'post_title'     => $title,
										'post_content'   => $content,
										'post_status'    => 'publish',
										'post_type'      => $post_type,
										'comment_status' => 'closed',
										'post_name'      => $postslug,
								];

								//insert the the post into database by passing $new_post to wp_insert_post
								//store our post ID in a variable $pid
								$pid                                   = wp_insert_post( $new_post );
								$invoice                               = Utils::generate_invoice_number( $pid );
								$meta_array['wpc_reservation_invoice'] = $invoice;

								// if food with reservation
								if ( !empty( $post_arr['order_id'] ) ) {
										$meta_array['order_id']          = $post_arr['order_id'];
										$response_data                   = get_post_meta( $meta_array['order_id'] , 'reservation_details', true);
										if ( $response_data !=="" ) {
												$response_data->reservation_id = $pid;
												update_post_meta( $meta_array['order_id'] , 'reservation_details' , $response_data );
										}

										//Automatic confirmed booking guest no
										$meta_array['wpc_reservation_state'] = !empty( $response_data->status ) ? $response_data->status : 'pending';

								}else{
										$default_guest                       = isset( $settings['wpc_default_guest_no'] ) ? intval( $settings['wpc_default_guest_no'] ) : 1;
										$meta_array['wpc_reservation_state'] = ( (int)$default_guest !== 0 && (int)$wpc_total_guest  <= (int) $default_guest ) ? 'confirmed' : 'pending';
								}

								//we now use $pid (post id) to help add out post meta data
								foreach ( $meta_array as $key => $value ) {
										add_post_meta( $pid, $key, $value, true );
								}

								apply_filters( 'wpcafe_pro/action/extra_field', $pid , $post_arr );

								/** use action for success message **/
								if ( $pid != 0 ) {

										$message = ''; $form_type = "";
										
										if ( $meta_array['wpc_reservation_state'] == 'confirmed' ) {
												$message  = $settings['wpc_booking_confirmed_message'];

										} elseif ( $meta_array['wpc_reservation_state'] == 'pending' ) {
												$message  = $settings['wpc_pending_message'];
										}

										$form_type          ="wpc_reservation";

										$response = [ 'status_code' => 200 , 'message' => [ $message ] ,'data' => ['form_type' => $form_type , 'invoice'=> $invoice ] ];

										/**
										 * email to admin & user for new booking request
										 */
										$args = array(
												'wpc_email'     => $wpc_email,
												'invoice'       => $invoice,
												'message'       => $message,
												'reservation_id'=> $pid
										);

										if ( class_exists('Wpcafe_Pro') ){

												if ( $meta_array['wpc_reservation_state']  == 'confirmed' ) {
													// if auto confirm and pro active send a confirmation message to admin and user
													apply_filters( 'wpcafe/metabox/notification', $settings, $meta_array['wpc_reservation_state'], $args );
												}
												// send email for food with reservation.
												$send_notification = apply_filters('wpcafe/notification/send_email_notification', true, $invoice);
												if( $send_notification ){
														Utils::send_notification_admin_user( $settings , $args );
												}

										}else{
											Utils::send_notification_admin_user( $settings , $args );
										}

										//send email to specific branch email id
										$branch_data = get_term_by( 'slug', $wpc_branch, 'wpcafe_location' );
										if(!empty($branch_data)){
												$location_email = get_term_meta($branch_data->term_id, 'location_email', true);
												if($location_email != '') {
														$args_branch = array(
																'wpc_email'     => $location_email,
																'invoice'       => $invoice,
																'message'       => $message,
																'reservation_id'=> $pid
														);

														$send_notification_branch = apply_filters('wpcafe/notification/send_email_notification', true, $invoice);

														if( $send_notification_branch ){
																Utils::send_notification_branch_user( $settings , $args_branch );
														}
												}

										}

										wp_send_json_success( $response );
										
										
								} else {
										$response = [ 'status_code' => 400 , 'message' => [ esc_html__('Booking placement was failed, please try again!' ,'wpcafe' ) ] , 'data' => ['form_type' => 'wpc_reservation_field_missing'] ];
										wp_send_json_error( $response );
								}
						} else {
								$response = [ 'status_code' => 400 , 'message' => [ esc_html__('Please enter all required fields!' ,'wpcafe' )  ] , 'data' => ['form_type' => 'wpc_reservation_field_missing'] ];
								wp_send_json_error( $response );
						}
				} else {
						$response = [ 'status_code' => 400 , 'message' => [ $table_based_error_msg ] , 'data' => ['form_type' => 'wpc_reservation_field_missing'] ];
						wp_send_json_error( $response );
				}
		}

		if ( isset( $_POST['wpc_action'] ) && "wpc_cancellation" == sanitize_text_field( $_POST['wpc_action'] ) ) {

				$post_arr   = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
				$invoice_no = isset( $post_arr['wpc_reservation_invoice'] ) ?
				sanitize_text_field( $post_arr['wpc_reservation_invoice'] ) : "";
				$wpc_email = ( isset( $post_arr['wpc_cancell_email'] ) && is_email( $post_arr['wpc_cancell_email'] ) ) ?
				sanitize_email( $post_arr['wpc_cancell_email'] ) : "";
				$wpc_phone = isset( $post_arr['wpc_cancell_phone'] ) ?
				preg_replace( '/[^0-9+-]/', '', sanitize_text_field( $post_arr['wpc_cancell_phone'] ) ) : "";
				$content = sanitize_text_field( $post_arr['wpc_message'] );
				$eligible_for_cancellation = apply_filters('wpcafe/cancellation_form/invoice_eligibility', true, $invoice_no);
				//check if all required fields are given
				//else show a message

				if( !$eligible_for_cancellation ){
						$response = [ 
								'status_code' => 400 , 
								'message' => [ esc_html__(  'Your reservation includes food menu order. So this reservation can not be cancelled through this form. Please contact restaurant admin for cancelling your reservation manually.', 'wpcafe' )  ] , 
								'data' => ['form_type' => 'wpc_reservation_cancell'] 
						];
						wp_send_json_error( $response );
				}else if ( $invoice_no && $wpc_email ) {
						$args = array(
								'post_type'      => 'wpc_reservation',
								'posts_per_page' => '1',
								'meta_query'     => array(
										array(
												'key'   => 'wpc_reservation_invoice',
												'value' => $invoice_no,
										),
										array(
												'key'   => 'wpc_email',
												'value' => $wpc_email,
										)
								),
						);

						$reservations = get_posts( $args );

						//check if reservation record found with the given details
						if ( !$reservations || is_wp_error( $reservations ) ) {
								$response = [ 'status_code' => 401 , 'message' => [ esc_html__( 'No reservation found with the given details' , 'wpcafe' ) ] , 'data' => ['form_type' => 'wpc_reservation_cancell'] ];
						} else {
								$reservation_id = $reservations[0]->ID;
								update_post_meta( $reservation_id, 'wpc_reservation_state', 'cancelled' );
								apply_filters( 'wpcafe/action/cancell_notification', $settings, $invoice_no ,[]);
								$response = [ 'status_code' => 200 , 'message' => [ esc_html__( 'Cancellation requested successfully!', 'wpcafe' ) ] , 'data' => ['form_type' => 'wpc_reservation_cancell'] ];
						}
						wp_send_json_success( $response );
				} else {
						$response = [ 'status_code' => 400 , 'message' => [ esc_html__(  'Please enter required fields correctly!', 'wpcafe' )  ] , 'data' => ['form_type' => 'wpc_reservation_cancell'] ];
						wp_send_json_error( $response );
				}
		}

		exit;
	}

	/**
	 * Filter menu by food location
	 */
	public function filter_food_location(){
		global $woocommerce;

		if ( ! wp_verify_nonce( $_REQUEST['_wpc_nonce'], 'filter_food_location_nonce' ) ) {
            wp_send_json_error( [
                'message' => esc_html__( 'Nonce verification failed!', 'wpcafe' )
            ] );
        }
		
		$post_arr     = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
		$location     = $post_arr['location'];
		
		if( isset( $post_arr['product_data'] ) ){
				$product_data           = $post_arr['product_data'];
				$show_thumbnail         = $product_data['show_thumbnail'];
				$show_item_status       = $product_data['show_item_status'];
				$wpc_cart_button        = $product_data['wpc_cart_button'];
				$wpc_price_show         = $product_data['wpc_price_show'];
				$wpc_show_desc          = $product_data['wpc_show_desc'];
				$wpc_delivery_time_show = $product_data['wpc_delivery_time_show'];
				$wpc_desc_limit         = $product_data['wpc_desc_limit'];
				$unique_id              = $product_data['unique_id'];
				$col                    = 'wpc-col-md-'.$product_data['wpc_menu_col'];
				$title_link_show        = $product_data['title_link_show'];
				$get_location           = $location =="" ? [] : [$location];

				$args = array(
						'order'         => 'DESC',
						'wpc_cat'       => $get_location,
						'taxonomy'      => 'wpcafe_location',
				);
				

				$products = Utils::product_query ( $args );
				ob_start();
				?>
						<div class='wpc-food-wrapper wpc-menu-list-style1'>
								<?php

								if ( !empty( $products ) ) { 
										
										include \Wpcafe::plugin_dir() . "widgets/wpc-menus-list/style/style-1.php";

								}else{
										?>
												<div><?php esc_html_e( 'No menu found', 'wpcafe'); ?></div>
										<?php
								}
								?>
						</div>
				<?php
				$html=ob_get_clean();
		}


		// clear cart data
		if (!empty($post_arr['clear_cart']) && $post_arr['clear_cart'] == 1 ) {
				$woocommerce->cart->empty_cart();
				WC()->session->set('cart', array());
		}

		// check cart data
		$cart_empty = WC()->cart->cart_contents_count == 0  ? 1 : 0 ;

		if( isset( $post_arr['product_data'] ) ){
				wp_send_json([ 'html'=>$html, 'cart_empty' => $cart_empty ]);
		}else{
				wp_send_json([ 'cart_empty' => $cart_empty ]);
		}

		wp_die();
	}

	/**
	 * Responsible for seat capacity
	 */
	public function wpc_seat_capacity(){

		if ( ! wp_verify_nonce( $_REQUEST['_wpc_nonce'], 'wpc_seat_capacity_nonce' ) ) {
            wp_send_json_error( [
                'message' => esc_html__( 'Nonce verification failed!', 'wpcafe' )
            ] );
        }

			$data    = array('message'=>'','status'=>'open');
			$message = esc_html__(  'Something is wrong ','wpcafe');

			$post_arr= filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );

			if ( empty($post_arr['wpc_action']) || "wpc_seat_status" !== $post_arr['wpc_action']) {

					return wp_send_json_error([ 'success' => false , 'message' => [ $message ] , 'data' => $data
					] );
			}

			if (!empty($post_arr['selected_date']) ) {
					$from_time  = !empty($post_arr['from_time']) ? $post_arr['from_time'] : '';
					$to_time    = !empty($post_arr['to_time']) ? $post_arr['to_time'] : '';
					$type    = !empty($post_arr['type']) ? $post_arr['type'] : '';
					if ( class_exists('Wpcafe_Pro') &&  '' !== $from_time ) {
						$data = \WpCafe_Pro\Core\Modules\Reservation\Hooks::instance()->
						reser_capacity_status($post_arr['selected_date'], $from_time , $to_time , $type);

						$message = esc_html__('everything is ok ','wpcafe');
					}

			}

			wp_send_json_success( [ 'success' => true , 'message' => [ $message  ] , 'data' => $data ] );

			exit();
	}

	/**
	 * Filter chart data for overview page
	 */
	public function filter_chart_data(){

		if ( ! wp_verify_nonce( $_REQUEST['_wpc_nonce'], 'filter_chart_data_nonce' ) ) {
            wp_send_json_error( [
                'message' => esc_html__( 'Nonce verification failed!', 'wpcafe' )
            ] );
        }

		$post_arr   = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
		$start      = null ; 
		$end        = null ; 
		
		if ( !empty($post_arr['date_range']) && is_array($post_arr['date_range']) ) {
				$start      = !empty($post_arr['date_range'][0]) ? date('Y-m-d', strtotime($post_arr['date_range'][0])) : null;
				$end        = !empty($post_arr['date_range'][1]) ? date('Y-m-d', strtotime($post_arr['date_range'][1])) : null;
		}else{
				$start      = $post_arr['date_range'];
		}
		
		// get chart data
		$chart_data = \WpCafe\Core\Modules\Reservation\Hooks::instance()
		->filter_report_by_date($post_arr['type'],[$start,$end]);

		wp_send_json_success([ 'success'=> true , 'data' => $chart_data ]);  

		wp_die();
	}

}
