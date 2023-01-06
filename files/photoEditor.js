
var videoElement = document.getElementById("videoFeed");
var picElement = document.getElementById("outputImage");
var filterElement1 = document.getElementById("Filter0");
var filterElement1 = document.getElementById("Filter1");
var filterElement2 = document.getElementById("Filter2");
var filterElement3 = document.getElementById("Filter3");
var filterElement4 = document.getElementById("Filter4");
var filterElement5 = document.getElementById("Filter5");
var filterElement6 = document.getElementById("Filter6");
var filterElement = document.getElementById("positionnedFilter");

function applyFilter(number) {
    id = "filter" + number;
    filterPath = document.getElementById(id).src;
    filterElement.src = filterPath;
}

// AJAX BONUS !!!

function deleteImage(id) {
    fetch('/deleteImage.php?id=' + id + '', {method:"GET"})
    .then(response => {
      if (response.ok) return response;
      else throw Error(`Server returned ${response.status}: ${response.statusText}`)
    })
    .then(response => console.log(response.text()))
    .catch(err => {
      alert(err);
    });
    
    setTimeout(function (){
        location.reload();      
    }, 1000);
}
