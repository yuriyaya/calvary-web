<!DOCTYPE html>
<html>
    <title>영락교회 갈보리 찬양대</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/operator.css">
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
                    if(($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'accounting')) {
                        include_once "./inc/operator_menu_member.php";
                        include_once "./inc/func_global.php";
                        if(isset($_POST['member_cont_submit'])) {
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
                    } else {
                        $status_msg_code = '5003';
                    }
                } else {
                    $status_msg_code = '5001';
                }
            ?>
            <?php
                if(!empty($status_msg_code)) {
                    echo displayAlert($status_msg_code);
                    $status_msg_code = '';
                }
            ?>
            <div class="member_continue_year">
                <form class="member_continue_year_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
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
                            <td>조회기준일 : </td><td><input type="date" name="att_date" value="<?php if(empty($date)){echo date("Y-m-d");} else {echo $date;} ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="member_cont_submit" class="w3-button w3-green">조회</button>
                            </td>
                        </tr>
                    </table>
                </form>
                <?php
                    if($success) {
                        echo ' - 근속일 계산은 정대원 승급일 기준이며 제적 후 재입대시에는 재입대일 기준으로 계산됩니다.<br>';
                        echo ' - 정대원 인정 이후 휴식할 경우 근속기간에서 제외 됩니다. 즉, 근속일이 10년이 되었더라도 휴식일이 길면 10년 근속자로 인정하지 않습니다.<br>';
                        echo ' - 근속의 경우 현재 정대원만 표시되며, 조회 기준일 당시 신입/휴식 상태일 경우 근속자로 인정되지 않습니다.<br>';
                        echo displayMemberContinueYear($part_number, $date);
                    }
                    $success = false;
                ?>
            </div>
        </div>
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>