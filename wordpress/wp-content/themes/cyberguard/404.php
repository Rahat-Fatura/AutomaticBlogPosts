<?php
/**
 * @package Bravis-Themes
 */
get_header(); ?>
<div class="container">
    <div class="row content-row">
        <div id="pxl-content-area" class="pxl-content-area col-12">
            <main id="pxl-content-main">
                <div class="pxl-error-inner">
                    <div class="pxl-error-holder">
                        <div class="pxl-error-number wow fadeInUp">
                            <span>404</span>
                        </div>
                        <h3 class="pxl-error-title wow fadeInUp">
                            <?php echo esc_html__('Page cannot be found', 'cyberguard'); ?>
                        </h3>
                        <div class="pxl-error-description wow fadeInUp"><?php echo esc_html__('Sorry, but the page you are looking for does not existing.', 'cyberguard'); ?></div>
                        <?php get_search_form(); ?>
                        <a class="btn btn-default wow fadeInUp" href="<?php echo esc_url(home_url('/')); ?>">
                            <span class="pxl--btn-text-meta"><?php echo esc_html__('Go Back Home', 'cyberguard'); ?></span>
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<?php get_footer();
