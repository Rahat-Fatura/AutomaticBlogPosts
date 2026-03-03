<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_meta',
        'title' => esc_html__('BR Meta', 'cyberguard'),
        'icon' => 'eicon-posts-ticker',
        'categories' => array('pxltheme-core'),
        'scripts' => array(
            'elementor-waypoints',
            'jquery-numerator',
            'pxl-counter',
            'cyberguard-counter',
        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_layout',
                    'label' => esc_html__('Layout', 'cyberguard' ),
                    'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
                    'controls' => array(
                        array(
                            'name' => 'layout',
                            'label' => esc_html__('Templates', 'cyberguard' ),
                            'type' => 'layoutcontrol',
                            'default' => '1',
                            'options' => [
                                '1' => [
                                    'label' => esc_html__('Layout 1', 'cyberguard' ),
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_meta/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'cyberguard' ),
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_meta/layout2.jpg'
                                ],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'cyberguard'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'lists_lv1',
                            'label' => esc_html__('Content Lv1', 'cyberguard'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'condition' => [
                                'layout' => ['1'],
                            ],
                            'controls' => array(
                                array(
                                    'name' => 'number_lv1',
                                    'label' => esc_html__('Number', 'cyberguard'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'title_lv1',
                                    'label' => esc_html__('Title', 'cyberguard'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'desc_lv1',
                                    'label' => esc_html__('Description', 'cyberguard' ),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'rows' => 10,
                                    'show_label' => false,
                                ),
                            ),
                            'title_field' => '{{{ title_lv1 }}}',
                        ),
                        array(
                            'name' => 'title',
                            'label' => esc_html__('Title', 'cyberguard'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'condition' => [
                                'layout' => ['2'],
                            ],
                        ),
                        array(
                            'name' => 'desc',
                            'label' => esc_html__('Description', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXTAREA,
                            'rows' => 10,
                            'show_label' => false,
                            'condition' => [
                                'layout' => ['2'],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style',
                    'label' => esc_html__('Style', 'cyberguard' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'align_dot',
                            'label' => esc_html__('Alignment Dot', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'control_type' => 'responsive',
                            'options' => [
                                'start'    => [
                                    'title' => esc_html__('Star', 'cyberguard' ),
                                    'icon' => 'fa fa-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__('Center', 'cyberguard' ),
                                    'icon' => 'fa fa-align-center',
                                ],
                                'end' => [
                                    'title' => esc_html__('End', 'cyberguard' ),
                                    'icon' => 'fa fa-align-right',
                                ],
                            ],
                            'default' => '',
                            'selectors'         => [
                                '{{WRAPPER}} .pxl-meta .pxl-item' => 'align-items: {{VALUE}} !important',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'number_bg',
                            'label' => esc_html__('Number BG Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-meta .pxl-meta-inner .pxl-item-number' => 'background-color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'number_color',
                            'label' => esc_html__('Number Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-meta .pxl-meta-inner .pxl-item-number' => 'color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'number_typography',
                            'label' => esc_html__('Number Typography', 'cyberguard' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-meta .pxl-meta-inner .pxl-item .pxl-item-number',
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-meta .pxl-meta-inner .pxl-item-title' => 'color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Typography', 'cyberguard' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-meta .pxl-meta-inner .pxl-item .pxl-item-title',
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'margin_title',
                            'label' => esc_html__('Margin Title', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-meta .pxl-meta-inner .pxl-item .pxl-item-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'control_type' => 'responsive',
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'desc_color',
                            'label' => esc_html__('Desc Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-meta .pxl-meta-inner .pxl-item-desc' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'desc_typography',
                            'label' => esc_html__('Desc Typography', 'cyberguard' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-meta .pxl-meta-inner .pxl-item-desc',
                        ),
                        array(
                            'name' => 'margin_list',
                            'label' => esc_html__('Margin List', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-meta .pxl-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'control_type' => 'responsive',
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'pxl_animate',
                            'label' => esc_html__('Animate', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => cyberguard_widget_animate(),
                            'default' => '',
                        ),
                        array(
                            'name' => 'pxl_animate_delay',
                            'label' => esc_html__('Animate Delay', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '0',
                            'description' => 'Enter number. Default 0ms',
                        ),
                    ),
                ),
            ),
        ),
    ),
    cyberguard_get_class_widget_path()
);