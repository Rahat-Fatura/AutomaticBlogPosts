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

class amedea__valentines_day_special extends Widget_Base {

	/**
	* Retrieve the widget name.
	*
	* @since 1.0.0
	*
	* @return string Widget name.
	*/

	public function get_name() {
		return 'valentines_day_special';
	}
	
	/**
	* Retrieve the widget title.
	*
	* @since 1.0.0
	*
	* @return string Widget title.
	*/

	public function get_title() {
		return esc_html__( 'Valentines Day Special', 'amedea' );
	}
	
	/**
	* Retrieve the widget icon.
	*
	* @since 1.0.0
	*
	* @return string Widget icon.
	*/

	public function get_icon() {
		return 'eicon-nerd-chuckle';
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
		return [ ];
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
		return [ 'valentines-day-special' ];
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
		return [ 'kiss-valentines-day-special-category' ];
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
			'section_content',
			[
				'label' => esc_html__( 'Configuration', 'thanksgiving-special' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		  
		$this->add_control(
			'message',
			[
				'label' => esc_html__( 'Message', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => sprintf( 'Happy Valentines</br> Day!', 'amedea' ),
				'placeholder' => esc_html__( '', 'amedea' ),
			]
		);
		
		$this->add_control(
			'message2',
			[
				'label' => esc_html__( 'Message Hover', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Remember not to eat too much candy —you are sweet enough already.', 'amedea' ),
				'placeholder' => esc_html__( '', 'amedea' ),
			]
		);
		
		$this->add_control(
			'hovertext',
			[
				'label' => esc_html__( 'Info', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '- Hover over the text -', 'amedea' ),
				'placeholder' => esc_html__( '', 'amedea' ),
			]
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
	?>
	<style>.valentines-day-special__text:hover:before {content:"<?php echo esc_html($settings['message2']); ?>";font-size:25px;}</style>
	<section class="valentines-day-special-container">
	<div class="valentines-day-special__happy-valentines">
	<div class="valentines-day-special__valentines-day-card">
	  <div class="valentines-day-special__clouds"></div>
	  <div class="valentines-day-special__hearts">
		<div class="valentines-day-special__heartOne">
		  <div class="valentines-day-special__left-side"></div>
		  <div class="valentines-day-special__right-side"></div>
		</div>
		<div class="valentines-day-special__heartTwo">
		  <div class="valentines-day-special__left-side"></div>
		  <div class="valentines-day-special__right-side"></div>
		</div>
			<div class="valentines-day-special__heartThree">
		  <div class="valentines-day-special__left-side"></div>
		  <div class="valentines-day-special__right-side"></div>
		</div>
		 <div class="valentines-day-special__heartFour">
		  <div class="valentines-day-special__left-side"></div>
		  <div class="valentines-day-special__right-side"></div>
		</div>
		 <div class="valentines-day-special__heartFive">
		 <div class="valentines-day-special__left-side"></div>
		  <div class="valentines-day-special__right-side"></div>
		</div>
	  </div>
	  <div class="valentines-day-special__text"><span><?php echo sprintf($settings['message']); ?></span></div>
	</div>
	<p class="valentines-day-special__hover"><?php echo esc_html($settings['hovertext']); ?></p>
	</div>
	</section>
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