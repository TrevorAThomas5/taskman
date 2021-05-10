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
$password = $_GET['password'];

$stmt = $con->prepare('SELECT * FROM users WHERE username=?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

// if this username isn't already taken
if(mysqli_num_rows($result) < 1) {
    // create new user
    $stmt = $con->prepare("INSERT INTO users (username, password, projects, friends) VALUES (?, ?, '[]', '[]')");
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();

    // return userdata of the new user
    $stmt = $con->prepare('SELECT * FROM users WHERE username=? AND password=?');
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = mysqli_fetch_array($result);
    echo json_encode($data);
}
else {
    echo 404;
    die(mysqli_error($con));
}

$con->close();

?>
