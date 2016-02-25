<?php

class TravelHelper extends AppHelper {

    function formatTimePeriod($timeArrive, $minutesStay) {
        $string = '';
        if (!empty($timeArrive) && $timeArrive != '00:00:00') {
            $string .= $timeArrive;
            if ($minutesStay > 0) {
                $time = explode(':', $timeArrive);
                if ($hours = intval($minutesStay / 60)) {
                    $time[0] += $hours;
                    $time[1] += $minutesStay % 60;
                } else {
                    $time[1] += $minutesStay;
                }
                if ($time[1] >= 60) {
                    $time[0] += 1;
                    $time[1] = $time[1] - 60;
                }
                if ($time[0] > 24) {
                    $time[0] -= 24;
                }
                $time[0] = str_pad($time[0], 2, '0', STR_PAD_LEFT);
                $time[1] = str_pad($time[1], 2, '0', STR_PAD_LEFT);
                $string .= ' - ' . implode(':', $time);
            }
        } elseif ($minutesStay > 0) {
            if ($hours = intval($minutesStay / 60)) {
                $string .= $hours . ' 小時 ' . ($minutesStay % 60) . ' 分鐘';
            } else {
                $string .= $minutesStay . ' 分鐘';
            }
        }
        return $string;
    }

    /**
     * 將一般座標轉換為度分秒格式
     * @param float $number
     */
    function convertLongLat($number) {
        $output = '';
        if ($number < 0) {
            $output .= '-';
            $number = abs($number);
        }
        $val = intval($number);
        $output .= $val . '° ';
        $number = ($number - $val) * 60;
        $val = intval($number);
        $output .= $val . '\' ';
        $number = ($number - $val) * 60;
        $val = round($number, 4);
        $output .= $val . '" ';
        return $output;
    }

    function getValue($data, $field) {
        if (!empty($data[$field . '_zh_tw'])) {
            return $data[$field . '_zh_tw'];
        } else if (!empty($data[$field . '_en_us'])) {
            return $data[$field . '_en_us'];
        }
        return $data[$field];
    }

}