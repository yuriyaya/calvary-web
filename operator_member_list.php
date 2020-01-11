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
            
                    if(($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'accounting')) {
                        include_once "./inc/operator_menu_member.php";
                        include_once "./inc/display_attendence.php";

                        if(isset($_POST['att_member_list'])) {

                            include './inc/dbconn.php';
                            if(isset($_POST['cond_id'])) {
                                $cond_id = true;
                            } else {
                                $cond_id = false;
                            }
                            if(isset($_POST['cond_state'])) {
                                $cond_state = true;
                            } else {
                                $cond_state = false;
                            }
                            if(isset($_POST['cond_church_staff'])) {
                                $cond_church_staff = true;
                            } else {
                                $cond_church_staff = false;
                            }
                            if(isset($_POST['disp_normal'])) {
                                $disp_normal = 5;
                            } else {
                                $disp_normal = 0;
                            }
                            if(isset($_POST['disp_pause'])) {
                                $disp_pause = 1;
                            } else {
                                $disp_pause = 0;
                            }
                            if(isset($_POST['disp_out'])) {
                                $disp_out = 3;
                            } else {
                                $disp_out = 0;
                            }

                            $result_html = '<table class="w3-table-all w3-hoverable">';
                            $result_html = $result_html.'<tr><th>파트</th>';
                            if($cond_id) {
                                $result_html = $result_html.'<th>ID</th>';
                            }
                            $result_html = $result_html.'<th>이름</th>';
                            if($cond_state) {
                                $result_html = $result_html.'<th>상태</th>';
                            }
                            if($cond_church_staff) {
                                $result_html = $result_html.'<th>직분</th>';
                            }
                            $result_html = $result_html.'</tr>';

                            $cond_str = ' ';
                            $cond_sum = $disp_normal + $disp_pause + $disp_out;
                            switch($cond_sum) {
                                case 9:
                                    $cond_str = ' WHERE last_state < 10 ';
                                    break;
                                case 5:
                                    $cond_str = ' WHERE last_state < 6 ';
                                    break;
                                case 1:
                                    $cond_str = ' WHERE last_state = 6 ';
                                    break;
                                case 3:
                                    $cond_str = ' WHERE last_state > 6 ';
                                    break;
                                case 6:
                                    $cond_str = ' WHERE last_state < 7 ';
                                    break;
                                case 8:
                                    $cond_str = ' WHERE last_state != 6 ';
                                    break;
                                case 4:
                                    $cond_str = ' WHERE last_state < 5 ';
                                    break;
                            }

                            $query = "SELECT * FROM member_info".$cond_str."ORDER BY part ASC, calvary_staff DESC, name ASC;";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $stmt->setFetchMode(PDO::FETCH_ASSOC);
                            while($row = $stmt->fetch()) {
                                $result_html = $result_html.'<tr><td style="width:150px">'.returnPartName($row['part']).'</td>';
                                if($cond_id) {
                                    $result_html = $result_html.'<td>'.$row['id'].'</td>';
                                }
                                $result_html = $result_html.'<td>'.$row['name'].'</td>';
                                if($cond_state) {
                                    $result_html = $result_html.'<td>'.getMemberStateString($row['last_state']).'</td>';
                                }
                                if($cond_church_staff) {
                                    $result_html = $result_html.'<td>'.returnChurchStaffName($row['church_staff']).'</td>';
                                }
                                $result_html = $result_html.'</tr>';
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
            <div class="operator_att_member_list">
                <form class="operator_att_member_list_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>출력 정보 선택 : </td>
                            <td>
                                <?php
                                    if(isset($_POST['cond_id'])) {
                                        $checked_id = ' checked';
                                    } else {
                                        $checked_id = '';
                                    }
                                    if(isset($_POST['cond_state'])) {
                                        $checked_state = ' checked';
                                    } else {
                                        $checked_state = '';
                                    }
                                    if(isset($_POST['cond_church_staff'])) {
                                        $checked_church_staff = ' checked';
                                    } else {
                                        $checked_church_staff = '';
                                    }
                                    echo '<input class="w3-check" type="checkbox" name="cond_id"'.$checked_id.'>ID';
                                    echo '<input class="w3-check" type="checkbox" name="cond_state"'.$checked_state.'>대원상태';
                                    echo '<input class="w3-check" type="checkbox" name="cond_church_staff"'.$checked_church_staff.'>직분';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>출력 조건 선택 : </td>
                            <td>
                                <?php
                                    if(isset($_POST['disp_normal'])) {
                                        $checked_normal = ' checked';
                                    } else {
                                        $checked_normal = '';
                                    }
                                    if(isset($_POST['disp_pause'])) {
                                        $checked_pause = ' checked';
                                    } else {
                                        $checked_pause = '';
                                    }
                                    if(isset($_POST['disp_out'])) {
                                        $checked_out = ' checked';
                                    } else {
                                        $checked_out = '';
                                    }
                                    echo '<input class="w3-check" type="checkbox" name="disp_normal"'.$checked_normal.'>출석대원';
                                    echo '<input class="w3-check" type="checkbox" name="disp_pause"'.$checked_pause.'>휴식대원';
                                    echo '<input class="w3-check" type="checkbox" name="disp_out"'.$checked_out.'>제적/은퇴/명예대원';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="att_member_list" class="w3-button w3-green">조회</button>
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
