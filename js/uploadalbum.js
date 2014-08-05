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

   $("#title").keyup(function(event){
     //event.preventDefault();
      var reg = /^[\w' ']+$/;
      var val =  $(this).val();//String.fromCharCode(event.which);
      console.log(reg.test(val)+"length :: "+val.length);
      if(reg.test(val) && val.length>3){
        console.log("valid"+val);

        $("#title_valid_msg").empty();
        $(".title-group").removeClass("has-error").addClass('has-success');
        $("#feedback_icon").removeClass("glyphicon-remove").addClass("glyphicon-ok");
         
      }else{
        console.log(" not valid");
        $("#title_valid_msg").html("Title should be more than 3 Alphanumeric characters");
        $(".title-group").removeClass("has-success").addClass('has-error');
        $("#feedback_icon").addClass("glyphicon-remove").removeClass("glyphicon-ok");      
       }
 
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
			$("#dialogText").text("\""+albumtitle+"\" album already exists. Do you want to upload photos in the existing album?");
			$( "#dialog" ).dialog( "open" );
		}else{
      console.log("album title is empty ");
      var title = $('#title').val();
      console.log("input title val ::"+title);
      if(title==""){
          $("#title_valid_msg").html("Title field is required");
          $(".title-group").removeClass("has-success").addClass('has-error');
          $("#feedback_icon").addClass("glyphicon-remove").removeClass("glyphicon-ok"); 

      }else{
         submitform();
      }
    }
		
	});
	
});
