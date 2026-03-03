<?php

if (!class_exists('cyberguard_Page')) {

    class cyberguard_Page
    {
        public function get_site_loader(){

            $site_loader = cyberguard()->get_theme_opt( 'site_loader', false );
            $loader_style = cyberguard()->get_theme_opt( 'loader_style', 'style-digital' );
            $gradient_color = cyberguard()->get_theme_opt( 'gradient_color' );
            if($site_loader) { ?>
                <div id="pxl-loadding" class="pxl-loader <?php if($gradient_color['from'] != $gradient_color['to']) { echo 'pxl-loader-gradient '; } ?> <?php echo esc_attr($loader_style); ?>">
                    <div class="pxl-loader-effect">
                        <?php switch ($loader_style) {
                            case 'style-default': ?>
                                <div id="de-loader">
                                    <div class="lds-roller">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                                <?php break;
                        } ?>
                    </div>
                </div>
            <?php }
        }

        public function get_link_pages() {
            wp_link_pages( array(
                'before'      => '<div class="page-links">',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
            ) ); 
        }

        public function get_page_title(){
            $titles = $this->get_title();
            $pt_mode = cyberguard()->get_opt('pt_mode');
            $ptitle_scroll_opacity = cyberguard()->get_opt('ptitle_scroll_opacity');
            if( $pt_mode == 'none' ) return;
            $ptitle_layout = (int)cyberguard()->get_opt('ptitle_layout');

            if ($pt_mode == 'bd' && $ptitle_layout > 0 && class_exists('Pxltheme_Core') && is_callable( 'Elementor\Plugin::instance' )) {
                ?>
                <div id="pxl-page-title-elementor" class="<?php if($ptitle_scroll_opacity == true) { echo 'pxl-scroll-opacity'; } ?>">
                    <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $ptitle_layout);?>
                </div>
                <?php 
            } else {
                $ptitle_breadcrumb_on = cyberguard()->get_opt( 'ptitle_breadcrumb_on', '1' );
                $ptitle_text_on = cyberguard()->get_opt( 'ptitle_text_on', '1' );
                $ptitle_text_custom = cyberguard()->get_opt( 'ptitle_text_custom', '' );
                if ( empty( $ptitle_text_custom ) ) {
                    $ptitle_text_custom = esc_html__( 'Prevention is cheaper than a breach', 'cyberguard' );
                }
                wp_enqueue_script('stellar-parallax'); ?>
                <div id="pxl-page-title-default" class="pxl--parallax section-dark" data-stellar-background-ratio="0.5">
                    <div class="container">
                        <div class="row">
                            <?php 
                            $col_class = ($ptitle_text_on == '1') ? 'col-lg-6' : 'col-lg-12'; 
                            ?>
                            <div class="<?php echo esc_attr($col_class); ?>">
                                <div class="ptitle-content">
                                    <h1 class="pxl-page-title"><?php echo cyberguard_html($titles['title']) ?></h1>
                                    <?php if($ptitle_breadcrumb_on == '1') : ?>
                                        <?php $this->get_breadcrumb(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if($ptitle_text_on == '1') : ?>
                                <div class="col-lg-6 ptitle-right">
                                    <h3 class="pxl-ptitle-text">
                                        <?php echo esc_html($ptitle_text_custom); ?>
                                    </h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php } 
        } 

        public function get_title() {
            $title = '';
            // Default titles
            if ( ! is_archive() ) {
                // Posts page view
                if ( is_home() ) {
                    // Only available if posts page is set.
                    if ( ! is_front_page() && $page_for_posts = get_option( 'page_for_posts' ) ) {
                        $title = get_post_meta( $page_for_posts, 'custom_title', true );
                        if ( empty( $title ) ) {
                            $title = get_the_title( $page_for_posts );
                        }
                    }
                    if ( is_front_page() ) {
                        $title = esc_html__( 'Blog', 'cyberguard' );
                    }
                } // Single page view
                elseif ( is_page() ) {
                    $title = get_post_meta( get_the_ID(), 'custom_title', true );
                    if ( ! $title ) {
                        $title = get_the_title();
                    }
                } elseif ( is_404() ) {
                    $title = esc_html__( '404 Error', 'cyberguard' );
                } elseif ( is_search() ) {
                    $title = esc_html__( 'Search results', 'cyberguard' );
                } elseif ( is_singular('lp_course') ) {
                    $title = esc_html__( 'Course', 'cyberguard' );
                } else {
                    $title = get_post_meta( get_the_ID(), 'custom_title', true );
                    if ( ! $title ) {
                        $title = get_the_title();
                    }
                }
            } else {
                $title = get_the_archive_title();
                if( (class_exists( 'WooCommerce' ) && is_shop()) ) {
                    $title = get_post_meta( wc_get_page_id('shop'), 'custom_title', true );
                    if(!$title) {
                        $title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
                    }
                }
            }

            return array(
                'title' => $title,
            );
        }

        public function get_breadcrumb(){

            if ( ! class_exists( 'CASE_Breadcrumb' ) )
            {
                return;
            }

            $breadcrumb = new CASE_Breadcrumb();
            $entries = $breadcrumb->get_entries();

            if ( empty( $entries ) )
            {
                return;
            }

            ob_start();

            foreach ( $entries as $entry )
            {
                $entry = wp_parse_args( $entry, array(
                    'label' => '',
                    'url'   => ''
                ) );

                $entry_label = $entry['label'];

                if(!empty($_GET['blog_title'])) {
                    $blog_title = $_GET['blog_title'];
                    $custom_title = explode('_', $blog_title);
                    foreach ($custom_title as $index => $value) {
                        $arr_str_b[$index] = $value;
                    }
                    $str = implode(' ', $arr_str_b);
                    $entry_label = $str;
                }

                if ( empty( $entry_label ) )
                {
                    continue;
                }

                echo '<li>';

                $pxl_post_title = cyberguard()->get_opt( 'pxl_post_title', 'default' );
                $pxl_post_title_text = cyberguard()->get_opt( 'pxl_post_title_text' );

                if ( ! empty( $entry['url'] ) )
                {
                    printf(
                        '<a class="breadcrumb-entry" href="%1$s">%2$s</a>',
                        esc_url( $entry['url'] ),
                        esc_attr( $entry_label )
                    );
                }
                elseif( is_singular('post') && $pxl_post_title == 'custom' && !empty($pxl_post_title_text) ) {
                    printf( '<span class="breadcrumb-entry" >'.$pxl_post_title_text.'</span>', esc_html( $entry_label ) );
                }
                else
                {
                    printf( '<span class="breadcrumb-entry" >%s</span>', esc_html( $entry_label ) );
                }

                echo '</li>';
            }

            $output = ob_get_clean();

            if ( $output )
            {
                printf( '<ul class="pxl-breadcrumb">%s</ul>', wp_kses_post($output));
            }
        }

        public function get_pagination( $query = null, $ajax = false ){

            if($ajax){
                add_filter('paginate_links', 'cyberguard_ajax_paginate_links');
            }

            $classes = array();

            if ( empty( $query ) )
            {
                $query = $GLOBALS['wp_query'];
            }

            if ( empty( $query->max_num_pages ) || ! is_numeric( $query->max_num_pages ) || $query->max_num_pages < 2 )
            {
                return;
            }

            $paged = $query->get( 'paged', '' );

            if ( ! $paged && is_front_page() && ! is_home() )
            {
                $paged = $query->get( 'page', '' );
            }

            $paged = $paged ? intval( $paged ) : 1;

            $pagenum_link = html_entity_decode( get_pagenum_link() );
            $query_args   = array();
            $url_parts    = explode( '?', $pagenum_link );

            if ( isset( $url_parts[1] ) )
            {
                wp_parse_str( $url_parts[1], $query_args );
            }

            $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
            $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';
            $paginate_links_args = array(
                'base'     => $pagenum_link,
                'total'    => $query->max_num_pages,
                'current'  => $paged,
                'mid_size' => 1,
                'add_args' => array_map( 'urlencode', $query_args ),
                'prev_text' => '<i class="caseicon-angle-arrow-left"></i>',
                'next_text' => '<i class="caseicon-angle-arrow-right"></i>',
            );
            if($ajax){
                $paginate_links_args['format'] = '?page=%#%';
            }
            $links = paginate_links( $paginate_links_args );
            if ( $links ):
            ?>
            <nav class="pxl-pagination-wrap <?php echo esc_attr($ajax?'ajax':''); ?>">
                <div class="pxl-pagination-links">
                    <?php
                        printf($links);
                    ?>
                </div>
            </nav>
            <?php
            endif;
        }
    }
}
