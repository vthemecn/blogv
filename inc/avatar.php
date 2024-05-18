<?php


/**
 * 增加修改用户头像功能
 */
function rt_add_admin_js(){ 
    wp_enqueue_media();
    wp_enqueue_script('rt-uploader', get_stylesheet_directory_uri().'/assets/js/lib/avatar.js', array('jquery'), false, true );
}
add_action('admin_enqueue_scripts', 'rt_add_admin_js');


/**
 * 添加上传字段
 */
function rt_add_profile_fields( $user ) {
    $avatar_id = ($user!=='add-new-user') ? get_user_meta($user->ID, 'user_avatar_attachment_id', true): false;

    if( !empty($avatar_id) ){
        $image = wp_get_attachment_image_src( $avatar_id, 'thumbnail' );
    }
    ?>
    <input type="hidden" name="rt_avatar_id" id="rt_avatar_id" value="<?php echo !empty($avatar_id) ? $avatar_id : ''; ?>" />
    <?php
}
add_action( 'show_user_profile', 'rt_add_profile_fields' );
add_action( 'edit_user_profile', 'rt_add_profile_fields' );
add_action( 'user_new_form', 'rt_add_profile_fields' );


/**
 * 更新用户头像
 */
function rt_update_profile($user_id){
    if( current_user_can('edit_users') ){
        $avatar_id = empty($_POST['rt_avatar_id']) ? '' : $_POST['rt_avatar_id'];
        update_user_meta($user_id, 'user_avatar_attachment_id', $avatar_id);
    }
}
add_action('profile_update', 'rt_update_profile');
add_action('user_register', 'rt_update_profile');


/*
 * 设置用户头像(系统默认使用的是网络头像)
 */
add_filter('get_avatar', 'rt_custom_avatar', 1, 5);
function rt_custom_avatar($avatar, $id_or_email, $size, $default, $alt)
{
    // $id_or_email->user_id
    $attachment_id = get_user_meta($id_or_email, "user_avatar_attachment_id")[0];
    $avatar = wp_get_attachment_image_src($attachment_id, 'thumbnail')[0];
    if(!$avatar) {
        $avatar = get_stylesheet_directory_uri() . '/assets/images/avatar.jpg';
    }
    $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
    return $avatar;
}



/**
 * 覆盖用户上传头像按钮
 */
function set_profile_avatar() {
    // $current_user = wp_get_current_user();
    if (current_user_can( 'upload_files' ) ) {
        return '<a class="button rt-avatar"  id="rt-avatar">上传头像</a>';
    } else {
        return '';
    }
}
add_filter( 'user_profile_picture_description', 'set_profile_avatar', 1 );



