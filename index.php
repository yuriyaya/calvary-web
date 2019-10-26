<!DOCTYPE html>
<html>
    <title>영락교회 갈보리 찬양대</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                if(isset($_GET['login']) && ($_GET['login']=='error')) {
                    $status_msg_code = '5000';
                }
                if(!empty($status_msg_code)) {
                    echo displayAlert($status_msg_code);
                    $status_msg_code = '';
                } else {
                    echo '<div class="w3-panel w3-yellow w3-topbar w3-bottombar w3-border-amber w3-container w3-center"><h3>영락교회 갈보리 찬양대</h3></div>';
                    echo "<a href='mailto:yncalvary@gmail.com' class='w3-button w3-blue'>관리자 문의</a><br>";
                    echo '<div class="w3-container w3-right" style="font-size:8px">v0.6.20191026.Yuls</div>';
                }
            ?>
        </div>
        
        <!-- !END PAGE CONTENT! -->

        <script type="text/javascript" src="./js/menu.js"></script>

    </body>
</html>
