<?php

namespace WpCafe\Core\Action;

use WpCafe\Utils\Wpc_Utilities;

defined( 'ABSPATH' ) || exit;
/**
 * Settings option  saving
 */
class Wpc_Action {

	use \WpCafe\Traits\Wpc_Singleton;

	private $key_option_settings;
	private $form_id;
	private $form_setting;
	public $response = [];

	/**
	 * Return response
	 */
	function __construct( $option_name='wpcafe_reservation_settings_options') {
		$this->key_option_settings = $option_name;
		$this->response            = [
			'saved'  => false,
			'status' => esc_html__( 'Something went wrong', 'wpcafe' ),
			'data'   => [],
		];
	}

	/**
	 * Update settings
	 */
	public function wpc_store( $form_id, $form_setting ) {
		if ( !current_user_can( 'manage_options' ) ) {
			return;
		}

		$this->wpc_sanitize( $form_setting );
		$this->form_id = $form_id;

		if ( $this->form_id == -1 ) {
			$this->wpc_update_option_settings();
		}

		return;
	}

	/**
	 * Sanitize field
	 */
	public function wpc_sanitize( $form_setting ) {
		foreach ( $form_setting as $key => $value ) {
			$this->form_setting[$key] = $value;
		}

	}

	/**
	 * Update field
	 */
	public function wpc_update_option_settings() {
		$form_data   = $this->form_setting;

		if ( !empty( $form_data['wpcafe_app_settings_options'] ) && $form_data['wpcafe_app_settings_options']=="app_settings_save" ) {
			// App settings
			if( isset( $form_data['home_banner'] ) ) {
				$home_banner_arr = $form_data['home_banner'];
			
				foreach( $home_banner_arr as $index => $data ) {
					if( empty( $data['image'] ) ) {
						unset( $home_banner_arr[$index] ); // if image is not set, then discard this block
					}
				}
			}
			
			$form_data['home_banner'] = array_values( $home_banner_arr );
			
			$response = update_option( 'wpcafe_app_settings_options', $form_data );
					
			return wp_redirect('admin.php?page=app_settings');
		}
		else if ( !empty( $form_data['wpcafe_tools_action'] ) && $form_data['wpcafe_tools_action']=="tools_save" ) {
			
			$response = update_option( 'wpcafe_tools_settings', $form_data );

			return wp_redirect('admin.php?page=wpc_tools');
		}
		else if ( !empty( $form_data['wpcafe_product_addons'] ) && $form_data['wpcafe_product_addons']=="product_addons_save" ) {
			
			$response = update_option( 'wpcafe_product_addons', $form_data );

			return wp_redirect('admin.php?page=wpc_product_addons');
		}
		else {
			// Cafe settings

			// array values are input field names from settings
			$no_related_setting_keys = [
				'wpc_pickup_exception_date',
				'wpc_delivery_exception_date',
			];

			$related_three_setting_keys = [
				// single slot weekly
				[
					'wpc_weekly_schedule',
					'wpc_weekly_schedule_start_time',
					'wpc_weekly_schedule_end_time',
				],
				// exception dates: common for single/multi slot reservation
				[
					'wpc_exception_date',
					'wpc_exception_start_time',
					'wpc_exception_end_time',
				],
				[
					'wpc_pickup_weekly_schedule',
					'wpc_pickup_weekly_schedule_start_time',
					'wpc_pickup_weekly_schedule_end_time',
				],
				[
					'wpc_delivery_schedule',
					'wpc_delivery_weekly_schedule_start_time',
					'wpc_delivery_weekly_schedule_end_time',
				],
			];

   //multi slot daily 4.
			$related_four_setting_keys = array(
				array(
					'multi_start_time',
					'multi_end_time',
					'schedule_name',
					'seat_capacity',
				),
			);

	 	 // multi slot weekly 5
			$related_five_setting_keys = array(
				array(
					'multi_diff_weekly_schedule',
					'multi_diff_start_time',
					'multi_diff_end_time',
					'diff_schedule_name',
					'diff_seat_capacity',
				),
			);

			// discard empty array field if all fields of a row is empty.
			$form_data = Wpc_Utilities::discard_individual_empty_fields_from_settings( $form_data, $no_related_setting_keys );
			$form_data = Wpc_Utilities::discard_three_related_empty_fields_from_settings( $form_data, $related_three_setting_keys );
			$form_data = Wpc_Utilities::discard_four_related_empty_fields_from_settings( $form_data, $related_four_setting_keys );
			$form_data = Wpc_Utilities::discard_five_related_empty_fields_from_settings( $form_data, $related_five_setting_keys );

			$response = update_option( $this->key_option_settings, $form_data );
			$tab_name = $form_data['settings_tab'];
			$redirect = 'admin.php?page=cafe_settings&saved='.$response.'';

			return wp_redirect( add_query_arg( 'settings-tab' , $tab_name, $redirect ) );
		}
	}

}
