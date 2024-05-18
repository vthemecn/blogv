/**
 * 图片懒加载
 */

export function lazyLoad() {
  window.addEventListener('load', function () {
    var imgs = document.querySelectorAll('.lazyload-img');

    loadImages(imgs);
    window.addEventListener('scroll', function () {
      loadImages(imgs);
    });

  });
}



function loadImages(images_arr) {
  if (!images_arr) { return; }
  images_arr.forEach(function (el) {
    var bound = el.getBoundingClientRect();
    if (bound.top <= window.innerHeight) {
      el.src = el.dataset.src;
    }
  })
}

