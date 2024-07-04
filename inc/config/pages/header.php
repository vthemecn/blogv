<?php
defined('ABSPATH') || exit;
?>



<form action="" method="post" enctype="multipart/form-data" name="op_form" id="op_form">
    <table class="form-table" role="presentation">
        <tbody>

            <tr>
                <th scope="row"><label for="meta_description">网站logo</label></th>
                <td>
                    <div>
                        <?php if ($vt_config['site_logo']) { ?>
                            <img class="my-img-preview" src="<?php echo $vt_config['site_logo']; ?>" style="display:block;">
                        <?php } else { ?>
                            <img class="my-img-preview" src="" style="display:none;">
                        <?php } ?>
                        <input type="text" name="site_logo" id="site_logo" value="<?php echo ($vt_config['site_logo']); ?>" class="regular-text image-input" />
                        <button type="button" class="upload-button">上传</button>
                        <button type="button" class="delete-button">删除</button>
                        <p class="description" id="tagline-description">图片尺寸 180*50</p>
                    </div>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><label for="logo_is_flashing">logo 闪光</label></th>
                <td>
                    <p>
                        <label>
                            <input name="logo_is_flashing" type="radio" value="1" <?php if ($vt_config['logo_is_flashing'] == 1) { ?>checked="checked" <?php } ?>>
                            显示
                        </label>
                        <label>
                            <input name="logo_is_flashing" type="radio" value="0" <?php if ($vt_config['logo_is_flashing'] == 0) { ?>checked="checked" <?php } ?>>
                            不显示
                        </label>
                    </p>
                </td>
            </tr>

            <!--
            <tr>
                <th scope="row"><label for="header_type">Header 类型</label></th>
                <td>
                    <p>
                        <label>
                            <input name="header_type" type="radio" value="0" <?php if ($vt_config['header_type'] == 0) { ?>checked="checked" <?php } ?>>
                            默认
                        </label>
                        <label>
                            <input name="header_type" type="radio" value="1" <?php if ($vt_config['header_type'] == 1) { ?>checked="checked" <?php } ?>>
                            传统
                        </label>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="header_description">网站副标题</label></th>
                <td><input type="text" name="header_description" id="header_description" value="<?php echo htmlspecialchars($vt_config['header_description']); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="header_phone_title">联系电话标题</label></th>
                <td><input type="text" name="header_phone_title" id="header_phone_title" value="<?php echo ($vt_config['header_phone_title']); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="header_phone_number">电话号码</label></th>
                <td><input type="text" name="header_phone_number" id="header_phone_number" value="<?php echo ($vt_config['header_phone_number']); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="meta_description">header 广告位</label></th>
                <td>
                    <div>
                        <?php if ($vt_config['header_ad_pic']) { ?>
                            <img class="my-img-preview" src="<?php echo $vt_config['header_ad_pic']; ?>" style="display:block;">
                        <?php } else { ?>
                            <img class="my-img-preview" src="" style="display:none;">
                        <?php } ?>
                        <input type="text" name="header_ad_pic" id="header_ad_pic" 
                          value="<?php echo $vt_config['header_ad_pic']; ?>" class="regular-text image-input" />
                        <button type="button" class="upload-button">上传</button>
                        <button type="button" class="delete-button">删除</button>
                        <p class="description" id="tagline-description">图片尺寸 180*50</p>
                    </div>
                </td>
            </tr>
            -->

        </tbody>
    </table>
    <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('vt_options_update') ?>">
    <input type="hidden" name="_action" value="vt_options_update">
    <input type="submit" name="input_save" value="保存" class="button button-primary" />
</form>
