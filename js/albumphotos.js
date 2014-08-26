$(document).ready(function() {


  var itemeventhandler =function(event){
    
    
     var id =  event.target.id+'';
     var email =  $(this).text();
     useridarr[id] = email;
     var searchstr = $("#sharepeople").val(); 
     var index = searchstr.lastIndexOf(",");
     console.log("index :: "+index);  
     if(index==-1){
      $("#sharepeople").val(email);
     }else if(index!=-1){
      $("#sharepeople").val(searchstr.substr(0, index)+","+email);
     }   
      
    /*for (var i in useridarr) {
    if ({}.hasOwnProperty.call(useridarr, i)){
        console.log("KKKKK::: "+useridarr[i] + i); 
        if( $("#sharepeople").val().length === 0 ) {
          $("#sharepeople").val(useridarr[i]); 
        }else{
          $("#sharepeople").val($("#sharepeople").val()+","+useridarr[i]); 
        }
      }
    }*/
     $("#searchhelp").fadeOut("slow"); 
     $("#searchhelp").html(""); 
     $("#sharetip").show(); 
     event.preventDefault();
  };
      
  var useridarr = {};
  $("#querymsg").text("");
  $("#sharepeople").keyup(function(event){
      $("#querymsg").text("");
      var value = $(this).val();
      if(value.length==0){
         $("#searchhelp").html("");
         $("#sharetip").show();  
      }
      
      var arr = value.split(",");
      console.log("share value :: "+value+" arr length ::"+arr.length); 
      var searchstr = arr[arr.length -1];
      if(searchstr.length>2){
          console.log("value length  :: "+value.length); 
          $.ajax({
		      url: '../php/ajax_call.php',
		      type: 'post',
		      data: {'action': 'get_user_info', 'searchstr': searchstr},
		      success: function(data, status) {
			    console.log("passed");
			      if(data != "false"){
				      var user_info = data;
              console.log("DATA EXISTS"+user_info);
              $("#searchhelp").fadeIn("slow");
              $("#searchhelp").html(data);
              $("#sharetip").hide();
              $(".search-suggestions").dropdown('toggle');
            }else{
              $("#searchhelp").html("");
              $("#sharetip").show();
            }
            $("a.clickablelinkhere").click(itemeventhandler);
		      },
		      error: function(xhr, desc, err) {
			    console.log(xhr);
			    console.log("Details: " + desc + "\nError:" + err);
		      }
		    });

      }
    
  });


  $("#sharebtn").click(function(event){
    var arr = $("#sharepeople").val().split(",");
    var ids="";
    for(var index in arr){
      var email = $.trim(arr[index]);
      console.log(email);  
      var id = checkForEmail(email);
      if(id!=-1){
        if(ids.length==0)
          ids =id;
        else
          ids = ids+"#"+id;
      }else{
          $("#querymsg").text("Please check user name or email address");
      }
    }//outter for
    
    var albumid = $("#albumid").val();
    console.log("ids :: "+ids+ "albumid :: "+albumid);
    if(ids.length!=0){
        $.ajax({
		          url: '../php/ajax_call.php',
		          type: 'post',
		          data: {'action': 'sharealbum', 'albumid': albumid,'uids':ids},
		          success: function(data, status) {
			          if(data != false){
				          var querymsg = "Album is shared successfully";
                }else{
                  var querymsg = "Error occured while sharing. Please try again."
                }
                $("#querymsg").text(querymsg);
                $("#sharepeople").val("");
                useridarr = {};
		          },
		          error: function(xhr, desc, err) {
			        console.log(xhr);
			        console.log("Details: " + desc + "\nError:" + err);
		          }
		        });
      }
    
  });

function checkForEmail(email){
  
  for(var i in useridarr){
      if ({}.hasOwnProperty.call(useridarr, i)){
        if(email === useridarr[i]){
          return i;
        }//end if
      }//end if hasown
  }//inner for

  return -1;
}

});
