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
$id = $_GET['id'];

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

        // add userid to user's list of friends
        $stmt = $con->prepare('SELECT * FROM users WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = mysqli_fetch_array($result);

        // get list of friends
        $friends = json_decode($userData['friends']);

        // user not in friends list, add user to list
        if(!in_array($userid, $friends)) {
            array_push($friends, $userid);
           
            // turn array to json data
            $return = "[";
            for($x=0; $x < count($friends); $x++) {
                if($x == count($friends) - 1)
                    $return = $return . $friends[$x] . "]";
                else
                    $return = $return . $friends[$x] . ', ';
            }

            // push updated array to database
            $stmt = $con->prepare('UPDATE users SET friends=? WHERE id=?');
            $stmt->bind_param('si', $return, $id);
            $stmt->execute();

            echo $username;
        }
        // user already added to project: 405
        else {
            echo 405;
        }
    }
    // this user doesn't exist: 404
    else {
        echo 404;
    }
}

$con->close();

?>
