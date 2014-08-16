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
    
    $build="<table style='width: 50%'><tr><td>comment</td><td>responses</td></tr>";
    $length = count($cids);
    for ($i = 0; $i < $length; $i++) {
        if(strlen($ctexts[$i])>34){
            $short=substr($ctexts[$i],0,34)."...";
        }
        else{
            $short=$ctexts[$i];
        }
        $build.="<tr><td><a href='comment.php?cid=$cids[$i]'>$short</a></td><td>$totals[$i]</td></tr>";
        
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
?>