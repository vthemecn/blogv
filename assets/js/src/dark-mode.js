/*
 * 夜间模式按钮
 */

import { getCookie, setCookie } from './utils';

export function darkModeInit() {
  if (document.querySelector(".dark-mode-button")) {
    document.querySelectorAll(".dark-mode-button").forEach(function (btn) {
      btn.addEventListener("click", darkMode);
    });
  }
}


function darkMode() {
  console.log("cookie: ", getCookie("darkMode"));
  var isDarkMode = document.body.classList.contains('dark-mode');
  if (isDarkMode) {
    setCookie("darkMode", 0);
  } else {
    setCookie("darkMode", 1);
  }
  document.body.classList.toggle("dark-mode");
  document.querySelectorAll(".dark-mode-button").forEach(function (btn) {
    btn.classList.toggle("dark");
  });
}
