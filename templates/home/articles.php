<?php

wp_reset_postdata();
$rt_config = rt_get_config();
$title = $rt_config['_home_options']['artilces_title'] ;

?>



<div class="articles-widget">
    <div class="articles-header">
        最新文章
    </div>
    <div class="articles-list">
        <?php
        $sticky_arr = get_option( 'sticky_posts' );
        ?>

        <?php if($sticky_arr): ?>
            <?php
            $args = array(
                'post__in' => $sticky_arr,
                'ignore_sticky_posts' => 1
            );

            if( $rt_config['_home_options']['artilces_not_in_ids'] ){
                $args['category__not_in'] = explode(',', $rt_config['_home_options']['artilces_not_in_ids']);
            }

            $query_posts = new WP_Query( $args );
            ?>
            <?php while ($query_posts->have_posts()) : ?>
                <?php $query_posts->the_post(); ?>
                <?php get_template_part( 'templates/media/media' ); ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif ?>


        <?php
        $args = array(
            'posts_per_page' => get_option('posts_per_page'),
            'ignore_sticky_posts' => true,
            'post__not_in' => $sticky_arr
        );

        if( $rt_config['_home_options']['artilces_not_in_ids'] ){
            $args['category__not_in'] = explode(',', $rt_config['_home_options']['artilces_not_in_ids']);
        }

        $query_posts = new WP_Query( $args );
        ?>
        <?php while ($query_posts->have_posts()) : ?>
            <?php $query_posts->the_post(); ?>
            <?php get_template_part( 'templates/media/media' ); ?>
        <?php endwhile; ?>
    </div>
    <button
        type="button"
        class="posts-more-button articles-more"
        data-auto-load='<?php echo $rt_config['_home_options']['articles_auto_load'] ?>'
        data-auto-limit='<?php echo $rt_config['_home_options']['articles_auto_limit'] ?>'
        data-no-more='false'
        data-current-page='1'>
        <i class="iconfont">&#xe895;</i>
        <span>查看更多</span>
    </button>
</div>



<?php
wp_reset_postdata();
?>


