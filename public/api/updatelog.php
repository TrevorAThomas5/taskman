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
$id = $_GET['id'];
$text = $_GET['text'];

$stmt = $con->prepare('UPDATE logs SET text=? WHERE id=?');
$stmt->bind_param('si', $text, $id);
$stmt->execute();

$result = $stmt->get_result();
echo $result;

$con->close();

?>
