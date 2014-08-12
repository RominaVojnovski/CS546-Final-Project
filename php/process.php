<?php
include("../../../dbprop.php");




$db = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$pinfo=$_POST['pinfo'];

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
    
    while($stmt->fetch())
    {
        
        
        echo "<li><a href='comment.php?cid=$cid'>$commenttext</a></li>";
    }
    $stmt->close();
}


else if($pinfo=="add")
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
?>