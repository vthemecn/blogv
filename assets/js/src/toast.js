var counter = 0;

function open(userOption) {
  var option = { title: ' ', duration: 3 };
  option = Object.assign(option, userOption);

  var id = "id" + counter;
  var htmlString = `<div class="toast-box" id="${id}">${option.title}</div>`;
  document.body.insertAdjacentHTML('beforeend', htmlString);
  counter++;
  setTimeout(function () {
    document.body.removeChild(document.querySelector('#' + id));
  }, option.duration * 1000);
}

export default { open };
