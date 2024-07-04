<?php
defined('ABSPATH') || exit;
?>

<form action="" method="post" enctype="multipart/form-data" name="op_form" id="op_form">
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label for="is_wxapp_active">启用微信小程序设置</label></th>
                <td>
                    <p>
                        <label>
                            <input name="is_wxapp_active" type="radio" value="1" <?php if ($vt_config['is_wxapp_active'] == 1) { ?>checked="checked" <?php } ?>>
                            启用
                        </label>
                        <label>
                            <input name="is_wxapp_active" type="radio" value="0" <?php if ($vt_config['is_wxapp_active'] == 0) { ?>checked="checked" <?php } ?>>
                            禁用
                        </label>
                    </p>
                    <p class="description">使用以下设置发送邮件</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="vt_wx_appid">appid</label></th>
                <td><input type="text" name="vt_wx_appid" id="vt_wx_appid" value="<?php echo ($vt_config['vt_wx_appid']); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="vt_wx_secret">secret</label></th>
                <td><input type="text" name="vt_wx_secret" id="vt_wx_secret" value="<?php echo ($vt_config['vt_wx_secret']); ?>" class="regular-text"></td>
            </tr>

        </tbody>
    </table>
    <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('vt_options_update') ?>">
    <input type="hidden" name="_action" value="vt_options_update">
    <input type="submit" name="input_save" value="保存" class="button button-primary" />
</form>