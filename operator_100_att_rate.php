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
            
                    if(($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'editor')) {
                        include_once "./inc/operator_menu.php";
                        include_once "./inc/display_attendence.php";

                        if(isset($_POST['att_100_member_search'])) {
                            $att_100_date = $_POST['att_100_date'];

                            $date_start = date('Y-m', strtotime($att_100_date)).'-01';
                            $date_end = date('Y-m-t', strtotime($att_100_date));

                            include './inc/dbconn.php';

                            $result_html = '<table class="w3-table-all w3-hoverable"><tr><th>파트</th><th>이름</th></tr>';

                            for($part_num=1; $part_num<8; $part_num++) {
                                $query = "SELECT mi.name FROM ".getMonthlyPartDBNameByPartNumber($part_num)." AS att LEFT JOIN member_info AS mi ON att.id=mi.id WHERE att.date>='".$date_start."' AND att.date<='".$date_end."' AND att.att_month_rate=100;";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                $result_html = $result_html.'<tr><td style="width:150px">'.returnPartName($part_num).'</td>';
                                $record_cnt = 0;
                                $name_list = '';
                                while($row = $stmt->fetch()) {
                                    if($record_cnt == 0) {
                                        $name_list = $row['name'];
                                        $record_cnt = $record_cnt + 1;
                                    } else {
                                        $name_list = $name_list.', '.$row['name'];
                                    }
                                }
                                $result_html = $result_html.'<td>'.$name_list.'</td></tr>';
                                $name_list = '';
                            }

                            $result_html = $result_html.'</table>';
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
            <div class="operator_att_100_member">
                <form class="operator_att_100_member_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>출석월 : </td><td><input type="date" name="att_100_date" value="<?php if(empty($att_100_date)){echo date("Y-m-d", strtotime("-1 months"));} else {echo $att_100_date;} ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="att_100_member_search" class="w3-button w3-green">조회</button>
                            </td>
                        </tr>
                    </table>
                    출석월의 개근대원을 파트별로 출력합니다.(일은 무시)<br>
                </form>
                <?php if(!empty($result_html)){echo $result_html;} ?>
            </div>
        </div>
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>
