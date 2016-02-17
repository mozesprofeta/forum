<?php 
    session_start();
    if (!$_SESSION['user']) {
        $user = "visitor";
    }
    else {
        $user = $_SESSION['user'];
    }
    include 'header.php';
?>
<?php include 'footer.php'; ?>