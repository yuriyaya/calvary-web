<!DOCTYPE html>
<html>
    <title>영락교회 갈보리 찬양대</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/operator.css">
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
                if(isset($_SESSION['u_id'])) {
                    if(($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'master')) {
                        include_once "./inc/operator_menu.php";
                        include_once "./inc/display_attendence.php";
                        include_once "./inc/monthly_report_att_stat.php";
                    } else {
                        $status_msg_code = '5003';
                    }
                } else {
                    $status_msg_code = '5001';
                }
                //ALERT!
                if(!empty($status_msg_code)) {
                    echo displayAlert($status_msg_code);
                    $status_msg_code = '';
                }
            ?>
            <div class="operator_report_stat">
                <form class="operator_report_stat_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>출석월 : </td><td><input type="date" name="report_att_stat_date" value="<?php if(empty($att_stat_date)){echo date("Y-m-d", strtotime("-1 months"));} else {echo $att_stat_date;} ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="operator_report_stat_search" class="w3-button w3-green">조회</button>
                            </td>
                        </tr>
                    </table>
                    출석월의 월말보고서 출석통계를 출력합니다. (출석월의 일은 무시됨)<br>
                </form>
                <?php if(!empty($str_html)){echo $str_html;} ?>
            </div>

        </div>
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>
