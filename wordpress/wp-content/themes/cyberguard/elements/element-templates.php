<?php 
 
if(!function_exists('cyberguard_get_post_grid')){
    function cyberguard_get_post_grid($posts = [], $settings = []){ 
        if (empty($posts) || !is_array($posts) || empty($settings) || !is_array($settings)) {
            return false;
        }
        switch ($settings['layout']) {

            case 'post-1':
                cyberguard_get_post_grid_layout1($posts, $settings);
                break;

            case 'portfolio-1':
                cyberguard_get_portfolio_grid_layout1($posts, $settings);
                break;

            default:
                return false;
                break;
        }
    }
}

// Start Post Grid
//--------------------------------------------------
function cyberguard_get_post_grid_layout1($posts = [], $settings = []){ 
    extract($settings);
    
    $images_size = !empty($img_size) ? $img_size : '1000x1000';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";
                
                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = ''; 
            $categories = get_the_category($post->ID);
            $user_position = get_user_meta(get_the_author_meta( 'ID' ), 'user_position', true);
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <a class="pxl-inner-link" href="<?php echo esc_url(get_permalink( $post->ID )); ?>"></a>
                    <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)):
                        $img_id = get_post_thumbnail_id($post->ID);
                        $img          = pxl_get_image_by_size( array(
                            'attach_id'  => $img_id,
                            'thumb_size' => $images_size
                        ) );
                        $thumbnail    = $img['thumbnail'];
                        ?>
                        <div class="pxl-post--featured hover-imge-effect3">
                            <?php echo wp_kses_post($thumbnail); ?>
                        </div>
                    <?php endif; ?>
                    <div class="pxl-item-content">
                        <?php if ($show_tags == 'true' && ($tags = get_the_tags($post->ID))) : ?>
                            <?php 
                                $random_tag = $tags[array_rand($tags)];
                            ?>
                            <div class="pxl-item--tags pxl-post-list">
                                <a href="<?php echo esc_url(get_tag_link($random_tag->term_id)); ?>">
                                    <?php echo esc_html($random_tag->name); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <h4 class="pxl-post--title"><?php echo pxl_print_html(get_the_title($post->ID)); ?></h4>
                        <div class="pxl-post-meta pxl-flex">
                            <?php if($show_author == 'true'): ?>
                                <?php $author = get_user_by('id', $post->post_author);
                                $author_avatar = get_avatar( $post->post_author, 60, '', $author->display_name, array( 'class' => '' ) );
                                $user_position = get_user_meta($post->post_author, 'user_position', true); ?>
                                <div class="pxl-post--author pxl-flex">
                                    <div class="pxl-author--img">
                                        <?php pxl_print_html($author_avatar); ?>
                                    </div>
                                    <div class="pxl-author-meta">
                                        <?php echo esc_html($author->display_name); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($show_date == 'true') : ?>
                                <div class="pxl-item--date pxl-flex">
                                    <i class="fal fa-calendar-alt"></i>
                                    <?php echo get_the_date('j M Y', $post->ID); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    endif;
}

// End Post Grid
//--------------------------------------------------

// Start Portfolio Grid
//--------------------------------------------------
function cyberguard_get_portfolio_grid_layout1($posts = [], $settings = []){ 
    extract($settings);
    
    $images_size = !empty($img_size) ? $img_size : '1000x1000';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";
                
                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $img_id = get_post_thumbnail_id($post->ID);
            if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                if($img_id) {
                    $img = pxl_get_image_by_size( array(
                        'attach_id'  => $img_id,
                        'thumb_size' => $images_size,
                        'class' => 'no-lazyload',
                    ));
                    $thumbnail = $img['thumbnail'];
                } else {
                    $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
                }
                $portfolio_excerpt = get_post_meta($post->ID, 'portfolio_excerpt', true); ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                        <span class="pxl-post--overlay"></span>
                        <div class="pxl-post--featured hover-imge-effect3">
                            <?php echo wp_kses_post($thumbnail); ?>
                        </div>
                        <h6 class="pxl-post--title">
                             <?php echo esc_attr(get_the_title($post->ID)); ?>
                        </h6>
                        <div class="pxl-post--holder">
                            <?php if($show_excerpt == 'true' && !empty($portfolio_excerpt)): ?>
                                <div class="pxl-item--content">
                                    <?php echo wp_trim_words( $portfolio_excerpt, $num_words, $more = null ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if($show_button == 'true'): ?>
                                <div class="pxl-post--btn">
                                    <a class="pxl-item-btn" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                        <span class="pxl-btn-title"><?php if(!empty($button_text)) {
                                            echo esc_attr($button_text);
                                        } else {
                                            echo esc_html__('Read more', 'cyberguard');
                                        } ?></span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach;
    endif;
}

// End Portfolio Grid
//--------------------------------------------------

// Start Service Grid
//--------------------------------------------------
function cyberguard_get_service_grid_layout1($posts = [], $settings = []){ 
    extract($settings);

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";   
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $service_excerpt = get_post_meta($post->ID, 'service_excerpt', true);
            $service_external_link = get_post_meta($post->ID, 'service_external_link', true);
            $service_icon_type = get_post_meta($post->ID, 'service_icon_type', true);
            $service_icon_font = get_post_meta($post->ID, 'service_icon_font', true);
            $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
            $gradient_color = cyberguard()->get_opt( 'gradient_color' );
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner pxl-not-active <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <?php if($service_icon_type == 'icon' && !empty($service_icon_font)) : ?>
                        <div class="pxl-item--icon">
                            <div class="pxl-inner-icon">
                                <div class="pxl-icon-clip">
                                    <i class="<?php echo esc_attr($service_icon_font); ?> pxl-icon1"></i>
                                    <span class="pxl-icon2">
                                        <i class="<?php echo esc_attr($service_icon_font); ?>"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($service_icon_type == 'image' && !empty($service_icon_img)) : 
                        $icon_img = pxl_get_image_by_size( array(
                            'attach_id'  => $service_icon_img['id'],
                            'thumb_size' => 'full',
                        ));
                        $icon_thumbnail = $icon_img['thumbnail'];
                        ?>
                        <div class="pxl-item--icon">
                            <div class="pxl-inner-icon">
                                <div class="pxl-icon-clip">
                                    <span class="pxl-icon1">
                                        <?php echo wp_kses_post($icon_thumbnail); ?>
                                    </span>
                                    <span class="pxl-icon2">
                                        <?php echo wp_kses_post($icon_thumbnail); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="pxl-inner-content">
                        <div class="pxl-item-meta">
                            <div class="pxl-item--holder">
                                <h5 class="pxl-item--title">
                                    <a href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a>
                                </h5>
                                <?php if($show_excerpt == 'true' && !empty($service_excerpt)): ?>
                                    <div class="pxl-item--content">
                                        <?php echo wp_trim_words( $service_excerpt, $num_words, $more = null ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if($show_button == 'true') : ?>
                        <div class="pxl-post--readmore">
                            <a class="btn pxl-icon-active btn-default pxl-icon--right" href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                <i class="flaticon flaticon-next"></i>
                                <span><?php if(!empty($button_text)) {
                                    echo esc_attr($button_text);
                                } else {
                                    echo esc_html__('Read more', 'cyberguard');
                                } ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php
        endforeach;
    endif;
}

function cyberguard_get_service_grid_layout2($posts = [], $settings = []){ 
    extract($settings);

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";   
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $service_excerpt = get_post_meta($post->ID, 'service_excerpt', true);
            $service_external_link = get_post_meta($post->ID, 'service_external_link', true);
            $service_icon_type = get_post_meta($post->ID, 'service_icon_type', true);
            $service_icon_font = get_post_meta($post->ID, 'service_icon_font', true);
            $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
            $gradient_color = cyberguard()->get_opt( 'gradient_color' );
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner pxl-not-active <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <?php if($service_icon_type == 'icon' && !empty($service_icon_font)) : ?>
                        <div class="pxl-item--icon">
                            <div class="pxl-inner-icon">
                                <div class="pxl-icon-clip">
                                    <i class="<?php echo esc_attr($service_icon_font); ?> pxl-icon1"></i>
                                    <span class="pxl-icon2">
                                        <i class="<?php echo esc_attr($service_icon_font); ?>"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($service_icon_type == 'image' && !empty($service_icon_img)) : 
                        $icon_img = pxl_get_image_by_size( array(
                            'attach_id'  => $service_icon_img['id'],
                            'thumb_size' => 'full',
                        ));
                        $icon_thumbnail = $icon_img['thumbnail'];
                        ?>
                        <div class="pxl-item--icon">
                            <div class="pxl-inner-icon">
                                <div class="pxl-icon-clip">
                                    <span class="pxl-icon1">
                                        <?php echo wp_kses_post($icon_thumbnail); ?>
                                    </span>
                                    <span class="pxl-icon2">
                                        <?php echo wp_kses_post($icon_thumbnail); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="pxl-inner-content">
                        <div class="pxl-item-meta">
                            <div class="pxl-item--holder">
                                <h5 class="pxl-item--title">
                                    <a href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a>
                                </h5>
                                <?php if($show_excerpt == 'true' && !empty($service_excerpt)): ?>
                                    <div class="pxl-item--content">
                                        <?php echo wp_trim_words( $service_excerpt, $num_words, $more = null ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    endif;
}
// End Service Grid
//-------------------------------------------------

// Start Product Grid
//--------------------------------------------------
// End Product Grid
//--------------------------------------------------

add_action( 'wp_ajax_cyberguard_load_more_post_grid', 'cyberguard_load_more_post_grid' );
add_action( 'wp_ajax_nopriv_cyberguard_load_more_post_grid', 'cyberguard_load_more_post_grid' );
function cyberguard_load_more_post_grid(){
    try{
        if(!isset($_POST['settings'])){
            throw new Exception(__('Something went wrong while requesting. Please try again!', 'cyberguard'));
        }

        $settings = isset($_POST['settings']) ? $_POST['settings'] : null;

        $source = isset($settings['source']) ? $settings['source'] : '';
        $term_slug = isset($settings['term_slug']) ? $settings['term_slug'] : '';
        if( !empty($term_slug) && $term_slug !='*'){
            $term_slug = str_replace('.', '', $term_slug);
            $source = [$term_slug.'|'.$settings['tax'][0]]; 
        }
        if( isset($_POST['handler_click']) && sanitize_text_field(wp_unslash( $_POST[ 'handler_click' ] )) == 'filter'){
            set_query_var('paged', 1);
            $settings['paged'] = 1;
        }elseif( isset($_POST['handler_click']) && sanitize_text_field(wp_unslash( $_POST[ 'handler_click' ] )) == 'select_orderby'){
            set_query_var('paged', 1);
            $settings['paged'] = 1;
        }else{
            set_query_var('paged', (int)$settings['paged']);
        }

        extract(pxl_get_posts_of_grid($settings['post_type'], [
            'source'      => $source,
            'orderby'     => isset($settings['orderby'])?$settings['orderby']:'date',
            'order'       => isset($settings['order']) ? ($settings['orderby'] == 'title' ? 'asc' : sanitize_text_field($settings['order']) ) : 'desc',
            'limit'       => isset($settings['limit'])?$settings['limit']:'6',
            'post_ids'    => isset($settings['post_ids'])?$settings['post_ids']: [],
            'post_not_in' => isset($settings['post_not_in'])?$settings['post_not_in']: [],
        ],
        $settings['tax']
    ));

        ob_start();
        if( isset($settings['wg_type']) && $settings['wg_type'] == 'post-list'){
            cyberguard_get_post_list($posts, $settings);
        }else{
            cyberguard_get_post_grid($posts, $settings);
        }
        $html = ob_get_clean();

        $pagin_html = '';
        if( isset($settings['pagination_type']) && $settings['pagination_type'] == 'pagination' ){ 
            ob_start();
            cyberguard()->page->get_pagination( $query,  true );
            $pagin_html = ob_get_clean();
        }

        $result_count = '';
        if( isset($settings['show_toolbar']) && $settings['show_toolbar'] == 'show' ){ 
            ob_start();
            if( (int)$settings['paged'] == 0){
                $limit_start = 1;
                $limit_end = ( (int)$settings['limit'] >= $total ) ? $total : (int)$settings['limit'];
            }else{
                $limit_start = (((int)$settings['paged'] - 1 ) * (int)$settings['limit']) + 1;
                $limit_end = (int)$settings['paged'] * (int)$settings['limit'];
                $limit_end = ( $limit_end >= $total ) ? $total : $limit_end;
            }
            if( isset($settings['pagination_type']) && $settings['pagination_type'] == 'loadmore' ){ 
                printf(
                    '<span class="result-count">%1$s %2$s %3$s %4$s %5$s</span>',
                    esc_html__('Showing','cyberguard'),
                    '1-'.$limit_end,
                    esc_html__('of','cyberguard'),
                    $total,
                    esc_html__('results','cyberguard')
                );
            }else{
                printf(
                    '<span class="result-count">%1$s %2$s %3$s %4$s %5$s</span>',
                    esc_html__('Showing','cyberguard'),
                    $limit_start.'-'.$limit_end,
                    esc_html__('of','cyberguard'),
                    $total,
                    esc_html__('results','cyberguard')
                );
            }

            $result_count = ob_get_clean();
        }

        wp_send_json(
            array(
                'status' => true,
                'message' => esc_attr__('Load Successfully!', 'cyberguard'),
                'data' => array(
                    'html' => $html,
                    'pagin_html' => $pagin_html,
                    'paged' => $settings['paged'],
                    'posts' => $posts,
                    'max' => $max,
                    'result_count' => $result_count,
                ),
            )
        );
    }
    catch (Exception $e){
        wp_send_json(array('status' => false, 'message' => $e->getMessage()));
    }
    die;
}

if(!function_exists('cyberguard_get_post_list')){
    function cyberguard_get_post_list($posts = [], $settings = []){ 
        if (empty($posts) || !is_array($posts) || empty($settings) || !is_array($settings)) {
            return;
        }
        extract($settings);

        switch ($settings['layout']) {
            case 'post-list-1':
            cyberguard_get_post_list_layout1($posts, $settings);
            break;

            default:
            return false;
            break;
        }
    }
}
function cyberguard_get_post_list_layout1($posts = [], $settings = []){
    extract($settings); 
    foreach ($posts as $key => $post):

        if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)){
            $img_id = get_post_thumbnail_id($post->ID);
            if($img_id){
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
            }else{  
                $thumbnail = get_the_post_thumbnail($post->ID, $img_size);
            }
        }else{
            $thumbnail = '';
        }
        $tags = get_the_tags($post->ID);
        $author = get_user_by('id', $post->post_author);
        $readmore_text = !empty($readmore_text) ? $readmore_text : esc_html__('Continue Reading', 'cyberguard');
        $date_format = get_option('date_format');

        $data_settings = '';
        $animate_cls = '';
        if ( !empty( $item_animation ) ) {
            $animate_cls = ' pxl-animate pxl-invisible animated-'.$item_animation_duration;
            $data_animation =  json_encode([
                'animation'      => $item_animation,
                'animation_delay' => (float)$item_animation_delay
            ]);
            $data_settings = 'data-settings="'.esc_attr($data_animation).'"';
        }

        
        $flag = false;
        $post_format = get_post_format($post->ID) == false ? 'format-standard' : 'format-'.get_post_format($post->ID);
        ?>
        <div class="<?php echo esc_attr('list-item w-100 '. $post_format); ?> <?php echo esc_attr($animate_cls) ?>" <?php pxl_print_html($data_settings); ?>>
            <div class="pxl-post--inner grid-item-inner item-inner-wrap  <?php echo esc_attr($post_format) ?>">
                <?php
                if (has_post_format('quote', $post->ID)){
                    $quote_text = get_post_meta( $post->ID, 'featured-quote-text', true );
                    $quote_cite = get_post_meta( $post->ID, 'featured-quote-cite', true );
                    ?>
                    <div class="col-12">
                        <div class="quote-wrap">
                            <div class="pxl-hl-image"><img src="<?php echo esc_url(get_template_directory_uri().'/assets/img/fm-qt.png'); ?>" /></div>
                            <div class="quote-inner-wrap">

                                <div class="link-inner ">
                                    <div class="link-icon">
                                       <span>“</span>
                                   </div>
                                   <div class="content-right">
                                    <div class="item-post-metas ">
                                        <div class="meta-inner  align-items-center">
                                            <?php if($show_date == 'true') : ?>
                                                <span class="post-date">
                                                    <?php echo get_the_date('d M', $post->ID); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if( $show_category == 'true' ) : ?>
                                                <span class="meta-item post-category  d-flex">
                                                    <?php the_terms( $post->ID, 'category', '', ', ', '' ); ?>
                                                </span>   
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a class="quote-text" href="<?php echo esc_url( get_permalink($post->ID)); ?>"><?php echo esc_html($quote_text);?></a>
                                </div>
                            </div>
                            <div class="quote-footer ">
                                <div class="quote-cite "><?php echo esc_html($quote_cite);?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } elseif (has_post_format('link', $post->ID)){
                $link_url = get_post_meta( $post->ID , 'featured-link-url', true );
                $link_text = get_post_meta( $post->ID , 'featured-link-text', true );
                ?>
                <div class="col-12">
                    <div class="link-wrap">
                        <div class="pxl-hl-image"><img src="<?php echo esc_url(get_template_directory_uri().'/assets/img/fm-qt.png'); ?>" /></div>
                        <div class="link-inner-wrap">
                            <div class="link-inner ">
                                <div class="link-icon">
                                    <a href="<?php echo esc_url( $link_url); ?>">
                                        <svg version="1.1" id="Glyph" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                        <path d="M192.5,240.5c20.7-21,56-23,79,0h0.2c6.4,6.4,11,14.2,13.8,22.6c6.7-1.1,12.6-4,17.1-8.5l22.1-21.9
                                        c-5-9.6-11.4-18.4-19-26.2c-42-41.1-106.9-40-147.2,0l-80,80c-40.6,40.9-40.6,106.3,0,147.2c40.9,40.6,106.3,40.6,147.2,0l75.4-75.4
                                        c-22,3.6-43.1,1.6-62.7-5.3l-46.7,46.6c-21.1,21.3-57.9,21.3-79.2,0c-21.8-21.8-21.8-57.3,0-79C113.9,318.9,197.8,235.1,192.5,240.5
                                        L192.5,240.5z"/>
                                        <path d="M319.5,271.5c-21,21.3-56.3,22.7-79,0c-0.2,0-0.2,0-0.2,0c-6.4-6.4-11-14.2-13.8-22.6c-6.7,1.1-12.6,4-17.1,8.5l-22.1,21.9
                                        c5,9.6,11.4,18.4,19,26.2c42,41.1,106.9,40,147.2,0l80-80c40.6-40.9,40.6-106.3,0-147.2c-40.9-40.6-106.3-40.6-147.2,0L211,153.8
                                        c22-3.6,43.1-1.6,62.7,5.3l46.7-46.6c21.1-21.3,57.9-21.3,79.2,0c21.8,21.8,21.8,57.3,0,79C398.1,193.1,314.2,276.9,319.5,271.5
                                        L319.5,271.5z"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="content-right">
                                <div class="item-post-metas ">
                                    <div class="meta-inner  align-items-center">
                                        <?php if($show_date == 'true') : ?>
                                            <span class="post-date">
                                                <?php echo get_the_date('d M', $post->ID); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if( $show_category == 'true' ) : ?>
                                            <span class="meta-item post-category  d-flex">
                                                <?php the_terms( $post->ID, 'category', '', ', ', '' ); ?>
                                            </span>   
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <h3 class="link-title"><a href="<?php echo esc_url( $link_url); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_title($post->ID); ?></a></h3>
                            </div>
                        </div>
                        <div class="link-footer">
                            <a class="link-text" target="_blank" href="<?php echo esc_url( $link_url); ?>"><?php echo esc_html($link_text);?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php  
        }else{
            if ( !empty( $thumbnail )){
                $flag = true;
                $featured_video = get_post_meta( $post->ID, 'featured-video-url', true );
                $audio_url = get_post_meta( $post->ID, 'featured-audio-url', true ); 
                ?>
                <div class="item-featured">
                    <div class="post-image <?php echo esc_attr('hover-imge-effect3') ?>">
                        <?php echo wp_kses_post($thumbnail); ?>       
                        <?php if (has_post_format('audio', $post->ID)) {  
                            $audio = get_post_meta( $post->ID, 'featured-audio-url', true );
                            ?>  
                            <a class="btn-volumn" href="<?php echo esc_url($audio); ?>" target="_blank"><i class="fas fa-volume"></i></a>
                        <?php } ?>

                        <?php if (has_post_format('video', $post->ID)) {  
                            $video = get_post_meta( $post->ID, 'featured-video-url', true );
                            ?>  
                            <a class="video-play-button pxl-action-popup" href="<?php echo esc_url($video); ?>">
                                <i class="caseicon-play1"></i>
                            </a>

                        <?php } ?>

                        <div class="pxl-category-date">
                            <div class="category-date-inner">
                                <?php if( $show_category == 'true' ) : ?>
                                    <div class="meta-item post-category  d-flex">
                                        <?php the_terms( $post->ID, 'category', '', ' ' ); ?>
                                    </div>   
                                <?php endif; ?>
                                <div class="pxl-border"></div>
                                <?php
                                if($show_date == 'true') : ?>
                                    <div class="post-date">
                                        <?php echo get_the_date('M d, Y', $post->ID); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div> 
                </div>
                <?php
            }else{
                if (has_post_format('video', $post->ID)){
                    $flag = true;
                    global $wp_embed;
                    $featured_video = get_post_meta( $post->ID, 'featured-video-url', true );
                    if (!empty($featured_video)) {
                        echo '<div class="item-featured">';
                        echo '<div class="feature-video">';
                        echo do_shortcode($wp_embed->autoembed($featured_video));
                        echo '</div>';
                        echo '</div>';
                    }
                }elseif(has_post_format('audio', $post->ID)){

                    $flag = true;
                    global $wp_embed;
                    $audio_url = get_post_meta( $post->ID, 'featured-audio-url', true );
                    if (!empty($audio_url)) {
                        echo '<div class="item-featured">';
                        echo '<div class="feature-audio">';
                        echo do_shortcode($wp_embed->autoembed($audio_url));
                        echo '</div>';
                        echo '</div>';
                    }
                }
            }
            ?>
            <div class="wrap-item-content">
                <div class="item-content">
                    <div class="pxl-content-left">
                        <h6 class="item-title title-hover-line"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a></h6>
                        <?php
                            if ($show_author == 'true' || $show_comment == 'true' ){
                                ?>
                                <div class="item-post-metas">
                                    <ul class="meta-inner d-flex-wrap align-items-center">
                                        <?php if( $show_author == 'true' ) : ?>
                                            <li class="pxl-item--author pxl-post-list">
                                                <span class="pxl-icon">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                      <g clip-path="url(#clip0_4807_366)">
                                                        <path d="M13.1743 5.94577L10.054 2.82555C9.97782 2.74933 9.85432 2.74933 9.77814 2.82555L2.58818 10.0155C2.51197 10.0917 2.51197 10.2153 2.58818 10.2914L5.70841 13.4116C5.78463 13.4879 5.90813 13.4879 5.98432 13.4116L13.1743 6.22168C13.2505 6.14549 13.2505 6.02199 13.1743 5.94577Z" fill="white"/>
                                                        <path d="M15.8284 2.73977L13.26 0.171387C13.0317 -0.0570509 12.6608 -0.0572071 12.4323 0.171387L10.8818 1.72189C10.8056 1.79811 10.8056 1.92161 10.8818 1.9978L14.002 5.11802C14.0782 5.19418 14.2017 5.19418 14.2779 5.11802L15.8284 3.56752C16.057 3.33899 16.057 2.96836 15.8284 2.73977Z" fill="white"/>
                                                        <path d="M15.8791 15.3472C15.8462 15.0486 15.5826 14.8285 15.2821 14.8285H3.48847L4.61528 14.3547C4.74303 14.3009 4.77563 14.1349 4.67759 14.0369L1.9634 11.3227C1.86537 11.2247 1.69931 11.2572 1.64559 11.385C1.63193 11.4175 0.0640543 15.1457 0.0507731 15.1784C-0.130946 15.5911 0.202492 16.0224 0.616618 15.9991H15.2975C15.6426 15.9991 15.9181 15.7003 15.8791 15.3472Z" fill="white"/>
                                                      </g>
                                                      <defs>
                                                        <clipPath id="clip0_4807_366">
                                                          <rect width="16" height="16" fill="white"/>
                                                        </clipPath>
                                                      </defs>
                                                    </svg>
                                                </span>
                                                <span class="pxl-name">
                                                    <a href="<?php echo esc_url(get_author_posts_url($post->post_author, $author->user_nicename)); ?>"><?php echo esc_html($author->display_name); ?></a>
                                                </span>
                                            </li>
                                        <?php endif; ?>

                                        <?php if($show_comment == 'true') : ?>
                                            <li class="post-comments">
                                                <a class="meta-item post-comment-count" href="<?php echo get_comments_link($post->ID); ?>#comments">
                                                    <span class="pxl-icon">
                                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                          <g clip-path="url(#clip0_4807_361)">
                                                            <path d="M7.17373 14.0101C10.795 13.4556 13.5987 10.3786 13.5987 6.61218C13.5993 6.07314 13.5413 5.53578 13.4259 5.01045C14.973 5.97551 15.9985 7.65934 15.9985 9.57522C15.9974 10.6269 15.6865 11.6525 15.1082 12.5123C15.1562 13.3073 15.425 14.0043 15.9161 14.6041C15.9608 14.6582 15.9891 14.725 15.9974 14.796C16.0058 14.867 15.9937 14.9389 15.9628 15.0027C15.9319 15.0665 15.8836 15.1193 15.824 15.1542C15.7644 15.1891 15.6962 15.2047 15.6281 15.1989C14.5346 15.1059 13.6403 14.842 12.9452 14.4072C12.1488 14.7995 11.2791 15.0018 10.399 14.9995C9.25497 15.0049 8.1342 14.6611 7.17373 14.0101Z" fill="white"/>
                                                            <path d="M4.49958 12.2409C4.97953 12.3598 5.48188 12.4235 5.99944 12.4235C9.31273 12.4235 11.9989 9.82151 11.9989 6.61135C11.9989 3.40286 9.31273 0.800049 5.99944 0.800049C2.68615 0.800049 0 3.40202 0 6.61218C0 8.2424 0.692735 9.71595 1.80943 10.7715C1.71485 11.4825 1.42435 12.1491 0.973509 12.6899C0.925501 12.7478 0.895074 12.8193 0.886121 12.8954C0.877167 12.9715 0.890095 13.0487 0.923249 13.1171C0.956403 13.1854 1.00828 13.2419 1.07223 13.2792C1.13619 13.3165 1.20932 13.333 1.28228 13.3265C2.64215 13.2109 3.71485 12.849 4.49958 12.2409Z" fill="white"/>
                                                          </g>
                                                          <defs>
                                                            <clipPath id="clip0_4807_361">
                                                              <rect width="16" height="16" fill="white"/>
                                                            </clipPath>
                                                          </defs>
                                                        </svg>
                                                    </span>
                                                    <span class="pxl-name">
                                                        <?php
                                                            echo comments_number(
                                                                '<span class="cmt-count">0</span> '.esc_html__('Comments', 'cyberguard'),
                                                                '<span class="cmt-count">1</span> '.esc_html__('Comment', 'cyberguard'),
                                                                '<span class="cmt-count">%</span> '.esc_html__('Comments', 'cyberguard'),
                                                                $post->ID
                                                            ); 
                                                        ?>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="pxl-content-right">
                        <?php if($show_excerpt == 'true'): ?>
                            <div class="item-excerpt">
                                <?php
                                if(!empty($post->post_excerpt)){
                                    echo wp_trim_words( $post->post_excerpt, $num_words, null );
                                } else{
                                    $content = strip_shortcodes( $post->post_content );
                                    $content = apply_filters( 'the_content', $content );
                                    $content = str_replace(']]>', ']]&gt;', $content);
                                    echo wp_trim_words( $content, $num_words, null );
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php 
                        if($show_readmore == 'true' || $post_share == 'true') : ?>
                            <div class="blog-post-footer  align-items-center justify-content-between">
                                <?php if( $show_readmore == 'true'): ?>
                                    <div class="post-readmore ">
                                        <a class="btn pxl-icon-active  btn-style1  pxl-icon--right" href="<?php echo esc_url( get_permalink($post->ID)); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="pxl-button-arrow" width="23" height="13" viewBox="0 0 23 13" fill="none"><path d="M0 6.5H22M22 6.5L16 1M22 6.5L16 12" stroke="#121212" stroke-width="1.5" stroke-linejoin="round"></path></svg>
                                            <span class="pxl--btn-text"><?php echo cyberguard_html($readmore_text); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
</div>
<?php
endforeach; 
}