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

class amedea__christmas_special2 extends Widget_Base {

	/**
	* Retrieve the widget name.
	*
	* @since 1.0.0
	*
	* @return string Widget name.
	*/

	public function get_name() {
		return 'christmas_special2';
	}
	
	/**
	* Retrieve the widget title.
	*
	* @since 1.0.0
	*
	* @return string Widget title.
	*/

	public function get_title() {
		return esc_html__( 'Christmas Special 2', 'amedea' );
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
		return [ 'christmas-special' ];
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
	<section class="christmas--css-container christmas--css__special2">
	
	<div class="christmas--css__special2-container">
	  <div class="xmas--css__artboard">
		<div class="xmas--css__deer">
		  <div class="xmas--css__rocking">
			<div class="xmas--css__head">
			  <div class="xmas--css__horns">
				<div class="xmas--css__horn xmas--css__horn-left">
				  <div class="xmas--css__line xmas--css__line-one"></div>
				  <div class="xmas--css__line"></div>
				  <div class="xmas--css__line xmas--css__line-three"></div>
				</div>
				<div class="xmas--css__horn xmas--css__horn-right">
				  <div class="xmas--css__line xmas--css__line-one"></div>
				  <div class="xmas--css__line"></div>
				  <div class="xmas--css__line xmas--css__line-three"></div>
				</div>
			  </div>
			  <div class="xmas--css__ears">
				<div class="xmas--css__ear xmas--css__ear-left"></div>
				<div class="xmas--css__ear xmas--css__ear-right"></div>
			  </div>
			  <div class="xmas--css__eyes">
				<div class="xmas--css__eye xmas--css__eye-left"></div>
				<div class="xmas--css__eye xmas--css__eye-right"></div>
			  </div>
			  <div class="xmas--css__nose"></div>
			</div>
			<div class="xmas--css__deer-body">
			  <div class="xmas--css__shadow"></div>
			  <div class="xmas--css__hooves">
				<div class="xmas--css__hoof-one">
				  <div class="xmas--css__line"></div>
				  <div class="xmas--css__anim-part">
					<div class="xmas--css__circle">
					  <div class="xmas--css__circle">
						<div class="xmas--css__circle">
						  <div class="xmas--css__circle">
							<div class="xmas--css__circle xmas--css__circle-last"></div>
						  </div>
						</div>
					  </div>
					</div>
				  </div>
				</div>
				<div class="xmas--css__hoof-two">
				  <div class="xmas--css__line-one"></div>
				  <div class="xmas--css__line-two"></div>
				</div>
			  </div>
			</div>
			<div class="xmas--css__tail">
			  <div class="xmas--css__circle">
				<div class="xmas--css__circle">
				  <div class="xmas--css__circle">
					<div class="xmas--css__circle">
					  <div class="xmas--css__circle"></div>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		  <div class="xmas--css__legs">
			<div class="xmas--css__leg-left">
			  <div class="xmas--css__anim-part">
				<div class="xmas--css__line"></div>
			  </div>
			</div>
			<div class="xmas--css__leg-right">
			  <div class="xmas--css__anim-part">
				<div class="xmas--css__circle">
				  <div class="xmas--css__circle">
					<div class="xmas--css__circle">
					  <div class="xmas--css__circle">
						<div class="xmas--css__circle">
						  <div class="xmas--css__circle">
							<div class="xmas--css__circle">
							  <div class="xmas--css__circle">
								<div class="xmas--css__circle xmas--css__circle-last"></div>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		<div class="xmas--css__presents">
		  <div class="xmas--css__present xmas--css__present-one"></div>
		  <div class="xmas--css__present xmas--css__present-two"></div>
		  <div class="xmas--css__present xmas--css__present-two xmas--css__present-two-right"></div>
		  <div class="xmas--css__present xmas--css__present-three"></div>
		</div>
		<div class="xmas--css__snow"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
		<div class="xmas--css__snowflake"></div>
	  </div>
	  <div class="christmas--css__message xmas--css__message">
		<h2><?php echo esc_html($settings['message']); ?></h2>
	</div>
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