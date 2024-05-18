<?php
/**
 * `/inc/rewrite.php` 所引用的文件
 */

global $wp_query;
global $current_user;

$param_user_id = $wp_query->query_vars['user_id'];
$usersService = new \api\users\UsersService();
$param_user = $usersService->getUserById($param_user_id);


global $wpdb;

// 每页显示条数
$pageSize = 3;
// 数据总数
$sql = "SELECT s.id AS star_id, p.post_title FROM wp_rt_star AS s
        LEFT JOIN wp_posts AS p ON p.ID=s.object_id 
        WHERE `type`='like' AND user_id=%d";
$counter = $wpdb->query($wpdb->prepare($sql, $param_user_id), ARRAY_A);
// p($counter);
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = $pageSize * ($page - 1);

$sql = "SELECT s.id AS star_id, s.user_id, s.object_id, p.post_title FROM wp_rt_star AS s
        LEFT JOIN wp_posts AS p ON p.ID=s.object_id 
        WHERE `type`='like' AND user_id=%d
        ORDER BY s.id DESC LIMIT %d OFFSET %d";
$list = $wpdb->get_results( $wpdb->prepare( $sql, $param_user_id, $pageSize, $start ));


foreach($list as $k=>$v){
    $list[$k]->nickname = get_user_meta($v->user_id, 'nickname', true);
    $list[$k]->avatar = rt_get_custom_avatar_url($v->user_id);
    $list[$k]->user_id = $v->user_id;
    $list[$k]->post = get_post($v->object_id);
    $list[$k]->hit_counter = get_post_meta($v->object_id, 'post_views_count', true);
    $list[$k]->thumbnail = get_the_post_thumbnail_url($v->object_id, 'thumbnail' );
}

// 分页类
require_once get_template_directory() . '/inc/paginator/Paginator.php';
$rt_page = new \Paginator($counter, $pageSize);
$rt_page->setQueryField(['page'=>'page']);
$rt_page->pagerCount = 6; // 显示页数
$rt_page->prevText = '上一页';
$rt_page->nextText = '下一页';


get_header();
?>


<div class="user-center-container">
    <?php require_once get_template_directory() . '/templates/users/banner.php'; ?>

    <?php require_once get_template_directory() . '/templates/users/sider.php'; ?>

    <div class="user-center-panel">
        <h3>我的点赞</h3>

        <div>
            <?php if(!$list): ?>
                <div class="user-no-content">
                    <img src="<?php bloginfo('template_url'); ?>/assets/images/empty.png">
                    <span>还没有点赞</span>
                </div>
            <?php endif ?>
            
            <?php foreach($list as $k=>$v): ?>
                <div class="media-item type-1">
                    <div class="media-title">
                        <a href="<?php echo get_permalink($v->object_id) ?>">
                            <?php  echo $v->post_title ?>
                        </a>
                    </div>
                    <div class="media-widget">
                        <div class="media-content">
                            <div class="media-description">
                                <?php echo get_the_excerpt($v->post); ?>
                            </div>
                            <div class="media-meta">
                                <span>
                                    <i class="iconfont">&#xe76d;</i>
                                    <?php echo wp_date('Y-m-d', strtotime($v->post->post_date) ); ?>
                                </span>
                                <span>
                                    <i class="iconfont">&#xe752;</i>
                                    <?php echo $v->hit_counter ?>
                                </span>
                                <span class="meta author">
                                    <img src="<?php echo $v->avatar ?>">
                                    <span>
                                        <a href="/users/<?php echo $v->user_id ?>" target='_blank'><?php echo $v->nickname ?></a>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <?php if ($v->thumbnail) { ?>
                            <div class="media-thumbnail">
                                <img src="<?php echo $v->thumbnail ?>" />
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php echo $counter ? $rt_page->links(['pager']) : '' ?>
    </div>
</div>


<input type="hidden" name="wp_create_nonce" value="<?php echo wp_create_nonce('wp_rest'); ?>">


<script type="module" src="<?php bloginfo('template_url'); ?>/assets/js/lib/axios/axios.esm.js"></script>
<script type="module" src="<?php bloginfo('template_url'); ?>/assets/js/src/toast.js"></script>

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/inc/page/css/pagination.css">

<?php get_footer(); ?>
