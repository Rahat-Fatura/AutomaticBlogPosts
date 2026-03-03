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

class amedea__easter_special extends Widget_Base {

	/**
	* Retrieve the widget name.
	*
	* @since 1.0.0
	*
	* @return string Widget name.
	*/

	public function get_name() {
		return 'easter_special';
	}
	
	/**
	* Retrieve the widget title.
	*
	* @since 1.0.0
	*
	* @return string Widget title.
	*/

	public function get_title() {
		return esc_html__( 'Easter Special', 'amedea' );
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
		return [ 'easter-special' ];
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
		return [ 'kiss-christmas-special-category' ];
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
				'default' => esc_html__( 'Merry Christmas!', 'amedea' ),
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
	<section class="easter-special-container">
	
		<div class="easter-special__cloud1"></div>
		<div class="easter-special__cloud2"></div>
		<div class="easter-special__cloud3"></div>
		<div class="easter-special__cloud4"></div>
		<div class="easter-special__cloud5"></div>
		<div class="easter-special__cloud6"></div>
		<div class="easter-special__cloud7"></div>
		<div class="easter-special__cloud8"></div>
		<div class="easter-special__cloud9"></div>
		<div class="easter-special__cloud10"></div>
		<div class="easter-special__cloud11"></div>
		<div class="easter-special__theman">
		  <div class="easter-special__hairleft"></div>
		  <div class="easter-special__hairright"></div>
		  <div class="easter-special__head">
			<div class="easter-special__hairline"></div>
			<div class="easter-special__bangsright"></div>
			<div class="easter-special__bangsleft"></div>
			<div class="easter-special__forehead1"></div>
			<div class="easter-special__forehead2"></div>
			<div class="easter-special__eyes"></div>
			<div class="easter-special__mouth"></div>
			<div class="easter-special__cheeks"></div>
			<div class="easter-special__beard"></div>
		  </div>
		  <div class="easter-special__torso">
			<div class="easter-special__egg"></div>
			<div class="easter-special__hand"></div>
			<div class="easter-special__arm1"></div>
			<div class="easter-special__arm2"></div>
		  </div>
		  <div class="easter-special__foot1"></div>
		  <div class="easter-special__foot2"></div>
		  <div class="easter-special__halo"></div>
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