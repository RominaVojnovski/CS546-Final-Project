<?php
if(isset($_GET['path'])){
$file=$_GET['path'];
header('Content-type:' . mime_content_type($file));
readfile($file);
}
?>
