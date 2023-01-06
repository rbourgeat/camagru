
var videoRunning = false;
var filterElement = document.getElementById("positionnedFilter");
var videoElement = document.getElementById("videoFeed");
var picElement = document.getElementById("outputImage");
var insertImageButton = document.getElementById("insertImageButton");
videoElement.style.display = "none";
insertImageButton.style.display = "block";
picElement.style.display = "block";

window.addEventListener("load", function(){

  document.getElementById("picUp").onclick = picUp;

  navigator.mediaDevices.getUserMedia({
    video: true
  })

  .then(function(stream) {
    var video = document.getElementById("videoFeed");
    video.srcObject = stream;
    video.play();
    videoRunning = true;
    videoElement.style.display = "block";
    picElement.style.display = "none";
    insertImageButton.style.display = "none";
  })

  .catch(function(err) {
    alert("Please enable access and/or attach a webcam");
  });
});

function preview_image(event) {
    var reader = new FileReader();
    reader.onload = function()
    {
      var output = document.getElementById('outputImage');
      output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}

function picUp () {
    if (filterElement.src && (videoElement.style.display == "block" || (picElement.style.display == "block" && picElement.src)))
    {
      var canvas = document.createElement("canvas"),
        elementWidth = videoRunning ? document.getElementById("videoFeed").clientWidth : document.getElementById("outputImage").clientWidth,
        elementHeight = videoRunning ? document.getElementById("videoFeed").clientHeight : document.getElementById("outputImage").clientHeight,
        image = videoRunning ? document.getElementById("videoFeed") : document.getElementById("outputImage"),
        context2D = canvas.getContext("2d"),
        filterWidth = document.getElementById("positionnedFilter").clientWidth,
        filterHeight = document.getElementById("positionnedFilter").clientHeight;

      canvas.width = elementWidth;
      canvas.height = elementHeight;
      context2D.drawImage(image, 0, 0, elementWidth, elementHeight);
      context2D.drawImage(filterElement, (elementWidth / 2) - (filterWidth / 2), (elementHeight / 2) - (filterHeight / 2), filterWidth, filterHeight);

      canvas.toBlob(function(blob){
        var fd = new FormData();
        fd.append('upimage', blob);

        fetch('/upload.php', {method:"POST", body:fd})
        .then(response => {
          if (response.ok) return response;
          else throw Error(`Return ${response.status}: ${response.statusText}`)
        })
        .then(response => console.log(response.text()))
        .catch(err => {
          alert(err);
        });

      });
      setTimeout(function (){
        location.reload();      
      }, 1000);
    }
}

