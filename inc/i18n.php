<?php

/**
 * 语言文件支持
 */
add_action('after_setup_theme', 'rt_theme_load_theme_textdomain');
function rt_theme_load_theme_textdomain()
{
    load_theme_textdomain('rt', get_template_directory() . '/inc/languages');
}

/**
 * 根据设置，修改当前语言
 */
add_filter('locale', 'rt_theme_localized');
function rt_theme_localized($locale)
{
    $rt_config = rt_get_config();
    return $rt_config['language'] ? $rt_config['language'] : 'zh_CN';
}
