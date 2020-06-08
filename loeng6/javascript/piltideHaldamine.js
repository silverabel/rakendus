function kustutaPilt(e) {
  var photoId = e.dataset.id;

  //AJAX
  let webRequest = new XMLHttpRequest();
  webRequest.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      //mis teha javascriptis, kui ajax on edukas
      e.disabled = true;
    }
  };
  webRequest.open("GET", "kustutaPilt.php?photoId=" + photoId, true);
  webRequest.send();

//AJAX lõpeb
}

var pildiElemendid = document.getElementsByTagName("img");

for (var i = 0; i < pildiElemendid.length; i++) {
  pildiElemendid[i].addEventListener("click", function() {
    var checkBoxElement = this.parentElement.firstElementChild;
    checkBoxElement.checked = !checkBoxElement.checked;
  });
}