<div class="w3-bar w3-blue">
    <?php  if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'master')) { ?>
        <a href="operator_att_stat_day.php" class="w3-bar-item w3-button w3-mobile">(파트) 일별 출석통계</a>
    <?php } ?>
    <?php  if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'editor') || ($_SESSION['u_id'] == 'master')) { ?>
        <a href="operator_att_stat_year_all_part.php" class="w3-bar-item w3-button w3-mobile">(파트) 월별 출석통계</a>
    <?php } ?>
    <?php  if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'master')) { ?>
        <a href="operator_attendence_log_monthly.php" class="w3-bar-item w3-button w3-mobile">(대원) 월별 출석통계</a>
        <a href="operator_att_stat_year.php" class="w3-bar-item w3-button w3-mobile">(대원) 일년 출석통계</a>
    <?php } ?>
    <?php  if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'accounting') || ($_SESSION['u_id'] == 'master')) { ?>
        <a href="operator_soli_att_stat.php" class="w3-bar-item w3-button w3-mobile">솔리스트 출석률</a>
    <?php } ?>
    <?php  if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'editor') || ($_SESSION['u_id'] == 'master')) { ?>
        <a href="operator_100_att_rate.php" class="w3-bar-item w3-button w3-mobile">개근 대원</a>
    <?php } ?>
    <?php  if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'master')) { ?>
        <a href="operator_report_stat.php" class="w3-bar-item w3-button w3-mobile">월말보고서 출석통계</a>
    <?php } ?>
    <?php  if(($_SESSION['u_id'] == 'admin') || ($_SESSION['u_id'] == 'operator') || ($_SESSION['u_id'] == 'accounting') || ($_SESSION['u_id'] == 'master')) { ?>
        <a href="operator_100_att_rate_year.php" class="w3-bar-item w3-button w3-mobile">결석일수 조회</a>
    <?php } ?>
</div>