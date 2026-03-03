<?php
if(class_exists('WPCF7')) {
    $cf7 = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');

    $contact_forms = array();
    if ($cf7) {
        foreach ($cf7 as $cform) {
            $contact_forms[$cform->ID] = $cform->post_title;
        }
    } else {
        $contact_forms[esc_html__('No contact forms found', 'cyberguard')] = 0;
    }

    pxl_add_custom_widget(
        array(
            'name' => 'pxl_contact_form',
            'title' => esc_html__('BR Contact Form', 'cyberguard'),
            'icon' => 'eicon-form-horizontal',
            'categories' => array('pxltheme-core'),
            'params' => array(
                'sections' => array(
                    array(
                        'name' => 'tab_content',
                        'label' => esc_html__('Content', 'cyberguard'),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                        'controls' => array(
                            array(
                                'name' => 'form_id',
                                'label' => esc_html__('Select Form', 'cyberguard'),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => $contact_forms,
                            ),
                        ),
                    ),
                    array(
                        'name' => 'tab_style_input',
                        'label' => esc_html__('Input', 'cyberguard'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'controls' => array(
                            array(
                                'name' => 'input_typography',
                                'label' => esc_html__('Typography', 'cyberguard' ),
                                'type' => \Elementor\Group_Control_Typography::get_type(),
                                'control_type' => 'group',
                                'selector' => '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit), {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight, {{WRAPPER}} .pxl-select .pxl-select-options li',
                            ),
                            array(
                                'name' => 'input_color',
                                'label' => esc_html__('Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit), {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight, {{WRAPPER}} .pxl-select .pxl-select-options li' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'input_bg_color',
                                'label' => esc_html__('Background Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit), {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight' => 'background-color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'input_padding',
                                'label' => esc_html__('Padding Input', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-textarea), {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'control_type' => 'responsive',
                            ),
                            array(
                                'name' => 'input_border_radius',
                                'label' => esc_html__('Border Radius', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit), {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'control_type' => 'responsive',
                            ),
                            array(
                                'name'         => 'input_box_shadow',
                                'label' => esc_html__( 'Box Shadow', 'cyberguard' ),
                                'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                                'control_type' => 'group',
                                'selector'     => '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit), {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight'
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
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-acceptance), {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight' => 'border-style: {{VALUE}} !important;',
                                ],
                            ),
                            array(
                                'name' => 'border_width',
                                'label' => esc_html__( 'Border Width', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit), {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit), {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight' => 'border-color: {{VALUE}} !important;',
                                ],
                                'condition' => [
                                    'border_type!' => '',
                                ],
                            ),
                            array(
                                'name' => 'input_height',
                                'label' => esc_html__('Input Height', 'cyberguard' ),
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
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-textarea), {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight' => 'height: {{SIZE}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'textarea_height',
                                'label' => esc_html__('Textarea Height', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::SLIDER,
                                'control_type' => 'responsive',
                                'size_units' => [ 'px' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 3000,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control.wpcf7-textarea' => 'height: {{SIZE}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'textarea_padding',
                                'label' => esc_html__('Padding Textarea', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control.wpcf7-textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'control_type' => 'responsive',
                            ),
                            array(
                                'name' => 'textarea_border_type',
                                'label' => esc_html__( 'Textarea Border Type', 'cyberguard' ),
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
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control.wpcf7-textarea' => 'border-style: {{VALUE}} !important;',
                                ],
                            ),
                            array(
                                'name' => 'textarea_border_width',
                                'label' => esc_html__( 'Textarea Border Width', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control.wpcf7-textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                                ],
                                'condition' => [
                                    'textarea_border_type!' => '',
                                ],
                                'responsive' => true,
                            ),
                            array(
                                'name' => 'textarea_typography',
                                'label' => esc_html__('Textarea Typography', 'cyberguard' ),
                                'type' => \Elementor\Group_Control_Typography::get_type(),
                                'control_type' => 'group',
                                'selector' => '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control.wpcf7-textarea',
                            ),
                            array(
                                'name' => 'textarea_color',
                                'label' => esc_html__('Textarea Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control.wpcf7-textarea' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'input_spacer_bottom',
                                'label' => esc_html__('Spacer Bottom', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::SLIDER,
                                'control_type' => 'responsive',
                                'size_units' => [ 'px' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 3000,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                                ],
                            ),
                        ),
                    ),
                    array(
                        'name' => 'tab_style_input_hover',
                        'label' => esc_html__('Input Hover', 'cyberguard'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'controls' => array(
                            array(
                                'name' => 'input_color_hover',
                                'label' => esc_html__('Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit):hover, {{WRAPPER}} .pxl-contact-form .pxl-select-options li, {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight:hover, {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight.active' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'input_bg_color_hover',
                                'label' => esc_html__('Background Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit):hover' => 'background-color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name'         => 'input_box_shadow_hover',
                                'label' => esc_html__( 'Box Shadow', 'cyberguard' ),
                                'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                                'control_type' => 'group',
                                'selector'     => '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit):hover'
                            ),
                            array(
                                'name' => 'border_type_hover',
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
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit):hover, {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight:hover' => 'border-style: {{VALUE}} !important;',
                                ],
                            ),
                            array(
                                'name' => 'border_width_hover',
                                'label' => esc_html__( 'Border Width', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit):hover, {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                                ],
                                'condition' => [
                                    'border_type_hover!' => '',
                                ],
                                'responsive' => true,
                            ),
                            array(
                                'name' => 'border_color_hover',
                                'label' => esc_html__( 'Border Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-form-control:not(.wpcf7-submit):hover, {{WRAPPER}} .pxl-contact-form .pxl-select-higthlight:hover' => 'border-color: {{VALUE}} !important;',
                                ],
                                'condition' => [
                                    'border_type_hover!' => '',
                                ],
                            ),
                        ),
                    ),
                    array(
                        'name' => 'tab_style_button',
                        'label' => esc_html__('Button', 'cyberguard'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'controls' => array(
                            array(
                                'name' => 'button_typography',
                                'label' => esc_html__('Button Typography', 'cyberguard' ),
                                'type' => \Elementor\Group_Control_Typography::get_type(),
                                'control_type' => 'group',
                                'selector' => '{{WRAPPER}} .pxl-contact-form .wpcf7-submit, {{WRAPPER}} .pxl-contact-form button',
                            ),
                            array(
                                'name' => 'button_color',
                                'label' => esc_html__('Button Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'button_color_hover',
                                'label' => esc_html__('Button Color Hover', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit:hover, {{WRAPPER}} .pxl-contact-form .wpcf7-submit:focus' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'button_bg_color',
                                'label' => esc_html__('Button Background Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit' => 'background: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'button_bg_color_hover',
                                'label' => esc_html__('Button Background Color Hover', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit:hover, {{WRAPPER}} .pxl-contact-form .wpcf7-submit:focus' => 'background-color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'button_padding',
                                'label' => esc_html__('Padding', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit, {{WRAPPER}} .pxl-contact-form button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'control_type' => 'responsive',
                            ),
                            array(
                                'name' => 'button_margin',
                                'label' => esc_html__('Margin', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit, {{WRAPPER}} .pxl-contact-form button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'control_type' => 'responsive',
                            ),
                            array(
                                'name' => 'button_border_radius',
                                'label' => esc_html__('Border Radius', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit, {{WRAPPER}} .pxl-contact-form button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'control_type' => 'responsive',
                            ),
                            array(
                                'name'         => 'button_box_shadow',
                                'label' => esc_html__( 'Box Shadow', 'cyberguard' ),
                                'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                                'control_type' => 'group',
                                'selector'     => '{{WRAPPER}} .pxl-contact-form .wpcf7-submit, {{WRAPPER}} .pxl-contact-form button'
                            ),
                            array(
                                'name' => 'button_border_type',
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
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit' => 'border-style: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'button_border_width',
                                'label' => esc_html__( 'Border Width', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'condition' => [
                                    'button_border_type!' => '',
                                ],
                                'responsive' => true,
                            ),
                            array(
                                'name' => 'button_border_color',
                                'label' => esc_html__( 'Border Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit' => 'border-color: {{VALUE}};',
                                ],
                                'condition' => [
                                    'button_border_type!' => '',
                                ],
                            ),
                            array(
                                'name' => 'btn_width',
                                'label' => esc_html__( 'Width', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => [
                                    'btn-w-auto' => 'Auto',
                                    'btn-w-full' => '100%',
                                ],
                                'default' => 'btn-w-auto',
                            ),
                            array(
                                'name' => 'btn_spacer_top',
                                'label' => esc_html__('Spacer Top', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::SLIDER,
                                'control_type' => 'responsive',
                                'size_units' => [ 'px' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 3000,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .wpcf7-submit' => 'margin-top: {{SIZE}}{{UNIT}};',
                                ],
                            ),
                        ),
                    ),
                    array(
                        'name' => 'tab_box',
                        'label' => esc_html__('Box', 'cyberguard'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'controls' => array(
                            array(
                                'name' => 'box_style',
                                'label' => esc_html__( 'Style', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => [
                                    'box-style-none' => 'None',
                                    'box-style-white' => 'Box White',
                                ],
                                'default' => 'box-style-none',
                            ),
                            array(
                                'name' => 'title',
                                'label' => esc_html__('Title', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::TEXT,
                                'label_block' => true,
                            ),
                            array(
                                'name' => 'box_title_typography',
                                'label' => esc_html__('Title Typography', 'cyberguard' ),
                                'type' => \Elementor\Group_Control_Typography::get_type(),
                                'control_type' => 'group',
                                'selector' => '{{WRAPPER}} .pxl-contact-form .pxl-contact-meta h5',
                            ),
                            array(
                                'name' => 'box_title_color',
                                'label' => esc_html__('Title Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .pxl-contact-meta h5' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'box_title_spacer_bottom',
                                'label' => esc_html__('Title Spacer Bottom', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::SLIDER,
                                'control_type' => 'responsive',
                                'size_units' => [ 'px' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 3000,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .pxl-contact-meta h5' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'desc',
                                'label' => esc_html__('Description', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::TEXTAREA,
                                'rows' => 10,
                                'show_label' => false,
                            ),
                            array(
                                'name' => 'box_desc_typography',
                                'label' => esc_html__('Description Typography', 'cyberguard' ),
                                'type' => \Elementor\Group_Control_Typography::get_type(),
                                'control_type' => 'group',
                                'selector' => '{{WRAPPER}} .pxl-contact-form .pxl-contact-meta p',
                            ),
                            array(
                                'name' => 'box_desc_color',
                                'label' => esc_html__('Description Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .pxl-contact-meta p' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'box_border_radius',
                                'label' => esc_html__('Border Radius', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name'         => 'box_box_shadow',
                                'label' => esc_html__( 'Box Shadow', 'cyberguard' ),
                                'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                                'control_type' => 'group',
                                'selector'     => '{{WRAPPER}} .pxl-contact-form'
                            ),
                            array(
                                'name' => 'box_padding',
                                'label' => esc_html__('Padding', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'control_type' => 'responsive',
                            ),
                        ),
                    ),
                    cyberguard_widget_animation_settings(),
                    array(
                        'name' => 'extra',
                        'label' => esc_html__('Extra', 'cyberguard'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'controls' => array(
                            array(
                                'name' => 'label_color',
                                'label' => esc_html__('Label Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .pxl--item > label' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'label_typography',
                                'label' => esc_html__('Label Typography', 'cyberguard' ),
                                'type' => \Elementor\Group_Control_Typography::get_type(),
                                'control_type' => 'group',
                                'selector' => '{{WRAPPER}} .pxl-contact-form .pxl--item > label',
                            ),
                            array(
                                'name' => 'label_spacer_bottom',
                                'label' => esc_html__('Label Spacer Bottom', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::SLIDER,
                                'control_type' => 'responsive',
                                'size_units' => [ 'px' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 3000,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .pxl--item > label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'icon_color',
                                'label' => esc_html__('Icon Color', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .pxl--form-icon' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'icon_space',
                                'label' => esc_html__('Icon Spacer', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-contact-form .pxl--form-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'control_type' => 'responsive',
                            ),
                            array(
                                'name' => 'notification_form',
                                'label' => esc_html__( 'Notification Form', 'cyberguard' ),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => [
                                    'notice-style1' => 'Style 1',
                                ],
                                'default' => 'notice-style1',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        cyberguard_get_class_widget_path()
    );
}