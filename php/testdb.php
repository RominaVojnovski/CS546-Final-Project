<?PHP
include("mysqli_class.php");
$db=new database();

$query="SELECT * FROM test";
$db->send_sql($query);

echo "Finished!";

?>