<?php
//File	: add_customer.php
//Deskripsi	: menampilkan form add data customer dan menginsert data ke database
$isbn= $_GET['isbn'];
// connect database
require_once('db_login.php');
$db = new mysqli($db_host, $db_username, $db_password, $db_database);
if ($db->connect_errno){
	die ("Could not connect to the database: <br />". $db->connect_error);
}
	$error_isbn="";
	$error_author="";
	$error_title="";
	$error_price="";
if (!isset($_POST["submit"])){
	$query = " SELECT * FROM books WHERE isbn='".$isbn."'";
	// Execute the query
	$result = $db->query( $query );
	if (!$result){
		die ("Could not query the database: <br />". $db->error);
	} else {
		while ($row = $result->fetch_object()){
			$author = $row->author;
			$title = $row->title;
			$price = $row->price;
		}
	}
} else {
	$author = test_input($_POST['author']);
	if ($author == ''){
		$error_author = "Author is required";
		$valid_author = FALSE;
	}elseif (!preg_match("/^[a-zA-Z ]*$/",$author)) {
       $error_author = "Only letters and white space allowed";
	   $valid_author = FALSE;
	}else{
		$valid_author = TRUE;
	}
	
	$title = test_input($_POST['title']);
	if ($title == ''){
		$error_title = "Title is required";
		$valid_title = FALSE;
	}else{
		$valid_title = TRUE;
	}

	$price = test_input($_POST['price']);
	if ($price == ''){
		$error_price = "Price is required";
		$valid_price = FALSE;
	} else {
		$valid_price = TRUE;
	}

	//update data into database
	if ($valid_author && $valid_title && $valid_price){
		//escape inputs data
		$author = $db->real_escape_string($author);
		$title = $db->real_escape_string($title);
		$price = $db->real_escape_string($price);
		//Asign a query
		$query = " UPDATE books 
			SET author='".$author."', title='".$title."', price='".$price."' WHERE isbn='".$isbn."' ";
		// Execute the query
		$result = $db->query( $query );
		if (!$result){
		   die ("Could not query the database: <br />". $db->error);
		}else{
			echo 'Data has been updated.<br /><br />';
			echo '<a href="view_books.php">Back to books data</a>';
			$db->close();
			exit;
		}
	}
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<!DOCTYPE HTML> 
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body> 
    <h2>Edit book</h2>
<form method="POST" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?isbn='.$isbn;?>">
<table>
    <tr>
        <td valign="top">Author</td>
        <td valign="top">:</td>
        <td valign="top"><input type="text" name="author" size="30" maxlength="50" placeholder="Author (max 50 characters)" value="<?php echo $author;?>" autofocus></td>
        <td valign="top"><span class="error">* <?php echo $error_author;?></span></td>
    </tr>
    <tr>
        <td valign="top">Title</td>
        <td valign="top">:</td>
        <td valign="top"><textarea name="title" rows="5" cols="30" placeholder="Title (max 100 characters)"><?php echo $title;?></textarea></td>
        <td valign="top"><span class="error">* <?php echo $error_title;?></span></td>
    </tr>
	<tr>
   		<td valign="top">Price</td>
   		<td valign="top">:</td>
   		<td valign="top"><input type="number" step="0.01" name="price" placeholder="Price (in Dollars)" value="<?php echo $price;?>"></td>
   		<td valign="top"><span class="error">* <?php echo $error_price;?></span></td>
   	</tr>
    <tr>
        <td valign="top" colspan="3"><br><input type="submit" name="submit" value="Submit">
    </tr>
    </table>
</form>
</body>
</html>
<script type="text/javascript">
$( '#productkey' ).on( 'keyup', 'input', function () {
    if ( this.value.length === 5 ) {
        $( this ).next().focus();            
    }
});
</script>
<?php
$db->close();
?>

