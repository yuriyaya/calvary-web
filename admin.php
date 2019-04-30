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
                if(isset($_SESSION['u_id'])) {
                    if($_SESSION['u_id'] == 'admin') {
            ?>
            <div class="add_user">
                <form class="add_user_form" action="./inc/register.php" method="POST">
                    아이디 : <input type="text" name="user_id" plasceholder="ID"><br>
                    비밀번호 : <input type="password" name="user_pw" plasceholder="Password"><br>
                    <button type="submit" name="submit">등록</button>
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
