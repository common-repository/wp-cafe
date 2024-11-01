<?php

namespace WpCafe\Widgets\Wpc_Food_Location_Filter;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use \WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;

defined( "ABSPATH" ) || exit;

class Wpc_Food_Location_Filter extends Widget_Base {

    /**
     * Retrieve the widget name.
     * @return string Widget name.
     */
    public function get_name() {
        return 'wpc-food-location-filter';
    }

    /**
     * Retrieve the widget title.
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Location Filter', 'wpcafe' );
    }

    /**
     * Retrieve the widget icon.
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-menu-card';
    }

    /**
     * Retrieve the widget category.
     * @return string Widget category.
     */
    public function get_categories() {
        return ['wpcafe-menu'];
    }

    protected function register_controls () {
        $get_data = apply_filters( 'elementor/control/search_control' , false);

        // Start of event section
        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__( 'WPC Food Location', 'wpcafe' ),
            ]
        );

        $this->add_responsive_control(
            'location_alignment',
            [
                'label' => esc_html__( 'Alignment', 'wpcafe' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpcafe' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpcafe' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpcafe' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,

            ]
        );

        $this->end_controls_section();

        // advance style section
        $this->start_controls_section(
            'wpc_advance_style',
            [
                'label' => esc_html__( 'Advance Style', 'wpcafe' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'wpc_box_padding',
            [
                'label'      => esc_html__( 'Padding', 'wpcafe' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpc-food-menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        //check if woocommerce exists
        if (!class_exists('Woocommerce')) { return; }

        $settings             = $this->get_settings();
        $location_alignment = $settings["location_alignment"];

        echo do_shortcode( "[food_location_filter location_alignment='$location_alignment']" );

    }

}
