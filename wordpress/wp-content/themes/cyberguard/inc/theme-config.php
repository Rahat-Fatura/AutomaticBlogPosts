<?php if(!function_exists('cyberguard_configs')){
    function cyberguard_configs($value){
        $configs = [
            'theme_colors' => [
                'primary'   => [
                    'title' => esc_html__('Primary', 'cyberguard'), 
                    'value' => cyberguard()->get_opt('primary_color', '#4a81d3')
                ],
                'secondary'   => [
                    'title' => esc_html__('Secondary', 'cyberguard'), 
                    'value' => cyberguard()->get_opt('secondary_color', '#797a8c')
                ],
                'body-bg'   => [
                    'title' => esc_html__('Body Background Color', 'cyberguard'), 
                    'value' => cyberguard()->get_page_opt('body_bg_color', '#fff')
                ]
            ],
            'link' => [
                'color' => cyberguard()->get_opt('link_color', ['regular' => '#111013'])['regular'],
                'color-hover'   => cyberguard()->get_opt('link_color', ['hover' => '#111013'])['hover'],
                'color-active'  => cyberguard()->get_opt('link_color', ['active' => '#111013'])['active'],
            ],
            'gradient' => [
                'color-from' => cyberguard()->get_opt('gradient_color', ['from' => '#4a81d3'])['from'],
                'color-to' => cyberguard()->get_opt('gradient_color', ['to' => '#514a9d'])['to'],
            ],
               
        ];
        return $configs[$value];
    }
}
if(!function_exists('cyberguard_inline_styles')) {
    function cyberguard_inline_styles() {  
        
        $theme_colors      = cyberguard_configs('theme_colors');
        $link_color        = cyberguard_configs('link');
        $gradient_color    = cyberguard_configs('gradient');
        ob_start();
        echo ':root{';
            
            foreach ($theme_colors as $color => $value) {
                printf('--%1$s-color: %2$s;', str_replace('#', '',$color),  $value['value']);
            }
            foreach ($theme_colors as $color => $value) {
                printf('--%1$s-color-rgb: %2$s;', str_replace('#', '',$color),  cyberguard_hex_rgb($value['value']));
            }
            foreach ($link_color as $color => $value) {
                printf('--link-%1$s: %2$s;', $color, $value);
            }
            foreach ($gradient_color as $color => $value) {
                printf('--gradient-%1$s: %2$s;', $color, $value);
            }
        echo '}';

        return ob_get_clean();
         
    }
}
 