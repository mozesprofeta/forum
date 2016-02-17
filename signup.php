<?php 
    session_start();
    include 'header.php'
?>
<a href="index.php">Click here to go back</a>
<form action="" method="post">
    <label for="uname">User name:</label>
    <input type="text" name="uname"/>
    <br>
    <label for="upass">Password:</label>
    <input type="text" name="upass"/>
    <br>
    <label for="upass_again">Password again:</label>
    <input type="text" name="upass_again"/>
    <br>
    <label for="uemail">E-mail:</label>
    <input type="text" name="uemail"/>
    <br>
    <input type="submit" value="Submit"/>
</form>
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include 'connect.php';

        // echo '<form action="" method="post">';
        //     echo '<label for="uname">User name:</label>';
        //     echo '<input type="text" name="uname"/>';
        //     echo '<label for="upass">Password:</label>';
        //     echo '<input type="text" name="upass"/>';
        //     echo '<label for="upass_again">Password again:</label>';
        //     echo '<input type="text" name="upass_again"/>';
        //     echo '<label for="uemail">E-mail:</label>';
        //     echo '<input type="text" name="uemail"/>';
        //     echo '<input type="submit" value="Submit"/>';
        // echo '</form>';

        $uname = $_POST['uname'];
        $upass = $_POST['upass'];
        $hash = password_hash($upass, PASSWORD_DEFAULT);
        $uemail = $_POST['uemail'];
        $ulevel = 0;

        $stmt = $link -> prepare("
            INSERT INTO users (user_name, user_pass, user_email, user_date, user_level)
            VALUES (:uname, :upass, :uemail, NOW(), :ulevel)
            ");

        $stmt -> execute(array(
                "uname" => $uname,
                "upass" => $hash,
                "uemail" => $uemail,
                "ulevel" => $ulevel
                )
            );

        $link = null;
    }
?>
<?php include 'footer.php'; ?>