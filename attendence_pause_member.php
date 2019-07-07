<?php
    include_once "./inc/func_global.php";
    if(!isset($_SESSION)) {
        session_start();
    }
    $id=null;
    if(isset($_SESSION['u_id'])){
        $id=$_SESSION['u_id'];
        
    }

    if(isset($_GET['login']) && ($_GET['login']=='error')) {
        $status_msg_code = '5000';
    } else if (!isset($_GET['part_num'])){
        //redirect to attendence log page for each part
        switch($id) {
            case 'sopa':
            case 'sopb':
            case 'sopbp':
            case 'altoa':
            case 'altob':
            case 'tenor':
            case 'bass':
                header("Location: ./attendence_pause_member.php?part_num=".returnPartNumberById($id));
                break;
            default:
                $status_msg_code = '5002';
                break;
        }
    }
?>
<!DOCTYPE html>
<html>
    <title>영락교회 갈보리 찬양대</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/attendence_log.css">
    <style>
        * {font-family: "Raleway", sans-serif}
    </style>
    <body class="w3-light-grey">
        <!-- Top container -->
        <?php include_once "./inc/top_menu.php"; ?>

        <!-- Sidebar/menu -->
        <?php include_once "./inc/left_navi.php" ?>

        <!-- log in modal form -->
        <?php include_once "./inc/login_form.php" ?>

        <!-- !PAGE CONTENT! -->
        <div class="w3-main" style="margin-left:300px;margin-top:43px;">
            <?php
                include_once "./inc/func_global.php"; 
                if(isset($_GET['login']) && ($_GET['login']=='error')) {
                    $status_msg_code = '5000';
                }
                
                if(isset($_GET['part_num'])){
                    $part_number = $_GET['part_num'];
                } else {
                    $part_number = 0;
                }
            ?>
            <?php
                if(!empty($status_msg_code)) {
                    echo displayAlert($status_msg_code);
                    $status_msg_code = '';
                }
            ?>
            <?php
                if(!empty($part_number)) {
                    include_once "./inc/attendence_stat_menu.php";
                    include_once "./inc/search_pause_member.php";
                    echo displayPauseMember($part_number);
                }
            ?>
        </div>
        
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>
