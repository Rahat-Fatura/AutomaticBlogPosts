<?php 
if ( ! empty( $settings['image_link']['url'] ) ) {
    $widget->add_render_attribute( 'image_link', 'href', $settings['image_link']['url'] );

    if ( $settings['image_link']['is_external'] ) {
        $widget->add_render_attribute( 'image_link', 'target', '_blank' );
    }

    if ( $settings['image_link']['nofollow'] ) {
        $widget->add_render_attribute( 'image_link', 'rel', 'nofollow' );
    }
}
$html_id = pxl_get_element_id($settings); 
if($settings['img_effect'] == 'pxl-image-parallax') { wp_enqueue_script( 'pxl-parallax-move-mouse'); }
?>
<div id="<?php echo esc_attr($html_id) ?>" class="pxl-image-single <?php if(!empty($settings['img_effect'])) { echo esc_attr($settings['img_effect']); } ?> <?php echo esc_attr($settings['style'].' '.$settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms" <?php if($settings['img_effect'] == 'pxl-image-tilt') : ?>data-maxtilt="<?php echo esc_attr($settings['max_tilt']); ?>" data-speedtilt="<?php echo esc_attr($settings['speed_tilt']); ?>" data-perspectivetilt="<?php echo esc_attr($settings['perspective_tilt']); ?>"<?php endif; ?>>
    <div class="pxl-item--inner <?php if($settings['style'] == 'style-divider') : ?>wow<?php endif;?>" data-wow-delay="120ms">
        <?php if($settings['source_type'] == 's_img' && !empty($settings['image']['id']) || $settings['source_type'] == 'f_img' && has_post_thumbnail()) : 
            $image_size = !empty($settings['img_size']) ? $settings['img_size'] : 'full';
            if(!empty($settings['image']['id'])) : $img_id = $settings['image']['id']; endif;
            if ($settings['source_type'] == 'f_img' && has_post_thumbnail()) {
                $img_id = get_post_thumbnail_id(get_the_ID());
            }
            $img  = pxl_get_image_by_size( array(
                'attach_id'  => $img_id,
                'thumb_size' => $image_size,
                'class' => 'no-lazyload'
            ) );
            $thumbnail    = $img['thumbnail'];
            $thumbnail_url    = $img['url']; ?>

            <?php switch ($settings['image_type']) {
                case 'bg': ?>
                    <div class="pxl-item--bg bg-image <?php echo esc_attr($settings['pxl_animate_img']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_img_delay']); ?>ms" style="background-image: url(<?php echo esc_url($thumbnail_url); ?>);"></div>
                    <?php break;

                default: ?>
                    <div class="pxl-item--image <?php echo esc_attr($settings['pxl_animate_img']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_img_delay']); ?>ms" data-parallax-value="<?php echo esc_attr($settings['parallax_value']); ?>">
                        <?php if ( ! empty( $settings['image_link']['url'] ) ) { ?><a <?php pxl_print_html($widget->get_render_attribute_string( 'image_link' )); ?>><?php } ?>
                            <?php if ( ! empty( $img_id ) ) { echo wp_kses_post($thumbnail); } ?>
                        <?php if ( ! empty( $settings['image_link']['url'] ) ) { ?></a><?php } ?>
                    </div>
                    <?php break;
            } ?>
        <?php endif; ?>
    </div>
</div>