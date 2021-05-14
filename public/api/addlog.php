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
$text = $_GET['text'];
$date = $_GET['date'];
$creatorid = $_GET['creatorid'];
$project_id = $_GET['project_id'];

// create new task
$stmt = $con->prepare('INSERT INTO logs (text, date, creatorid, project_id) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssii', $text, $date, $creatorid, $project_id);
$stmt->execute();

// the id of the newly inserted task
$log_index = mysqli_insert_id($con);

// add this task to the existing project task list
$stmt = $con->prepare("UPDATE projects SET logs=JSON_ARRAY_APPEND(logs, '$', ?) WHERE id=?");
$stmt->bind_param('ii', $log_index, $project_id);
$stmt->execute();

// echo the task id
echo $log_index;

$con->close();

?>
