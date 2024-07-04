<?php
/**
 * 移动端底部菜单
 */

$vt_options = vt_get_config();

$current_user_id = get_current_user_id();

?>


<div class="mobile-nav">
    <div class="mobile-nav-container">
        <?php
        $vt_mobile_nav_config = $vt_options['mobile_nav_config'];
        $vt_mobile_nav_config = explode("\n", $vt_mobile_nav_config);
        $vt_mobile_nav_config = array_filter($vt_mobile_nav_config);

        foreach ($vt_mobile_nav_config as $k => $v) :
            $nav_item = explode('|', $v);
        ?>
            <a class="nav-button" href="<?php echo $nav_item[2]; ?>">
                <i class="iconfont">&<?php echo $nav_item[0]; ?></i>
                <b><?php echo $nav_item[1]; ?></b>
            </a>
        <?php endforeach ?>
    </div>
</div>

<style>
body{ padding-bottom:45px; }

@media only screen and (min-width: 900px) {
    body{ padding-bottom:0; }
}
</style>
