<?php 
$html_id = pxl_get_element_id($settings); 

if (isset($settings['tabs']) && !empty($settings['tabs']) && count($settings['tabs'])): 
    $tab_bd_ids = [];
    ?>
    <div class="pxl-tabs-pricing pxl-tabs-pricing1 <?php echo esc_attr($settings['style'] . ' ' . $settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
        <div class="pxl-tabs--inner">

            <!-- Toggle Title -->
            <div class="pxl-tabs--title">
                <span class="pxl-tab--monthly"><?php echo wp_kses_post(pxl_print_html($settings['title_monthly'])); ?></span>
                <span class="pxl-tab--check active" data-target="monthly"></span>
                <span class="pxl-tab--yearly"><?php echo wp_kses_post(pxl_print_html($settings['title_yearly'])); ?></span>
            </div>

            <!-- Tabs Content -->
            <div class="pxl-tabs--content">
                <?php 
                foreach ($settings['tabs'] as $key => $content): 
                    $is_active = ($settings['tab_active'] == $key + 1) ? 'active' : '';
                    $display   = ($settings['tab_active'] == $key + 1) ? 'style="display:block;"' : '';
                    $content_id = $html_id . '-' . $content['_id'];
                    ?>
                    
                    <div 
                        id="<?php echo esc_attr($content_id); ?>" 
                        class="<?php echo esc_attr('pxl-tab--content ' . $is_active . ' ' . ($content['content_type'] === 'template' ? 'pxl-tabs--elementor' : '')); ?>" 
                        <?php echo wp_kses_post($display); ?>>
                        
                        <?php 
                        if ($content['content_type'] === 'df' && !empty($content['desc'])) {
                            echo wp_kses_post(pxl_print_html($content['desc'])); 
                        } elseif ($content['content_type'] === 'template' && !empty($content['content_template'])) {
                            $tab_content = Elementor\Plugin::$instance->frontend->get_builder_content_for_display((int)$content['content_template']);
                            $tab_bd_ids[] = (int)$content['content_template'];
                            echo wp_kses_post(pxl_print_html($tab_content));
                        } 
                        ?>        

                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
<?php endif; ?>