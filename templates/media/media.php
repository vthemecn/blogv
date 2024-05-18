<?php
$rt_config = rt_get_config();

$author_id = get_the_author_id();
$avatar = rt_get_custom_avatar_url($author_id);

$user_center_url = $rt_config['user_center_is_on'] ? '/users/'.$post->post_author : 'javascript:;';
?>

<div class="media-item">
    <div class="media-thumbnail">
        <a href="<?php the_permalink() ?>" target="_blank">
            <?php if (has_post_thumbnail()) { ?>
                <?php the_post_thumbnail(); ?>
            <?php } else { ?>
                <img src="<?php echo $rt_config['default_image'] ?>">
            <?php } ?>
        </a>
    </div>
    
    <div class="media-main">
        <div class="media-title">
            <?php if(is_sticky()): ?>
                <div class="sticky">置顶</div>
            <?php endif ?>
            <a href="<?php the_permalink() ?>" target="_blank"><?php the_title(); ?></a>
        </div>
        <div class="media-description">
            <?php echo get_the_excerpt(); ?>
        </div>
        <div class="media-meta">
            <a class="author" href="<?php echo $user_center_url ?>" target="_blank">
                <img src="<?php echo $avatar ?>">
                <span>
                    <?php echo get_the_author_meta('display_name', $post->post_author) ?>
                </span>
            </a>
            <span class="date">
                <i class="iconfont">&#xe76d;</i><?php the_time('Y-m-d'); ?>
            </span>
            <span class="hit-counter">
                <i class="iconfont">&#xe752;</i><?php echo getPostViews(get_the_ID()); ?>
            </span>
            <?php if($rt_config['show_comments_counter']):?>
                <span>
                    <i class="iconfont">&#xe8a6;</i><?php echo $post->comment_count; ?>
                </span>
            <?php endif ?>
        </div>
    </div>
</div>

