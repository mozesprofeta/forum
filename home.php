<?php 
    session_start();
    if (!$_SESSION['user']) {
        header("location: index.php");
    }
    include 'header.php';
?>
<h1>Home</h1>
<?php
    
    try {

        $link = new PDO('mysql:host=localhost;dbname=forum_db;charset=utf8;', 'root', '');

        $link -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $link -> query("
            SELECT
                categories.cat_id,
                categories.cat_name,
                categories.cat_description,
                topics.topic_subject,
                topics.topic_id
            FROM 
                categories
            LEFT JOIN
                topics   
            ON
                categories.cat_id = topics.topic_cat
            AND
                topics.topic_id = (
                    SELECT 
                        MAX(topics.topic_id)
                    FROM
                        topics
                    WHERE
                        categories.cat_id = topics.topic_cat
                )
            GROUP BY
                categories.cat_id
        ");

        $count = $stmt -> columnCount();

        echo '
            <table style="width: 80%; border: 1px solid black;">
        ';
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
                echo '
                     <tr style="border: 1px solid black;">
                        <td style="text-align: center; border: 1px solid black;">
                            <a href="category.php?id='.$row['cat_id'].'">'.$row['cat_name'].'</a>
                            <br>'
                            .$row['cat_description'].'
                        </td>
                        <td style="text-align: center; border: 1px solid black;">
                            <a href="topic.php?id='.$row['topic_id'].'"">'.$row['topic_subject'].'
                        </td>
                     </tr>
                ';
            }
        echo '
            </table>
        ';

        $link -> null;

    } catch (Exception $e) {
        echo get_class($e) . 
        " thrown within the exception handler. Message: " 
        . $e -> getMessage() . 
        "on line " .
        $e -> getLine();
    }
?>
<?php include 'footer.php'; ?>