<?php
namespace Amedea\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

/**
 * Elementor
 *
 * Elementor widget
 *
 * @since 1.0.0
 */
class amedea__split_header extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'split_header';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Split Header', 'amedea' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-user-preferences';
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'gsap' , 'scrolltrigger' , 'scrollsmoother' , 'amedea-split-header' ];
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
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'amedea-category' , 'amedea-theme-widgets-category' ];
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
				'label' => esc_html__( 'Content', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'image',
			[
			'label' => __( 'Image', 'amedea' ),
			'type' => \Elementor\Controls_Manager::MEDIA,
			'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
		] );
		
		
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
		$settings = $this->get_settings_for_display();
		
		
	?>
	<section id="split-header__wrapper">
		<section id="split-header__content">
			<div class="split-header__hero">
				<div class="split-header__hero__inner constrain">
					<div class="split-header__hero__image-cont">
					 <img src="<?php echo esc_url($settings['image']['url']); ?>" />
					 <div class="split-header__anim-swipe"></div>
					</div>
					<div class="split-header__hero__image-cont">
					 <img src="<?php echo esc_url($settings['image']['url']); ?>" />
					 <div class="split-header__anim-swipe"></div>
					</div>
					<div class="split-header__hero__image-cont">
					 <img src="<?php echo esc_url($settings['image']['url']); ?>" />
					 <div class="split-header__anim-swipe"></div>
					</div>
					<div class="split-header__hero__image-cont">
					 <img src="<?php echo esc_url($settings['image']['url']); ?>" />
					 <div class="split-header__anim-swipe"></div>
					</div>
					<div class="split-header__hero__image-cont">
					 <img src="<?php echo esc_url($settings['image']['url']); ?>" />
					 <div class="split-header__anim-swipe"></div>
					</div>
					<div class="split-header__hero__image-cont">
					 <img src="<?php echo esc_url($settings['image']['url']); ?>" />
					 <div class="split-header__anim-swipe"></div>
					</div>
				</div>
			</div>
			 <!--<div class="split-header__spacer"></div>-->
		</section>
	</section>

<?php			
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {}
	
}
