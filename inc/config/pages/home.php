<?php
/*
 * 首页调用
 *
 * @author: soushenji <soushenji@qq.com>
 * @link: https://github.com/soushenji
 */

defined('ABSPATH') || exit;

$config = rt_get_config();
// p($config['_home_options']);
// 

$args = array(
    'type' => 'post',
    'child_of' => 0,
    'parent' => '',
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => 0,
    'hierarchical' => 1,
    'exclude' => '',
    'include' => '',
    'number' => '',
    'taxonomy' => 'category',
    'pad_counts' => false
);
$categories = get_categories($args);
?>


<style type="text/css">
.home-widget-container{
    width: 100%;
    max-width: 600px;
    display: flex;
    justify-content: space-between;
    margin: 20px 0 20px;
}
.widget-wrapper{
    width: 49%;
    border: 2px dashed #ccc;
    padding: 8px 8px;
    box-sizing: border-box;
}
.widget-wrapper .header{
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    padding: 5px 0px;
    background-color: #dcdcde;
}

.widget-wrapper .widget-item-list {
    margin-top: 10px;
}
.widget-wrapper .widget-item-list .widget-item{
    box-sizing: border-box;
    border: 1px solid #c3c4c7;
    margin-top: 10px;
    padding: 5px 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.widget-item-list .widget-item{}
.widget-item-list .widget-item .action{
    display: flex;
}
.widget-item-list .widget-item label{
    font-size: 14px;
}
.widget-item-list .widget-item a{
    display: flex;
    border: 1px solid #c3c4c7;
    width: 23px;
    height: 23px;
    border-radius: 50%;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    font-size: 25px;
    margin-left: 3px;
}
.widget-wrapper.disabled a[up],
.widget-wrapper.disabled a[down],
.widget-wrapper.disabled a[remove]{ display: none; }
.widget-wrapper.enabled a[add]{ display: none; }


.widget-options-container{
    margin-top: 60px;
}
.widget-options-container .nav{
    display: flex;
}
.widget-options-container .nav a{
    display: block;
    min-width: 50px;
    height: 25px;
    line-height: 25px;
    text-align: center;
    border: 1px solid #ccc;
    text-decoration: none;
    margin-right: 10px;
    color: #646970;
    padding: 0 5px;
}
.widget-options-container .nav a.active{
    color: #2271b1;
    border-color: #2271b1;
}
.widget-options-container .options-content{
    padding: 0 0 20px 0;
    min-height: 200px;
    display: none;
}
.widget-options-container .options-content.active{
    display: block;
}
</style>


<form action="" method="post" enctype="multipart/form-data" name="op_form" id="op_form">
    <div class="home-widget">
        <div class="home-widget-container">
            <div class="widget-wrapper enabled">
                <div class="header">显示的模块</div>
                <div class="widget-item-list">
                    <?php if($config['_home_options']['enabled']):?>
                        <?php foreach($config['_home_options']['enabled'] as $k=>$v): ?>
                            <div class="widget-item">
                                <label><?php echo $v?></label>
                                <div class="action">
                                    <a href="javascript:;" up>
                                        <span class="dashicons dashicons-arrow-up-alt2"></span>
                                    </a>
                                    <a href="javascript:;" down>
                                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                                    </a>
                                    <a href="javascript:;" remove>
                                        <span class="dashicons dashicons-minus"></span>
                                    </a>
                                    <a href="javascript:;" add>
                                        <span class="dashicons dashicons-plus-alt2"></span>                                    
                                    </a>
                                </div>
                                <input type="hidden" name="_home_options[enabled][<?php echo $k?>]" value="<?php echo $v?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="widget-wrapper disabled">
                <div class="header">隐藏的模块</div>
                <div class="widget-item-list">
                    <?php if($config['_home_options']['disabled']):?>
                        <?php foreach($config['_home_options']['disabled'] as $k=>$v): ?>
                            <div class="widget-item">
                                <label><?php echo $v?></label>
                                <div class="action">
                                    <a href="javascript:;" up>
                                        <span class="dashicons dashicons-arrow-up-alt2"></span>
                                    </a>
                                    <a href="javascript:;" down>
                                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                                    </a>
                                    <a href="javascript:;" remove>
                                        <span class="dashicons dashicons-minus"></span>
                                    </a>
                                    <a href="javascript:;" add>
                                        <span class="dashicons dashicons-plus-alt2"></span>                                    
                                    </a>
                                </div>
                                <input type="hidden" name="_home_options[disabled][<?php echo $k?>]" value="<?php echo $v?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif ?>
                </div>
            </div>         
        </div>

        <?php
        $active = isset($_GET['tab']) ? $_GET['tab'] : 'slider';
        ?>
        <div class="widget-options-container">
            <ul class="nav">
                <li><a href="javascript:;" class="<?php echo $active=='slider' ? 'active' : ''?>" data-id="slider">幻灯片设置</a></li>
                <li><a href="javascript:;" class="<?php echo $active=='article' ? 'active' : ''?>" data-id="article">最新文章模块</a></li>
                <li><a href="javascript:;" class="<?php echo $active=='search' ? 'active' : ''?>" data-id="search">搜索条模块</a></li>
                <li><a href="javascript:;" class="<?php echo $active=='links' ? 'active' : ''?>" data-id="links">友情链接模块</a></li>
            </ul>


            <!-- 幻灯片设置 -->
            <div class="options-content slider <?php echo $active=='slider' ? 'active' : ''?>">
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="_home_options[sliders_height]">幻灯片高度</label></th>
                            <td><input type="text" name="_home_options[sliders_height]" id="_home_options[sliders_height]" value="<?php echo $config['_home_options']['sliders_height'] ?>" class="regular-text" placeholder="幻灯片高度"></td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="slider_img_0">幻灯片</label></th>
                            <td>
                                <div class="slider-list">
                                  <?php
                                  if($config['_home_options']['sliders']):
                                      foreach($config['_home_options']['sliders'] as $k=>$v):
                                  ?>
                                      <div class="number-item">
                                          <img class="my-img-preview" src="<?php echo $v['pic-url'] ?>" style="display:block;">
                                          <input type="text" name="_home_options[sliders][<?php echo $k ?>][pic-url]" value="<?php echo $v['pic-url'] ?>" class="pic-input" placeholder="图片地址">
                                          <input type="text" name="_home_options[sliders][<?php echo $k ?>][title]" value="<?php echo $v['title'] ?>" class="" placeholder="标题">
                                          <input type="text" name="_home_options[sliders][<?php echo $k ?>][description]" value="<?php echo $v['description'] ?>" class="" placeholder="描述">
                                          <input type="text" name="_home_options[sliders][<?php echo $k ?>][url]" value="<?php echo $v['url'] ?>" class="" placeholder="链接地址">
                                          <div class="action">
                                              <a href="javascript:;" class="upload-slider-button">上传</a>
                                              <a href="javascript:;" class="delete-slider-button">删除</a>
                                          </div>
                                      </div>
                                  <?php
                                      endforeach;
                                  endif;
                                  ?>
                                </div>
                                <button type="button" class="add-slider-button">增加幻灯片</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <!-- 最新文章模块 -->
            <div class="options-content article <?php echo $active=='article' ? 'active' : ''?>">
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="_home_options[artilces_title]">文章模块标题</label></th>
                            <td>
                                <input type="text" name="_home_options[artilces_title]" id="_home_options[artilces_title]" 
                                value="<?php echo $config['_home_options']['artilces_title']; ?>" 
                                class="regular-text" placeholder="">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="artilces_not_in_ids">要排除的分类</label></th>
                            <td>
                                <input type="text" name="_home_options[artilces_not_in_ids]" 
                                    id="_home_options[artilces_not_in_ids]"
                                    value="<?php echo $config['_home_options']['artilces_not_in_ids']; ?>" 
                                    class="regular-text" placeholder="">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="_home_options[articles_auto_load]">自动加载</label></th>
                            <td>
                                <label>
                                    <input name="_home_options[articles_auto_load]" type="radio" 
                                        value="1" <?php if ($config['_home_options']['articles_auto_load'] == 1) { ?>checked="checked" <?php } ?>>
                                    开启
                                </label>
                                <label>
                                    <input name="_home_options[articles_auto_load]" type="radio" 
                                        value="0" <?php if ($config['_home_options']['articles_auto_load'] == 0) { ?>checked="checked" <?php } ?>>
                                    关闭
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 搜索模块 -->
            <div class="options-content search <?php echo $active=='search' ? 'active' : ''?>">
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="list_cards_num">搜索条主标题</label></th>
                            <td><input type="text" name="list_cards_num" id="list_cards_num" value="<?php echo ($config['list_cards_num']); ?>" class="regular-text" placeholder=""></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="list_cards_num">搜索条描述</label></th>
                            <td><input type="text" name="list_cards_num" id="list_cards_num" value="<?php echo ($config['list_cards_num']); ?>" class="regular-text" placeholder=""></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="meta_description">搜索条背景图片</label></th>
                            <td>
                                <div>
                                    <?php if ($config['site_logo']) { ?>
                                        <img class="my-img-preview" src="<?php echo $config['site_logo']; ?>" style="display:block;">
                                    <?php } else { ?>
                                        <img class="my-img-preview" src="" style="display:none;">
                                    <?php } ?>
                                    <input type="text" name="site_logo" id="site_logo" value="<?php echo ($config['site_logo']); ?>" class="regular-text image-input" />
                                    <button type="button" class="upload-button">上传</button>
                                    <button type="button" class="delete-button">删除</button>
                                    <p class="description" id="tagline-description">图片尺寸 180*50</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 友情链接模块 -->
            <div class="options-content links <?php echo $active=='links' ? 'active' : ''?>">
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="_home_options[links_title]">友情链接标题</label></th>
                            <td>
                                <input type="text" name="_home_options[links_title]" id="_home_options[links_title]" 
                                value="<?php echo $config['_home_options']['links_title']; ?>" 
                                class="regular-text" placeholder="">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="_home_options[links_cat_id]">选择分类</label></th>
                            <td>
                                <select name="_home_options[links_cat_id]" id="_home_options[links_cat_id]">
                                    <option value="0">请选择</option>
                                    <?php foreach ($categories as $k => $v) : ?>
                                        <?php $selected = $v->term_id == $rt_config['_home_options']['links_cat_id'] ? ' selected="selected"' : ""; ?>
                                        <option <?php echo $selected; ?> value="<?php echo $v->term_id; ?>"><?php echo $v->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="description">请选择相关栏目所调用的文章分类</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        
    </div>
    <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('rt_options_update') ?>">
    <input type="hidden" name="_action" value="rt_options_update">
    <input type="submit" name="input_save" value="保存" class="button button-primary" />
</form>


<!-- 幻灯片模块模板 -->
<div style="display: none;" id="slider-item-template">
    <div class="number-item">
        <img class="my-img-preview" src="" style="display:none;">
        <input type="text" name="_home_options[sliders][0][pic-url]" value="" class="pic-input" placeholder="图片地址">
        <input type="text" name="_home_options[sliders][0][title]" value="" class="" placeholder="标题">
        <input type="text" name="_home_options[sliders][0][description]" value="" class="" placeholder="描述">
        <input type="text" name="_home_options[sliders][0][url]" value="" class="" placeholder="链接地址">
        <div class="action">
            <a href="javascript:;" class="upload-slider-button">上传</a>
            <a href="javascript:;" class="delete-slider-button">删除</a>
        </div>
    </div>
</div>



