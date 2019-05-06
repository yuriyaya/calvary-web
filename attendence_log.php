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
                }
                //load attendence log for each part
                if(isset($_SESSION['u_id'])) {
                    $part_id = $_SESSION['u_id'];
                    switch($part_id) {
                        case 'sopa':
                            include_once './attendence_log_sopa.php';
                            break;
                        default:
                            break;
                    }
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
