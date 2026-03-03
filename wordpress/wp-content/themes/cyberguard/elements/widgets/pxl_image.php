<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_image',
        'title' => esc_html__('BR Image', 'cyberguard' ),
        'icon' => 'eicon-image',
        'categories' => array('pxltheme-core'),
        'scripts' => array(
            'tilt',
            'pxl-tweenmax',
        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'tab_content',
                    'label' => esc_html__('Content', 'cyberguard' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'source_type',
                            'label' => esc_html__('Source Type', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                's_img' => 'Select Image',
                                'f_img' => 'Featured Image',
                            ],
                            'default' => 's_img',
                        ),
                        array(
                            'name' => 'image',
                            'label' => esc_html__('Choose Image', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                            'condition' => [
                                'source_type' => ['s_img'],
                            ],
                        ),
                        array(
                            'name' => 'image_link',
                            'label' => esc_html__('Link', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::URL,
                        ),
                        array(
                            'name' => 'image_type',
                            'label' => esc_html__('Image Type', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'img' => 'Image',
                                'bg' => 'Background',
                            ],
                            'default' => 'img',
                        ),
                        array(
                            'name' => 'img_size',
                            'label' => esc_html__('Image Size', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'description' => 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height).',
                            'control_type' => 'responsive',
                            'condition' => [
                                'image_type' => ['img'],
                            ],
                        ),
                        array(
                            'name' => 'image_align',
                            'label' => esc_html__('Image Alignment', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'control_type' => 'responsive',
                            'options' => [
                                'left' => [
                                    'title' => esc_html__('Left', 'cyberguard' ),
                                    'icon' => 'fa fa-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__('Center', 'cyberguard' ),
                                    'icon' => 'fa fa-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__('Right', 'cyberguard' ),
                                    'icon' => 'fa fa-align-right',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-image-single' => 'text-align: {{VALUE}};',
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'tab_style_img',
                    'label' => esc_html__('Image', 'cyberguard' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-default' => 'Style Default',
                                'style-overlay' => 'Style Overlay',
                            ],
                            'default' => 'style-default',
                        ),
                        array(
                            'name' => 'image_max_height',
                            'label' => esc_html__('Image Max Height', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'description' => esc_html__('Enter number.', 'cyberguard' ),
                            'condition' => [
                                'image_type' => 'img',
                            ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-image-single img' => 'max-height: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'image_type' => 'img',
                            ],
                        ),
                        array(
                            'name' => 'image_width',
                            'label' => esc_html__('Image Width', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'options' => [
                                'auto' => [
                                    'title' => esc_html__( 'Auto', 'cyberguard' ),
                                    'icon' => 'fas fa-arrows-alt-v',
                                ],
                                '100%' => [
                                    'title' => esc_html__( 'Full', 'cyberguard' ),
                                    'icon' => 'fas fa-arrows-alt-h',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-image-single img' => 'width: {{VALUE}};',
                            ],
                            'condition' => [
                                'image_type' => 'img',
                            ],
                            'control_type' => 'responsive',
                        ),
                        array(
                            'name' => 'image_height',
                            'label' => esc_html__('Image Height', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'description' => esc_html__('Enter number.', 'cyberguard' ),
                            'condition' => [
                                'image_type' => 'bg',
                            ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-image-single .pxl-item--inner' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'border_radius',
                            'label' => esc_html__('Border Radius', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-image-single img, {{WRAPPER}} .pxl-item--inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .pxl-image-single img' => 'border-style: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'border_width',
                            'label' => esc_html__( 'Border Width', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-image-single img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
                                '{{WRAPPER}} .pxl-image-single img' => 'border-color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'border_type!' => '',
                            ],
                        ),
                        array(
                            'name'         => 'box_shadow',
                            'label' => esc_html__( 'Box Shadow', 'cyberguard' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .pxl-image-single img'
                        ),
                        array(
                            'name' => 'img_effect',
                            'label' => esc_html__('Image Effect', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '' => 'None',
                                'pxl-image-effect1' => 'Zigzag',
                                'pxl-image-tilt' => 'Tilt',
                                'pxl-image-spin-normal' => 'Spin',
                                'pxl-image-spin' => 'Spin Reverse',
                                'pxl-image-zoom' => 'Zoom',
                                'pxl-image-bounce' => 'Bounce',
                                'slide-up-down' => 'Slide Up Down',
                                'slide-top-to-bottom' => 'Slide Top To Bottom ',
                                'pxl-image-effect2' => 'Slide Bottom To Top ',
                                'slide-right-to-left' => 'Slide Right To Left ',
                                'slide-left-to-right' => 'Slide Left To Right ',
                                'pxl-hover1' => 'ZoomIn',
                                'pxl-hover2' => 'ZoomOut',
                                'pxl-animation-round' => 'Round',
                                'pxl-image-parallax' => 'Parallax',
                            ],
                            'default' => '',
                            'condition' => [
                                'image_type' => 'img',
                            ],
                        ),
                        array(
                            'name' => 'parallax_value',
                            'label' => esc_html__('Parallax Value', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'img_effect' => 'pxl-image-parallax',
                            ],
                            'default' => '40',
                            'description' => esc_html__('Enter number.', 'cyberguard' ),
                        ),
                        array(
                            'name' => 'max_tilt',
                            'label' => esc_html__('Max Tilt', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'img_effect' => 'pxl-image-tilt',
                            ],
                            'default' => '10',
                            'description' => esc_html__('Enter number.', 'cyberguard' ),
                        ),
                        array(
                            'name' => 'speed_tilt',
                            'label' => esc_html__('Speed Tilt', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'img_effect' => 'pxl-image-tilt',
                            ],
                            'default' => '400',
                            'description' => esc_html__('Enter number.', 'cyberguard' ),
                        ),
                        array(
                            'name' => 'perspective_tilt',
                            'label' => esc_html__('Perspective Tilt', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'img_effect' => 'pxl-image-tilt',
                            ],
                            'default' => '1000',
                            'description' => esc_html__('Enter number.', 'cyberguard' ),
                        ),
                        array(
                            'name' => 'speed_effect',
                            'label' => esc_html__('Speed', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 100000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-image-single' => 'animation-duration: {{SIZE}}ms;',
                            ],
                            'condition' => [
                                'img_effect!' => ['pxl-image-tilt','pxl-hover1'],
                            ],
                            'description' => 'Enter number, unit is ms.',
                        ),
                        array(
                            'name' => 'opacity',
                            'label' => esc_html__('Opacity', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 100000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-image-single img' => 'opacity: {{SIZE}};',
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
                        array(
                            'name' => 'pxl_animate_img',
                            'label' => esc_html__('Animate Image', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => cyberguard_widget_animate(),
                            'default' => '',
                        ),
                        array(
                            'name' => 'pxl_animate_img_delay',
                            'label' => esc_html__('Animate Image Delay', 'cyberguard' ),
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