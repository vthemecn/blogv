<?php
$rt_config = rt_get_config();
?>

<style>
@media screen and (min-width:900px) {
    .swiper.swiper-container {
        height: <?php echo $rt_config['_home_options']['sliders_height'] ?>;
    }
}
</style>

<div class="swiper swiper-container">
    <div class="swiper-wrapper">
        <?php if ($rt_config['_home_options']['sliders']) : ?>
            <?php foreach ($rt_config['_home_options']['sliders'] as $k => $v) : ?>
                <a class="swiper-slide" href="<?php echo $v['url'] ?>">
                    <img src="<?php echo $v['pic-url'] ?>" />
                </a>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>
