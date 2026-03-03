<?php
$html_id = pxl_get_element_id($settings);
if ( ! empty( $settings['link']['url'] ) ) {
    $widget->add_render_attribute( 'link_inner', 'href', $settings['link']['url'] );

    if ( $settings['link']['is_external'] ) {
        $widget->add_render_attribute( 'link_inner', 'target', '_blank' );
    }

    if ( $settings['link']['nofollow'] ) {
        $widget->add_render_attribute( 'link_inner', 'rel', 'nofollow' );
    }
}
?>

<div id="<?php echo esc_attr($html_id); ?>" class="pxl-banner-box pxl-banner-box1 <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
    <div class="pxl-banner-inner <?php echo esc_attr($settings['style']); ?>">
        <a class="pxl-inner-link" <?php pxl_print_html($widget->get_render_attribute_string( 'link_inner' )); ?>></a>
        <div class="row">
            <div class="col-sm-6">
                <div class="pxl-item-content">
                    <?php if (!empty($settings['title'])): ?>
                        <h3 class="pxl-item-title">
                            <?php echo esc_html($settings['title']); ?>
                        </h3>
                    <?php endif; ?>
                    <?php if (!empty($settings['desc'])): ?>
                        <div class="pxl-item-desc">
                            <?php echo esc_html($settings['desc']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="pxl-inner-section">
                    <?php if (!empty($settings['image']['id'])) :
                        $image_size = !empty($settings['img_size']) ? $settings['img_size'] : '1000x1000';
                        $img = pxl_get_image_by_size([
                            'attach_id'  => $settings['image']['id'],
                            'thumb_size' => $image_size,
                        ]);
                        ?>
                        <div class="pxl-item--img">
                            <?php echo pxl_print_html($img['thumbnail']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($settings['number'])): ?>
                        <div class="pxl-item-number">
                            <?php echo esc_html($settings['number']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>