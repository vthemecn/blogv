/**
 * 图片上传控件
 */

/*
<div>
    <?php if($options['footer_logo']){?>
        <img class="my-img-preview" src="<?php echo $options['footer_logo'];?>" style="display:block;">
    <?php } else { ?>
        <img class="my-img-preview" src="" style="width:180px;height:50px;display:none;">
    <?php } ?>
    <input type="text" name="footer_logo" id="footer_logo" value="<?php echo($options['footer_logo']); ?>" class="regular-text image-input"/>
    <button type="button" class="upload-button">上传</button>
    <button type="button" class="delete-button">删除</button>
    <p class="description" id="tagline-description">图片尺寸 180*50</p>
</div>
*/



window.addEventListener('load', function () {
  window.send_to_editor = function (html) {
    var pattern = /(?:src=|data-original=)[\'\"]?([^\'\"]*)[\'\"]?/;
    var imgurl = pattern.exec(html)[1];
    window.targetfield.value = imgurl;
    window.imgPreview.src = imgurl;
    window.imgPreview.style.display = "block";
    tb_remove();
  }
});


// 上传图片按钮
document.querySelectorAll(".upload-button").forEach(function (btn) {
  btn.addEventListener("click", uploadButtonHandler);
});
// 删除图片按钮
document.querySelectorAll(".delete-button").forEach(function (btn) {
  btn.addEventListener("click", deleteButtonHandler);
});


/*
 * 上传按钮绑定的事件
 * 调用 WordPress 的图片上传弹窗
 */
function uploadButtonHandler() {
  window.targetfield = this.parentNode.querySelector(".image-input");
  window.imgPreview = this.parentNode.querySelector(".my-img-preview");
  console.log("imgPreview: ", window.targetfield, this.parentNode.querySelector(".image-input"));
  tb_show('', 'media-upload.php?type=image&mode=grid&amp;TB_iframe=true&amp;width=800&amp;height=500&amp;mode=grid');
  return false;
}

// 删除按钮事件【可在多个地方使用】
function deleteButtonHandler() {
  if (this.dataset.deleteAll) { // 删除整个上传控件
    this.parentNode.remove();
    return;
  }
  window.targetfield = this.parentNode.querySelector(".image-input");
  window.imgPreview = this.parentNode.querySelector(".my-img-preview");
  this.parentNode.querySelector(".my-img-preview").style.display = "none";
  this.parentNode.querySelector(".image-input").value = "";
}




/**
 * 图片列表上传，
 *
(function () {
  var thumbnailGroup = '\
    <div class="thumbnail-group">\
        <img class="my-img-preview" src="/wp-content/themes/light/assets/images/default.jpg" style="display:none;">\
        <input type="text" name="slider_images[]" value="" class="regular-text image-input" placeholder="图片地址"/><br/> \
        <input type="text" name="slider_urls[]" value="" class="regular-text margin-top-10" placeholder="跳转地址"/><br/>\
        <button type="button" name="upload_button" class="upload-button">上传</button>\
        <button type="button" class="delete-button btn btn-sm btn-danger">删除</button>\
    </div>\
  ';

  if (document.querySelector(".add-thumbnail-group-button") == null) { return; }

  document.querySelector(".add-thumbnail-group-button").addEventListener("click", function () {
    console.log("添加图片上传列表", this);
    this.parentNode.querySelector('.thumbnail-group-list').insertAdjacentHTML("beforeend", thumbnailGroup);
    // 重新所有上传按钮事件
    document.querySelectorAll(".upload-button").forEach(function (btn) {
      window.targetfield = btn.parentNode.querySelector(".image-input");
      window.imgPreview = btn.parentNode.querySelector(".my-img-preview");
      btn.removeEventListener("click", uploadButtonHandler);
      btn.addEventListener("click", uploadButtonHandler);

    });

    // 重新所有删除按钮事件
    document.querySelectorAll(".delete-button").forEach(function (btn) {
      window.targetfield = btn.parentNode.querySelector(".image-input");
      window.imgPreview = btn.parentNode.querySelector(".my-img-preview");
      btn.removeEventListener("click", deleteButtonHandler);
      btn.addEventListener("click", deleteButtonHandler);
    });

    // document.querySelectorAll(".img-preview").forEach(function (item) {
    //   item.removeEventListener("click", imagePreviewHandler);
    //   item.addEventListener("click", imagePreviewHandler);
    // });
  });
})();
*/


