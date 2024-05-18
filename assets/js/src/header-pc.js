import toast from "./toast";

export default function () {
  pcSearch();
  nav();
  avatarShow();
}

/* PC 端搜索 */
function pcSearch() {
  var searchToggleButton = document.querySelector('.header.pc .search-toggle-button');
  if(searchToggleButton){
    searchToggleButton.addEventListener('click', function(e){
      this.classList.toggle('active');
      if(this.classList.contains('active')){
        document.querySelector('.header.pc .search-widget').classList.add('show');
        var input = document.querySelector('.header.pc .search-widget .keyword');
        var text = input.value;
        input.value = '';
        input.focus();
        input.value = text;
      }else{
        document.querySelector('.header.pc .search-widget').classList.remove('show');
      }
    });
  }
  
  var closeSearchButton = document.querySelector('.header.pc .close-widget a');
  if(closeSearchButton ){
    closeSearchButton.addEventListener('click', ()=>{
      document.querySelector('.header.pc .search-widget').classList.remove('show');
      searchToggleButton.classList.remove('active');
    });
  }

  var searchButton = document.querySelector(".header.pc .search .button");
  if (searchButton) {
    searchButton.addEventListener("click", function (e) {
      e.preventDefault();
      if (!document.querySelector(".keyword").value) {
        toast.open({ title: "请输入关键词" });
        return false;
      } else {
        document.querySelector(".search").submit();
      }
    });
  }
}

/* 导航 */
function nav(){
  var items = document.querySelectorAll(".header.pc .menu-item-has-children");
  if(!items) return;
  
  items.forEach(function(i){
    i.addEventListener('mouseenter', function(e){
      if(i.querySelector('.child-menu')){
          i.classList.add('active');
      }
    });
    
    i.addEventListener('mouseleave', function(e){
      if(i.querySelector('.child-menu')){
          i.classList.remove('active');
      }
    });
  });
}

/* 头像点击 */
function avatarShow(){
  var avatarShowButton = document.querySelector('.header-top-avatar');
  if(!avatarShowButton){
    return false;
  }
  
  avatarShowButton.addEventListener('click', function(e){
    this.classList.toggle('active');
    if (this.classList.contains('active')) {
      document.querySelector('.header.pc .user-widget').classList.add('show');
    } else {
      document.querySelector('.header.pc .user-widget').classList.remove('show');
    }
  });
}
