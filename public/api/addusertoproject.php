<?php include('secret.php');

header('Access-Control-Allow-Origin: *');

// connect to the database
$host = 'localhost';
$user = $USERNAME;
$password $PASSWORD; 
$dbname = 'taskman';

$con = mysqli_connect($host, $user, $password, $dbname);

if(!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// construct query
$username = $_GET['username'];
$project_id = $_GET['project_id'];

// find user with this username
$stmt = $con->prepare('SELECT * FROM users WHERE username=?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if(!$result) {
    echo 404;
    die(mysqli_error($con));
}
else {
    $userData = mysqli_fetch_array($result);
    
    if($userData) {
        // get user id
        $userid = $userData['id'];

        // get the project to add the user to
        $stmt = $con->prepare('SELECT * FROM projects WHERE id=?');
        $stmt->bind_param('i', $project_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $projectData  = mysqli_fetch_array($result);

        if($projectData) {
            // get list of users
            $users = json_decode($projectData['users']);

            // user not in project: add user to project
            if(!in_array($userid, $users)) {
                array_push($users, $userid);
               
                // turn array to json data
                $return = "[";
                for($x=0; $x < count($users); $x++) {
                    if($x == count($users) - 1)
                        $return = $return . $users[$x] . "]";
                    else
                        $return = $return . $users[$x] . ', ';
                }

                // push updated array to database
                $stmt = $con->prepare('UPDATE projects SET users=? WHERE id=?');
                $stmt->bind_param('si', $return, $project_id);
                $stmt->execute();

                // add project to user's list of projects
                $projects = json_decode($userData['projects']);
                
                // if project not in user's project list
                if(!in_array($project_id, $projects)) {
                    array_push($projects, $project_id);
     
                    // turn array to json data
                    $return = "[";
                    for($x=0; $x < count($projects); $x++) {
                        if($x == count($projects) - 1)
                            $return = $return . $projects[$x] . "]";
                        else
                            $return = $return . $projects[$x] . ', ';
                    }

                    // push updated array to database
                    $stmt = $con->prepare('UPDATE users SET projects=? WHERE id=?');
                    $stmt->bind_param('si', $return, $userid);
                    $stmt->execute();
                    
                    echo $username;
                }
                // user already has this project: major error 406/this shouldn't ever happen
                else {
                    echo 406;
                }
            }
            // user already added to project: 405
            else {
                echo 405;
            }
        }
        // this project doesn't exist
        else {
            echo 404;
        }
    }
    // this user doesn't exist: 404
    else {
        echo 404;
    }
}

$con->close();

?>
