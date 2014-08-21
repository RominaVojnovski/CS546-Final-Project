<?php

include("mysqli_class.php");

/* This class will perform different database operations (based on the request from controller.php ) by using database class object  */
class dboperation{
  private $db;

  public function __construct() {
       $this->db = new database();
       $this->db->connect();
   }

  /* Function get the Album information from the database if exists */
  public function getAlbumObj($title,$userid){
    $sql = "select album_id from album where title = '".$title."' and userid = ".$userid;
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting album information from db";
    }
 
  }

  /* Function get the Album information by albumid from the database if exists */
  public function getAlbumById($albumid,$userid){
    $sql = "select title,album_path,when_posted from album where album_id = ".$albumid." and userid = ".$userid;
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting album information from db";
    }
 
  }

  public function getUserByName($name){
    $sql = "select uid,name,email from users where name like '%".$name."%'";
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting user information by name from db";
    }
  }

  public function getUserArr($user_res){
     $arr = array(); 
     while ($row  =  $user_res->fetch_assoc()) {
          $arr[$row['uid']] = $row['name']."<".$row['email'].">" ;
			  } 

    return $arr;
  

  }

  public function getSharedAlbumById($albumid,$userid){

    $sql = "select a.title,a.userid,a.album_path,sa.when_shared from shared_albums as sa,album as a where a.album_id = sa.album_id 
            and sa.shared_with_uid = ".$userid." and sa.album_id = ".$albumid;
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting album information from db";
    }

  }

  public function getTags(){
    $sql = "select tag_id,tag_text from tags";
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting tag information from db";
    }

  }

  public function getTagAlbum($albumid){

    $sql = "select * from tags_albums where album_id=".$albumid;
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting tag album information from db";
    }

  }

  public function insertTagAlbum($tagid,$albumid){
    $sql = "insert into tags_albums(tagid,album_id) values (".$tagid.",".$albumid.")";
    try{
      $this->db->send_sql($sql);
      //return $this->db->insert_id();
    }catch(Exception $e){
       return "Error in inserting tag album details into db. ".$e->getMessage();   
    }
    
  }
  
  public function updateTagAlbum($tagid,$albumid){
    $sql = "update tags_albums set tagid = ".$tagid." where album_id = ".$albumid;
    try{
      $this->db->send_sql($sql);
    }catch(Exception $e){
       return "Error in updating tag album info into db. ".$e->getMessage();   
    }


  }

  public function getTagArr($tagRes){
     $arr = array(); 
     while ($row  =  $tagRes->fetch_assoc()) {
          $arr[$row['tag_id']] = $row['tag_text'] ;
			  } 

      return $arr;

  }
  public function getAlbumPhotos($albumid){
  
    $sql = "select photo_id,photo_path from album_photos where album_id = ".$albumid;
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting photo information from db";
    }

  }


  public function getPhotoArr($photores){

   $arr = array(); 
   $index = 0; 
   while ($row  =  $photores->fetch_assoc()) {
        $arr[$index] = array( 'photoid' => $row['photo_id'], 
                      'path' => $row['photo_path']);
        $index++;
			} 

    return $arr;

  }
  public function getUser($userid){
    $sql = "select when_confirmed,name from users where uid = ".$userid;
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting user information from db";
    }

  }

  public function isAlbumAlreadyShared($albumid,$uid){
      $sql = "select * from shared_albums where album_id = ".$albumid." and shared_with_uid = ".$uid;
      try{
        $res = $this->db->send_sql($sql);
        if($res->num_rows == 0){
           return False;
        }
        else
          return true; 
      }catch(Exception $e){
            echo "Error in getting user information from db";
      }

  }
/* Function add album information into the database */
  public function insertAlbum($title,$uid,$apath){
    $sql = "insert into album(title,userid,album_path) values ('".$title."',".$uid.",'".$apath."')";
    try{
      $this->db->send_sql($sql);
      return $this->db->insert_id();
    }catch(Exception $e){
       return "Error in inserting album details into db. ".$e->getMessage();   
    }
 
  }

  public function insertSharedAlbums($albumid,$uid){
    $sql = "insert into shared_albums(album_id,shared_with_uid) values (".$albumid.",".$uid.")";
    try{
      $result = $this->db->send_sql($sql);
      return $result;
    }catch(Exception $e){
       return "Error in inserting shared album details into db. ".$e->getMessage();   
    }
  }

  /* Function add album_photos information into the database */
  public function insertAlbumPhotos($albumid,$photo_path){
    $sql = "insert into album_photos(album_id,photo_path) values (".$albumid.",'".$photo_path."')";
    try{
      $this->db->send_sql($sql);
      //return $this->db->insert_id();
    }catch(Exception $e){
       return "Error in inserting album photo details into db. ".$e->getMessage();   
    }
 
  }


  public function getAlbumPhoto($albumid,$imgpath){
    $sql = "select photo_id from album_photos where album_id = ".$albumid." and photo_path = '".$imgpath."'";
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting album information from db";
    }
  }

  public function next_res_row($result){
      return $this->db->next_res_row($result);
  }



  public function getUserAlbum($userid){

    $sql = "select a.album_id,a.title,a.album_path,a.when_posted,count(ap.photo_id) as total_photos from album as a, album_photos as ap
    where a.userid = ". $userid." and a.album_id = ap.album_id GROUP BY a.album_id";
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting album information from db";
    }

  }
  
  public function getUserAlbumByTitle($userid,$searchstr){

    $sql = "select a.album_id,a.title,a.album_path,a.when_posted,count(ap.photo_id) as total_photos from album as a, album_photos as ap
    where a.userid = ". $userid." and a.album_id = ap.album_id and a.title like '%".$searchstr."%' GROUP BY a.album_id";
    try{
      $res = $this->db->send_sql($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting album information from db";
    }

  }

  public function getAlbumArray($alm_res){
   $arr = array(); 
   $index = 0; 
   while ($row  =  $alm_res->fetch_assoc()) {
        $arr[$index] = array( 'title' => $row['title'], 
                      'path' => $row['album_path'] , 
                      'date' => $row['when_posted'] , 
                      'totalphotos' => $row['total_photos'], 
                      'albumpgpath' => '',
                      'id' => $row['album_id']     
                    );
        $index++;
			} 

    return $arr;
  }

  public function getSharedAlbum($userid){

    $sql = "select sa.album_id,a.title,sa.when_shared,a.album_path,count(ap.photo_id) as total_photos from shared_albums as sa, 
    album as a, album_photos as ap where sa.shared_with_uid = ".$userid." and sa.album_id = a.album_id and a.album_id = ap.album_id 
    GROUP BY a.album_id";

    try{
      $res = $this->db->send_sql_new($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting shared album information from db";
    }

  }

  public function getSharedAlbumByTitle($userid,$searchstr){
    $sql = "select sa.album_id,a.title,sa.when_shared,a.album_path,count(ap.photo_id) as total_photos from shared_albums as sa, 
    album as a, album_photos as ap where sa.shared_with_uid = ".$userid." and sa.album_id = a.album_id and a.album_id = ap.album_id
    and a.title like '%".$searchstr."%' GROUP BY a.album_id";

    try{
      $res = $this->db->send_sql_new($sql);
      if($res->num_rows == 0){
         return False;
      }
      else
        return $res; 
    }catch(Exception $e){
          echo "Error in getting shared album information from db";
    }

  }



  public function getSharedAlbumArray($shared_album_res){
   
   $arr = array(); 
   $index = 0; 
   while ($row  =  $shared_album_res->fetch_assoc()) {
        $arr[$index] = array( 'title' => $row['title'], 
                      'path' => $row['album_path'] , 
                      'date' => $row['when_shared'] , 
                      'totalphotos' => $row['total_photos'], 
                      'albumpgpath' => '',
                      'id' => $row['album_id']     
                    );
        $index++;
			} 

    return $arr;
  }

  /* disconnnect database */
  public function disconnect_db(){
    $this->db->disconnect();
  }

  public function startTransaction(){
    $this->db->startTransaction();

  }
  public function endTransaction(){
      $this->db->endTransaction();
  }
  
}
?>
