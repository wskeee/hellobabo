<?php

namespace apiend\modules\v1\actions\system;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\components\aliyuncs\Aliyun;
use common\models\goods\Goods;
use common\models\order\OrderGoods;
use common\utils\PosterUtils;
use jianyan\easywechat\Wechat;
use Yii;

/**
 * 生成海报
 *
 * @author Administrator
 */
class Poster extends BaseAction
{

    public function run()
    {
        $type = $this->getSecretParam('type');
        $id = $this->getSecretParam('id');
        $rec_id = $this->getSecretParam('rec_id', Yii::$app->user->id);

        $savePath = Yii::$app->params['webuploader']['savePath'];
        /* @var $wechat Wechat  */
        /*
          $wechat = \Yii::$app->wechat;
          $response = $wechat->miniProgram->app_code->getUnlimit('1_5_16', ['page' => 'pages/goods/goods']);
          if ($response instanceof StreamResponse) {
          $filename = $response->saveAs('/web/app_code/', 'appcode.png');
          } */
        // 二维码路径
        $code_object = Yii::getAlias('@app/web/imgs/app_code.png');
        // 绘本封面或者用户封面
        $modelClass = $type == 1 ? Goods::class : OrderGoods::class;
        $model = $modelClass::findOne(['id' => $id]);
        $model_img = $type == 1 ? $model->cover_url : $model->user_cover_url;       
        
        $config = [
            // 用户昵称
            'text' => [
                [
                    'text' => Yii::$app->user->identity->nickname,
                    'left' => 182,
                    'top' => 105,
                    'fontPath' => Yii::getAlias('@apiend/web/css/Xxht.ttf'), //字体文件
                    'fontSize' => 18, //字号
                    'fontColor' => '255,0,0', //字体颜色
                    'angle' => 0,],
            ],
            'image' => [
                // 封面
                ['url' => $model_img,
                    'left' => 120,
                    'top' => 70,
                    'right' => 0,
                    'stream' => 0,
                    'bottom' => 0,
                    'width' => 300,
                    'height' => 300,
                    'opacity' => 100],
                // 二维码
                ['url' => "$code_object", //图片资源路径
                    'left' => 0,
                    'top' => 0,
                    'stream' => 0, //图片资源是否是字符串图像流
                    'right' => 0,
                    'bottom' => 0,
                    'width' => 120,
                    'height' => 120,
                    'opacity' => 100],
                // 图像
                ['url' => 'https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eofD96opK97RXwM179G9IJytIgqXod8jH9icFf6Cia6sJ0fxeILLMLf0dVviaF3SnibxtrFaVO3c8Ria2w/0',
                    'left' => 120,
                    'top' => 70,
                    'right' => 0,
                    'stream' => 0,
                    'bottom' => 0,
                    'width' => 55,
                    'height' => 55,
                    'opacity' => 100],
            ],
            'background' => 'http://file.wskeee.top/hellobabo/files/c5b8fd73b148ed02a49f96c2c900be6f.png',
        ];
        // 新建文件路径
        $filename = md5(time() . rand(100000, 999999)) . '.jpg';
        $filepath = Yii::getAlias('@apiend/web/upload/qrcode/' . $filename);
        // 创建海报
        PosterUtils::createPoster($config, $filepath);
        $oss_object = $savePath . $filename;
        // 传输到阿里云
        Aliyun::getOss()->multiuploadFile($oss_object, $filepath);

        return new Response(Response::CODE_COMMON_OK, null, ['poster_url' => Aliyun::absolutePath($oss_object)]);
    }

}
