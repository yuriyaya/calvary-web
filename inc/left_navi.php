<?php
    if(!isset($_SESSION)) {
        session_start();
    }
?>

<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
<div class="w3-container w3-row">
    <?php
        if(isset($_SESSION['u_id'])) {
            echo $_SESSION['u_id'].' 로그인 되었습니다.';
    ?>
            <form class="w3-container" action="./inc/logout.php" method="POST">
                <button type="submit" name="submit" class="w3-button w3-green w3-small">로그아웃</button>
            </form>
    <?php
        } else {
            echo '<button onclick="document.getElementById(\'id01\').style.display=\'block\'" class="w3-button w3-green w3-large">로그인</button>';
        }
    ?>
</div>
<hr>
<div class="w3-container">
    <h5>메뉴</h5>
</div>
<div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  메뉴 닫기</a>
    <a href="./index.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-home"></i>  홈페이지</a>
    <a href="./attendence_log.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  출석입력</a>
    <a href="./attendence_stat.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-eye fa-fw"></i>  출석현황</a>
    <a href="./operator.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bar-chart"></i>  출석통계</a>
    <a href="./admin.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog"></i>  관리자</a><br><br>
</div>
</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>