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


// delete from tasks
$stmt = $con->prepare('DELETE FROM tasks WHERE id=?');
$stmt->bind_param('i', $id);
$stmt->execute();

// get the project containing this task
$stmt = $con->prepare('SELECT * FROM projects WHERE id=?');
$stmt->bind_param('i', $project_id);
$stmt->execute();
$result = $stmt->get_result();

// delete task from project
$data = mysqli_fetch_array($result);
$tasks = json_decode($data['tasks']);
$pos = array_search($id, $tasks);
array_splice($tasks, $pos, 1);

// determine what array to return
$return = '';
if(count($tasks) <= 0) {
    $return = '[]';
}
else {
$return = "[";
for($x=0; $x < count($tasks); $x++) {
    if($x == count($tasks) - 1)
        $return = $return . $tasks[$x] . "]";
    else
        $return = $return . $tasks[$x] . ', ';
}
}

$stmt = $con->prepare('UPDATE projects SET tasks=? WHERE id=?');
$stmt->bind_param('si', $return, $project_id);
$stmt->execute();

$con->close();

?>
