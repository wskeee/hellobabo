<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\utils;

use Yii;

/**
 * Description of DateUtil
 *
 * @author Administrator
 */
class DateUtil
{

    /**
     * 
     * @param date $date 指定日期
     * @return array(start=>起始日期,end=>结束日期)
     */
    public static function getWeekSE($date, $offset = 0)
    {
        //$date=date('Y-m-d');  //当前日期
        $first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $w = date('w', strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $now_start = date('Y-m-d', strtotime("$date -" . ($w ? $w - $first : 6) . ' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $now_end = date('Y-m-d', strtotime("$now_start +7 days"));  //本周结束日期
        if ($offset != 0) {
            $off_start = $offset * 7;
            $off_end = $offset * 7 + 7;
            $now_start = date('Y-m-d', strtotime("$now_start $off_start days"));  //上周开始日期
            $now_end = date('Y-m-d', strtotime("$now_start $off_end days"));  //上周结束日期
        }

        return [
            'start' => $now_start,
            'end' => $now_end,
        ];
    }

    /**
     * 
     * @param date $date 指定日期
     * @return array(start=>起始日期,end=>结束日期)
     */
    public static function getMonthSE($date, $offset = 0)
    {
        $y = date("Y", strtotime($date));
        $m = date("m", strtotime($date));
        $d = '01';
        //$m = sprintf("%02d", intval($m));
        //$y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);
        $now_start = date("Y-m-d", strtotime($y . $m . $d));
        $now_end = date('Y-m-d', strtotime(date('Y-m-d', strtotime("$now_start +1 month -1 day"))));

        if ($offset != 0) {
            $date = $y . $m . $d;
            $y = date('Y', strtotime("$date $offset month"));
            $m = date('m', strtotime("$date $offset month"));
            $now_start = date("Y-m-d", strtotime($y . $m . $d));
            $now_end = date('Y-m-d', strtotime(date('Y-m-d', strtotime("$now_start +1 month -1 day"))));
        }

        return [
            'start' => $now_start,
            'end' => $now_end,
        ];
    }

    /**
     * 数字转换成时间格式
     * @param integer $value
     * @param string $format
     * @param boolean $default [true(hh:mm:ss), false(mm:ss)]
     */
    public static function intToTime($value, $format = ':', $default = false)
    {
        $h = floor($value / 3600);
        $i = floor($value % 3600 / 60);
        $s = floor($value % 60);

        return ($default ? self::zeor($h) . $format : null) . self::zeor($i) . $format . self::zeor($s);
    }

    /**
     * 字符转int
     * 
     * @param string $strTime     12:20:21
     * @return int 长度
     */
    public static function timeToInt($strTime)
    {
        if (!is_numeric($strTime)) {
            if (strpos($strTime, ":")) {
                $times = explode(":", $strTime);
            } else if (strpos($strTime, '：')) {
                $times = explode(":", $strTime);
            } else {
                return 0;
            }
            $h = (int) $times[0];
            $m = (int) $times[1];
            $s = count($times) == 3 ? (int) $times[2] : 0;
            return $h * 3600 + $m * 60 + $s;
        }

        return 0;
    }

    /**
     * 小于9自动在数字前添加0
     * @param int $value
     */
    public static function zeor($value)
    {
        return $value > 9 ? "$value" : "0$value";
    }

    /**
     * @author injection(injection.mail@gmail.com)
     * @var date1日期1
     * @var date2 日期2
     * @var tags 年月日之间的分隔符标记,默认为'-' 
     * @return 相差的月份数量
     * @example:
      $date1 = "2003-08-11";
      $date2 = "2008-11-06";
      $monthNum = getMonthNum( $date1 , $date2 );
      echo $monthNum;
     */
    public static function getMonthNum($date1, $date2, $tags = '-')
    {
        $date1 = explode($tags, $date1);
        $date2 = explode($tags, $date2);
        return abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
    }

    /**
     * 获取当前时间为当前月的第几周
     * 
     * @param int $when     当前时间搓
     * @return type
     */
    public static function getWeekOfMonth($when = null)
    {
        if ($when === null)
            $when = time();
        $week = date('W', $when); // note that ISO weeks start on Monday
        $firstWeekOfMonth = date('W', strtotime(date('Y-m-01', $when)));
        return 1 + ($week < $firstWeekOfMonth ? $week : $week - $firstWeekOfMonth);
    }

    /**
     * 获取当前月所有周
     * 
     * @param string $month         指定月 eg:201906
     * @return array [weekNum => start - end]
     */
    public static function getWeeksOfMonth($month = null)
    {
        if ($month == null) {
            $month = date('Ym', time());
        }

        // 首日
        $monthFirstDay = date('Y-m-d', strtotime("first day of $month"));
        // 尾日
        //$monthLastDay = strtotime("last day of $month");
        // 月天数
        $monthDayNum = date('t', strtotime($month));

        $weeks = [];

        for ($i = 0; $i < $monthDayNum; $i++) {
            $time = strtotime("$monthFirstDay +$i days");
            $weekNum = self::getWeekOfMonth($time);
            if (!isset($weeks[$weekNum])) {
                $weeks[$weekNum] = [$time];
            } else {
                $weeks[$weekNum] [] = $time;
            }
        }

        foreach ($weeks as $weekNum => &$weekData) {
            $start = date('Y-m-d', reset($weekData));
            $end = date('Y-m-d', end($weekData));
            $weekData = "$start - $end";
        }

        return $weeks;
    }

}
