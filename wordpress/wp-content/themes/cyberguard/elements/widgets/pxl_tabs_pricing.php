<?php
$templates = cyberguard_get_templates_option('tab', []) ;
pxl_add_custom_widget(
    array(
        'name' => 'pxl_tabs_pricing',
        'title' => esc_html__( 'BR Tabs Pricing', 'cyberguard' ),
        'icon' => 'eicon-tabs',
        'categories' => array('pxltheme-core'),
        'scripts' => array(
            'cyberguard-tabs'
        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'tab_content',
                    'label' => esc_html__( 'Tabs', 'cyberguard' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'tab_active',
                            'label' => esc_html__( 'Active Tab', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 1,
                            'separator' => 'after',
                        ),
                        array(
                            'name' => 'title_monthly',
                            'label' => esc_html__( 'Title Monthly', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                        ),
                        array(
                            'name' => 'title_yearly',
                            'label' => esc_html__( 'Title Yearly', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                        ),
                        array(
                            'name' => 'tabs',
                            'label' => esc_html__( 'Content', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'content_type',
                                    'label' => esc_html__('Content Type', 'cyberguard'),
                                    'type' => 'select',
                                    'options' => [
                                        'df' => esc_html__( 'Default', 'cyberguard' ),
                                        'template' => esc_html__( 'From Template Builder', 'cyberguard' )
                                    ],
                                    'default' => 'df' 
                                ),
                                array(
                                    'name' => 'desc',
                                    'label' => esc_html__( 'Content', 'cyberguard' ),
                                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                                    'condition' => ['content_type' => 'df'] 
                                ),
                                array(
                                    'name' => 'content_template',
                                    'label' => esc_html__('Select Template', 'cyberguard'),
                                    'type' => 'select',
                                    'options' => $templates,
                                    'default' => 'df',
                                    'description' => 'Add new tab template: "<a href="' . esc_url( admin_url( 'edit.php?post_type=pxl-template' ) ) . '" target="_blank">Click Here</a>"',
                                    'condition' => ['content_type' => 'template'] 
                                ),
                            ),
                        ),
                        array(
                            'name' => 'max_width',
                            'label' => esc_html__('Content Max Width', 'cyberguard' ),
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
                                '{{WRAPPER}} .pxl-tabs .pxl-tabs--content' => 'max-width: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'tab_style',
                    'label' => esc_html__( 'Style', 'cyberguard' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-1' => 'Style 1',
                            ],
                            'default' => 'style-1',
                        ),
                        array(
                            'name' => 'content_color',
                            'label' => esc_html__('Content Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-tabs .pxl-tab--content' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'content_typography',
                            'label' => esc_html__('Content Typography', 'cyberguard' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-tabs .pxl-tab--content',
                        ),
                        array(
                            'name' => 'content_space_Top',
                            'label' => esc_html__('Content Top Spacer', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'default' => [
                                'size' => 0,
                            ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-tabs .pxl-tabs--content' => 'margin-top: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'after',
                        ),
                    ),
                ),
                cyberguard_widget_animation_settings(),
            ),
        ),
    ),
    cyberguard_get_class_widget_path()
);