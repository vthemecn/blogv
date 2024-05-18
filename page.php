<?php

/**
 * Template Name: 页面模板
 * Template Post Type: post
 * 
 * @author: soushenji <soushenji@qq.com>
 * @link https://github.com/soushenji
 */

get_header();

$rt_options = rt_get_config();

?>

<div class="main-container">
    <div class="widget-one">
        <div class="singular-article-container">
            <div class="page-title">
                <?php the_title(); ?>
            </div>

            <?php if( current_user_can( 'manage_options' ) ): ?>
                 <div class="edit-button">
                     <a href="/wp-admin/post.php?post=<?php echo get_the_ID() ?>&action=edit">
                        <i class="iconfont">&#xe77a;</i> <span>编辑</span>
                    </a>
                </div>
            <?php endif ?>

            <div class="page-content">
                <?php
                if (have_posts()) {

                    while (have_posts()) {
                        the_post();
                        the_content();
                        // get_template_part( 'templates/content', get_post_type() );
                    }
                }
                ?>
            </div>
        </div>
    </div><!-- .widget-one -->

    <div class="sider little-widget">
        <?php if ( is_active_sidebar( 'main-sidebar' ) ) : ?>
            <?php dynamic_sidebar( 'main-sidebar' ); ?>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
