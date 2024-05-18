export default function(){
  userLink();
}

function userLink() {
  var users = document.querySelectorAll('.nickname a');
  if(!users) return;
  console.log('users', users)
  for (var i = 0; i < users.length; i++) {
    users[i].href = users[i].dataset.url;
  }
}
