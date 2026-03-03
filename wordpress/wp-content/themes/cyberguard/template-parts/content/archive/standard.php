<?php
/**
 * @package Bravis-Themes
 */
$archive_date = cyberguard()->get_theme_opt( 'archive_date', true );
$archive_category = cyberguard()->get_theme_opt( 'archive_category', true );
$archive_readmore_text = cyberguard()->get_theme_opt('archive_readmore_text', esc_html__('Read more', 'cyberguard'));

$img_url = '';
if (has_post_thumbnail(get_the_ID()) && wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), false)) {
    $img_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), false);
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('pxl---post pxl-item--archive pxl-item--archive'); ?>>
    <div class="pxl-content-thumbnail">
        <div class="pxl-date">
            <?php if($archive_date) : ?>
                <span class="pxl-item--date"><?php echo get_the_date('j'); ?></span>
                <div class="pxl-item--date2"><?php echo get_the_date('M'); ?></div>
            <?php endif; ?>
        </div>
        <?php if (has_post_thumbnail()) { 
            echo '<div class="pxl-item--image">'; ?>
                <a href="<?php echo esc_url( get_permalink()); ?>"><?php the_post_thumbnail('cyberguard-large'); ?></a>
            <?php echo '</div>';
        } ?>
    </div>
    <div class="pxl-content">
        <?php if($archive_category) : ?>
            <div class="pxl-item--category"><?php the_terms( get_the_ID(), 'category', '' ); ?></div>
        <?php endif; ?>
        <h3 class="pxl-item--title">
            <a href="<?php echo esc_url( get_permalink()); ?>" title="<?php the_title_attribute(); ?>">
                <?php if(is_sticky()) { ?>
                <?php } ?>
                <?php the_title(); ?>
            </a>
        </h3>
        <div class="pxl-item--excerpt">
            <?php
                cyberguard()->blog->get_excerpt();
                wp_link_pages( array(
                    'before'      => '<div class="page-links">',
                    'after'       => '</div>',
                    'link_before' => '<span>',
                    'link_after'  => '</span>',
                ) );
            ?>
        </div>
        <div class="pxl-item-bottom">
            <a class="btn--readmore" href="<?php echo esc_url( get_permalink()); ?>">
                <span><?php echo cyberguard_html($archive_readmore_text); ?></span>
            </a>
        </div>
    </div>
</article>