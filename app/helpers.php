<?php

function format_number($number, $type)
{
    $number = intval($number);
    $type = $type[1];
    if ($type == 'JPY')
        return number_format($number);
    else {
        $formated_number = '';
        $split_numbers = explode('.', $number);
        if (count($split_numbers) > 1)
            $formated_number = number_format($number, 2);
        else
            $formated_number = number_format($number);
    }
    return $formated_number;
}

function format_phone($tel)
{
    $telLength = strlen($tel);
    $phone = '';
    if ($telLength == 10 && $tel != '') {
        $phone = preg_replace("/^(\d{3})(\d{4})(\d{4})$/", "$1-$2-$3", "0" . $tel);
    } else if ($telLength == 9 && $tel != '') {
        $phone = preg_replace("/^(\d{2})(\d{4})(\d{4})$/", "$1-$2-$3", "0" . $tel);
    }
    return $phone;
}

function convert_width($full_width)
{
    return str_replace('px', '', $full_width);
}
