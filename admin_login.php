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
                        //error
                        if(isset($_GET['register'])) {
                            $status_msg_code = $_GET['register'];
                            if(!empty($status_msg_code)) {
                                echo displayAlert($status_msg_code);
                                $status_msg_code = '';
                            }
                        }
            ?>
            <div class="add_user">
                <form class="add_user_form" action="./inc/register.php" method="POST">
                    <table style="border:0px;">
                        <tr>
                            <td>아이디 : </td><td><input type="text" name="user_id"></td>
                        </tr>
                        <tr>
                            <td>기존 비밀번호 : </td><td><input type="password" name="user_pw_before"></td>
                        </tr>
                        <tr>
                            <td>변경 비밀번호 : </td><td><input type="password" name="user_pw"></td>
                        </tr>
                        <tr>
                            <td></td><td><button type="submit" name="submit" class="w3-button w3-green">등록</button></td>
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
