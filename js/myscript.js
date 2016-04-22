var input = $( "input:file" ).css({
  //background: "yellow",
  border: "1px black solid"
});

$( "form" ).submit(function( event ) {
  event.preventDefault();
});

local = {}
local.cropper = {
    init: false,
    cropperobj: null,
    orignalUrl: "",
    load: function() {
          this.init=true;
          this.cropperobj = $('#image').cropper({
          //aspectRatio: 16/9,
              crop: function(e) {
              // Output the result data for cropping image.
                  console.log(e.x);
                  console.log(e.y);
                  console.log(e.width);
                  console.log(e.height);
                  console.log(e.rotate);
                  console.log(e.scaleX);
                  console.log(e.scaleY);
              }
        });

    },
    reset: function() {
       if(this.init === true)
        $('#image').cropper('destroy');
    },
    submitImage: function() {

        var formData = new FormData();
        var v = $('#image').cropper('getData');
        formData.append('imageData-x', v.x);
        formData.append('imageData-y', v.y);
        formData.append('imageData-height', v.height);
        formData.append('imageData-width', v.width);
        formData.append('image', this.originalUrl)
        formData.append('file-name', this.originalUrl.name)

        $.ajax('image-insert.php', {
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
          console.log('Upload success');
          alert("File uploaded successfully, please check uploads folder under the web appln.")
        },
        error: function () {
          console.log('Upload error');
          alert("There was an error with the file upload, please check server logs.")
        }
        });

    }
}


var loadImage = function(event) {

      file = event.target.files[0];
      local.cropper.originalUrl = file;

      canvasResize(file, {
                width: 1000,
                height: 0,
                crop: false,
                quality: 100,
                //rotate: 90,
                callback: function(data, width, height) {

                      $('#image').attr('src', data);
                      local.cropper.reset();

                      local.cropper.load();
                      var fname = local.cropper.originalUrl.name;
                      local.cropper.originalUrl = canvasResize('dataURLtoBlob', data);
                      local.cropper.originalUrl.name = fname;
                }
            });
};

var submitToServer = function (){
  console.log("inside SubmitToServer");
  local.cropper.submitImage();
};
