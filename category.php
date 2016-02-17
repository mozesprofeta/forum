<?php
    session_start();
    include 'header.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        try {

            $link = new PDO('mysql:host=localhost;dbname=forum_db;charset=utf8;', 'root', '');

            $link -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $id = $_GET['id'];

            $stmt = $link -> prepare("
                SELECT 
                    topics.topic_id,
                    topics.topic_subject,
                    topic_date,
                    categories.cat_name
                FROM 
                    topics
                LEFT JOIN
                    categories
                ON
                    topics.topic_cat = categories.cat_id
                WHERE
                    topics.topic_cat = :id
            ");

            $stmt -> execute(
                array(
                    ":id" => $id
                )
            );

            // Option #1
            // to iterate over the array twice use foreach,
            // because using while loop you cannot reset the internal pointer
            // at least not in MySql...
            
            $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);

            echo '
                <h1>Topics in '.$result[0]['cat_name'].'</h1>
            ';

            echo '<table style="width: 80%; border: 1px solid black; text-align: center;">';
            foreach ($result as $row) {
                    
                echo '
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">
                            <a href="topic.php?id='.$row['topic_id'].'">'.$row['topic_subject'].'</a>
                        <td/>   
                        <td style="border: 1px solid black;">
                            '.$row['topic_date'].'
                        </td>
                    </tr>
                ';
            }
            echo '</table>';

            // Option #2
            // should be using PDO::FETCH_ORI_ABS, numValue after you have set the 
            // PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL attribute but this doesnt work with MySql
        

            // $row = $stmt -> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, 0);
            // echo '<h1>Topics in '.$row['cat_name'].'</h1>';
                
            // echo '<table style = "width: 80%; border: 1px solid black;">';
            // while ($row = $stmt -> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, 2)) {

            //     echo '
            //         <tr style = "border: 1px solid black;">
            //             <td style = "border: 1px solid black;">
            //                 '.$row['topic_subject'].'
            //             </td>
            //             <td style = "border: 1px solid black;">
            //                 '.$row['topic_date'].'
            //             </td>
            //         </tr>
            //     ';
            // }
            // echo '</table>';

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