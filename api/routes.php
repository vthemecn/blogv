<?php
require_once TEMP_DIR . '/api/home.php';

add_action('rest_api_init', function (){
    /**
     * 获取更多文章
     * 路径：/wp-json/vtheme/v1/home/get-more-articles
     * posts_per_page, page
     */
    register_rest_route('vtheme/v1', 'home/get-more-articles', [
        'methods'  => 'GET',
        'callback' => function ($request) {
            $homeController = new \vtheme\api\HomeController();
            return $homeController->getMoreArticles($request);
        }
    ]);
    
    /**
     * 测试邮箱设置
     * 路径：/wp-json/vtheme/v1/home/get-more-articles
     * posts_per_page, page
     */
    register_rest_route('vtheme/v1', 'sendmail-test', [
        'methods'  => 'POST',
        'callback' => function ($request) {
            $homeController = new \vtheme\api\HomeController();
            return $homeController->sendmail($request);
        }
    ]);

    // /wp-json/vtheme/v1/home/test
    register_rest_route('vtheme/v1', 'home/test', [
        'methods'  => 'GET',
        'callback' => function ($request) {
            $user_id = vt_get_user_id();
            echo $user_id;

            global $current_user;
            p($current_user);
        }
    ]);

});


