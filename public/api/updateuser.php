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
$description = $_GET['description'];

$stmt = $con->prepare('UPDATE users SET description=? WHERE id=?');
$stmt->bind_param('si', $description, $id);
$stmt->execute();

$result = $stmt->get_result();
echo $result;

$con->close();

?>
