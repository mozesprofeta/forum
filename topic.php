<?php
    session_start();
    include 'header.php';

    try {

        $link = new PDO('mysql:host=localhost;dbname=forum_db;charset=utf8;', 'root', '');

        $link -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $link -> prepare("
            SELECT 
                posts.post_id,
                posts.post_content,
                posts.post_date,
                users.user_name,
                topics.topic_id
            FROM
                posts
            LEFT JOIN
                users
            ON 
                users.user_id = posts.post_by
            LEFT JOIN
                topics
            ON
                topics.topic_id = posts.post_topic
            WHERE
                topics.topic_id = :id
        ");

        $reply_stmt = $link -> prepare("
            SELECT
                replies.reply_to
            FROM
                replies
            WHERE
                replies.reply_to
        ")

        $id = $_GET['id'];

        $stmt -> execute(
            array(
                ':id' => $id
            )
        );

        $result =  $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $topic_id = $result[0]['topic_id'];

        echo '<table style="width: 80%; border: 1px solid black; text-align: center;">';
        foreach ($result as $row) {
            
            echo '
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;">
                        '.$row['user_name'].'
                        <br>
                        '.$row['post_date'].'
                    </td>
                    <td style="border: 1px solid black;">
                        '.$row['post_content'].'
                        <br>
                        <a href="reply.php?id='.$row['post_id'].'">Reply</a>
                        <br>
            ';
            if (condition) {
                echo '  Reply to:<a href="">'.$row['user_name'].'('.$row['post_id'].')</a>';
            }
            echo '
                    </td>
                </tr>
            ';
        }
        echo '</table>';
        
    } catch (PDOException $e) {
        
        echo get_class($e) . 
        " thrown within the exception handler. Message: " 
        . $e -> getMessage() . 
        "on line " .
        $e -> getLine();
    }
?>
<form action="" method="post">
    <label for="message">Message:</label>
    <br>
    <input type="text" name="message" style="width: 270px; height: 100px;">
    <br>
    <input type="submit" value="Submit">
</form>
<?php

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        try {

            $stmt = $link -> prepare("
                INSERT INTO
                    posts (
                        post_content,
                        post_date,
                        post_topic,
                        post_by
                    )
                VALUES (
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

            echo '<meta http-equiv="refresh" content="0">';

        } catch (PDOException $e) {

            echo get_class($e) . 
            " thrown within the exception handler. Message: " 
            . $e -> getMessage() . 
            "on line " .
            $e -> getLine();
        }  
    }
        
    include 'footer.php';
?>