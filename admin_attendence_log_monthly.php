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
    } else {
        //redirect to attendence log page for each part
        switch($id) {
            case 'admin':
                break;
            case 'master':
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
    <link rel="stylesheet" href="./css/admin.css">
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
                $success = false;
                if(isset($_SESSION['u_id'])) {
                    if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'master')) {
                        include_once "./inc/admin_menu.php";
                        include_once "./inc/func_global.php";
                        if(isset($_POST['att_log_submit'])) {
                            if(isset($_POST['part_num'])) {
                                $part_number = $_POST['part_num'];
                            } else {
                                $part_number = 0;
                            }
                            $date = $_POST['att_date'];
                            include './inc/display_attendence.php';
                            // echo $part_number.'/'.$date.'<br>';
                            if(empty($part_number)) {
                                $status_msg_code = '9060';
                            } else{
                                $success = true;
                            }
                        }
                    }
                }
            ?>
            <?php
                if(!empty($status_msg_code)) {
                    echo displayAlert($status_msg_code);
                    $status_msg_code = '';
                }
            ?>
            <div class="add_attendence_log_month">
                <form class="add_attendence_log_month_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>파트 : </td><td><select class="w3-select w3-border" id="part" name="part_num">
                                    <option value="0" <?php if(empty($part_number)){echo "selected";} ?> disabled>파트를 선택하세요</option>  
                                    <option value="1" <?php if(!empty($part_number) && ($part_number==1)){echo "selected";} ?>>소프라노A</option>
                                    <option value="2" <?php if(!empty($part_number) && ($part_number==2)){echo "selected";} ?>>소프라노B</option>
                                    <option value="3" <?php if(!empty($part_number) && ($part_number==3)){echo "selected";} ?>>소프라노B+</option>
                                    <option value="4" <?php if(!empty($part_number) && ($part_number==4)){echo "selected";} ?>>알토A</option>
                                    <option value="5" <?php if(!empty($part_number) && ($part_number==5)){echo "selected";} ?>>알토B</option>
                                    <option value="6" <?php if(!empty($part_number) && ($part_number==6)){echo "selected";} ?>>테너</option>
                                    <option value="7" <?php if(!empty($part_number) && ($part_number==7)){echo "selected";} ?>>베이스</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td>출석월 : </td><td><input type="date" name="att_date" value="<?php if(empty($date)){echo date("Y-m").'-01';} else {echo $date;} ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="att_log_submit" class="w3-button w3-green">월간 조회</button>
                            </td>
                        </tr>
                    </table>
                </form>
                <?php
                    if($success) {
                ?>
                <form class="att_log_part" action="./inc/attendence_log_part_month_update.php" method="POST">
                    <?php
                        echo displayAttendenceMonthlyForm($part_number, $date);
                    ?>
                    <input type="hidden" name="att_part" value="<?php echo $part_number ?>">
                    <input type="hidden" name="att_date" value="<?php echo $date ?>">
                    <button type="submit" name="monthly_submit" class="w3-button w3-green" id="submit_button">출석 마감</button>
                </form>
                <?php
                    }
                    $success = false;
                ?>
            </div>
        </div>
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>