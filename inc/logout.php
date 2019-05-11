<?php

    if(!isset($_SESSION['u_id'])) {
        session_start();
    }

    if(isset($_POST['submit'])) {
        session_unset();
        session_destroy();
        header("Location: ../index.php");
    }

?>