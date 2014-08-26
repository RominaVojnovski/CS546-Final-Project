<?php
include("../../../dbprop.php");
$db = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$pinfo=$_POST['pinfo'];
session_start();
if(isset($_SESSION['uid'])){
  $userid = $_SESSION['uid'];
}

if($pinfo=="show")
{
    $pid=$_POST['pid'];
    $sql="SELECT comment_id,comment_text FROM comments WHERE photo_id = ?";
    
    if (!$stmt = $db->prepare($sql)) 
    {
        //echo 'Database prepare error';
        //exit;
    }

    $stmt->bind_param('i',$pid);

    if (!$stmt->execute()) 
    {
        //echo 'Database execute error';
        //exit;
    }
  
    $stmt->bind_result($cid,$commenttext); 
    $cids=array();
    $ctexts=array();
    
    while($stmt->fetch())
    {
        //$build.="<tr><td><a href='comment.php?cid=$cid'>$commenttext</a></td></tr>";
        $cids[]=$cid;
        $ctexts[]=$commenttext;
    
    }
    
    $stmt->close();
    
    $totals=array();
    foreach($cids as $val){
        $sql="SELECT COUNT(*) FROM responses WHERE comment_id = ? GROUP BY comment_id";
        if (!$stmt = $db->prepare($sql)) 
        {
        //echo 'Database prepare error';
        //exit;
        }

        $stmt->bind_param('i',$val);

        if (!$stmt->execute()) 
        {
        //echo 'Database execute error';
        //exit;
        }
  
        $stmt->bind_result($total);
        $stmt->fetch();
        if($total>0){
            $totals[]=$total;
        }
        else{
            $totals[]=0;    
        }
            $stmt->close();

    }
    
    $build="<table style='width: 50%;margin-left:auto;margin-right:auto'><tr><td style='width:80%'>comment</td><td style='text-align:center;width=20%'>responses</td></tr>";
    $length = count($cids);
    for ($i = 0; $i < $length; $i++) {
        if(strlen($ctexts[$i])>34){
            $short=substr($ctexts[$i],0,34)."...";
        }
        else{
            $short=$ctexts[$i];
        }
        $build.="<tr><td style='width=80%'><a href='comment.php?cid=$cids[$i]'>$short</a></td><td style='text-align:center;;width=20%'>$totals[$i]</td></tr>";
        
    }

    $build.="</table>";
    echo $build;

}


if($pinfo=="add")
{
    
    $uid=$_POST['uid'];
    $pid=$_POST['pid']; 
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
    $proceed=true;
    
    if((empty($title))||(empty($comment))){
        
        $proceed=false;
    
    }
    
    if($proceed)
    {
        $sql = "INSERT INTO comments (title,comment_text,photo_id,userid) VALUES (?,?,?,?)";

        if (!$stmt = $db->prepare($sql)) 
        {
            echo 'Database prepare error';
            exit;
        }

        $stmt->bind_param('ssii',$title,$comment,$pid,$uid);

        if (!$stmt->execute()) 
        {
            echo 'Database execute error';
            exit;
        }
        $stmt->close();
        echo "Comment submitted!";
    }
    else
    {
        echo "Please write something!";
    }
}

if($pinfo=="show2")
{
    $cid=$_POST['cid'];
    $sql="SELECT r.response_id,r.when_posted,r.response_text,u.name FROM responses r,users u WHERE u.uid = r.userid AND comment_id = ?";
    
    if (!$stmt = $db->prepare($sql)) 
    {
        //echo 'Database prepare error';
        //exit;
    }

    $stmt->bind_param('i',$cid);

    if (!$stmt->execute()) 
    {
        //echo 'Database execute error';
        //exit;
    }
  
    $stmt->bind_result($rid,$rdate,$responsetext,$responder); 

    $build='';
    while($stmt->fetch())
    {
        $build.="<p>$responsetext</p><p>$rdate by: $responder</p><hr/>";
    
    }
    
    $stmt->close();
  
    echo $build;
    
    
    
}

if($pinfo=="add2")
{
    $uid=$_POST['uid'];
    $cid=$_POST['cid']; 
    $response = filter_var($_POST['response'], FILTER_SANITIZE_STRING);
    $proceed=true;
    
    if((empty($response))){
        
        $proceed=false;
    
    }
    
    if($proceed)
    {
        $sql = "INSERT INTO responses (response_text,comment_id,userid) VALUES (?,?,?)";

        if (!$stmt = $db->prepare($sql)) 
        {
            echo 'Database prepare error';
            exit;
        }

        $stmt->bind_param('sii',$response,$cid,$uid);

        if (!$stmt->execute()) 
        {
            echo 'Database execute error';
            exit;
        }
        $stmt->close();
        echo "Response submitted!";
    }
    else
    {
        echo "Please write something!";
    }
    
    
    
}
if($pinfo=="showtags")
{
    $sql="SELECT tag_id,tag_text from tags";
    
    if (!$stmt = $db->prepare($sql)) 
    {
        //echo 'Database prepare error';
        //exit;
    }

    if (!$stmt->execute()) 
    {
        //echo 'Database execute error';
        //exit;
    }
  
    $stmt->bind_result($tid,$ttext);
    $build="<option disabled selected>----Choose a tag----</option>";
    while($stmt->fetch())
    {
        $build.="<option value=".$tid.">".$ttext."</option>";

    }
    
    $stmt->close();
    
    echo $build;
}
if($pinfo=="addtags")
{
    $message='';
    $proceed=true;
    $tagtext=trim($_POST['newtag']);
    
    if((empty($tagtext))){
        $proceed=false;
    }
    
    $tarray = explode(',',$tagtext);
    
    //check for illegal characters in any of the tags if there are then let user know
    foreach($tarray as $val){

        if(!ctype_alpha(str_replace(' ', '', $val))){
            $proceed=false;
            //$message.="please enter only alphabetic chars";
        }
    }
    
    if($proceed){
        //loop thru each tag and insert if not already in db
        $status=false;
        foreach($tarray as $val){
            $val2=ucwords(strtolower($val));
            $sql="SELECT tag_text from tags where tag_text = ?";
            
            if(!$stmt = $db->prepare($sql)) 
            {
                echo 'Database prepare error';
                exit;
            }
            $stmt->bind_param('s',$val2);
            if (!$stmt->execute()) 
            {
                echo 'Database execute error';
                exit;
            }
  
            $stmt->bind_result($ttext);
            
            $stmt->fetch();
            $stmt->close();
            
            //if this then tag not in db so go ahead and insert in db
            if(((strcmp($val2,$ttext))>0) || ((strcmp($val2,$ttext))<0)) {
                $status=true;
                $sql = "INSERT INTO tags (tag_text) VALUES (?)";
                
                if (!$stmt = $db->prepare($sql)) 
                {
                    echo 'Database prepare error';
                    exit;
                }

                $stmt->bind_param('s',$val2);

                if (!$stmt->execute()) 
                {
                    echo 'Database execute error';
                    exit;
                }
        
                $stmt->close();
                }  
        }
        
        if($status){
            echo "Tags inserted";
            
        }
        else{
            echo "No tags inserted";
        }
        
    }
    else{
    
        echo "Please try again";
    }
}
if($pinfo=="tagged")
{
    $tagid=$_POST['val'];
    $sql="SELECT a.title,u.name,ta.album_id FROM album a,users u,tags_albums ta WHERE ta.album_id = a.album_id AND a.userid = u.uid AND  ta.tagid = ? AND u.uid = ?";



    if (!$stmt = $db->prepare($sql)) 
    {
        echo 'Database prepare error';
        exit;
    }

    $stmt->bind_param('ii',$tagid,$userid);
    if (!$stmt->execute()) 
    {
        echo 'Database execute error';
        exit;
    }
  
    $stmt->bind_result($title,$name,$aid); 
    $titles=array();
    $posters=array();
    $aids=array();
    
    while($stmt->fetch())
    {
        $titles[]=$title;
        $posters[]=$name;
        $aids[]=$aid;
    }
    
    $stmt->close();
    
    $totals=array();
    foreach($aids as $val){
        $sql="SELECT COUNT(*) FROM album_photos WHERE album_id = ? GROUP BY album_id";
        if (!$stmt = $db->prepare($sql)) 
        {
            echo 'Database prepare error';
            exit;
        }

        $stmt->bind_param('i',$val);

        if (!$stmt->execute()) 
        {
            echo 'Database execute error';
            exit;
        }
  
        $stmt->bind_result($total);
        $stmt->fetch();
        if($total>0){
            $totals[]=$total;
        }
        else{
            $totals[]=0;    
        }
            $stmt->close();

    }
    $build="<tr>
                <th>Album Title</th>
                <th>Total Photos</th>
                <th>Uploaded By</th>
            <tr/>";
    
    $length = count($aids);
    for ($i = 0; $i < $length; $i++) {
        $build.="<tr>
                <td><a href='getAlbum.php?albumid=$aids[$i]'>$titles[$i]</a></td>
                <td>$totals[$i]</td>
                <td>$posters[$i]</td>
                </tr>";
        
    }

    //Get shared albums associated with a given tag.

    $sharedalbums = getTaggedSharedAlbum($tagid,$db,$userid);
    if(!empty($sharedalbums))
      $build.=$sharedalbums;
    echo $build;
}
   
function getTaggedSharedAlbum($tagid,$db,$userid){


$sql = "select  a.title,u.name,ta.album_id,count(ap.photo_id) as total_photos from tags_albums ta, shared_albums sa, users u, album_photos as ap, album a where a.album_id = sa.album_id and ta.album_id = sa.album_id and a.userid = u.uid and ta.tagid  = ? and sa.shared_with_uid = ? and sa.album_id = ap.album_id GROUP BY sa.album_id";

 if (!$stmt = $db->prepare($sql)) 
    {
        echo 'Database prepare error';
        exit;
    }

    $stmt->bind_param('ii',$tagid,$userid);
    if (!$stmt->execute()) 
    {
        echo 'Database execute error';
        exit;
    }
  
    $stmt->bind_result($title,$name,$aid,$total_photos); 
    $titles=array();
    $posters=array();
    $aids=array();
    $photo_count = array();

    while($stmt->fetch())
    {
        $titles[]=$title;
        $posters[]=$name;
        $aids[]=$aid;
        $photo_count[] = $total_photos;
    }
    $stmt->close();
    
    $result = "";

    $length = count($aids);
    for ($i = 0; $i < $length; $i++) {
        $result.="<tr>
                <td><a href='getAlbum.php?albumid=$aids[$i]'>$titles[$i]</a></td>
                <td>$photo_count[$i]</td>
                <td>$posters[$i]</td>
                </tr>";
        
    }

  return $result;
}

?>
