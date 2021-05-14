<?php include('secret.php');

header('Access-Control-Allow-Origin: *');

// connect to the database
$host = 'localhost';
$user = $USERNAME;
$password = $PASSWORD;
$dbname = 'taskman';

$con = mysqli_connect($host, $user, $password, $dbname);

if(!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// construct query
$id  = $_GET['id'];
$URL = $_GET['URL'];

$stmt = $con->prepare('UPDATE users SET picture=? WHERE id=?');
$stmt->bind_param('si', $URL, $id);
$stmt->execute();

$con->close();

?>
