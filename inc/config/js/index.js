/**
 * 关闭 notice
 */
(function(){
  window.addEventListener('load', function(){
    document.querySelector(".notice-dismiss")?.addEventListener("click", function(){
      this.parentNode.remove();
    });
  });
})();



/**
 * 发送测试邮件
 */
window.addEventListener('load', function(){
  if(!document.querySelector("#sendemail-test-button")){
    return;
  }

  var EMAIL_RE = /^[a-z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]+(?:\.[a-z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]+)*@(?:[a-z0-9](?:[a-z0-9\-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9\-]*[a-z0-9])?$/i;

  document.querySelector("#sendemail-test-button").addEventListener("click", function(){
    console.log("发送测试邮件");
    var testEmail = document.querySelector("input[name='test_email']").value;
    var res = EMAIL_RE.exec(testEmail);
    if(!res){
      alert("请填写正确的邮箱地址！");
      document.querySelector("input[name='test_email']").focus();
      return false;
    }
    // console.log("res: ", res);
    this.setAttribute('disabled', 'disabled');
    var that = this;

    var payload = JSON.stringify({
      email:testEmail
    });
    var headers = new Headers({
      'Content-Type':'application/json',

    });

    var _wpnonce = this.dataset.token;
    var url = this.dataset.url + '?_wpnonce=' + _wpnonce;
    fetch(url,{
      method:'POST',
      body:payload,
      headers:headers
    })
    .then(response => {
      if(response.status == 200){
        alert("邮件发送成功");
      }else{
        response.json()
          .then(function(data){
            alert( data.message +" " + (data.detail ? data.detail : "") );
          });
      }
      that.removeAttribute('disabled');
    })
  });
});




/**
 * 首页模块排序
 */
window.addEventListener('load', function(){
  var items = document.querySelectorAll('.widget-item-list .widget-item');

  items.forEach(function (item){
      item.querySelector('a[up]').addEventListener('click', upAction);
      item.querySelector('a[down]').addEventListener('click', downAction);
      item.querySelector('a[remove]').addEventListener('click', removeAction);
      item.querySelector('a[add]').addEventListener('click', addAction);
  });

  function upAction(button){
      console.log('a[up]');
      var item = this.parentNode.parentNode;
      console.log('item ', item.querySelector('label').innerText );

      var prevItem = item.previousElementSibling;
      console.log(prevItem);
      if(!prevItem) return;

      document.querySelector('.widget-wrapper.enabled .widget-item-list').insertBefore(item, prevItem);
  }

  function downAction(){
      console.log('a[down]');
      var item = this.parentNode.parentNode;
      console.log('item ', item.querySelector('label').innerText );

      var nextItem = item.nextElementSibling;
      if(!nextItem) return;

      if(item.parentNode.lastChild == nextItem){
          item.parentNode.appendChild(item);
          return;
      } else {
         document.querySelector('.widget-wrapper.enabled .widget-item-list').insertBefore(item, nextItem.nextSibling); 
      }
  }

  function removeAction(button){
      var item = this.parentNode.parentNode;
      var nameText = item.querySelector('input[type="hidden"]').getAttribute('name');
      nameText = nameText.replace('enabled', 'disabled');
      item.querySelector('input[type="hidden"]').setAttribute('name', nameText);
      document.querySelector('.widget-wrapper.disabled .widget-item-list').insertBefore(item, null);
  }

  function addAction(button){
      var item = this.parentNode.parentNode;
      var nameText = item.querySelector('input[type="hidden"]').getAttribute('name');
      nameText = nameText.replace('disabled', 'enabled');
      item.querySelector('input[type="hidden"]').setAttribute('name', nameText);
      document.querySelector('.widget-wrapper.enabled .widget-item-list').insertBefore(item, null);
  }

  /**
   * 设置选项卡
   */
  document.querySelectorAll('.widget-options-container .nav a').forEach(function(item){
      item.addEventListener('click', function(e){
          document.querySelectorAll('.widget-options-container .nav a').forEach(function(item){
              item.classList.remove('active');
          });
          this.classList.add('active');

          document.querySelectorAll('.widget-options-container .options-content').forEach(function(d){
              d.classList.remove('active');
              console.log('e ', e.target.dataset)
              if(d.classList.contains(e.target.dataset.id)){
                  d.classList.add('active');
              }
          });

          // 修改地址
          let url ="?page=rangtuo-options&action=home&tab=" + this.dataset.id;
          let title = '标题';
          history.pushState({id:0},title,url);
      });
  });

});


/**
 * 数字模块设置
 */
window.addEventListener('load', function(){
  if(!document.querySelector(".add-number-item") ){
    return;
  }
  
  document.querySelector('.add-number-item').addEventListener('click', function(e){
      var str = document.querySelector('#number-item-template').innerHTML;
      console.log('str ', typeof str);
      document.querySelector('.number-item-list').insertAdjacentHTML('beforeEnd', str);
      // 
      document.querySelectorAll('.number-item-list .number-item').forEach(function(item, index){
          console.log('item, index: ', item, index);
          item.querySelectorAll('input').forEach(function(input, i){
              var text = input.getAttribute('name').replace(/[{0-9}]/, index);
              input.setAttribute('name', text);
          });
      });
  });
  
  document.querySelectorAll('.number-item-list .delete-number-item-button').forEach(function(item, index){
      item.addEventListener('click', function(ele, i){
          console.log('ele ', ele, this);
          this.parentNode.parentNode.remove();
      });
  });
});



/**
 * 幻灯片上传功能
 */
window.addEventListener('load', function(){
  if(!document.querySelectorAll(".slider-list .upload-slider-button")) return;
  
  // 图片上传按钮
  document.querySelectorAll(".slider-list .upload-slider-button").forEach(function (btn) {
    btn.removeEventListener("click", uploadHandler);
    var _orig_send_attachment = wp.media.editor.send.attachment;
    btn.addEventListener("click", uploadHandler);
  });
  
  // 删除按钮
  document.querySelectorAll(".slider-list .delete-slider-button").forEach(function (btn) {
    btn.removeEventListener("click", deleteHandler);
    btn.addEventListener("click", deleteHandler);
  });
  
  /**
   * 上传图片
   */
  document.querySelectorAll(".slider-list .upload-slider-button").forEach(function (btn) {
    var _orig_send_attachment = wp.media.editor.send.attachment;
    btn.addEventListener("click", function(){
      var that = this;
      wp.media.editor.send.attachment = function(props, attachment) {
          that.parentNode.parentNode.querySelector('.pic-input').value = attachment.sizes[props.size].url;
          that.parentNode.parentNode.querySelector('.my-img-preview').src = attachment.sizes[props.size].url;
          that.parentNode.parentNode.querySelector('.my-img-preview').style.display = 'block';
          wp.media.editor.send.attachment = _orig_send_attachment;
      }
      wp.media.editor.open();
      return false;
    });
  });
  
  /**
   * 增加幻灯片设置模块
   */
  var addSliderButton = document.querySelector('.add-slider-button');
  if(addSliderButton){
    addSliderButton.addEventListener('click', function(e){
      var str = document.querySelector('#slider-item-template').innerHTML;
      document.querySelector('.slider-list').insertAdjacentHTML('beforeEnd', str);
      // 重构表单name
      fixInputName();
    
      // 图片上传按钮
      document.querySelectorAll(".slider-list .upload-slider-button").forEach(function (btn) {
        btn.removeEventListener("click", uploadHandler);
        var _orig_send_attachment = wp.media.editor.send.attachment;
        btn.addEventListener("click", uploadHandler);
      });
      
      // 删除按钮
      document.querySelectorAll(".slider-list .delete-slider-button").forEach(function (btn) {
        btn.removeEventListener("click", deleteHandler);
        btn.addEventListener("click", deleteHandler);
      });
    });
  }
  
  
  /**
   * 图片上传按钮事件
   */
  function uploadHandler(){
    var that = this;
    wp.media.editor.send.attachment = function(props, attachment) {
        that.parentNode.parentNode.querySelector('.pic-input').value = attachment.sizes[props.size].url;
        that.parentNode.parentNode.querySelector('.my-img-preview').src = attachment.sizes[props.size].url;
        that.parentNode.parentNode.querySelector('.my-img-preview').style.display = 'block';
        wp.media.editor.send.attachment = _orig_send_attachment;
    }
    wp.media.editor.open();
    return false;
  }
  
  /**
   * 删除按钮事件
   */
  function deleteHandler(){
    this.parentNode.parentNode.remove();
    fixInputName();
    return false;
  }
  
  /**
   * 重构表单name
   */
  function fixInputName(){
    document.querySelectorAll('.slider-list .number-item').forEach(function(item, index){
        console.log('item, index: ', item, index);
        item.querySelectorAll('input').forEach(function(input, i){
            var text = input.getAttribute('name').replace(/[{0-9}]/, index);
            input.setAttribute('name', text);
        });
    });
  }
});




