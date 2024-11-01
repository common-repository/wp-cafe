<?php
namespace WpCafe\Core\Modules\Guten_Block\Inc;

defined( "ABSPATH" ) || exit;

class Register_Blocks {
    
    use \WpCafe\Traits\Wpc_Singleton;

    /**
     *  Call admin function
     */
    function init() {

    	// register block category
    	global $wp_version;
		$filter_hook = "block_categories";
		if( version_compare($wp_version, '5.8') >= 0){
			$filter_hook = "block_categories_all";
		}
        add_filter( $filter_hook , [$this, 'wpc_block_category'], 10, 2);

        add_action( 'init', [$this, 'wpc_block_assets'] );

        $this->initialize_blocks();
    }

    public function wpc_block_category( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'wp-cafe-blocks',
					'title' => esc_html__( 'WP Cafe', 'wpcafe' ),
				),
			)
		);
	}

	//register block assets
	public function wpc_block_assets() {
	    global $wp_version;

	    if( version_compare($wp_version, '5.8') >= 0){
	        $wp_editor = [ 'wp-block-editor'];
	    } else{
	        $wp_editor = [ 'wp-editor'];
	    }

		// Register block editor styles for backend.
		wp_register_style(
				'wpc-block-editor-style-css',
				plugins_url( 'build/index.css', dirname( __FILE__ ) ),
				array( 'wp-edit-blocks' ),
				null
		);

		// Register block editor script for backend.
		wp_register_script(
			'wpc-block-js',
			plugins_url( '/build/index.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-compose', 'wp-server-side-render' ),
			null,
			true
		);
		register_block_type( \Wpcafe::plugin_dir() . 'core/modules/guten-block/build' );
	}

	public function initialize_blocks(){

		if ( ! class_exists( 'WooCommerce' ) ) { return; }
		
		\WpCafe\Core\Modules\Guten_Block\Inc\Blocks\Food_List::instance()->init();
		\WpCafe\Core\Modules\Guten_Block\Inc\Blocks\Food_Tab::instance()->init();
		\WpCafe\Core\Modules\Guten_Block\Inc\Blocks\Pickup_Delivery::instance()->init();
		\WpCafe\Core\Modules\Guten_Block\Inc\Blocks\Location::instance()->init();
	}

}