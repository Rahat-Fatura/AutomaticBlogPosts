<?php

if (!class_exists('cyberguard_Footer')) {

    class cyberguard_Footer
    {
        public function getFooter()
        {
            if(is_singular('elementor_library')) return;
            
            $footer_layout = (int)cyberguard()->get_opt('footer_layout');
            
            if ($footer_layout <= 0 || !class_exists('Pxltheme_Core') || !is_callable( 'Elementor\Plugin::instance' )) {
                get_template_part( 'template-parts/footer/default');
            } else {
                $args = [
                    'footer_layout' => $footer_layout
                ];
                get_template_part( 'template-parts/footer/elementor','', $args );
            } 

            // Back To Top
            $back_totop_on = cyberguard()->get_theme_opt('back_totop_on', true); 
            if (isset($back_totop_on) && $back_totop_on) : ?>
                <div class="pxl-scroll-top">
                    <a class="pxl-scroll-top-link" href="#">
                        <span>Scroll to top</span>
                    </a>
                    <div class="scrollbar-v">
                        <svg viewBox="0 0 2 100">
                            <path d="M1,100 L1,0" />
                        </svg>
                    </div>
                </div>
            <?php endif;

            // Mouse Move Animation
            cyberguard_mouse_move_animation();

            // Cookie Policy
            cyberguard_cookie_policy();

            // Subscribe Popup
            cyberguard_subscribe_popup();

            // Page Popup
            cyberguard_page_popup();
            
        }
 
    }
}
 