/**
 * 点赞，收藏，评论
 */

import toast from "./toast";
import axios from './axios';

export default function () {
  likeInit();
  starInit();
}

/**
 * 点赞事件绑定
 */
async function likeInit(){
  var likeButtons = document.querySelectorAll('.widget-action.like');
  if(!likeButtons) return;
  likeButtons.forEach( button=>{
    button.addEventListener('click', async function(){
      var that = this;
      
      var wpnonce = document.querySelector("input[name='wp_create_nonce']").value;
      var post_id = document.querySelector("input[name='post_id']").value;
      
      var addUrl = '/wp-json/rangtuo/v1/stars' + "?_wpnonce=" + wpnonce;
      var deleteUrl = '/wp-json/rangtuo/v1/stars/' + post_id + "?_wpnonce=" + wpnonce;
      
      if( this.classList.contains('active') ){
        await axios.request({ method: 'DELETE', url: deleteUrl, data: {'type':'like'} })
        .then(function (response) {
          if(response.status == 204){
            that.classList.remove('active');
            that.querySelector('.number').innerText = --that.querySelector('.number').innerText;
          }
        });
      } else {
        var data = {};
        data.object_id = document.querySelector("input[name='post_id']").value;
        data.type = 'like';
        
        await axios.request({
          method: 'post',
          url: addUrl,
          data: JSON.stringify(data)
        })
        .then(function (response) {
          console.log(response);
          if(response.status == 201){
            that.classList.add('active');
            that.querySelector('.number').innerText = response.data.counter;
          }
        });
      }
      
    });
  });
}

/**
 * 收藏事件绑定
 */
async function starInit(){
  var starButtons = document.querySelectorAll('.widget-action.star');
  if(!starButtons) return;
  starButtons.forEach( button=>{
    button.addEventListener('click', async function(){
      var that = this;
      
      var wpnonce = document.querySelector("input[name='wp_create_nonce']").value;
      var post_id = document.querySelector("input[name='post_id']").value;
      
      var addUrl = '/wp-json/rangtuo/v1/stars' + "?_wpnonce=" + wpnonce;
      var deleteUrl = '/wp-json/rangtuo/v1/stars/' + post_id + "?_wpnonce=" + wpnonce;
      
      if( this.classList.contains('active') ){
        await axios.request({ method: 'DELETE', url: deleteUrl, data: {'type':'star'} })
        .then(function (response) {
          if(response.status == 204){
            that.classList.remove('active');
            that.querySelector('.number').innerText = --that.querySelector('.number').innerText;
          }
        });
      } else {
        var data = {};
        data.object_id = document.querySelector("input[name='post_id']").value;
        data.type = 'star';
        
        await axios.request({
          method: 'post',
          url: addUrl,
          data: JSON.stringify(data)
        })
        .then(function (response) {
          console.log(response);
          if(response.status == 201){
            that.classList.add('active');
            that.querySelector('.number').innerText = response.data.counter;
          }
        });
      }
      
    });
  });
}
