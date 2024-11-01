<?php

namespace WpCafe\Widgets\Wpc_Food_Menu_Tab;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use \WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;

defined( "ABSPATH" ) || exit;

class Wpc_Food_Menu_Tab extends Widget_Base {

    /**
     * Retrieve the widget name.
     * @return string Widget name.
     */
    public function get_name() {
        return 'wpc-menu-tab';
    }

    /**
     * Retrieve the widget title.
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'WPC Food Menu Tab', 'wpcafe' );
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

    protected function register_controls() {
        $get_data = apply_filters( 'elementor/control/search_control' , false);

        // Start of event section
        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__( 'WPC Food Menu Tab', 'wpcafe' ),
            ]
        );

        $this->add_control(
            'food_tab_menu_style',
            [
                'label'   => esc_html__( 'Menu tab Style', 'wpcafe' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                    'style-1' => esc_html__( 'Menu Style 1', 'wpcafe' ),
                    'style-2' => esc_html__( 'Menu Style 2', 'wpcafe' ),
                ],
            ]
        );

        $repeater = new \Elementor\Repeater();

	    $repeater->add_control(
			'post_cats', [
                'label'       => esc_html__( 'Select Categories', 'wpcafe' ),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_menu_category(),
                'label_block' => true,
                'multiple'    => true,
			]
        );
	    $repeater->add_control(
			'tab_title', [
                'label'   => esc_html__( 'Tab title', 'wpcafe' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Add Label',
			]
        );

        $this->add_control(
			'food_menu_tabs',
			[
				'label' => esc_html__( 'Repeater List', 'wpcafe' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( 'Add Label', 'wpcafe' ),
					],
				
				],
				'title_field' => '{{{ tab_title }}}',
			]
        );
        

        $this->add_control(
            'wpc_menu_count',
            [
                'label'   => esc_html__( 'Menu count', 'wpcafe' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '6',
            ]
        );
        $this->add_control(
            'wpc_menu_order',
            [
                'label'   => esc_html__( 'Menu Order', 'wpcafe' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC'  => esc_html__( 'ASC', 'wpcafe' ),
                    'DESC' => esc_html__( 'DESC', 'wpcafe' ),
                ],
            ]
        );
        $this->add_control(
            'show_thumbnail',
            [
                'label'        => esc_html__( 'Show Thumbnail', 'wpcafe' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'wpcafe' ),
                'label_off'    => esc_html__( 'Hide', 'wpcafe' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_item_status',
            [
                'label'        => esc_html__( 'Show Item Status', 'wpcafe' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'wpcafe' ),
                'label_off'    => esc_html__( 'Hide', 'wpcafe' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'wpc_show_desc',
            [
                'label'        => esc_html__( 'Show Description', 'wpcafe' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'wpcafe' ),
                'label_off'    => esc_html__( 'Hide', 'wpcafe' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'wpc_desc_limit',
            [
                'label'     => esc_html__( 'Description Limit', 'wpcafe' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '15',
                'condition' => ['wpc_show_desc' => 'yes'],
            ]
        );

        $this->add_control(
            'title_link_show',
            [
                'label'        => esc_html__( 'Use Title Link?', 'wpcafe' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'wpcafe' ),
                'label_off'    => esc_html__( 'Hide', 'wpcafe' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'wpc_cart_button_show',
            [
                'label'        => esc_html__( 'Show add to cart button', 'wpcafe' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'wpcafe' ),
                'label_off'    => esc_html__( 'Hide', 'wpcafe' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        
        $this->add_control(
            'wpc_price_show',
            [
                'label'   => esc_html__( 'Show Price', 'wpcafe' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'yes',
                'options' => [
                    'yes'  => esc_html__( 'Show', 'wpcafe' ),
                    'no'  => esc_html__( 'Hide', 'wpcafe' ),
                    'min'   => esc_html__( 'Min Price (For Variation)', 'wpcafe' ),
                    'max'   => esc_html__( 'Max Price (For Variation)', 'wpcafe' ),
                ],
            ]
        );

        if(class_exists('Wpcafe_Multivendor')) { 
            $this->add_control(
                'wpc_show_vendor',
                [
                    'label'        => esc_html__( 'Show Vendor', 'wpcafe' ),
                    'type'         => \Elementor\Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'wpcafe' ),
                    'label_off'    => esc_html__( 'Hide', 'wpcafe' ),
                    'return_value' => 'yes',
                    'default'      => 'no',
                ]
            );
        }

        if( is_array( $get_data ) && count( $get_data )>0 && isset( $get_data['search_control'] ) ){
            $this->add_control( $get_data['search_control']['name'], $get_data['search_control']['parameter']);
        }

        $this->end_controls_section();
        // item status style section
        $this->start_controls_section(
            'item_status_style',
            [
                'label'     => esc_html__( 'Item Status Style', 'wpcafe' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_item_status' => 'yes'],
            ]
        );
        $this->add_control(
            'wpc_menu_item_status_color',
            [
                'label'     => esc_html__( 'Item Status Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-menu-tag li' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'wpc_menu_item_status_bg_color',
            [
                'label'     => esc_html__( 'Item Status BG Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-menu-tag li' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'wpc_menu_status_typo',
                'label'    => esc_html__( 'Typography', 'wpcafe' ),
                'selector' => '{{WRAPPER}} .wpc-menu-tag li',
            ]
        );
        $this->add_responsive_control(
            'wpc_menu_item_status_paddding',
            [
                'label'      => esc_html__( 'Padding', 'wpcafe' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpc-menu-tag li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // item cart button style section
        $this->start_controls_section(
            'item_cart_button_style',
            [
                'label'     => esc_html__( 'Cart Button Style', 'wpcafe' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['wpc_cart_button_show' => 'yes'],
            ]
        );
        $this->add_control(
            'wpc_cart_color',
            [
                'label'     => esc_html__( 'Cart Button Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'wpc_cart_button_bg_color',
            [
                'label'     => esc_html__( 'Cart Button BG Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'wpc_cart_button_typo',
                'label'    => esc_html__( 'Typography', 'wpcafe' ),
                'selector' => '{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a i',
            ]
        );
        $this->add_responsive_control(
            'cart_btn_width',
            [
                'label'      => esc_html__( 'Width', 'wpcafe' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 35,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cart_btn_height',
            [
                'label'      => esc_html__( 'Height', 'wpcafe' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 35,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wpc_cart_btn_paddding',
            [
                'label'      => esc_html__( 'Padding', 'wpcafe' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Start of nav section
        $this->start_controls_section(
            'nav_style',
            [
                'label' => esc_html__( 'Nav style', 'wpcafe' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'nav_align',
            [
                'label'     => esc_html__( 'Alignment', 'wpcafe' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [

                    'left'    => [
                        'title' => esc_html__( 'Left', 'wpcafe' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__( 'Center', 'wpcafe' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'   => [
                        'title' => esc_html__( 'Right', 'wpcafe' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'wpcafe' ),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}}  .wpc-nav' => 'text-align: {{VALUE}};',
                ],
            ]
        );
		//Responsive control end

        //control for nav typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'wpc_nav_typography',
                'label'    => esc_html__( 'Nav Title Typography', 'wpcafe' ),
                'selector' => '{{WRAPPER}} .wpc-nav li a',
            ]
        );

        //start of nav color tabs (normal and hover)
        $this->start_controls_tabs(
            'wpc_nav_tabs'
        );

        //start of nav normal color tab
        $this->start_controls_tab(
            'wpc_nav_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wpcafe' ),
            ]
        );

        $this->add_control(
            'wpc_nav_color',
            [
                'label'     => esc_html__( 'Nav Title Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-nav li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'nav_border',
                'label'    => esc_html__( 'Border', 'wpcafe' ),
                'selector' => '{{WRAPPER}} .wpc-nav li a',
            ]
        );

        $this->end_controls_tab();

		//end of nav normal color tab

        //start of nav active color tab
        $this->start_controls_tab(
            'wpc_nav_active_tab',
            [
                'label' => esc_html__( 'Active', 'wpcafe' ),
            ]
        );
        $this->add_control(
            'wpc_nav_active_color',
            [
                'label'     => esc_html__( 'Nav active color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-nav li a.wpc-active' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wpc-nav li a:after'      => 'border-color: {{VALUE}} transparent transparent transparent;',
                ],
            ]
        );

        $this->add_control(
            'wpc_nav_angle_active_color',
            [
                'label'     => esc_html__( 'Nav Angle Active color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-nav li a:after' => 'border-color: {{VALUE}}  transparent transparent transparent;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'nav_border_active',
                'label'    => esc_html__( 'Border active', 'wpcafe' ),
                'selector' => '{{WRAPPER}} .wpc-nav li a.wpc-active',
            ]
        );
        $this->end_controls_tab();
        //end of nav hover color tab

        $this->end_controls_tabs();
        //end of nav color tabs (normal and hover)

        $this->end_controls_section();
		// End of nav section

        // title style section
        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__( 'Title Style', 'wpcafe' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'wpc_menu_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-post-title a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'wpc_menu_title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-post-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'wpc_menu_title_bg_color',
            [
                'label'     => esc_html__( 'Title BG Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-post-title a' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'wpc_menu_price_bg_color',
            [
                'label'     => esc_html__( 'Price BG Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-menu-price span .wpc-menu-price del .wpc-food-menu-item .wpc-food-inner-content .wpc-menu-currency' => 'background: {{VALUE}};',
                ],
            ]
        );
        //control for title typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'wpc_menu_title',
                'label'    => esc_html__( 'Title Typography', 'wpcafe' ),
                'selector' => '{{WRAPPER}} .wpc-post-title',
            ]
        );
        $this->add_responsive_control(
            'wpc_title_margin',
            [
                'label'      => esc_html__( 'Title Margin', 'wpcafe' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpc-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // price style section
        $this->start_controls_section(
            'price_style',
            [
                'label' => esc_html__( 'Price Style', 'wpcafe' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'wpc_menu_price_color',
            [
                'label'     => esc_html__( 'Price Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-menu-price' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wpc-menu-currency' => 'color: {{VALUE}};',
                ],
            ]
        );

        //control for title typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'wpc_menu_price',
                'label'    => esc_html__( 'Price Typography', 'wpcafe' ),
                'selector' => '{{WRAPPER}} .wpc-menu-price, {{WRAPPER}} .wpc-menu-currency',
            ]
        );

        $this->end_controls_section();

        // description style section
        $this->start_controls_section(
            'wpc_desc_style',
            [
                'label' => esc_html__( 'Description Style', 'wpcafe' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'wpc_menu_desc_color',
            [
                'label'     => esc_html__( 'Description Color', 'wpcafe' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpc-food-inner-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        //control for title typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'wpc_menu_desc',
                'label'    => esc_html__( 'Description Typography', 'wpcafe' ),
                'selector' => '{{WRAPPER}} .wpc-food-inner-content p',
            ]
        );

        $this->add_responsive_control(
            'wpc_desc_margin',
            [
                'label'      => esc_html__( 'Description Margin', 'wpcafe' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpc-food-inner-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
            'wpc_box_margin',
            [
                'label'      => esc_html__( 'Margin', 'wpcafe' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpc-food-menu-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
        $style                = $settings["food_tab_menu_style"];

        $food_menu_tabs       = $settings["food_menu_tabs"];
        $show_item_status     = $settings["show_item_status"];
        $wpc_cart_button      = $settings["wpc_cart_button_show"];
        $wpc_price_show       = $settings["wpc_price_show"];

        $wpc_desc_limit       = $settings["wpc_desc_limit"];
        $wpc_menu_order       = $settings["wpc_menu_order"];
        $title_link_show      = $settings["title_link_show"];
        $unique_id            = $this->get_id();

        include \Wpcafe::core_dir() ."shortcodes/views/food-menu/food-tab.php";
    }

    protected function get_menu_category() {
        return Wpc_Utilities::get_menu_category();
    }

}
