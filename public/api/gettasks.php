<?php
header('Access-Control-Allow-Origin: *');

// connect to the database
$host = 'localhost';
$user = 'trevor';
$password = 'Grandma11223344!';
$dbname = 'test1';

$con = mysqli_connect($host, $user, $password, $dbname);

if(!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// construct query
$userid = $_GET['userid'];
$stmt = $con->prepare('SELECT * FROM tasks WHERE userid = ?');
$stmt->bind_param('s', $userid);
$stmt->execute();
$result = $stmt->get_result();

if(!$result) {
    echo 404;
    die(mysqli_error($con));
}
else {

    $encode = array();

    while($row = mysqli_fetch_array($result)) {
        $encode[] = $row;
    }

    echo json_encode($encode);
}

$con->close();

?>
