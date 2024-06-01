<?php
$rt_config = rt_get_config();


function add_theme_support_all()
{
    //文章编辑页，没有页面属性选择模板，添加注释 Template Name

    // 新的 WordPress 网页标题设置方法
    add_theme_support('title-tag');

    /* Enable support for Post Thumbnails on posts and pages.
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    // Set post thumbnail size.
    set_post_thumbnail_size(1200, 9999);
}
add_action('after_setup_theme', 'add_theme_support_all');



/**
 * 支持文章排序
 */
function rt_add_post_attributes()
{
  add_post_type_support('post', 'page-attributes');
}
add_action('init', 'rt_add_post_attributes', 500);

function rt_pre_insert_post($post, \WP_REST_Request $request)
{
  $body = $request->get_body();
  if ($body) {
    $body = json_decode($body);
    if (isset($body->menu_order)) {
      $post->menu_order = $body->menu_order;
    }
  }
  return $post;
}
add_filter('rest_pre_insert_post', 'rt_pre_insert_post', 12, 2);

function rt_prepare_post(\WP_REST_Response $response, $post, $request)
{
  $response->data['menu_order'] = $post->menu_order;
  return $response;
}
add_filter('rest_prepare_post', 'rt_prepare_post', 12, 3);



// 不显示顶部的工具栏
if($rt_config['show_admin_bar'] != 1){
  show_admin_bar(false);
}


// function smartwp_reverse_comment_order( $comments ) {
//  return array_reverse( $comments );
// }
// add_filter ('comments_array', 'smartwp_reverse_comment_order');

/**
 * 设置显示字数
 */
add_filter('excerpt_length', 'rt_excerpt_length', 999);
function rt_excerpt_length($length) {
    return 200;
}


/**
 * 禁用更新
 */
if($rt_config['update_is_on'] == 0){
    add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) ); // 移除版本更新提示
    add_filter( 'pre_site_transient_update_plugins', create_function( '$b', "return null;" ) ); // 移除插件更新提示
    add_filter('pre_site_transient_update_core', create_function('$a', "return null;")); // 移除主题更新提示 
    add_filter('pre_site_transient_update_themes', create_function('$a', "return null;"));  // 关闭插件提示
    remove_action('admin_init', '_maybe_update_core');    // 禁用 WordPress 检查更新
    remove_action('admin_init', '_maybe_update_plugins'); // 禁用 WordPress 更新插件
    remove_action('admin_init', '_maybe_update_themes');  // 禁用 WordPress 更新主题
    // add_filter( 'automatic_updater_disabled', '__return_true' );
    remove_action( 'load-update-core.php', 'wp_update_plugins' );
    remove_action ('load-update-core.php', 'wp_update_themes'); 
    
    function rt_remove_php_nag() {
        remove_meta_box( 'dashboard_php_nag', 'dashboard', 'normal' );// 建议更新PHP版本
    }
    add_action( 'wp_dashboard_setup', 'rt_remove_php_nag' );
}


/**
 * 是否禁用古腾堡编辑器，启用经典编辑器
 */
if ($rt_config['editor_type'] == 1) {
    /* Disable Gutenberg Block Editor */
    add_filter('use_block_editor_for_post', '__return_false', 10);
    /* Disable Widgets Block Editor */
    // add_filter( 'use_widgets_block_editor', '__return_false' );
    
    //添加HTML编辑器自定义快捷标签按钮
    add_action('after_wp_tiny_mce', 'add_button_mce');
    function add_button_mce($mce_settings)
    {
        ?>
        <script type="text/javascript">
        QTags.addButton( 'hr', 'hr', "<hr />", "" );
        QTags.addButton( 'h1', 'h1', "<h1>", "</h1>" );
        QTags.addButton( 'h2', 'h2', "<h2>", "</h2>" );
        QTags.addButton( 'h3', 'h3', "<h3>", "</h3>" );
        QTags.addButton( 'p', 'p', "<p>", "</p>" );
        QTags.addButton( 'pre', 'pre', "<pre>", "</pre>" );
        </script>
        <?php
    }
    
    // 修改编辑器内容样式
    function rt_add_editor_style( $mceInit ) {
      $styles = "#tinymce{ font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif; } pre { font-size:13px; background-color:#f0f0f0; padding:8px;border-radius:3px}";
    
      if ( !isset( $mceInit['content_style'] ) ) {
        $mceInit['content_style'] = $styles . ' ';
      } else {
        $mceInit['content_style'] .= ' ' . $styles . ' ';
      }
      return $mceInit;
    }
    add_filter( 'tiny_mce_before_init', 'rt_add_editor_style' );
}


/* 删除登录页面底部的语言切换 */
add_filter('login_display_language_dropdown', '__return_false');


/* 修改登录页的样式 */
function custom_loginlogo() {
    $rt_config = rt_get_config();
    echo '<style type="text/css">
    h1 a {
        background-image: url('. $rt_config['site_logo'] .') !important;
        max-width:180px !important;
        max-height:70px !important;
        background-size: 100% !important;
        object-fit:cover;
    }
    #loginform{
        border-radius: 5px;
    }
    </style>';
}
add_action('login_head', 'custom_loginlogo'); 


/* 文章自动保存 */
if($rt_config['editor_revision'] == 0){
    //禁用文章自动保存
    add_action('wp_print_scripts','rt_not_autosave');
    function rt_not_autosave(){
        wp_deregister_script('autosave');
    }
    //禁用文章修订版本
    add_filter( 'wp_revisions_to_keep', 'rt_revisions_to_keep', 10, 2 );
    function rt_revisions_to_keep( $num, $post ) {
        return 0;
    }
}


//开启友情链接管理
add_filter( 'pre_option_link_manager_enabled', '__return_true' );


/* 登录后跳转控制 */
function rt_login_redirect( $redirect_to, $request, $user ) {
    $rt_config = rt_get_config();
    // 如果登录成功并且用户是管理员，则跳转到后台管理页面
    if ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) {
        return admin_url();
    } else {
        // 否则跳转到个人资料页面
        if($rt_config['user_center_is_on']){
            return home_url( '/users/' .  $user->ID );
        }
        return home_url( '/wp-admin/profile.php' );
    }
}

add_filter( 'login_redirect', 'rt_login_redirect', 10, 3 );


/**
 * 自定义图片名称
 */
add_filter('wp_handle_upload_prefilter', 'rt_upload_filter');
function rt_upload_filter($file)
{
    $info = pathinfo($file['name']);
    $ext = $info['extension'];

    global $current_user;

    $filedate = date('YmdHis') . rand(10, 99) . $current_user->ID; //为了避免时间重复，再加一段2位的随机数
    $file['name'] = $filedate . '.' . $ext;
    return $file;
}



if ($rt_config['rt_email_is_on'] == 1) {
    add_action('phpmailer_init', 'mail_smtp');
    function mail_smtp($phpmailer)
    {
        $config = rt_get_config();

        $phpmailer->IsSMTP();
        $phpmailer->SMTPAuth     = true;
        $phpmailer->SMTPSecure   = "ssl";
        $phpmailer->Port         = $config['smtp_port'];
        $phpmailer->Host         = $config['smtp_host'];
        $phpmailer->Username     = $config['smtp_username'];
        $phpmailer->Password     = $config['smtp_password'];
    }

    add_filter('wp_mail_from', 'rt_wp_mail_from');
    function rt_wp_mail_from()
    {
        $config = rt_get_config();
        return $config['smtp_username'];
    }

    add_filter('wp_mail_from_name', 'mail_from_name');
    function mail_from_name()
    {
        $config = rt_get_config();
        return $config['smtp_nicename'];
    }

    // 保存邮件发送错误信息
    add_action('wp_mail_failed', 'rt_add_mail_error');
    function rt_add_mail_error($wp_error)
    {
        update_option('rt_mail_error', $wp_error->get_error_message('wp_mail_failed'));
    }
}
