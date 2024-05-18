/**
 * 公共函数
 */

export function getCookie(c_name) {
  if (document.cookie.length > 0) {
    var c_start = document.cookie.indexOf(c_name + "=");
    if (c_start != -1) {
      c_start = c_start + c_name.length + 1;
      var c_end = document.cookie.indexOf(";", c_start);
      if (c_end == -1) c_end = document.cookie.length;
      return unescape(document.cookie.substring(c_start, c_end));
    }
  }
  return ""
}


export function setCookie(c_name, value, expiredays) {
  var exdate = new Date();
  exdate.setDate(exdate.getDate() + expiredays);
  var cookieStr = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
  cookieStr += "; path=/";
  document.cookie = cookieStr;
}


/**
 * 判断是否出现在视口
 * @param {{}}} el 需要判断的 div 选择器
 * @returns {Boolean}
 */
export function isElementVisible(el) {
  const rect = el.getBoundingClientRect()
  const vWidth = window.innerWidth || document.documentElement.clientWidth
  const vHeight = window.innerHeight || document.documentElement.clientHeight
  if (
    rect.right < 0 ||
    rect.bottom < 0 ||
    rect.left > vWidth ||
    rect.top > vHeight
  ) {
    return false
  }
  return true
}


/**
 * 判断是否是手机号
 * @param {string} mobile 手机号
 * @returns {Boolean}
 */
export function isMobile(mobile){
  return /^1[3-9]\d{9}$/.test(mobile);
}


/**
 * 判断是否是邮箱
 * @param {string} email 邮箱
 * @returns {Boolean}
 */
export function isEmail(email){
  var EMAIL_RE = /^[a-z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]+(?:\.[a-z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]+)*@(?:[a-z0-9](?:[a-z0-9\-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9\-]*[a-z0-9])?$/i;
  return EMAIL_RE.test(email);
}
