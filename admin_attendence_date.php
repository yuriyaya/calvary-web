<!DOCTYPE html>
<html>
    <title>영락교회 갈보리 찬양대</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/admin.css">
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
                if(isset($_SESSION['u_id'])) {
                    if($_SESSION['u_id'] == 'admin') {
                        include_once "./inc/admin_menu.php";
                        include_once "./inc/func_global.php";
                        include_once "./inc/add_attendence_date.php";
            ?>
            <!-- !ALERT! -->
            <?php
                if(!empty($status_msg_code) && $status_msg_code<9045) {
                    echo displayAlert($status_msg_code);
                    $status_msg_code = '';
                }
            ?>
            <div class="add_attendence_date">
                <form class="add_attendence_date_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>출석일 : </td><td><input type="date" name="att_date" value="<?php if(empty($att_date)){echo date("Y-m-d");} else {echo $att_date;} ?>"></td>
                        </tr>
                        <tr>
                            <td>설명 : </td><td><input type="text" name="att_date_desc" value="<?php if(!empty($att_date_desc)){echo $att_date_desc;} ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="att_date_add_submit" class="w3-button w3-green">추가</button>
                                <button type="submit" name="att_date_delete_submit" class="w3-button w3-green">삭제</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="w3-panel w3-light-blue">
                <p>출결날짜 조회</p>
            </div>
            <?php
                if(!empty($status_msg_code) && ($status_msg_code>9044)) {
                    echo displayAlert($status_msg_code);
                    $status_msg_code = '';
                }
            ?>
            <div class="search_attendence_date">
                <form class="search_attendence_date_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>조회 기간 : </td><td><input type="date" name="att_date_start" value="<?php if(empty($att_date_start)){echo date("Y-m-d", strtotime("-1 months"));} else {echo $att_date_start;} ?>"></td>
                            <td> ~ <input type="date" name="att_date_end" value="<?php if(empty($att_date_end)){echo date("Y-m-d", strtotime("+1 months"));} else {echo $att_date_end;} ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="att_date_search_submit" class="w3-button w3-green">조회</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <?php if(!empty($search_result_info)){echo $search_result_info;} ?>
            <?php
                    } else {
                        echo "admin이 아닙니다.";
                    }
                } else {
                    echo "admin 로그인이 필요합니다.";
                }
            ?>

        </div>
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>
