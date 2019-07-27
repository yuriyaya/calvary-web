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
                        
                        if(isset($_POST['soli_att_submit'])) {
                            $date_start = $_POST['att_date_start'];
                            $date_end = $_POST['att_date_end'];

                            include './inc/dbconn.php';
                            $query = "SELECT count(att_date) FROM attendence_date WHERE att_date>='".$date_start."' AND att_date<='".$date_end."'";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $stmt->setFetchMode(PDO::FETCH_ASSOC);
                            $att_date_cnt = 1;
                            while($row = $stmt->fetch()) {
                                $att_date_cnt = $row['count(att_date)'];
                            }

                            $query = "SELECT id, name FROM member_info WHERE last_state=2 ORDER BY id ASC;";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $stmt->setFetchMode(PDO::FETCH_ASSOC);
                            $member_id = 0;
                            $search_result_info = '<table class="w3-table-all w3-hoverable"><tr><th>파트</th><th>이름</th><th>출석률</th><th>상세내역</th></tr>';
                            while($row = $stmt->fetch()) {

                                $member_id = $row['id'];
                                $member_name = $row['name'];
                                
                                $attendence_db_name = getPartDBNameByMemberId($member_id);

                                $query_att = "SELECT sum(attend_value) FROM ".$attendence_db_name." WHERE date>='".$date_start."' AND date <='".$date_end."' AND id=:in1 GROUP BY id;";

                                $stmt_att = $conn->prepare($query_att);
                                $stmt_att->bindParam(':in1', $in1);
                                $in1 = $member_id;
                                $stmt_att->execute();
                                $num_of_rows = $stmt_att->rowCount();


                                if($num_of_rows == 1) {
                                    $stmt_att->setFetchMode(PDO::FETCH_ASSOC);
                                    while($row = $stmt_att->fetch()) {
                                        $part_num = (int)($member_id/10000);
                                        $att_date = (int)($row['sum(attend_value)']/10);
                                        $att_rate = round(($att_date/$att_date_cnt)*100);
                                        $search_result_info = $search_result_info.'<tr><td>'.returnPartName($part_num).'</td><td>'.$member_name.'</td><td>'.$att_rate.'%</td><td>'.$att_date.'/'.$att_date_cnt.'</td></tr>';
                                    }
                                }
                                
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
            <div class="search_soli_att">
                <form class="search_soli_att_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>조회 기간 : </td><td><input type="date" name="att_date_start" value="<?php if(empty($date_start)){echo date('Y-m').'-01';} else {echo $date_start;} ?>"></td>
                            <td> ~ <input type="date" name="att_date_end" value="<?php if(empty($date_end)){echo date('Y-m-t');} else {echo $date_end;} ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="soli_att_submit" class="w3-button w3-green">조회</button>
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
