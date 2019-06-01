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

                            $search_result_info = $att_date_cnt;
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
                            <td>조회 기간 : </td><td><input type="date" name="att_date_start" value="<?php if(empty($att_date_start)){echo date('Y-m').'-01';} else {echo $att_date_start;} ?>"></td>
                            <td> ~ <input type="date" name="att_date_end" value="<?php if(empty($att_date_end)){echo date('Y-m-t');} else {echo $att_date_end;} ?>"></td>
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
