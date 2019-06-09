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
                        
                        if(isset($_POST['search_member_stat_update_submit'])) {
                            $date_start = $_POST['search_date_start'];
                            $date_end = $_POST['search_date_end'];
                            // echo $date_start.'<br>';
                            // echo $date_end.'<br>';

                            include './inc/dbconn.php';
                            $query = "SELECT ms.id, mi.name, ms.state_update_date, ms.state FROM member_state AS ms LEFT JOIN member_info AS mi ON ms.id=mi.id WHERE state_update_date>='".$date_start."' AND state_update_date<='".$date_end."' ORDER BY id ASC, state_update_date DESC;";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $stmt->setFetchMode(PDO::FETCH_ASSOC);

                            $search_result_info = '<table class="w3-table-all w3-hoverable"><tr><th>파트</th><th>이름</th><th>변동일</th><th>변동사항</th></tr>';

                            while($row = $stmt->fetch()) {
                                $search_result_info = $search_result_info.'<tr><td>';
                                $part_num = (int)($row['id']/10000);
                                $search_result_info = $search_result_info.returnPartName($part_num).'</td><td>';
                                $search_result_info = $search_result_info.$row['name'].'</td><td>';
                                $search_result_info = $search_result_info.$row['state_update_date'].'</td><td>';
                                $search_result_info = $search_result_info.getMemberStateString($row['state']).'</td></tr>';
                            }

                            $search_result_info = $search_result_info.'</table>';

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
            <!-- !ALERT! -->
            <div class="search_member_stat_update">
                <form class="search_member_stat_update_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>조회 기간 : </td><td><input type="date" name="search_date_start" value="<?php if(empty($date_start)){echo date('Y-m').'-01';} else {echo $date_start;} ?>"></td>
                            <td> ~ <input type="date" name="search_date_end" value="<?php if(empty($date_end)){echo date('Y-m-t');} else {echo $date_end;} ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="search_member_stat_update_submit" class="w3-button w3-green">조회</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <?php if(!empty($search_result_info)){echo $search_result_info;} ?>

        </div>
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>
