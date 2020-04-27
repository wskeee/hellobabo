<?php

namespace apiend\modules\v1\actions\system;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\components\aliyuncs\Aliyun;
use common\components\qrcode\QRcode;
use common\models\goods\Goods;
use common\models\order\OrderGoods;
use common\models\User;
use common\utils\PosterUtils;
use jianyan\easywechat\Wechat;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;

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

        // 用户
        /* @var $user User */
        $user = \Yii::$app->user->identity;
        // 绘本封面或者用户封面
        $modelClass = $type == 1 ? Goods::class : OrderGoods::class;
        $model = $modelClass::findOne(['id' => $id]);

        $model_img = $type == 1 ? $model->cover_url : $model->user_cover_url;
        /* @var $goods_model Goods */
        $goods_model = $type == 1 ? $model : Goods::findOne(['id' => $model->goods_id]);
        $goods_poster = $goods_model->poster_url;

        $savePath = Yii::$app->params['webuploader']['savePath'];
        $link = Url::to(['http://shop.hellobabo.com/jump/wx-min/open','page' => 'show','id'=>$id,'type' =>$type,'rec_id' => $rec_id],'http');
        $code_filename = $type == 1 ? "qrcode_{$user->id}_{$goods_model->id}.png" : "qrcode_{$user->id}_{$model->id}.png";
        // 二维码路径
        $code_object = Yii::getAlias("@app/web/upload/qrcode/{$code_filename}");
        QRcode::png($link,$code_object);

        $config = [
            // 用户昵称
            'text' => [
                [
                    'text' => $user->nickname,
                    'left' => 110,
                    'bottom' => 55,
                    'fontPath' => Yii::getAlias('@apiend/web/css/msyhbd.ttf'), //字体文件
                    'fontSize' => 16, //字号
                    'fontColor' => '0,0,0', //字体颜色
                    'angle' => 0,],
                [
                    'text' => "向你分享了绘本",
                    'left' => 110,
                    'bottom' => 15,
                    'fontPath' => Yii::getAlias('@apiend/web/css/msyh.ttf'), //字体文件
                    'fontSize' => 16, //字号
                    'fontColor' => '192,192,192', //字体颜色
                    'angle' => 0,],
                [
                    'text' => "《 $goods_model->goods_name 》",
                    'left' => 250,
                    'bottom' => 15,
                    'fontPath' => Yii::getAlias('@apiend/web/css/msyhbd.ttf'), //字体文件
                    'fontSize' => 16, //字号
                    'fontColor' => '255,138,0', //字体颜色
                    'angle' => 0,],
            ],
            'image' => [
                // 绘本个性
                ['url' => $goods_poster,
                    'right' => 0,
                    'bottom' => 0,],
                // 二维码
                ['url' => $code_object, //图片资源路径
                    'bottom' => 115,
                    'right' => 20,
                    'stream' => 0, //图片资源是否是字符串图像流
                    'width' => 135,
                    'height' => 135,
                    'opacity' => 100],
                // 头像
                ['url' => $user->avatar,
                    'left' => 30,
                    'bottom' => 30,
                    'width' => 66,
                    'height' => 66,],
            ],
            // 封面
            'background' => $model_img,
        ];
        // 新建文件路径
        $filename = $type == 1 ? "share_{$user->id}_{$goods_model->id}.jpg" : "share_{$user->id}_{$model->id}.jpg";
        $filepath = Yii::getAlias('@apiend/web/upload/poster/');
        $this->mkdir($filepath);
        // 创建海报
        PosterUtils::createPoster($config, $filepath . $filename);
        $oss_object = $savePath . $filename;
        // 传输到阿里云
        Aliyun::getOss()->multiuploadFile($oss_object, $filepath . $filename);

        return new Response(Response::CODE_COMMON_OK, null, ['poster_url' => Aliyun::absolutePath($oss_object), 'goods' => $goods_model]);
    }

    /**
     * 创建目录
     * @param string $path
     */
    private function mkdir($path)
    {
        if (!file_exists($path)) {
            if (!(@mkdir($path, 0777, true))) {
                throw new HttpException(500, '创建目录失败');
            }
        }
    }

}
