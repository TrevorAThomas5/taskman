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

$stmt = $con->prepare('SELECT * FROM projects WHERE id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if(!$result) {
    echo 404;
    die(mysqli_error($con));
}
else {
    // return user data
    $data = mysqli_fetch_array($result);

    if($data) {
        echo json_encode($data);
    }
    else {
        echo 404;
    }
}

$con->close();

?>
