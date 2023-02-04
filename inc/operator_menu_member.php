<div class="w3-bar w3-blue">
    <?php  if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'accounting') || ($_SESSION['u_id'] == 'master')) { ?>
        <a href="operator_member_stat_search.php" class="w3-bar-item w3-button w3-mobile">대원상태변경조회</a>
    <?php } ?>
    <?php  if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'master')) { ?>
        <a href="operator_newbie.php" class="w3-bar-item w3-button w3-mobile">신입대원현황</a>
        <a href="operator_pause.php" class="w3-bar-item w3-button w3-mobile">휴식대원현황</a>
    <?php } ?>
    <?php  if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'accounting') || ($_SESSION['u_id'] == 'master')) { ?>
        <a href="operator_member_continue.php" class="w3-bar-item w3-button w3-mobile">근속현황</a>
        <a href="operator_member_list.php" class="w3-bar-item w3-button w3-mobile">대원명단</a>
    <?php } ?>
</div>