import toast from "./toast";
import axios from "./axios";
import { getCookie, setCookie, isMobile, isEmail } from './utils';


function init() {
  loginInit();
  registerInit();
  sendCodeInit();
  showPasswordInit();
  resetPasswordInit();
  bottomLinkInit();
}


/**
 * 登录操作
 */
function loginInit() {
  var loginModal = document.querySelector('.modal.login-modal');

  // 登录对话框显示按钮
  var loginDialogButton = document.querySelector('.button.login-button');
  if(loginDialogButton){
    loginDialogButton.addEventListener('click', function(e){
      loginModal.classList.add('modal-show');
      document.querySelector('body').classList.add('no-scroll');
    });
  }

  // 登录对话框关闭按钮
  var closeDialogButton = document.querySelector('.login-modal .close-button');
  if(closeDialogButton){
    closeDialogButton.addEventListener('click', function(e){
      loginModal.classList.remove('modal-show');
      document.querySelector('body').classList.remove('no-scroll');
    });
  }

  // 登录按钮
  var loginButton = document.querySelector('.field-button .login-button');
  if (loginButton) {
    loginButton.addEventListener('click', async function () {
      var payload = JSON.stringify({
        account: document.querySelector('input[name="account"]').value,
        password: document.querySelector('input[name="password"]').value
      });
      axios({
        method: 'post',
        url: '/wp-json/rangtuo/v1/auth/login',
        data: payload
      })
        .then(function (response) {
          let errorMessage = response.data.error ? response.data.error : '登录失败';
          if (response.status == 200 && response.data.token) {
            location.href = location.href;
          } else {
            toast.open({ 'title': errorMessage });
          }
        });
    });
  }
}


/**
 * 注册操作
 */
function registerInit() {
  var registerModal = document.querySelector('.modal.register-modal');

  // 登录对话框显示按钮
  var registerDialogButton = document.querySelector('.button.register-button');
  if(registerDialogButton){
    registerDialogButton.addEventListener('click', function(e){
      registerModal.classList.add('modal-show');
      document.querySelector('body').classList.add('no-scroll');
    });
  }

  // 登录对话框关闭按钮
  var closeDialogButton = document.querySelector('.register-modal .close-button');
  if(closeDialogButton){
    closeDialogButton.addEventListener('click', function(e){
      registerModal.classList.remove('modal-show');
      document.querySelector('body').classList.remove('no-scroll');
    });
  }

  var registerButton = document.querySelector('.register-modal .register-button');
  if (registerButton) {
    registerButton.addEventListener('click', function (e) {
      var payload = {
        account: document.querySelector('.register-modal input[name="account"]').value,
        password: document.querySelector('.register-modal input[name="password"]').value,
        code: document.querySelector('.register-modal input[name="code"]').value
      };
      if (false == (payload.account && payload.password && payload.code)) {
        toast.open({ title: '请填写所有字段' });
        return;
      }

      axios({
        method: 'post',
        url: '/wp-json/rangtuo/v1/auth/register',
        data: payload
      })
        .then(function (response) {
          console.log(response);
          if (response.status == 200 || response.status == 201) {
            location.href = location.href;
          }
        });
    });
  }
}


/**
 * 显示密码
 */
function showPasswordInit() {
  var showPasswordButtonArr = document.querySelectorAll('.show-password');
  showPasswordButtonArr.forEach((item, index) => {
    if (!item) { return; }
    item.addEventListener('click', function () {
      if (this.classList.contains('show') == false) {
        this.classList.add('show');
        this.parentNode.querySelector('input').setAttribute('type', 'text');
      } else {
        this.classList.remove('show');
        this.parentNode.querySelector('input').setAttribute('type', 'password');
      }
    });
  });
}


/**
 * 绑定所有的验证码发送按钮
 */
function sendCodeInit() {
  var sendCodeButtons = document.querySelectorAll('.send-code');
  sendCodeButtons.forEach((item, index) => {
    item.addEventListener('click', sendCodeAction);
  });
}

async function sendCodeAction() {
  var account = document.querySelector('.register-modal input[name="account"]').value;
  if(!isEmail(account) && !isMobile(account)){
    toast.open({title:'账户格式不正确'});
    return;
  }
  
  var that = this;
  var time = 60;
  var flag = this.dataset.flag;
  this.querySelector('span').innerHTML = time;
  this.querySelector('span').style.display = 'inline-block';
  this.setAttribute('disabled', 'disabled');

  var payload = JSON.stringify({
    account: this.parentNode.parentNode.parentNode.querySelector('input[name="account"]').value,
    length: 6,
    flag
  });
  axios({
    method: 'post',
    url: '/wp-json/rangtuo/v1/auth/send-code',
    data: payload
  })
    .then(function (response) {
      console.log(response);
      if (response.status == 200) {
        toast.open({ title: "验证码发送成功" });
      } else {
        toast.open({ title: response.data.message ? response.data.message : response.status });
      }
    });

  var t = setInterval(function () {
    if (time == 0) {
      clearInterval(t);
      that.querySelector('span').style.display = 'none';
      that.removeAttribute('disabled');
      time = 60;
      return;
    }
    time--;
    that.querySelector('span').innerHTML = time;
  }, 1000);
}


/**
 * 重置密码
 */
function resetPasswordInit() {
  var resetPasswordButton = document.querySelector('.reset-password-button');
  if (!resetPasswordButton) { return; }
  resetPasswordButton.addEventListener('click', function (e) {
    console.log('重置密码');
    var payload = {
      account: document.querySelector('.reset-password-modal input[name="account"]').value,
      password: document.querySelector('.reset-password-modal input[name="password"]').value,
      code: document.querySelector('.reset-password-modal input[name="code"]').value
    };
    if (false == (payload.account && payload.password && payload.code)) {
      toast.open({ title: '请填写所有字段' });
      return;
    }

    axios({
      method: 'post',
      url: '/wp-json/rangtuo/v1/auth/reset-password',
      data: payload
    })
      .then(function (response) {
        console.log(response);
        if (response.status == 200 || response.status == 201) {
          location.href = location.href;
        }
      });
  });
}


/**
 * 绑定登录、注册和重置密码对话框下面链接的事件
 */
function bottomLinkInit() {
  var linkArr = document.querySelectorAll('.bottom-link');
  linkArr.forEach((item, index) => {
    item.addEventListener('click', function (e) {
      let action = item.dataset.action;
      console.log('action: ', action);
      document.querySelectorAll('.modal').forEach((item, index) => {
        item.classList.remove('modal-show');
      });
      document.querySelector('.' + action + '-modal').classList.add('modal-show');
    });
  });
}


/**
 * 注销操作
 */
function logoutInit() {
  var logoutButton = document.querySelector('.logout-button');
  if (logoutButton == null) { return; }
  logoutButton.addEventListener('click', async function () {
    axios({
      method: 'post',
      url: '/wp-json/rangtuo/v1/auth/logout'
    })
      .then(function (response) {
        console.log(response);
        if (response.status == 204) {
          location.href = location.href;
        } else {
          toast.open({ 'title': '注销失败' });
        }
      });
  });
}



export default {
  init
};