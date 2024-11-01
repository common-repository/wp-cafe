<?php

namespace WpCafe\Core\Base;

defined( 'ABSPATH' ) || exit;

use DateTime;
use Exception;
use WpCafe\Utils\Wpc_Utilities as Utils;
use WP_Error;


abstract class Wpc_Metabox extends Wpc_Repeater_Metabox {

   protected function is_secured($nonce_field, $action, $post_id,  $post) {
      $nonce = isset($post[$nonce_field]) ? sanitize_text_field( $post[$nonce_field] ) : '';

      if ($nonce == '') {
         return false;
      }

      if (!current_user_can('edit_post', $post_id)) {
         return false;
      }

      if (wp_is_post_autosave($post_id)) {
         return false;
      }

      if (wp_is_post_revision($post_id)) {
         return false;
      }

      if (!wp_verify_nonce($nonce, $action)) {
         return false;
      }
      return true;
   }

   public function display_callback($post) {
       foreach ($this->wpc_default_metabox_fields() as $key => $value){
          $this->get_markup($value, $key);
       }
       wp_nonce_field('wpc_meta_data', 'wpc_meta_fields');
    }
 
    function save_meta_box_data($post_id) {
      $post_arr = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

       if (!$this->is_secured('wpc_meta_fields', 'wpc_meta_data', $post_id, $post_arr)) {
          return $post_id;
       }
 
       try {
          $this->update( $post_arr , $this->wpc_default_metabox_fields() );
       } catch (Exception $e) {
          $error = new WP_Error($e->getCode(), $e->getMessage());
       }
    }
 
    protected function update( $post_arr ,  $fields = null) {
       if (!is_array($fields) || !count($fields)) {
          throw new Exception(esc_html__("meta data field not found", 'wpcafe'));
       }
	   	$extra_field_arr = array();	
       foreach ($fields as $field_key => $field) {
          if ($field['type'] == 'radio' || $field['type'] == 'select2') {
             if (isset($post_arr[$field_key])) {
                $upload_key  = isset($post_arr[$field_key]) ? sanitize_text_field($post_arr[$field_key]) : '';
                $rv = $upload_key;
                update_post_meta(get_the_ID(), $field_key, $rv);
             } else {
                update_post_meta(get_the_ID(), $field_key, '');
             }
          }
		  elseif ($field['type'] == 'checkbox') {
			if (isset($post_arr[$field_key])) {
				$checked_value   = implode( "," , $post_arr[$field_key] );
			   	$upload_key      = isset($post_arr[$field_key]) ? sanitize_text_field( $checked_value ) : '';
			   	$rv = $upload_key;
			   update_post_meta(get_the_ID(), $field_key, $rv);

			} else {
			   update_post_meta(get_the_ID(), $field_key, '');
			}
		 }
          elseif ($field['type'] == 'upload') {
             if (isset($post_arr[$field_key])) {
                $upload_key  = isset($post_arr[$field_key]) ? sanitize_text_field($post_arr[$field_key]) : '';
                $upload_key = sanitize_text_field($upload_key);
                update_post_meta(get_the_ID(), $field_key, $upload_key);
             }
          } 
          elseif ($field['type'] == 'wp_editor') {
             if (isset($post_arr[$field_key])) {
                $upload_key  = isset($post_arr[$field_key]) ? sanitize_text_field($post_arr[$field_key]) : '';
                update_post_meta(get_the_ID(), $field_key, $upload_key);
             }
          } 
          elseif ($field['type'] == 'social_reapeater') {
             if (isset($post_arr[$field_key])) {
                $social_key  = isset($post_arr[$field_key]) ? sanitize_text_field($post_arr[$field_key]) : '';
                if (is_array($social_key)) {
                   if (count($social_key) == 1) {
                      if ($social_key[0]['icon'] == '') {
                         update_post_meta(get_the_ID(), $field_key, "");
                      } else {
                         update_post_meta(get_the_ID(), $field_key, $social_key);
                      }
                   } else {
                      update_post_meta(get_the_ID(), $field_key, $social_key);
                   }
                }
             }
          } 
          elseif ($field['type'] == 'repeater') {
             if (isset($post_arr[$field_key])) {
                $etn_rep_key  = isset($post_arr[$field_key]) ? sanitize_text_field($post_arr[$field_key]) : '';
                if (is_array($etn_rep_key)) {
                   if (count($etn_rep_key) == 1) {
                      if (strlen(trim(implode($etn_rep_key[0]))) == 0) {
                         update_post_meta(get_the_ID(), $field_key, "");
                      } else {
                         update_post_meta(get_the_ID(), $field_key, $etn_rep_key);
                      }
                   } else {
                      update_post_meta(get_the_ID(), $field_key, $etn_rep_key);
                   }
                }
             }
          }   
          elseif($field['type'] == 'email') {
            if (isset($post_arr[$field_key])) {
               $email_value  = isset($post_arr[$field_key]) ? sanitize_email( $post_arr[$field_key]) : '';
               update_post_meta(get_the_ID(), $field_key, $email_value);
            }
         }
          elseif($field['type'] == 'tel') {
            if (isset($post_arr[$field_key])) {
               // If you want to clean it up manually you can:
               $phone = preg_replace('/[^0-9+-]/', '', $post_arr[$field_key]);
               $email_value  = isset($post_arr[$field_key]) ? $phone : '';
               update_post_meta(get_the_ID(), $field_key, $email_value);
            }
         }
		else {
			if (isset($post_arr[$field_key])) {
			$text_value  = isset($post_arr[$field_key]) ? sanitize_text_field($post_arr[$field_key]) : '';
			if ( $field_key =='wpc_booking_date' && $text_value !== "" ) {
				$wpc_date_format    =  get_option('date_format');
				$date_format        =  $wpc_date_format == "j F Y" ? "F j, Y" : $wpc_date_format;
				$date               = DateTime::createFromFormat($date_format, $text_value);
				$text_value         = !is_bool($date) ? $date->format('Y-m-d') : $text_value;
			}else {
				$text_value = sanitize_text_field($text_value);
			}
				update_post_meta(get_the_ID(), $field_key, $text_value);
				$text_field_value = $text_value;
			}
		}

       }

		$settings = get_option('wpcafe_reservation_settings_options');

		if ( isset( $settings['reserv_extra_label'] ) ) {
		// get from settings
		$extra_field_arr        = array();
			foreach ($settings['reserv_extra_label'] as $key => $value) {
				$extra_field_arr[$key]['label'] = $settings['reserv_extra_label'][$key];
				$extra_field_arr[$key]['type']  = $settings['wpc_extra_field_type'][$key];
				$extra_field_arr[$key]['value']  = get_post_meta(get_the_ID(), 'reserv_extra_'.$key , true );
			}
			update_post_meta(get_the_ID(), 'reserv_extra', $extra_field_arr);
		}	

    }
 
    protected function get_markup($item = null, $key = '') {
       if (is_null($item)) {
          return;
       }
       if (isset($item['type'])) {
 
          switch ($item['type']) {
             case "text":
                return $this->get_text_input($item, $key);
                break;
			case "hidden":
				return $this->get_hidden_input($item, $key);
				break;
             case "tel":
                return $this->get_tel_input($item, $key);
                break;
             case "date":
                return $this->get_text_input($item, $key);
                break;
             case "time":
                return $this->get_text_input($item, $key);
                break;
             case "textarea":
                return $this->get_textarea($item, $key);
                break;
             case "url":
                return $this->get_url_input($item, $key);
                break;
             case "email":
                  return $this->get_email_input($item, $key);
                  break;
             case "radio":
                return $this->get_radio_input($item, $key);
                break;
             case "select2":
                return $this->get_select2($item, $key);
                break;
             case "select_single":
                return $this->get_select_single($item, $key);
                break;
             case "upload":
                return $this->get_upload($item, $key);
                break;
             case "wp_editor":
                return $this->get_wp_editor($item, $key);
                break;
             case "social_reapeater":
                return $this->get_wp_social_reapeater($item, $key);
                break;
             case "repeater":
                return $this->get_wp_repeater($item, $key);
                break;
             case "heading":
                return $this->get_heading($item, $key);
                break;
             case "separator":
                return $this->get_separator($item, $key);
                break;
              case "checkbox":
               return $this->get_checkbox_input($item, $key);
               break;
             default:
                return;
          }
       }
 
       return;
    }
 
    public function get_wp_repeater($item, $key) {
       $value = [];
       $class = $key;
       $options_fields = $item['options'];
       $repeater_arr = get_post_meta(get_the_ID(), $key, true);
       $count = is_array($repeater_arr) ? count($repeater_arr) : 1;
       ?>
       <div class='wpcafe-event-repeater-clearfix wpcafe-repeater-item'>
         <h3 class='wpcafe-title'> <?php echo esc_html($item['label']) ?> </h3>
         <div class='wpcafe-event-manager-repeater-fld <?php echo esc_attr( $class ); ?>'>
         <div data-repeater-list='<?php echo esc_attr( $key ); ?>'>
       <?php for ($x = 0; $x < $count; $x++) {
          $label_no = $x; ?>
          <div data-repeater-list="wpcafe-event-repeater-options" class="wpcafe-repeater-item" data-repeater-item>
             <div class="form-group mb-3">
                <div class="wpcafe-event-shedule-collapsible">
                   <span class="event-title"><?php echo esc_html($item['label'] . ' ' . ++$label_no); ?></span>
                   <i data-repeater-delete type="button" class="dashicons dashicons-no-alt" aria-hidden="true"></i>
                </div>
 
                <div class="wpcafe-event-repeater-collapsible-content" style="display: none">
                   <?php $i = $x;
                   foreach ($options_fields as $op_fld_key => $options_field) : ?>
                      <?php
 
                      $nested_data = isset($repeater_arr[$i]) ? $repeater_arr[$i] : [];
 
                      ?>
                      <?php echo wp_kses($this->get_repeater_markup($options_field, $op_fld_key, $nested_data), Utils::wpc_kses_allowed_tags()); ?>
                   <?php endforeach;  ?>
                </div>
             </div>
          </div>
       <?php } ?>
 
       </div>
       <input data-repeater-create type='button' class='wpcafe-btn attr-btn-primary mb-2 clearfix' value='<?php echo esc_html__('Add','wpcafe');?>' />
       </div>
     </div>
    <?php
    }

	/**
	 * Hidden field
	 */

	public function get_hidden_input($item, $key){
		$value = '';
        $class = $key;

        $value = !empty( get_post_meta( get_the_ID(), $key, true ) ) ? get_post_meta( get_the_ID(), $key, true ) : ( !empty( $item['value'] ) ? $item['value'] : "" );

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
        }

        ?>
        <div class="<?php echo esc_html( $class ); ?>" style='display:none'>
            <div class="wpc-label">
                <label for="<?php echo esc_html( $key ); ?>"> 
                    <?php echo esc_html( $item['label'] ); ?>                     
                </label>
                <div class="wpc-desc">  <?php echo esc_html( $item['desc'] ); ?>  </div>
            </div>
            <div class="wpc-meta">
                <input autocomplete="off" class="wpc-form-control" type="hidden" name="<?php echo esc_html( $key ); ?>"
				id="<?php echo esc_html( $key ); ?>" value="<?php echo esc_html( $value ); ?>" />
            </div>
        </div>
        <?php
	}
 
    public function get_text_input($item, $key) {
        /**
         * key is the metabox id
         * item is the array of values of that id
         */
       $value = '';
       $class = $key;
       if (isset($item['value'])) {
         $value = get_post_meta(get_the_ID(), $key, true);
       }else{ 
         $value = get_post_meta(get_the_ID(), $key, true);
      }

      if ( "wpc_booking_date" == $key && ""!==$value ) {
         $wpc_date_format    =  get_option('date_format');
         $value = date_i18n($wpc_date_format, strtotime( $value ) );
      }

       if (isset($item['attr'])) {
          $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
       }

       $html = sprintf(
       '<div class="%s"> 
            <div class="wpc-label"> 
                  <label for="%s"> %s : </label>
                  <div class="wpcafe-desc">  %s  </div>
            </div>
            <div class="wpcafe-meta"> 
                <input autocomplete="off" class="wpc-form-control" type="%s" name="%s" id="%s" value="%s"/>
            </div>
            
        </div>', 
        $class, $key, $item['label'], $item['desc'], $item['type'], $key, $key, $value );

      // temporary purpose, will delete later
      $wpc_visual_selection = ( isset( $item['attr']['wpc_visual_selection'] ) && absint( $item['attr']['wpc_visual_selection'] ) == 1 ) ? 1 : 0;
      if ( $wpc_visual_selection ) {
         $html = sprintf(
            '<div class="%s"> 
                 <div class="wpc-label"> 
                       <label for="%s"> %s : </label>
                       <div class="wpcafe-desc">  %s  </div>
                 </div>
                 <div class="wpcafe-meta"> 
                  %s
                 </div>
             </div>', 
             $class, $key, $item['label'], $item['desc'], $value);
      }

      echo wp_kses($html, Utils::wpc_kses_allowed_tags());
    }
 
    public function get_tel_input($item, $key) {
        /**
         * key is the metabox id
         * item is the array of values of that id
         */
       $value = '';
       $class = $key;
 
       if (isset($item['value'])) {
         $value = get_post_meta(get_the_ID(), $key, true);
       }else{ 
         $value = get_post_meta(get_the_ID(), $key, true);
      }


       if (isset($item['attr'])) {
          $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
       }
 
       $html = sprintf(
       '<div class="%s"> 
            <div class="wpc-label"> <label for="%s"> %s : </label>
            <div class="wpcafe-desc">  %s  </div>
            </div>
            <div class="wpcafe-meta"> 
                <input autocomplete="off" class="wpc-form-control" type="%s" name="%s" id="%s" value="%s"/>
            </div>
        </div>', 
        $class, $key, $item['label'], $item['desc'], $item['type'], $key, $key, $value);
       echo  wp_kses($html, Utils::wpc_kses_allowed_tags()); 
    }
 
    public function get_email_input($item, $key) {

      $value = '';
      $class = $key;

      if (isset($item['value'])) {
         $value = get_post_meta(get_the_ID(), $key, true);
      }


      if (isset($item['attr'])) {
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field ' : ' wpc_meta_field';
      }

      $html = sprintf('<div class="%s"> 
      <div class="wpc-label"> <label for="%s"> %s : </label>
      <div class="wpcafe-desc">  %s  </div>
      </div>
      <div class="wpcafe-meta">
      <input autocomplete="off" class="wpc-form-control" type="%s" name="%s" id="%s" value="%s"/>
     </div></div>', $class, $key, $item['label'], $item['desc'],$item['type'], $key, $key, $value);

      echo wp_kses($html, Utils::wpc_kses_allowed_tags()); 
    }
 
    public function get_radio_input($item, $key) {
 
       $value = '';
       $class = $key;
       $input = '';
 
       $value = get_post_meta(get_the_ID(), $key, true);
 
       if (isset($item['attr'])) {
          $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field ' : 'wpc_meta_field ';
       }
 
       if (!isset($item['options']) || !count($item['options'])) {
          $html = sprintf('<div class=" %s"> 
          <label for="%s"> %s : </label>
         
         </div>', $class, $key, $item['label']);
 
          echo wp_kses($html, Utils::wpc_kses_allowed_tags()); 
          return;
       } elseif (isset($item['options']) && count($item['options'])) {
          $options = $item['options'];
 
          foreach ($options as $option_key => $option) {
             $checked =  $option_key == $value ? 'checked' : '';
 
             $input .= sprintf(' <input  %s type="%s" name="%s" class="wpc-form-control" value="%s"/><span> %s  </span> ', $checked, $item['type'], $key, $option_key, $option);
          }
       }
 
 
       $html = sprintf('<div class="%s form-group"> <label> %s  </label> 
           %s
      </div>', $class, $item['label'], $input);
 
       echo wp_kses($html, Utils::wpc_kses_allowed_tags()); 
    }
 
    public function get_select2($item, $key) {
       $value = '';
       $class = $key;
       $input = '';
       $value = get_post_meta(get_the_ID(), $key, true);

       if (isset($item['attr'])) {
          $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
       }
       if (!isset($item['options']) || !count($item['options'])) {
          $html = sprintf('<div class="%s form-group"> 
          <div class="wpc-label"> <label for="%s"> %s : </label></div>
         </div>', $class, $key, $item['label']);
          echo wp_kses($html, Utils::wpc_kses_allowed_tags()); 
          return;
       } elseif (isset($item['options']) && count($item['options'])) {
          $options = $item['options'];
          $input .= sprintf('<select multiple name="%s[]" class="wpc-form-control wpc_select2 %s">', $key, $key, $class);
          foreach ($options as $option_key => $option) {
             if (is_array($value) && in_array($option_key, $value)) {
                $input .= sprintf(' <option %s value="%s"> %s </option>', 'selected', $option_key, $option);
             } else {
                $input .= sprintf(' <option value="%s"> %s </option>',  $option_key, $option);
             }
          }
          $input .= sprintf('</select>');
       }
       
       $html = sprintf('
       <div class="%s"> 
          <div class="wpc-label"> 
             <label> %s  </label>
          </div>
           %s
      </div>', $class, $item['label'], $input);
 
       echo wp_kses($html, Utils::wpc_kses_allowed_tags());
    }
 
    public function get_select_single($item, $key) {
       $value = '';
       $class = $key;
       $input = '';
       $value = get_post_meta(get_the_ID(), $key, true);
       if (isset($item['attr'])) {
          $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
       }
       if (!isset($item['options']) || !count($item['options'])) {
          $html = sprintf('<div class="%s form-group"> 
          <div class="wpc-label"> <label for="%s"> %s : </label></div>
         </div>', $class, $key, $item['label']);
          echo wp_kses($html, Utils::wpc_kses_allowed_tags());
          
          return;
       } elseif (isset($item['options']) && count($item['options'])) {
          $options = $item['options'];
          $input .= sprintf('<select name="%s" class="wpc-form-control wpc_select2 %s">', $key, $key, $class);
          foreach ($options as $option_key => $option) {
             if ($option_key == $value) {
                $input .= sprintf(' <option selected value="%s"> %s </option>',  $option_key, $option);
             } else {
                $input .= sprintf(' <option value="%s"> %s </option>',  $option_key, $option);
             }
          }
          $input .= sprintf('</select>');
       }

       // temporary purpose, will delete later
       $input = ( isset( $item['attr']['wpc_visual_selection'] ) && absint( $item['attr']['wpc_visual_selection'] ) == 1 ) ? $value : $input;

       $html = sprintf('
       <div class="%s"> 
          <div class="wpc-label"> 
             <label> %s  </label>
             <div class="wpc-desc">  %s  </div>
          </div>
           %s
      </div>', $class, $item['label'], $item['desc'], $input);
 
       echo wp_kses($html, Utils::wpc_kses_allowed_tags());
    }
 
    public function get_url_input($item, $key) {
 
       $value = '';
       $class = $key;
 
       if (isset($item['value'])) {
          $value = get_post_meta(get_the_ID(), $key, true);
       }
 
       if (isset($item['attr'])) {
          $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field ' : 'wpc_meta_field ';
       }
 
       $html = sprintf('<div class="%s"> 
       <div class="wpc-label"> <label for="%s"> %s : </label></div>
       <div class="wpcafe-meta">
                 <input class="wpc-form-control" type="%s" name="%s" id="%s" value="%s"/>
           </div></div>', $class, $key, $item['label'], $item['type'], $key, $key, $value);
 
       echo wp_kses($html, Utils::wpc_kses_allowed_tags());
    }
 
    public function get_upload($item, $key) {
 
       $class = $key;
       $value = get_post_meta(get_the_ID(), $key, true);
       $image = ' button">Upload image';
       $image_size = 'full';
       $display = 'none';
       $multiple = 0;
 
       if (isset($item['multiple']) && $item['multiple']) {
          $multiple = true;
       }
 
       if (isset($item['attr'])) {
 
          if (isset($item['attr']['class']) && $item['attr']['class'] != '') {
             $class = ' wpc_meta_field ' . $class . ' ' . $item['attr']['class'];
          } else {
             $class = ' wpc_meta_field ';
          }
       }
 
       if ($image_attributes = wp_get_attachment_image_src($value, $image_size)) {
 
          $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
          $display = 'inline-block';
       }
       ?>
       <div class='<?php echo esc_attr( $class ); ?>'>
       <div class="wpc-label"> <label><?php echo esc_html(  $item['label'] ); ?></label></div>
         <div class="wpcafe-meta">
         <a data-multiple="<?php echo esc_html( $multiple ); ?>" class="etn_event_upload_image_button<?php echo esc_html( $image ); ?></a>
               <input type="hidden" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" />
         <a href="#" class="wpc_remove_image_button" style="display:inline-block;display:<?php echo esc_attr( $display ); ?>"><?php echo esc_html__('Remove image', 'wpcafe'); ?></a>
         </div>
      </div>
    <?php
    }
 
    public function get_textarea($item, $key) {
       $rows = 14;
       $cols = 50;
       $value = '';
       $class = $key;
       if (isset($item['value'])) {
          $value = get_post_meta(get_the_ID(), $key, true);
       }
       if (isset($item['attr'])) {
          $rows = isset($item['attr']['row']) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
          $cols = isset($item['attr']['col']) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
          $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field ' : 'wpc_meta_field ';
       }
 
       $html = sprintf('<div class="%s form-group"><div class="wpc-label"><label for="%s"> %s : </label>
       <div class="wpcafe-desc">  %s  </div>
       </div> <div class="wpcafe-meta"><textarea class="wpc-form-control msg-control-box" id="%s" rows="%s" cols="%s" name="%s"> %s  </textarea></div> </div>', $class, $key, $item['label'], $item['desc'], $key, $rows, $cols, $key, $value);
 
       echo wp_kses($html, Utils::wpc_kses_allowed_tags()); 
    }
 
    public function get_wp_editor($item, $key) {
 
       $rows = 14;
       $cols = 50;
       $value = '';
       $class = $key;
 
       if (isset($item['settings']) && is_array($item['settings'])) {
          $settings = $item['settings'];
       }
 
       if (isset($item['value'])) {
          $value = get_post_meta(get_the_ID(), $key, true);
       }
 
       if (isset($item['attr'])) {
          $rows = isset($item['attr']['row']) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
          $cols = isset($item['attr']['col']) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
          $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field ' : 'wpc_meta_field ';
       }
       ?>
       <div class='<?php echo esc_attr( $class ); ?>'>
 
       <?php wp_editor($value, $key, $settings); ?>
 
       </div>
       <?php
    }

    public function get_checkbox_input($item, $key) {
      $value = '';
      $class = $key;
      $input = '';
    //   $value = get_post_meta(get_the_ID(), $key, true);
		// checked value
      $value = $item['checked_value'];
      if (isset($item['attr'])) {
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field ' : 'wpc_meta_field ';
      }

      if (!isset($item['options']) || !count($item['options'])) {
         $html = sprintf('<div class=" %s"> 
         <label for="%s"> %s : </label>
        </div>', $class, $key, $item['label']);

         echo wp_kses($html, Utils::wpc_kses_allowed_tags()); 
         return;
      } elseif (isset($item['options']) && count($item['options'])) {
         $options = $item['options'];
		$i=0;
        foreach ($options as $option_key => $option) {
			$i++;
            $checked =  in_array(str_replace(' ', '', $option ),$value) ? 'checked' : '';
            $input .= sprintf(' <input  %s type="%s" name="%s[]" class="" id=check_field_'.$item['row_key'].$i.' value="%s"/><label for=check_field_'.$item['row_key'].$i.'> %s  </label> ', $checked, $item['type'], $key, $option, $option);
        }
      }


      $html = sprintf('<div class="%s form-group"> <div class="wpc-label"><label for="%s"> %s  </label></div>
          %s
     </div>', $class, $key, $item['label'], $input);

      echo wp_kses($html, Utils::wpc_kses_allowed_tags()); 
   }

}

abstract class Wpc_Repeater_Metabox {

    protected function get_repeater_markup($item = null, $key = '', $data = [], $rep_key = '') {
        if (is_null($item)) {
            return;
        }

        if (isset($item['type'])) {

            switch ($item['type']) {
                case "text":
                    return $this->get_repeater_text_input($item, $key, $data);
                    break;
                case "date":
                    return $this->get_repeater_text_input($item, $key, $data);
                    break;
                case "email":
                    return $this->get_repeater_text_input($item, $key, $data);
                    break;
                case "time":
                    return $this->get_repeater_text_input($item, $key, $data);
                    break;
                case "url":
                    return $this->get_repeater_text_input($item, $key, $data);
                    break;
                case "textarea":
                    return $this->get_repeater_textarea($item, $key, $data);
                    break;
                case "select2":
                    return $this->get_repeater_select2($item, $key, $data);
                    break;
                case "radio":
                    return $this->get_repeater_radio($item, $key, $data);
                    break;
                case "upload":
                    return $this->get_repeater_upload($item, $key, $data);
                    break;
                case "heading":
                    return $this->get_heading($item, $key);
                    break;
                case "separator":
                    return $this->get_separator($item, $key);
                    break;
                case "select_single":
                    return $this->get_repeater_select_single($item, $key);
                    break;
                default:
                    return;
            }
        }

        return;
    }

    public function get_repeater_select_single($item, $key){
        $value = '';
        $class = $key;
        $input = '';
        $value = get_post_meta(get_the_ID(), $key, true);
        if (isset($item['attr'])) {
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
        }
        if (!isset($item['options']) || !count($item['options'])) {
            $html = sprintf('<div class="%s form-group"> 
         <div class="wpc-label"> <label for="%s"> %s : </label></div>
        </div>', $class, $key, $item['label']);
            echo wp_kses($html, Utils::wpc_kses_allowed_tags()); 
            return;
        } elseif (isset($item['options']) && count($item['options'])) {
            $options = $item['options'];
            $input .= sprintf('<select name="%s" class="wpc_select2 wpc-form-control %s">', $key, $key, $class);
            foreach ($options as $option_key => $option) {
                if ($option_key == $value) {
                    $input .= sprintf(' <option selected value="%s"> %s </option>',  $option_key, $option);
                } else {
                    $input .= sprintf(' <option value="%s"> %s </option>',  $option_key, $option);
                }
            }
            $input .= sprintf('</select>');
        }


        $html = sprintf('
      <div class="%s"> 
         <div class="wpc-label"> 
            <label> %s  </label>
         </div>
          %s
     </div>', $class, $item['label'], $input);

        echo wp_kses($html, Utils::wpc_kses_allowed_tags());
    }

    public function get_repeater_upload($item, $key, $data) {
        $class = $key;
        $value  = null;
        if (is_array($data) && count($data)) {
            $value = isset($data[$key]) ? $data[$key] : '';
        }

        $image = ' button">Upload image';
        $image_size = 'full';
        $display = 'none';
        $multiple = 0;

        if (isset($item['multiple']) && $item['multiple']) {
            $multiple = true;
        }

        if (isset($item['attr'])) {

            if (isset($item['attr']['class']) && $item['attr']['class'] != '') {
                $class = 'attr-form-control wpc_meta_field ' . $class . ' ' . $item['attr']['class'];
            } else {
                $class = 'wpc_meta_field attr-form-control';
            }
        }

        if ($image_attributes = wp_get_attachment_image_src($value, $image_size)) {

            $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
            $display = 'inline-block';
        }
        ?>
        <div class='<?php echo esc_attr( $class ); ?> form-group'>
            <label><?php echo esc_html( $item['label'] ); ?></label>
            <a data-multiple="<?php echo esc_html( $multiple ); ?>" class="etn_event_upload_image_button<?php echo esc_html( $image ); ?></a>
                   <input type="hidden" name="<?php echo esc_html( $key ); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" />
            <a href="#" class="wpc_remove_image_button" style="display:inline-block;display:<?php echo esc_attr( $display ); ?>"><?php echo esc_html__('Remove image', 'wpcafe'); ?></a>
        </div>
        <?php
    }

    public function get_repeater_text_input($item, $key, $data) {
        $value = $data;
        $value = isset($value[$key]) ? $value[$key] : '';
        $class = $key;

        if (isset($item['attr'])) {
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
        }

        $html = "<div class='wpcafe-label-item'>";
        $html .= sprintf('<div class="wpc-label"><label for="%s"> %s : </label></div><div class="wpcafe-meta"><input autocomplete="off" class="wpc-form-control %s" type="%s" name="%s"  value="%s" />', $key, $item['label'], $class, $item['type'], $key, $value);
        $html .= "</div></div>";

        return $html;
    }

    public function get_repeater_textarea($item, $key, $data) {
        $value = $data;
        $value = isset($value[$key]) ? $value[$key] : '';
        $class = $key;
        $rows = 14;
        $cols = 50;

        if (isset($item['attr'])) {
            $rows = isset($item['attr']['row']) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
            $cols = isset($item['attr']['col']) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field wpc-form-control' : 'wpc_meta_field form-control';
        }

        if (isset($item['attr'])) {
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
        }

        $html = sprintf('<div class="%s wpcafe-label-item"> <div class="wpc-label"><label for="%s"> %s : </label></div><div class="wpcafe-meta"> <textarea id="%s" rows="%s" cols="%s" class="wpc-form-control msg-control-box" name="%s"> %s  </textarea> </div></div>', $class, $key, $item['label'], $key, $rows, $cols, $key, $value);

        return $html;
    }

    public function get_repeater_radio($item, $key, $data) {
        $value = $data;
        $value = isset($value[$key]) ? $value[$key] : '';
        $class = $key;
        $input = '';

        if (isset($item['attr'])) {
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field attr-form-control' : 'wpc_meta_field attr-form-control';
        }

        if (!isset($item['options']) || !count($item['options'])) {

            $html = sprintf('<div class="attr-form-control %s"> 
       <label for="%s"> %s : </label>
       </div>', $class, $key, $item['label']);
            return $html;
        } elseif (isset($item['options']) && count($item['options'])) {

            $options = $item['options'];

            foreach ($options as $option_key => $option) {
                $checked =  $option_key == $value ? 'checked' : '';
                $input .= sprintf(' <input  %s type="%s" name="%s" value="%s"/><span> %s  </span> ', $checked, $item['type'], $key, $option_key, $option);
            }
        }

        $html = sprintf('<div class="%s form-group"> <label> %s  </label> %s </div>', $class, $item['label'], $input);

        return $html;
    }

    public function get_repeater_select2($item, $key, $data) {
        $input = '';
        $class = $key;
        $value = $data;
        $value = isset($value[$key]) ? $value[$key] : '';

        if (isset($item['attr'])) {
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field ' : 'wpc_meta_field form-control';
        }

        if (!isset($item['options']) || !count($item['options'])) {

            $html = sprintf('<div class="%s form-group"> 
          <label for="%s"> %s : </label>
      </div>', $class, $key, $item['label']);

            echo wp_kses($html, Utils::wpc_kses_allowed_tags());
            return;
        } elseif (isset($item['options']) && count($item['options'])) {

            $options = $item['options'];
            $input .= sprintf('<div class="wpcafe-meta"><select name="%s" class="wpc_repeater_select2 wpc-form-control">', $key, $key);
            foreach ($options as $option_key => $option) {
                if ($option_key == $value) {
                    $input .= sprintf(' <option %s selected value="%s"> %s </option>', $class, $option_key, $option);
                } else {
                    $input .= sprintf(' <option %s value="%s"> %s </option>', $class, $option_key, $option);
                }
            }
            $input .= sprintf('</select></div>');
        }

        $html = sprintf('<div class="%s wpcafe-label-item"> <div class="wpc-label"> <label> %s  </label> </div> %s </div>', $class, $item['label'], $input);
        return $html;
    }

}
