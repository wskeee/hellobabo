<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\utils;

/**
 * Description of StringUtil
 *
 * @author Kiwi°
 */
class StringUtil
{

    //put your code here
    /**
     * 补全文件路径
     * @param srting $path 路径
     * @param string|array $withStr 指定的字符，默认['http://', 'https://', '/']
     * @param srting $appendStr 补全的字符，默认‘/’
     * @return srting
     */
    public static function completeFilePath($path, $withStr = '', $appendStr = '/')
    {
        //如果$withStr为空的，默认['http://', 'https://', '/']
        if (empty($withStr)) {
            $withStr = ['http://', 'https://', '/'];
        }

        //如果$withStr不是数组，默认转为数组
        if (!is_array($withStr)) {
            $withStr = [$withStr];
        }
        //如果参数path为空，默认为空字符串
        if ($path == null) {
            $path = '';
        }
        //判断指定的字符串是否存在，若不存在则补全
        $isAppendStr = false;
        foreach ($withStr as $str) {
            if (stripos($path, "$str") !== 0) {
                $isAppendStr = true;
            } else {
                $isAppendStr = false;
                break;
            }
        }
        return $isAppendStr ? $appendStr . $path : $path;
    }

    /**
     * 检查手机有效性，
     * 手机必须满足以下规定：
     * 1、必须为11位数字
     * 2、第一位数字必须为1
     * 3、第二位数字必须在345678中的其中一个
     * @param type $phone
     */
    public static function checkPhoneValid($phone)
    {
        return preg_match('/^[1][345678][0-9]{9}$/', $phone);
    }

    /**
     * 获取文件的后缀名
     * @param string $file
     * @return string
     */
    public static function getFileExtensionName($file)
    {
        $extName = '';
        //获取文件的后缀名
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        //判断文件后缀名的最后一个字符是否是大写
        if (preg_match('/^[A-Z]+$/', substr($ext, -1))) {
            //判断后缀名最后一个字符是否为大写X
            if (substr($ext, -1) == 'X') {
                //在后缀名最后一个字符是大写X的情况下替换为小写
                $extName = str_replace(substr($extName, -1), 'x', $extName);
            }
            //判断后缀名最后一个字符是否为小写x
        } else if (substr($ext, -1) == 'x') {
            $extName = $ext;
        } else {
            $extName = $ext . 'x';
        }

        return $extName;
    }

    /**
     * 数字转为大写中文
     * @param integer $num
     * @return string
     */
    public static function toUpcaseChinese($num)
    {
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        $num = round($num, 2);
        $num = $num * 100;
        if (strlen($num) > 11) {
            return "数据太长，没有这么大的钱吧，检查下";
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                $n = substr($num, strlen($num) - 1, 1);
            } else {
                $n = $num % 10;
            }
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            $num = $num / 10;
            $num = (int)$num;
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            $m = substr($c, $j, 6);
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j - 3;
                $slen = $slen - 3;
            }
            $j = $j + 3;
        }
        if (substr($c, strlen($c) - 3, 3) == '零') {
            $c = substr($c, 0, strlen($c) - 3);
        }
        if (empty($c)) {
            return "零元整";
        } else {
            return $c . "整";
        }

    }

    /**
     * 生成16位随机码
     * @param array $codes 开始字符数组
     * @param interger $start_year 开始年份
     * @return string
     */
    public static function getRandomSN($codes = null, $start_year = 2019)
    {
        $yCode = $codes ? $codes : array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - $start_year] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * 创建随机字段串
     * @param int $num 长度
     * @param bool $only_num 是否指定为数字
     * @return string
     */
    public static function getSimpleRandomStr($num, $only_num = false)
    {
        $strTlp = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $numTlp = '0123456789';
        $tlp = $only_num ? $numTlp : $strTlp;
        $str = '';
        $tlp_count = strlen($tlp);
        for ($i = 0; $i < $num; $i++) {
            $str .= substr($tlp, rand(0, $tlp_count), 1);
        }
        return $str;
    }

    /**
     * 生成不重复随机字符串
     * @param int $num 长度
     * @param int $offset 起始位置 0
     * @return false|string
     */
    public static function getRandomStr($num = 16, $offset = 8)
    {
        return substr(md5(time() . rand(1, 1000000)), 0, $num);
    }

    /**
     * 字符串大小转字节
     * @param string $size
     * @return interger
     */
    public static function StringSizeToBytes($size)
    {
        //把所有字符转换为小写
        $size = strtolower($size);
        //执行一个正则表达式的搜索和替换
        $unit = preg_replace('/[^a-z]/', '', $size);
        //截取单位前的数值
        $value = substr($size, 0, strripos($size, $unit));
        //定义一个单位数组
        $units = ['b' => 0, 'kb' => 1, 'mb' => 2, 'gb' => 3, 'tb' => 4];
        // 获取指数值
        $exponent = isset($units[$unit]) ? $units[$unit] : 0;
        //函数返回 1024 的几次方
        return intval($value * pow(1024, $exponent));
    }

    /**
     * 脱敏
     * 如手机中间数据显示数字
     *
     * @param string $str
     * @param string $replace
     *
     *
     */
    public static function str_hide($str, $replace = '*')
    {
        /*
        var_dump(str_hide("王磊"));
        var_dump(str_hide("何阳超"));
        var_dump(str_hide("5558888"));
        var_dump(str_hide("0205558888"));
        var_dump(str_hide("15915762146"));
        var_dump(str_hide("440883198508163"));
        var_dump(str_hide("440883198508163912"));
        */

        $rules = [
            // 规格 = [前面保留长度，中间隐藏长度，后面保留长茺]
            2 => [0, 1, 1], // 2个字
            3 => [1, 1, 1], // 3个字
            4 => [1, 2, 1], // 4个字
            7 => [2, 3, 2], // 7位坐机 5558888
            10 => [3, 3, 4], // 10位坐机 0205558888
            11 => [3, 4, 4], // 11手机 15915762146
            //15 => [6, 5, 4], // 15位身份证 440883198508163
            //18 => [6, 8, 4], // 18位身份证 440883198508163912
            15 => [1, 13, 1], // 15位身份证 440883198508163
            18 => [1, 16, 1], // 18位身份证 440883198508163912
        ];
        // 都不合适隐中间 3/1
        $len = mb_strlen($str);
        $rule = isset($rules[$len]) ? $rules[$len] : [floor($len / 3) - 1, floor($len / 3), floor($len / 3)];
        $hide_num = $rule[1];//min(4, $rule[1]);
        //$hide_str = str_pad($replace, $hide_num, $replace);
        //$pattern = "/(\w{" . $rule[0] . "})\w{" . $rule[1] . "}(\w{" . $rule[2] . "})/";
        //$replacement = "\$1{$hide_str}\$2";
        //$str = preg_replace($pattern, $replacement, $str);
        $str = mb_substr($str,0,$rule[0]).str_pad("",$hide_num,"*").mb_substr($str,-$rule[2],$rule[2]);

        return $str;
    }
}
