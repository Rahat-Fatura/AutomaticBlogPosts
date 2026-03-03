<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_icon',
        'title' => esc_html__('BR Icons', 'cyberguard'),
        'icon' => 'eicon-alert',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'cyberguard'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'icons',
                            'label' => esc_html__('Icons', 'cyberguard'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'pxl_icon',
                                    'label' => esc_html__('Icon', 'cyberguard' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                ),
                                array(
                                    'name' => 'icon_link',
                                    'label' => esc_html__('Link', 'cyberguard'),
                                    'type' => \Elementor\Controls_Manager::URL,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'color_item',
                                    'label' => esc_html__( 'Color', 'cyberguard' ),
                                    'type' => \Elementor\Controls_Manager::COLOR,
                                    'default' => '',
                                    'selectors' => [
                                        '{{WRAPPER}} .pxl-icon-list {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                                    ],
                                ),
                                array(
                                    'name' => 'color_item_hover',
                                    'label' => esc_html__( 'Color Hover', 'cyberguard' ),
                                    'type' => \Elementor\Controls_Manager::COLOR,
                                    'default' => '',
                                    'selectors' => [
                                        '{{WRAPPER}} .pxl-icon-list {{CURRENT_ITEM}}:hover' => 'color: {{VALUE}};',
                                    ],
                                ),
                            ),
                        ),
                        array(
                          'name' => 'align',
                            'label' => esc_html__( 'Alignment', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'control_type' => 'responsive',
                            'options' => [
                                'left' => [
                                    'title' => esc_html__( 'Left', 'cyberguard' ),
                                    'icon' => 'eicon-text-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__( 'Center', 'cyberguard' ),
                                    'icon' => 'eicon-text-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__( 'Right', 'cyberguard' ),
                                    'icon' => 'eicon-text-align-right',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list' => 'text-align: {{VALUE}};',
                            ],
                        ),
                    ),
                ),
                
                array(
                    'name' => 'section_style',
                    'label' => esc_html__('Style', 'cyberguard'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-default' => 'Default',
                                'style2' => 'Style2',
                            ],
                            'default' => 'style-default',
                        ),
                        array(
                            'name' => 'color',
                            'label' => esc_html__( 'Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'box_color',
                            'label' => esc_html__( 'Box Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                        array(
                            'name' => 'color_hover',
                            'label' => esc_html__( 'Color Hover', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a:hover' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'box_color_hover',
                            'label' => esc_html__( 'Box Color Hover', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a:hover' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                        array(
                            'name' => 'icon_font_size',
                            'label' => esc_html__('Font Size', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'box_width',
                            'label' => esc_html__('Box Width', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                        array(
                            'name' => 'box_height',
                            'label' => esc_html__('Box Height', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                        array(
                            'name' => 'border_type',
                            'label' => esc_html__( 'Border Type', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '' => esc_html__( 'None', 'cyberguard' ),
                                'solid' => esc_html__( 'Solid', 'cyberguard' ),
                                'double' => esc_html__( 'Double', 'cyberguard' ),
                                'dotted' => esc_html__( 'Dotted', 'cyberguard' ),
                                'dashed' => esc_html__( 'Dashed', 'cyberguard' ),
                                'groove' => esc_html__( 'Groove', 'cyberguard' ),
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'border-style: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'border_width',
                            'label' => esc_html__( 'Border Width', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                            'condition' => [
                                'border_type!' => '',
                            ],
                            'responsive' => true,
                        ),
                        array(
                            'name' => 'border_color',
                            'label' => esc_html__( 'Border Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'border-color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'border_type!' => '',
                            ],
                        ),
                        array(
                            'name' => 'border_color_hover',
                            'label' => esc_html__( 'Border Color Hover', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a:hover' => 'border-color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'border_type!' => '',
                            ],
                        ),
                        array(
                            'name' => 'box_radius',
                            'label' => esc_html__('Border Radius', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                        array(
                            'name'         => 'box_shadow',
                            'label' => esc_html__( 'Box Shadow', 'cyberguard' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .pxl-icon-list a',
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                        array(
                            'name'         => 'box_shadow_hover',
                            'label' => esc_html__( 'Hover - Box Shadow', 'cyberguard' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .pxl-icon-list a:hover',
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                        array(
                            'name' => 'icon_space',
                            'label' => esc_html__('Spacer (Left/Right)', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list' => 'gap: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'icon_space_bottom',
                            'label' => esc_html__('Spacer (Bottom)', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'background-color',
                            'label' => esc_html__( 'Bg Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list.style-box1 a' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'bg-color-hover',
                            'label' => esc_html__( 'Bg Hover Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list.style-box1 a:hover' => 'background-color: {{VALUE}};',
                            ],
                        ),
                    ),
                ),
                cyberguard_widget_animation_settings(),
            ),
        ),
    ),
    cyberguard_get_class_widget_path()
);