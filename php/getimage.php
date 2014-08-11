<?php

if(isset($_GET['path']) && !empty($_GET['path'])){

    $imgpath = $uploadsDirectory = dirname($_SERVER['DOCUMENT_ROOT'])."/".'uploaded_files'."/".$_GET['path'];
    $type = getImageType(get_file_extension($imgpath));

    if ( $type == 'jpg' ) {
			       header("Content-Type: image/jpeg");
		       } else if ( $type == 'png' ) {
			       header("Content-Type: image/png");
		       } else if ( $type == 'gif' ) {
			       header("Content-Type: image/gif");
		       }else{
             //header("Content-Type: image/gif");      
           }
     readfile($imgpath);
}


  
    function get_file_extension($fpath)  
    { 
      $file_name = substr($fpath,strrpos($fpath,"/")+1); 
     
      return substr($file_name,strrpos($file_name,'.'));  
    }  

    function getImageType($ext) {
		  if ( preg_match('/jpg|jpeg/i', $ext) ) {
			    return 'jpg';
		  } else if ( preg_match('/png/i', $ext) ) {
			    return 'png';
		  } else if ( preg_match('/gif/i', $ext) ) {
			    return 'gif';
		  } else {
			    return '';
		  }
	}

 


?>
