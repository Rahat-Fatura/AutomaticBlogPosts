<?php
$widget->add_render_attribute( 'counter', [
    'class' => 'pxl--counter-value '.$settings['effect'].'',
    'data-duration' => $settings['duration'],
    'data-startnumber' => $settings['starting_number'],
    'data-endnumber' => $settings['ending_number'],
    'data-to-value' => $settings['ending_number'],
    'data-delimiter' => $settings['thousand_separator_char'],
] ); ?>
<div class="pxl-counter pxl-counter1 <?php echo esc_attr($settings['style']); ?> <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
    <div class="pxl--item-inner">
        <div class="pxl--counter-meta">
            <div class="pxl--counter-number">
                <span <?php pxl_print_html($widget->get_render_attribute_string( 'counter' )); ?>><?php echo esc_html($settings['starting_number']); ?></span>
                <?php if(!empty($settings['suffix'])) : ?>
                    <span class="pxl--counter-suffix"><?php echo pxl_print_html($settings['suffix']); ?></span>
                <?php endif; ?>
            </div>
            <?php if(!empty($settings['title'])) : ?>
                <div class="pxl--item-title"><?php echo pxl_print_html($settings['title']); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>