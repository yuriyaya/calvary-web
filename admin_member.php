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
                        include_once "./inc/add_member.php";
            ?>
            <!-- !ALERT! -->
            <?php
                if(!empty($status_msg_code)) {
                    echo displayAlert($status_msg_code);
                    $status_msg_code = '';
                }
            ?>
            <?php if(!empty($additional_info)){echo $additional_info;} ?>
            <div class="add_member">
                <form class="add_member_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>일련번호 : </td><td><input type="text" name="member_id" value="<?php if(!empty($member_id)){echo $member_id;} ?>"></td>
                        </tr>
                        <tr>
                            <td>이름 : </td><td><input type="text" name="member_name" value="<?php if(!empty($member_name)){echo $member_name;} ?>"></td>
                        </tr>
                        <tr>
                            <td>파트 : </td>
                            <td>
                                <select class="w3-select w3-border" id="part" name="member_part">
                                    <option value="0" <?php if(empty($member_part)){echo "selected";} ?> disabled>파트를 선택하세요</option>  
                                    <option value="1" <?php if(!empty($member_part) && ($member_part==1)){echo "selected";} ?>>소프라노A</option>
                                    <option value="2" <?php if(!empty($member_part) && ($member_part==2)){echo "selected";} ?>>소프라노B</option>
                                    <option value="3" <?php if(!empty($member_part) && ($member_part==3)){echo "selected";} ?>>소프라노B+</option>
                                    <option value="4" <?php if(!empty($member_part) && ($member_part==4)){echo "selected";} ?>>알토A</option>
                                    <option value="5" <?php if(!empty($member_part) && ($member_part==5)){echo "selected";} ?>>알토B</option>
                                    <option value="6" <?php if(!empty($member_part) && ($member_part==6)){echo "selected";} ?>>테너</option>
                                    <option value="7" <?php if(!empty($member_part) && ($member_part==7)){echo "selected";} ?>>베이스</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td><td><button type="submit" name="member_search" class="w3-button w3-green">조회</button></td>
                        </tr>
                        <tr>
                            <td>등록일 : </td><td><input type="date" name="member_join_date" value="<?php if(empty($member_join_date)){echo date("Y-m-d");} else {echo $member_join_date;} ?>"></td>
                        </tr>
                        <tr>
                            <td>직분 : </td>
                            <td>
                                <select class="w3-select w3-border" id="staff" name="member_staff">
                                    <option value="0" <?php if(empty($member_staff)){echo "selected";} ?> disabled>직분을 선택하세요</option>  
                                    <option value="1" <?php if(!empty($member_staff) && ($member_staff==1)){echo "selected";} ?>>성도</option>
                                    <option value="2" <?php if(!empty($member_staff) && ($member_staff==2)){echo "selected";} ?>>집사</option>
                                    <option value="3" <?php if(!empty($member_staff) && ($member_staff==3)){echo "selected";} ?>>안수집사</option>
                                    <option value="4" <?php if(!empty($member_staff) && ($member_staff==4)){echo "selected";} ?>>권사</option>
                                    <option value="5" <?php if(!empty($member_staff) && ($member_staff==5)){echo "selected";} ?>>장로</option>
                                    <option value="6" <?php if(!empty($member_staff) && ($member_staff==6)){echo "selected";} ?>>전도사</option>
                                    <option value="7" <?php if(!empty($member_staff) && ($member_staff==7)){echo "selected";} ?>>목사</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>파트장 : </td>
                            <td>
                                <select class="w3-select w3-border" id="calvary_staff" name="calvary_staff">
                                    <option value="0" <?php if(empty($calvary_staff)){echo "selected";} ?> disabled>파트장 여부 선택</option>  
                                    <option value="1" <?php if(!empty($calvary_staff) && ($calvary_staff==1)){echo "selected";} ?>>대원</option>
                                    <option value="2" <?php if(!empty($calvary_staff) && ($calvary_staff==2)){echo "selected";} ?>>파트장</option>
                                    <option value="3" <?php if(!empty($calvary_staff) && ($calvary_staff==3)){echo "selected";} ?>>부파트장</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" name="member_register" class="w3-button w3-green">등록</button>
                                <button type="submit" name="member_update" class="w3-button w3-green">수정</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
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
