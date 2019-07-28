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

                        if(isset($_POST['att_stat_day_search'])) {
                            $att_stat_day = $_POST['att_stat_day'];

                            include './inc/dbconn.php';

                            $result_html = ''; //to be implemented

                            $result_html = '<table class="w3-table-all w3-hoverable"><tr><th>파트</th><th>현황</th></tr>';
                            $total_att = 0;

                            for($part_num=1; $part_num<8; $part_num++) {
                                $query = "SELECT sum(attend_value) FROM ".getAttDBName($part_num)." WHERE date='".$att_stat_day."';";
                                // echo $query.'<br>';
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                $result_html = $result_html.'<tr><td style="width:150px">'.returnPartName($part_num).'</td>';
                                $att_date = 0;
                                while($row = $stmt->fetch()) {
                                    $att_date = (int)($row['sum(attend_value)']/10);
                                }
                                if($att_date == 0) {
                                    $result_html = $result_html.'<td>미입력</td></tr>';
                                } else {
                                    $result_html = $result_html.'<td>'.$att_date.'</td></tr>';
                                    $total_att = $total_att + $att_date;
                                }
                            }

                            $result_html = $result_html.'<tr><td>합계</td><td>'.$total_att.'</td></tr>';
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
            <div class="operator_att_stat_day">
                <form class="operator_att_stat_day_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>조회 일자 : </td><td><input type="date" name="att_stat_day" value="<?php if(empty($att_stat_day)){echo date("Y-m-d");} else {echo $att_stat_day;} ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="att_stat_day_search" class="w3-button w3-green">조회</button>
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
