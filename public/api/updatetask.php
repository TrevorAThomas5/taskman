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
$is_done = $_GET['is_done'];
$id = $_GET['id'];
$description = $_GET['description'];


if($is_done == 'true')
    $stmt = $con->prepare('UPDATE tasks SET is_done=1, description=? WHERE id=?');
else
    $stmt = $con->prepare('UPDATE tasks SET is_done=0, description=? WHERE id=?');
$stmt->bind_param('si', $description, $id);
$stmt->execute();


$result = $stmt->get_result();
echo $result;

$con->close();

?>
