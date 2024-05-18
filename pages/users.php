<?php
/**
 * `/inc/rewrite.php` 所引用的文件
 */

global $wp_query;
global $current_user;

$param_user_id = $wp_query->query_vars['user_id'];

$param_user = get_user_by_id($param_user_id); 

switch($param_user['gender']){
    case '0': $param_user['gender'] = '保密'; break;
    case '1': $param_user['gender'] = '男'; break;
    case '2': $param_user['gender'] = '女'; break;
    default: $param_user['gender'] = '保密'; break;
}

$has_auth = false;
if( $param_user_id ==  $current_user->ID || current_user_can( 'manage_options' ) ){
    $has_auth = true;
}


get_header();
?>



<div class="user-center-container">
    <?php // require_once get_template_directory() . '/templates/users/banner.php'; ?>

    <?php require_once get_template_directory() . '/templates/users/sider.php'; ?>

    <div class="user-center-panel">
        <h3>基本资料</h3>

        <div class="">
            <?php /*
            <div class="user-item">
                <div class="user-item-header">账号:</div>
                <div class="user-item-body"><?php echo $param_user['user_login']?></div>
            </div>
            */ ?>
           
            <div class="user-item">
                <div class="user-item-header">昵称:</div>
                <div class="user-item-body"><?php echo $param_user['nickname']?></div>
            </div>
            <div class="user-item">
                <div class="user-item-header">性别:</div>
                <div class="user-item-body"><?php echo $param_user['gender'] ?></div>
            </div>

            <?php if($has_auth): ?>
                <div class="user-item">
                    <div class="user-item-header">邮箱:</div>
                    <div class="user-item-body">
                        <?php echo $param_user['user_email'] ?>
                        <span>仅自己可见</span>    
                    </div>
                </div>
            <?php endif ?>

            <?php if($has_auth): ?>
            <div class="user-item">
                <div class="user-item-header">手机号:</div>
                <div class="user-item-body"><?php echo $param_user['mobile'] ?></div>
            </div>
            <?php endif ?>
            
            <div class="user-item">
                <div class="user-item-header">简介:</div>
                <div class="user-item-body description">
                    <?php echo $param_user['description'] ?>
                </div>
            </div>
            <?php
            /*
            <div class="user-item achievement">
                <div class="achievement-widget">
                    <span>9</span>
                    <span>关注</span>
                </div>
                <div class="achievement-widget">
                    <span>2000</span>
                    <span>粉丝</span>
                </div>
                <div class="achievement-widget">
                    <span>88</span>
                    <span>收藏</span>
                </div>
                <div class="achievement-widget">
                    <span>2000</span>
                    <span>点赞</span>
                </div>
                <div class="achievement-widget">
                    <span>56</span>
                    <span>文章</span>
                </div>
            </div>
            */
            ?>
            
        </div>
        
    </div>
    
</div>

<input type="hidden" name="wp_create_nonce" value="<?php echo wp_create_nonce('wp_rest'); ?>">

<?php get_footer(); ?>
