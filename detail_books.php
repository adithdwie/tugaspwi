<?php
$isbn = $_GET['isbn'];
// connect database
require_once('db_login.php');
$db = new mysqli($db_host, $db_username, $db_password, $db_database);
if ($db->connect_errno){
	die ("Could not connect to the database: <br />". $db->connect_error);
}
if (!isset($_POST["submit"])){
	$query = " SELECT * FROM books WHERE isbn='".$isbn."'";
	// Execute the query
	$result = $db->query( $query );
	if (!$result){
		die ("Could not query the database: <br />". $db->error);
	}else{
		while ($row = $result->fetch_object()){
			echo '<h3>Menampilkan Data Buku dengan isbn = '.$row->isbn.'</h3>';
			echo 'Author : '.$row->author;
			echo '<br/> Title : '.$row->title;
			echo '<br/> Price : '.$row->price;
			echo '<br/><br/><a href="view_books.php">Kembali</a>';
		}
	}
}
?>