import axios from './axios.js';
import toast from './toast.js';

export default function init(){
  buyButtonInit();
  deleteOrderInit();
  payOrderInit();
  updateDomainInit();
}

/**
 * 商品添加到订单按钮事件绑定
 */
function buyButtonInit(){
  var buyButton = document.querySelector('.button.buy-button');
  buyButton && buyButton.addEventListener('click', function (e) {
    
    var wpnonce = document.querySelector("input[name='wp_create_nonce']").value;
    var createOrderUrl = '/wp-json/rangtuo/v1/orders' + "?_wpnonce=" + wpnonce;
    var payload = [{ item_id: this.dataset.itemId, quantity: 1 }];

    axios({
      method: 'post',
      url: createOrderUrl,
      data: JSON.stringify(payload)
    })
      .then(response => {
        console.log('xxxx: ', response);
        if(response.status == 201){
          location.href = '/orders/' + response.data.order_trade_no;
        } else {
          toast.open({ title:'操作失败：' . response.status });
        }
      });
  });
}

/**
 * 订单列表中，删除订单按钮事件绑定
 */
function deleteOrderInit(){
  var deleteOrderButtons = document.querySelectorAll('.delete-order-button');
  if(!deleteOrderButtons) { return; }
  for (let btn of deleteOrderButtons) {
    btn.addEventListener('click', function (e) {
      console.log('xxxx');
      var wpnonce = document.querySelector("input[name='wp_create_nonce']").value;
      let url = '/wp-json/rangtuo/v1/orders/' + this.dataset.orderTradeNo + "?_wpnonce=" + wpnonce;
      var payload = [{ item_id: this.dataset.itemId, quantity: 1 }];

      axios({
        method: 'delete',
        url: url,
        data: JSON.stringify(payload)
      })
        .then(response => {
          if(response.status == 204){
            toast.open({'title':'删除成功'});
            setTimeout(()=>{ location.href = location.href; },1000);
          } else {
            toast.open({ title:'操作失败：' . response.status });
          }
        });
    });
  }
}


/**
 * 订单详情页，余额支付按钮事件绑定
 */
function payOrderInit(){
  var payButton = document.querySelector('.btn.pay');
  if(!payButton) return;
  payButton.addEventListener('click', function(e){
    let wpnonce = document.querySelector('input[name="wp_create_nonce"]').value;
    let url = '/wp-json/rangtuo/v1/orders/' + this.dataset.orderId + "?_wpnonce=" + wpnonce + '&pay=true';
    let payload = {};
    payload['comment'] = document.querySelector('#order-comment').value;
    
    axios({
      method: 'PUT',
      url: url,
      data: JSON.stringify(payload)
    })
      .then(response => {
        if(response.status == 200){
          toast.open({title:'支付成功'});
          setTimeout(()=> location.href = location.href, 1000);
        } else {
          toast.open({ title:'操作失败：' . response.status });
        }
      });
  });
}


function updateDomainInit(){
  var button = document.querySelector('.update-domain-button');
  button && button.addEventListener('click', function(e){
    let wpnonce = document.querySelector('input[name="wp_create_nonce"]').value;
    let url = '/wp-json/auth/v1/key' + "?_wpnonce=" + wpnonce;
    let payload = {};
    payload.order_trade_no = this.dataset.orderId;
    payload.domain = document.querySelector('#order-domain').value;
    
    axios({
      method: 'POST',
      url: url,
      data: JSON.stringify(payload)
    })
      .then(response => {
        console.log('response: ', response);
        if(response.status == 200){
          toast.open({title:'修改成功'});
          setTimeout(()=>{ location.href = location.href; },1000);
        } else {
          toast.open({ title:'操作失败：' . response.status });
        }
      });
  });
}

