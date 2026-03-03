<?php
$html_id = pxl_get_element_id($settings);
?>
<div class="pxl-icon-box pxl-icon-box2">
	<div class="pxl-item-inner <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
        <?php if ( $settings['icon_type'] == 'icon' && !empty($settings['pxl_icon']['value']) ) : ?>
            <div class="pxl-item--icon">
                <?php \Elementor\Icons_Manager::render_icon( $settings['pxl_icon'], [ 'aria-hidden' => 'true', 'class' => '' ], 'i' ); ?>
            </div>
        <?php endif; ?>
        <?php if ( $settings['icon_type'] == 'image' && !empty($settings['icon_image']['id']) ) : ?>
            <div class="pxl-item--icon">
                <?php $img_icon  = pxl_get_image_by_size( array(
                        'attach_id'  => $settings['icon_image']['id'],
                        'thumb_size' => '100x100',
                    ) );
                    $thumbnail_icon    = $img_icon['thumbnail'];
                echo pxl_print_html($thumbnail_icon); ?>
            </div>
        <?php endif; ?>
		<div class="pxl-meta-content">
            <h4 class="pxl-item-title">
                <?php echo esc_attr($settings['title']); ?>
            </h4>
            <div class="pxl-item-desc">
                <?php echo esc_attr($settings['desc']); ?>
            </div>
		</div>
	</div>
</div>