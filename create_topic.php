<?php 
    session_start();
    include 'header.php';

    try {

        $link = new PDO('mysql:host=localhost;dbname=forum_db;charset=utf8;', 'root', '');

        $link -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $link -> query("
            SELECT cat_id, cat_name
            FROM categories 
        ");
        
        echo '  <form action="" method="post">
                    <label for="topic_subject">Topic subject:</label>
                    <input type="text" name="topic_subject">
                    <br>
                    <label for="category">Category:</label>
                    <select name="category">';
                    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
                       echo '<option value="'.$row['cat_id'].'">'.$row['cat_name'].'</option>';
                    }
        echo '      </select>
                
                    <label for="message">Message:</label>
                    <input type="text" name="message">
                    
                    <input type="submit" value="Create topic">
                </form>';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $link -> beginTransaction();
                
            $stmt = $link -> prepare("
                INSERT INTO topics (
                    topic_subject,
                    topic_date,
                    topic_cat,
                    topic_by
                ) VALUES (
                    :topic_subject,
                    NOW(),
                    :topic_cat,
                    :topic_by
                )
            ");

            $topic_subject = $_POST['topic_subject'];
            $topic_cat = $_POST['category'];
            $topic_by = $_SESSION['user_id'];

            $stmt -> execute(
                array(
                    ":topic_subject" => $topic_subject,
                    ":topic_cat" => $topic_cat,
                    ":topic_by" => $topic_by,
                )
            );

            $topic_id = $link -> lastInsertId();

            $stmt = $link -> prepare("
                INSERT INTO posts (
                    post_content,
                    post_date,
                    post_topic,
                    post_by
                ) VALUES (
                    :post_content,
                    NOW(),
                    :post_topic,
                    :post_by
                )
            ");

            $post_content = $_POST['message'];
            $post_topic = $topic_id;
            $post_by = $_SESSION['user_id'];

            $stmt -> execute(
                array(
                    ":post_content" => $post_content,
                    ":post_topic" => $post_topic,
                    ":post_by" => $post_by
                )
            );

            $link -> commit();
        }      
    } catch (Exception $e) {

        $link -> rollBack();

        echo get_class($e) . 
        " thrown within the exception handler. Message: " 
        . $e -> getMessage() . 
        "on line" .
        $e -> getLine();
    }

    include 'footer.php'; 
?>