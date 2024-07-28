<?php

/**
 * 文章列表页 按类别请求帖子时，将使用类别模板
 * 
 * @author: soushenji <soushenji@qq.com>
 * @link https://github.com/soushenji
 */

get_header();
?>


<?php
/**
 * 分类页 Banner
 */
$vt_config = vt_get_config();
$category = get_term($cat);

// $default_image = $vt_config['default_image'] ? $vt_config['default_image'] : get_template_directory_uri() . '/assets/images/default.jpg';

// 获取分类第一篇文章的缩略图或者图片
$banner_image = get_bloginfo('template_url') . '/assets/images/user-center-banner.jpg';
$args = array(
    'cat' => $cat,
    'orderby' => array("menu_order" => "desc",'date' => "desc"),
    'posts_per_page'=>1
);
$query_posts = new WP_Query($args);
if($query_posts->posts){
    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($query_posts->posts[0]->ID), 'medium');
    $banner_image = $thumbnail ? $thumbnail[0] : $banner_image;
}

// 列数
$col_num_class = 'col-num-5';
switch ($vt_config['list_cards_col']) {
    case '3':
        $col_num_class = 'col-num-3'; break;
    case '4':
        $col_num_class = 'col-num-4'; break;
    case '5':
        $col_num_class = 'col-num-5'; break;
    case '6':
        $col_num_class = 'col-num-6'; break;
    default:
        break;
}
?>


<div class="banner" style="background-image: url(<?php echo $banner_image; ?>)">
    <div class="banner-container">
        <div class="title"><?php echo $category->name; ?></div>
        <?php if($category->description):?>
            <div class="description"><?php echo $category->description ?></div>
        <?php endif; ?>
    </div>
</div>


<div class="main-container">
    <div class="widget-one">
        <?php if (have_posts()) : ?>
            <div class="posts-widget <?php echo $col_num_class?>">
                <?php
                wp_reset_postdata();

                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $posts_per_page =  get_option('posts_per_page');
                $args = array(
                    'paged' => $paged,
                    'cat' => $cat,
                    'orderby' => array("menu_order" => "desc",'date' => "desc"),
                    'posts_per_page' => $posts_per_page
                );
                $query_posts = new WP_Query($args);
                ?>

                <?php while ($query_posts->have_posts()) : ?>
                    <?php $query_posts->the_post(); ?>
                    <div class="card-item">
                        <a class="card-image" href="<?php the_permalink() ?>">
                            <?php  $cur_post = get_post(); ?>
                            <img src="<?= vt_get_thumbnail_url($cur_post->ID, 'medium') ?>" alt="<?php the_title(); ?>">
                        </a>
                        <div class="item-info">
                            <a class="title" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div class="no-content">
                <img src="<?php bloginfo('template_url'); ?>/assets/images/empty.png">
                <p>本栏目暂无内容</p>
            </div>
        <?php endif; ?>

        <?php
        the_posts_pagination(array(
            'mid_size' => 3,
            'prev_text' => '<',
            'next_text' => '>',
            'screen_reader_text' => ' ',
            'aria_label' => "",
        ));
        ?>
    </div>


    <div class="sider little-widget">
        <?php if ( is_active_sidebar( 'default-sidebar' ) ) : ?>
            <?php dynamic_sidebar( 'default-sidebar' ); ?>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
