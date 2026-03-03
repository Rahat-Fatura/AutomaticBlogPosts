<?php
if(class_exists('WPCF7') && !empty($settings['form_id'])) : 
	wp_enqueue_script('datetimepicker', get_template_directory_uri() . '/assets/js/libs/datetimepicker.min.js', array('jquery'), '2.3.7', true);
	wp_enqueue_script('pxl-datetimepicker', get_template_directory_uri() . '/assets/js/libs/datetimepicker.pxl.js', array('jquery'), '1.0.0', true);
	wp_enqueue_style('pxl-datetimepicker', get_template_directory_uri() . '/assets/css/libs/datetimepicker.css', array(), '1.0.0');
	?>
    <div class="pxl-contact-form pxl-contact-form1 <?php echo esc_attr($settings['notification_form'].' '.$settings['box_style'].' '.$settings['btn_width'].' '.$settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
        <?php echo do_shortcode('[contact-form-7 id="'.esc_attr( $settings['form_id'] ).'"]'); ?>
    </div>
<?php endif; ?>