<?php
$html_id = pxl_get_element_id($settings);
?>
<?php if(isset($settings['lists_lv1']) && !empty($settings['lists_lv1']) && count($settings['lists_lv1'])): ?>
    <div class="pxl-meta pxl-meta1">
    	<div class="pxl-meta-inner">
    		<?php foreach ($settings['lists_lv1'] as $key => $value):
    		 	?>
	            <div class="pxl-item <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
					<?php if(!empty($value['number_lv1'])): ?>
						<div class="pxl-item-number">
		                    <?php echo pxl_print_html($value['number_lv1'])?>
		                </div>
	                <?php endif; ?>
	                <div class="pxl-item-content">
	                	<?php if(!empty($value['title_lv1'])): ?>
							<h5 class="pxl-item-title">
			                    <?php echo pxl_print_html($value['title_lv1'])?>
			                </h5>
		                <?php endif; ?>

		                <?php if(!empty($value['desc_lv1'])): ?>
			                <div class="pxl-item-desc">
			                    <?php echo pxl_print_html($value['desc_lv1'])?>
			                </div>
		                <?php endif; ?>
	                </div>
	            </div>
	        <?php endforeach; ?>
    	</div>
    </div>
<?php endif; ?>