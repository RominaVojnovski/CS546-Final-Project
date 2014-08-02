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

  /* Function add album_photos information into the database */
  public function insertAlbumPhotos($albumid,$photo_path){
    $sql = "insert into album_photos(album_id,photo_path) values (".$albumid.",'".$photo_path."')";
    try{
      $this->db->send_sql($sql);
      return $this->db->insert_id();
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
