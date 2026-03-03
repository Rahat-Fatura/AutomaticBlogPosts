<?php
$html_id = pxl_get_element_id($settings);
?>
<div class="pxl-service-sidebar pxl-service-sidebar1">
	<div class="pxl-item-inner <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
		<?php if(isset($settings['lists_post']) && !empty($settings['lists_post']) && count($settings['lists_post'])): ?>
	    	<?php foreach ($settings['lists_post'] as $key => $value):
	            $link_key = $widget->get_repeater_setting_key( 'link', 'value', $key );
		            if ( ! empty( $value['link']['url'] ) ) {
		                $widget->add_render_attribute( $link_key, 'href', $value['link']['url'] );

		                if ( $value['link']['is_external'] ) {
		                    $widget->add_render_attribute( $link_key, 'target', '_blank' );
		                }

		                if ( $value['link']['nofollow'] ) {
		                    $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
		                }
		            }
		            $link_attributes = $widget->get_render_attribute_string( $link_key ); 
    		 	?>
	            <div class="pxl-item">
                    <a class="pxl-item-link" <?php echo implode( ' ', [ $link_attributes ] ); ?>>
	                    <span class="pxl-item-title">
		                    <?php echo pxl_print_html($value['title_list'])?>
		                </span>
		                <span class="pxl-item--arrow">
							<i class="caseicon-long-arrow-right"></i>
						</span>
					</a>
	            </div>
		    <?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>