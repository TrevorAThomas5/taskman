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
$username = $_GET['username'];

$stmt = $con->prepare('SELECT * FROM users WHERE username=?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if(!$result) {
    echo 404;
    die(mysqli_error($con));
}
else {
  
    $data = mysqli_fetch_array($result);
    
    // null out password
    $data['password'] = "XXX";
    $data[3] = "XXX";

    if($data)
        echo json_encode($data);
    else
        echo 404;

}

$con->close();

?>
