<?php 

namespace WpCafe\Core\Base;

defined( 'ABSPATH' ) || exit;

class Api_Handler{

    public $prefix = '';
    public $param = '';
    public $request = null;

    public function __construct(){
        $this->config();
        $this->init();
    }

    public function config(){

    }

    public function init(){
        add_action( 'rest_api_init', function () {
            register_rest_route( untrailingslashit( 'wpc/' . $this->prefix), '/(?P<action>\w+)/' . ltrim($this->param, '/'), array(
                'methods'   => \WP_REST_Server::ALLMETHODS,
                'callback'  => [$this, 'callback'],
                'permission_callback' => '__return_true', 
                // all permissions are implemented inside the callback action
            ));
        });
    }

    public function callback($request){
        $this->request = $request;

        if ( $this->request->get_params()['action'] == 'table_mapping'  ) {
            $this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
        }

        $action_class = strtolower($this->request->get_method()) .'_'. $this->request['action'];

        if(method_exists($this, $action_class)){
            return $this->{$action_class}();
        }
    }

}