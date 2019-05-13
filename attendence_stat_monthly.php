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
                $part_number = returnPartNumberById($id);
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
                include_once "./inc/display_attendence.php";
                if(isset($_GET['login']) && ($_GET['login']=='error')) {
                    $status_msg_code = '5000';
                }
                if(isset($_POST['att_log_submit'])){
                    $date = $_POST['att_date'];
                } else {
                    $date = date("Y-m").'-01';
                }
                include_once "./inc/attendence_stat_menu.php";
            ?>
            <?php
                if(!empty($status_msg_code)) {
                    echo displayAlert($status_msg_code);
                    $status_msg_code = '';
                }
            ?>
            <form class="attendence_log_month_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                <table style="border:0px;">
                    <tr>
                        <td>파트 : </td><td><?php echo returnPartName($part_number) ?></td>
                    </tr>
                    <tr>
                        <td>출석월 : </td><td><input type="date" name="att_date" value="<?php echo $date; ?>"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" name="att_log_submit" class="w3-button w3-green">월간 출석 조회</button>
                        </td>
                    </tr>
                </table>
            </form>
            <?php
                if(!empty($part_number)) {
                    echo displayAttendenceMonthlyForm($part_number, $date);
                }
            ?>
        </div>
        
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>
