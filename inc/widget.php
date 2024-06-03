<?php
/**
 * 
 */

//禁止新版小工具
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false');

function rt_widgets_init() {
    $args = array(
        'name' => __( '默认侧边栏', 'rt' ),
        'id' => 'default-sidebar',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    );
    register_sidebar($args);

    $args = array(
        'name' => __( '文章页侧边栏', 'rt' ),
        'id' => 'posts-sidebar',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    );
    register_sidebar($args);
}
add_action('init', 'rt_widgets_init');


/**
 * 图文列表
 */
class ImageArticleWidget extends WP_Widget {
    function __construct(){
        $this->WP_Widget( 'image-article-list', __( '【Nine】图文列表', 'rt' ), array( 'description' => __('图文列表描述', 'rt' ) ) );
    }
 
    function widget( $args, $instance ){
        extract( $args, EXTR_SKIP );
        echo $before_widget;

        $rt_config = rt_get_config();
        switch ($rt_config['widget_title_type']) {
            case '1': $widget_title_class='type-1'; break;
            case '2': $widget_title_class='type-2'; break;
            case '3': $widget_title_class='type-3'; break;
            default:  $widget_title_class=''; break;
        }

        $title = $instance['title'] ? $instance['title'] : __('图文列表', 'rt');

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $instance['posts_per_page'] ? $instance['posts_per_page'] : 4,
            //'orderby' => 'rand',
            'meta_query' => array(array( 'key' => '_thumbnail_id'))
        );
        if($instance['cat_id']){
            global $wpdb;
            $sql = "SELECT taxonomy FROM {$wpdb->prefix}term_taxonomy WHERE term_id=%s";
            $res = $wpdb->get_row($wpdb->prepare($sql, $instance['cat_id']), ARRAY_A);

            if($res['taxonomy'] == 'category'){
                $args['cat'] = $instance['cat_id'];
            } else {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $res['taxonomy'],
                        'terms' => $instance['cat_id']
                    )
                );
            }
        }
        $query = new WP_Query($args);
        ?>
        <div class="image-title widget-container">
            <div class="widget-header <?php echo $widget_title_class; ?>">
                <div class="widget-title"><?php echo $title ?></div>
            </div>
            <div class="item-list-wrapper">
                <ul class='item-list'>
                    <?php if ( $query->have_posts() ) : ?>
                        <?php while ( $query->have_posts() ) : ?>
                            <?php
                            $query->the_post();
                            $current_post = get_post();
                            $thumbnail_image = wp_get_attachment_image_src(get_post_thumbnail_id($current_post->ID), 'thumbnail');
                            
                            $price = get_post_meta( $current_post->ID, 'price', true );
                            $price = $price ? number_format($price/100,2) : '';
                            ?>
                            
                            <li class='item-widget'>
                                <a href="<?php the_permalink() ?>">
                                    <?php if (!empty($thumbnail_image)) :?>
                                        <img src="<?php echo $thumbnail_image[0] ?>" alt="<?php the_title(); ?>">
                                    <?php else : ?>
                                        <img src="<?php echo $rt_config['default_image'] ?>">
                                    <?php endif ?>
                                    <div class="item-title">
                                        <div><?php the_title(); ?></div>
                                        <span><?php echo friendly_time(get_the_time('Y-m-d H:i:s')); ?></span>
                                    </div>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </ul>
            </div>
        </div>
        <?php
        echo $after_widget;
    }

    function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $posts_per_page = !empty($instance['posts_per_page']) ? $instance['posts_per_page'] : '';
        $cat_id = !empty($instance['cat_id']) ? $instance['cat_id'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">标题:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>">数量:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo esc_attr($posts_per_page); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('cat_id'); ?>">分类ID:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('cat_id'); ?>" name="<?php echo $this->get_field_name('cat_id'); ?>" value="<?php echo esc_attr($cat_id); ?>">
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['posts_per_page'] = (!empty($new_instance['posts_per_page'])) ? strip_tags($new_instance['posts_per_page']) : '';
        $instance['cat_id'] = (!empty($new_instance['cat_id'])) ? strip_tags($new_instance['cat_id']) : '';
        return $instance;
    }
}


/**
 * 热门
 */
class HotWidget extends WP_Widget {
    function __construct(){
        $this->WP_Widget( 'hot-list', __( '【Nine】热门', 'rt' ), array( 'description' => __( '热门描述', 'rt' ) ) );
    }
 
    function widget( $args, $instance ){
        extract( $args, EXTR_SKIP );
        echo $before_widget;

        $rt_config = rt_get_config();
        switch ($rt_config['widget_title_type']) {
            case '1': $widget_title_class='type-1'; break;
            case '2': $widget_title_class='type-2'; break;
            case '3': $widget_title_class='type-3'; break;
            default:  $widget_title_class=''; break;
        }

        $title = $instance['title'] ? $instance['title'] : __('热门文章', 'rt');

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $instance['posts_per_page'] ? $instance['posts_per_page'] : 4,
            'meta_key'  => 'post_views_count',
            'orderby'   => 'meta_value',
            'order' => 'DESC'
        );
        $query = new WP_Query($args);
        ?>
        <div class="hot widget-container">
            <div class="widget-header <?php echo $widget_title_class; ?>">
                <div class="widget-title"><?php echo $title ?></div>
            </div>
            <ul class="hot-list">
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : ?>
                        <?php $query->the_post(); ?>
                        <li>
                            <div class="hot-order"></div>
                            <div class="item">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <div class="time"><?php echo friendly_time(get_the_time('Y-m-d H:i:s')); ?></div>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </ul>
        </div>
        <?php
        echo $after_widget;
    }

    function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $posts_per_page = !empty($instance['posts_per_page']) ? $instance['posts_per_page'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">标题:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>">数量:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo esc_attr($posts_per_page); ?>">
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['posts_per_page'] = (!empty($new_instance['posts_per_page'])) ? strip_tags($new_instance['posts_per_page']) : '';
        return $instance;
    }
}


/**
 * 文章列表
 */
class ArticleWidget extends WP_Widget {
    function __construct(){
        $this->WP_Widget( 'article-list', __( '【Nine】文章列表', 'rt' ), array( 'description' => __( '文章列表描述', 'rt' ) ) );
    }
 
    function widget( $args, $instance ){
        extract( $args, EXTR_SKIP );
        echo $before_widget;

        $rt_config = rt_get_config();
        switch ($rt_config['widget_title_type']) {
            case '1': $widget_title_class='type-1'; break;
            case '2': $widget_title_class='type-2'; break;
            case '3': $widget_title_class='type-3'; break;
            default:  $widget_title_class=''; break;
        }

        wp_reset_postdata();

        $title = $instance['title'] ? $instance['title'] : __('文章列表', 'rt');

        $args = array(
                    // 'post_type' => 'posts',
                    'posts_per_page' => $instance['posts_per_page'] ? $instance['posts_per_page'] : 4,
                    'meta_key' => 'post_views_count',
                    'orderby' => 'meta_value',
                    'order' => 'DESC'
                );
        // if($instance['cat_id']){
        //     $args['cat'] = $instance['cat_id'];
        // }
        if($instance['cat_id']){
            global $wpdb;
            $sql = "SELECT taxonomy FROM {$wpdb->prefix}term_taxonomy WHERE term_id=%s";
            $res = $wpdb->get_row($wpdb->prepare($sql, $instance['cat_id']), ARRAY_A);

            if($res['taxonomy'] == 'category'){
                $args['cat'] = $instance['cat_id'];
            } else {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $res['taxonomy'],
                        'terms' => $instance['cat_id']
                    )
                );
            }
        }

        $post_query = new WP_Query($args);
        // p($post_query);
        ?>
        <div class="article widget-container">
            <div class="widget-header <?php echo $widget_title_class; ?>">
                <div class="widget-title">
                    <?php echo $title ?>
                </div>
            </div>
            <div class="article-list">
                <?php if ( $post_query->have_posts() ) : ?>
                    <?php while ( $post_query->have_posts() ) : ?>
                        <?php $post_query->the_post(); ?>
                    <div class="article-item"><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></div>
                    <?php endwhile; ?>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
        <?php
        echo $after_widget;
    }

    function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $posts_per_page = !empty($instance['posts_per_page']) ? $instance['posts_per_page'] : '';
        $cat_id = !empty($instance['cat_id']) ? $instance['cat_id'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">标题:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>">数量:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo esc_attr($posts_per_page); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('cat_id'); ?>">分类ID:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('cat_id'); ?>" name="<?php echo $this->get_field_name('cat_id'); ?>" value="<?php echo esc_attr($cat_id); ?>">
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['posts_per_page'] = (!empty($new_instance['posts_per_page'])) ? strip_tags($new_instance['posts_per_page']) : '';
        $instance['cat_id'] = (!empty($new_instance['cat_id'])) ? strip_tags($new_instance['cat_id']) : '';
        return $instance;
    }
}


/**
 * 图片列表
 */
class ImageWidget extends WP_Widget {
    function __construct(){
        $this->WP_Widget( 'image-list', __( '【Nine】图片列表', 'rt' ), array( 'description' => __( '图片列表描述', 'rt' ) ) );
    }
 
    function widget( $args, $instance ){
        extract( $args, EXTR_SKIP );
        echo $before_widget;

        $rt_config = rt_get_config();
        switch ($rt_config['widget_title_type']) {
            case '1': $widget_title_class='type-1'; break;
            case '2': $widget_title_class='type-2'; break;
            case '3': $widget_title_class='type-3'; break;
            default:  $widget_title_class=''; break;
        }

        $title = $instance['title'] ? $instance['title'] : __('图片列表', 'rt');

        wp_reset_postdata();
        $args = array(
            'posts_per_page' => $instance['posts_per_page'] ? $instance['posts_per_page'] : 4
        );
        // if($instance['cat_id']){
        //     $args['cat'] = $instance['cat_id'];
        // }
        if($instance['cat_id']){
            global $wpdb;
            $sql = "SELECT taxonomy FROM {$wpdb->prefix}term_taxonomy WHERE term_id=%s";
            $res = $wpdb->get_row($wpdb->prepare($sql, $instance['cat_id']), ARRAY_A);

            if($res['taxonomy'] == 'category'){
                $args['cat'] = $instance['cat_id'];
            } else {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $res['taxonomy'],
                        'terms' => $instance['cat_id']
                    )
                );
            }
        }

        $query = new WP_Query( $args );
        ?>
        <div class="item-list-container widget-container">
            <div class="widget-header <?php echo $widget_title_class; ?>">
                <div class="widget-title"><?php echo $title ?></div>
            </div>
            
            <div class="item-list-wrapper">
                <ul class='item-list'>
                    <?php if ( $query->have_posts() ) : ?>
                        <?php while ( $query->have_posts() ) : ?>
                        <?php
                        $query->the_post();
                        $current_post = get_post();
                        $thumbnail_image = wp_get_attachment_image_src(get_post_thumbnail_id($current_post->ID), 'thumbnail');
                        
                        $price = get_post_meta( $current_post->ID, 'price', true );
                        $price = $price ? number_format($price/100,2) : '';
                        ?>
                        
                        <li class='item-widget'>
                            <a href="<?php the_permalink() ?>">
                                <?php if (!empty($thumbnail_image)) :?>
                                    <img src="<?php echo $thumbnail_image[0] ?>" alt="<?php the_title(); ?>">
                                <?php else : ?>
                                    <img src="<?php echo $rt_config['default_image'] ?>">
                                <?php endif ?>
                                <div class="item-title"><?php the_title(); ?></div>
                                <?php if($price): ?>
                                    <div class="item-price"><?php echo $rt_options['item_currency_symbol']?> <?php echo $price; ?></div>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </ul>
            </div>
        </div>
        <?php
        echo $after_widget;
    }

    function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $posts_per_page = !empty($instance['posts_per_page']) ? $instance['posts_per_page'] : '';
        $cat_id = !empty($instance['cat_id']) ? $instance['cat_id'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">标题:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>">数量:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo esc_attr($posts_per_page); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('cat_id'); ?>">分类ID:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('cat_id'); ?>" name="<?php echo $this->get_field_name('cat_id'); ?>" value="<?php echo esc_attr($cat_id); ?>">
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['posts_per_page'] = (!empty($new_instance['posts_per_page'])) ? strip_tags($new_instance['posts_per_page']) : '';
        $instance['cat_id'] = (!empty($new_instance['cat_id'])) ? strip_tags($new_instance['cat_id']) : '';
        return $instance;
    }
}


/**
 * 分类列表
 */
class CategoryWidget extends WP_Widget {
    function __construct(){
        $this->WP_Widget( 'category-list', __( '【Nine】分类列表', 'rt' ), array( 'description' => __( '分类列表描述', 'rt' ) ) );
    }
 
    function widget( $args, $instance ){
        extract( $args, EXTR_SKIP );
        echo $before_widget;

        $rt_config = rt_get_config();
        switch ($rt_config['widget_title_type']) {
            case '1': $widget_title_class='type-1'; break;
            case '2': $widget_title_class='type-2'; break;
            case '3': $widget_title_class='type-3'; break;
            default:  $widget_title_class=''; break;
        }

        $title = $instance['title'] ? $instance['title'] : __('分类列表', 'rt');

        $child_categories = get_categories(array(
            'child_of' => $instance['cat_id'],
            'hide_empty' => false,
            'orderby' => 'term_group',
            'order' => 'ASC'
        ));
        ?>
        <div class="category widget-container">
            <div class="widget-header <?php echo $widget_title_class; ?>">
                <div class="widget-title">
                    <?php echo $title ?>
                </div>
            </div>
            <div class="category-list">
                <?php if ( $child_categories ) : ?>
                    <?php foreach ($child_categories as $k => $v): ?>
                        <div class="category-item"><a href="<?php echo get_category_link($v->term_id) ?>"><?php echo $v->name; ?></a></div>
                    <?php endforeach ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
        echo $after_widget;
    }

    function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $posts_per_page = !empty($instance['posts_per_page']) ? $instance['posts_per_page'] : '';
        $cat_id = !empty($instance['cat_id']) ? $instance['cat_id'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">标题:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>">数量:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo esc_attr($posts_per_page); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('cat_id'); ?>">分类ID:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('cat_id'); ?>" name="<?php echo $this->get_field_name('cat_id'); ?>" value="<?php echo esc_attr($cat_id); ?>">
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['posts_per_page'] = (!empty($new_instance['posts_per_page'])) ? strip_tags($new_instance['posts_per_page']) : '';
        $instance['cat_id'] = (!empty($new_instance['cat_id'])) ? strip_tags($new_instance['cat_id']) : '';
        return $instance;
    }
}




/**
 * 用户卡片
 */
class UserWidget extends WP_Widget {
    function __construct(){
        $this->WP_Widget( 'user-widget', __( '【Nine】用户卡片', 'rt' ), array( 'description' => __( '用户卡片描述', 'rt' ) ) );
    }
 
    function widget( $args, $instance ){
        extract( $args, EXTR_SKIP );
        echo $before_widget;

        global $wpdb;
        wp_reset_postdata();

        $rt_config = rt_get_config();
        switch ($rt_config['widget_title_type']) {
            case '1': $widget_title_class='type-1'; break;
            case '2': $widget_title_class='type-2'; break;
            case '3': $widget_title_class='type-3'; break;
            default:  $widget_title_class=''; break;
        }

        $title = $instance['title'];

        $user_id = get_the_author_meta('ID');
        $avatar_url = get_template_directory_uri() . '/assets/images/avatar.jpg';
        $avatar_id = get_the_author_meta('user_avatar_attachment_id');
        $avatar = wp_get_attachment_image_src($avatar_id, 'medium');
        $avatar_url = isset($avatar[0]) ? $avatar[0] : $avatar_url;

        $nickname = get_user_meta($user_id, 'nickname', true);

        $sql = "SELECT count(id) AS counter FROM wp_posts WHERE post_author=%d";
        $res = $wpdb->get_results($wpdb->prepare($sql, [$user_id]), ARRAY_A);
        $posts_counter = $res[0]['counter'] ? $res[0]['counter'] : 0;

        $sql = "SELECT count(comment_ID) AS counter FROM wp_comments WHERE user_id=%d";
        $res = $wpdb->get_results($wpdb->prepare($sql, [$user_id]), ARRAY_A);
        $comments_counter = $res[0]['counter'] ? $res[0]['counter'] : 0;

        $sql = "SELECT count(id) AS counter FROM wp_rt_star WHERE type='like' AND user_id=%d";
        $res = $wpdb->get_results($wpdb->prepare($sql, [$user_id]), ARRAY_A);
        $like_counter = $res[0]['counter'] ? $res[0]['counter'] : 0;

        ?>

        <?php if($user_id):?>
            <div class="user-card-container widget-container">
                <?php if($title):?>
                <div class="widget-header <?php echo $widget_title_class; ?>">
                    <div class="widget-title"><?php echo $title ?></div>
                </div>
                <?php endif ?>
                <div class="user-header">
                    <a href="javascript:;" class="user-avatar">
                        <img src="<?php echo $avatar_url?>">
                    </a>
                    <div class="nickname"><?php echo $nickname ?></div>
                    <div class="description"></div>
                </div>
                <div class="user-meta">
                    <div class="meta-item">
                        <span><?php echo $posts_counter ?></span> <span>文章</span>
                    </div>
                    <div class="meta-item">
                        <span><?php echo $comments_counter ?></span> <span>评论</span>
                    </div>
                    <div class="meta-item">
                        <span><?php echo $like_counter ?></span> <span>收藏</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php
        echo $after_widget;
    }

    function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $posts_per_page = !empty($instance['posts_per_page']) ? $instance['posts_per_page'] : '';
        $cat_id = !empty($instance['cat_id']) ? $instance['cat_id'] : '';
        $post_type = !empty($instance['post_type']) ? $instance['post_type'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">标题:</label>
            <input type="text" class="" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}



function rt_add_widget(){
    register_widget('HotWidget');
    register_widget('ArticleWidget');
    register_widget('ImageArticleWidget');
    register_widget('ImageWidget');
    register_widget('CategoryWidget');
    register_widget('UserWidget');
}

add_action( 'widgets_init', 'rt_add_widget' );
