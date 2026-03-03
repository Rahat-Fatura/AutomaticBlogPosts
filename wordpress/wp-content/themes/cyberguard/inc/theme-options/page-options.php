<?php
 
add_action( 'pxl_post_metabox_register', 'cyberguard_page_options_register' );
function cyberguard_page_options_register( $metabox ) {
 
	$panels = [
		'post' => [
			'opt_name'            => 'post_option',
			'display_name'        => esc_html__( 'Post Options', 'cyberguard' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'post_settings' => [
					'title'  => esc_html__( 'Post Options', 'cyberguard' ),
					'icon'   => 'el el-refresh',
					'fields' => array_merge(
						cyberguard_sidebar_pos_opts(['prefix' => 'post_', 'default' => true, 'default_value' => '-1']),
						array(
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Spacing Top/Bottom', 'cyberguard' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							),
					    )
					)
				]
			]
		],
		'page' => [
			'opt_name'            => 'pxl_page_options',
			'display_name'        => esc_html__( 'Page Options', 'cyberguard' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'Header', 'cyberguard' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
				        cyberguard_header_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				           		'id'       => 'logo_m',
					            'type'     => 'media',
					            'title'    => esc_html__('Mobile Logo', 'cyberguard'),
					            'default'  => '',
					            'url'      => false,
					        ),
					        array(
					            'id'       => 'mobile_style',
					            'type'     => 'button_set',
					            'title'    => esc_html__('Mobile Style', 'cyberguard'),
					            'options'  => array(
					                'inherit'  => esc_html__('Inherit', 'cyberguard'),
					                'light'  => esc_html__('Light', 'cyberguard'),
					                'dark'  => esc_html__('Dark', 'cyberguard'),
					            ),
					            'default'  => 'inherit',
					        ),
					        array(
				                'id'       => 'p_menu',
				                'type'     => 'select',
				                'title'    => esc_html__( 'Menu', 'cyberguard' ),
				                'options'  => cyberguard_get_nav_menu_slug(),
				                'default' => '',
				            ),
				            array(
					            'id'          => 'bg_header_color',
					            'type'        => 'color',
					            'title'       => esc_html__('Header Background Color', 'cyberguard'),
					            'transparent' => false,
					            'default'     => ''
					        ),
					    ),
					    array(
				            array(
				                'id'       => 'sticky_scroll',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Sticky Scroll', 'cyberguard'),
				                'options'  => array(
				                    '-1' => esc_html__('Inherit', 'cyberguard'),
				                    'pxl-sticky-stt' => esc_html__('Scroll To Top', 'cyberguard'),
				                    'pxl-sticky-stb'  => esc_html__('Scroll To Bottom', 'cyberguard'),
				                ),
				                'default'  => '-1',
				            ),
				        ),
				    )
					 
				],
				'page_title' => [
					'title'  => esc_html__( 'Page Title', 'cyberguard' ),
					'icon'   => 'el el-indent-left',
					'fields' => array_merge(
				        cyberguard_page_title_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
					        array(
					            'id' => 'custom_ptitle',
					            'type' => 'text',
					            'title' => esc_html__('Custom Page Title', 'cyberguard'),
					        ),
					    )
				    )
				],
				'content' => [
					'title'  => esc_html__( 'Content', 'cyberguard' ),
					'icon'   => 'el-icon-pencil',
					'fields' => array_merge(
						cyberguard_sidebar_pos_opts(['prefix' => 'page_', 'default' => false, 'default_value' => '0']),
						array(
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Spacing Top/Bottom', 'cyberguard' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							), 
					    )
					)
				],
				'footer' => [
					'title'  => esc_html__( 'Footer', 'cyberguard' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        cyberguard_footer_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'p_footer_fixed',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Footer Fixed', 'cyberguard'),
				                'options'  => array(
				                    'inherit' => esc_html__('Inherit', 'cyberguard'),
				                    'on' => esc_html__('On', 'cyberguard'),
				                    'off' => esc_html__('Off', 'cyberguard'),
				                ),
				                'default'  => 'inherit',
				            ),
						)
				    )
				],
				'colors' => [
					'title'  => esc_html__( 'Colors', 'cyberguard' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        array(
				        	array(
					            'id'          => 'body_bg_color',
					            'type'        => 'color',
					            'title'       => esc_html__('Body Background Color', 'cyberguard'),
					            'transparent' => false,
					            'default'     => ''
					        ),
				        	array(
					            'id'          => 'primary_color',
					            'type'        => 'color',
					            'title'       => esc_html__('Primary Color', 'cyberguard'),
					            'transparent' => false,
					            'default'     => ''
					        ),
					        array(
					            'id'          => 'secondary_color',
					            'type'        => 'color',
					            'title'       => esc_html__('Secondary Color', 'cyberguard'),
					            'transparent' => false,
					            'default'     => ''
					        ),
					        array(
					            'id'          => 'gradient_color',
					            'type'        => 'color_gradient',
					            'title'       => esc_html__('Gradient Color', 'cyberguard'),
					            'transparent' => false,
					            'default'  => array(
					                'from' => '',
					                'to'   => '', 
					            ),
					        ),
					    )
				    )
				],
				'extra' => [
					'title'  => esc_html__( 'Extra', 'cyberguard' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        array(
				        	array(
					            'id' => 'body_custom_class',
					            'type' => 'text',
					            'title' => esc_html__('Body Custom Class', 'cyberguard'),
					        ),
					    )
				    )
				]
			]
		],
		'portfolio' => [
			'opt_name'            => 'pxl_portfolio_options',
			'display_name'        => esc_html__( 'Portfolio Options', 'cyberguard' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'cyberguard' ),
					'icon'   => 'el-icon-website',
				    'fields' => array_merge(
						array(
							array(
					            'id'=> 'portfolio_excerpt',
					            'type' => 'textarea',
					            'title' => esc_html__('Excerpt', 'cyberguard'),
					            'validate' => 'html_custom',
					            'default' => '',
					        ),
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Content Spacing Top/Bottom', 'cyberguard' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							),
						),
				    )
				],
			]
		],
		'service' => [
			'opt_name'            => 'pxl_service_options',
			'display_name'        => esc_html__( 'Service Options', 'cyberguard' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'cyberguard' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
						cyberguard_header_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
					            'id'=> 'service_external_link',
					            'type' => 'text',
					            'title' => esc_html__('External Link', 'cyberguard'),
					            'validate' => 'url',
					            'default' => '',
					        ),
							array(
					            'id'=> 'service_excerpt',
					            'type' => 'textarea',
					            'title' => esc_html__('Excerpt', 'cyberguard'),
					            'validate' => 'html_custom',
					            'default' => '',
					        ),
					        array(
					            'id'       => 'service_icon_type',
					            'type'     => 'button_set',
					            'title'    => esc_html__('Icon Type', 'cyberguard'),
					            'options'  => array(
					                'icon'  => esc_html__('Icon', 'cyberguard'),
					                'image'  => esc_html__('Image', 'cyberguard'),
					            ),
					            'default'  => 'icon'
					        ),
					        array(
					            'id'       => 'service_icon_font',
					            'type'     => 'pxl_iconpicker',
					            'title'    => esc_html__('Icon', 'cyberguard'),
					            'required' => array( 0 => 'service_icon_type', 1 => 'equals', 2 => 'icon' ),
            					'force_output' => true
					        ),
					        array(
					            'id'       => 'service_icon_img',
					            'type'     => 'media',
					            'title'    => esc_html__('Icon Image', 'cyberguard'),
					            'default' => '',
					            'required' => array( 0 => 'service_icon_type', 1 => 'equals', 2 => 'image' ),
				            	'force_output' => true
					        ),
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Content Spacing Top/Bottom', 'cyberguard' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							),
						)
				    )
				],
				'page_title_service' => [
					'title'  => esc_html__( 'Page Title Service', 'cyberguard' ),
					'icon'   => 'el el-indent-left',
					'fields' => array_merge(
				        cyberguard_page_title_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
					        array(
					            'id' => 'custom_ptitle',
					            'type' => 'text',
					            'title' => esc_html__('Custom Page Title Service', 'cyberguard'),
					        ),
					    )
				    )
				],
			]
		],
		'product' => [
			'opt_name'            => 'pxl_product_options',
			'display_name'        => esc_html__( 'Product Options', 'cyberguard' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'cyberguard' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
						array(
							array(
					            'id'=> 'product_label',
					            'type' => 'text',
					            'title' => esc_html__('Label', 'cyberguard'),
					            'default' => '',
					        ),
					        array(
					            'id'=> 'product_text_btn',
					            'type' => 'text',
					            'title' => esc_html__('Text Button Video', 'cyberguard'),
					            'default' => '',
					        ),
					        array(
								'id'           => 'link_video',
								'type'         => 'text',
								'title'        => esc_html__( 'Link Video', 'cyberguard' ),
								'default'		=> '',
								'force_output' => true
							),
						)
				    )
				],
			]
		],
		'pxl-template' => [
			'opt_name'            => 'pxl_hidden_template_options',
			'display_name'        => esc_html__( 'Template Options', 'cyberguard' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'cyberguard' ),
					'icon'   => 'el-icon-website',
					'fields' => array(
						array(
							'id'    => 'template_type',
							'type'  => 'select',
							'title' => esc_html__('Type', 'cyberguard'),
				            'options' => [
				            	'df'       	   => esc_html__('Select Type', 'cyberguard'), 
								'header'       => esc_html__('Header', 'cyberguard'), 
								'footer'       => esc_html__('Footer', 'cyberguard'), 
								'mega-menu'    => esc_html__('Mega Menu', 'cyberguard'), 
								'page-title'   => esc_html__('Page Title', 'cyberguard'), 
								'tab' => esc_html__('Tab', 'cyberguard'),
								'wgaboutauthor' => esc_html__('Widget Sidebar', 'cyberguard'),
								'hidden-panel' => esc_html__('Hidden Panel', 'cyberguard'),
								'popup' => esc_html__('Popup', 'cyberguard'),
								'slider' => esc_html__('Slider', 'cyberguard'),
				            ],
				            'default' => 'df',
				        ),
				        array(
							'id'    => 'header_type',
							'type'  => 'select',
							'title' => esc_html__('Header Type', 'cyberguard'),
				            'options' => [
				            	'px-header--default'       	   => esc_html__('Default', 'cyberguard'), 
								'px-header--transparent'       => esc_html__('Transparent', 'cyberguard'),
								'px-header--fixed'       => esc_html__('Fixed', 'cyberguard'),
				            ],
				            'default' => 'px-header--default',
				            'indent' => true,
                			'required' => array( 0 => 'template_type', 1 => 'equals', 2 => 'header' ),
				        ),
					),
				    
				],
			]
		],
	];
 
	$metabox->add_meta_data( $panels );
}
 