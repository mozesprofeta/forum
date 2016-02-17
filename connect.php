<?php
    try {
        $link = new PDO('mysql:host=localhost;dbname=forum_db;charset=utf8', 'root', '');
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
?>