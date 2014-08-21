$(document).ready(function() {

  $("#searchalbum").keyup(function(event){
    var value = $(this).val();
    console.log("length :: "+value.length);
    if(value.length==0){
      $( "#searchform" ).submit();	
    }
      
  });
  
  $("#searchbtn").click(function(event){
    var searchstr = $("#searchalbum").val();
    console.log(searchstr);
    console.log("submitting search form");
    $( "#searchform" ).submit();	

  });

 
});
