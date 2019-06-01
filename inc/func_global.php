<?php

    function returnAlertPanel($color, $title, $desc){
        $ret_html="";

        $ret_html = $ret_html.'<div class="w3-panel w3-'.$color.'">';
        $ret_html = $ret_html.'<h3>'.$title.'</h3>';
        $ret_html = $ret_html.'<p>'.$desc.'</p>';
        $ret_html = $ret_html.'</div>';

        return $ret_html;
    }

    function displayAlert($input_code){
        $ret_str = '';
        $status_db_str = file_get_contents(__DIR__.'/../lib/status_msg.json');
        $status_db = json_decode($status_db_str, true);
        if(array_key_exists($input_code, $status_db)) {
            $ret_str = returnAlertPanel($status_db[$input_code]['level'], $status_db[$input_code]['title'].'('.$input_code.')', $status_db[$input_code]['description']);
        } else {
            $ret_str = returnAlertPanel('red', 'UNDEFINED ERROR('.$input_code.')', '관리자에게 문의 하세요');
        }
        return $ret_str;
    }

    function returnPartName($part_num) {
        $ret_str = '';

        switch($part_num){
            case 1:
                $ret_str = "소프라노A";
                break;
            case 2:
                $ret_str = "소프라노B";
                break;
            case 3:
                $ret_str = "소프라노B+";
                break;
            case 4:
                $ret_str = "알토A";
                break;
            case 5:
                $ret_str = "알토B";
                break;
            case 6:
                $ret_str = "테너";
                break;
            case 7:
                $ret_str = "베이스";
                break;
            default:
                break;
        }

        return $ret_str;
    }

    function returnChurchStaffName($church_staff_num) {
        $ret_str = '';

        switch($church_staff_num){
            case 1:
                $ret_str = "성도";
                break;
            case 2:
                $ret_str = "집사";
                break;
            case 3:
                $ret_str = "안수집사";
                break;
            case 4:
                $ret_str = "권사";
                break;
            case 5:
                $ret_str = "장로";
                break;
            case 6:
                $ret_str = "전도사";
                break;
            case 7:
                $ret_str = "목사";
                break;
            default:
                break;
        }

        return $ret_str;
    }

    function getMemberStateString($state_num){
        $ret_str = '';

        switch($state_num){
            case 1:
                $ret_str = "정대원";
                break;
            case 2:
                $ret_str = "솔리스트";
                break;
            case 3:
                $ret_str = "신입";
                break;
            case 4:
                $ret_str = "임시";
                break;
            case 5:
                $ret_str = "특별";
                break;
            case 6:
                $ret_str = "휴식";
                break;
            case 7:
                $ret_str = "제적";
                break;
            case 8:
                $ret_str = "은퇴";
                break;
            case 9:
                $ret_str = "명예";
                break;
            default:
                $ret_str = "";
                break;
        }

        return $ret_str;
    }

    function returnPartNumberById($part_id) {
        $ret_num = 0;

        switch($part_id){
            case 'sopa':
                $ret_num = 1;
                break;
            case 'sopb':
                $ret_num = 2;
                break;
            case 'sopbp':
                $ret_num = 3;
                break;
            case 'altoa':
                $ret_num = 4;
                break;
            case 'altob':
                $ret_num = 5;
                break;
            case 'tenor':
                $ret_num = 6;
                break;
            case 'bass':
                $ret_num = 7;
                break;
            default:
                break;
        }

        return $ret_num;
    }

    function getPartDBNameByMemberId($member_id) {
        $ret_db = '';

        $part_num = (int)($member_id/10000);
        switch($part_num){
            case 1:
                $ret_db = "attendence_sopa";
                break;
            case 2:
                $ret_db = "attendence_sopb";
                break;
            case 3:
                $ret_db = "attendence_sopbp";
                break;
            case 4:
                $ret_db = "attendence_altoa";
                break;
            case 5:
                $ret_db = "attendence_altob";
                break;
            case 6:
                $ret_db = "attendence_tenor";
                break;
            case 7:
                $ret_db = "attendence_bass";
                break;
            default:
                break;
        }

        return $ret_db;
    }

    function getMonthlyPartDBNameByPartNumber($part_number) {
        $ret_db = '';

        switch($part_number){
            case 1:
                $ret_db = "attendence_month_sopa";
                break;
            case 2:
                $ret_db = "attendence_month_sopb";
                break;
            case 3:
                $ret_db = "attendence_month_sopbp";
                break;
            case 4:
                $ret_db = "attendence_month_altoa";
                break;
            case 5:
                $ret_db = "attendence_month_altob";
                break;
            case 6:
                $ret_db = "attendence_month_tenor";
                break;
            case 7:
                $ret_db = "attendence_month_bass";
                break;
            default:
                break;
        }

        return $ret_db;
    }
?>