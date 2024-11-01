<?php
namespace WpCafe\Core\Modules\Guten_Block\Inc\Blocks;

defined( "ABSPATH" ) || exit;

use WpCafe\Utils\Wpc_Utilities;

class Food_Tab {
    
    use \WpCafe\Traits\Wpc_Singleton;

    function init() {
        add_action('init', [$this, 'register_food_tab_block']);
    }

	function register_food_tab_block(){
		register_block_type(
			'wpc/food-menu-tab', array(
				// Enqueue blocks.style.build.css on both frontend & backend.
				'style'         => 'wpc-block-style-css',
				// Enqueue blocks.editor.build.css in the editor only.
				'editor_style'  => 'wpc-block-editor-style-css',
				// Enqueue blocks.build.js in the editor only.
				'editor_script' => 'wpc-block-js',
				'render_callback'	=> [$this, 'wpc_food_menu_tab_callback'],
				'api_version' => 1,
				'attributes' => array(
					'food_menu_style' => array(
						'type' => 'string',
						'default'	=> 'style-1'
					),
					'wpc_food_categories' => array(
						'type' => 'array',
						'default'	=> []
					),
					'show_thumbnail' => array(
						'type' => 'string',
						'default'	=> 'yes'
					),
					'wpc_desc_limit' => array(
						'type' => 'integer',
						'default'	=> 20
					),
					'wpc_show_desc' => array(
						'type' => 'string',
						'default'	=> 'yes'
					),
					'wpc_cart_button' => array(
						'type' => 'string',
						'default'	=> 'yes'
					),
					'title_link_show' => array(
						'type' => 'string',
						'default'	=> 'yes'
					),
					'show_item_status' => array(
						'type' => 'string',
						'default'	=> 'yes'
					),
					'wpc_price_show' => array(
						'type' => 'string',
						'default'	=> 'yes'
					),
					'wpc_menu_count' => array(
						'type' => 'integer',
						'default'	=> 20
					),
					'wpc_menu_order' => array(
						'type' => 'string',
						'default' => 'DESC'
					),
					'wpc_show_vendor' => array(
						'type' => 'string',
						'default'	=> 'yes'
					)
				)
			)
		);
	}

	// food menu tab block callback
	function wpc_food_menu_tab_callback($settings){
		//check if woocommerce exists
		if (!class_exists('Woocommerce')) { return; }

		$unique_id = md5(md5(microtime()));

		$style = $settings['food_menu_style'];
		$wpc_menu_order = $settings['wpc_menu_order'];
		$wpc_desc_limit = $settings['wpc_desc_limit'];
		$wpc_cart_button = $settings['wpc_cart_button'];
		$show_item_status = $settings['show_item_status'];
		$wpc_price_show = $settings["wpc_price_show"];
		$wpc_show_vendor    = $settings["wpc_show_vendor"];

		$wpc_cat_arr  = $settings['wpc_food_categories'];
		
		$food_menu_tabs = [];
		if (count($wpc_cat_arr) > 0) {
			foreach ($wpc_cat_arr as $key => $value) {
				if ($wpc_cat = get_term_by('id', $value, 'product_cat')) {
					$wpc_get_menu_order = get_term_meta($wpc_cat->term_id, 'wpc_menu_order_priority', true);
					$wpc_cat    = get_term_by('id', $value, 'product_cat');
					$cat_name   = ($wpc_cat && $wpc_cat->name ) ? $wpc_cat->name : "";
					$tab_data   = array('post_cats'=>[$value],'tab_title' => $cat_name);
					if ($wpc_get_menu_order == '') {
						$food_menu_tabs[$key] = $tab_data;
					} else {
						$food_menu_tabs[$wpc_get_menu_order] = $tab_data;
					}
				}
			}
		}else{
			return "<p>Select at least one category.</p>";
		}

		if( is_array( $food_menu_tabs ) && count( $food_menu_tabs )>0 ){
			$wpc_menu_count = is_array($settings) && isset($settings['wpc_menu_count']) ? $settings['wpc_menu_count'] : 5;
			$wpc_show_desc  = is_array($settings) && isset($settings['wpc_show_desc']) ? $settings['wpc_show_desc'] : 'yes';
			$show_thumbnail = is_array($settings) && isset($settings['show_thumbnail']) ? $settings['show_thumbnail'] : 'yes';
			$title_link_show= is_array($settings) && isset($settings['title_link_show']) ? $settings['title_link_show'] : 'yes';
			$class = ($title_link_show=='yes')? '' : 'wpc-no-link';

			$allowed_file_names = [
				'style-1',
			    'style-2'
			];
		
			if( in_array($style, $allowed_file_names)){
				$template_file = esc_html($style);
			}else{
				$template_file = $allowed_file_names[0];
			}

			ob_start();
			?>
			<div class="wpc-food-tab-wrapper wpc-nav-shortcode main_wrapper_<?php echo esc_html($unique_id)?>" data-id="<?php echo esc_attr($unique_id)?>">
				<ul class="wpc-nav">
					<?php
					if( is_array( $food_menu_tabs ) && count( $food_menu_tabs )>0 ){
						foreach ($food_menu_tabs as $tab_key => $value) {
							$active_class = (($tab_key == array_keys($food_menu_tabs)[0]) ? 'wpc-active' : ' ');
							$cat_id       = isset($value['post_cats'][0] ) ? intval( $value['post_cats'][0] ) : 0 ;
							?>
							<li>
								<a href='#' class='wpc-tab-a <?php echo esc_attr($active_class); ?>' data-id='tab_<?php echo intval($tab_key); ?>'
								   data-cat_id='<?php echo esc_attr( $cat_id ); ?>'>
									<span><?php echo esc_html($value['tab_title']); ?></span>
								</a>
							</li>
							<?php
						}
					}
					?>
				</ul>
				<div class="wpc-tab-content wpc-widget-wrapper">
					<?php
					foreach ($food_menu_tabs as $content_key => $value) {
						if(isset( $value['post_cats'][0] )){
							$active_class = (($content_key == array_keys($food_menu_tabs)[0]) ? 'tab-active' : ' ');
							$cat_id = isset($value['post_cats'][0] ) ? intval( $value['post_cats'][0] ) : 0 ;
							?>
							<div class='wpc-tab <?php echo esc_attr($active_class); ?>' data-id='tab_<?php echo intval($content_key); ?>'
								 data-cat_id='<?php echo  esc_attr($cat_id);?>'>
								<div class="tab_template_<?php echo esc_attr( $cat_id.'_'.$unique_id );?>"></div>
								<div class="template_data_<?php echo esc_attr( $cat_id.'_'.$unique_id );?>">
									<?php
	                                $food_tab_args = array(
	                                    'post_type'     => 'product',
	                                    'no_of_product' => $wpc_menu_count,
	                                    'wpc_cat'       => $value['post_cats'],
	                                    'order'         => $wpc_menu_order,
	                                );
									$products = Wpc_Utilities::product_query( $food_tab_args );
									include \Wpcafe::plugin_dir() . "widgets/wpc-food-menu-tab/style/{$template_file}.php";
									?>
								</div>
							</div><!-- Tab pane 1 end -->
							<?php
						}
					}
					?>
				</div><!-- Tab content-->
			</div>
			<?php
		}
		return ob_get_clean();

	}

}
