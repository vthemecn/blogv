<?php
$vt_config = vt_get_config();
?>

<div class="card-item">
    <a class="card-image" href="<?php the_permalink() ?>">
        <?php
        $cur_post = get_post();
        $thumbnail_image = wp_get_attachment_image_src(get_post_thumbnail_id($cur_post->ID), 'medium');
        if (!empty($thumbnail_image)) :
        ?>
            <img src="<?php echo $thumbnail_image[0] ?>" alt="<?php the_title(); ?>">
        <?php else : ?>
            <img src="<?php echo $vt_config['default_image'] ?>">
        <?php endif ?>
    </a>
    <div class="item-info">
        <a class="title" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
         <?php  /* ?>
        <div class="item-price">
            <span class="price">
                <i>¥</i><b>5.41</b>
            </span>
            <span class="price item-delete">
                <i>¥</i><b>50.80</b>
            </span>
        </div>
        <?php */ ?>
    </div>
    <?php /* ?>
    <div class="card-meta">
        <span class="meta date">
            <i class="iconfont">&#xe76d;</i><?php the_time('Y-m-d'); ?>
        </span>
        <span class='meta hit-conuter'>
            <i class="iconfont">&#xe752;</i><?php echo getPostViews(get_the_ID()); ?>
        </span>
        <span class='meta hit-conuter'>
            <i class="iconfont">&#xe663;</i>238
        </span>
    </div>
    <?php */ ?>
</div>