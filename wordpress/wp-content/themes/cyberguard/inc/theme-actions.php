<?php 
/**
 * Actions Hook for the theme
 *
 * @package Bravis-Themes
 */
add_action('after_setup_theme', 'cyberguard_setup');
function cyberguard_setup(){

    //Set the content width in pixels, based on the theme's design and stylesheet.
    $GLOBALS['content_width'] = apply_filters( 'cyberguard_content_width', 1200 );

    // Make theme available for translation.
    load_theme_textdomain( 'cyberguard', get_template_directory() . '/languages' );

    // Custom Header
    add_theme_support( 'custom-header' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    set_post_thumbnail_size( 1200, 750 );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary', 'cyberguard' ),
    ) );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for core custom logo.
    add_theme_support( 'custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ) );
    add_theme_support( 'post-formats', array (
        '',
    ) );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');
    add_image_size( 'cyberguard-thumb-small', 260, 170, true );
    add_image_size( 'cyberguard-thumb-xs', 120, 104, true );
    add_image_size( 'cyberguard-large', 1200, 750, true );
    add_image_size( 'cyberguard-portfolio', 600, 600, true );

    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    remove_theme_support('widgets-block-editor');

}

/**
 * Register Widgets Position.
 */
add_action( 'widgets_init', 'cyberguard_widgets_position' );
function cyberguard_widgets_position() {
    register_sidebar( array(
        'name'          => esc_html__( 'Blog Sidebar', 'cyberguard' ),
        'id'            => 'sidebar-blog',
        'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-content">',
        'after_widget'  => '</div></section>',
        'before_title'  => '<h2 class="widget-title"><span>',
        'after_title'   => '</span></h2>',
    ) );

    if (class_exists('ReduxFramework')) {
        register_sidebar( array(
            'name'          => esc_html__( 'Page Sidebar', 'cyberguard' ),
            'id'            => 'sidebar-page',
            'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-content">',
            'after_widget'  => '</div></section>',
            'before_title'  => '<h2 class="widget-title"><span>',
            'after_title'   => '</span></h2>',
        ) );
    }

    if ( class_exists( 'Woocommerce' ) ) {
        register_sidebar( array(
            'name'          => esc_html__( 'Shop Sidebar', 'cyberguard' ),
            'id'            => 'sidebar-shop',
            'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-content">',
            'after_widget'  => '</div></section>',
            'before_title'  => '<h2 class="widget-title"><span>',
            'after_title'   => '</span></h2>',
        ) );
    }
}

/**
 * Enqueue Styles Scripts : Front-End
 */
add_action( 'wp_enqueue_scripts', 'cyberguard_scripts' );
function cyberguard_scripts() {  
    $cyberguard_version = wp_get_theme( get_template() );

    /* Popup Libs */
    wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/css/libs/magnific-popup.css', array(), '1.1.0');
    wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/assets/js/libs/magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );

    /* Wow Libs */
    wp_enqueue_style('wow-animate', get_template_directory_uri() . '/assets/css/libs/animate.min.css', array(), '1.1.0');
    wp_enqueue_script( 'wow-animate', get_template_directory_uri() . '/assets/js/libs/wow.min.js', array( 'jquery' ), '1.0.0', true );

    /* Particles Background Libs */
    wp_register_script( 'particles-background', get_template_directory_uri() . '/assets/js/libs/particles.min.js', array( 'jquery' ), '1.1.0', true );

    /* Parallax Image */
    wp_register_script( 'tilt', get_template_directory_uri() . '/assets/js/libs/tilt.min.js', array( 'jquery' ), '1.0.0', true );

    /* Parallax Libs */
    wp_register_script( 'stellar-parallax', get_template_directory_uri() . '/assets/js/libs/stellar-parallax.min.js', array( 'jquery' ), '0.6.2', true );
    wp_enqueue_script(
        'cyberguard-parallax-init',
        get_template_directory_uri() . '/assets/js/libs/pxl-parallax-init.js',
        array('jquery', 'stellar-parallax'),
        null,
        true
    );

    /* Bodyscrolllock */
    wp_enqueue_script( 'pxl-scrolllock', get_template_directory_uri() . '/assets/js/libs/bodyscrolllock.min.js', array( 'jquery' ), 'all', true );

    /* Smooth Scroll */
    $smooth_scroll = cyberguard()->get_theme_opt( 'smooth_scroll', 'off' );
    if($smooth_scroll == 'on') {
        wp_enqueue_script( 'gsap' );
        wp_enqueue_script( 'pxl-scroll-trigger' );
        wp_enqueue_script( 'pxl-bundled-lenis' );
    }

    /* Nice Select */
    wp_enqueue_script( 'nice-select', get_template_directory_uri() . '/assets/js/libs/nice-select.min.js', array( 'jquery' ), 'all', true );

    /* Icons Lib - CSS */
    wp_enqueue_style('flaticon', get_template_directory_uri() . '/assets/fonts/flaticon/css/flaticon.css' , array(), $cyberguard_version->get( 'Version' ));

    /* Counter Effect */
    wp_register_script( 'pxl-counter-slide', get_template_directory_uri() . '/assets/js/libs/counter-slide.min.js', array( 'jquery' ), '1.0.0', true );

    /* Scroll Effect */
    wp_register_script( 'pxl-scroll', get_template_directory_uri() . '/assets/js/libs/scroll.min.js', array( 'jquery' ), '0.6.0', true );

    /* Parallax Scroll */
    wp_register_script( 'pxl-parallax-scroll', get_template_directory_uri() . '/assets/js/libs/parallax-scroll.js', array( 'jquery' ), '1.0.0', true );
    wp_register_script( 'pxl-easing', get_template_directory_uri() . '/assets/js/libs/easing.js', array( 'jquery' ), '1.3.0', true );

    /* Tweenmax */
    wp_register_script( 'pxl-tweenmax', get_template_directory_uri() . '/assets/js/libs/tweenmax.min.js', array( 'jquery' ), '2.1.2', true );
    
    /* Parallax Move Mouse */
    wp_register_script( 'pxl-parallax-move-mouse', get_template_directory_uri() . '/assets/js/libs/parallax-move-mouse.js', array( 'jquery' ), '1.0.0', true );

    /* Woocommerce */
    wp_enqueue_script( 'pxl-woocommerce', get_template_directory_uri() . '/woocommerce/js/woocommerce.js', array( 'jquery' ), $cyberguard_version->get( 'Version' ), true );

    /* Cookie */
    wp_register_script( 'pxl-cookie', get_template_directory_uri() . '/assets/js/libs/cookie.js', array( 'jquery' ), '1.4.1', true );

    /* Direction Effect */
    wp_register_script('pxl-direction', get_template_directory_uri() . '/elements/widgets/js/direction.js', array('jquery'), '1.0.0', true);

    wp_enqueue_style( 'pxl-caseicon', get_template_directory_uri() . '/assets/css/caseicon.css', array(), $cyberguard_version->get( 'Version' ) );
    wp_enqueue_style( 'pxl-grid', get_template_directory_uri() . '/assets/css/grid.css', array(), $cyberguard_version->get( 'Version' ) );
    wp_enqueue_style( 'pxl-style', get_template_directory_uri() . '/assets/css/style.css', array(), $cyberguard_version->get( 'Version' ) );
    wp_add_inline_style( 'pxl-style', cyberguard_inline_styles() );
    wp_enqueue_style( 'pxl-base', get_template_directory_uri() . '/style.css', array(), $cyberguard_version->get( 'Version' ) );
    wp_enqueue_style( 'pxl-google-fonts', cyberguard_fonts_url(), array(), null );
    wp_enqueue_script( 'pxl-main', get_template_directory_uri() . '/assets/js/theme.js', array( 'jquery' ), $cyberguard_version->get( 'Version' ), true );
    wp_localize_script( 'pxl-main', 'main_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    do_action( 'cyberguard_scripts');
}

/**
 * Enqueue Styles Scripts : Back-End
 */
add_action('admin_enqueue_scripts', 'cyberguard_admin_style');
function cyberguard_admin_style() {
    $theme = wp_get_theme( get_template() );
    wp_enqueue_style( 'cyberguard-admin-style', get_template_directory_uri() . '/assets/css/admin.css', array(), $theme->get( 'Version' ) );
    wp_enqueue_style('flaticon', get_template_directory_uri() . '/assets/fonts/flaticon/css/flaticon.css');
}

add_action( 'elementor/editor/before_enqueue_scripts', function() {
    wp_enqueue_style( 'elementor-flaticon', get_template_directory_uri() . '/assets/fonts/flaticon/css/flaticon.css');
    wp_enqueue_style( 'cyberguard-admin-style', get_template_directory_uri() . '/assets/css/admin.css');
} );

/* Favicon */
add_action('wp_head', 'cyberguard_site_favicon');
function cyberguard_site_favicon(){
    $favicon = cyberguard()->get_theme_opt( 'favicon' );
    if(!empty($favicon['url']))
        echo '<link rel="icon" type="image/png" href="'.esc_url($favicon['url']).'"/>';
}

add_action('wp_head', function() {
    $bg_header_color = cyberguard()->get_opt('bg_header_color'); // lấy option

    if (!empty($bg_header_color)) {
        echo '<style>
            .px-header--fixed {
                background-color: ' . esc_attr($bg_header_color) . ' !important;
            }
        </style>';
    }
});

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
add_action( 'wp_head', 'cyberguard_pingback_header' );
function cyberguard_pingback_header(){
    if ( is_singular() && pings_open() ) {
        echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
    }
}

/* Hidden Panel */
add_action( 'pxl_anchor_target', 'cyberguard_hook_anchor_templates_hidden_panel');
function cyberguard_hook_anchor_templates_hidden_panel(){

    $hidden_templates = cyberguard_get_templates_slug('hidden-panel');
    if(empty($hidden_templates)) return;

    foreach ($hidden_templates as $slug => $values){
        $args = [
            'slug' => $slug,
            'post_id' => $values['post_id']
        ];
        if( did_action('pxl_anchor_target_hidden_panel_'.$values['post_id']) <= 0){  
            do_action( 'pxl_anchor_target_hidden_panel_'.$values['post_id'], $args );  
        }
    } 
}
if(!function_exists('cyberguard_hook_anchor_hidden_panel')){
    function cyberguard_hook_anchor_hidden_panel($args){
        $hidden_panel_bg = get_post_meta( $args['post_id'], 'hidden_panel_bg', true );
        $hidden_panel_width = get_post_meta( $args['post_id'], 'hidden_panel_width', true );
        ?>
        <div id="pxl-hidden-panel-popup" class="pxl-popup-wrap">
            <div class="pxl-item--overlay pxl-cursor--cta"></div>
            <div class="pxl-item--conent" style="background-color: <?php echo esc_attr($hidden_panel_bg); ?>; width:<?php echo esc_attr($hidden_panel_width).'px'; ?>;">
                <div class="pxl-item--close pxl-close pxl-cursor--cta"></div>
                <div class="pxl-conent-elementor">
                    <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( (int)$args['post_id']); ?>
                </div>
            </div>
        </div>
    <?php }
}

/* Elementor Popup */
add_action( 'pxl_anchor_target', 'cyberguard_hook_anchor_templates_popup');
function cyberguard_hook_anchor_templates_popup(){

    $popup_templates = cyberguard_get_templates_slug('popup');
    if(empty($popup_templates)) return;

    foreach ($popup_templates as $slug => $values){
        $args = [
            'slug' => $slug,
            'post_id' => $values['post_id']
        ];
        if( did_action('pxl_anchor_target_popup_'.$values['post_id']) <= 0){  
            do_action( 'pxl_anchor_target_popup_'.$values['post_id'], $args );  
        }
    } 
}
if(!function_exists('cyberguard_hook_anchor_popup')){
    function cyberguard_hook_anchor_popup($args){ ?>
        <div id="pxl-popup-elementor" class="pxl-popup-elementor-wrap">
            <div class="pxl-item--overlay pxl-cursor--cta">
                <div class="pxl-item--flip pxl-item--flip1"></div>
                <div class="pxl-item--flip pxl-item--flip2"></div>
                <div class="pxl-item--flip pxl-item--flip3"></div>
                <div class="pxl-item--flip pxl-item--flip4"></div>
                <div class="pxl-item--flip pxl-item--flip5"></div>
            </div>
            <div class="pxl-item--close pxl-close pxl-cursor--cta"></div>
            <div class="pxl-item--conent">
                <div class="pxl-conent-elementor">
                    <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( (int)$args['post_id']); ?>
                </div>
            </div>
        </div>
    <?php }
}

/* Search Popup */
if(!function_exists('cyberguard_hook_anchor_search')){
    function cyberguard_hook_anchor_search(){ ?>
        <div id="pxl-search-popup" class="pxl-popup-wrap">
            <div class="pxl-item--overlay pxl-cursor--cta"></div>
            <div class="pxl-item--conent">
                <div class="pxl-item--close pxl-close pxl-cursor--cta"></div>
                <?php get_search_form(); ?>
            </div>
        </div>
    <?php }
}       

/* Cart Sidebar */
if(!function_exists('cyberguard_hook_anchor_cart')){
    function cyberguard_hook_anchor_cart(){ ?>
        <div id="pxl-cart-sidebar" class="pxl-popup-wrap">
            <div class="pxl-item--overlay pxl-cursor--cta"></div>
            <div class="pxl-item--conent pxl-widget-cart-sidebar">
                <div class="widget_shopping_cart">
                    <div class="widget_shopping_head">
                        <div class="pxl-item--close pxl-close pxl-cursor--cta"></div>
                        <div class="widget_shopping_title">
                            <?php echo esc_html__( 'Cart', 'cyberguard' ); ?> <span class="widget_cart_counter">(<?php echo sprintf (_n( '%d item', '%d items', WC()->cart->cart_contents_count, 'cyberguard' ), WC()->cart->cart_contents_count ); ?>)</span>
                        </div>
                    </div>
                    <div class="widget_shopping_cart_content">
                        <?php woocommerce_mini_cart(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}


// like post
/**
 * Processes like/unlike
 * @since    0.5
 */
add_action( 'wp_ajax_nopriv_process_simple_like', 'process_simple_like' );
add_action( 'wp_ajax_process_simple_like', 'process_simple_like' );
function process_simple_like() {
    // Security
    $nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0;
    if ( !wp_verify_nonce( $nonce, 'simple-likes-nonce' ) ) {
        exit( __( 'Not permitted', 'cyberguard' ) );
    }
    // Test if javascript is disabled
    $disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;
    // Test if this is a comment
    $is_comment = ( isset( $_REQUEST['is_comment'] ) && $_REQUEST['is_comment'] == 1 ) ? 1 : 0;
    // Base variables
    $post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : '';
    $result = array();
    $post_users = NULL;
    $like_count = 0;
    // Get plugin options
    if ( $post_id != '' ) {
        $count = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_comment_like_count", true ) : get_post_meta( $post_id, "_post_like_count", true ); // like count
        $count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
        if ( !already_liked( $post_id, $is_comment ) ) { // Like the post
            if ( is_user_logged_in() ) { // user is logged in
                $user_id = get_current_user_id();
                $post_users = post_user_likes( $user_id, $post_id, $is_comment );
                if ( $is_comment == 1 ) {
                    // Update User & Comment
                    $user_like_count = get_user_option( "_comment_like_count", $user_id );
                    $user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
                    update_user_option( $user_id, "_comment_like_count", ++$user_like_count );
                    if ( $post_users ) {
                        update_comment_meta( $post_id, "_user_comment_liked", $post_users );
                    }
                } else {
                    // Update User & Post
                    $user_like_count = get_user_option( "_user_like_count", $user_id );
                    $user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
                    update_user_option( $user_id, "_user_like_count", ++$user_like_count );
                    if ( $post_users ) {
                        update_post_meta( $post_id, "_user_liked", $post_users );
                    }
                }
            } else { // user is anonymous
                $user_ip = sl_get_ip();
                $post_users = post_ip_likes( $user_ip, $post_id, $is_comment );
                // Update Post
                if ( $post_users ) {
                    if ( $is_comment == 1 ) {
                        update_comment_meta( $post_id, "_user_comment_IP", $post_users );
                    } else { 
                        update_post_meta( $post_id, "_user_IP", $post_users );
                    }
                }
            }
            $like_count = ++$count;
            $response['status'] = "liked";
            $response['icon'] = get_liked_icon();
        } else { // Unlike the post
            if ( is_user_logged_in() ) { // user is logged in
                $user_id = get_current_user_id();
                $post_users = post_user_likes( $user_id, $post_id, $is_comment );
                // Update User
                if ( $is_comment == 1 ) {
                    $user_like_count = get_user_option( "_comment_like_count", $user_id );
                    $user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
                    if ( $user_like_count > 0 ) {
                        update_user_option( $user_id, "_comment_like_count", --$user_like_count );
                    }
                } else {
                    $user_like_count = get_user_option( "_user_like_count", $user_id );
                    $user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
                    if ( $user_like_count > 0 ) {
                        update_user_option( $user_id, '_user_like_count', --$user_like_count );
                    }
                }
                // Update Post
                if ( $post_users ) {    
                    $uid_key = array_search( $user_id, $post_users );
                    unset( $post_users[$uid_key] );
                    if ( $is_comment == 1 ) {
                        update_comment_meta( $post_id, "_user_comment_liked", $post_users );
                    } else { 
                        update_post_meta( $post_id, "_user_liked", $post_users );
                    }
                }
            } else { // user is anonymous
                $user_ip = sl_get_ip();
                $post_users = post_ip_likes( $user_ip, $post_id, $is_comment );
                // Update Post
                if ( $post_users ) {
                    $uip_key = array_search( $user_ip, $post_users );
                    unset( $post_users[$uip_key] );
                    if ( $is_comment == 1 ) {
                        update_comment_meta( $post_id, "_user_comment_IP", $post_users );
                    } else { 
                        update_post_meta( $post_id, "_user_IP", $post_users );
                    }
                }
            }
            $like_count = ( $count > 0 ) ? --$count : 0; // Prevent negative number
            $response['status'] = "unliked";
            $response['icon'] = get_unliked_icon();
        }
        if ( $is_comment == 1 ) {
            update_comment_meta( $post_id, "_comment_like_count", $like_count );
            update_comment_meta( $post_id, "_comment_like_modified", date( 'Y-m-d H:i:s' ) );
        } else { 
            update_post_meta( $post_id, "_post_like_count", $like_count );
            update_post_meta( $post_id, "_post_like_modified", date( 'Y-m-d H:i:s' ) );
        }
        $response['count'] = get_like_count( $like_count );
        $response['testing'] = $is_comment;
        if ( $disabled == true ) {
            if ( $is_comment == 1 ) {
                wp_redirect( get_permalink( get_the_ID() ) );
                exit();
            } else {
                wp_redirect( get_permalink( $post_id ) );
                exit();
            }
        } else {
            wp_send_json( $response );
        }
    }
}

/**
 * Utility to test if the post is already liked
 * @since    0.5
 */
function already_liked( $post_id, $is_comment ) {
    $post_users = NULL;
    $user_id = NULL;
    if ( is_user_logged_in() ) { // user is logged in
        $user_id = get_current_user_id();
        $post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
        if ( count( $post_meta_users ) != 0 ) {
            $post_users = $post_meta_users[0];
        }
    } else { // user is anonymous
        $user_id = sl_get_ip();
        $post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" ); 
        if ( count( $post_meta_users ) != 0 ) { // meta exists, set up values
            $post_users = $post_meta_users[0];
        }
    }
    if ( is_array( $post_users ) && in_array( $user_id, $post_users ) ) {
        return true;
    } else {
        return false;
    }
} // already_liked()

function get_simple_likes_button( $post_id, $is_comment = NULL ) {
    $is_comment = ( NULL == $is_comment ) ? 0 : 1;
    $output = '';
    $nonce = wp_create_nonce( 'simple-likes-nonce' ); // Security
    if ( $is_comment == 1 ) {
        $post_id_class = esc_attr( ' sl-comment-button-' . $post_id );
        $comment_class = esc_attr( ' sl-comment' );
        $like_count = get_comment_meta( $post_id, "_comment_like_count", true );
        $like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
    } else {
        $post_id_class = esc_attr( ' sl-button-' . $post_id );
        $comment_class = esc_attr( '' );
        $like_count = get_post_meta( $post_id, "_post_like_count", true );
        $like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
    }
    $count = get_like_count( $like_count );
    $icon_empty = get_unliked_icon();
    $icon_full = get_liked_icon();
    // Loader
    $loader = '<span id="sl-loader"></span>';
    // Liked/Unliked Variables
    if ( already_liked( $post_id, $is_comment ) ) {
        $class = esc_attr( ' liked' );
        $title = __( 'Unlike', 'cyberguard' );
        $icon = $icon_full;
    } else {
        $class = '';
        $title = __( 'Like', 'cyberguard' );
        $icon = $icon_empty;
    }
    $output = '<span class="sl-wrapper"><a href="' . admin_url( 'admin-ajax.php?action=process_simple_like' . '&post_id=' . $post_id . '&nonce=' . $nonce . '&is_comment=' . $is_comment . '&disabled=true' ) . '" class="sl-button' . $post_id_class . $class . $comment_class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" data-iscomment="' . $is_comment . '" title="' . $title . '">' . $icon . $count . '</a>' . $loader . '</span>';
    return $output;
} // get_simple_likes_button()



function post_user_likes( $user_id, $post_id, $is_comment ) {
    $post_users = '';
    $post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
    if ( count( $post_meta_users ) != 0 ) {
        $post_users = $post_meta_users[0];
    }
    if ( !is_array( $post_users ) ) {
        $post_users = array();
    }
    if ( !in_array( $user_id, $post_users ) ) {
        $post_users['user-' . $user_id] = $user_id;
    }
    return $post_users;
}

function post_ip_likes( $user_ip, $post_id, $is_comment ) {
    $post_users = '';
    $post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" );
    // Retrieve post information
    if ( count( $post_meta_users ) != 0 ) {
        $post_users = $post_meta_users[0];
    }
    if ( !is_array( $post_users ) ) {
        $post_users = array();
    }
    if ( !in_array( $user_ip, $post_users ) ) {
        $post_users['ip-' . $user_ip] = $user_ip;
    }
    return $post_users;
}

function sl_get_ip() {
    $ip = '';

    // Lấy giá trị an toàn từ biến môi trường
    $client_ip        = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP', FILTER_SANITIZE_STRING);
    $forwarded_for_ip = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR', FILTER_SANITIZE_STRING);
    $remote_addr_ip   = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING);

    if ( ! empty( $client_ip ) ) {
        $ip = $client_ip;
    } elseif ( ! empty( $forwarded_for_ip ) ) {
        
        $ip_list = explode( ',', $forwarded_for_ip );
        $ip      = trim( $ip_list[0] );
    } elseif ( ! empty( $remote_addr_ip ) ) {
        $ip = $remote_addr_ip;
    }

    $ip = filter_var( $ip, FILTER_VALIDATE_IP );
    return ( $ip === false ) ? '0.0.0.0' : $ip;
}

// sl_get_ip()

function get_liked_icon() {
    /* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart"></i> */
    $icon = '<span class="sl-icon"><svg role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xml:space="preserve"><path id="heart-full" d="M124 20.4C111.5-7 73.7-4.8 64 19 54.3-4.9 16.5-7 4 20.4c-14.7 32.3 19.4 63 60 107.1C104.6 83.4 138.7 52.7 124 20.4z"/>&#9829;</svg></span>';
    return $icon;
} // get_liked_icon()


function get_unliked_icon() {
    /* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart-o"></i> */
    $icon = '<span class="sl-icon"><svg role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xml:space="preserve"><path id="heart" d="M64 127.5C17.1 79.9 3.9 62.3 1 44.4c-3.5-22 12.2-43.9 36.7-43.9 10.5 0 20 4.2 26.4 11.2 6.3-7 15.9-11.2 26.4-11.2 24.3 0 40.2 21.8 36.7 43.9C124.2 62 111.9 78.9 64 127.5zM37.6 13.4c-9.9 0-18.2 5.2-22.3 13.8C5 49.5 28.4 72 64 109.2c35.7-37.3 59-59.8 48.6-82 -4.1-8.7-12.4-13.8-22.3-13.8 -15.9 0-22.7 13-26.4 19.2C60.6 26.8 54.4 13.4 37.6 13.4z"/>&#9829;</svg></span>';
    return $icon;
} // get_unliked_icon()


function sl_format_count( $number ) {
    $precision = 2;
    if ( $number >= 1000 && $number < 1000000 ) {
        $formatted = number_format( $number/1000, $precision ).'K';
    } else if ( $number >= 1000000 && $number < 1000000000 ) {
        $formatted = number_format( $number/1000000, $precision ).'M';
    } else if ( $number >= 1000000000 ) {
        $formatted = number_format( $number/1000000000, $precision ).'B';
    } else {
        $formatted = $number; // Number is less than 1000
    }
    $formatted = str_replace( '.00', '', $formatted );
    return $formatted;
} // sl_format_count()

/**
 * Utility retrieves count plus count options, 
 * returns appropriate format based on options
 * @since    0.5
 */
function get_like_count( $like_count ) {
    $like_text = __( 'Like', 'cyberguard' );
    if ( is_numeric( $like_count ) && $like_count > 0 ) { 
        $number = sl_format_count( $like_count );
    } else {
        $number = $like_text;
    }
    $count = '<span class="sl-count">' . $number . '</span>';
    return $count;
} // get_like_count()

// User Profile List
add_action( 'show_user_profile', 'show_user_likes' );
add_action( 'edit_user_profile', 'show_user_likes' );
function show_user_likes($user) {
    ?>
    <table class="form-table">
        <tr>
            <th><label for="user_likes"><?php _e('You Like:', 'cyberguard'); ?></label></th>
            <td>
                <?php
                $types = get_post_types(array('public' => true));
                $args = array(
                    'numberposts' => -1,
                    'post_type' => $types,
                    'meta_query' => array(
                        array(
                            'key' => '_user_liked',
                            'value' => sanitize_text_field($user->ID),
                            'compare' => 'LIKE'
                        )
                    )
                );
                $sep = '';
                $like_query = new WP_Query($args);
                if ($like_query->have_posts()) :
                    ?>
                    <p>
                        <?php
                        while ($like_query->have_posts()) : $like_query->the_post();
                            echo esc_html($sep);
                            ?>
                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(the_title_attribute(['echo' => false])); ?>">
                                <?php the_title(); ?>
                            </a>
                            <?php
                            $sep = ' &middot; ';
                        endwhile;
                        ?>
                    </p>
                <?php else : ?>
                    <p><?php _e('You do not like anything yet.', 'cyberguard'); ?></p>
                <?php
                endif;
                wp_reset_postdata();
                ?>
            </td>
        </tr>
    </table>
    <?php
}




//end like