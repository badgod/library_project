<?php
function dateThaiFormat($date)
{
    $m = array('', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.',);
    $_d = explode('-', $date);
    $d = (int)$_d[2] . ' ' . $m[(int)$_d[1]] . ' ' . ($_d[0] + 543);
    return $d;
}

function dateThaiFormatFullMonth($date)
{
    $m = array('', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม',);
    $_d = explode('-', $date);
    $d = (int)$_d[2] . ' ' . $m[(int)$_d[1]] . ' ' . ($_d[0] + 543);
    return $d;
}

function convertDateToDatabese($date)
{
    $d = explode('/', $date);
    $y = (int)$d[2];
    $m = (int)$d[1];
    $d = (int)$d[0];
    return $y . '-' . $m . '-' . $d;
}
