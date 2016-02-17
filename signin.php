<?php
    session_start();
    include 'header.php';
?>
<a href="logout.php">Click here to log out.</a>
<a href="index.php">Click here to go back.</a>
<form action="" method="post">
    <label for="uname">Username:</label>
    <input type="text" name="uname">
    <br>
    <label for="upass">Password:</label>
    <input type="text" name="upass">
    <br>
    <input type="submit" value="Submit">
</form>
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // create a new connection
        try {
            $link = new PDO('mysql:host=localhost;dbname=forum_db;charset=utf8', 'root', ''); 

            $link -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // obtaining the data from the form and database
            $username = $_POST['uname'];
            $password = $_POST['upass'];

            // prepare the statement
            $stmt = $link -> prepare("
                SELECT user_pass, user_id
                FROM users
                WHERE user_name = ?
                ");

            // execute the statement
            $stmt -> execute(array($username));;

            // check the username
            if ($stmt -> rowCount() == 0) {
                echo "Incorrect username or password.";
            }
            else {

                $row = $stmt -> fetch(PDO::FETCH_ASSOC);
                $hash = $row['user_pass'];
                $user_id = $row['user_id'];

                // verifying password
                if (!password_verify($password, $hash)) {
                    echo "Incorrect username or password.";
                }
                else {
                    // start the session
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user'] = $username;
                    header("location: home.php");
                }
            }
            // could be done with an if branch, testing the rowCount AND the verify password bool

            $link -> null;
            
        } catch (PDOException $e) {
            echo get_class($e) . 
            " thrown within the exception handler. Message: " 
            . $e -> getMessage() . 
            "on line " .
            $e -> getLine();
        }
    }
?>
<?php include 'footer.php' ?>