<?php
    $today = date('Y-m-d');
    $today_description = '';
    //check today is a attendence date
    include_once './inc/dbconn.php';
    $query = "SELECT * FROM attendence_date WHERE att_date='".$today."'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $num_of_rows = $stmt->rowCount();
    // echo "num_of_rows: ".$num_of_rows."<br>";
    if($num_of_rows > 0) {
        $today_description = $today.' 출석 입력일입니다.';
    } else {
        $today_description = $today.' 오늘은 출석 입력일이 아닙니다.';
    }
?>
<div class="w3-panel w3-blue-grey">
    <h3>소프라노A</h3>
    <p><?php echo $today_description ?></p>
</div>
<?php
    if($num_of_rows == 0){
        //do not display log form
        exit();
    } else {
?>
<form class="attendence_log_sopa" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<?php
        $check_form = '<td><input class="w3-check" type="checkbox"></td>';
        $status_option = '<td><select class="w3-select w3-border" id="state" name="member_state">
            <option value="0" selected disabled>선택</option>
            <option value="1">정대원</option>
            <option value="6">휴식</option>
            <option value="7">제적</option>
        </select></td>';
        //display attendence check form
        $att_check_form='<table class="w3-table-all w3-hoverable" id="att_table" style="width:500px"><tr><th>이름</th><th>상태</th><th>출석</th><th>4월</th><th>3월</th><th>2월</th><th>상태변경</th>';
        $att_check_form=$att_check_form.'<tr><td>이름1<input type="hidden" name="id_10001" value="10001"></td><td></td>'.$check_form.'<td style="background-color:Tomato">100%</td><td>100%</td><td>100%</td>'.$status_option;
        $att_check_form=$att_check_form.'<tr><td>이름2<input type="hidden" name="id_10002" value="10002"></td><td></td>'.$check_form.'<td>100%</td><td>100%</td><td>100%</td>'.$status_option;
        $att_check_form=$att_check_form.'</table>';
        
        echo $att_check_form;
    }
?>
<button type="submit" name="submit" class="w3-button w3-green" id="submit_button">출석 입력</button>
</form>
<?php
    if(isset($_POST['submit'])) {
        echo '입력 버튼';
        unset($_POST['submit']);
    }
?>

