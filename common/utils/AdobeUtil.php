<?php

namespace common\utils;

use common\components\aliyuncs\Aliyun;
use Yii;
use yii\web\HttpException;
use ZipArchive;

/**
 * 解压打包好的cc zip 文件,查找 adobeID，返回skin.js路径
 */
class AdobeUtil
{

    public static function analyse($filepath)
    {
        // 新建 zip
        $route = self::mkdir();
        $zip = new ZipArchive();
        $adobeId = "";
        if ($zip->open($filepath) === true) {
            $mcw = $zip->extractTo($route);
            $zip->close();
            if ($mcw) {
                // do something ...
                $adobeId = self::getAdobeId("$route/skin.html");
            }
        }
        return $adobeId;
    }

    /**
     * 获取 skin 路径，先解压，再坐解压文件夹获取skin.js路径
     * cc 文件zip 会售中解压到 unzip/{uuid} 文件里
     *     
     * @param type $zipPath 
     */
    public static function getSkinPath($zipPath, $absolute = true)
    {
        if (pathinfo($zipPath, PATHINFO_EXTENSION) == 'zip') {
            $unzipPath = Yii::$app->params['webuploader']['unzipPath'];
            $filename = pathinfo($zipPath, PATHINFO_FILENAME);
            $skinPath = "{$unzipPath}{$filename}/skin.js";
            return $absolute ? Aliyun::absolutePath($skinPath) : $skinPath;
        }

        return "";
    }

    /**
     * 获取 adobeId
     */
    public static function getAdobeId($htmlpath)
    {
        $filename = $htmlpath;
        $handle = fopen($filename, "r"); //读取二进制文件时，需要将第二个参数设置成'rb'
        //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
        $contents = fread($handle, filesize($filename));
        $matches = [];
        preg_match('/AdobeAn\.getComposition\(\".*?\"\)/', $contents, $matches);
        fclose($handle);
        if (count($matches) > 0) {
            return substr($matches[0], -34, 32);
        } else {
            return "";
        }
    }

    /**
     * 创建目录
     * 
     * @throws HttpException
     */
    private static function mkdir()
    {
        $tempDir = 'upload/temp';
        $fileDir = uniqid();
        $path = "$tempDir/$fileDir";
        if (!file_exists($path)) {
            if (!(@mkdir($path, 0777, true))) {
                throw new HttpException(500, '创建目录失败');
            }
        }
        return $path;
    }

}
