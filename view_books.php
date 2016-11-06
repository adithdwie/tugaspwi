<!--File		: view_book.php
    Deskripsi	: menampilkan data customers
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Displaying in an HTML table</title>
</head>
<body>
<h2>Book List</h2>
<form method="get" action="">
    <input type="text" name="cari" placeholder="isbn, title, author or 'all'">
    <input type="submit" name="submit">
</form>
<br>


<?php echo "<a href='add_books.php'>Add Book</a><br><br>";  ?>
<table border="1">
    <tr>
	<th>No</th>
	<th>ISBN</th>
    <th>Author</th>
	<th>Title</th>
    <th>Price</th>
	<th colspan=3>Action</th>
    </tr>
<?php
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

// connect database
require_once('db_login.php');
$rec_limit = 10;
$db = new mysqli($db_host, $db_username, $db_password, $db_database);
if ($db->connect_errno){
    die ("Could not connect to the database: <br />". $db->connect_error);
}

$query = "SELECT count(isbn) FROM books ";
$count = $db->query( $query );
if(!$count){
    die('Could not get the data: '.$db->error);
}
$row = $count->fetch_array();
$rec_count = $row[0];
// echo $row[1];
if (isset($_GET['page'])){
    $page = $_GET['page']+1;
    $offset = $rec_limit * $page;
} else {
    $page = 0;
    $offset = 0;
}
$left_rec = $rec_count - ($page * $rec_limit);

//Asign a query
$query = " SELECT * FROM books ORDER BY isbn LIMIT $offset, $rec_limit";

// mengecek apakah pencarian diaktifkan
if (isset($_GET['submit'])) {
    $kata_kunci = $_GET['cari'];
    $kata_kunci = test_input($kata_kunci);
    $kata_kunci = $db->real_escape_string($kata_kunci);

    $query = " SELECT * FROM books
                    WHERE title LIKE '%".$kata_kunci."%' OR author LIKE '%".$kata_kunci."%'
                    OR isbn LIKE '%" . $kata_kunci . "%'
                    ORDER BY isbn ";

    // ketika input isbn
    if (is_numeric($kata_kunci)) {
        $kata_kunci = str_split($kata_kunci);
        $kata_kunci_sesungguhnya = '';
        if (count($kata_kunci) == 10) {
            for ($i = 0; $i < 10; $i++) {
                if ($i == 1 or $i == 4 or $i == 9) {
                    $kata_kunci_sesungguhnya .= '-';
                }

                $kata_kunci_sesungguhnya .= $kata_kunci[$i];
            }
        } else {
            $kata_kunci_sesungguhnya = implode('', $kata_kunci);
        }

        $query = "SELECT * FROM books where isbn LIKE '%" . $kata_kunci_sesungguhnya."%'";
    }

    if ($kata_kunci === 'all') {
        $query = " SELECT * FROM books ORDER BY isbn ";
    }
}

// Execute the query
$result = $db->query( $query );
if (!$result){
   die ("Could not query the database: <br />". $db->error);
}
// Fetch and display the results
$i = 1;
while ($row = $result->fetch_object()){
    echo '<tr>';
    echo '<td>'.$i.'</td>';
    echo '<td>'.$row->isbn.'</td> ';
	echo '<td>'.$row->author.'</td> ';
    echo '<td>'.$row->title.'</td>';
    echo '<td>'.$row->price.'</td>';
    echo '<td>';
    echo '<a href="detail_books.php?isbn='.$row->isbn.'">Detail</a> ';
    echo '</td>';
    echo '<td>';
    echo '<a href="edit_books.php?isbn='.$row->isbn.'">Edit</a> ';
    echo '</td>';
	echo '<td>';
    echo '<a href="delete_books.php?isbn='.$row->isbn.'">Delete</a>';
    echo '</td>';
	echo '</tr>';
	$i++;
}

echo '</table>';
echo '<br />';
if (!isset($_GET['submit'])) {
    // ketika fitur pencarian digunakan
    if( $left_rec > $rec_limit && $page != 0) {
        $last = $page - 2;
        echo "<a href = \"view_books.php?page=".$last."\"><button>&lt; Prev Records</button></a>";
        echo "<a href = \"view_books.php?page=".$page."\"><button>Next Records &gt;</button></a>";
    } elseif ($left_rec <= $rec_limit && $page == 0) {
        // echo "haiii";
    } elseif( $page == 0 ) {
        echo "<a href = \"view_books.php?page=".$page."\"><button> Next Records &gt;</button></a>";
    } elseif( $left_rec <= $rec_limit ) {
        $last = $page - 2;
        echo "<a href = \"view_books.php?page=".$last."\"><button>&lt; Prev Records</button></a>";
    }
}

echo '</br><br>';
echo 'Total Rows = '.$result->num_rows;

$result->free();
$db->close();
?>
</table>
</body>
</html>
