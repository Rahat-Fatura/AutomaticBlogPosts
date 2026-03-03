<?php	
namespace Amedea\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */

class amedea__fullscreen_menu2 extends Widget_Base {

	/**
	* Retrieve the widget name.
	*
	* @since 1.0.0
	*
	* @return string Widget name.
	*/

	public function get_name() {
		return 'fullscreen_menu2';
	}
	
	/**
	* Retrieve the widget title.
	*
	* @since 1.0.0
	*
	* @return string Widget title.
	*/

	public function get_title() {
		return esc_html__( 'Fullscreen Menu #2', 'amedea' );
	}
	
	/**
	* Retrieve the widget icon.
	*
	* @since 1.0.0
	*
	* @return string Widget icon.
	*/

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	* Retrieve the list of scripts the widget depended on.
	*
	* Used to set scripts dependencies required to run the widget.
	*
	* @since 1.0.0
	*
	* @return array Widget scripts dependencies.
	*/

	public function get_script_depends() {
		return [  'gsap' , 'imagesloaded' ,  'amedea-fullscreen-menu' ];
	}

	/**
	 * Retrieve the list of style the widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends() {
		return [ 'fullscreen-menu' ];
	}
	
	/**
	* Retrieve the list of categories the widget belongs to.
	*
	* Used to determine where to display the widget in the editor.
	*
	* Note that currently Elementor supports only one category.
	* When multiple categories passed, Elementor uses the first one.
	*
	* @since 1.0.0
	*
	* @return array Widget categories.
	*/

	public function get_categories() {
		return [ 'amedea-category' , 'amedea-fullscreen-menu-category' ];
	}


	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	 
	protected function register_controls() {

		$this->start_controls_section(
			'section_content0',
			[
				'label' => esc_html__( 'Color', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'style',
			array(
			  'label'       => esc_html__('Text Color', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'dark',
			  'label_block' => true,
			  'options' => array(
				'light' => esc_html__('Light', 'amedea'),
				'dark' => esc_html__('Dark', 'amedea'),
			  )
			)
		  );
	 
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Configuration', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'sitetitle',
			[
				'label' => esc_html__( 'Site Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Title Visible', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'siteimage',
			[
				'label' => esc_html__( 'Site Image', 'amedea' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'placeholder' => esc_html__( 'Required*', 'amedea' ),
			]
		);
		
		/*$this->add_control(
			'sitesubtitle',
			[
				'label' => esc_html__( 'Site Subtitle', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Spring/Summer Collection', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);*/
		
		$repeater = new \Elementor\Repeater();
				
		$repeater->add_control(
			'itemtitle',
			[
				'label' => esc_html__( 'Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$repeater->add_control(
			'itemaftertitle',
			[
				'label' => esc_html__( 'Title After', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'amazing', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		  
		$repeater->add_control(
			'button_url',
			[
				'label' => esc_html__( 'Button URL', 'amedea' ),
				'type'  => \Elementor\Controls_Manager::URL,
			]
		);
		  			
		$this->add_control(
		  'images',
		  array(
			'label'     => sprintf('Menu items', 'amedea'),
			'type'      => Controls_Manager::REPEATER,
			'fields'    => $repeater->get_controls(),
			'default' => [
					[
						'itemtitle' => esc_html__( 'Item 1', 'amedea' ),
						'itemaftertitle' => esc_html__( '', 'amedea' ),
						'button_url' => '',
					],
					[
						'itemtitle' => esc_html__( 'Item 2', 'amedea' ),
						'itemaftertitle' => esc_html__( '', 'amedea' ),
						'button_url' => '',
					],
				],
			'title_field' => '<span>{{ itemtitle }}</span>',
		  )
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content2',
			[
				'label' => esc_html__( 'Social Networks', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'socialtitle',
			[
				'label' => esc_html__( 'Social Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Facebook', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
				  
		$repeater->add_control(
			'social_url',
			[
				'label' => esc_html__( 'Social URL', 'amedea' ),
				'type'  => \Elementor\Controls_Manager::URL,
			]
		);
		
		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__( 'Social Icon SVG', 'amedea' ),
				'type'  => \Elementor\Controls_Manager::TEXTAREA,
			]
		);
		  			
		$this->add_control(
		  'socials',
		  array(
			'label'     => sprintf('Social network items', 'amedea'),
			'type'      => Controls_Manager::REPEATER,
			'fields'    => $repeater->get_controls(),
			'default' => [
					[
						'socialtitle' => esc_html__( 'Item 1', 'amedea' ),
					],
					[
						'socialtitle' => esc_html__( 'Item 2', 'amedea' ),
					],
				],
			'title_field' => '<span>{{ socialtitle }}</span>',
		  )
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content3',
			[
				'label' => esc_html__( 'Color Management', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
					
		$this->add_control(
			'colorbgmenu',
			array(
			  'label'       => esc_html__('Menu background', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::COLOR
			  )
		);	
		
		$this->add_control(
			'linkcolor',
			array(
			  'label'       => esc_html__('Link color', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::COLOR
			  )
		);
		
		$this->add_control(
			'hovercolor',
			array(
			  'label'       => esc_html__('Link hover color', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::COLOR
			  )
		);
			 
		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings	= $this->get_settings_for_display();
		$images	    = $settings['images'];
		$socials	= $settings['socials'];
		$style	    = $settings['style'];
		
	?>
<style>.fullscreen-menu{--color-bg-menu:<?php echo esc_html($settings['colorbgmenu']); ?>;--color-menu:<?php echo esc_html($settings['linkcolor']); ?>;
	--color-menu-hover:<?php echo esc_html($settings['hovercolor']); ?>}</style>
<div class="fullscreen-menu <?php echo esc_html($settings['style']); ?>">
		<div class="fullscreen-menu__frame">
			<div class="fullscreen-menu__frame__button">
				<button class="unbutton fullscreen-menu__button-menu" aria-label="Menu">
					<svg viewBox="0 0 448 512"><path d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/></svg>
				</button>
			</div>
	
			<div class="fullscreen-menu__frame__heading">
				<span class="fullscreen-menu__frame__heading-main"><?php echo esc_html($settings['sitetitle']); ?></span>
				<!--<span class="fullscreen-menu__frame__heading-sub"><?php echo esc_html($settings['sitesubtitle']); ?></span>-->
			</div>
		</div>
		
		<div class="fullscreen-menu__menu-wrap">
			<div class="fullscreen-menu__content">
		<div class="fullscreen-menu__content-item">
			<div class="fullscreen-menu__column fullscreen-menu__column--image">
				<img alt="<?php echo esc_html($settings['sitetitle']); ?>" src="<?php echo esc_url($settings['siteimage']['url']); ?>">
			</div>
			<div class="fullscreen-menu__column">
				<!-- menu -->
				<?php $i = 0; if (is_array($images) || is_object($images)) { foreach($images as $key => $image): ?>
				<a class="fullscreen-menu__column__item fullscreen-menu__menu__item--selector" href="<?php echo esc_html($image['button_url']['url']); ?>">
					<span class="fullscreen-menu__column__item-name"><?php echo esc_html($image['itemtitle']); ?></span>
					<?php if ( ! empty( $image['itemaftertitle'] ) ) { ?><span class="fullscreen-menu__column__item-label"><?php echo esc_html($image['itemaftertitle']); ?></span><?php } ?>
				</a>
				<?php $i++; endforeach; } ?>
				<!-- menu -->
				<div class="fullscreen-menu__social">
					<ul class="fullscreen-menu__social-icons">
						<?php $i = 0; if (is_array($socials) || is_object($socials)) { foreach($socials as $key => $social): ?>
						<li><a href="<?php echo esc_html($social['social_url']['url']); ?>" target="_blank">
						<?php if ( ! empty( $social['social_icon'] ) ) { echo sprintf($social['social_icon']); } else { echo esc_html($social['socialtitle']); } ?></a></li>
						<?php $i++; endforeach; } ?>
					</ul>
				</div>
			</div>
			<div class="fullscreen-menu__column fullscreen-menu__column--image--end"></div>
		</div>
	</div>
			<button class="unbutton fullscreen-menu__button-close">
				<svg viewBox="-8 -8 64 64">
					<path d="M9.016,40.837c0.195,0.195,0.451,0.292,0.707,0.292c0.256,0,0.512-0.098,0.708-0.293l14.292-14.309
						l14.292,14.309c0.195,0.196,0.451,0.293,0.708,0.293c0.256,0,0.512-0.098,0.707-0.292c0.391-0.39,0.391-1.023,0.001-1.414
						L26.153,25.129L40.43,10.836c0.39-0.391,0.39-1.024-0.001-1.414c-0.392-0.391-1.024-0.391-1.414,0.001L24.722,23.732L10.43,9.423
						c-0.391-0.391-1.024-0.391-1.414-0.001c-0.391,0.39-0.391,1.023-0.001,1.414l14.276,14.293L9.015,39.423
						C8.625,39.813,8.625,40.447,9.016,40.837z">
					</path>
				</svg>
			</button>
		</div>
		<svg class="fullscreen-menu__overlay" width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
			<path class="fullscreen-menu__overlay__path" vector-effect="non-scaling-stroke" d="M 0 100 V 100 Q 50 100 100 100 V 100 z" />
		</svg>
	</div>

	
	<?php
	
	}

	/**
	* Render the widget output in the editor.
	*
	* Written as a Backbone JavaScript template and used to generate the live preview.
	*
	* @since 1.0.0
	*/

	protected function content_template() {}
}