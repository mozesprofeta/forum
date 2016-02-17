<?php  
    session_start();
    include 'header.php';

    try {

        $link = new PDO('mysql:host=localhost;dbname=forum_db;charset=utf8;', 'root', '');

        $link -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $link -> prepare("
            SELECT
                posts.post_content,
                posts.post_date,
                users.user_name,
                users.user_id,
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
                posts.post_id = :id
        ");

        $post_id = $_GET['id'];

        $stmt -> execute(
            array(
                ":id" => $post_id
            )
        );

        $row = $stmt -> fetch(PDO::FETCH_ASSOC);

        $reply_topic = $row['topic_id'];
        $reply_by = $row['user_id'];

        echo '
        <table style="width: 80%; border: 1px solid black; text-align: center;">
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">
                    '.$row['user_name'].'<br>
                    '.$row['post_date'].'
                </td>
                <td style="border: 1px solid black;">
                    '.$row['post_content'].'
                </td>
            </tr>
        </table>
        <h1>Reply:</h1>
        <form action="" method="post">
            <label for="reply">Message:</label>
            <br>
            <input type="text" name="reply" style="width: 270px; height: 100px;">
            <br>
            <input type="submit" value="Sumbit">
        </form>
        ';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $stmt = $link -> prepare("
                INSERT INTO 
                    replies (
                        replies.reply_content,
                        replies.reply_date,
                        replies.reply_topic,
                        replies.reply_by,
                        replies.reply_to
                    )
                VALUES (
                    :reply_content,
                    NOW(),
                    :reply_topic,
                    :reply_by,
                    :reply_to
                )
            ");

            $reply_content = $_POST['reply'];

            $stmt -> execute(
                array(
                    ':reply_content' => $reply_content,
                    ':reply_topic' => $reply_topic,
                    ':reply_by' => $reply_by,
                    ':reply_to' => $post_id
                )
            );
            
        }
        
    } catch (PDOException $e) {
        
        echo get_class($e) . 
        " thrown within the exception handler. Message: " 
        . $e -> getMessage() . 
        "on line " .
        $e -> getLine();
    }


?>
<?php
    include 'footer.php';
?>