<?php
$html_id = pxl_get_element_id($settings);
$select_post_by = $widget->get_setting('select_post_by', '');
$source = $post_ids = [];
if($select_post_by === 'post_selected'){
    $post_ids = $widget->get_setting('source_'.$settings['post_type'].'_post_ids', '');
}else{
    $source  = $widget->get_setting('source_'.$settings['post_type'], '');
}
$orderby = $widget->get_setting('orderby', 'date');
$order = $widget->get_setting('order', 'desc');
$limit = $widget->get_setting('limit', 6);
$settings['layout']    = $settings['layout_'.$settings['post_type']];
extract(pxl_get_posts_of_grid('portfolio', [
    'source' => $source,
    'orderby' => $orderby,
    'order' => $order,
    'limit' => $limit,
    'post_ids' => $post_ids,
]));

$pxl_animate = $widget->get_setting('pxl_animate', '');
$col_xs = $widget->get_setting('col_xs', '');
$col_sm = $widget->get_setting('col_sm', '');
$col_md = $widget->get_setting('col_md', '');
$col_lg = $widget->get_setting('col_lg', '');
$col_xl = $widget->get_setting('col_xl', '');
$col_xxl = $widget->get_setting('col_xxl', '');
if($col_xxl == 'inherit') {
    $col_xxl = $col_xl;
}
$slides_to_scroll = $widget->get_setting('slides_to_scroll', '');

$img_size = $widget->get_setting('img_size');
$arrows = $widget->get_setting('arrows','false');
$pagination = $widget->get_setting('pagination','false');
$pagination_type = $widget->get_setting('pagination_type','bullets');
$pause_on_hover = $widget->get_setting('pause_on_hover');
$autoplay = $widget->get_setting('autoplay');
$autoplay_speed = $widget->get_setting('autoplay_speed', '5000');
$infinite = $widget->get_setting('infinite');
$speed = $widget->get_setting('speed', '500');

$show_excerpt = $widget->get_setting('show_excerpt');
$num_words = $widget->get_setting('num_words', 15);
$show_button = $widget->get_setting('show_button');
$button_text = $widget->get_setting('button_text');

$opts = [
    'slide_direction'               => 'horizontal',
    'slide_percolumn'               => '1', 
    'slide_percolumnfill'           => '1', 
    'slide_mode'                    => 'slide', 
    'slides_to_show'                => $col_xl,
    'slides_to_show_xxl'             => $col_xxl,  
    'slides_to_show_lg'             => $col_lg, 
    'slides_to_show_md'             => $col_md, 
    'slides_to_show_sm'             => $col_sm, 
    'slides_to_show_xs'             => $col_xs, 
    'slides_to_scroll'              => $slides_to_scroll,  
    'slides_gutter'                 => 30, 
    'arrow'                         => $arrows,
    'pagination'                    => $pagination,
    'pagination_type'               => $pagination_type,
    'autoplay'                      => $autoplay,
    'pause_on_hover'                => $pause_on_hover,
    'pause_on_interaction'          => 'true',
    'delay'                         => $autoplay_speed,
    'loop'                          => $infinite,
    'speed'                         => $speed
];

$widget->add_render_attribute( 'carousel', [
    'class'         => 'pxl-swiper-container',
    'dir'           => is_rtl() ? 'rtl' : 'ltr',
    'data-settings' => wp_json_encode($opts)
]); ?>

<?php if (is_array($posts)): ?>
    <div class="pxl-swiper-sliders pxl-portfolio-carousel pxl-portfolio-carousel1 <?php if($arrows == true) { echo 'pxl-arrows-active'; } ?>" <?php if($settings['drap']) : ?>data-cursor-drap="<?php echo esc_html('DRAG', 'cyberguard'); ?>"<?php endif; ?>>
        <div class="pxl-carousel-inner <?php echo esc_attr($pxl_animate); ?>">
            <div <?php pxl_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <div class="pxl-swiper-wrapper">
                    <?php
                        foreach ($posts as $key => $post):
                        $image_size = !empty($img_size) ? $img_size : '1000x1000';
                        $img_id       = get_post_thumbnail_id( $post->ID );
                        $img          = pxl_get_image_by_size( array(
                            'attach_id'  => $img_id,
                            'thumb_size' => $image_size
                        ) );
                        $thumbnail    = $img['thumbnail']; 
                        if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                            <div class="pxl-swiper-slide">
                                <div class="pxl-post--inner">
                                    <span class="pxl-post--overlay"></span>
                                    <div class="pxl-post--featured hover-imge-effect3">
                                        <?php echo wp_kses_post($thumbnail); ?>
                                    </div>
                                    <h6 class="pxl-post--title">
                                        <?php echo esc_attr(get_the_title($post->ID)); ?>
                                    </h6>
                                    <div class="pxl-post--holder">
                                        <?php if ($show_excerpt == 'true'): ?>
                                            <div class="pxl-item--content">
                                                <?php echo wp_trim_words($post->post_excerpt ?: $post->post_content, $num_words, null); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($show_button == 'true'): ?>
                                            <div class="pxl-post--btn">
                                                <a class="pxl-item-btn" href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                                                    <span class="pxl-btn-title"><?php echo !empty($button_text) ? esc_html($button_text) : esc_html__('Explore Projects', 'cyberguard'); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div> 
            </div>
            <?php if($arrows !== 'false'): ?>
                <div class="pxl-swiper-arrow-wrap">
                    <div class="pxl-swiper-arrow pxl-swiper-arrow-prev">
                        <svg width="7" height="13" viewBox="0 0 7 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 1L1 6.5L6 12" stroke="#000" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="pxl-swiper-arrow pxl-swiper-arrow-next">
                        <svg width="7" height="13" viewBox="0 0 7 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L6 6.5L1 12" stroke="#000" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            <?php endif; ?>
            <?php if($pagination !== 'false'): ?>
                <div class="pxl-swiper-pagination">
                    <div class="pxl-swiper-dots style-1"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>