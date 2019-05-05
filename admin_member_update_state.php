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
                        include_once "./inc/update_member_state.php";
            ?>

            <!-- !ALERT! -->
            <?php
                if(!empty($status_msg_code)) {
                    echo displayAlert($status_msg_code);
                    $status_msg_code = '';
                }
            ?>
            <?php if(!empty($additional_info)){echo $additional_info;} ?>
            <div class="update_member_state">
                <form class="update_member_state_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
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
                            <td>변경일 : </td><td><input type="date" name="state_update_date" value="<?php if(empty($state_update_date)){echo date("Y-m-d");} else {echo $state_update_date;} ?>"></td>
                        </tr>
                        <tr>
                            <td>상태 : </td>
                            <td>
                                <select class="w3-select w3-border" id="state" name="member_state">
                                    <option value="0" <?php if(empty($member_part)){echo "selected";} ?> disabled>상태를 선택하세요</option>
                                    <option value="1" <?php if(!empty($member_part) && ($member_part==1)){echo "selected";} ?>>일반</option>
                                    <option value="2" <?php if(!empty($member_part) && ($member_part==2)){echo "selected";} ?>>솔리스트</option>
                                    <option value="3" <?php if(!empty($member_part) && ($member_part==3)){echo "selected";} ?>>신입</option>
                                    <option value="4" <?php if(!empty($member_part) && ($member_part==4)){echo "selected";} ?>>임시</option>
                                    <option value="5" <?php if(!empty($member_part) && ($member_part==5)){echo "selected";} ?>>특별</option>
                                    <option value="6" <?php if(!empty($member_part) && ($member_part==6)){echo "selected";} ?>>휴식</option>
                                    <option value="7" <?php if(!empty($member_part) && ($member_part==7)){echo "selected";} ?>>제적</option>
                                    <option value="8" <?php if(!empty($member_part) && ($member_part==8)){echo "selected";} ?>>은퇴</option>
                                    <option value="9" <?php if(!empty($member_part) && ($member_part==9)){echo "selected";} ?>>명예</option>
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

        <script>
            // Get the Sidebar
            var mySidebar = document.getElementById("mySidebar");
            // Get the DIV with overlay effect
            var overlayBg = document.getElementById("myOverlay");
            // Toggle between showing and hiding the sidebar, and add overlay effect
            function w3_open() {
            if (mySidebar.style.display === 'block') {
                mySidebar.style.display = 'none';
                overlayBg.style.display = "none";
            } else {
                mySidebar.style.display = 'block';
                overlayBg.style.display = "block";
            }
            }
            // Close the sidebar with the close button
            function w3_close() {
                mySidebar.style.display = "none";
                overlayBg.style.display = "none";
            }

            function changeSelectedId(){
                var selectedLoginId = document.getElementById("login_id");
                var selectValue = selectedLoginId.options[selectedLoginId.selectedIndex].value;
                var selectText = selectedLoginId.options[selectedLoginId.selectedIndex].text;
                // alert(selectValue);
                // alert(selectText);
                document.getElementById("username").value = selectValue;
            }

        </script>

    </body>
</html>
