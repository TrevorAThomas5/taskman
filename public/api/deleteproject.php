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
$project_id = $_GET['project_id'];

// get user and task lists from project data
$stmt = $con->prepare('SELECT * FROM projects WHERE id=?');
$stmt->bind_param('i', $project_id);
$stmt->execute();
$result = $stmt->get_result();

$project_data = mysqli_fetch_array($result);
$tasks = json_decode($project_data['tasks']);
$logs = json_decode($project_data['logs']);
$users = json_decode($project_data['users']);

// delete project
$stmt = $con->prepare('DELETE FROM projects WHERE id=?');
$stmt->bind_param('i', $project_id);
$stmt->execute();

// delete all tasks in project
foreach($tasks as $task_id) {
    $stmt = $con->prepare('DELETE FROM tasks WHERE id=?');
    $stmt->bind_param('i', $task_id);
    $stmt->execute();
}

// delete all logs in project
foreach($logss as $log_id) {
    $stmt = $con->prepare('DELETE FROM logs WHERE id=?');
    $stmt->bind_param('i', $log_id);
    $stmt->execute();
}

// update all users project list who had the project
foreach($users as $user_id) {
    $stmt = $con->prepare('SELECT * FROM users WHERE id=?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $user_data = mysqli_fetch_array($result);
    $projects = json_decode($user_data['projects']);
    $pos = array_search($project_id, $projects);
    array_splice($projects, $pos, 1);

    // determine what array to return
    $return = '';
    if(count($projects) <= 0) {
        $return = '[]';
    }
    else {
        $return = "[";
        for($x=0; $x < count($projects); $x++) {
            if($x == count($projects) - 1)
                $return = $return . $projects[$x] . "]";
            else
                $return = $return . $projects[$x] . ', ';
        }
    }
    
    $stmt = $con->prepare('UPDATE users SET projects=? WHERE id=?');
    $stmt->bind_param('si', $return, $user_id);
    $stmt->execute();
}

$con->close();

?>
