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
            
                    if(($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'admin')) {
                        include_once "./inc/operator_menu.php";
                        include_once "./inc/display_attendence.php";
                        if(isset($_POST['report_member_state_search'])) {
                            $member_state_date = $_POST['member_state_date'];

                            $date_start = date('Y-m', strtotime($member_state_date)).'-01';
                            $date_end = date('Y-m-t', strtotime($member_state_date));

                            include './inc/dbconn.php';
                            $query = "SELECT mi.part, mi.name, ms.state_update_date, ms.state FROM member_state AS ms RIGHT JOIN member_info AS mi ON ms.id=mi.id WHERE state_update_date>='".$date_start."' AND state_update_date<='".$date_end."' AND (ms.state=3 OR ms.state=7) ORDER BY state_update_date DESC;";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $stmt->setFetchMode(PDO::FETCH_ASSOC);
                            $result_html = '<table class="w3-table-all w3-hoverable"><tr><th>파트</th><th>이름</th><th>변동일</th><th>상태</th><tr>';
                            while($row = $stmt->fetch()) {
                                $result_html = $result_html.'<tr><td>'.returnPartName($row['part']).'</td><td>'.$row['name'].'</td><td>'.$row['state_update_date'].'</td><td>'.getMemberStateString($row['state']).'</td></tr>';
                            }
                        }
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
            <div class="operator_report_member_state">
                <form class="operator_report_member_state_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>조회 일자 : </td><td><input type="date" name="member_state_date" value="<?php if(empty($member_state_date)){echo date("Y-m-d", strtotime("-1 months"));} else {echo $member_state_date;} ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="report_member_state_search" class="w3-button w3-green">조회</button>
                            </td>
                        </tr>
                    </table>
                </form>
                <?php if(!empty($result_html)){echo $result_html;} ?>
            </div>
        </div>
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>
