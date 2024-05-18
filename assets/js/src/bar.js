
export default function init(){
  toTopInit();
}

/*
 * 回到顶部
 */
export function toTopInit() {
  var btn = document.querySelector(".to-top");
  if (!btn) { return; }

  var clientHeight = document.documentElement.clientHeight;
  var timer = null;
  var istop = true;

  window.onscroll = function () {
    var dtop = document.documentElement.scrollTop || document.body.scrollTop;
    if (dtop >= (clientHeight * 0.1)) {
      btn.style.display = "flex";
    } else {
      btn.style.display = "none";
    }
    if (!istop) {
      clearInterval(timer);
    }
    istop = false;
  }

  btn.onclick = function () {
    timer = setInterval(function () {
      var dtop = document.documentElement.scrollTop || document.body.scrollTop;
      var speed = Math.floor(-dtop / 10);
      document.documentElement.scrollTop = dtop + speed;
      document.body.scrollTop = dtop + speed;
      istop = true;
      if (dtop == 0) {
        clearInterval(timer);
      }
    }, 15)
  }
}