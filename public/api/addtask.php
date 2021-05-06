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
$title = $_GET['title'];
$date = $_GET['date'];
$creatorid = $_GET['creatorid'];
$project_id = $_GET['project_id'];
$category = $_GET['category'];

// start a new project
if($project_id == -1) {
    

    // create new project
    $stmt = $con->prepare("INSERT INTO projects (users, tasks, logs, title) VALUES ('[]', '[]', '[]', ?)");
    $stmt->bind_param('s', $category);
    $stmt->execute();
    $project_index = mysqli_insert_id($con);

    // add task to list
    $stmt = $con->prepare("UPDATE projects SET users=JSON_ARRAY_APPEND(users, '$', ?) WHERE id=?");
    $stmt->bind_param('ii', $creatorid, $project_index);
    $stmt->execute();

    // create new task
    $stmt = $con->prepare('INSERT INTO tasks (title, date, creatorid, is_done, project_id) VALUES (?, ?, ?, false, ?)');
    $stmt->bind_param('ssii', $title, $date, $creatorid, $project_index);
    $stmt->execute();
    $task_index = mysqli_insert_id($con);  

    // add task to project's task list
    $stmt = $con->prepare("UPDATE projects SET tasks=JSON_ARRAY_APPEND(tasks, '$', ?) WHERE id=?");
    $stmt->bind_param('ii', $task_index, $project_index);
    $stmt->execute();
 
    // add project to list of user projects
    $stmt = $con->prepare("UPDATE users SET projects=JSON_ARRAY_APPEND(projects, '$', ?) WHERE id=?");
    $stmt->bind_param('ii', $project_index, $creatorid);
    $stmt->execute();

    // echo the project id and task id
    echo $project_index . ' ' . $task_index;
}
else {
    
    // create new task
    $stmt = $con->prepare('INSERT INTO tasks (title, date, creatorid, is_done, project_id) VALUES (?, ?, ?, false, ?)');
    $stmt->bind_param('ssii', $title, $date, $creatorid, $project_id);
    $stmt->execute();

    // the id of the newly inserted task
    $task_index = mysqli_insert_id($con);

    // add this task to the existing project task list
    $stmt = $con->prepare("UPDATE projects SET tasks=JSON_ARRAY_APPEND(tasks, '$', ?) WHERE id=?");
    $stmt->bind_param('ii', $task_index, $project_id);
    $stmt->execute();

    // echo the task id
    echo $task_index;
}

$con->close();

?>
