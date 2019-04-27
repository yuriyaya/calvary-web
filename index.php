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
        <?php include "./php_lib/top_menu.php"; ?>

        <!-- Sidebar/menu -->
        <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
        <div class="w3-container w3-row">
            <button onclick="document.getElementById('id01').style.display='block'" class="w3-button w3-green w3-large">로그인</button>
        </div>
        <hr>
        <div class="w3-container">
            <h5>메뉴</h5>
        </div>
        <div class="w3-bar-block">
            <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  메뉴 닫기</a>
            <a href="./index.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-home"></i>  홈페이지</a>
            <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  출석입력</a>
            <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-eye fa-fw"></i>  출석현황</a>
            <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bar-chart"></i>  출석통계</a>
            <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog"></i>  관리자</a><br><br>
        </div>
        </nav>
        <!-- Overlay effect when opening sidebar on small screens -->
        <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

        <!-- log in modal form -->
        <div id="id01" class="w3-modal">
            <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">
                <div class="w3-center"><br>
                    <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                </div>

                <form class="w3-container" action="/action_page.php">
                    <div class="w3-section">
                        <label><b>아이디</b></label>
                        <select id="login_id" name="login_id" onchange="changeSelectedId()">
                            <option value="" selected disabled>파트를 선택</option>  
                            <option value="sopa">소프라노A</option>
                            <option value="sopb">소프라노B</option>
                            <option value="sopbp">소프라노B+</option>
                            <option value="altoa">알토A</option>
                            <option value="altob">알토B</option>
                            <option value="tenner">테너</option>
                            <option value="bass">베이스</option>
                            <option value="operator">임원단</option>
                            <option value="admin">Admin</option>
                        </select>
                        <input class="w3-input w3-border w3-margin-bottom" type="text" id="username" placeholder="아이디를 입력하세요" name="usrname" required>
                        <label><b>비밀번호</b></label>
                        <input class="w3-input w3-border" type="password" placeholder="비밀번호를 입력하세요" name="psw" required>
                        <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit">Login</button>
                    </div>
                </form>
                <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                    <button onclick="document.getElementById('id01').style.display='none'" type="button" class="w3-button w3-red">Cancel</button>
                </div>
            </div>
        </div>

        <!-- !PAGE CONTENT! -->
        <div class="w3-main" style="margin-left:300px;margin-top:43px;">

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
