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

class amedea__thanksgiving_minimal extends Widget_Base {

	/**
	* Retrieve the widget name.
	*
	* @since 1.0.0
	*
	* @return string Widget name.
	*/

	public function get_name() {
		return 'thanksgiving_minimal';
	}
	
	/**
	* Retrieve the widget title.
	*
	* @since 1.0.0
	*
	* @return string Widget title.
	*/

	public function get_title() {
		return esc_html__( 'Thanksgiving Minimal', 'amedea' );
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
		return [ 'tweenmax' , 'lettering' , 'amedea-thanksgiving-minimal' ];
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
		return [ 'thanksgiving-special' ];
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
		return [ 'kiss-thanksgiving-special-category' ];
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
			'mode',
			array(
			  'label'       => esc_html__('Mode', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => '',
			  'label_block' => true,
			  'options' => array(
				'' => esc_html__('Light Mode', 'amedea'),
				'dark-mode' => esc_html__('Dark Mode', 'amedea'),
			  )
			)
		  );
		  
		$this->add_control(
			'message',
			[
				'label' => esc_html__( 'Message', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Happy Thanksgiving', 'amedea' ),
				'placeholder' => esc_html__( '', 'amedea' ),
			]
		);
		
		$this->add_control(
			'reply',
			[
				'label' => esc_html__( 'Reply', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Replay &#8635;', 'amedea' ),
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
	<section class="thanksgiving--css-container thanksgiving--css__minimal <?php echo esc_html($settings['mode']); ?>">
	 <div id="thanksgiving-css__minimal-container">
			<div id="thanksgiving-css__text-container">
			<h1 class="thanksgiving-css__h1"><?php echo esc_html($settings['message']); ?></h1>
			<h2 class="thanksgiving-css__h2"><span>20</span><span>25</span></h2>
		</div>
		<div id="thanksgiving-css__sun-container">

			<svg viewBox="0 0 150 145" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g id="thanksgiving-css__inner-sun" fill-rule="evenodd">
					<circle id="thanksgiving-css__sun" cx="61" cy="82" r="61"></circle>
					<circle class="thanksgiving-css__dot" cx="10" cy="19" r="3"></circle>
					<circle class="thanksgiving-css__dot" cx="148.5" cy="1.5" r="1.5"></circle>
					<circle class="thanksgiving-css__dot" cx="95" cy="144" r="1"></circle>
				</g>
			</svg>
		</div>
		<div id="thanksgiving-css__rays-container" data-depth='0.5'>
			<svg viewBox="0 0 404 229" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g id="thanksgiving-css__rays" fill-rule="evenodd">
					<line x1="31" y1="226" x2="3" y2="217" class="thanksgiving-css__ray"></line>
					<line x1="54" y1="184" x2="17" y2="147" class="thanksgiving-css__ray"></line>
					<line x1="114" y1="155" x2="59" y2="60" class="thanksgiving-css__ray"></line>
					<line x1="173" y1="137" x2="145" y2="3" class="thanksgiving-css__ray"></line>
					<line x1="231" y1="137" x2="259" y2="3" class="thanksgiving-css__ray"></line>
					<line x1="290" y1="155" x2="345" y2="60" class="thanksgiving-css__ray"></line>
					<line x1="350" y1="184" x2="387" y2="147" class="thanksgiving-css__ray"></line>
					<line x1="373" y1="226" x2="401" y2="217" class="thanksgiving-css__ray"></line>
				</g>
			</svg>
		</div>

		<div id="thanksgiving-css__turkey-container" data-depth='0.5'>
			<svg viewBox="0 0 172 186" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g id="thanksgiving-css__turkey" fill-rule="evenodd">
					<path d="M167.206001,70.3765841 C167.206001,40.3765841 132.206001,44.3765841 132.206001,64.3765841 C132.206001,84.3765841 157.206001,84.3765841 157.206001,116.376584 C157.206001,148.376584 124.206001,153.376584 115.206001,148.376584" id="thanksgiving-css__neck"></path>
					<path d="M118.206001,133.376584 C118.206001,153.376584 95.2060007,173.376584 51.2060007,166.376584 C77.2060007,150.376584 77.2060007,143.376584 80.2060007,130.376584" id="thanksgiving-css__thanksgiving-css__wing"></path>
					<line x1="108.206001" y1="155.376584" x2="108.206001" y2="181.376584" id="thanksgiving-css__leg1"></line>
					<line x1="88.2060007" y1="165.376584" x2="88.2060007" y2="181.376584" id="thanksgiving-css__leg2"></line>
					<path d="M75.2060007,145.376584 C58.2060007,159.376584 29.2060007,142.376584 29.2060007,103.376584 C29.2060007,64.3765841 59.2060007,46.3765841 63.2060007,44.3765841 C75.2060007,48.3765841 81.2060007,63.3765841 81.2060007,71.3765841" id="thanksgiving-css__tail"></path>
					<path d="M57.2060007,87.3765841 C68.539334,74.7099174 82.539334,69.3765841 99.2060007,71.3765841 C115.872667,73.3765841 127.872667,82.3765841 135.206001,98.3765841" id="thanksgiving-css__back"></path>
					<path d="M111.348958,72.982222 C119.139287,43.9083194 101.885541,14.0239553 72.8116387,6.23362654 C43.737736,-1.55670219 13.8533719,15.6970435 6.06304317,44.7709461 C-0.148643244,67.9532754 9.56221456,91.6508807 28.491812,104.252432" id="thanksgiving-css__feather"></path>
				</g>
			</svg>
		</div>

		<div id="thanksgiving-css__leaf-container">
			<svg viewBox="0 0 141 73" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g id="thanksgiving-css__leaf" fill-rule="evenodd">
					<path d="M137,10 C114.866666,29.3333333 92.533333,39 70,39 C47.466667,39 24.8000003,29.3333333 2,10" id="thanksgiving-css__stem"></path>
					<path d="M114,27 C99.3333333,35 95,46.3333333 101,61 C117,56.3333333 121.333333,45 114,27 Z"></path>
					<path d="M113.612903,27 C98.9462366,35 94.6129032,46.3333333 100.612903,61 C116.612903,56.3333333 120.946237,45 113.612903,27 Z"></path>
					<path d="M114,27 C107.333333,12.3333333 97,8.33333333 83,15 C87,28.3333333 97.3333333,32.3333333 114,27 Z"></path>
					<path d="M76.2997601,38.1183854 C59.4332534,40.0394618 51.6666667,48.6666667 53,64 C68.3333333,65.3333333 76.09992,56.7061285 76.2997601,38.1183854 Z"></path>
					<path d="M76.65867,38.2633944 C75.0253544,21.6368075 66.2861342,13.219667 50.4410096,13.0119729 C48.4850009,29.5634625 57.224221,37.980603 76.65867,38.2633944 Z"></path>
					<path d="M39.3648737,32.3926122 C20.4549579,30.1308707 9.55644378,35.4642198 6.66933133,48.3926594 C21.5536496,55.8916492 32.4521637,50.5583001 39.3648737,32.3926122 Z"></path>
					<path d="M39.6479783,32.6566116 C45.2159928,16.8855372 40.3333333,6.66666667 25,2 C19,16.6666667 23.8826594,26.8855372 39.6479783,32.6566116 Z"></path>
				</g>
				<circle class="thanksgiving-css__dot" cx="9.5" cy="2.5" r="2.5"></circle>
				<circle class="thanksgiving-css__dot" cx="118.5" cy="6.5" r="1.5"></circle>
				<circle class="thanksgiving-css__dot" cx="86.5" cy="70.5" r="2.5"></circle>

			</svg>
			<span></span>
		</div>
	 </div>

	 <button class="thanksgiving-css__meta" id="thanksgiving-css__color-toggle"></button>
	 <button class="thanksgiving-css__meta" id="thanksgiving-css__replay"><?php echo esc_html($settings['reply']); ?></button>
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