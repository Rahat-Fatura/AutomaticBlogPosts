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

class amedea__halloween_special extends Widget_Base {

	/**
	* Retrieve the widget name.
	*
	* @since 1.0.0
	*
	* @return string Widget name.
	*/

	public function get_name() {
		return 'halloween_special';
	}
	
	/**
	* Retrieve the widget title.
	*
	* @since 1.0.0
	*
	* @return string Widget title.
	*/

	public function get_title() {
		return esc_html__( 'Halloween Special', 'halloween-special' );
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
		return [ 'halloween-special' ];
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
		return [ 'kiss-halloween-special-category' ];
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
				'label' => esc_html__( 'Configuration', 'halloween-special' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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
	<section class="halloween--css">
<div class="halloween--css__container">
  <div class="halloween--css__shelf-one">
    <div class="halloween--css__shelf halloween--css__shadow">
      <div class="halloween--css__shelf-hooks"></div>
      <div class="halloween--css__base"></div>
      <div class="halloween--css__crystal-ball">
        <div class="halloween--css__stand"></div>
        <div class="halloween--css__ball"></div>
      </div>
      <div class="halloween--css__bottle halloween--css__bottle-1">
        <div class="halloween--css__bowl"></div>
        <div class="halloween--css__bottle-neck"></div>
        <div class="halloween--css__bottle-top"></div>
      </div>
      <div class="halloween--css__drippings">
        <div class="halloween--css__drip"></div>
      </div>
      <div class="halloween--css__bottle halloween--css__bottle-3">
        <div class="halloween--css__bowl"></div>
        <div class="halloween--css__bottle-neck"></div>
        <div class="halloween--css__bottle-top"></div>
      </div>
    </div>
    <div class="halloween--css__shelf">
      <div class="halloween--css__shelf-hooks"></div>
      <div class="halloween--css__base"></div>
      <div class="halloween--css__crystal-ball">
        <div class="halloween--css__stand"></div>
        <div class="halloween--css__ball">
          <div class="halloween--css__ball-highlight"></div>
        </div>
      </div>
      <div class="halloween--css__bottle halloween--css__bottle-1">
        <div class="halloween--css__bowl">
          <div class="halloween--css__bowl-in">
            <div class="halloween--css__liquid">
              <div class="halloween--css__bottle-bubble"></div>
              <div class="halloween--css__bottle-bubble"></div>
              <div class="halloween--css__bottle-bubble"></div>
              <div class="halloween--css__bottle-bubble"></div>
              <div class="halloween--css__bottle-bubble"></div>
            </div>
            <div class="halloween--css__bottle-reflection"></div>
          </div>
        </div>
        <div class="halloween--css__bottle-neck"></div>
        <div class="halloween--css__bottle-top"></div>
      </div>
      <div class="halloween--css__drippings">
        <div class="halloween--css__drip"></div>
      </div>
      <div class="halloween--css__bottle halloween--css__bottle-3">
        <div class="halloween--css__bowl">
          <div class="halloween--css__bowl-in">
            <div class="halloween--css__liquid"></div>
          </div>
        </div>
        <div class="halloween--css__bottle-neck"></div>
        <div class="halloween--css__bottle-top"></div>
      </div>
    </div>
  </div>
  <div class="halloween--css__shelf-two">
    <div class="halloween--css__shelf shadow">
      <div class="halloween--css__shelf-hooks"></div>
      <div class="halloween--css__base"></div>
      <div class="halloween--css__skull">
        <div class="halloween--css__head"></div>
        <div class="halloween--css__teeth"></div>
      </div>
      <div class="halloween--css__candles">
        <div class="halloween--css__candle">
          <div class="halloween--css__candle-wax"></div>
          <div class="halloween--css__flame">
            <div class="halloween--css__flame-in"></div>
          </div>
        </div>
        <div class="halloween--css__candle">
          <div class="halloween--css__candle-wax"></div>
          <div class="halloween--css__flame">
            <div class="halloween--css__flame-in"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="halloween--css__shelf">
      <div class="halloween--css__shelf-hooks"></div>
      <div class="halloween--css__base"></div>
      <div class="halloween--css__skull">
        <div class="halloween--css__head">
          <div class="halloween--css__skull-stain"></div>
          <div class="halloween--css__eye"></div>
          <div class="halloween--css__eye"></div>
          <div class="halloween--css__nose"></div>
        </div>
        <div class="halloween--css__teeth">
          <div class="halloween--css__tooth"></div>
          <div class="halloween--css__tooth"></div>
          <div class="halloween--css__tooth"></div>
        </div>
      </div>
      <div class="halloween--css__candles">
        <div class="halloween--css__candle">
          <div class="halloween--css__candle-wax">
            <div class="halloween--css__wax-reflection"></div>
            <div class="halloween--css__wax-reflection-top"></div>
          </div>
          <div class="halloween--css__candle-reflection"></div>
          <div class="halloween--css__flame">
            <div class="halloween--css__flame-in"></div>
          </div>
        </div>
        <div class="halloween--css__candle">
          <div class="halloween--css__candle-wax">
            <div class="halloween--css__wax-reflection"></div>
            <div class="halloween--css__wax-reflection-top"></div>
          </div>
          <div class="halloween--css__candle-reflection"></div>
          <div class="halloween--css__flame">
            <div class="halloween--css__flame-in"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="halloween--css__shelf-three">
    <div class="halloween--css__shelf shadow">
      <div class="halloween--css__shelf-hooks"></div>
      <div class="halloween--css__base"></div>
      <div class="halloween--css__spider-group">
        <div class="halloween--css__thread"></div>
        <div class="halloween--css__spider">
          <div class="halloween--css__spider-leg"></div>
          <div class="halloween--css__spider-leg"></div>
          <div class="halloween--css__spider-leg"></div>
          <div class="halloween--css__spider-leg"></div>
          <div class="halloween--css__spider-leg"></div>
          <div class="halloween--css__spider-leg"></div>
        </div>
      </div>
      <div class="halloween--css__books">
        <div class="halloween--css__book"></div>
        <div class="halloween--css__book"></div>
        <div class="halloween--css__book"></div>
        <div class="halloween--css__book-up"></div>
      </div>
      <div class="halloween--css__candy-bowl">
        <div class="halloween--css__candy-bowl-top"></div>
      </div>
    </div>
    <div class="halloween--css__shelf">
      <div class="halloween--css__shelf-hooks"></div>
      <div class="halloween--css__base"></div>
      <div class="halloween--css__spider-group">
        <div class="halloween--css__thread"></div>
        <div class="halloween--css__spider">
          <div class="halloween--css__spider-leg"></div>
          <div class="halloween--css__spider-leg"></div>
          <div class="halloween--css__spider-leg"></div>
          <div class="halloween--css__spider-leg"></div>
          <div class="halloween--css__spider-leg"></div>
          <div class="halloween--css__spider-leg"></div>
        </div>
      </div>
      <div class="halloween--css__books">
        <div class="halloween--css__book">
          <div class="halloween--css__details"></div>
        </div>
        <div class="halloween--css__book">
          <div class="halloween--css__details"></div>
        </div>
        <div class="halloween--css__book">
          <div class="halloween--css__details"></div>
        </div>
        <div class="halloween--css__book-up">
          <div class="halloween--css__details"></div>
        </div>
      </div>
      <div class="halloween--css__candy-bowl">
        <div class="halloween--css__candy-bowl-top"></div>
        <div class="halloween--css__candy-bowl-in">
          <div class="halloween--css__candy-1"></div>
          <div class="halloween--css__candy-1"></div>
          <div class="halloween--css__candy-1"></div>
          <div class="halloween--css__candy-1"></div>
          <div class="halloween--css__candy-2">
            <div class="halloween--css__candy-reflection"></div>
          </div>
          <div class="halloween--css__candy-2">
            <div class="halloween--css__candy-reflection"></div>
          </div>
          <div class="halloween--css__candy-2">
            <div class="halloween--css__candy-reflection"></div>
          </div>
          <div class="halloween--css__candy-2">
            <div class="halloween--css__candy-reflection"></div>
          </div>
          <div class="halloween--css__candy-3"></div>
          <div class="halloween--css__candy-3"></div>
          <div class="halloween--css__candy-3"></div>
          <div class="halloween--css__candy-3"></div>
          <div class="halloween--css__candy-4">
            <div class="halloween--css__candy-reflection"></div>
          </div>
          <div class="halloween--css__candy-4">
            <div class="halloween--css__candy-reflection"></div>
          </div>
          <div class="halloween--css__candy-4">
            <div class="halloween--css__candy-reflection"></div>
          </div>
          <div class="halloween--css__candy-4">
            <div class="halloween--css__candy-reflection"></div>
          </div>
        </div>
        <div class="halloween--css__candy-bowl-reflection"></div>
      </div>
    </div>
  </div>
  <div class="halloween--css__witch halloween--css__shadow">
    <div class="halloween--css__middle"></div>
    <div class="halloween--css__right-arm">
      <div class="halloween--css__bottle halloween--css__bottle-1">
        <div class="halloween--css__bowl">
          <div class="halloween--css__bowl-in"></div>
        </div>
        <div class="halloween--css__bottle-neck"></div>
        <div class="halloween--css__bottle-top">
          <div class="halloween--css__pink-liquid"></div>
        </div>
      </div>
      <div class="halloween--css__right-hand"></div>
    </div>
    <div class="halloween--css__head-group">
      <div class="halloween--css__hair-back">
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
      </div>
      <div class="halloween--css__witch-head"></div>
      <div class="halloween--css__hat">
        <div class="halloween--css__hat-top">
          <div class="halloween--css__hat-top-in"></div>
        </div>
        <div class="halloween--css__hat-band"></div>
        <div class="halloween--css__hat-bottom"></div>
      </div>
    </div>
  </div>
  <div class="halloween--css__witch">
    <div class="halloween--css__bottom"></div>
    <div class="halloween--css__middle"></div>
    <div class="halloween--css__right-arm">
      <div class="halloween--css__bottle halloween--css__bottle-1">
        <div class="halloween--css__bowl">
          <div class="halloween--css__bowl-in">
            <div class="halloween--css__liquid"></div>
            <div class="halloween--css__bottle-reflection"></div>
          </div>
        </div>
        <div class="halloween--css__bottle-neck"></div>
        <div class="halloween--css__bottle-top">
          <div class="halloween--css__pink-liquid"></div>
        </div>
      </div>
      <div class="halloween--css__right-hand"></div>
    </div>
    <div class="halloween--css__head-group">
      <div class="halloween--css__neck"></div>
      <div class="halloween--css__hair-back">
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__hair"></div>
        <div class="halloween--css__witch-ears">
          <div class="halloween--css__witch-ear"></div>
          <div class="halloween--css__witch-ear"></div>
        </div>
      </div>
      <div class="halloween--css__witch-head">
        <div class="halloween--css__cheeks"></div>
        <div class="halloween--css__eyes"></div>
        <div class="halloween--css__eyes"></div>
        <div class="halloween--css__mouth"></div>
        <div class="halloween--css__mouth-hidden"></div>
        <div class="halloween--css__hair-front"></div>
      </div>
      <div class="halloween--css__hat">
        <div class="halloween--css__hat-top">
          <div class="halloween--css__hat-top-in"></div>
        </div>
        <div class="halloween--css__hat-band"></div>
        <div class="halloween--css__hat-stars">
          <div class="halloween--css__hat-star"></div>
          <div class="halloween--css__hat-star"></div>
          <div class="halloween--css__hat-star"></div>
        </div>
        <div class="halloween--css__hat-band-top"></div>
        <div class="halloween--css__hat-bottom"></div>
      </div>
    </div>
  </div>
  <div class="halloween--css__light"></div>
  <div class="halloween--css__cauldron halloween--css__shadow">
    <div class="halloween--css__left-arm"></div>
    <div class="halloween--css__stick-group">
      <div class="halloween--css__stick"></div>
      <div class="halloween--css__hand"></div>
    </div>
    <div class="halloween--css__bubbles">
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
    </div>
    <div class="halloween--css__cauldron-top"></div>
  </div>
  <div class="halloween--css__cauldron">
    <div class="halloween--css__left-arm"></div>
    <div class="halloween--css__stick-group">
      <div class="halloween--css__stick"></div>
      <div class="halloween--css__hand"></div>
    </div>
    <div class="halloween--css__handle"></div>
    <div class="halloween--css__bubbles">
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
      <div class="halloween--css__bubble"></div>
    </div>
    <div class="halloween--css__cauldron-top"></div>
  </div>
  <div class="halloween--css__window-group halloween--css__shadow">
    <div class="halloween--css__window"></div>
  </div>
  <div class="halloween--css__window-group">
    <div class="halloween--css__window">
      <div class="halloween--css__window-reflections"></div>
      <div class="halloween--css__window-reflections"></div>
      <div class="halloween--css__window-reflections"></div>
      <div class="halloween--css__window-in">
        <div class="halloween--css__ghost-group">
          <div class="halloween--css__ghost">
            <div class="halloween--css__bottom">
              <div class="halloween--css__bottom-part"></div>
              <div class="halloween--css__bottom-part"></div>
              <div class="halloween--css__bottom-part"></div>
            </div>
            <div class="halloween--css__eyes"></div>
          </div>
        </div>
        <div class="halloween--css__moon">
          <div class="halloween--css__craters"></div>
        </div>
        <div class="halloween--css__stars">
          <div class="halloween--css__star"></div>
          <div class="halloween--css__star"></div>
          <div class="halloween--css__star"></div>
          <div class="halloween--css__star"></div>
          <div class="halloween--css__star"></div>
        </div>
      </div>
    </div>
    <div class="halloween--css__cat halloween--css__highlight">
      <div class="halloween--css__cat-head">
        <div class="halloween--css__ear">
          <div class="halloween--css__ear-in"></div>
        </div>
        <div class="halloween--css__ear">
          <div class="halloween--css__ear-in"></div>
        </div>
      </div>
      <div class="halloween--css__cat-body"></div>
      <div class="halloween--css__cat-paw"></div>
      <div class="halloween--css__cat-neck">
        <div class="halloween--css__neck">
          <div class="halloween--css__neck">
            <div class="halloween--css__neck">
              <div class="halloween--css__neck">
                <div class="halloween--css__neck">
                  <div class="halloween--css__neck">
                    <div class="halloween--css__neck">
                      <div class="halloween--css__neck">
                        <div class="halloween--css__neck"></div>
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
    <div class="halloween--css__cat">
      <div class="halloween--css__cat-head">
        <div class="halloween--css__ear">
          <div class="halloween--css__ear-in"></div>
        </div>
        <div class="halloween--css__ear">
          <div class="halloween--css__ear-in"></div>
        </div>
      </div>
      <div class="halloween--css__cat-body"></div>
      <div class="halloween--css__cat-paw"></div>
      <div class="halloween--css__cat-neck">
        <div class="halloween--css__neck">
          <div class="halloween--css__neck">
            <div class="halloween--css__neck">
              <div class="halloween--css__neck">
                <div class="halloween--css__neck">
                  <div class="halloween--css__neck">
                    <div class="halloween--css__neck">
                      <div class="halloween--css__neck">
                        <div class="halloween--css__neck"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="halloween--css__cat-tail halloween--css__highlight">
        <div class="halloween--css__tail">
          <div class="halloween--css__tail">
            <div class="halloween--css__tail">
              <div class="halloween--css__tail">
                <div class="halloween--css__tail">
                  <div class="halloween--css__tail">
                    <div class="halloween--css__tail">
                      <div class="halloween--css__tail halloween--css__last"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="halloween--css__cat-tail halloween--css__shadow">
        <div class="halloween--css__tail">
          <div class="halloween--css__tail">
            <div class="halloween--css__tail">
              <div class="halloween--css__tail">
                <div class="halloween--css__tail">
                  <div class="halloween--css__tail">
                    <div class="halloween--css__tail">
                      <div class="halloween--css__tail halloween--css__last"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="halloween--css__cat-tail">
        <div class="halloween--css__tail">
          <div class="halloween--css__tail">
            <div class="halloween--css__tail">
              <div class="halloween--css__tail">
                <div class="halloween--css__tail">
                  <div class="halloween--css__tail">
                    <div class="halloween--css__tail">
                      <div class="halloween--css__tail halloween--css__last"></div>
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
  <div class="halloween--css__broom halloween--css__shadow">
    <div class="halloween--css__broom-stick">
      <div class="halloween--css__bat">
        <div class="halloween--css__bat-legs"></div>
        <div class="halloween--css__bat-body"></div>
        <div class="halloween--css__bat-wing"></div>
        <div class="halloween--css__bat-wing"></div>
        <div class="halloween--css__bat-ear"></div>
        <div class="halloween--css__bat-ear"></div>
      </div>
    </div>
    <div class="halloween--css__broom-hook"></div>
    <div class="halloween--css__broom-hook"></div>
    <div class="halloween--css__broom-hair">
      <div class="halloween--css__broom-hair-top"></div>
      <div class="halloween--css__broom-hair-bottom"></div>
      <div class="halloween--css__broom-hair-band"></div>
    </div>
  </div>
  <div class="halloween--css__broom">
    <div class="halloween--css__broom-stick">
      <div class="halloween--css__broom-stick-shadow"></div>
      <div class="halloween--css__bat">
        <div class="halloween--css__bat-legs"></div>
        <div class="halloween--css__bat-body"></div>
        <div class="halloween--css__bat-wing"></div>
        <div class="halloween--css__bat-wing"></div>
        <div class="halloween--css__bat-ear"></div>
        <div class="halloween--css__bat-ear"></div>
      </div>
    </div>
    <div class="halloween--css__broom-hook"></div>
    <div class="halloween--css__broom-hook"></div>
    <div class="halloween--css__broom-hair">
      <div class="halloween--css__broom-hair-top"></div>
      <div class="halloween--css__broom-hair-bottom"></div>
      <div class="halloween--css__broom-lines"></div>
      <div class="halloween--css__broom-hair-bottom-reflection"></div>
      <div class="halloween--css__broom-hair-band"></div>
      <div class="halloween--css__broom-details"></div>
      <div class="halloween--css__broom-details"></div>
    </div>
  </div>
  <div class="halloween--css__pumpkins halloween--css__shadow">
    <div class="halloween--css__pumpkin">
      <div class="halloween--css__top"></div>
    </div>
    <div class="halloween--css__pumpkin">
      <div class="halloween--css__top"></div>
    </div>
  </div>
  <div class="halloween--css__pumpkins">
    <div class="halloween--css__pumpkin">
      <div class="halloween--css__top"></div>
    </div>
    <div class="halloween--css__pumpkin">
      <div class="halloween--css__top"></div>
    </div>
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