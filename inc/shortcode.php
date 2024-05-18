<?php

/**
 * @param  $atts    shortcode 的各个参数
 * @param  $content 标签内的内容
 * @return          html string
 */
function test_shortcode($atts, $content = null){
    // 使用 extract 函数解析标签内的参数
    extract(shortcode_atts(array( "title" => '标题' ), $atts));


    return '<div class="myshortcode">
            <h3>'. $title .'</h3>
            <p>
                '. $content .'
            </p>
        </div>';
}
 
add_shortcode("test", "test_shortcode");



