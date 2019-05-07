<!DOCTYPE html>
<html>
    <title>영락교회 갈보리 찬양대</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                if(isset($_GET['login']) && ($_GET['login']=='error')) {
                    echo "로그인이 실패하였습니다. 비밀번호를 확인해 주세요.";
                } else {
                    include_once "./inc/display_attendence.php";
                    include_once "./inc/func_global.php";
                    $part_number = $_GET['part_num'];
                    $id = $_GET['id'];
            ?>
            <div class="w3-panel w3-blue-grey">
                <h3><?php echo returnPartName($part_number); ?></h3>
                <p><?php echo displayTitleDescription($id); ?></p>
            </div>
            <form class="att_log_part_search" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                <table style="border:0px">
                    <tr>
                        <td>출석일 :</td>
                        <td><?php echo displayAttSearchOpt($id); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><button type="submit" name="search_submit" class="w3-button w3-green" id="submit_search_button">조회</button></td>
                    <tr>
                </table>
            </form>
            <form class="att_log_part" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <?php
                    echo displayAttendenceForm($part_number, $id);
                }
            ?>
            <button type="submit" name="submit" class="w3-button w3-green" id="submit_button">출석 입력</button>
            </form>
        </div>
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>
