<?php
    
    include("dboperation.php");
    //Twig loader
    require_once '../include/Twig-1.15.1/lib/Twig/Autoloader.php';
    Twig_Autoloader::register();
    //Twig use $loader variable to locate the templates.
    $loader = new Twig_Loader_Filesystem(dirname(dirname(__FILE__))."/".'twigtpl');
    //Twig_Environment is used to store the configuration.
    $twig = new Twig_Environment($loader);

    session_start();
    $templatename = "uploadalbum.tpl";
    $dboperation = new dboperation();

    //$_SESSION['userid']=1; 
    // Get the userid from session.
    $userid = $_SESSION['uid'];
    $uname = $_SESSION['name'];
    $confirmed_reg_date = $_SESSION['confirmed'];
    //error_log("\nwhen confirmed ".$confirmed_reg_date. " ".$userid, 3, "/var/tmp/my-errors.log");

    if(empty($confirmed_reg_date) && isset($userid) ){
        if($user_res = $dboperation->getUser($userid)){
          $user_arr = $dboperation->next_res_row($user_res);
          $confirmed_reg_date = $user_arr['when_confirmed'];
          $_SESSION['confirmed'] = $confirmed_reg_date;
          //error_log("\nwhen confirmed ".$confirmed_reg_date. " ".$userid, 3, "/var/tmp/my-errors.log");
           
        }
    }
  

    // If session doesnot exist redirect user to login page.
    if((!isset($_SESSION['loggedin']) && !isset($userid)) || empty($confirmed_reg_date)){
          header("location:login.php");
    }
    elseif(isset($_FILES['file_input']) && !empty($_FILES['file_input'])){
        $uploadsDirectory = dirname($_SERVER['DOCUMENT_ROOT'])."/".'uploaded_files'."/".$userid; 
        // Convert image files to image objects.
        $ifileobjs = covertImageFilesToObj($_FILES['file_input'],$twig);  

        if(is_array($ifileobjs) && count($ifileobjs)>0){
            //error_log("\ni am here ::: ".empty($_POST['title'])." title::: ".$_POST['title']." string lenght ".strlen(trim($_POST['title']))." reg ".preg_match('/^[\w\-" "]+$/', trim($_POST['title'])), 3, "/var/tmp/my-errors.log");
            
              if(empty($_POST['title'])){
                  //error_log("\ntitle is not set", 3, "/var/tmp/my-errors.log");
                  echo $twig->render($templatename, array('msg'=>'Album title was empty.','uname'=>$uname));
              }
              elseif(!empty($_POST['title']) && strlen(trim($_POST['title']))>3 && preg_match('/^[\w\-" "]+$/', trim($_POST['title'])) ){
                   $album_title = trim($_POST['title']);
                  // Make the directory for user in uploaded directory if not exist.
                  if(!file_exists($uploadsDirectory)){
                    mkdir($uploadsDirectory, 0777, true);
                  }

                  $albumpath = $uploadsDirectory."/".$album_title;
                  // Check if given album already exists for a loggedin user. 
                  if(!file_exists($albumpath) && !mkdir($albumpath, 0777, true)){

                   echo $twig->render($templatename, array('msg'=>'Failed to create folders \" '.$albumpath."\"",'title'=>$album_title,'uname' => $uname)); 
                   
                  }else{
                    
                    
                    if($alm_res = $dboperation->getAlbumObj($album_title,$userid)){
                       $res_arr = $dboperation->next_res_row($alm_res); 
                       $albumid = $res_arr['album_id']; 
                    }else{
                       $albumid= $dboperation->insertAlbum(addslashes($album_title),$userid,$albumpath); 
                    }
                    // Move the uploaded file to it's new location.  
                    if(uploadFiles($ifileobjs,$albumpath,$albumid,$dboperation,$twig,$templatename )){
                        // Create thumbnail images of album photos.    
                        createThumbnailImage($albumpath);
                        echo $twig->render($templatename, array('msg'=>'Photos are uploaded successfully.','title'=>"",'uname' => $uname));    
                    } 
                 }
                 
              }// else if
              else{
                 echo $twig->render($templatename, array('msg'=>'Album title should be more than 3 Alphanumeric characters','title'=>$_POST ['title'],'uname' => $uname));     
               } 
         }elseif(is_array($ifileobjs) && count($ifileobjs)==0){
            if(isset($_POST['title']))
                $album_title = trim($_POST['title']);
              else
                $album_title = "";    
            echo $twig->render($templatename, array('msg'=>'No image file is uploaded. Only .jpg/.jpeg, .gif and .png images under are    accepted for upload.','title'=>$album_title,'uname' => $uname));
         }

        elseif(is_string($ifileobjs)){
              if(isset($_POST['title']))
                $album_title = trim($_POST['title']);
              else
                $album_title = "";            
              echo $twig->render($templatename, array('msg'=>$ifileobjs,'title'=>$album_title,'uname' => $uname));
         }
     }
    elseif(empty($_FILES['file_input']) && !isset($_FILES['file_input']) && !isset($_POST['title'])){
        //error_log("no problem initial call ***".isset($_POST['title']), 3, "/var/tmp/my-errors.log");
        echo $twig->render($templatename,array('uname' => $uname));       
    }
    else{
        echo $twig->render($templatename, array('msg'=>'Please enter (*) mandatory field(s)','title'=>"",'uname' => $uname));
    }

    function uploadFiles($iFileArr,$albumpath,$albumid,$dboperation,$twig,$templatename) {
          $dboperation->startTransaction();
          $result = false;
          foreach($iFileArr as $if)  
                {  
                  // Get a unique name of a image file.
                  $imgfpath=$albumpath."/".sha1_file($if->tmp_name).get_file_extension($if->name);  
                  if(upload($if,$imgfpath)){  
                      // if photo in the given album already exist donot insert into db
                      if(!$dboperation->getAlbumPhoto($albumid,$imgfpath))
                         $dboperation->insertAlbumPhotos($albumid,$imgfpath);
                      $result = true; 
                  }else{
                      $result = false;
                  }  
              } 
         $dboperation->endTransaction();
         return $result;
    }
    
    function upload($ifobj,$imgfpath){  
       if(move_uploaded_file($ifobj->tmp_name,$imgfpath)){  
            return true;  
       } 
       else
            return false;  
    }


  function covertImageFilesToObj($fileArr,$twig){  
          $imageFiles = array();
          foreach($fileArr['name'] as $k => $val)  
          {  
             if($fileArr['error'][$k] == 0){ 
                 //Check if the file MIME type and extension ( JPG, PNG or GIF images are accepted)
                 if(acceptedMIMEType($fileArr['tmp_name'][$k]) && checkFileExt($fileArr['name'][$k])){ 
                   $ifile = new ImageFile();
                   $ifile->name = $fileArr['name'][$k];
                   $ifile->type = $fileArr['type'][$k];
                   $ifile->tmp_name = $fileArr['tmp_name'][$k]; 
                   $ifile->error = $fileArr['error'][$k];  
                   $imageFiles[$k] =  $ifile;
                }/*else{
                  echo "Error in uploading File ".$fileArr['name'][$k].". Only .jpg/.jpeg, .gif and .png images under are accepted for upload.<br/>";
                }*/
            }else{
                 switch ($fileArr['error'][$k]) {
                  case UPLOAD_ERR_PARTIAL:
                      return 'Upload failed : Uploaded folder contains subfolders. It should only contains files.';  
                      break;      
                  case UPLOAD_ERR_NO_FILE:
                      return 'Upload failed : No file sent.';
                      break;
                  case UPLOAD_ERR_INI_SIZE:
                      return "Upload failed : The uploaded file".$fileArr['name'][$k]." exceeds the accepted size limit (i.e. 5mb).";
                      break;    
              }
            }
          }// foreach  
          return $imageFiles;  
      }  

    function get_file_extension($file_name)  
    {  
      return substr($file_name,strrpos($file_name,'.'));  
    }  

    function checkFileExt($file_name){
      $arrayExtensions = array(".jpg", ".jpeg", ".png", ".gif");
      $fileExt = get_file_extension($file_name);
      return in_array(strtolower($fileExt), $arrayExtensions);  

    }

    function acceptedMIMEType($name){
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      $allowedMIME = array("image/jpeg","image/png","image/gif");

      if (false === $ext = array_search($finfo->file($name),$allowedMIME,true)) {
          return false;
      }else
          return true;
    }

    class ImageFile{  
        public $name;
        public $type;
        public $tmp_name;
        public $error;
          
    } 

  function createThumbnailImage($albumpath){

    //Create thumbs folder.
    if(!file_exists($albumpath."/"."thumbs")){
       $thumb_path = $albumpath."/"."thumbs";    
       mkdir($thumb_path,0777,true);
     }else
       $thumb_path = $albumpath."/"."thumbs";

    // Open current album directory. 
     if ( $handle = opendir($albumpath)) {
        while ( ($file = readdir($handle)) !== false ) {
            $fpath = $albumpath."/".$file;  
			      if ( strcmp(filetype($fpath),"file")==0 ) {
                resizeExistingImage($thumb_path,$file,$albumpath) ;
			      }//if is_dir close
		      }// while close
     } //if close

  }

  function resizeExistingImage($thumb_path,$file,$albumpath){
    $max_height = 140 ;
    $max_width = 140;

    $ext = get_file_extension($file);
    $fpath =  $albumpath."/".$file; 
    $img_type = getImageType($ext);
    //echo "file ::".$fpath." ext ".$img_type."<br/>";
    if($img_type!=''){
       //echo "one<br/>"; 
       if ( ! file_exists($thumb_path."/".$file) ) {   
            if ( $img_type == 'jpg' ) {
			         $src_hand = imagecreatefromjpeg($fpath);
		        } else if ( $img_type == 'png' ) {
			         $src_hand = imagecreatefrompng($fpath);
		        } else if ( $img_type == 'gif' ) {
			         $src_hand = imagecreatefromgif($fpath);
		        }
            if($src_hand){
              if ( ($oldW = imagesx($src_hand)) < ($oldH = imagesy($src_hand)) ) 
                 {
                    $newW = $oldW * ($max_width / $oldH);
                    $newH = $max_height;
                 } else {
                    $newW = $max_width;
                    $newH = $oldH * ($max_height / $oldW);
                 }
               // create a black block image.    
               //error_log("\n new dimentions :::: for file ::".$file."width:: ".$newW." height:: ".$newH, 3, "/var/tmp/my-errors.log"); 
               $new_imgres = imagecreatetruecolor($newW, $newH);
               //copy and resizing the original image into thumbnail image.
               imagecopyresampled($new_imgres, $src_hand, 0, 0, 0, 0, $newW, $newH, $oldW, $oldH);   
               if ( $img_type == 'jpg' ) {
			            imagejpeg($new_imgres, $thumb_path."/".$file);
		           } else if ( $img_type == 'png' ) {
			            imagepng($new_imgres, $thumb_path."/".$file);
		           } else if ( $img_type == 'gif' ) {
			            imagegif($new_imgres, $thumb_path."/".$file);
		           }
		           imagedestroy($new_imgres);
		           imagedestroy($src_hand);
                                   
           }
       } 
    }// if type close

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

