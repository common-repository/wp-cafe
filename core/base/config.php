<?php

namespace WpCafe\Core\Base;

use WpCafe\Utils\Wpc_Utilities as Utils;

defined( 'ABSPATH' ) || exit;

class Config extends Setting_field{
    /**
     * Call base class for generate markup
     */
    public function get_field_markup( $item = null, $key = '', $data = [] )
    {
        return $this->get_field_block(  $item , $key , $data );
    }
}

/**
 * This wrapper class is responsible for
 * Generate Settings option markup
 */
abstract class Setting_field{

    public function get_field_block(  $item = null, $key = '', $data = [] ){
        
        if (is_null($item)) {
            return;
        }
        
        if (!empty($item['type'])) {

            switch ($item['type']) {
                case "text":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "password":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "email":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "url":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "color":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "hidden":
                    return $this->get_text_input($item, $key, $data);
                    break;
                case "textarea":
                    return $this->get_text_area_input($item, $key, $data);
                    break;
                case "number":
                    return $this->get_number_input($item, $key, $data);
                    break;
                case "select_single":
                    return $this->get_select_single($item, $key, $data);
                    break;
                case "select2":
                    return $this->get_select2($item, $key, $data);
                    break;
                case "checkbox":
                    return $this->get_checkbox_input($item, $key,$data);
                    break;
                case "wp_editor":
                    return $this->get_wp_editor($item, $key,$data);
                    break;
                case "media":
                    return $this->get_media_input($item, $key,$data);
                    break;

                default:
                    return;
            }
        }
        
        return;
    }

    /**
     * Render text input field
     *
     * @param [array] $item
     * @param [string]$key
     * @param [array] $data
     * @return void
     */
    public function get_text_input( $item, $key , $data ) {
        $value = $data;
        $value = isset($value[$key]) ? $value[$key] : '';
        $class = $key;
        $input_class  = "";

        if (isset($item['attr'])) {
            $class          = !empty($item['attr']['class'])  ? $item['attr']['class'] : '';
            $input_class    = !empty($item['attr']['input_class'])  ? $item['attr']['input_class'] : '';
        }

        $label  = !empty($item['label']) ? $item['label'] : "";
        $desc   = !empty($item['desc']) ? $item['desc'] : "";
        $place_holder  = !empty($item['place_holder']) ? $item['place_holder'] : "";

        $span   = "";
        if ( !empty($item['span']) ) {
            $span_class = !empty($item['span']['class']) ? $item['span']['class'] : "";
            $span_id    = !empty($item['span']['id']) ? $item['span']['id'] : "";
            $span .="<span class={$span_class} id={$span_id}>{$item['span']['html']}</span>";
        }

        $disable_field  = "";
        if ( !empty($item['disable_field']) && $item['disable_field'] == true ) {
            $disable_field  = "disabled";
        }

        $eye_html ="";
        if ($item['type'] =="password") {
           $eye_html .='<div class="wpc_show_password">
            <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
           </div>';
        }

        ?>
            <div class="<?php echo esc_attr($class); ?>">
                <div class="wpc-label">
                    <label for="<?php echo esc_attr($key); ?>"> <?php echo esc_html($label); ?> </label>
                    <div class="wpc-desc"> 
			<?php 
			echo wp_kses( $desc, Utils::wpc_kses_allowed_tags() );
			?>  
		     </div>
                </div>
                <?php 
                if((isset($item['pro']) && !class_exists( 'Wpcafe_Pro' ))){ 
					echo wp_kses( Utils::get_pro(), Utils::wpc_kses_allowed_tags() );
                } else {
                    ?>
                    <div class="wpc-meta">
                        <input placeholder="<?php echo esc_attr($place_holder); ?>" class="<?php echo esc_attr($input_class); ?>" type="<?php echo esc_attr($item['type']); ?>" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" <?php echo esc_attr($disable_field); ?> />
                        <?php echo wp_kses( $span . $eye_html, Utils::wpc_kses_allowed_tags() ); ?>
                    </div>
                    <?php
                }
                ?>
            </div>

        <?php
    }

    /**
     * Render text input field
     *
     * @param [array] $item
     * @param [string]$key
     * @param [array] $data
     * @return void
     */
    public function get_text_area_input( $item, $key , $data ){
        $value  = $data;
        $rows   = 14;
        $cols   = 50;
        $class  = $key;
        $value  = isset($value[$key]) ? $value[$key] : '';

        if (isset($item['attr'])) {
           $rows = isset($item['attr']['row']) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
           $cols = isset($item['attr']['col']) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
           $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] : ' ';
        }
  
        $html = sprintf('<div class="%s form-group"><div class="wpc-label"><label for="%s"> %s  </label>
        <div class="wpc-desc">  %s  </div>
        </div> <div class="wpc-meta"><textarea class="wpc-form-control wpc-msg-box" id="%s" rows="%s" cols="%s" name="%s">%s</textarea></div> </div>', $class, $key, $item['label'], $item['desc'], $key, $rows, $cols, $key, $value);
  
	echo wp_kses( $html, Utils::wpc_kses_allowed_tags() );

    }

    /**
     * Render number input field
     *
     * @param [array] $item
     * @param [string] $key
     * @param [array] $data
     * @return void
     */
    public function get_number_input( $item, $key, $data ) {

        $value  = $data;
        $value  = isset($value[$key]) ? $value[$key] : '';
  
        $step   = isset( $item['step'] ) ? $item['step'] : "1";
        $min    = isset( $item['min'] ) ? $item['min'] : "0";
        $max    = isset( $item['max'] ) ? $item['max'] : '';
        $style  ="";

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
        }

        if ( !empty( $item['style'] ) ) {
            $base_attr = !empty($item['style']['attr']) ? $item['style']['attr'] : "";
            $style    .= " style='{$base_attr}'";
        }

        $html = sprintf(
                    '<div class="%s" '.$style.'>
                        <div class="wpc-label">
                            <label for="%s"> %s  </label>
                            <div class="wpc-desc">%s</div>
                        </div>
                        <div class="wpc-meta">
                            <input autocomplete="off" class="wpc-form-control" type="%s" name="%s" id="%s" value="%s" step="%s" min="%s" max="%s"/>
                        </div>
                    </div>', $class, $key, $item['label'], $item['desc'], $item['type'], $key, $key, $value, $step, $min, $max );

		echo wp_kses( $html, Utils::wpc_kses_allowed_tags() );   
    }

    /**
     * Render dropdown select option input
     */
    public function get_select_single( $item, $key, $data ) {
        $value = $data;
        $class = $key;
        $input = '';
        $value = !empty($value[$key]) ? $value[$key] : '';
        $input_class = '';
        $style = '';

        if (isset($item['attr'])) {
            $input_class = !empty($item['attr']['input_class'])  ? $item['attr']['input_class'] : '';
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
            $style = !empty($item['attr']['style']) ? $item['attr']['style'] : "";
        }


        $div_wrap   = "";
        if ( !empty($item['span']) ) {
            $span_class = !empty($item['span']['class']) ? $item['span']['class'] : "";
            $span_id    = !empty($item['span']['id']) ? $item['span']['id'] : "";
            $div_wrap .="<div class={$span_class} id={$span_id}>{$item['span']['html']}</div>";
        }

        $disable_field  = "";
        if ( !empty($item['disable_field']) && $item['disable_field'] == true ) {
            $disable_field  = "disabled";
        }

        if (!isset($item['options']) || !count($item['options'])) {
            $html = sprintf('<div class="%s form-group"> 
            <div class="wpc-label"> <label for="%s"> %s </label></div>
            </div>', $class, $key, $item['label']);
	    echo wp_kses( $html, Utils::wpc_kses_allowed_tags() );
            
            return;
        } elseif (isset($item['options']) && count($item['options'])) {
            $options = $item['options'];
            $input .= sprintf('<select id="%s" %s name="%s" class="wpc-form-control wpc_select2 %s">',
            $key , $disable_field , $key , $input_class);

            foreach ($options as $option_key => $option) {
                if ($option_key == $value) {
                    $input .= sprintf('<option selected value="%s"> %s </option>',  $option_key, $option);
                } else {
                    $input .= sprintf(' <option value="%s"> %s </option>',  $option_key, $option);
                }
            }
            $input .= sprintf('</select>');
        }
        
        ?>
        <div class="<?php echo esc_attr($class); ?>" style="<?php echo esc_attr($style); ?>"> 
            <div class="wpc-label"> 
                    <strong> 
                        <?php echo esc_html($item['label']); ?>
                    </strong>
                    <div class="wpc-desc">
                        <?php echo esc_html( $item['desc'] ); ?> 
                    </div>
            </div>
            <?php 
                if((isset($item['pro']) && !class_exists( 'Wpcafe_Pro' ))){ 
                    echo wp_kses( Utils::get_pro(), Utils::wpc_kses_allowed_tags() );
                } else {
                    ?>
                    <div class="wpc-meta">
                        <?php echo wp_kses( $input, Utils::wpc_kses_allowed_tags() ); ?>
                        <?php echo wp_kses( $div_wrap, Utils::wpc_kses_allowed_tags() ); ?>
                    </div>
                    <?php
                }
            ?>
            
        </div>
        <?php
    }

    /**
     * Multiple select option
     *
     * @param [array] $item
     * @param [string]$key
     * @return void
     */
    public function get_select2( $item, $key , $data ) {
        $value = $data;
        $class = $key;
        $input = '';
        $value = !empty($value[$key]) ? $value[$key] : [];

        if (isset($item['attr'])) {
           $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' wpc_meta_field' : 'wpc_meta_field';
        }
        if (!isset($item['options']) || !count($item['options'])) {
           $html = sprintf('<div class="%s form-group"> 
           <div class="wpc-label"> <label for="%s"> %s : </label></div>
          </div>', $class, $key, $item['label'] );
	   echo wp_kses( $html, Utils::wpc_kses_allowed_tags() );
           return;
        } elseif (isset($item['options']) && count($item['options'])) {
           $options = $item['options'];
           $input .= sprintf('<select multiple name="%s[]" id="%s[]" class="wpc-form-control wpc_select2 %s">', $key, $key, $key, $class);
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
              <label for="%s[]"> %s  </label>
              <div class="wpc-desc">%s</div>
           </div>
           <div class="wpc-meta">
            %s
       </div></div>', $class, $key, $item['label'], $item['desc'] , $input);
  
       echo wp_kses( $html, Utils::wpc_kses_allowed_tags() );
    }

    /**
     * Checkbox input
     *
     * @param [array] $item
     * @param [string]$key
     * @return void
     */
    public function get_checkbox_input( $item, $key , $data ) {

        $value = $data;
        $class = $key;
        $input = '';
        $value = !empty($value[$key]) ? $value[$key] : '';
        $input_class = "";
        $data_text = "";
        $data_text_alt = "";

        if (isset($item['attr'])) {
           $class           = !empty($item['attr']['class'])  ? $item['attr']['class']  : '';
           $input_class     = !empty($item['attr']['input_class']) ? $item['attr']['input_class']: '';
           $data_text       = !empty($item['attr']['data_text'])  ? $item['attr']['data_text'] : '';
           $data_text_alt   = !empty($item['attr']['data_text_alt'])  ? $item['attr']['data_text_alt'] : '';
        }

        $disable_field  = "";
        if ( !empty($item['disable_field']) && $item['disable_field'] == true ) {
            $disable_field  = "disabled";
        }

        if (!isset($item['options']) || !count($item['options'])) {
           $html = sprintf('<div class="%s"> 
           <label for="%s"> %s </label>
          
          </div>', $class, $key, $item['label']);
  
	   echo wp_kses( $html, Utils::wpc_kses_allowed_tags() );
           return;
        } elseif (isset($item['options']) && count($item['options'])) {
           $options = $item['options'];
           $get_data = $value =="checked" ? 'on' : $value;
           $input .= '<div class="wpc-meta">';
           foreach ($options as $option_key => $option) {
               if ( count($options) > 1 ) {
                    $hide_class =  $option == "off" ? "hide_field" : "";
                    $input .= sprintf('<input '. esc_attr($get_data == $option ? 'checked' : ''  ).'  value="%s" type="%s" 
                    name="%s" class="%s  %s" '. esc_attr( $option == "on"? 'id=%s' : ''  ).' />
                    ', $option , $item['type'], $key , $hide_class , $input_class, $key );

               } else {
                $checked =  $get_data =="on" ? 'checked' : '';
                $input .= sprintf('
                <input  %s type="%s" '. $disable_field .' name="%s" class="%s" id="%s" />
                 ', $checked, $item['type'], $key , $input_class, $key );
               }
           }
           
           $input .= sprintf('<label for="%s" class="wpcafe_switch_button_label" data-text="%s" data-textalt="%s" ></label>
            ', $key , $data_text , $data_text_alt );

           $input .= '</div>';
           
        }
        ?>
        <div class="<?php echo esc_attr($class); ?>"> 
            <div class="wpc-label">
                <?php if((isset($item['pro']) && !class_exists( 'Wpcafe_Pro' ))):  ?>
                    <strong> <?php echo esc_html($item['label']); ?> </strong>
                    <div class="wpc-desc"><?php echo esc_html($item['desc']); ?></div>
                <?php else : ?>
                    <label for="<?php echo esc_attr($key); ?>"> <?php echo esc_html($item['label']); ?> </label>
                    <div class="wpc-desc"><?php echo esc_html($item['desc']); ?></div>
                <?php endif; ?>
            </div>
            <?php 
                if((isset($item['pro']) && !class_exists( 'Wpcafe_Pro' ))){ 
                    echo wp_kses( Utils::get_pro(), Utils::wpc_kses_allowed_tags() );
                } else {
					echo wp_kses( $input, Utils::wpc_kses_allowed_tags() );
                }
            ?>
        </div>
        <?php
    }

    /**
     * Render wp editor
     *
     * @param [array] $item
     * @param [string]$key
     * @param [array] $data
     * @return void
     */
    public function get_wp_editor( $item, $key , $data ) {
        $value = $data;
        $class = $key;
        $input = "";

        $value = !empty($value[$key]) ? $value[$key] : '';

        if (isset($item['settings']) && is_array($item['settings'])) {
           $settings = $item['settings'];
        }
  
        if (isset($item['attr'])) {
           $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] : '';
        }

        ?>
        <div class="<?php echo esc_attr($class); ?>"> 
            <div class="wpc-label">
                <label  for="<?php echo esc_attr($key); ?>">
                    <?php echo esc_attr($item['label']); ?> 
                </label>
                <div class="wpc-desc"><?php echo esc_attr($item['desc']); ?></div>
            </div>
            <?php 
                if((isset($item['pro']) && !class_exists( 'Wpcafe_Pro' ))){ 
					echo wp_kses( Utils::get_pro(), Utils::wpc_kses_allowed_tags() );
                } else {
                    ?>
                    <div class='wpc-meta'>
                        <?php wp_editor($value, $key, $settings); ?>
                    </div>
                    <?php
                }
            ?>
        </div>
         <?php

    }

    
    /**
     * Render audio input
     *
     * @param [array] $item
     * @param [string]$key
     * @param [array] $data
     * @return void
     */
    public function get_media_input( $item, $key , $data ){
        $value  = $data;
        $style  = '';
        $cols   = 50;
        $class  = $key;
        $value  = !empty($value[$key]) ? $value[$key] : '';
        $input_name = !empty($item['input_name']) ? $item['input_name'] : "audio";
        $input_data = "";

        if (isset($item['attr'])) {
           $style         = !empty($item['attr']['style']) ? $item['attr']['style'] : "";
           $input_class   = !empty($item['attr']['input_class'])  ? $item['attr']['input_class'] : '';
           $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] : ' ';
        }

        if( $value !=="" ){
            $input_data .=sprintf('<div id="sound-media-wrapper">
            <audio class="%s" id="%s"  name="%s" controls>
                <source src="'.wp_get_attachment_url( $value ).'" type="audio/mpeg">
            </audio>
            </audio> </div>',$key,$key,$key);
        }else {
            $input_data .="<div id='sound-media-wrapper'></div>";
        }

        $html = sprintf('<div class="wpc-label-item %s" style="%s"><div class="wpc-label"><strong for="%s"> %s  </strong>
        <div class="wpc-desc">  %s  </div></div>
        <div class="wpc-meta">
            <input type="hidden" id="'.$key.'" name="'.$key.'" class="custom_media_url" value="'.$value.'">
            %s
            <p>
                <input type="button" class="button button-secondary sound_media_button wpc-btn-text wpc-btn-upload" id="sound_media_button" name="sound_media_button" value="'.esc_attr__("Add Media File", "wpcafe").'" />
                <input type="button" class="button button-secondary sound_media_remove wpc-btn-text wpc-btn-upload" id="sound_media_remove" name="sound_media_remove" value="'.esc_attr__("Remove Media File", "wpcafe").'" />
            </p>
        </div></div>'
        ,
         $class, $style , $key, $item['label'], $item['desc'],$input_data);
  
	echo wp_kses( $html, Utils::wpc_kses_allowed_tags() ) ;
    }
}




