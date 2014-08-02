$(document).ready(function() {
  var albumtitle = "";	
  var isimagefile = "";
  $("#not_image_file").text("");
  

  $('#title').change(function(e) {
		var title = $(this).val();
    albumtitle="";  		
		$.ajax({
		  url: '../php/ajax_call.php',
		  type: 'post',
		  data: {'action': 'check_album_exists', 'title': title},
		  success: function(data, status) {
			console.log("passed");
			if(data != "false"){
				albumtitle = data;
        console.log("DATA EXISTS"+albumtitle);
      }
		  },
		  error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		  }
		}); // end ajax call
    
	});

  $("#file_input").change(function(e){
    console.log('onchange called with e=' + e);
    $("#server_msg").text("");
    $("#not_image_file").text("");
    var fnames = getFileList();
    var exts = [ "jpg", "gif", "png","jpeg" ];
    $.each( fnames, function( i, val ) {
      var extension = val.replace(/^.*\./, '');
        if (extension == val) {
            extension = '';
        } else {
           extension = extension.toLowerCase();
        }
      if(jQuery.inArray(extension,exts)==-1){
          $("#not_image_file").text("Uploaded files contains some non image files. Server will upload only image files and ignore rest.");     
          return false;    
        }
        
     });

  });


 var submitform = function(){
  console.log("submitting ");
  $( "#upalbumform" ).submit();	
 };

  var getFileList = function(){
    isimagefile ="";
    var fileList = document.getElementById('file_input').files;
    var exts = new Array("jpg", "gif", "png","jpeg");
    console.log('length' + fileList.length);
    var filenames = [];
    for (var i = 0, file; file = fileList[i]; i++) {
         filenames.push(file.name);
     }
    return filenames;
  }
  
  $("#dialog").dialog({
	  title:"AlbumExist",
	  resizable: false,
	  autoOpen: false,
    modal: true,
	  buttons: {
		'Proceed': function() {
		   
		  $(this).dialog('close');
      submitform();
		},
		'Cancel': function() {
      
		   $(this).dialog('close');
		}
	  }
	});

	$("#uploadbtn").on("click",function(event) {
    console.log("submit is clicked");
		event.preventDefault();
		if(albumtitle!=""){
			$("#dialogText").text(albumtitle+" album already exists. Do you want to upload photos in the existing album?");
			$( "#dialog" ).dialog( "open" );
		}else{
      console.log("album title is empty ");

        /*$('form').validate({
            submitHandler: function (form) {
                form.submit();
            }
        });
        
        $("#title").rules("add", {
            required: true,
            minlength: 2,
            messages: {
                required: "Required input"
            }
        });*/



      submitform();
    }
		
	});
	
});
