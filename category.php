<?php

/**
 * 不同分类使用不同模版
 */

global $wp_query;
$cat_ID = get_query_var('cat');
$rt_cat_tpl = get_option('rt_cat_tpl_' . $cat_ID);

if ($rt_cat_tpl == 1) {
    get_template_part('templates/categories/category-cards');
} else {
    get_template_part('templates/categories/category-list');
}
