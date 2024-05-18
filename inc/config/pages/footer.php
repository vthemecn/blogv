<?php
/**
 * 底部设置
 *
 * @author: soushenji <soushenji@qq.com>
 * @link: https://github.com/soushenji
 */
defined('ABSPATH') || exit;

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



<form action="" method="post" enctype="multipart/form-data" name="op_form" id="op_form">
    <table class="form-table" role="presentation">
        <tbody>

            <tr class="td-top-line">
                <th scope="row">
                    <div class='title-light'>Footer 设置</div>
                </th>
                <td></td>
            </tr>

            <tr>
                <th scope="row"><label for="footer_description">底部标题</label></th>
                <td>
                    <textarea name="footer_description" id="footer_description" class="regular-text"><?php echo ($rt_config['footer_description']); ?></textarea>
                    <p class="description" id="tagline-description">用简洁的文字描述本站点。</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="footer_copyright">底部版权</label></th>
                <td>
                    <textarea name="footer_copyright" id="footer_copyright" class="regular-text" rows="5"><?php echo ($rt_config['footer_copyright']); ?></textarea>
                    <p class="description" id="tagline-description">底部版权的文字信息，支持 HTML</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="footer_logo">底部 logo</label></th>
                <td>
                    <div>
                        <?php if ($rt_config['footer_logo']) { ?>
                            <img class="my-img-preview" src="<?php echo $rt_config['footer_logo']; ?>" style="display:block;">
                        <?php } else { ?>
                            <img class="my-img-preview" src="" style="display:none;">
                        <?php } ?>
                        <input type="text" name="footer_logo" id="footer_logo" value="<?php echo ($rt_config['footer_logo']); ?>" class="regular-text image-input" />
                        <button type="button" class="upload-button">上传</button>
                        <button type="button" class="delete-button">删除</button>
                        <p class="description" id="tagline-description">图片尺寸 180*50</p>
                    </div>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="footer_bg_type">footer 颜色</label></th>
                <td>
                    <p>
                        <label>
                            <input name="footer_bg_type" type="radio" value="0" <?php if ($rt_config['footer_bg_type'] == 0) { ?>checked="checked" <?php } ?>>
                            暗色
                        </label>
                        <label>
                            <input name="footer_bg_type" type="radio" value="1" <?php if ($rt_config['footer_bg_type'] == 1) { ?>checked="checked" <?php } ?>>
                            亮色
                        </label>
                        <!-- 跟随系统 -->
                    </p>
                    <p class="description">背景色的类型</p>
                </td>
            </tr>


            <tr class="td-top-line">
                <th scope="row">
                    <div class='title-light'>移动端底部导航设置</div>
                </th>
                <td></td>
            </tr>
            <tr>
                <th scope="row"><label for="is_mobile_nav_show">显示移动端导航</label></th>
                <td>
                    <p>
                        <label>
                            <input name="is_mobile_nav_show" type="radio" value="1" <?php if ($rt_config['is_mobile_nav_show'] == 1) { ?>checked="checked" <?php } ?>>
                            显示
                        </label>
                        <br>
                        <label>
                            <input name="is_mobile_nav_show" type="radio" value="0" <?php if ($rt_config['is_mobile_nav_show'] == 0) { ?>checked="checked" <?php } ?>>
                            不显示
                        </label>
                    </p>
                    <p class="description">默认显示移动端导航</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mobile_nav_config">导航设置</label></th>
                <td>
                    <textarea name="mobile_nav_config" id="mobile_nav_config" class="regular-text" rows="5"><?php echo ($rt_config['mobile_nav_config']); ?></textarea>
                    <p class="description" id="tagline-description"></p>
                </td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('rt_options_update') ?>">
    <input type="hidden" name="_action" value="rt_options_update">
    <input type="submit" name="input_save" value="保存" class="button button-primary" />
</form>
