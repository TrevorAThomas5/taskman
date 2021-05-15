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
$project_id = $_GET['project_id'];

// delete from logs
$stmt = $con->prepare('DELETE FROM logs WHERE id=?');
$stmt->bind_param('i', $id);
$stmt->execute();

// get the project containing this log 
$stmt = $con->prepare('SELECT * FROM projects WHERE id=?');
$stmt->bind_param('i', $project_id);
$stmt->execute();
$result = $stmt->get_result();

// delete log from project
$data = mysqli_fetch_array($result);
$logs = json_decode($data['logs']);
$pos = array_search($id, $logs);
array_splice($logs, $pos, 1);

// determine what array to return
$return = '';
if(count($logs) <= 0) {
    $return = '[]';
}
else {
$return = "[";
for($x=0; $x < count($logs); $x++) {
    if($x == count($logs) - 1)
        $return = $return . $logs[$x] . "]";
    else
        $return = $return . $logs[$x] . ', ';
}
}

$stmt = $con->prepare('UPDATE projects SET logs=? WHERE id=?');
$stmt->bind_param('si', $return, $project_id);
$stmt->execute();

$con->close();

?>
