<?php

    session_start();

    $id=null;
    if(isset($_SESSION['u_id'])){
        $id=$_SESSION['u_id'];
    }

    if(isset($_GET['login']) && ($_GET['login']=='error')) {
        echo "로그인이 실패하였습니다. 비밀번호를 확인해 주세요.";
    } else {
        //redirect to attendence log page for each part
        switch($id) {
            case 'sopa':
                header("Location: ./attendence_log_part.php?part_num=1&id=0");
                break;
            default:
                echo "로그인 아이디를 찾을 수 없습니다";
                break;
        }
    }

?> 