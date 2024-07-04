<?php

/**
 * 不同分类使用不同模版
 */

global $wp_query;
$cat_ID = get_query_var('cat');
$vt_cat_tpl = get_option('vt_cat_tpl_' . $cat_ID);

if ($vt_cat_tpl == 1) {
    get_template_part('templates/categories/category-cards');
} else {
    get_template_part('templates/categories/category-list');
}
