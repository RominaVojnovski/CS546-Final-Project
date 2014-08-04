<?php
    
    include("dboperation.php");
    //Twig loader
    require_once '../include/Twig-1.15.1/lib/Twig/Autoloader.php';
    Twig_Autoloader::register();
    //Twig use $loader variable to locate the templates.
    $loader = new Twig_Loader_Filesystem(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'twigtpl');
    //Twig_Environment is used to store the configuration.
    $twig = new Twig_Environment($loader);

    session_start();
    $templatename = "uploadalbum.tpl";
    //$_SESSION['userid']=1; 
    // Get the userid from session.
    $userid = $_SESSION['uid'];
    
   error_log("\nloggedin :: ".$_SESSION['loggedin']." userid::  ".$userid, 3, "/var/tmp/my-errors.log");
    // If session doesnot exist redirect user to login page.
    if(!isset($_SESSION['loggedin']) && !isset($userid)){
          header("location:login.php");
    }
    elseif(isset($_FILES['file_input']) && !empty($_FILES['file_input'])){
        $uploadsDirectory = dirname($_SERVER['DOCUMENT_ROOT']).DIRECTORY_SEPARATOR.'uploaded_files'.DIRECTORY_SEPARATOR.$userid; 
        // Convert image files to image objects.
        $ifileobjs = covertImageFilesToObj($_FILES['file_input'],$twig);  

        if(is_array($ifileobjs) && count($ifileobjs)>0){
            error_log("\ni am here ::: ".empty($_POST['title'])." title::: ".$_POST['title'], 3, "/var/tmp/my-errors.log");
            if(empty($_POST['title'])){
              error_log("\ntitle is not set", 3, "/var/tmp/my-errors.log");
              echo $twig->render($templatename, array('msg'=>'Album title was empty.'));
            }

            $album_title = trim($_POST['title']);
            // Make the directory for user in uploaded directory if not exist.
            if(!file_exists($uploadsDirectory)){
              mkdir($uploadsDirectory, 0777, true);
            }

            $albumpath = $uploadsDirectory.DIRECTORY_SEPARATOR.$album_title;
            // Check if given album already exists for a loggedin user. 
            if(!file_exists($albumpath) && !mkdir($albumpath, 0777, true)){

             echo $twig->render($templatename, array('msg'=>'Failed to create folders \" '.$albumpath."\"",'title'=>$album_title)); 
             
            }else{
              
              $dboperation = new dboperation();
              if($alm_res = $dboperation->getAlbumObj($album_title,$userid)){
                 $res_arr = $dboperation->next_res_row($alm_res); 
                 $albumid = $res_arr['album_id']; 
              }else{
                 $albumid= $dboperation->insertAlbum($album_title,$userid,$albumpath); 
              }
              // Move the uploaded file to it's new location.  
              uploadFiles($ifileobjs,$albumpath,$albumid,$dboperation,$twig,$templatename ); 
              
            }
         }elseif(is_array($ifileobjs) && count($ifileobjs)==0){
            if(isset($_POST['title']))
                $album_title = trim($_POST['title']);
              else
                $album_title = "";    
            echo $twig->render($templatename, array('msg'=>'No image file is uploaded. Only .jpg/.jpeg, .gif and .png images under are accepted for upload.','title'=>$album_title));
         }

        elseif(is_string($ifileobjs)){
              if(isset($_POST['title']))
                $album_title = trim($_POST['title']);
              else
                $album_title = "";            
              echo $twig->render($templatename, array('msg'=>$ifileobjs,'title'=>$album_title));
         }
     }
    elseif(empty($_FILES['file_input']) && !isset($_FILES['file_input']) && !isset($_POST['title'])){
        error_log("no problem initial call ***".isset($_POST['title']), 3, "/var/tmp/my-errors.log");
        echo $twig->render($templatename);       
    }
    else{
        echo $twig->render($templatename, array('msg'=>'Please enter (*) mandatory field(s)','title'=>""));
    }

    function uploadFiles($iFileArr,$albumpath,$albumid,$dboperation,$twig,$templatename) {
          $dboperation->startTransaction();
          foreach($iFileArr as $if)  
                {  
                  // Get a unique name of a image file.
                  $imgfpath=$albumpath.DIRECTORY_SEPARATOR.sha1_file($if->tmp_name).get_file_extension($if->name);  
                  if(upload($if,$imgfpath)){  
                      // if photo in the given album already exist donot insert into db
                      if(!$dboperation->getAlbumPhoto($albumid,$imgfpath))
                         $dboperation->insertAlbumPhotos($albumid,$imgfpath);
                  }  
              } 
          $dboperation->endTransaction();
          echo $twig->render($templatename, array('msg'=>'Photos are uploaded successfully.','title'=>"")); 
    }
    
    function upload($ifobj,$imgfpath){  
       if(move_uploaded_file($ifobj->tmp_name,$imgfpath)){  
              return true;  
         }  
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


?>

