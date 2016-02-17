<?php 
    session_start();
    include 'header.php';
?>
<form action="" method="post">
    <label for="cat_name">Category name:</label>
    <input type="text" name="cat_name">
    <br>
    <label for="cat_desc">Category description:</label>
    <br>
    <input type="text" name="cat_desc" style="width: 270px; height: 100px;">
    <br>
    <input type="submit" value="Add category">
</form>
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        try {

            $link = new PDO('mysql:host=localhost;dbname=forum_db;charset=utf8;', 'root', '');

            $link -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $cat_name = $_POST['cat_name'];
            $cat_desc = $_POST['cat_desc'];

            $stmt = $link -> prepare("
                INSERT INTO categories (cat_name, cat_description)
                VALUES (:cat_name, :cat_description)
            ");

            $stmt -> execute(array(
                "cat_name" => $cat_name,
                "cat_description" => $cat_desc
                )
            );

            $link = null;

        } catch (PDOException $e) {

            echo get_class($e) . " thrown within the exception handler. Message: " . $e -> getMessage() .
            "on line" . $e -> getLine();

        }
    }
?>
<?php include 'footer.php' ?>;