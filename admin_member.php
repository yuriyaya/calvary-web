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
                        include_once "./inc/add_member.php";
            ?>
            
            <div class="w3-container w3-light-blue">대원 등록 및 수정</div>
            <!-- !ALERT! -->
            <?php
                if($error == 'member_input_empty') {
                    echo '<div class="w3-panel w3-red">';
                    echo '<h3>대원 추가 입력 에러</h3>';
                    echo '<p>대원 등록에 필요한 정보가 입력되지 않았습니다. 이름/파트/등록일/직분 항목 모두 입력해 주세요.</p>';
                    echo '</div>';
                    $error = '';
                } elseif ($error == 'member_register_success') {
                    echo '<div class="w3-panel w3-green">';
                    echo '<h3>대원 등록 성공</h3>';
                    echo '<p>대원이 성공적으로 등록되었습니다.</p>';
                    echo '</div>';
                    $error = '';
                }
            ?>

            <div class="add_member">
                <form class="add_member_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>이름 : </td><td><input type="text" name="member_name" value="<?php if(!empty($member_name)){echo $member_name;} ?>"></td>
                        </tr>
                        <tr>
                            <td>파트 : </td>
                            <td>
                                <select class="w3-select w3-border" id="part" name="member_part">
                                    <option value="" <?php if(empty($member_part)){echo "selected";} ?> disabled>파트를 선택하세요</option>  
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
                            <td>일련번호 : </td><td><input type="text" name="member_sn" value="<?php if(!empty($member_id)){echo $member_id;} ?>"></td>
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
                                    <option value="" <?php if(empty($member_staff)){echo "selected";} ?> disabled>직분을 선택하세요</option>  
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
                            <td></td>
                            <td>
                                <button type="submit" name="member_register" class="w3-button w3-green">등록</button>
                                <button type="submit" name="member_update" class="w3-button w3-green">수정</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="w3-container w3-light-blue">대원 상태 변경</div>
            <div class="w3-container w3-light-blue">대원 상태 변경 승인</div>
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
