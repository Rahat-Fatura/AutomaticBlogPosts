<?php
// Register Video Player Widget
pxl_add_custom_widget(
    array(
        'name' => 'pxl_video_player',
        'title' => esc_html__('BR Video Player', 'cyberguard' ),
        'icon' => 'eicon-play',
        'categories' => array('pxltheme-core'),
        'scripts' => array(
            'tilt'
        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'cyberguard' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'video_link',
                            'label' => esc_html__('Link', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => 'https://www.youtube.com/watch?v=SF4aHwxHtZ0'
                        ),
                        array(
                            'name' => 'video_icon',
                            'label' => esc_html__('Video Icon', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon',
                        ),
                        array(
                            'name' => 'image_type',
                            'label' => esc_html__('Image Type', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'none' => 'None',
                                'img' => 'Image',
                                'bg' => 'Background',
                            ],
                            'default' => 'none',
                        ),
                        array(
                            'name' => 'image',
                            'label' => esc_html__('Image', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                            'condition' => [
                                'image_type' => ['img', 'bg'],
                            ],
                        ),
                        array(
                            'name' => 'img_size',
                            'label' => esc_html__('Image Size', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'description' => 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height).',
                            'condition' => [
                                'image_type' => 'img',
                            ],
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
                                '{{WRAPPER}} .pxl-video-player .pxl-video--holder' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'btn_video_style',
                            'label' => esc_html__('Button Video Style', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                            ],
                            'default' => 'style1',
                        ),
                        array(
                            'name' => 'background',
                            'label' => esc_html__('Background Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-video-player .btn-video.style1' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'btn_video_style' => ['style1'],
                            ],
                        ),
                        array(
                            'name' => 'icon_color',
                            'label' => esc_html__('Icon Color', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-video-player .btn-video.style1' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'btn_video_style' => ['style1'],
                            ],
                        ),
                        array(
                            'name' => 'btn_video_position',
                            'label' => esc_html__('Button Video Position', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'p-center' => 'Center',
                                'p-top-left' => 'Top Left',
                                'p-top-right' => 'Top Right',
                                'p-bottom-left' => 'Bottom Left',
                                'p-bottom-right' => 'Bottom Right',
                            ],
                            'default' => 'p-center',
                            'condition' => [
                                'image_type' => ['img','bg'],
                            ],
                        ),
                        array(
                            'name' => 'top_positioon',
                            'label' => esc_html__('Top Position', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'control_type' => 'responsive',
                            'default' => [
                                'size' => 0,
                                'unit' => '%',
                            ],
                            'range' => [
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-video--holder + .btn-video-wrap.p-top-left, {{WRAPPER}} .pxl-video--holder + .btn-video-wrap.p-top-right' => 'top: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'btn_video_position' => ['p-top-left', 'p-top-right'],
                            ],
                        ),
                        array(
                            'name' => 'right_positioon',
                            'label' => esc_html__('Right Position', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'control_type' => 'responsive',
                            'default' => [
                                'size' => 0,
                                'unit' => '%',
                            ],
                            'range' => [
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-video--holder + .btn-video-wrap.p-top-right, {{WRAPPER}} .pxl-video--holder + .btn-video-wrap.p-bottom-right' => 'right: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'btn_video_position' => ['p-top-right', 'p-bottom-right'],
                            ],
                        ),
                        array(
                            'name' => 'bottom_positioon',
                            'label' => esc_html__('Bottom Position', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'control_type' => 'responsive',
                            'default' => [
                                'size' => 0,
                                'unit' => '%',
                            ],
                            'range' => [
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-video--holder + .btn-video-wrap.p-bottom-left, {{WRAPPER}} .pxl-video--holder + .btn-video-wrap.p-bottom-right' => 'bottom: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'btn_video_position' => ['p-bottom-left', 'p-bottom-right'],
                            ],
                        ),
                        array(
                            'name' => 'left_positioon',
                            'label' => esc_html__('Left Position', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'control_type' => 'responsive',
                            'default' => [
                                'size' => 0,
                                'unit' => '%',
                            ],
                            'range' => [
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-video--holder + .btn-video-wrap.p-top-left, {{WRAPPER}} .pxl-video--holder + .btn-video-wrap.p-bottom-left' => 'left: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'btn_video_position' => ['p-top-left', 'p-bottom-left'],
                            ],
                        ),
                        array(
                            'name' => 'border_radius',
                            'label' => esc_html__('Border Radius', 'cyberguard' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-video-player .pxl-video--inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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