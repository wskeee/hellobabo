<?php

namespace common\utils;

/**
 * Description of PosterUtils
 *
 * @author Administrator
 */
class PosterUtils
{

    /**
     *  * 生成宣传海报
     *  * @param array  参数,包括图片和文字
     *  * @param string  $filename 生成海报文件名,不传此参数则不生成文件,直接输出图片
     *  * @return [type] [description]
     *   */
    static public function createPoster($config = array(), $filename = "")
    {
        //如果要看报什么错，可以先注释调这个header
        if (empty($filename)) header("content-type: image/png");
        $imageDefault = array(
            //'left' => 0,
            //'top' => 0,
            //'right' => 0,
            //'bottom' => 0,
            //'width' => 100,
            //'height' => 100,
            'opacity' => 100
        );
        $textDefault = array(
            'text' => '',
            'fontSize' => 32, //字号
            'fontColor' => '255,255,255', //字体颜色
            'angle' => 0,
        );
        $backgroundConfig = $config['background']; //海报最底层得背景
        //背景方法
        $backgroundInfo = @getimagesize($backgroundConfig['url']);
        $backgroundFun = 'imagecreatefrom' . image_type_to_extension($backgroundInfo[2], false);
        $background = $backgroundFun($backgroundConfig['url']);
        $backgroundWidth = $backgroundConfig['width'];//imagesx($background);  //背景宽度
        $backgroundHeight = $backgroundConfig['height'];//imagesy($background);  //背景高度
        $imageRes = imageCreatetruecolor($backgroundWidth, $backgroundHeight);
        $color = imagecolorallocate($imageRes, 0, 0, 0);
        imagefill($imageRes, 0, 0, $color);
        // imageColorTransparent($imageRes, $color);  //颜色透明
        imagecopyresampled($imageRes, $background, 0, 0, 0, 0, $backgroundWidth, $backgroundHeight, imagesx($background), imagesy($background));
        //处理了图片
        if (!empty($config['image'])) {
            foreach ($config['image'] as $key => $val) {
                $val = array_merge($imageDefault, $val);
                $info = @getimagesize($val['url']);
                $function = 'imagecreatefrom' . image_type_to_extension($info[2], false);
                if (isset($val['stream']) && $val['stream']) {   //如果传的是字符串图像流
                    $info = getimagesizefromstring($val['url']);
                    $function = 'imagecreatefromstring';
                    var_dump($info);
                    exit;
                }
                $res = $function($val['url']);
                $resWidth = $info[0];
                $resHeight = $info[1];
                empty($val['width']) && $val['width'] = $resWidth;
                empty($val['height']) && $val['height'] = $resHeight;
                //建立画板 ，缩放图片至指定尺寸
                //$canvas = imagecreatetruecolor($val['width'], $val['height']);
                //imagefill($canvas, 0, 0, $color);
                //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
                //imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'], $resWidth, $resHeight);
                $val = self::calculate($val, $backgroundWidth, $backgroundHeight);
                //放置图像
                imagecopyresampled($imageRes, $res, $val['left'], $val['top'], 0, 0, $val['width'], $val['height'], $resWidth, $resHeight); //左，上，右，下，宽度，高度，透明度
            }
        }
        //处理文字
        if (!empty($config['text'])) {
            foreach ($config['text'] as $key => $val) {
                $val = array_merge($textDefault, $val);
                list($R, $G, $B) = explode(',', $val['fontColor']);
                $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
                // 计算字体宽高
                $font_box = ImageTTFBBox($val['fontSize'], $val['angle'], $val['fontPath'], $val['text']);
                $val['width'] = $font_box[2] - $font_box[0];
                $val['height'] = $font_box[3] - $font_box[5];
                // 计算 left/top
                $val = self::calculate($val, $backgroundWidth, $backgroundHeight);
                // 把文字画到图片上
                imagettftext($imageRes, $val['fontSize'], $val['angle'], $val['left'], $val['top'], $fontColor, $val['fontPath'], $val['text']);
            }
        }
        //生成图片
        if (!empty($filename)) {
            $res = imagejpeg($imageRes, $filename, 100); //保存到本地
            imagedestroy($imageRes);
            if (!$res)
                return false;
            return $filename;
        } else {
            imagejpeg($imageRes);     //在浏览器上显示
            imagedestroy($imageRes);
        }
    }

    /**
     * 计算 left/top/left/bottom
     * @param array $val
     * @param int $targetWidth 相对宽度
     * @param int $targetHeight 相对高度
     * @return array
     */
    private static function calculate($val, $targetWidth, $targetHeight)
    {
        !isset($val['left']) && $val['left'] = isset($val['right']) ? $targetWidth - $val['right'] - $val['width'] : 0;
        !isset($val['right']) && $val['right'] = isset($val['left']) ? $targetWidth - $val['left'] - $val['width'] : 0;
        !isset($val['top']) && $val['top'] = isset($val['bottom']) ? $targetHeight - $val['bottom'] - $val['height'] : 0;
        !isset($val['bottom']) && $val['bottom'] = isset($val['top']) ? $targetHeight - $val['top'] - $val['height'] : 0;
        return $val;
    }
}
