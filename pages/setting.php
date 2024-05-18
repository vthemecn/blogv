<?php
/**
 * `/inc/rewrite.php` 所引用的文件
 */

global $wp_query;
global $current_user;

$param_user_id = $wp_query->query_vars['user_id'];
$usersService = new \api\users\UsersService();
$param_user = $usersService->getUserById($param_user_id);


get_header();
?>



<div class="user-center-container">
    <?php require_once get_template_directory() . '/templates/users/banner.php'; ?>

    <?php require_once get_template_directory() . '/templates/users/sider.php'; ?>

    <div class="user-center-panel">
        <h3>我的设置</h3>

        <div class="form">
            <div class="field field-text">
                <label for="user_login">账号:</label>
                <input name="user_login" id="user_login" type="text" value="<?php echo $param_user['user_login']?>" disabled>
            </div>
            <div class="field field-text">
                <label for="nickname">昵称:</label>
                <input name="nickname" id="nickname" type="text" value="<?php echo $param_user['nickname']?>">
            </div>
            <div class="field field-text">
                <label for="email">邮箱:</label>
                <input name="email" id="email" type="text" value="<?php echo $param_user['user_email']?>" disabled>
            </div>
            <div class="field field-text">
                <label for="mobile">手机号:</label>
                <input name="mobile" id="mobile" type="text" value="<?php echo $param_user['mobile']?>" disabled>
            </div>
            <div class="field field-text">
                <label for="description">签名:</label>
                <textarea name="description" id="description" cols="20" rows="6"><?php echo $param_user['description']?></textarea>
            </div>
            <div class="field">
                <button class="btn btn-primary update-user">确定</button>
            </div>
        </div>
    </div>
    
</div>

<input type="hidden" name="api_url" value="/wp-json/rangtuo/v1/users/<?php echo $param_user_id ?>">
<input type="hidden" name="wp_create_nonce" value="<?php echo wp_create_nonce('wp_rest'); ?>">

<script type="module" src="<?php bloginfo('template_url'); ?>/assets/js/lib/axios/axios.esm.js"></script>
<script type="module" src="<?php bloginfo('template_url'); ?>/assets/js/src/toast.js"></script>

<script type="module">
import axios from '<?php bloginfo('template_url'); ?>/assets/js/lib/axios/axios.esm.js';
import toast from '<?php bloginfo('template_url'); ?>/assets/js/src/toast.js';

document.querySelector(".update-user").addEventListener("click", function(e) {
    e.preventDefault();
    
    var wpnonce = document.querySelector("input[name='wp_create_nonce']").value;
    var url = document.querySelector('input[name="api_url"]').value + "?_wpnonce=" + wpnonce;
    
    var requestData = {};
    requestData.nickname = document.querySelector("input[name='nickname']").value;
    requestData.description = document.querySelector("textarea[name='description']").value;

    axios({
        method: 'post',
        url: url,
        responseType: 'json',
        data: JSON.stringify(requestData),
        headers: {'Content-Type':'application/json'}
      })
      .then(function(response) {
          console.log(response);
        if (response.status == 200) {
            toast.open({title: "修改成功！"});
            document.querySelector('.user-info .nickname').innerText = response.data.nickname;
        } else {
            toast.open({title: "修改失败：" + response.data.error});
        }
      })
      .catch(function(error) {
            console.log(error);
            toast.open({title: "修改失败：" + error.response.data.error});
      });
    
});

</script>

<?php get_footer(); ?>
