<?php


$isbn = $_GET['isbn'];
// connect database
require_once('db_login.php');
$db = new mysqli($db_host, $db_username, $db_password, $db_database);
if ($db->connect_errno){
	die ("Could not connect to the database: <br />". $db->connect_error);
}
if (isset($_POST["submit"])){
	$query = " DELETE FROM books WHERE isbn='".$isbn."'";
	// Execute the query
	$result = $db->query( $query );
	if (!$result){
		die ("Could not query the database: <br />". $db->error);
	}else{
			echo 'Data has been deleted.<br /><br />';
			echo '<a href="view_books.php">Back to books data</a>';
			$db->close();
			exit;
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Delete</title>
</head>
<body>
<h3>Are you sure want to delete book with isbn=<?php echo $isbn?></h3>
<form method="POST" action=''>
<input type='submit' name='submit' value='yes'>
<button><a style="text-decoration: none; color: black; cursor: default;" href='view_books.php'>no, back to book list</a></button> 
</form>
</body>
</html>