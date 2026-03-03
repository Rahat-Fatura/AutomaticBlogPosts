<?php
$col_xs = $widget->get_setting('col_xs', '');
$col_sm = $widget->get_setting('col_sm', '');
$col_md = $widget->get_setting('col_md', '');
$col_lg = $widget->get_setting('col_lg', '');
$col_xl = $widget->get_setting('col_xl', '');

$col_xl = 12 / intval($col_xl);
$col_lg = 12 / intval($col_lg);
$col_md = 12 / intval($col_md);
$col_sm = 12 / intval($col_sm);
$col_xs = 12 / intval($col_xs);

$grid_sizer = "col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
?>
<?php if(isset($settings['team']) && !empty($settings['team']) && count($settings['team'])):
$image_size = !empty($settings['img_size']) ? $settings['img_size'] : '500x500';
?>
    <div class="pxl-grid pxl-team-grid pxl-team-grid1" data-layout="<?php echo esc_attr($settings['data_layout']); ?>">
        <div class="pxl-grid-inner pxl-grid-masonry row" data-gutter="15">
            <?php foreach ($settings['team'] as $key => $value):
                $title = isset($value['title']) ? $value['title'] : '';
                $position = isset($value['position']) ? $value['position'] : '';
                $social = isset($value['social']) ? $value['social'] : '';
                $image = isset($value['image']) ? $value['image'] : '';
                ?>
                <div class="<?php echo esc_attr($item_class); ?>">
                    <div class="pxl-item--inner <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                        <?php if(!empty($image['id'])) { 
                            $img = pxl_get_image_by_size( array(
                                'attach_id'  => $image['id'],
                                'thumb_size' => $image_size,
                                'class' => 'no-lazyload',
                            ));
                            $thumbnail = $img['thumbnail'];
                            ?>
                            <div class="pxl-item--image">
                                <div class="pxl-img">
                                    <?php echo wp_kses_post($thumbnail); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="pxl-item-content">
                            <h4 class="pxl-item--title">
                                <?php echo pxl_print_html($title); ?>
                            </h4>
                            <div class="pxl-item--position"><?php echo pxl_print_html($position); ?></div>
                            <?php if(!empty($social)): ?>
                                <div class="pxl-social--wrap">
                                    <?php  $team_social = json_decode($social, true); ?>
                                    <div class="pxl-social--icon">
                                        <?php foreach ($team_social as $value): ?>
                                            <a href="<?php echo esc_url($value['url']); ?>" target="_blank"><i class="<?php echo esc_attr($value['icon']); ?>"></i></a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                   </div>
                </div>
            <?php endforeach; ?>
            <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
        </div>
    </div>
<?php endif; ?>